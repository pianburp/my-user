<?php

namespace App\Services;

use App\Models\ProgramModel;
use App\Models\StudentDocumentModel;
use App\Models\StudentModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Database;
use DomainException;
use RuntimeException;
use Throwable;

class StudentService
{
    private const DOCUMENT_TYPES = ['ic', 'passport', 'transcript', 'certificate'];
    private const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png'];
    private const MAX_FILE_SIZE_BYTES = 2097152;

    private BaseConnection $db;
    private StudentModel $studentModel;
    private ProgramModel $programModel;
    private StudentDocumentModel $studentDocumentModel;

    public function __construct(
        ?StudentModel $studentModel = null,
        ?ProgramModel $programModel = null,
        ?StudentDocumentModel $studentDocumentModel = null,
        ?BaseConnection $db = null
    ) {
        $this->studentModel         = $studentModel ?? new StudentModel();
        $this->programModel         = $programModel ?? new ProgramModel();
        $this->studentDocumentModel = $studentDocumentModel ?? new StudentDocumentModel();
        $this->db = $db ?? Database::connect();
    }

    public function paginateStudents(int $perPage = 10, ?string $search = null): array
    {
        $search = trim((string) $search);

        $query = $this->studentModel
            ->select('students.*, programs.program_name, programs.program_code')
            ->join('programs', 'programs.id = students.program_id', 'left');

        if ($search !== '') {
            $keywords = preg_split('/\s+/', $search) ?: [];

            $query->groupStart()
                ->like('students.student_id', $search)
                ->orLike('students.email', $search)
                ->orLike('students.first_name', $search)
                ->orLike('students.last_name', $search)
                ->groupEnd();

            if (count($keywords) >= 2) {
                $query->groupStart()
                    ->like('students.first_name', $keywords[0])
                    ->like('students.last_name', $keywords[1])
                    ->groupEnd();
            }
        }

        $students = $query
            ->orderBy('students.created_at', 'DESC')
            ->paginate($perPage);

        return [
            'students' => $students,
            'pager'    => $this->studentModel->pager,
        ];
    }

    public function getProgramsForDropdown(): array
    {
        return $this->programModel->forDropdown();
    }

    public function getStudentById(int $id): ?array
    {
        $student = $this->studentModel->select('students.*, programs.program_name, programs.program_code')
            ->join('programs', 'programs.id = students.program_id', 'left')
            ->where('students.id', $id)
            ->first();

        if ($student === null) {
            return null;
        }

        $student['documents'] = $this->studentDocumentModel->findByStudent($id);

        return $student;
    }

    public function createStudent(array $payload, ?UploadedFile $documentFile = null, ?string $documentType = null): int
    {
        if ($this->emailExists($payload['email'] ?? null)) {
            throw new DomainException('Email is already registered.');
        }

        $payload['status'] = $payload['status'] ?? 'active';

        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->db->transBegin();

            try {
                $payload['student_id'] = $this->generateStudentId();
                $studentId = (int) $this->studentModel->insert($payload, true);

                if ($studentId <= 0) {
                    throw new RuntimeException('Failed to create student record.');
                }

                $this->storeDocumentIfProvided($studentId, $payload['student_id'], $documentFile, $documentType);

                $this->db->transCommit();

                return $studentId;
            } catch (Throwable $exception) {
                $this->db->transRollback();

                if ($this->isStudentIdDuplicateError($exception)) {
                    continue;
                }

                throw $exception;
            }
        }

        throw new RuntimeException('Unable to generate a unique student ID after multiple attempts.');
    }

    public function updateStudent(int $id, array $payload, ?UploadedFile $documentFile = null, ?string $documentType = null): void
    {
        if (! $this->studentModel->find($id)) {
            throw new DomainException('Student not found.');
        }

        if ($this->emailExists($payload['email'] ?? null, $id)) {
            throw new DomainException('Email is already registered.');
        }

        unset($payload['student_id']);

        $this->db->transBegin();

        try {
            $updated = $this->studentModel->update($id, $payload);

            if (! $updated) {
                throw new RuntimeException('Failed to update student record.');
            }

            $student = $this->studentModel->find($id);
            if (! is_array($student)) {
                throw new RuntimeException('Student could not be reloaded.');
            }

            $this->storeDocumentIfProvided($id, $student['student_id'], $documentFile, $documentType);

            $this->db->transCommit();
        } catch (Throwable $exception) {
            $this->db->transRollback();
            throw $exception;
        }
    }

    public function softDeleteStudent(int $id): void
    {
        if (! $this->studentModel->find($id)) {
            throw new DomainException('Student not found.');
        }

        if (! $this->studentModel->delete($id)) {
            throw new RuntimeException('Failed to archive student.');
        }
    }

    private function generateStudentId(): string
    {
        $year   = date('Y');
        $prefix = 'STU' . $year;

        $lastRecord = $this->studentModel->withDeleted()
            ->select('student_id')
            ->like('student_id', $prefix, 'after')
            ->orderBy('student_id', 'DESC')
            ->first();

        $nextSequence = 1;

        if (is_array($lastRecord) && isset($lastRecord['student_id'])) {
            $lastSequence = (int) substr($lastRecord['student_id'], -4);
            $nextSequence = $lastSequence + 1;
        }

        return $prefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    private function storeDocumentIfProvided(
        int $studentDbId,
        string $studentIdentifier,
        ?UploadedFile $documentFile,
        ?string $documentType
    ): void {
        if ($documentFile === null || $documentFile->getError() === UPLOAD_ERR_NO_FILE) {
            return;
        }

        $this->validateDocument($documentFile, $documentType);

        $destination = WRITEPATH . 'uploads/students/' . $studentIdentifier;
        if (! is_dir($destination) && ! mkdir($destination, 0775, true) && ! is_dir($destination)) {
            throw new RuntimeException('Unable to create upload directory.');
        }

        $newName = $documentFile->getRandomName();
        $documentFile->move($destination, $newName);

        if (! is_file($destination . DIRECTORY_SEPARATOR . $newName)) {
            throw new RuntimeException('Uploaded document was not stored correctly.');
        }

        $relativePath = 'uploads/students/' . $studentIdentifier . '/' . $newName;

        $saved = $this->studentDocumentModel->insert([
            'student_id'    => $studentDbId,
            'document_type' => $documentType,
            'file_path'     => $relativePath,
            'uploaded_at'   => date('Y-m-d H:i:s'),
        ]);

        if (! $saved) {
            throw new RuntimeException('Failed to save document metadata.');
        }
    }

    private function validateDocument(UploadedFile $file, ?string $documentType): void
    {
        if (! $file->isValid()) {
            throw new DomainException('Document upload is invalid.');
        }

        if ($documentType === null || $documentType === '') {
            throw new DomainException('Document type is required when uploading a file.');
        }

        if (! in_array($documentType, self::DOCUMENT_TYPES, true)) {
            throw new DomainException('Invalid document type selected.');
        }

        $extension = strtolower($file->getExtension() ?? '');
        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new DomainException('Only PDF, JPG, and PNG files are allowed.');
        }

        if ($file->getSize() > self::MAX_FILE_SIZE_BYTES) {
            throw new DomainException('Document size must not exceed 2MB.');
        }

        $mimeType = (new File($file->getTempName()))->getMimeType();
        $allowedMimes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
        ];

        if (! in_array($mimeType, $allowedMimes, true)) {
            throw new DomainException('Invalid document MIME type.');
        }
    }

    private function emailExists(?string $email, ?int $ignoreId = null): bool
    {
        if ($email === null || $email === '') {
            return false;
        }

        $builder = $this->studentModel->withDeleted()->where('email', $email);

        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->first() !== null;
    }

    private function isStudentIdDuplicateError(Throwable $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'duplicate') && str_contains($message, 'student_id');
    }
}

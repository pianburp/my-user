<?php

namespace App\Controllers;

use App\Services\StudentService;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;
use Throwable;

class StudentController extends BaseController
{
    private StudentService $studentService;

    public function __construct(?StudentService $studentService = null)
    {
        $this->studentService = $studentService ?? service('studentService');
    }

    public function index(): string
    {
        $search  = trim((string) $this->request->getGet('q'));
        $results = $this->studentService->paginateStudents(10, $search);

        return view('user/students/index', [
            'user'     => auth()->user(),
            'students' => $results['students'],
            'pager'    => $results['pager'],
            'search'   => $search,
        ]);
    }

    public function create(): string
    {
        return view('user/students/create', [
            'user'     => auth()->user(),
            'programs' => $this->studentService->getProgramsForDropdown(),
        ]);
    }

    public function store(): RedirectResponse
    {
        if (! $this->validate($this->studentValidationRules())) {
            return redirect()->to('/user/students/create')
                ->withInput()
                ->with('student_errors', $this->validator->getErrors());
        }

        $documentType = $this->request->getPost('document_type');
        $documentFile = $this->request->getFile('document_file');

        if ($this->hasDocumentTypeWithoutFile($documentType, $documentFile)) {
            return redirect()->to('/user/students/create')
                ->withInput()
                ->with('student_errors', ['document_file' => 'Document file is required when a document type is selected.']);
        }

        if ($this->hasFileWithoutDocumentType($documentType, $documentFile)) {
            return redirect()->to('/user/students/create')
                ->withInput()
                ->with('student_errors', ['document_type' => 'Document type is required when uploading a file.']);
        }

        try {
            $this->studentService->createStudent($this->studentPayload(), $documentFile, $documentType ?: null);

            return redirect()->to('/user/students')->with('student_success', 'Student created successfully.');
        } catch (DomainException $exception) {
            return redirect()->to('/user/students/create')
                ->withInput()
                ->with('student_errors', ['general' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            log_message('error', 'Student create failed: {message}', ['message' => $exception->getMessage()]);

            return redirect()->to('/user/students/create')
                ->withInput()
                ->with('student_errors', ['general' => 'Unable to create student right now. Please try again.']);
        }
    }

    public function edit(int $id): string|RedirectResponse
    {
        $student = $this->studentService->getStudentById($id);

        if ($student === null) {
            return redirect()->to('/user/students')->with('student_errors', ['general' => 'Student not found.']);
        }

        return view('user/students/edit', [
            'user'     => auth()->user(),
            'student'  => $student,
            'programs' => $this->studentService->getProgramsForDropdown(),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        if (! $this->validate($this->studentValidationRules($id))) {
            return redirect()->to('/user/students/' . $id . '/edit')
                ->withInput()
                ->with('student_errors', $this->validator->getErrors());
        }

        $documentType = $this->request->getPost('document_type');
        $documentFile = $this->request->getFile('document_file');

        if ($this->hasDocumentTypeWithoutFile($documentType, $documentFile)) {
            return redirect()->to('/user/students/' . $id . '/edit')
                ->withInput()
                ->with('student_errors', ['document_file' => 'Document file is required when a document type is selected.']);
        }

        if ($this->hasFileWithoutDocumentType($documentType, $documentFile)) {
            return redirect()->to('/user/students/' . $id . '/edit')
                ->withInput()
                ->with('student_errors', ['document_type' => 'Document type is required when uploading a file.']);
        }

        try {
            $this->studentService->updateStudent($id, $this->studentPayload(), $documentFile, $documentType ?: null);

            return redirect()->to('/user/students')->with('student_success', 'Student updated successfully.');
        } catch (DomainException $exception) {
            return redirect()->to('/user/students/' . $id . '/edit')
                ->withInput()
                ->with('student_errors', ['general' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            log_message('error', 'Student update failed: {message}', ['message' => $exception->getMessage()]);

            return redirect()->to('/user/students/' . $id . '/edit')
                ->withInput()
                ->with('student_errors', ['general' => 'Unable to update student right now. Please try again.']);
        }
    }

    public function delete(int $id): RedirectResponse
    {
        try {
            $this->studentService->softDeleteStudent($id);

            return redirect()->to('/user/students')->with('student_success', 'Student archived successfully.');
        } catch (DomainException $exception) {
            return redirect()->to('/user/students')->with('student_errors', ['general' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            log_message('error', 'Student delete failed: {message}', ['message' => $exception->getMessage()]);

            return redirect()->to('/user/students')->with('student_errors', ['general' => 'Unable to archive student right now.']);
        }
    }

    private function studentPayload(): array
    {
        return [
            'first_name'      => trim((string) $this->request->getPost('first_name')),
            'last_name'       => trim((string) $this->request->getPost('last_name')),
            'gender'          => $this->request->getPost('gender'),
            'date_of_birth'   => $this->request->getPost('date_of_birth'),
            'email'           => strtolower(trim((string) $this->request->getPost('email'))),
            'phone_number'    => trim((string) $this->request->getPost('phone_number')),
            'address_line1'   => trim((string) $this->request->getPost('address_line1')),
            'address_line2'   => trim((string) $this->request->getPost('address_line2')),
            'city'            => trim((string) $this->request->getPost('city')),
            'state'           => trim((string) $this->request->getPost('state')),
            'postcode'        => trim((string) $this->request->getPost('postcode')),
            'country'         => trim((string) $this->request->getPost('country')),
            'program_id'      => (int) $this->request->getPost('program_id'),
            'intake_session'  => $this->request->getPost('intake_session'),
            'enrollment_date' => $this->request->getPost('enrollment_date'),
            'status'          => $this->request->getPost('status') ?: 'active',
        ];
    }

    private function studentValidationRules(?int $studentId = null): array
    {
        $emailRule = 'required|valid_email|max_length[150]|is_unique[students.email]';

        if ($studentId !== null) {
            $emailRule = 'required|valid_email|max_length[150]|is_unique[students.email,id,' . $studentId . ']';
        }

        return [
            'first_name'      => 'required|min_length[2]|max_length[100]',
            'last_name'       => 'required|min_length[2]|max_length[100]',
            'gender'          => 'required|in_list[male,female,other]',
            'date_of_birth'   => 'required|valid_date[Y-m-d]',
            'email'           => $emailRule,
            'phone_number'    => 'required|max_length[30]',
            'address_line1'   => 'required|max_length[255]',
            'address_line2'   => 'permit_empty|max_length[255]',
            'city'            => 'required|max_length[100]',
            'state'           => 'required|max_length[100]',
            'postcode'        => 'required|max_length[20]',
            'country'         => 'required|max_length[100]',
            'program_id'      => 'required|is_natural_no_zero',
            'intake_session'  => 'required|regex_match[/^\d{4}-(0[1-9]|1[0-2])$/]',
            'enrollment_date' => 'required|valid_date[Y-m-d]',
            'status'          => 'permit_empty|in_list[active,inactive,graduated,suspended]',
            'document_type'   => 'permit_empty|in_list[ic,passport,transcript,certificate]',
            'document_file'   => 'permit_empty|max_size[document_file,2048]|ext_in[document_file,pdf,jpg,jpeg,png]|mime_in[document_file,application/pdf,image/jpeg,image/png]',
        ];
    }

    private function hasDocumentTypeWithoutFile(?string $documentType, $documentFile): bool
    {
        return $documentType !== null
            && $documentType !== ''
            && ($documentFile === null || $documentFile->getError() === UPLOAD_ERR_NO_FILE);
    }

    private function hasFileWithoutDocumentType(?string $documentType, $documentFile): bool
    {
        return $documentFile !== null
            && $documentFile->getError() !== UPLOAD_ERR_NO_FILE
            && ($documentType === null || $documentType === '');
    }
}

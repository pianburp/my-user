<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone_number',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postcode',
        'country',
        'program_id',
        'intake_session',
        'enrollment_date',
        'status',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $dateFormat     = 'datetime';
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';

    public function paginateWithProgram(int $perPage = 10, ?string $search = null): array
    {
        $search = trim((string) $search);

        $query = $this->select('students.*, programs.program_name, programs.program_code')
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
            'pager'    => $this->pager,
        ];
    }

    public function findWithProgramById(int $id): ?array
    {
        return $this->select('students.*, programs.program_name, programs.program_code')
            ->join('programs', 'programs.id = students.program_id', 'left')
            ->where('students.id', $id)
            ->first();
    }

    public function emailExists(?string $email, ?int $ignoreId = null): bool
    {
        if ($email === null || $email === '') {
            return false;
        }

        $builder = $this->withDeleted()->where('email', $email);

        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->first() !== null;
    }

    public function generateNextStudentId(string $prefix = 'STU'): string
    {
        $year         = date('Y');
        $fullPrefix   = $prefix . $year;
        $nextSequence = 1;

        $lastRecord = $this->withDeleted()
            ->select('student_id')
            ->like('student_id', $fullPrefix, 'after')
            ->orderBy('student_id', 'DESC')
            ->first();

        if (is_array($lastRecord) && isset($lastRecord['student_id'])) {
            $lastSequence = (int) substr($lastRecord['student_id'], -4);
            $nextSequence = $lastSequence + 1;
        }

        return $fullPrefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    public function baseListQuery(?string $search = null): BaseBuilder
    {
        $builder = $this->builder()
            ->select('students.*, programs.program_name, programs.program_code')
            ->join('programs', 'programs.id = students.program_id', 'left')
            ->where('students.deleted_at', null)
            ->orderBy('students.created_at', 'DESC');

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('students.student_id', $search)
                ->orLike('students.email', $search)
                ->orLike('students.first_name', $search)
                ->orLike('students.last_name', $search)
                ->groupEnd();

            $keywords = preg_split('/\s+/', $search) ?: [];
            if (count($keywords) >= 2) {
                $builder->groupStart()
                    ->like('students.first_name', $keywords[0])
                    ->like('students.last_name', $keywords[1])
                    ->groupEnd();
            }
        }

        return $builder;
    }

    public function getForEdit(int $id): ?array
    {
        return $this->where('id', $id)->first();
    }
}

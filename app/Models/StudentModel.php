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

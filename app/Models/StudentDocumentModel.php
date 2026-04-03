<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentDocumentModel extends Model
{
    protected $table            = 'student_documents';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'document_type',
        'file_path',
        'uploaded_at',
    ];

    protected bool $allowEmptyInserts = false;

    public function findByStudent(int $studentId): array
    {
        return $this->where('student_id', $studentId)->orderBy('uploaded_at', 'DESC')->findAll();
    }
}

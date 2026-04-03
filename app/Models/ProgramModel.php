<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table            = 'programs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'program_name',
        'program_code',
        'duration_years',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function forDropdown(): array
    {
        return $this->orderBy('program_name', 'ASC')->findAll();
    }
}

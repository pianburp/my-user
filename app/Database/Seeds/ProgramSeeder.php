<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'program_name'   => 'Bachelor of Computer Science',
                'program_code'   => 'BCS',
                'duration_years' => 4,
            ],
            [
                'program_name'   => 'Diploma in Information Technology',
                'program_code'   => 'DIT',
                'duration_years' => 3,
            ],
            [
                'program_name'   => 'Bachelor of Business Administration',
                'program_code'   => 'BBA',
                'duration_years' => 3,
            ],
        ];

        $builder = $this->db->table('programs');

        foreach ($programs as $program) {
            $builder->insert($program);
        }
    }
}

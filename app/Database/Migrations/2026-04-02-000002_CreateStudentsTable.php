<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'gender' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'phone_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'address_line1' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'address_line2' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'postcode' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'program_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'intake_session' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
            ],
            'enrollment_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('student_id');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('program_id');
        $this->forge->addForeignKey('program_id', 'programs', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('students', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('students', true);
    }
}

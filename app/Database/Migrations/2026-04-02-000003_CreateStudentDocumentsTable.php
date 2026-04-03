<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentDocumentsTable extends Migration
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
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'document_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'uploaded_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('student_id');
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('student_documents', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('student_documents', true);
    }
}

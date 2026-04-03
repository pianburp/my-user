<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProgramsTable extends Migration
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
            'program_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'program_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'duration_years' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('program_code');
        $this->forge->createTable('programs', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('programs', true);
    }
}

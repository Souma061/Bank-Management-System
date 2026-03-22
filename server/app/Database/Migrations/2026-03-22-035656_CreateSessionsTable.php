<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
            'timestamp' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0,
            ],
            'data' => ['type' => 'BLOB'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('ci_session');
    }

    public function down()
    {
        $this->forge->dropTable('ci_session');
    }
}

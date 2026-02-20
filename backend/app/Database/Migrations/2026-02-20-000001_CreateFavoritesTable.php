<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFavoritesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'movie_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // Prevent duplicate favorites
        $this->forge->addUniqueKey(['user_id', 'movie_id']);
        $this->forge->addKey('user_id');
        $this->forge->addKey('movie_id');
        $this->forge->createTable('favorites');
    }

    public function down()
    {
        $this->forge->dropTable('favorites');
    }
}

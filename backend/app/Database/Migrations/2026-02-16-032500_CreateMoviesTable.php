<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'genre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'year' => [
                'type'       => 'INT',
                'constraint' => 4,
            ],
            'duration' => [
                'type'       => 'INT',
                'constraint' => 5,
                'comment'    => 'Duration in minutes',
            ],
            'rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,1',
                'default'    => 0,
            ],
            'poster_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'backdrop_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'trailer_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'video_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'director' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'cast' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_trending' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
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
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('movies');
    }

    public function down()
    {
        $this->forge->dropTable('movies');
    }
}

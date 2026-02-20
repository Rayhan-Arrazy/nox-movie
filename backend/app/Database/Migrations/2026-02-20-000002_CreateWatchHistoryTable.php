<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWatchHistoryTable extends Migration
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
            // How many seconds into the movie the user stopped
            'progress_seconds' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            // Whether the user finished the full movie
            'completed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            // Last time this entry was updated (i.e. last watched)
            'watched_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        // One row per user+movie (upsert on re-watch)
        $this->forge->addUniqueKey(['user_id', 'movie_id']);
        $this->forge->addKey('user_id');
        $this->forge->addKey('movie_id');
        $this->forge->createTable('watch_history');
    }

    public function down()
    {
        $this->forge->dropTable('watch_history');
    }
}

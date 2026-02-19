<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds tmdb_id column to the movies table.
 * This allows tracking which movies were imported from TMDB
 * and prevents duplicate imports.
 */
class AddTmdbIdToMovies extends Migration
{
    public function up()
    {
        $this->forge->addColumn('movies', [
            'tmdb_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => null,
                'after' => 'id',
            ],
        ]);

        // Add index for fast duplicate checks
        $this->db->query('ALTER TABLE movies ADD INDEX idx_tmdb_id (tmdb_id)');
    }

    public function down()
    {
        $this->forge->dropColumn('movies', 'tmdb_id');
    }
}

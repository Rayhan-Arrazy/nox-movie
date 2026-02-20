<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        // ── categories ────────────────────────────────────────────────
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Font Awesome class or emoji',
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Hex color for UI badge',
                'default' => '#c8ff00',
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 4,
                'default' => 0,
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
        $this->forge->createTable('categories');

        // ── movie_categories (pivot) ──────────────────────────────────
        $this->forge->addField([
            'movie_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addPrimaryKey(['movie_id', 'category_id']);
        $this->forge->createTable('movie_categories');
    }

    public function down()
    {
        $this->forge->dropTable('movie_categories');
        $this->forge->dropTable('categories');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * AddForeignKeyConstraints
 *
 * Adds all FK relationships so phpMyAdmin Designer shows arrows:
 *
 *  favorites.user_id      → users.id
 *  favorites.movie_id     → movies.id
 *
 *  watch_history.user_id  → users.id
 *  watch_history.movie_id → movies.id
 *
 *  movie_categories.movie_id    → movies.id
 *  movie_categories.category_id → categories.id
 *
 * All tables are converted to InnoDB first (InnoDB is required for FK enforcement).
 */
class AddForeignKeyConstraints extends Migration
{
    // All tables that must be InnoDB
    private array $tables = [
        'users',
        'movies',
        'favorites',
        'watch_history',
        'categories',
        'movie_categories',
    ];

    public function up(): void
    {
        // ── 1. Convert every table to InnoDB ──────────────────────────
        foreach ($this->tables as $tbl) {
            $this->db->query("ALTER TABLE `{$tbl}` ENGINE = InnoDB");
        }

        // ── 2. favorites ──────────────────────────────────────────────
        // favorites.user_id → users.id  (CASCADE delete: remove favs when user deleted)
        $this->db->query("
            ALTER TABLE `favorites`
                ADD CONSTRAINT `fk_favorites_user`
                    FOREIGN KEY (`user_id`)
                    REFERENCES `users` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
        ");

        // favorites.movie_id → movies.id  (CASCADE delete: remove favs when movie deleted)
        $this->db->query("
            ALTER TABLE `favorites`
                ADD CONSTRAINT `fk_favorites_movie`
                    FOREIGN KEY (`movie_id`)
                    REFERENCES `movies` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
        ");

        // ── 3. watch_history ──────────────────────────────────────────
        // watch_history.user_id → users.id
        $this->db->query("
            ALTER TABLE `watch_history`
                ADD CONSTRAINT `fk_watch_history_user`
                    FOREIGN KEY (`user_id`)
                    REFERENCES `users` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
        ");

        // watch_history.movie_id → movies.id
        $this->db->query("
            ALTER TABLE `watch_history`
                ADD CONSTRAINT `fk_watch_history_movie`
                    FOREIGN KEY (`movie_id`)
                    REFERENCES `movies` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
        ");

        // ── 4. movie_categories (pivot) ───────────────────────────────
        // movie_categories.movie_id → movies.id
        $this->db->query("
            ALTER TABLE `movie_categories`
                ADD CONSTRAINT `fk_movie_categories_movie`
                    FOREIGN KEY (`movie_id`)
                    REFERENCES `movies` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
        ");

        // movie_categories.category_id → categories.id
        $this->db->query("
            ALTER TABLE `movie_categories`
                ADD CONSTRAINT `fk_movie_categories_category`
                    FOREIGN KEY (`category_id`)
                    REFERENCES `categories` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
        ");
    }

    public function down(): void
    {
        // Drop FK constraints in reverse order (children first)
        $this->db->query("ALTER TABLE `movie_categories` DROP FOREIGN KEY `fk_movie_categories_category`");
        $this->db->query("ALTER TABLE `movie_categories` DROP FOREIGN KEY `fk_movie_categories_movie`");
        $this->db->query("ALTER TABLE `watch_history`    DROP FOREIGN KEY `fk_watch_history_movie`");
        $this->db->query("ALTER TABLE `watch_history`    DROP FOREIGN KEY `fk_watch_history_user`");
        $this->db->query("ALTER TABLE `favorites`        DROP FOREIGN KEY `fk_favorites_movie`");
        $this->db->query("ALTER TABLE `favorites`        DROP FOREIGN KEY `fk_favorites_user`");
    }
}

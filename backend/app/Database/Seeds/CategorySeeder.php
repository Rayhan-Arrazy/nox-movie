<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $categories = [
            [
                'name' => 'New Releases',
                'slug' => 'new-releases',
                'description' => 'The latest movies added to CineVerse.',
                'icon' => '🆕',
                'color' => '#c8ff00',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Award Winners',
                'slug' => 'award-winners',
                'description' => 'Oscar, BAFTA, and Golden Globe winning films.',
                'icon' => '🏆',
                'color' => '#ffd700',
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Critically Acclaimed',
                'slug' => 'critically-acclaimed',
                'description' => 'Movies with a rating of 8.0 or above.',
                'icon' => '⭐',
                'color' => '#ff9f43',
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Blockbusters',
                'slug' => 'blockbusters',
                'description' => 'The biggest box office hits of all time.',
                'icon' => '💥',
                'color' => '#ff6b6b',
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Science Fiction',
                'slug' => 'science-fiction',
                'description' => 'Explore future worlds, space, and technology.',
                'icon' => '🚀',
                'color' => '#48dbfb',
                'sort_order' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thriller & Horror',
                'slug' => 'thriller-horror',
                'description' => 'Edge-of-your-seat suspense and terror.',
                'icon' => '😱',
                'color' => '#6c5ce7',
                'sort_order' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Classic Cinema',
                'slug' => 'classic-cinema',
                'description' => 'Timeless masterpieces from before the year 2000.',
                'icon' => '🎞️',
                'color' => '#a29bfe',
                'sort_order' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Family & Animation',
                'slug' => 'family-animation',
                'description' => 'Fun for the whole family.',
                'icon' => '🎨',
                'color' => '#55efc4',
                'sort_order' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('categories')->insertBatch($categories);
        echo "Seeded " . count($categories) . " categories.\n";

        // ── Auto-assign movies to categories ─────────────────────────
        $db = \Config\Database::connect();

        // Award Winners → movies with rating >= 8.5
        $awardId = $db->table('categories')->where('slug', 'award-winners')->get()->getRowArray()['id'];
        $awardMovies = $db->table('movies')->select('id')->where('rating >=', 8.5)->get()->getResultArray();
        foreach ($awardMovies as $m) {
            $db->query('INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?,?)', [$m['id'], $awardId]);
        }

        // Critically Acclaimed → movies with rating >= 8.0
        $acclaimedId = $db->table('categories')->where('slug', 'critically-acclaimed')->get()->getRowArray()['id'];
        $acclaimedMovies = $db->table('movies')->select('id')->where('rating >=', 8.0)->get()->getResultArray();
        foreach ($acclaimedMovies as $m) {
            $db->query('INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?,?)', [$m['id'], $acclaimedId]);
        }

        // Sci-Fi category
        $scifiId = $db->table('categories')->where('slug', 'science-fiction')->get()->getRowArray()['id'];
        $scifiMovies = $db->table('movies')->select('id')->like('genre', 'Sci-Fi')->get()->getResultArray();
        foreach ($scifiMovies as $m) {
            $db->query('INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?,?)', [$m['id'], $scifiId]);
        }

        // Thriller & Horror
        $thrillerId = $db->table('categories')->where('slug', 'thriller-horror')->get()->getRowArray()['id'];
        $thrillerMovies = $db->table('movies')
            ->select('id')
            ->groupStart()
            ->like('genre', 'Thriller')
            ->orLike('genre', 'Horror')
            ->groupEnd()
            ->get()->getResultArray();
        foreach ($thrillerMovies as $m) {
            $db->query('INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?,?)', [$m['id'], $thrillerId]);
        }

        // Classic Cinema → year < 2000
        $classicId = $db->table('categories')->where('slug', 'classic-cinema')->get()->getRowArray()['id'];
        $classicMovies = $db->table('movies')->select('id')->where('year <', 2000)->get()->getResultArray();
        foreach ($classicMovies as $m) {
            $db->query('INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?,?)', [$m['id'], $classicId]);
        }

        // Family & Animation
        $familyId = $db->table('categories')->where('slug', 'family-animation')->get()->getRowArray()['id'];
        $familyMovies = $db->table('movies')->select('id')->like('genre', 'Animation')->get()->getResultArray();
        foreach ($familyMovies as $m) {
            $db->query('INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?,?)', [$m['id'], $familyId]);
        }

        // Blockbusters → is_trending or is_featured
        $blockId = $db->table('categories')->where('slug', 'blockbusters')->get()->getRowArray()['id'];
        $blockMovies = $db->table('movies')
            ->select('id')
            ->groupStart()
            ->where('is_featured', 1)
            ->orWhere('is_trending', 1)
            ->groupEnd()
            ->get()->getResultArray();
        foreach ($blockMovies as $m) {
            $db->query('INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?,?)', [$m['id'], $blockId]);
        }

        // New Releases → year >= 2022
        $newId = $db->table('categories')->where('slug', 'new-releases')->get()->getRowArray()['id'];
        $newMovies = $db->table('movies')->select('id')->where('year >=', 2022)->get()->getResultArray();
        foreach ($newMovies as $m) {
            $db->query('INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?,?)', [$m['id'], $newId]);
        }

        echo "Category→movie assignments complete.\n";
    }
}

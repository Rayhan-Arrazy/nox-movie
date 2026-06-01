<?php

namespace App\Services;

/**
 * TmdbService
 *
 * Wraps The Movie Database (TMDB) API v3.
 * Base URL : https://api.themoviedb.org/3
 * Auth     : Bearer token (read-access token) via Authorization header
 * Images   : https://image.tmdb.org/t/p/{size}{file_path}
 *
 * Poster sizes  : w92 | w154 | w185 | w342 | w500 | w780 | original
 * Backdrop sizes: w300 | w780 | w1280 | original
 */
class TmdbService
{
    // private string $baseUrl  = 'https://api.themoviedb.org/3';
    // private string $imageUrl = 'https://image.tmdb.org/t/p';
    private string $token;

    // Genre id → name map (TMDB genre IDs for movies)
    private array $genreMap = [
        28 => 'Action',
        12 => 'Adventure',
        16 => 'Animation',
        35 => 'Comedy',
        80 => 'Crime',
        99 => 'Documentary',
        18 => 'Drama',
        10751 => 'Family',
        14 => 'Fantasy',
        36 => 'History',
        27 => 'Horror',
        10402 => 'Music',
        9648 => 'Mystery',
        10749 => 'Romance',
        878 => 'Sci-Fi',
        10770 => 'TV Movie',
        53 => 'Thriller',
        10752 => 'War',
        37 => 'Western',
    ];

    public function __construct()
    {
        $this->token = env('TMDB_API_TOKEN', '');
    }

    // ---------------------------------------------------------------
    // Core HTTP helper
    // ---------------------------------------------------------------

    /**
     * Make a GET request to TMDB API.
     *
     * @param  string $endpoint  e.g. '/movie/popular'
     * @param  array  $params    Query parameters
     * @return array|null        Decoded JSON or null on failure
     */
    public function get(string $endpoint, array $params = []): ?array
    {
        if (empty($this->token)) {
            return ['error' => 'TMDB_API_TOKEN not configured in .env'];
        }

        $url = $this->baseUrl . $endpoint;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false, // dev only
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            return ['error' => "TMDB request failed (HTTP $httpCode)", 'url' => $url];
        }

        return json_decode($response, true);
    }

    // ---------------------------------------------------------------
    // Image URL builders
    // ---------------------------------------------------------------

    public function posterUrl(?string $path, string $size = 'w500'): string
    {
        if (empty($path)) {
            return 'https://placehold.co/500x750/1a1a2e/818cf8?text=No+Poster';
        }
        return "{$this->imageUrl}/{$size}{$path}";
    }

    public function backdropUrl(?string $path, string $size = 'w1280'): string
    {
        if (empty($path)) {
            return 'https://placehold.co/1280x720/1a1a2e/818cf8?text=No+Backdrop';
        }
        return "{$this->imageUrl}/{$size}{$path}";
    }

    // ---------------------------------------------------------------
    // Genre helpers
    // ---------------------------------------------------------------

    public function genreName(int $id): string
    {
        return $this->genreMap[$id] ?? 'Other';
    }

    public function genreNamesFromIds(array $ids): string
    {
        $names = array_map(fn($id) => $this->genreName($id), array_slice($ids, 0, 2));
        return implode(', ', $names);
    }

    // ---------------------------------------------------------------
    // Map TMDB movie → CineVerse movie schema
    // ---------------------------------------------------------------

    public function mapMovie(array $m): array
    {
        $title = $m['title'] ?? $m['original_title'] ?? 'Unknown';
        $year = isset($m['release_date']) ? (int) substr($m['release_date'], 0, 4) : date('Y');
        $genre = !empty($m['genre_ids'])
            ? $this->genreNamesFromIds($m['genre_ids'])
            : ($m['genres'][0]['name'] ?? 'Other');

        return [
            'tmdb_id' => $m['id'],
            'title' => $title,
            'slug' => url_title($title . '-' . $m['id'], '-', true),
            'description' => $m['overview'] ?? '',
            'genre' => $genre,
            'year' => $year,
            'duration' => $m['runtime'] ?? 120,
            'rating' => round($m['vote_average'] ?? 0, 1),
            'poster_url' => $this->posterUrl($m['poster_path'] ?? null),
            'backdrop_url' => $this->backdropUrl($m['backdrop_path'] ?? null),
            'trailer_url' => '',
            'video_url' => '',
            'director' => '',
            'cast' => '',
            'is_featured' => ($m['vote_average'] ?? 0) >= 7.5 ? 1 : 0,
            'is_trending' => ($m['popularity'] ?? 0) >= 100 ? 1 : 0,
        ];
    }

    // ---------------------------------------------------------------
    // Movie endpoints
    // ---------------------------------------------------------------

    /** GET /movie/popular */
    public function getPopular(int $page = 1): ?array
    {
        return $this->get('/movie/popular', ['page' => $page, 'language' => 'en-US']);
    }

    /** GET /movie/top_rated */
    public function getTopRated(int $page = 1): ?array
    {
        return $this->get('/movie/top_rated', ['page' => $page, 'language' => 'en-US']);
    }

    /** GET /movie/now_playing */
    public function getNowPlaying(int $page = 1): ?array
    {
        return $this->get('/movie/now_playing', ['page' => $page, 'language' => 'en-US']);
    }

    /** GET /movie/upcoming */
    public function getUpcoming(int $page = 1): ?array
    {
        return $this->get('/movie/upcoming', ['page' => $page, 'language' => 'en-US']);
    }

    /** GET /trending/movie/{time_window} */
    public function getTrending(string $timeWindow = 'week'): ?array
    {
        return $this->get("/trending/movie/{$timeWindow}", ['language' => 'en-US']);
    }

    /** GET /movie/{movie_id} */
    public function getMovieDetail(int $id): ?array
    {
        return $this->get("/movie/{$id}", [
            'language' => 'en-US',
            'append_to_response' => 'credits,videos',
        ]);
    }

    /** GET /search/movie */
    public function searchMovies(string $query, int $page = 1): ?array
    {
        return $this->get('/search/movie', [
            'query' => $query,
            'page' => $page,
            'language' => 'en-US',
        ]);
    }

    /** GET /discover/movie */
    public function discoverMovies(array $params = []): ?array
    {
        $defaults = ['language' => 'en-US', 'sort_by' => 'popularity.desc', 'page' => 1];
        return $this->get('/discover/movie', array_merge($defaults, $params));
    }

    /** GET /genre/movie/list */
    public function getGenres(): ?array
    {
        return $this->get('/genre/movie/list', ['language' => 'en-US']);
    }

    /** GET /movie/{movie_id}/similar */
    public function getSimilar(int $id): ?array
    {
        return $this->get("/movie/{$id}/similar", ['language' => 'en-US']);
    }

    /** GET /movie/{movie_id}/recommendations */
    public function getRecommendations(int $id): ?array
    {
        return $this->get("/movie/{$id}/recommendations", ['language' => 'en-US']);
    }

    /** GET /movie/{movie_id}/credits */
    public function getCredits(int $id): ?array
    {
        return $this->get("/movie/{$id}/credits");
    }

    /** GET /movie/{movie_id}/videos */
    public function getVideos(int $id): ?array
    {
        return $this->get("/movie/{$id}/videos", ['language' => 'en-US']);
    }

    /** GET /person/{person_id} */
    public function getPerson(int $id): ?array
    {
        return $this->get("/person/{$id}", ['language' => 'en-US']);
    }

    /** GET /configuration */
    public function getConfiguration(): ?array
    {
        return $this->get('/configuration');
    }
}

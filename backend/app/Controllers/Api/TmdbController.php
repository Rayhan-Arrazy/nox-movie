<?php

namespace App\Controllers\Api;

use App\Services\TmdbService;
use CodeIgniter\Controller;

/**
 * TmdbController
 *
 * Proxy controller that forwards requests to TMDB API and returns
 * formatted JSON responses. All endpoints are prefixed with /api/tmdb/
 *
 * Endpoints:
 *   GET /api/tmdb/popular              → Popular movies
 *   GET /api/tmdb/top-rated            → Top rated movies
 *   GET /api/tmdb/now-playing          → Now playing in cinemas
 *   GET /api/tmdb/upcoming             → Upcoming releases
 *   GET /api/tmdb/trending             → Trending this week
 *   GET /api/tmdb/trending/day         → Trending today
 *   GET /api/tmdb/search?q=query       → Search movies
 *   GET /api/tmdb/discover             → Discover with filters
 *   GET /api/tmdb/genres               → Genre list
 *   GET /api/tmdb/movie/{id}           → Movie detail
 *   GET /api/tmdb/movie/{id}/similar   → Similar movies
 *   GET /api/tmdb/movie/{id}/recommendations → Recommendations
 *   GET /api/tmdb/movie/{id}/credits   → Cast & crew
 *   GET /api/tmdb/movie/{id}/videos    → Trailers & clips
 *   GET /api/tmdb/person/{id}          → Person detail
 *   GET /api/tmdb/configuration        → API configuration
 *   POST /api/tmdb/import              → Import TMDB movies into local DB
 */
class TmdbController extends Controller
{
    protected $format = 'json';
    protected TmdbService $tmdb;
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->tmdb = new TmdbService();
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
    }

    // ---------------------------------------------------------------
    // Helper: wrap response
    // ---------------------------------------------------------------

    private function success(mixed $data, array $meta = []): \CodeIgniter\HTTP\Response
    {
        $payload = ['status' => 'success', 'data' => $data];
        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }
        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/json')
            ->setBody(json_encode($payload));
    }

    private function tmdbError(array $result): \CodeIgniter\HTTP\Response
    {
        return $this->response
            ->setStatusCode(502)
            ->setContentType('application/json')
            ->setBody(json_encode([
                'status' => 'error',
                'message' => $result['error'] ?? 'TMDB request failed',
            ]));
    }

    private function jsonError(string $message, int $code = 400): \CodeIgniter\HTTP\Response
    {
        return $this->response
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setBody(json_encode(['status' => 'error', 'message' => $message]));
    }

    private function mapResults(array $raw): array
    {
        return array_map(fn($m) => $this->tmdb->mapMovie($m), $raw['results'] ?? []);
    }

    private function buildMeta(array $raw): array
    {
        return [
            'page' => $raw['page'] ?? 1,
            'total_pages' => $raw['total_pages'] ?? 1,
            'total_results' => $raw['total_results'] ?? 0,
        ];
    }

    // ---------------------------------------------------------------
    // Movie list endpoints
    // ---------------------------------------------------------------

    /** GET /api/tmdb/popular */
    public function popular(): \CodeIgniter\HTTP\Response
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $raw = $this->tmdb->getPopular($page);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/top-rated */
    public function topRated(): \CodeIgniter\HTTP\Response
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $raw = $this->tmdb->getTopRated($page);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/now-playing */
    public function nowPlaying(): \CodeIgniter\HTTP\Response
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $raw = $this->tmdb->getNowPlaying($page);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/upcoming */
    public function upcoming(): \CodeIgniter\HTTP\Response
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $raw = $this->tmdb->getUpcoming($page);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/trending  (default: week) */
    public function trending(): \CodeIgniter\HTTP\Response
    {
        $window = $this->request->getGet('window') ?? 'week'; // 'day' or 'week'
        $raw = $this->tmdb->getTrending($window);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/search?q=... */
    public function search(): \CodeIgniter\HTTP\Response
    {
        $query = $this->request->getGet('q') ?? $this->request->getGet('query') ?? '';
        if (empty($query)) {
            return $this->jsonError('Query parameter "q" is required', 400);
        }
        $page = (int) ($this->request->getGet('page') ?? 1);
        $raw = $this->tmdb->searchMovies($query, $page);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/discover */
    public function discover(): \CodeIgniter\HTTP\Response
    {
        $params = [];
        foreach (['genre_id', 'year', 'sort_by', 'page', 'vote_average.gte', 'with_genres'] as $key) {
            $val = $this->request->getGet($key);
            if ($val !== null)
                $params[$key] = $val;
        }
        $raw = $this->tmdb->discoverMovies($params);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/genres */
    public function genres(): \CodeIgniter\HTTP\Response
    {
        $raw = $this->tmdb->getGenres();
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($raw['genres'] ?? []);
    }

    /** GET /api/tmdb/configuration */
    public function configuration(): \CodeIgniter\HTTP\Response
    {
        $raw = $this->tmdb->getConfiguration();
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($raw);
    }

    // ---------------------------------------------------------------
    // Single movie endpoints
    // ---------------------------------------------------------------

    /** GET /api/tmdb/movie/{id} */
    public function movie(int $id): \CodeIgniter\HTTP\Response
    {
        $raw = $this->tmdb->getMovieDetail($id);
        if (isset($raw['error']))
            return $this->tmdbError($raw);

        // Enrich mapped movie with credits & videos if appended
        $mapped = $this->tmdb->mapMovie($raw);

        // Extract director from credits
        if (!empty($raw['credits']['crew'])) {
            $director = collect_first($raw['credits']['crew'], fn($c) => $c['job'] === 'Director');
            $mapped['director'] = $director['name'] ?? '';
        }

        // Extract top 5 cast
        if (!empty($raw['credits']['cast'])) {
            $cast = array_slice($raw['credits']['cast'], 0, 5);
            $mapped['cast'] = implode(', ', array_column($cast, 'name'));
        }

        // Extract YouTube trailer
        if (!empty($raw['videos']['results'])) {
            foreach ($raw['videos']['results'] as $v) {
                if ($v['site'] === 'YouTube' && in_array($v['type'], ['Trailer', 'Teaser'])) {
                    $mapped['trailer_url'] = 'https://www.youtube.com/watch?v=' . $v['key'];
                    break;
                }
            }
        }

        // Override genre with full genre names from detail
        if (!empty($raw['genres'])) {
            $mapped['genre'] = implode(', ', array_column(array_slice($raw['genres'], 0, 2), 'name'));
        }

        // Override duration with actual runtime
        $mapped['duration'] = $raw['runtime'] ?? $mapped['duration'];

        return $this->success($mapped);
    }

    /** GET /api/tmdb/movie/{id}/similar */
    public function similar(int $id): \CodeIgniter\HTTP\Response
    {
        $raw = $this->tmdb->getSimilar($id);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/movie/{id}/recommendations */
    public function recommendations(int $id): \CodeIgniter\HTTP\Response
    {
        $raw = $this->tmdb->getRecommendations($id);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($this->mapResults($raw), $this->buildMeta($raw));
    }

    /** GET /api/tmdb/movie/{id}/credits */
    public function credits(int $id): \CodeIgniter\HTTP\Response
    {
        $raw = $this->tmdb->getCredits($id);
        if (isset($raw['error']))
            return $this->tmdbError($raw);

        $director = null;
        foreach (($raw['crew'] ?? []) as $c) {
            if ($c['job'] === 'Director') {
                $director = $c;
                break;
            }
        }

        return $this->success([
            'director' => $director,
            'cast' => array_slice($raw['cast'] ?? [], 0, 10),
            'crew' => array_slice($raw['crew'] ?? [], 0, 10),
        ]);
    }

    /** GET /api/tmdb/movie/{id}/videos */
    public function videos(int $id): \CodeIgniter\HTTP\Response
    {
        $raw = $this->tmdb->getVideos($id);
        if (isset($raw['error']))
            return $this->tmdbError($raw);

        $results = $raw['results'] ?? [];
        // Add full YouTube URL
        foreach ($results as &$v) {
            if ($v['site'] === 'YouTube') {
                $v['youtube_url'] = 'https://www.youtube.com/watch?v=' . $v['key'];
                $v['embed_url'] = 'https://www.youtube.com/embed/' . $v['key'];
            }
        }

        return $this->success($results);
    }

    /** GET /api/tmdb/person/{id} */
    public function person(int $id): \CodeIgniter\HTTP\Response
    {
        $raw = $this->tmdb->getPerson($id);
        if (isset($raw['error']))
            return $this->tmdbError($raw);
        return $this->success($raw);
    }

    // ---------------------------------------------------------------
    // Import endpoint — pulls TMDB movies into local DB
    // ---------------------------------------------------------------

    /**
     * POST /api/tmdb/import
     *
     * Body (JSON):
     *   { "source": "popular|top_rated|trending|now_playing", "pages": 1 }
     *
     * Requires admin session.
     */
    public function import(): \CodeIgniter\HTTP\Response
    {
        // Only admins can import
        if (session()->get('role') !== 'admin') {
            return $this->jsonError('Admin access required', 403);
        }

        $body = $this->request->getJSON(true) ?? [];
        $source = $body['source'] ?? 'popular';
        $pages = min((int) ($body['pages'] ?? 1), 5); // max 5 pages

        $imported = 0;
        $skipped = 0;
        $errors = [];

        $movieModel = new \App\Models\MovieModel();

        for ($page = 1; $page <= $pages; $page++) {
            $raw = match ($source) {
                'top_rated' => $this->tmdb->getTopRated($page),
                'trending' => $this->tmdb->getTrending('week'),
                'now_playing' => $this->tmdb->getNowPlaying($page),
                default => $this->tmdb->getPopular($page),
            };

            if (isset($raw['error'])) {
                $errors[] = $raw['error'];
                break;
            }

            foreach (($raw['results'] ?? []) as $tmdbMovie) {
                $mapped = $this->tmdb->mapMovie($tmdbMovie);

                // Skip if slug already exists
                if ($movieModel->where('slug', $mapped['slug'])->first()) {
                    $skipped++;
                    continue;
                }

                // Fetch full detail for director/cast/trailer/runtime
                $detail = $this->tmdb->getMovieDetail($tmdbMovie['id']);
                if ($detail && !isset($detail['error'])) {
                    // Director
                    foreach (($detail['credits']['crew'] ?? []) as $c) {
                        if ($c['job'] === 'Director') {
                            $mapped['director'] = $c['name'];
                            break;
                        }
                    }
                    // Cast (top 5)
                    $cast = array_slice($detail['credits']['cast'] ?? [], 0, 5);
                    $mapped['cast'] = implode(', ', array_column($cast, 'name'));
                    // Trailer
                    foreach (($detail['videos']['results'] ?? []) as $v) {
                        if ($v['site'] === 'YouTube' && in_array($v['type'], ['Trailer', 'Teaser'])) {
                            $mapped['trailer_url'] = 'https://www.youtube.com/watch?v=' . $v['key'];
                            break;
                        }
                    }
                    // Runtime
                    if (!empty($detail['runtime']))
                        $mapped['duration'] = $detail['runtime'];
                    // Genres
                    if (!empty($detail['genres'])) {
                        $mapped['genre'] = implode(', ', array_column(array_slice($detail['genres'], 0, 2), 'name'));
                    }
                }

                if ($movieModel->insert($mapped)) {
                    $imported++;
                } else {
                    $errors[] = 'Insert failed for: ' . $mapped['title'];
                }
            }
        }

        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/json')
            ->setBody(json_encode([
                'status' => 'success',
                'message' => "Import complete: {$imported} imported, {$skipped} skipped",
                'imported' => $imported,
                'skipped' => $skipped,
                'errors' => $errors,
            ]));
    }
}

// ---------------------------------------------------------------
// Helper (PHP 8 compatible)
// ---------------------------------------------------------------
if (!function_exists('collect_first')) {
    function collect_first(array $arr, callable $fn): ?array
    {
        foreach ($arr as $item) {
            if ($fn($item))
                return $item;
        }
        return null;
    }
}

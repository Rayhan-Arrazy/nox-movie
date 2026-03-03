<?php

namespace App\Controllers\Api;

use App\Models\FavoriteModel;
use App\Models\WatchlistModel;
use App\Models\WatchHistoryModel;
use CodeIgniter\Controller;

/**
 * UserController  — handles all user-specific features backed by MySQL DB
 *
 * All routes require the user to be logged in (auth filter via session).
 * userId is read from session — never trusted from client input.
 *
 * Routes (all prefixed /api/user/):
 *   GET    /api/user/favorites              → list favorites
 *   POST   /api/user/favorites/{movieId}    → toggle favorite
 *   DELETE /api/user/favorites              → clear all favorites
 *
 *   GET    /api/user/watchlist              → list watchlist
 *   POST   /api/user/watchlist/{movieId}    → toggle watchlist
 *   DELETE /api/user/watchlist              → clear watchlist
 *
 *   GET    /api/user/history                → list watch history
 *   POST   /api/user/history/{movieId}      → record/update watch entry
 *   DELETE /api/user/history                → clear history
 *
 *   GET    /api/user/state                  → all 3 lists in one request (for initial page load)
 */
class UserController extends Controller
{
    protected $response;
    protected $request;
    protected int $userId;

    public function __construct()
    {
        $this->response = \Config\Services::response();
        $this->request = \Config\Services::request();
        $this->userId = (int) session()->get('userId');
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    private function json(mixed $data, int $code = 200)
    {
        return $this->response
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setBody(json_encode($data));
    }

    private function requireLogin()
    {
        if (!session()->get('isLoggedIn') || !$this->userId) {
            return $this->json(['status' => 'error', 'message' => 'Login required'], 401);
        }
        return null;
    }

    // ─── Full State (bootstrap on page load) ─────────────────────────

    /**
     * GET /api/user/state
     * Returns all favorite IDs, watchlist IDs, and history in one call.
     * Used by JavaScript to initialize UI state (hearts, bookmarks, history).
     */
    public function state()
    {
        if ($err = $this->requireLogin())
            return $err;

        $favModel = new FavoriteModel();
        $wlModel = new WatchlistModel();
        $histModel = new WatchHistoryModel();

        return $this->json([
            'status' => 'success',
            'data' => [
                'favorite_ids' => $favModel->getFavoriteIds($this->userId),
                'watchlist_ids' => $wlModel->getWatchlistIds($this->userId),
                'history' => $histModel->getHistory($this->userId, 20),
            ],
        ]);
    }

    // ─── Favorites ───────────────────────────────────────────────────

    /** GET /api/user/favorites */
    public function favorites()
    {
        if ($err = $this->requireLogin())
            return $err;

        $model = new FavoriteModel();
        $movies = $model->getFavoriteMovies($this->userId);

        return $this->json(['status' => 'success', 'data' => $movies]);
    }

    /**
     * POST /api/user/favorites/{movieId}
     * Toggles a movie in/out of favorites.
     * Returns { added: true/false }
     */
    public function toggleFavorite(int $movieId)
    {
        if ($err = $this->requireLogin())
            return $err;

        $model = new FavoriteModel();
        $added = $model->toggle($this->userId, $movieId);

        return $this->json([
            'status' => 'success',
            'added' => $added,
            'message' => $added ? '❤️ Added to Favorites' : 'Removed from Favorites',
        ]);
    }

    /** DELETE /api/user/favorites — clear all */
    public function clearFavorites()
    {
        if ($err = $this->requireLogin())
            return $err;

        $model = new FavoriteModel();
        $model->where('user_id', $this->userId)->delete();

        return $this->json(['status' => 'success', 'message' => 'Favorites cleared']);
    }

    // ─── Watchlist (Collections / Bookmarks) ─────────────────────────

    /** GET /api/user/watchlist */
    public function watchlist()
    {
        if ($err = $this->requireLogin())
            return $err;

        $model = new WatchlistModel();
        $movies = $model->getWatchlistMovies($this->userId);

        return $this->json(['status' => 'success', 'data' => $movies]);
    }

    /**
     * POST /api/user/watchlist/{movieId}
     * Toggles a movie in/out of the watchlist.
     */
    public function toggleWatchlist(int $movieId)
    {
        if ($err = $this->requireLogin())
            return $err;

        $model = new WatchlistModel();
        $added = $model->toggle($this->userId, $movieId);

        return $this->json([
            'status' => 'success',
            'added' => $added,
            'message' => $added ? '🔖 Added to Watchlist' : 'Removed from Watchlist',
        ]);
    }

    /** DELETE /api/user/watchlist — clear all */
    public function clearWatchlist()
    {
        if ($err = $this->requireLogin())
            return $err;

        $model = new WatchlistModel();
        $model->clearAll($this->userId);

        return $this->json(['status' => 'success', 'message' => 'Watchlist cleared']);
    }

    // ─── Watch History ───────────────────────────────────────────────

    /** GET /api/user/history */
    public function history()
    {
        if ($err = $this->requireLogin())
            return $err;

        $model = new WatchHistoryModel();
        $history = $model->getHistory($this->userId, 20);

        return $this->json(['status' => 'success', 'data' => $history]);
    }

    /**
     * POST /api/user/history/{movieId}
     * Body (JSON, optional): { "progress_seconds": 0, "completed": false }
     * Records or updates a watch entry for the logged-in user.
     */
    public function recordHistory(int $movieId)
    {
        if ($err = $this->requireLogin())
            return $err;

        $body = $this->request->getJSON(true) ?? [];
        $progress = (int) ($body['progress_seconds'] ?? 0);
        $completed = (bool) ($body['completed'] ?? false);

        $model = new WatchHistoryModel();
        $model->record($this->userId, $movieId, $progress, $completed);

        return $this->json(['status' => 'success', 'message' => 'History recorded']);
    }

    /** DELETE /api/user/history — clear all history */
    public function clearHistory()
    {
        if ($err = $this->requireLogin())
            return $err;

        $model = new WatchHistoryModel();
        $model->clearHistory($this->userId);

        return $this->json(['status' => 'success', 'message' => 'Watch history cleared']);
    }
}

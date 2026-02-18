<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="mb-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-lime/10 flex items-center justify-center">
                    <i data-lucide="bookmark" class="w-5 h-5 text-lime"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold font-display text-white">My Collections</h1>
                    <p class="text-sm text-gray-600">Your bookmarked movies & watchlist</p>
                </div>
            </div>
        </div>

        <?php if (session()->get('isLoggedIn')): ?>
            <!-- Collections Grid -->
            <div id="collections-grid" class="grid gap-4 grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6">
                <div class="col-span-full flex items-center justify-center py-10">
                    <div class="w-7 h-7 border-[3px] border-dark-400 border-t-lime rounded-full animate-spin"></div>
                </div>
            </div>
        <?php else: ?>
            <!-- Not Logged In -->
            <div class="bg-dark-200 rounded-2xl p-10 border border-white/5 text-center animate-fade-in-up">
                <div class="w-16 h-16 rounded-2xl bg-lime/10 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="bookmark" class="w-8 h-8 text-lime"></i>
                </div>
                <h2 class="text-lg font-bold text-white font-display mb-2">Build Your Watchlist</h2>
                <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Sign in to bookmark movies and create your personal
                    watchlist.</p>
                <a href="/login" class="btn-lime px-6 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Sign In
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const grid = document.getElementById('collections-grid');
        if (!grid) return;

        // Load featured movies as demo collections
        const movies = await apiFetch('/movies/featured');

        if (movies.length === 0) {
            grid.innerHTML = `
            <div class="col-span-full text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-dark-300 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="bookmark" class="w-8 h-8 text-gray-600"></i>
                </div>
                <h3 class="text-base font-semibold text-white mb-1">No bookmarks yet</h3>
                <p class="text-xs text-gray-600 mb-4">Bookmark movies to watch later</p>
                <a href="/browse" class="btn-lime px-5 py-2 rounded-xl text-xs font-bold inline-block">Browse Movies</a>
            </div>`;
        } else {
            grid.innerHTML = movies.map((m, i) => `
            <div class="animate-fade-in-up" style="animation-delay: ${i * 40}ms">${createMovieCard(m, i)}</div>
        `).join('');
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>
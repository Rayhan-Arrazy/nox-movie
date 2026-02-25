<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section id="hero-section" class="relative h-[75vh] min-h-[500px] overflow-hidden bg-[#06060f]">
    <div class="absolute inset-0 skeleton"></div>
    <div class="absolute bottom-16 left-6 space-y-3 z-10">
        <div class="skeleton h-3 w-28 rounded-full"></div>
        <div class="skeleton h-10 w-80 rounded-xl"></div>
        <div class="skeleton h-3 w-72 rounded-lg"></div>
        <div class="flex gap-3 mt-4">
            <div class="skeleton h-11 w-36 rounded-xl"></div>
            <div class="skeleton h-11 w-32 rounded-xl"></div>
        </div>
    </div>
</section>

<!-- Continue Watching (if logged in) -->
<?php if (session()->get('isLoggedIn')): ?>
    <section id="continue-watching-section" class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto -mt-8 relative z-10 mb-6">
        <div class="bg-[#0f0f22] rounded-2xl p-5 border border-white/5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="play-circle" class="w-5 h-5 text-[#7c5cfc]"></i>
                    <h2 class="text-base font-semibold font-display text-white">Continue Watching</h2>
                </div>
                <a href="/browse" class="text-xs text-[#7c5cfc] hover:underline">Browse more</a>
            </div>
            <div id="continue-watching" class="scroll-container flex gap-3">
                <p class="text-sm text-[#6b6b9a] py-4">Movies you watch will appear here.</p>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Movie Sections -->
<div id="movie-sections" class="relative z-10 space-y-2 pb-10">
    <div class="flex items-center justify-center py-10">
        <div class="w-7 h-7 border-[3px] border-[#1c1c38] border-t-[#7c5cfc] rounded-full animate-spin"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const [featured, trending, all] = await Promise.all([
            apiFetch('/movies/featured'),
            apiFetch('/movies/trending'),
            apiFetch('/movies'),
        ]);

        initHero(featured);
        loadContinueWatching();

        // Group movies by genre
        const byGenre = {};
        all.forEach(m => {
            if (!byGenre[m.genre]) byGenre[m.genre] = [];
            byGenre[m.genre].push(m);
        });

        const container = document.getElementById('movie-sections');
        let html = '';
        html += createMovieRow('🔥 Hot Movie Now', 'Trending this week', trending, 'trending');
        for (const [genre, movies] of Object.entries(byGenre)) {
            html += createMovieRow(genre, `Explore ${genre.toLowerCase()} movies`, movies, slugify(genre));
        }
        html += createMovieRow('🎬 All Movies', 'Complete collection', all, 'all-movies');
        container.innerHTML = html;
    });
</script>
<?= $this->endSection() ?>
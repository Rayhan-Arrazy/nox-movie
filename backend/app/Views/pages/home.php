<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section id="hero-section" class="relative h-[75vh] min-h-[500px] overflow-hidden bg-dark">
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
    <section class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto -mt-8 relative z-10 mb-6">
        <div class="bg-dark-200 rounded-2xl p-5 border border-white/5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="play-circle" class="w-5 h-5 text-lime"></i>
                    <h2 class="text-base font-semibold font-display text-white">Continue Watching</h2>
                </div>
                <a href="/browse" class="text-xs text-lime hover:underline">Show all</a>
            </div>
            <div id="continue-watching" class="scroll-container flex gap-3">
                <!-- Populated by JS -->
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Movie Sections -->
<div id="movie-sections" class="relative z-10 space-y-2 pb-10">
    <div class="flex items-center justify-center py-10">
        <div class="w-7 h-7 border-[3px] border-dark-400 border-t-lime rounded-full animate-spin"></div>
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

        // Continue watching (show first 5 trending as demo)
        const cw = document.getElementById('continue-watching');
        if (cw && trending.length > 0) {
            cw.innerHTML = trending.slice(0, 5).map((m, i) => `
                <a href="/movie/${m.slug}" class="flex-shrink-0 w-[260px] bg-dark-300 rounded-xl overflow-hidden border border-white/5 hover:border-lime/20 transition-all group">
                    <div class="relative h-32 overflow-hidden">
                        <img src="${m.backdrop_url}" alt="${m.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-300 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-dark-400"><div class="h-full bg-lime rounded-r" style="width: ${30 + i * 15}%"></div></div>
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-semibold text-white truncate">${m.title}</h4>
                        <p class="text-[10px] text-gray-500">Season 1 • Episode ${i + 1}</p>
                    </div>
                </a>
            `).join('');
        }

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
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-black">
    <!-- YouTube Player Container -->
    <div id="video-container" class="relative w-full h-screen bg-black">

        <!-- Top Bar (always visible) -->
        <div id="top-bar" class="absolute top-0 left-0 right-0 z-40">
            <div class="bg-gradient-to-b from-black/90 to-transparent px-4 sm:px-8 py-5">
                <div class="flex items-center justify-between max-w-7xl mx-auto">
                    <a href="/movie/<?= esc($movie['slug']) ?>"
                        class="flex items-center gap-3 text-white hover:text-[#e8b84b] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold"><?= esc($movie['title']) ?></p>
                            <p class="text-[10px] text-gray-400"><?= esc($movie['year']) ?> •
                                <?= esc($movie['genre']) ?>
                            </p>
                        </div>
                    </a>
                    <div class="flex items-center gap-2">
                        <span
                            class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-semibold bg-[rgba(232,184,75,0.15)] text-[#e8b84b] border border-[rgba(232,184,75,0.25)]">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <?= esc($movie['rating']) ?>
                        </span>
                        <a href="/movie/<?= esc($movie['slug']) ?>"
                            class="px-3 py-1.5 rounded-lg bg-white/10 text-white text-xs hover:bg-white/15 transition-all border border-white/10">
                            Movie Info
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- YouTube iframe will be injected here by JS -->
        <div id="yt-embed-wrapper" class="w-full h-full flex items-center justify-center bg-black">
            <div class="text-center">
                <div
                    class="w-12 h-12 border-4 border-[#2e2920] border-t-[#e8b84b] rounded-full animate-spin mx-auto mb-3">
                </div>
                <p class="text-sm text-gray-500">Loading player...</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Pass movie data to JS
    window.CURRENT_MOVIE = <?= json_encode([
        'id' => $movie['id'],
        'title' => $movie['title'],
        'slug' => $movie['slug'],
        'genre' => $movie['genre'],
        'year' => $movie['year'],
        'rating' => $movie['rating'],
        'poster_url' => $movie['poster_url'],
        'backdrop_url' => $movie['backdrop_url'],
        'duration' => $movie['duration'],
    ]) ?>;

    document.addEventListener('DOMContentLoaded', () => {
        // Record watch history
        if (window.CURRENT_MOVIE && window.CURRENT_MOVIE.id) {
            recordWatch(window.CURRENT_MOVIE);
        }

        // Inject YouTube iframe
        const wrapper = document.getElementById('yt-embed-wrapper');
        wrapper.innerHTML = `
            <iframe
                src="https://www.youtube.com/embed/NvT7aGbAP14?autoplay=1&rel=0&modestbranding=1"
                class="w-full h-full"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                allowfullscreen
                frameborder="0">
            </iframe>
        `;

        // ESC key to go back
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') window.location.href = '/movie/<?= esc($movie['slug']) ?>';
        });
    });
</script>
<?= $this->endSection() ?>
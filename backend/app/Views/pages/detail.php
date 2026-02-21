<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$castMembers = !empty($movie['cast']) ? array_map('trim', explode(',', $movie['cast'])) : [];
$hrs = floor($movie['duration'] / 60);
$mins = $movie['duration'] % 60;
$durationStr = $hrs > 0 ? "{$hrs}h {$mins}m" : "{$mins}m";
?>

<!-- Backdrop -->
<div class="relative h-[55vh] overflow-hidden">
    <img src="<?= esc($movie['backdrop_url']) ?>" alt="<?= esc($movie['title']) ?>"
        class="w-full h-full object-cover" />
    <div class="hero-gradient absolute inset-0"></div>
    <div class="hero-side-gradient absolute inset-0"></div>

    <!-- Now Streaming Badge -->
    <div
        class="absolute top-6 left-6 z-20 flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-sm border border-white/10">
        <div class="w-2 h-2 bg-[#e8b84b] rounded-full animate-pulse"></div>
        <span class="text-[11px] text-white font-medium">Now Streaming</span>
    </div>

    <!-- Back -->
    <a href="javascript:history.back()"
        class="absolute top-6 right-6 z-20 flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/40 backdrop-blur-sm text-white hover:bg-[rgba(232,184,75,0.2)] transition-all border border-white/10">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        <span class="text-xs font-medium">Back</span>
    </a>

    <!-- Watch Now -->
    <a href="/watch/<?= esc($movie['slug']) ?>"
        class="absolute right-6 bottom-20 z-20 flex items-center gap-2 px-4 py-2.5 rounded-full bg-[#e8b84b] text-[#0a0908] font-bold text-sm hover:bg-[#f0cd7a] transition-all shadow-lg shadow-[rgba(232,184,75,0.3)]">
        Watch Now
        <div class="w-7 h-7 rounded-full bg-[#0a0908]/20 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                <polygon points="5 3 19 12 5 21 5 3" />
            </svg>
        </div>
    </a>
</div>

<!-- Content -->
<div class="relative z-10 -mt-36 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="flex flex-col md:flex-row gap-8">

        <!-- Poster -->
        <div class="flex-shrink-0 w-full md:w-64 lg:w-72">
            <div class="relative rounded-2xl overflow-hidden shadow-2xl shadow-black/60 border border-white/5">
                <img src="<?= esc($movie['poster_url']) ?>" alt="<?= esc($movie['title']) ?>"
                    class="w-full aspect-[2/3] object-cover" />
            </div>
        </div>

        <!-- Details -->
        <div class="flex-1 animate-fade-in-up">
            <!-- Tags -->
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span
                    class="badge-lime px-3 py-1 rounded-full text-[11px] font-semibold uppercase tracking-wider"><?= esc($movie['genre']) ?></span>
                <div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#242019] border border-white/5">
                    <svg class="w-3.5 h-3.5 text-[#e8b84b] star-glow" fill="currentColor" viewBox="0 0 24 24">
                        <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                    <span class="text-xs font-bold text-[#e8b84b]"><?= esc($movie['rating']) ?></span>
                </div>
                <span class="text-xs text-[#7a6e60]"><?= esc($movie['year']) ?></span>
                <span class="text-[#4a4238]">•</span>
                <span class="text-xs text-[#7a6e60]"><?= $durationStr ?></span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-display leading-tight mb-4">
                <?= esc($movie['title']) ?>
            </h1>

            <!-- Description -->
            <p class="text-sm text-[#7a6e60] leading-relaxed mb-6 max-w-xl">
                <?= esc($movie['description']) ?>
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 mb-8">
                <a href="/watch/<?= esc($movie['slug']) ?>"
                    class="btn-lime inline-flex items-center gap-2 px-7 py-3 rounded-xl text-sm font-bold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <polygon points="5 3 19 12 5 21 5 3" />
                    </svg>
                    Watch Now
                </a>

                <!-- Favorite Button -->
                <button id="detail-fav-btn" onclick="handleDetailFav(<?= $movie['id'] ?>, this)"
                    class="w-11 h-11 rounded-xl bg-[#242019] border border-white/5 flex items-center justify-center transition-all hover:border-[rgba(232,184,75,0.3)]"
                    title="Add to Favorites">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                </button>

                <!-- Bookmark Button -->
                <button id="detail-bookmark-btn" onclick="handleDetailBookmark(<?= $movie['id'] ?>, this)"
                    class="w-11 h-11 rounded-xl bg-[#242019] border border-white/5 flex items-center justify-center transition-all hover:border-[rgba(232,184,75,0.3)]"
                    title="Add to Collections">
                    <i data-lucide="bookmark" class="w-4 h-4"></i>
                </button>

                <!-- Share Button -->
                <button onclick="shareMovie('<?= esc($movie['title']) ?>')"
                    class="w-11 h-11 rounded-xl bg-[#242019] border border-white/5 flex items-center justify-center text-[#7a6e60] hover:text-white hover:border-[rgba(232,184,75,0.3)] transition-all"
                    title="Share">
                    <i data-lucide="share-2" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Cast & Crew -->
            <div class="bg-[#1a1714] rounded-2xl p-5 border border-white/5 space-y-4">
                <?php if (!empty($movie['director'])): ?>
                    <div>
                        <h3 class="text-[11px] font-semibold text-[#7a6e60] uppercase tracking-wider mb-1.5">Director</h3>
                        <p class="text-sm text-white font-medium"><?= esc($movie['director']) ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($castMembers)): ?>
                    <div>
                        <h3 class="text-[11px] font-semibold text-[#7a6e60] uppercase tracking-wider mb-2">Cast & Crew</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($castMembers as $member): ?>
                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#242019] border border-white/5">
                                    <div
                                        class="w-6 h-6 rounded-full bg-gradient-to-br from-[#e8b84b]/60 to-[#c9962e] flex items-center justify-center text-[10px] font-bold text-[#0a0908]">
                                        <?= strtoupper(substr(trim($member), 0, 1)) ?>
                                    </div>
                                    <span class="text-xs text-[#7a6e60]"><?= esc($member) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Related Movies -->
    <?php if (!empty($relatedMovies)): ?>
        <div class="mt-12" id="related-section"></div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const movieId = <?= $movie['id'] ?>;

        // Set initial state for fav/bookmark buttons
        const favBtn = document.getElementById('detail-fav-btn');
        const bookmarkBtn = document.getElementById('detail-bookmark-btn');

        if (isFavorite(movieId)) {
            favBtn.classList.add('text-[#e8b84b]', 'border-[rgba(232,184,75,0.3)]');
            favBtn.classList.remove('text-[#7a6e60]');
            favBtn.title = 'Remove from Favorites';
        } else {
            favBtn.classList.add('text-[#7a6e60]');
        }

        if (isBookmarked(movieId)) {
            bookmarkBtn.classList.add('text-[#e8b84b]', 'border-[rgba(232,184,75,0.3)]');
            bookmarkBtn.classList.remove('text-[#7a6e60]');
            bookmarkBtn.title = 'Remove from Collections';
        } else {
            bookmarkBtn.classList.add('text-[#7a6e60]');
        }

        // Related movies
        <?php if (!empty($relatedMovies)): ?>
            const related = <?= json_encode($relatedMovies) ?>;
            document.getElementById('related-section').innerHTML = createMovieRow(
                'More Like This',
                '<?= esc($movie['genre']) ?> movies you might enjoy',
                related,
                'related'
            );
        <?php endif; ?>
    });

    function handleDetailFav(movieId, btn) {
        const isNowFav = toggleFavorite(movieId, null);
        if (isNowFav) {
            btn.classList.add('text-[#e8b84b]', 'border-[rgba(232,184,75,0.3)]');
            btn.classList.remove('text-[#7a6e60]');
            btn.title = 'Remove from Favorites';
        } else {
            btn.classList.remove('text-[#e8b84b]', 'border-[rgba(232,184,75,0.3)]');
            btn.classList.add('text-[#7a6e60]');
            btn.title = 'Add to Favorites';
        }
        // bounce animation
        btn.style.transform = 'scale(1.3)';
        setTimeout(() => btn.style.transform = '', 250);
    }

    function handleDetailBookmark(movieId, btn) {
        const isNowIn = toggleCollection(movieId, null);
        if (isNowIn) {
            btn.classList.add('text-[#e8b84b]', 'border-[rgba(232,184,75,0.3)]');
            btn.classList.remove('text-[#7a6e60]');
            btn.title = 'Remove from Collections';
        } else {
            btn.classList.remove('text-[#e8b84b]', 'border-[rgba(232,184,75,0.3)]');
            btn.classList.add('text-[#7a6e60]');
            btn.title = 'Bookmark';
        }
        btn.style.transform = 'scale(1.3)';
        setTimeout(() => btn.style.transform = '', 250);
    }

    function shareMovie(title) {
        if (navigator.share) {
            navigator.share({ title: title, url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => showToast('📋 Link copied to clipboard!'));
        }
    }
</script>
<?= $this->endSection() ?>
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
        class="absolute top-6 left-6 z-20 flex items-center gap-2 px-3 py-1.5 rounded-full bg-dark/60 backdrop-blur-sm border border-white/10">
        <div class="w-2 h-2 bg-lime rounded-full animate-pulse"></div>
        <span class="text-[11px] text-white font-medium">Now streaming</span>
    </div>

    <!-- Back -->
    <a href="javascript:history.back()"
        class="absolute top-6 right-6 z-20 flex items-center gap-2 px-3 py-1.5 rounded-full bg-dark/40 backdrop-blur-sm text-white hover:bg-lime/20 transition-all border border-white/10">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        <span class="text-xs font-medium">Back</span>
    </a>

    <!-- Watch Now Floating Button -->
    <a href="/watch/<?= esc($movie['slug']) ?>"
        class="absolute right-6 bottom-20 z-20 flex items-center gap-2 px-4 py-2.5 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-lime hover:text-dark transition-all border border-white/10 hover:border-lime group">
        <span class="text-sm font-semibold">Watch Now</span>
        <div
            class="w-8 h-8 rounded-full bg-lime flex items-center justify-center group-hover:bg-dark group-hover:text-lime transition-all">
            <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
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
                <div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-dark-300 border border-white/5">
                    <svg class="w-3.5 h-3.5 text-gold star-glow" fill="currentColor" viewBox="0 0 24 24">
                        <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                    <span class="text-xs font-bold text-gold"><?= esc($movie['rating']) ?></span>
                </div>
                <span class="text-xs text-gray-500"><?= esc($movie['year']) ?></span>
                <span class="text-gray-700">•</span>
                <span class="text-xs text-gray-500"><?= $durationStr ?></span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-display leading-tight mb-4">
                <?= esc($movie['title']) ?>
            </h1>

            <!-- Description -->
            <p class="text-sm text-gray-500 leading-relaxed mb-6 max-w-xl">
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
                <button
                    class="w-11 h-11 rounded-xl bg-dark-300 border border-white/5 flex items-center justify-center text-gray-500 hover:text-lime hover:border-lime/30 transition-all">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                </button>
                <button
                    class="w-11 h-11 rounded-xl bg-dark-300 border border-white/5 flex items-center justify-center text-gray-500 hover:text-lime hover:border-lime/30 transition-all">
                    <i data-lucide="bookmark" class="w-4 h-4"></i>
                </button>
                <button
                    class="w-11 h-11 rounded-xl bg-dark-300 border border-white/5 flex items-center justify-center text-gray-500 hover:text-lime hover:border-lime/30 transition-all">
                    <i data-lucide="share-2" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Cast & Crew -->
            <div class="bg-dark-200 rounded-2xl p-5 border border-white/5 space-y-4">
                <?php if (!empty($movie['director'])): ?>
                    <div>
                        <h3 class="text-[11px] font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Director</h3>
                        <p class="text-sm text-white font-medium"><?= esc($movie['director']) ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($castMembers)): ?>
                    <div>
                        <h3 class="text-[11px] font-semibold text-gray-600 uppercase tracking-wider mb-2">Cast & Crew</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($castMembers as $member): ?>
                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-dark-300 border border-white/5">
                                    <div
                                        class="w-6 h-6 rounded-full bg-gradient-to-br from-lime/60 to-green-600 flex items-center justify-center text-[10px] font-bold text-dark">
                                        <?= strtoupper(substr(trim($member), 0, 1)) ?>
                                    </div>
                                    <span class="text-xs text-gray-400"><?= esc($member) ?></span>
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
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const related = <?= json_encode($relatedMovies) ?>;
                document.getElementById('related-section').innerHTML = createMovieRow(
                    'More <?= esc($movie['genre']) ?> Movies',
                    'You might also like',
                    related,
                    'related'
                );
            });
        </script>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
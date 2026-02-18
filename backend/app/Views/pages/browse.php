<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="mb-6 animate-fade-in-up">
            <h1 class="text-2xl sm:text-3xl font-bold font-display mb-1">
                <span class="gradient-text">Browse</span> <span class="text-white">Movies</span>
            </h1>
            <p class="text-sm text-gray-600">Discover your next favorite film</p>
        </div>

        <!-- Search & Filters -->
        <div class="bg-dark-200 rounded-2xl p-4 sm:p-5 mb-6 border border-white/5 animate-fade-in-up"
            style="animation-delay: 80ms">
            <div class="flex flex-col sm:flex-row gap-3">
                <form onsubmit="handleBrowseSearch(event)" class="flex-1 relative">
                    <i data-lucide="search"
                        class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600"></i>
                    <input id="browse-search" type="text" placeholder="Search by title, director, or cast..."
                        class="form-input w-full rounded-xl pl-10 pr-4 py-2.5 text-sm" />
                </form>
                <div class="flex items-center gap-2">
                    <button onclick="setGridSize('normal')"
                        class="p-2.5 rounded-xl bg-lime/10 text-lime border border-lime/20 transition-all"
                        title="Normal">
                        <i data-lucide="grid-3x3" class="w-4 h-4"></i>
                    </button>
                    <button onclick="setGridSize('large')"
                        class="p-2.5 rounded-xl bg-dark-300 text-gray-600 border border-white/5 hover:text-white transition-all"
                        title="Large">
                        <i data-lucide="layout-grid" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Genre Filters -->
            <div class="mt-3 flex flex-wrap gap-2 items-center">
                <i data-lucide="filter" class="w-3.5 h-3.5 text-gray-600"></i>
                <?php foreach ($genres as $genre): ?>
                    <button onclick="filterGenre('<?= esc($genre) ?>')" data-genre="<?= esc($genre) ?>"
                        class="genre-btn cat-tab px-3.5 py-1.5 rounded-full text-[11px] font-medium text-gray-500">
                        <?= esc($genre) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Results Info -->
        <div class="flex items-center justify-between mb-5">
            <p id="results-count" class="text-xs text-gray-600">Loading...</p>
        </div>

        <!-- Movie Grid -->
        <div id="movie-grid" class="grid gap-4 grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6">
            <?php for ($i = 0; $i < 12; $i++): ?>
                <div class="skeleton aspect-[2/3] rounded-xl"></div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => initBrowsePage());
</script>
<?= $this->endSection() ?>
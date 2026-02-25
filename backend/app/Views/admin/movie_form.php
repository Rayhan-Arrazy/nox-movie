<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php $isEdit = !empty($movie); ?>

<div class="min-h-screen pb-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="/admin"
                class="w-10 h-10 rounded-xl bg-[#15152d] border border-white/5 flex items-center justify-center text-gray-500 hover:text-white transition-all">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold font-display text-white">
                    <?= $isEdit ? 'Edit Movie' : 'Add New Movie' ?>
                </h1>
                <p class="text-xs text-gray-600">
                    <?= $isEdit ? 'Update movie details' : 'Fill in the movie information' ?>
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-[#0f0f22] rounded-2xl p-5 sm:p-7 border border-white/5">

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-5 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <p class="text-xs text-red-400">
                            <?= esc($err) ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= $isEdit ? '/admin/movies/update/' . $movie['id'] : '/admin/movies/store' ?>" method="POST"
                class="space-y-5">

                <!-- Title & Genre -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Title
                            *</label>
                        <input type="text" name="title" required value="<?= esc($movie['title'] ?? old('title')) ?>"
                            placeholder="Movie title" class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Genre
                            *</label>
                        <select name="genre" required class="form-input w-full rounded-xl px-4 py-2.5 text-sm">
                            <option value="">Select genre</option>
                            <?php foreach (['Action', 'Sci-Fi', 'Drama', 'Thriller', 'Horror', 'Comedy', 'Animation', 'Romance', 'Fantasy', 'Mystery'] as $g): ?>
                                <option value="<?= $g ?>" <?= (($movie['genre'] ?? old('genre')) === $g) ? 'selected' : '' ?>>
                                    <?= $g ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label
                        class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Description
                        *</label>
                    <textarea name="description" required rows="3" placeholder="Movie description..."
                        class="form-input w-full rounded-xl px-4 py-2.5 text-sm resize-none"><?= esc($movie['description'] ?? old('description')) ?></textarea>
                </div>

                <!-- Year, Duration, Rating -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Year
                            *</label>
                        <input type="number" name="year" required
                            value="<?= esc($movie['year'] ?? old('year') ?? '2024') ?>"
                            class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Duration
                            (min) *</label>
                        <input type="number" name="duration" required
                            value="<?= esc($movie['duration'] ?? old('duration') ?? '120') ?>"
                            class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Rating
                            *</label>
                        <input type="number" step="0.1" name="rating" required
                            value="<?= esc($movie['rating'] ?? old('rating') ?? '7.5') ?>"
                            class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                </div>

                <!-- URLs -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Poster
                            URL</label>
                        <input type="url" name="poster_url"
                            value="<?= esc($movie['poster_url'] ?? old('poster_url')) ?>" placeholder="https://..."
                            class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Backdrop
                            URL</label>
                        <input type="url" name="backdrop_url"
                            value="<?= esc($movie['backdrop_url'] ?? old('backdrop_url')) ?>" placeholder="https://..."
                            class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Trailer
                            URL</label>
                        <input type="url" name="trailer_url"
                            value="<?= esc($movie['trailer_url'] ?? old('trailer_url')) ?>" placeholder="https://..."
                            class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Video
                            URL</label>
                        <input type="url" name="video_url" value="<?= esc($movie['video_url'] ?? old('video_url')) ?>"
                            placeholder="https://..." class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                </div>

                <!-- Director & Cast -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Director</label>
                        <input type="text" name="director" value="<?= esc($movie['director'] ?? old('director')) ?>"
                            placeholder="Director name" class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Cast</label>
                        <input type="text" name="cast" value="<?= esc($movie['cast'] ?? old('cast')) ?>"
                            placeholder="Comma separated" class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                </div>

                <!-- Flags -->
                <div class="flex items-center gap-6 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" <?= (!empty($movie['is_featured'])) ? 'checked' : '' ?>
                        class="w-4 h-4 rounded bg-[#15152d] border-gray-600 text-[#a78bfa] focus:ring-lime/30" />
                        <span class="text-xs text-gray-400">Featured</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_trending" value="1" <?= (!empty($movie['is_trending'])) ? 'checked' : '' ?>
                        class="w-4 h-4 rounded bg-[#15152d] border-gray-600 text-[#a78bfa] focus:ring-lime/30" />
                        <span class="text-xs text-gray-400">Trending</span>
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-center gap-3 pt-4 border-t border-white/5">
                    <button type="submit"
                        class="btn-lime px-6 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                        <i data-lucide="<?= $isEdit ? 'save' : 'plus' ?>" class="w-4 h-4"></i>
                        <?= $isEdit ? 'Update Movie' : 'Add Movie' ?>
                    </button>
                    <a href="/admin"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-500 bg-[#15152d] border border-white/5 hover:text-white transition-all">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold font-display text-white">Admin Dashboard</h1>
                <p class="text-sm text-gray-600">Manage movies and users</p>
            </div>
            <a href="/admin/movies/create"
                class="btn-lime px-5 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2 w-fit">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Movie
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-dark-200 rounded-2xl p-5 border border-white/5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-lime/10 flex items-center justify-center">
                        <i data-lucide="film" class="w-5 h-5 text-lime"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-white font-display">
                    <?= $totalMovies ?>
                </p>
                <p class="text-xs text-gray-600 mt-0.5">Total Movies</p>
            </div>
            <div class="bg-dark-200 rounded-2xl p-5 border border-white/5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                        <i data-lucide="users" class="w-5 h-5 text-blue-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-white font-display">
                    <?= $totalUsers ?>
                </p>
                <p class="text-xs text-gray-600 mt-0.5">Total Users</p>
            </div>
            <div class="bg-dark-200 rounded-2xl p-5 border border-white/5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                        <i data-lucide="star" class="w-5 h-5 text-amber-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-white font-display">
                    <?= count(array_filter($movies, fn($m) => $m['is_featured'])) ?>
                </p>
                <p class="text-xs text-gray-600 mt-0.5">Featured</p>
            </div>
            <div class="bg-dark-200 rounded-2xl p-5 border border-white/5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-5 h-5 text-rose-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-white font-display">
                    <?= count(array_filter($movies, fn($m) => $m['is_trending'])) ?>
                </p>
                <p class="text-xs text-gray-600 mt-0.5">Trending</p>
            </div>
        </div>

        <!-- Movies Table -->
        <div class="bg-dark-200 rounded-2xl border border-white/5 overflow-hidden mb-8">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-white flex items-center gap-2">
                    <i data-lucide="film" class="w-4 h-4 text-lime"></i> All Movies
                </h2>
                <a href="/admin/movies/create" class="text-xs text-lime hover:underline">+ Add New</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full admin-table">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">
                                Movie</th>
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                Genre</th>
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Year</th>
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Rating</th>
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">
                                Flags</th>
                            <th
                                class="text-right px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movies as $movie): ?>
                            <tr>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="<?= esc($movie['poster_url']) ?>" alt=""
                                            class="w-9 h-12 rounded-lg object-cover flex-shrink-0" />
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-white truncate max-w-[180px]">
                                                <?= esc($movie['title']) ?>
                                            </p>
                                            <p class="text-[10px] text-gray-600 truncate max-w-[180px]">
                                                <?= esc($movie['slug']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 hidden sm:table-cell">
                                    <span class="badge-lime px-2 py-0.5 rounded-md text-[10px] font-medium">
                                        <?= esc($movie['genre']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3 hidden md:table-cell text-xs text-gray-500">
                                    <?= esc($movie['year']) ?>
                                </td>
                                <td class="px-5 py-3 hidden md:table-cell">
                                    <span class="text-xs text-gold font-semibold">⭐
                                        <?= esc($movie['rating']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex gap-1">
                                        <?php if ($movie['is_featured']): ?>
                                            <span
                                                class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/15 text-amber-400">F</span>
                                        <?php endif; ?>
                                        <?php if ($movie['is_trending']): ?>
                                            <span
                                                class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-500/15 text-rose-400">T</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center gap-1 justify-end">
                                        <a href="/movie/<?= esc($movie['slug']) ?>"
                                            class="p-1.5 rounded-lg text-gray-600 hover:text-white hover:bg-dark-300 transition-all"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <a href="/admin/movies/edit/<?= $movie['id'] ?>"
                                            class="p-1.5 rounded-lg text-gray-600 hover:text-lime hover:bg-lime/10 transition-all"
                                            title="Edit">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <a href="/admin/movies/delete/<?= $movie['id'] ?>"
                                            onclick="return confirm('Delete this movie?')"
                                            class="p-1.5 rounded-lg text-gray-600 hover:text-red-400 hover:bg-red-500/10 transition-all"
                                            title="Delete">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-dark-200 rounded-2xl border border-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-white flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-blue-400"></i> Users
                </h2>
                <a href="/admin/users" class="text-xs text-lime hover:underline">Manage</a>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php foreach (array_slice($users, 0, 6) as $user): ?>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-dark-300 border border-white/5">
                        <div
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-lime/60 to-green-600 flex items-center justify-center text-dark font-bold text-xs flex-shrink-0">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-white truncate">
                                <?= esc($user['name']) ?>
                            </p>
                            <p class="text-[10px] text-gray-600 truncate">
                                <?= esc($user['email']) ?>
                            </p>
                        </div>
                        <span
                            class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase <?= $user['role'] === 'admin' ? 'bg-lime/15 text-lime' : 'bg-blue-500/15 text-blue-400' ?>">
                            <?= esc($user['role']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
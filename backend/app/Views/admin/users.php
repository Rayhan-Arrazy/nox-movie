<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="/admin"
                class="w-10 h-10 rounded-xl bg-[#15152d] border border-white/5 flex items-center justify-center text-gray-500 hover:text-white transition-all">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold font-display text-white">Manage Users</h1>
                <p class="text-xs text-gray-600">
                    <?= count($users) ?> registered users
                </p>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-[#0f0f22] rounded-2xl border border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full admin-table">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">
                                User</th>
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                Email</th>
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="text-left px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Joined</th>
                            <th
                                class="text-right px-5 py-3 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-lime/60 to-green-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                        <p class="text-xs font-semibold text-white">
                                            <?= esc($user['name']) ?>
                                        </p>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-500 hidden sm:table-cell">
                                    <?= esc($user['email']) ?>
                                </td>
                                <td class="px-5 py-3">
                                    <span
                                        class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase <?= $user['role'] === 'admin' ? 'bg-[#7c5cfc]/15 text-[#a78bfa]' : 'bg-blue-500/15 text-blue-400' ?>">
                                        <?= esc($user['role']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-600 hidden md:table-cell">
                                    <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <?php if ($user['id'] != session()->get('userId')): ?>
                                        <a href="/admin/users/delete/<?= $user['id'] ?>"
                                            onclick="return confirm('Delete this user?')"
                                            class="p-1.5 rounded-lg text-gray-600 hover:text-red-400 hover:bg-red-500/10 transition-all inline-flex"
                                            title="Delete">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-[10px] text-gray-600">You</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
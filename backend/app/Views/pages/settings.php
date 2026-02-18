<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="mb-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-lime/10 flex items-center justify-center">
                    <i data-lucide="settings" class="w-5 h-5 text-lime"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold font-display text-white">Settings</h1>
                    <p class="text-sm text-gray-600">Manage your account preferences</p>
                </div>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="bg-dark-200 rounded-2xl p-5 sm:p-7 border border-white/5 mb-5 animate-fade-in-up"
            style="animation-delay: 80ms">
            <h2 class="text-sm font-semibold text-white flex items-center gap-2 mb-5">
                <i data-lucide="user" class="w-4 h-4 text-lime"></i> Profile
            </h2>

            <div class="flex items-center gap-5 mb-6">
                <div
                    class="w-16 h-16 rounded-2xl bg-gradient-to-br from-lime to-green-500 flex items-center justify-center text-dark text-2xl font-black flex-shrink-0">
                    <?= strtoupper(substr(session()->get('userName') ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <p class="text-base font-bold text-white">
                        <?= esc(session()->get('userName')) ?>
                    </p>
                    <p class="text-xs text-gray-500">
                        <?= esc(session()->get('userEmail')) ?>
                    </p>
                    <span
                        class="inline-block mt-1 px-2 py-0.5 rounded-md text-[9px] font-bold uppercase <?= session()->get('role') === 'admin' ? 'bg-lime/15 text-lime' : 'bg-blue-500/15 text-blue-400' ?>">
                        <?= esc(session()->get('role')) ?> account
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Display
                        Name</label>
                    <input type="text" value="<?= esc(session()->get('userName')) ?>" disabled
                        class="form-input w-full rounded-xl px-4 py-2.5 text-sm opacity-60 cursor-not-allowed" />
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Email
                        Address</label>
                    <input type="email" value="<?= esc(session()->get('userEmail')) ?>" disabled
                        class="form-input w-full rounded-xl px-4 py-2.5 text-sm opacity-60 cursor-not-allowed" />
                </div>
            </div>
        </div>

        <!-- Preferences -->
        <div class="bg-dark-200 rounded-2xl p-5 sm:p-7 border border-white/5 mb-5 animate-fade-in-up"
            style="animation-delay: 160ms">
            <h2 class="text-sm font-semibold text-white flex items-center gap-2 mb-5">
                <i data-lucide="sliders-horizontal" class="w-4 h-4 text-lime"></i> Preferences
            </h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-xl bg-dark-300 border border-white/5">
                    <div class="flex items-center gap-3">
                        <i data-lucide="bell" class="w-4 h-4 text-gray-500"></i>
                        <div>
                            <p class="text-xs font-medium text-white">Notifications</p>
                            <p class="text-[10px] text-gray-600">Get notified about new releases</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-dark-400 rounded-full peer peer-checked:bg-lime peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all">
                        </div>
                    </label>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-dark-300 border border-white/5">
                    <div class="flex items-center gap-3">
                        <i data-lucide="play-circle" class="w-4 h-4 text-gray-500"></i>
                        <div>
                            <p class="text-xs font-medium text-white">Autoplay</p>
                            <p class="text-[10px] text-gray-600">Auto-play next episode</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-dark-400 rounded-full peer peer-checked:bg-lime peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all">
                        </div>
                    </label>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-dark-300 border border-white/5">
                    <div class="flex items-center gap-3">
                        <i data-lucide="subtitles" class="w-4 h-4 text-gray-500"></i>
                        <div>
                            <p class="text-xs font-medium text-white">Subtitles</p>
                            <p class="text-[10px] text-gray-600">Show subtitles by default</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-dark-400 rounded-full peer peer-checked:bg-lime peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all">
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-dark-200 rounded-2xl p-5 sm:p-7 border border-red-500/10 animate-fade-in-up"
            style="animation-delay: 240ms">
            <h2 class="text-sm font-semibold text-red-400 flex items-center gap-2 mb-4">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Danger Zone
            </h2>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-white">Logout</p>
                    <p class="text-[10px] text-gray-600">End your current session</p>
                </div>
                <a href="/logout"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-red-400 bg-red-500/10 border border-red-500/15 hover:bg-red-500/20 transition-all">
                    Sign Out
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
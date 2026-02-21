<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="mb-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-[rgba(232,184,75,0.1)] flex items-center justify-center">
                    <i data-lucide="bookmark" class="w-5 h-5 text-[#e8b84b]"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold font-display text-white">My Collections</h1>
                    <p class="text-sm text-[#7a6e60]">Your bookmarked movies & watchlist</p>
                </div>
            </div>
        </div>

        <?php if (session()->get('isLoggedIn')): ?>
            <!-- Collections Grid -->
            <div id="collections-grid" class="grid gap-4 grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6">
                <div class="col-span-full flex items-center justify-center py-10">
                    <div class="w-7 h-7 border-[3px] border-[#2e2920] border-t-[#e8b84b] rounded-full animate-spin"></div>
                </div>
            </div>
        <?php else: ?>
            <!-- Not Logged In -->
            <div class="bg-[#1a1714] rounded-2xl p-10 border border-white/5 text-center animate-fade-in-up">
                <div class="w-16 h-16 rounded-2xl bg-[rgba(232,184,75,0.1)] flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="bookmark" class="w-8 h-8 text-[#e8b84b]"></i>
                </div>
                <h2 class="text-lg font-bold text-white font-display mb-2">Build Your Watchlist</h2>
                <p class="text-sm text-[#7a6e60] mb-6 max-w-md mx-auto">Sign in to bookmark movies and create your personal
                    watchlist.</p>
                <a href="/login" class="btn-lime px-6 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Sign In
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => initCollectionsPage());
</script>
<?= $this->endSection() ?>
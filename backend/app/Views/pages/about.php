<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Hero -->
        <div class="text-center mb-10 animate-fade-in-up">
            <div class="w-14 h-14 rounded-2xl bg-[rgba(124,92,252,0.12)] flex items-center justify-center mx-auto mb-4">
                <i data-lucide="moon" class="w-7 h-7 text-[#a78bfa]"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black font-display text-white mb-3">About <span
                    class="gradient-text">NOX Movie</span></h1>
            <p class="text-sm text-gray-500 max-w-lg mx-auto leading-relaxed">Your ultimate destination for premium
                movie streaming. Discover, watch, and enjoy cinema like never before.</p>
        </div>

        <!-- Mission -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
            <div class="bg-[#0f0f22] rounded-2xl p-6 border border-white/5 animate-fade-in-up"
                style="animation-delay: 80ms">
                <div class="w-10 h-10 rounded-xl bg-[rgba(124,92,252,0.12)] flex items-center justify-center mb-4">
                    <i data-lucide="film" class="w-5 h-5 text-[#a78bfa]"></i>
                </div>
                <h3 class="text-sm font-bold text-white mb-2">Premium Content</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Curated selection of high-quality movies across every
                    genre, from blockbusters to indie gems.</p>
            </div>
            <div class="bg-[#0f0f22] rounded-2xl p-6 border border-white/5 animate-fade-in-up"
                style="animation-delay: 160ms">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center mb-4">
                    <i data-lucide="zap" class="w-5 h-5 text-blue-400"></i>
                </div>
                <h3 class="text-sm font-bold text-white mb-2">Seamless Streaming</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Zero buffering, crisp quality, and a beautiful
                    interface designed for the best viewing experience.</p>
            </div>
            <div class="bg-[#0f0f22] rounded-2xl p-6 border border-white/5 animate-fade-in-up"
                style="animation-delay: 240ms">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center mb-4">
                    <i data-lucide="users" class="w-5 h-5 text-amber-400"></i>
                </div>
                <h3 class="text-sm font-bold text-white mb-2">Community First</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Connect with fellow movie lovers, share reviews, and
                    discover what's trending in the community.</p>
            </div>
        </div>

        <!-- Team / Tech -->
        <div class="bg-[#0f0f22] rounded-2xl p-6 sm:p-8 border border-white/5 animate-fade-in-up"
            style="animation-delay: 320ms">
            <h2 class="text-lg font-bold font-display text-white mb-4">Built With Love</h2>
            <p class="text-sm text-gray-500 leading-relaxed mb-6">NOX Movie is a cinematic streaming platform built as a
                web
                development project, combining a CodeIgniter 4 backend with a modern Tailwind CSS frontend to deliver a
                premium, responsive user experience.</p>
            <div class="flex flex-wrap gap-2">
                <?php foreach (['CodeIgniter 4', 'PHP 8', 'MySQL', 'Tailwind CSS', 'JavaScript', 'Lucide Icons', 'REST API'] as $tech): ?>
                    <span
                        class="px-3 py-1.5 rounded-lg text-[11px] font-medium bg-[#15152d] text-gray-400 border border-white/5">
                        <?= $tech ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
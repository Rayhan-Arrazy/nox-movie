<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <div class="w-14 h-14 rounded-2xl bg-rose-500/10 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="megaphone" class="w-7 h-7 text-rose-400"></i>
            </div>
            <h1 class="text-3xl font-black font-display text-white mb-3">Press & Media</h1>
            <p class="text-sm text-gray-500 max-w-md mx-auto">Latest news and updates from NOX Movie.</p>
        </div>

        <!-- Press Releases -->
        <div class="space-y-4">
            <?php
            $articles = [
                ['NOX Movie Launches New Streaming Platform', 'Feb 2026', 'NOX Movie officially launches its premium cinematic streaming platform, featuring a curated collection of movies across all genres with a beautifully designed, dark-themed interface — where stories shine brightest.'],
                ['Introducing Collections & Favorites', 'Feb 2026', 'Users can now save their favorite movies and create personal collections for a more personalized streaming experience.'],
                ['Admin Dashboard Released', 'Feb 2026', 'NOX Movie unveils its powerful admin dashboard, giving administrators full control over movie management, user administration, and platform analytics.'],
            ];
            foreach ($articles as $i => $article):
                ?>
                <div class="bg-[#0f0f22] rounded-2xl p-6 border border-white/5 animate-fade-in-up"
                    style="animation-delay: <?= $i * 80 ?>ms">
                    <div class="flex items-center gap-2 mb-3">
                        <span
                            class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/15">Press
                            Release</span>
                        <span class="text-[10px] text-gray-600">
                            <?= esc($article[1]) ?>
                        </span>
                    </div>
                    <h3 class="text-sm font-bold text-white mb-2">
                        <?= esc($article[0]) ?>
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        <?= esc($article[2]) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Media Contact -->
        <div class="mt-8 bg-[#0f0f22] rounded-2xl p-6 border border-white/5 animate-fade-in-up">
            <h3 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4 text-[#a78bfa]"></i> Media Inquiries
            </h3>
            <p class="text-xs text-gray-500 mb-4">For press and media inquiries, please contact our communications team.
            </p>
            <a href="/contact" class="btn-lime px-5 py-2 rounded-xl text-xs font-bold inline-flex items-center gap-2">
                <i data-lucide="send" class="w-3.5 h-3.5"></i> Contact Press Team
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
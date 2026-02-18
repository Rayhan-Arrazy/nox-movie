<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="shield" class="w-7 h-7 text-purple-400"></i>
            </div>
            <h1 class="text-3xl font-black font-display text-white mb-3">Privacy Policy</h1>
            <p class="text-sm text-gray-500">Last updated: February 2026</p>
        </div>

        <div class="bg-dark-200 rounded-2xl p-6 sm:p-8 border border-white/5 space-y-6 animate-fade-in-up"
            style="animation-delay: 80ms">
            <?php
            $sections = [
                ['Information We Collect', 'When you create an account, we collect your name, email address, and password (securely hashed). We may also collect usage data such as movies watched, favorites, and browsing preferences to improve your experience.'],
                ['How We Use Your Information', 'Your information is used to provide and personalize the CineVerse service, including movie recommendations, favorites, and watchlist features. We never sell your personal data to third parties.'],
                ['Data Security', 'We implement industry-standard security measures to protect your data. Passwords are hashed using bcrypt and never stored in plain text. All sessions are securely managed.'],
                ['Cookies & Sessions', 'CineVerse uses session cookies to keep you logged in and remember your preferences. These cookies are essential for the service to function properly.'],
                ['Your Rights', 'You have the right to access, update, or delete your personal information at any time. You can manage your account settings or contact us to request data deletion.'],
                ['Changes to This Policy', 'We may update this privacy policy from time to time. Any changes will be posted on this page with an updated revision date.'],
            ];
            foreach ($sections as $i => $section):
                ?>
                <div>
                    <h2 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-lg bg-lime/10 text-lime text-[10px] font-bold flex items-center justify-center">
                            <?= $i + 1 ?>
                        </span>
                        <?= esc($section[0]) ?>
                    </h2>
                    <p class="text-xs text-gray-500 leading-relaxed pl-8">
                        <?= esc($section[1]) ?>
                    </p>
                </div>
            <?php endforeach; ?>

            <div class="pt-4 border-t border-white/5">
                <p class="text-xs text-gray-600">If you have questions about this privacy policy, please <a
                        href="/contact" class="text-lime hover:underline">contact us</a>.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
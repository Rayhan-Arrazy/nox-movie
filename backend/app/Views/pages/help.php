<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="help-circle" class="w-7 h-7 text-blue-400"></i>
            </div>
            <h1 class="text-3xl font-black font-display text-white mb-3">Help Center</h1>
            <p class="text-sm text-gray-500 max-w-md mx-auto">Find answers to common questions and get support.</p>
        </div>

        <!-- FAQ -->
        <div class="space-y-3">
            <?php
            $faqs = [
                ['How do I create an account?', 'Click the "Sign Up" button in the top navigation bar. Fill in your name, email, and password to create your free account.'],
                ['How do I watch a movie?', 'Browse our collection and click on any movie. On the movie detail page, click "Watch Now" to start streaming. You need to be logged in to watch.'],
                ['Is CineVerse free?', 'Yes! CineVerse is completely free to use. Create an account and start watching movies right away.'],
                ['How do I add movies to my favorites?', 'Click the heart icon on any movie card or on the movie detail page to add it to your favorites list.'],
                ['Can I bookmark movies to watch later?', 'Yes! Click the bookmark icon on the movie detail page to save movies to your collections for later viewing.'],
                ['How do I search for movies?', 'Use the search bar in the top navigation to search by title, director, or cast. You can also filter by genre on the Browse page.'],
                ['What if a movie won\'t play?', 'Try refreshing the page or checking your internet connection. If the problem persists, contact us through the Contact page.'],
                ['How do I become an admin?', 'Admin roles are assigned by existing administrators. Contact the site owner if you need admin access.'],
            ];
            foreach ($faqs as $i => $faq):
                ?>
                <div class="bg-dark-200 rounded-2xl border border-white/5 overflow-hidden animate-fade-in-up"
                    style="animation-delay: <?= $i * 60 ?>ms">
                    <button
                        onclick="this.parentElement.querySelector('.faq-answer').classList.toggle('hidden'); this.querySelector('.faq-icon').classList.toggle('rotate-45')"
                        class="w-full flex items-center justify-between p-5 text-left">
                        <span class="text-sm font-medium text-white pr-4">
                            <?= esc($faq[0]) ?>
                        </span>
                        <div
                            class="faq-icon w-6 h-6 rounded-lg bg-dark-300 flex items-center justify-center flex-shrink-0 transition-transform duration-200">
                            <i data-lucide="plus" class="w-3.5 h-3.5 text-lime"></i>
                        </div>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 -mt-1">
                        <p class="text-xs text-gray-500 leading-relaxed">
                            <?= esc($faq[1]) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Still need help -->
        <div class="mt-8 bg-dark-200 rounded-2xl p-6 border border-white/5 text-center animate-fade-in-up">
            <h3 class="text-sm font-bold text-white mb-2">Still need help?</h3>
            <p class="text-xs text-gray-500 mb-4">Can't find what you're looking for? Reach out to our support team.</p>
            <a href="/contact" class="btn-lime px-5 py-2 rounded-xl text-xs font-bold inline-flex items-center gap-2">
                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i> Contact Support
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
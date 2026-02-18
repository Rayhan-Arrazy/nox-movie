<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <div class="w-14 h-14 rounded-2xl bg-lime/10 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="briefcase" class="w-7 h-7 text-lime"></i>
            </div>
            <h1 class="text-3xl font-black font-display text-white mb-3">Join Our Team</h1>
            <p class="text-sm text-gray-500 max-w-md mx-auto">Help us build the future of movie streaming. We're always
                looking for passionate people.</p>
        </div>

        <!-- Open Positions -->
        <div class="space-y-3">
            <?php
            $positions = [
                ['Full Stack Developer', 'Engineering', 'Remote', 'Build scalable features with PHP, JavaScript, and modern web technologies.'],
                ['UI/UX Designer', 'Design', 'Remote', 'Create beautiful, intuitive interfaces that millions of users will love.'],
                ['Content Curator', 'Content', 'Jakarta', 'Discover and curate the best movies for our growing library.'],
                ['DevOps Engineer', 'Engineering', 'Remote', 'Manage cloud infrastructure, CI/CD pipelines, and system reliability.'],
            ];
            foreach ($positions as $i => $pos):
                ?>
                <div class="bg-dark-200 rounded-2xl p-5 border border-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-in-up"
                    style="animation-delay: <?= $i * 80 ?>ms">
                    <div>
                        <h3 class="text-sm font-bold text-white mb-1">
                            <?= esc($pos[0]) ?>
                        </h3>
                        <p class="text-xs text-gray-500 mb-2">
                            <?= esc($pos[3]) ?>
                        </p>
                        <div class="flex items-center gap-2">
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-lime/10 text-lime border border-lime/15">
                                <?= esc($pos[1]) ?>
                            </span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-dark-300 text-gray-400 border border-white/5">
                                <?= esc($pos[2]) ?>
                            </span>
                        </div>
                    </div>
                    <a href="/contact"
                        class="px-4 py-2 rounded-xl text-xs font-semibold text-lime bg-lime/10 border border-lime/15 hover:bg-lime/20 transition-all flex-shrink-0 text-center">
                        Apply Now
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- CTA -->
        <div class="mt-8 bg-dark-200 rounded-2xl p-6 border border-white/5 text-center animate-fade-in-up">
            <h3 class="text-sm font-bold text-white mb-2">Don't see a fit?</h3>
            <p class="text-xs text-gray-500 mb-4">Send us your resume anyway. We're always looking for talented people.
            </p>
            <a href="/contact" class="btn-lime px-5 py-2 rounded-xl text-xs font-bold inline-flex items-center gap-2">
                <i data-lucide="send" class="w-3.5 h-3.5"></i> Get in Touch
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
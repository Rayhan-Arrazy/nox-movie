<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-black">
    <div id="video-container" class="relative w-full h-screen flex items-center justify-center bg-black cursor-pointer">
        <video id="main-video" src="<?= esc($movie['video_url']) ?>" class="w-full h-full object-contain"
            preload="metadata"></video>

        <!-- Center Play -->
        <div id="center-play-btn" class="absolute inset-0 flex items-center justify-center bg-black/30 z-10">
            <div
                class="w-20 h-20 rounded-full bg-lime/90 flex items-center justify-center shadow-2xl shadow-lime/30 cursor-pointer hover:bg-lime transition-all hover:scale-110">
                <svg class="w-8 h-8 text-dark ml-1" fill="currentColor" viewBox="0 0 24 24">
                    <polygon points="5 3 19 12 5 21 5 3" />
                </svg>
            </div>
        </div>

        <!-- Top Bar -->
        <div id="top-bar"
            class="absolute top-0 left-0 right-0 z-30 transition-all duration-500 opacity-100 translate-y-0">
            <div class="bg-gradient-to-b from-black/90 to-transparent px-4 sm:px-8 py-4">
                <div class="flex items-center justify-between max-w-7xl mx-auto">
                    <a href="/movie/<?= esc($movie['slug']) ?>"
                        class="flex items-center gap-3 text-white hover:text-lime transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold"><?= esc($movie['title']) ?></p>
                            <p class="text-[10px] text-gray-500"><?= esc($movie['year']) ?> •
                                <?= esc($movie['genre']) ?>
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Controls -->
        <div id="controls-bar"
            class="absolute bottom-0 left-0 right-0 transition-all duration-500 opacity-100 translate-y-0 z-30">
            <div class="bg-gradient-to-t from-black/95 via-black/60 to-transparent pt-20 pb-6 px-4 sm:px-8">
                <div class="max-w-7xl mx-auto mb-4">
                    <div id="progress-bar"
                        class="progress-bar-container group relative h-1 bg-white/20 rounded-full cursor-pointer hover:h-2 transition-all duration-200">
                        <div id="progress-fill" class="absolute inset-y-0 left-0 bg-lime rounded-full"
                            style="width: 0%"></div>
                        <div id="progress-thumb"
                            class="absolute top-1/2 -translate-y-1/2 w-3.5 h-3.5 bg-lime rounded-full shadow-lg progress-thumb"
                            style="left: -7px"></div>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <button id="play-pause-btn" class="p-2 rounded-lg text-white hover:bg-white/10 transition-all">
                            <svg class="w-5 h-5 ml-0.5" fill="white" viewBox="0 0 24 24">
                                <polygon points="5 3 19 12 5 21 5 3" />
                            </svg>
                        </button>
                        <button id="skip-back"
                            class="p-2 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition-all"><svg
                                class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polygon points="19 20 9 12 19 4 19 20" />
                                <line x1="5" y1="19" x2="5" y2="5" />
                            </svg></button>
                        <button id="skip-fwd"
                            class="p-2 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition-all"><svg
                                class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polygon points="5 4 15 12 5 20 5 4" />
                                <line x1="19" y1="5" x2="19" y2="19" />
                            </svg></button>
                        <button id="volume-btn"
                            class="p-2 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition-all"><svg
                                class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07" />
                            </svg></button>
                        <input id="volume-slider" type="range" min="0" max="1" step="0.05" value="1"
                            class="hidden sm:block w-20 h-1 accent-lime cursor-pointer" />
                        <span id="time-display" class="text-[11px] text-white/50 font-mono ml-1">0:00 / 0:00</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <button id="fullscreen-btn"
                            class="p-2 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition-all"><svg
                                class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="15 3 21 3 21 9" />
                                <polyline points="9 21 3 21 3 15" />
                                <line x1="21" y1="3" x2="14" y2="10" />
                                <line x1="3" y1="21" x2="10" y2="14" />
                            </svg></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => initVideoPlayer());
</script>
<?= $this->endSection() ?>
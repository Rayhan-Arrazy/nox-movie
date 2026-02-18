/* ============================
   CineVerse - Main JavaScript
   Dark + Lime Theme
   ============================ */

const API_BASE = '/api';

// ─── API Helper ─────────────────────────────────────────
async function apiFetch(endpoint) {
  try {
    const res = await fetch(`${API_BASE}${endpoint}`);
    const json = await res.json();
    return json.data || [];
  } catch (err) {
    console.error('API Error:', err);
    return [];
  }
}

// ─── Utility ────────────────────────────────────────────
function formatDuration(minutes) {
  const hrs = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return hrs > 0 ? `${hrs}h ${mins}m` : `${mins}m`;
}

function slugify(text) {
  return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

// ─── Navbar ─────────────────────────────────────────────
window.addEventListener('scroll', () => {
  const nav = document.getElementById('main-navbar');
  if (!nav) return;
  nav.classList.toggle('navbar-scrolled', window.scrollY > 40);
});

function toggleSearch() {
  const form = document.getElementById('search-form');
  const btn = document.getElementById('search-toggle');
  if (!form) return;
  const isHidden = form.classList.contains('hidden');
  form.classList.toggle('hidden', !isHidden);
  form.classList.toggle('flex', isHidden);
  btn?.classList.toggle('hidden', isHidden);
  if (isHidden) document.getElementById('nav-search-input')?.focus();
}

function handleNavSearch(e) {
  e.preventDefault();
  const q = (document.getElementById('nav-search-input') || document.getElementById('mobile-search-input'))?.value.trim();
  if (q) window.location.href = `/browse?search=${encodeURIComponent(q)}`;
}

function toggleMobileMenu() {
  document.getElementById('mobile-menu')?.classList.toggle('hidden');
}

function toggleProfileMenu() {
  document.getElementById('profile-dropdown')?.classList.toggle('hidden');
}

// Close dropdowns on outside click
document.addEventListener('click', (e) => {
  const pw = document.getElementById('profile-dropdown-wrapper');
  const pd = document.getElementById('profile-dropdown');
  if (pw && pd && !pw.contains(e.target)) pd.classList.add('hidden');
});

// ─── Movie Card ─────────────────────────────────────────
function createMovieCard(movie, index = 0) {
  return `
    <a href="/movie/${movie.slug}" class="movie-card group block rounded-2xl overflow-hidden relative" style="animation-delay: ${index * 60}ms">
      <div class="relative aspect-[2/3] overflow-hidden bg-dark-200 rounded-2xl">
        <img src="${movie.poster_url}" alt="${movie.title}" class="w-full h-full object-cover" loading="lazy" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
        <div class="card-overlay absolute inset-0 bg-lime/5 flex items-center justify-center">
          <div class="w-12 h-12 rounded-full bg-lime flex items-center justify-center shadow-lg shadow-lime/30 transform scale-75 group-hover:scale-100 transition-transform duration-300">
            <svg class="w-5 h-5 text-dark ml-0.5" fill="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </div>
        </div>
        <!-- Heart -->
        <button onclick="event.preventDefault();event.stopPropagation();this.classList.toggle('text-red-500');this.classList.toggle('text-white/50')" class="absolute top-2.5 right-2.5 text-white/50 hover:text-red-500 transition-colors z-10">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>
        <!-- Genre Badge -->
        <div class="absolute top-2.5 left-2.5">
          <span class="px-2 py-0.5 rounded-md text-[9px] font-semibold uppercase bg-dark/70 text-lime backdrop-blur-sm">${movie.genre}</span>
        </div>
        <!-- Bottom Info -->
        <div class="absolute bottom-0 left-0 right-0 p-3">
          <h3 class="text-xs font-bold text-white truncate leading-tight mb-1">${movie.title}</h3>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5 text-[10px] text-gray-400">
              <span>${movie.year}</span>
              <span class="w-0.5 h-0.5 rounded-full bg-gray-600"></span>
              <span>${formatDuration(movie.duration)}</span>
            </div>
            <div class="flex items-center gap-0.5">
              <svg class="w-3 h-3 text-gold star-glow" fill="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <span class="text-[10px] font-bold text-gold">${movie.rating}</span>
            </div>
          </div>
        </div>
      </div>
    </a>
  `;
}

// ─── Movie Row ──────────────────────────────────────────
function createMovieRow(title, subtitle, movies, id) {
  if (!movies || movies.length === 0) return '';
  const sectionId = id || slugify(title);
  const cards = movies.map((m, i) => `<div class="flex-shrink-0 w-[150px] sm:w-[170px] lg:w-[190px]">${createMovieCard(m, i)}</div>`).join('');

  return `
    <section class="relative py-4" id="section-${sectionId}">
      <div class="flex items-end justify-between mb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-white font-display">${title}</h2>
          ${subtitle ? `<p class="text-[11px] text-gray-600 mt-0.5">${subtitle}</p>` : ''}
        </div>
        <div class="flex gap-1.5">
          <button onclick="scrollRow('${sectionId}', 'left')" class="p-1.5 rounded-lg bg-dark-300 text-gray-600 hover:text-lime hover:bg-lime/10 transition-all border border-white/5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button onclick="scrollRow('${sectionId}', 'right')" class="p-1.5 rounded-lg bg-dark-300 text-gray-600 hover:text-lime hover:bg-lime/10 transition-all border border-white/5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
      </div>
      <div id="scroll-${sectionId}" class="scroll-container flex gap-3 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        ${cards}
      </div>
    </section>
  `;
}

function scrollRow(id, dir) {
  const el = document.getElementById(`scroll-${id}`);
  if (el) el.scrollBy({ left: dir === 'left' ? -280 : 280, behavior: 'smooth' });
}

// ─── Hero Carousel ──────────────────────────────────────
let heroMovies = [], heroIndex = 0, heroInterval = null;

function initHero(movies) {
  heroMovies = movies;
  if (!movies.length) return;
  renderHero();
  heroInterval = setInterval(heroNext, 8000);
}

function renderHero() {
  const m = heroMovies[heroIndex];
  const container = document.getElementById('hero-section');
  if (!container || !m) return;

  const dots = heroMovies.map((_, i) =>
    `<button onclick="heroGoTo(${i})" class="transition-all duration-300 rounded-full ${i === heroIndex ? 'w-6 h-1.5 bg-lime' : 'w-1.5 h-1.5 bg-white/25 hover:bg-white/40'}"></button>`
  ).join('');

  container.innerHTML = `
    <div class="absolute inset-0">
      <img src="${m.backdrop_url}" alt="${m.title}" class="w-full h-full object-cover" />
      <div class="hero-gradient absolute inset-0"></div>
      <div class="hero-side-gradient absolute inset-0"></div>
    </div>
    <div class="relative z-10 h-full flex items-end pb-16 sm:pb-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-xl animate-fade-in-up">
          <div class="flex items-center gap-2 mb-3 flex-wrap">
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-lime/15 text-lime border border-lime/20">
              🔥 Hot Movie Now
            </span>
            <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-dark-300/80 text-gray-400 backdrop-blur-sm">${m.genre}</span>
            <div class="flex items-center gap-0.5">
              <svg class="w-3 h-3 text-gold star-glow" fill="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <span class="text-[11px] font-bold text-gold">${m.rating}</span>
            </div>
          </div>
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight mb-3 font-display">${m.title}</h1>
          <p class="text-sm text-gray-400 mb-6 line-clamp-2 leading-relaxed">${m.description}</p>
          <div class="flex items-center gap-3">
            <a href="/watch/${m.slug}" class="btn-lime inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              Watch Now
            </a>
            <a href="/movie/${m.slug}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-white bg-white/10 hover:bg-white/15 backdrop-blur-sm transition-all border border-white/10">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
              More Info
            </a>
            <button class="w-11 h-11 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white/60 hover:text-red-400 border border-white/10 transition-all">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="absolute bottom-6 right-4 sm:right-8 z-20 flex items-center gap-2.5">
      <button onclick="heroPrev()" class="p-2 rounded-lg bg-dark/40 backdrop-blur-sm border border-white/10 text-white/60 hover:text-lime hover:border-lime/30 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      </button>
      <div class="flex items-center gap-1">${dots}</div>
      <button onclick="heroNext()" class="p-2 rounded-lg bg-dark/40 backdrop-blur-sm border border-white/10 text-white/60 hover:text-lime hover:border-lime/30 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>
  `;
}

function heroNext() { heroIndex = (heroIndex + 1) % heroMovies.length; renderHero(); resetHeroInterval(); }
function heroPrev() { heroIndex = (heroIndex - 1 + heroMovies.length) % heroMovies.length; renderHero(); resetHeroInterval(); }
function heroGoTo(i) { heroIndex = i; renderHero(); resetHeroInterval(); }
function resetHeroInterval() { clearInterval(heroInterval); heroInterval = setInterval(heroNext, 8000); }

// ─── Video Player ───────────────────────────────────────
let playerControlsTimeout = null;

function initVideoPlayer() {
  const video = document.getElementById('main-video');
  if (!video) return;

  const container = document.getElementById('video-container');
  const playPauseBtn = document.getElementById('play-pause-btn');
  const centerPlayBtn = document.getElementById('center-play-btn');
  const progressBar = document.getElementById('progress-bar');
  const progressFill = document.getElementById('progress-fill');
  const progressThumb = document.getElementById('progress-thumb');
  const timeDisplay = document.getElementById('time-display');
  const volumeBtn = document.getElementById('volume-btn');
  const volumeSlider = document.getElementById('volume-slider');
  const fullscreenBtn = document.getElementById('fullscreen-btn');
  const controlsBar = document.getElementById('controls-bar');
  const topBar = document.getElementById('top-bar');
  let isPlaying = false;

  function togglePlay() { video.paused ? video.play() : video.pause(); }

  function showControls() {
    controlsBar.classList.remove('opacity-0', 'translate-y-8');
    controlsBar.classList.add('opacity-100', 'translate-y-0');
    topBar.classList.remove('opacity-0', '-translate-y-full');
    topBar.classList.add('opacity-100', 'translate-y-0');
    clearTimeout(playerControlsTimeout);
    playerControlsTimeout = setTimeout(() => { if (isPlaying) hideControls(); }, 3000);
  }

  function hideControls() {
    controlsBar.classList.add('opacity-0', 'translate-y-8');
    controlsBar.classList.remove('opacity-100', 'translate-y-0');
    topBar.classList.add('opacity-0', '-translate-y-full');
    topBar.classList.remove('opacity-100', 'translate-y-0');
  }

  video.addEventListener('play', () => {
    isPlaying = true;
    playPauseBtn.innerHTML = '<svg class="w-5 h-5" fill="white" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>';
    centerPlayBtn.classList.add('hidden');
  });

  video.addEventListener('pause', () => {
    isPlaying = false;
    playPauseBtn.innerHTML = '<svg class="w-5 h-5 ml-0.5" fill="white" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>';
    centerPlayBtn.classList.remove('hidden');
  });

  video.addEventListener('timeupdate', () => {
    if (video.duration) {
      const pct = (video.currentTime / video.duration) * 100;
      progressFill.style.width = pct + '%';
      progressThumb.style.left = `calc(${pct}% - 7px)`;
    }
    timeDisplay.textContent = `${fmtTime(video.currentTime)} / ${fmtTime(video.duration)}`;
  });

  video.addEventListener('ended', () => { isPlaying = false; centerPlayBtn.classList.remove('hidden'); showControls(); });

  container.addEventListener('mousemove', showControls);
  container.addEventListener('click', (e) => {
    if (e.target === video || e.target === centerPlayBtn || centerPlayBtn.contains(e.target)) togglePlay();
  });

  playPauseBtn.addEventListener('click', (e) => { e.stopPropagation(); togglePlay(); });
  centerPlayBtn.addEventListener('click', (e) => { e.stopPropagation(); togglePlay(); });
  document.getElementById('skip-back').addEventListener('click', (e) => { e.stopPropagation(); video.currentTime -= 10; });
  document.getElementById('skip-fwd').addEventListener('click', (e) => { e.stopPropagation(); video.currentTime += 10; });

  progressBar.addEventListener('click', (e) => {
    e.stopPropagation();
    const rect = progressBar.getBoundingClientRect();
    video.currentTime = ((e.clientX - rect.left) / rect.width) * video.duration;
  });

  volumeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    video.muted = !video.muted;
    volumeBtn.innerHTML = video.muted
      ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>'
      : '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>';
  });

  if (volumeSlider) volumeSlider.addEventListener('input', (e) => { e.stopPropagation(); video.volume = e.target.value; video.muted = e.target.value == 0; });

  fullscreenBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    document.fullscreenElement ? document.exitFullscreen() : container.requestFullscreen();
  });

  document.addEventListener('keydown', (e) => {
    if (e.target.tagName === 'INPUT') return;
    switch (e.key) {
      case ' ': case 'k': e.preventDefault(); togglePlay(); break;
      case 'f': container.requestFullscreen?.(); break;
      case 'm': video.muted = !video.muted; break;
      case 'ArrowLeft': video.currentTime -= 10; break;
      case 'ArrowRight': video.currentTime += 10; break;
      case 'ArrowUp': e.preventDefault(); video.volume = Math.min(1, video.volume + 0.1); break;
      case 'ArrowDown': e.preventDefault(); video.volume = Math.max(0, video.volume - 0.1); break;
    }
    showControls();
  });

  showControls();
}

function fmtTime(t) {
  if (isNaN(t)) return '0:00';
  return `${Math.floor(t / 60)}:${Math.floor(t % 60).toString().padStart(2, '0')}`;
}

// ─── Browse Page ────────────────────────────────────────
async function initBrowsePage() {
  const grid = document.getElementById('movie-grid');
  const countEl = document.getElementById('results-count');
  if (!grid) return;

  const params = new URLSearchParams(window.location.search);
  const search = params.get('search') || '';
  const genre = params.get('genre') || 'All';

  document.querySelectorAll('.genre-btn').forEach(btn => {
    if (btn.dataset.genre === genre) {
      btn.classList.add('active');
    }
  });

  const searchInput = document.getElementById('browse-search');
  if (searchInput && search) searchInput.value = search;

  let endpoint = '/movies';
  if (search) endpoint += `?search=${encodeURIComponent(search)}`;
  else if (genre && genre !== 'All') endpoint += `?genre=${encodeURIComponent(genre)}`;

  const movies = await apiFetch(endpoint);

  if (countEl) countEl.textContent = `${movies.length} movie${movies.length !== 1 ? 's' : ''} found`;

  if (movies.length === 0) {
    grid.innerHTML = `
      <div class="col-span-full text-center py-16">
        <div class="text-5xl mb-3">🎬</div>
        <h3 class="text-base font-semibold text-white mb-1">No movies found</h3>
        <p class="text-xs text-gray-600 mb-4">Try different keywords or filters</p>
        <a href="/browse" class="btn-lime px-5 py-2 rounded-xl text-xs font-bold inline-block">Clear Filters</a>
      </div>`;
  } else {
    grid.innerHTML = movies.map((m, i) => `
      <div class="animate-fade-in-up" style="animation-delay: ${i * 40}ms">${createMovieCard(m, i)}</div>
    `).join('');
  }
}

function handleBrowseSearch(e) {
  e.preventDefault();
  const q = document.getElementById('browse-search')?.value.trim();
  window.location.href = q ? `/browse?search=${encodeURIComponent(q)}` : '/browse';
}

function filterGenre(genre) {
  window.location.href = genre === 'All' ? '/browse' : `/browse?genre=${encodeURIComponent(genre)}`;
}

function setGridSize(size) {
  const grid = document.getElementById('movie-grid');
  if (!grid) return;
  grid.className = size === 'large'
    ? 'grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4'
    : 'grid gap-4 grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6';
}

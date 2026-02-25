/* ============================
   NOX Movie � Main JavaScript
   Night Cinema � Electric Violet
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

// ─── LocalStorage Keys ────────────────────────────────────
const LS_FAVORITES = 'cv_favorites';
const LS_COLLECTIONS = 'cv_collections';
const LS_WATCH_HIST = 'cv_watch_history';
const LS_SETTINGS = 'cv_settings';

function lsGet(key) {
  try { return JSON.parse(localStorage.getItem(key) || '[]'); } catch { return []; }
}
function lsSet(key, val) {
  localStorage.setItem(key, JSON.stringify(val));
}
function lsGetObj(key, def = {}) {
  try { return JSON.parse(localStorage.getItem(key) || JSON.stringify(def)); } catch { return def; }
}

// ─── Favorites ────────────────────────────────────────────
function getFavorites() { return lsGet(LS_FAVORITES); }
function isFavorite(movieId) { return getFavorites().includes(Number(movieId)); }

function toggleFavorite(movieId, btn) {
  movieId = Number(movieId);
  let favs = getFavorites();
  const wasFav = favs.includes(movieId);
  if (wasFav) { favs = favs.filter(id => id !== movieId); }
  else { favs.push(movieId); }
  lsSet(LS_FAVORITES, favs);

  if (btn) {
    btn.classList.toggle('text-[#a78bfa]', !wasFav);
    btn.classList.toggle('text-white/40', wasFav);
    btn.title = wasFav ? 'Add to Favorites' : 'Remove from Favorites';
    // animate
    btn.style.transform = 'scale(1.4)';
    setTimeout(() => btn.style.transform = '', 250);
  }

  showToast(wasFav ? 'Removed from Favorites' : '❤️ Added to Favorites');
  return !wasFav;
}

// ─── Collections (Bookmarks) ──────────────────────────────
function getCollections() { return lsGet(LS_COLLECTIONS); }
function isBookmarked(movieId) { return getCollections().includes(Number(movieId)); }

function toggleCollection(movieId, btn) {
  movieId = Number(movieId);
  let cols = getCollections();
  const wasIn = cols.includes(movieId);
  if (wasIn) { cols = cols.filter(id => id !== movieId); }
  else { cols.push(movieId); }
  lsSet(LS_COLLECTIONS, cols);

  if (btn) {
    btn.classList.toggle('text-[#a78bfa]', !wasIn);
    btn.classList.toggle('text-white/40', wasIn);
    btn.title = wasIn ? 'Bookmark' : 'Remove Bookmark';
    btn.style.transform = 'scale(1.4)';
    setTimeout(() => btn.style.transform = '', 250);
  }

  showToast(wasIn ? 'Removed from Collections' : '🔖 Added to Collections');
  return !wasIn;
}

// ─── Watch History ────────────────────────────────────────
function recordWatch(movie) {
  let hist = lsGet(LS_WATCH_HIST);
  hist = hist.filter(h => h.id !== movie.id);  // remove old entry
  hist.unshift({ ...movie, watchedAt: Date.now(), progress: 0 });
  if (hist.length > 20) hist = hist.slice(0, 20);
  lsSet(LS_WATCH_HIST, hist);
}

function getWatchHistory() { return lsGet(LS_WATCH_HIST); }

// ─── Toast ────────────────────────────────────────────────
function showToast(msg, type = 'success') {
  const existing = document.getElementById('dynamic-toast');
  if (existing) existing.remove();

  const div = document.createElement('div');
  div.id = 'dynamic-toast';
  div.className = `fixed top-20 right-4 lg:right-8 z-[100] animate-fade-in-up bg-[#0f0f22] border rounded-xl px-5 py-3 shadow-2xl max-w-xs ${type === 'error' ? 'border-red-500/30 toast-error' : 'border-[rgba(124,92,252,0.3)] toast-success'}`;
  div.innerHTML = `<p class="text-sm text-white font-medium">${msg}</p>`;
  document.body.appendChild(div);
  setTimeout(() => div.remove(), 3000);
}

// ─── Navbar ─────────────────────────────────────────────
window.addEventListener('scroll', () => {
  const nav = document.getElementById('main-navbar');
  if (!nav) return;
  nav.classList.toggle('navbar-scrolled', window.scrollY > 40);
});

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
  document.getElementById('notif-panel')?.classList.add('hidden');
}

// ─── Notifications ────────────────────────────────────────
function toggleNotifications() {
  const panel = document.getElementById('notif-panel');
  const profileDrop = document.getElementById('profile-dropdown');
  if (!panel) return;
  profileDrop?.classList.add('hidden');
  panel.classList.toggle('hidden');
}

function markAllRead() {
  document.querySelectorAll('.notif-unread').forEach(el => {
    el.classList.remove('notif-unread', 'bg-[rgba(124,92,252,0.05)]');
  });
  const dot = document.getElementById('notif-dot');
  if (dot) dot.classList.add('hidden');
  showToast('All notifications marked as read');
}

// Close dropdowns on outside click
document.addEventListener('click', (e) => {
  const pw = document.getElementById('profile-dropdown-wrapper');
  const pd = document.getElementById('profile-dropdown');
  const nw = document.getElementById('notif-wrapper');
  const np = document.getElementById('notif-panel');

  if (pw && pd && !pw.contains(e.target)) pd.classList.add('hidden');
  if (nw && np && !nw.contains(e.target)) np.classList.add('hidden');
});

// ─── Movie Card ─────────────────────────────────────────
function createMovieCard(movie, index = 0) {
  const favClass = isFavorite(movie.id) ? 'text-[#a78bfa]' : 'text-white/40';
  const bookClass = isBookmarked(movie.id) ? 'text-[#a78bfa]' : 'text-white/40';

  return `
    <a href="/movie/${movie.slug}" class="movie-card group block rounded-2xl overflow-hidden relative" style="animation-delay:${index * 60}ms">
      <div class="relative aspect-[2/3] overflow-hidden bg-[#0f0f22] rounded-2xl">
        <img src="${movie.poster_url}" alt="${movie.title}" class="w-full h-full object-cover" loading="lazy"
          onerror="this.onerror=null;this.src='https://placehold.co/300x450/0f0f22/7c5cfc?text=${encodeURIComponent(movie.title.substring(0, 20))}'" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
        <div class="card-overlay absolute inset-0 bg-[rgba(124,92,252,0.05)] flex items-center justify-center">
          <div class="w-12 h-12 rounded-full bg-[#7c5cfc] flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition-transform duration-300">
            <svg class="w-5 h-5 text-[#06060f] ml-0.5" fill="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </div>
        </div>
        <!-- Favorite Heart -->
        <button onclick="event.preventDefault();event.stopPropagation();toggleFavorite(${movie.id},this)"
          class="absolute top-2.5 right-2.5 ${favClass} hover:text-[#a78bfa] transition-all z-10"
          title="${isFavorite(movie.id) ? 'Remove from Favorites' : 'Add to Favorites'}"
          style="transition: transform 0.25s ease">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>
        <!-- Genre Badge -->
        <div class="absolute top-2.5 left-2.5">
          <span class="px-2 py-0.5 rounded-md text-[9px] font-semibold uppercase bg-black/60 text-[#7c5cfc] backdrop-blur-sm">${movie.genre}</span>
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
              <svg class="w-3 h-3 text-[#7c5cfc] star-glow" fill="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <span class="text-[10px] font-bold text-[#7c5cfc]">${movie.rating}</span>
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
          ${subtitle ? `<p class="text-[11px] text-[#6b6b9a] mt-0.5">${subtitle}</p>` : ''}
        </div>
        <div class="flex gap-1.5">
          <button onclick="scrollRow('${sectionId}','left')" class="p-1.5 rounded-lg bg-[#15152d] text-[#6b6b9a] hover:text-[#a78bfa] hover:bg-[rgba(124,92,252,0.1)] transition-all border border-white/5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button onclick="scrollRow('${sectionId}','right')" class="p-1.5 rounded-lg bg-[#15152d] text-[#6b6b9a] hover:text-[#a78bfa] hover:bg-[rgba(124,92,252,0.1)] transition-all border border-white/5">
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
    `<button onclick="heroGoTo(${i})" class="transition-all duration-300 rounded-full ${i === heroIndex ? 'w-6 h-1.5 bg-[#7c5cfc]' : 'w-1.5 h-1.5 bg-white/20 hover:bg-white/40'}"></button>`
  ).join('');

  const favBtn = isFavorite(m.id)
    ? `<button id="hero-fav-btn" onclick="toggleFavorite(${m.id},this)" class="w-11 h-11 rounded-xl bg-[rgba(124,92,252,0.15)] backdrop-blur-sm flex items-center justify-center text-[#7c5cfc] border border-[rgba(124,92,252,0.3)] transition-all" title="Remove from Favorites"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>`
    : `<button id="hero-fav-btn" onclick="toggleFavorite(${m.id},this)" class="w-11 h-11 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white/60 hover:text-[#a78bfa] border border-white/10 transition-all" title="Add to Favorites"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>`;

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
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[rgba(124,92,252,0.15)] text-[#7c5cfc] border border-[rgba(124,92,252,0.25)]">
              🎬 Featured
            </span>
            <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-[#15152d]/80 text-gray-400 backdrop-blur-sm">${m.genre}</span>
            <div class="flex items-center gap-0.5">
              <svg class="w-3 h-3 text-[#7c5cfc] star-glow" fill="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <span class="text-[11px] font-bold text-[#7c5cfc]">${m.rating}</span>
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
            ${favBtn}
          </div>
        </div>
      </div>
    </div>
    <div class="absolute bottom-6 right-4 sm:right-8 z-20 flex items-center gap-2.5">
      <button onclick="heroPrev()" class="p-2 rounded-lg bg-black/40 backdrop-blur-sm border border-white/10 text-white/60 hover:text-[#a78bfa] hover:border-[rgba(124,92,252,0.3)] transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      </button>
      <div class="flex items-center gap-1">${dots}</div>
      <button onclick="heroNext()" class="p-2 rounded-lg bg-black/40 backdrop-blur-sm border border-white/10 text-white/60 hover:text-[#a78bfa] hover:border-[rgba(124,92,252,0.3)] transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>
  `;
}

function heroNext() { heroIndex = (heroIndex + 1) % heroMovies.length; renderHero(); resetHeroInterval(); }
function heroPrev() { heroIndex = (heroIndex - 1 + heroMovies.length) % heroMovies.length; renderHero(); resetHeroInterval(); }
function heroGoTo(i) { heroIndex = i; renderHero(); resetHeroInterval(); }
function resetHeroInterval() { clearInterval(heroInterval); heroInterval = setInterval(heroNext, 8000); }

// ─── Continue Watching (from localStorage) ───────────────
function loadContinueWatching() {
  const cw = document.getElementById('continue-watching');
  if (!cw) return;
  const hist = getWatchHistory();
  const section = document.getElementById('continue-watching-section');

  if (!hist.length) {
    if (section) section.style.display = 'none';
    return;
  }

  if (section) section.style.display = '';
  cw.innerHTML = hist.slice(0, 8).map((m, i) => `
    <a href="/watch/${m.slug}" class="flex-shrink-0 w-[260px] bg-[#0f0f22] rounded-xl overflow-hidden border border-white/5 hover:border-[rgba(124,92,252,0.2)] transition-all group">
      <div class="relative h-32 overflow-hidden">
        <img src="${m.backdrop_url || m.poster_url}" alt="${m.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f22] to-transparent"></div>
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <div class="w-10 h-10 rounded-full bg-[#7c5cfc] flex items-center justify-center shadow-lg">
            <svg class="w-4 h-4 text-[#06060f] ml-0.5" fill="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-[#1c1c38]">
          <div class="h-full bg-[#7c5cfc] rounded-r" style="width:${Math.min(90, 20 + i * 12)}%"></div>
        </div>
      </div>
      <div class="p-3">
        <h4 class="text-xs font-semibold text-white truncate">${m.title}</h4>
        <p class="text-[10px] text-[#6b6b9a]">${m.genre} • ${m.year}</p>
      </div>
    </a>
  `).join('');
}

// ─── Video Player (YouTube Embed) ────────────────────────
const YOUTUBE_VIDEO_ID = 'NvT7aGbAP14';

function initVideoPlayer() {
  // If there's a native video element, replace it with YouTube
  const container = document.getElementById('video-container');
  if (!container) return;

  // Record to watch history
  const movieData = window.CURRENT_MOVIE || {};
  if (movieData && movieData.id) recordWatch(movieData);

  // Build YouTube embed (no controls hidden, using YouTube's own player)
  container.innerHTML = `
    <div class="relative w-full h-screen bg-black flex items-center justify-center">
      <iframe
        id="yt-player"
        src="https://www.youtube.com/embed/${YOUTUBE_VIDEO_ID}?autoplay=1&rel=0&modestbranding=1&color=white"
        class="w-full h-full"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
        allowfullscreen
        frameborder="0">
      </iframe>
    </div>
  `;

  // Top bar controls (back button / title)
  const topBar = document.getElementById('top-bar');
  if (topBar) topBar.style.zIndex = '40';

  // Keyboard shortcut: F for fullscreen
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') window.history.back();
  });
}

// ─── Browse Page ────────────────────────────────────────
async function initBrowsePage() {
  const grid = document.getElementById('movie-grid');
  const countEl = document.getElementById('results-count');
  if (!grid) return;

  const params = new URLSearchParams(window.location.search);
  const search = params.get('search') || '';
  const genre = params.get('genre') || 'All';

  // Mark active genre button
  document.querySelectorAll('.genre-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.genre === genre);
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
        <p class="text-xs text-[#6b6b9a] mb-4">Try different keywords or filters</p>
        <a href="/browse" class="btn-lime px-5 py-2 rounded-xl text-xs font-bold inline-block">Clear Filters</a>
      </div>`;
  } else {
    grid.innerHTML = movies.map((m, i) => `
      <div class="animate-fade-in-up" style="animation-delay:${i * 40}ms">${createMovieCard(m, i)}</div>
    `).join('');
  }
  if (typeof lucide !== 'undefined') lucide.createIcons();
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

// ─── Favorites Page ──────────────────────────────────────
async function initFavoritesPage() {
  const grid = document.getElementById('favorites-grid');
  if (!grid) return;

  const favIds = getFavorites();
  if (!favIds.length) {
    grid.innerHTML = `
      <div class="col-span-full text-center py-16">
        <div class="w-16 h-16 rounded-2xl bg-[rgba(124,92,252,0.1)] flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-[#7c5cfc]" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-white mb-1">No favorites yet</h3>
        <p class="text-xs text-[#6b6b9a] mb-4">Click the ❤️ on any movie to save it here</p>
        <a href="/browse" class="btn-lime px-5 py-2 rounded-xl text-xs font-bold inline-block">Browse Movies</a>
      </div>`;
    return;
  }

  // Fetch all movies then filter by saved IDs
  const allMovies = await apiFetch('/movies');
  const favMovies = allMovies.filter(m => favIds.includes(Number(m.id)));

  if (!favMovies.length) {
    grid.innerHTML = `<div class="col-span-full text-center py-10 text-[#6b6b9a] text-sm">Your favorites will appear here.</div>`;
    return;
  }

  grid.innerHTML = favMovies.map((m, i) => `
    <div class="animate-fade-in-up" style="animation-delay:${i * 40}ms">${createMovieCard(m, i)}</div>
  `).join('');
  if (typeof lucide !== 'undefined') lucide.createIcons();
}

// ─── Collections Page ────────────────────────────────────
async function initCollectionsPage() {
  const grid = document.getElementById('collections-grid');
  if (!grid) return;

  const colIds = getCollections();
  if (!colIds.length) {
    grid.innerHTML = `
      <div class="col-span-full text-center py-16">
        <div class="w-16 h-16 rounded-2xl bg-[rgba(124,92,252,0.1)] flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-[#7c5cfc]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-white mb-1">No bookmarks yet</h3>
        <p class="text-xs text-[#6b6b9a] mb-4">Bookmark movies to watch later</p>
        <a href="/browse" class="btn-lime px-5 py-2 rounded-xl text-xs font-bold inline-block">Browse Movies</a>
      </div>`;
    return;
  }

  const allMovies = await apiFetch('/movies');
  const colMovies = allMovies.filter(m => colIds.includes(Number(m.id)));

  grid.innerHTML = colMovies.map((m, i) => `
    <div class="animate-fade-in-up" style="animation-delay:${i * 40}ms">${createMovieCard(m, i)}</div>
  `).join('');
  if (typeof lucide !== 'undefined') lucide.createIcons();
}

// ─── Settings ────────────────────────────────────────────
function initSettings() {
  const settings = lsGetObj(LS_SETTINGS, { notifications: true, autoplay: true, subtitles: false });

  // Apply stored toggle states
  const toggleMap = {
    'toggle-notifications': 'notifications',
    'toggle-autoplay': 'autoplay',
    'toggle-subtitles': 'subtitles',
  };
  Object.entries(toggleMap).forEach(([elId, key]) => {
    const el = document.getElementById(elId);
    if (!el) return;
    el.checked = !!settings[key];
    el.addEventListener('change', () => {
      settings[key] = el.checked;
      lsSet(LS_SETTINGS, settings);
      showToast(`${key.charAt(0).toUpperCase() + key.slice(1)} ${el.checked ? 'enabled' : 'disabled'}`);
    });
  });

  // Clear history button
  const clearBtn = document.getElementById('clear-history-btn');
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      if (confirm('Clear your entire watch history?')) {
        lsSet(LS_WATCH_HIST, []);
        showToast('Watch history cleared');
      }
    });
  }

  // Clear favorites button
  const clearFavBtn = document.getElementById('clear-favorites-btn');
  if (clearFavBtn) {
    clearFavBtn.addEventListener('click', () => {
      if (confirm('Clear all favorites?')) {
        lsSet(LS_FAVORITES, []);
        showToast('Favorites cleared');
      }
    });
  }

  // Change password form
  const pwForm = document.getElementById('change-password-form');
  if (pwForm) {
    pwForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const currentPw = document.getElementById('current-password')?.value;
      const newPw = document.getElementById('new-password')?.value;
      const confirmPw = document.getElementById('confirm-password')?.value;

      if (!currentPw || !newPw || !confirmPw) { showToast('Please fill all fields', 'error'); return; }
      if (newPw !== confirmPw) { showToast('Passwords do not match', 'error'); return; }
      if (newPw.length < 8) { showToast('Password must be at least 8 characters', 'error'); return; }

      // POST to backend
      try {
        const res = await fetch('/api/auth/change-password', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ current_password: currentPw, new_password: newPw }),
        });
        const json = await res.json();
        if (res.ok) { showToast('✅ Password changed successfully'); pwForm.reset(); }
        else { showToast(json.message || 'Failed to change password', 'error'); }
      } catch {
        showToast('Could not connect to server', 'error');
      }
    });
  }
}

// ─── Utility: format seconds ─────────────────────────────
function fmtTime(t) {
  if (isNaN(t)) return '0:00';
  return `${Math.floor(t / 60)}:${Math.floor(t % 60).toString().padStart(2, '0')}`;
}

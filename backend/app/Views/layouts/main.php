<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="<?= esc($metaDescription ?? 'CineVerse - Your ultimate movie streaming platform.') ?>" />
    <meta name="theme-color" content="#0d0d0d" />
    <title><?= esc($title ?? 'CineVerse') ?> - CineVerse</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        lime: { DEFAULT: '#c8ff00', dark: '#a0cc00', light: '#d4ff33' },
                        dark: { DEFAULT: '#0d0d0d', 100: '#141414', 200: '#1a1a1a', 300: '#222222', 400: '#2a2a2a', 500: '#333333' },
                        gold: '#fbbf24',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link rel="stylesheet" href="/css/custom.css" />
</head>

<body class="font-sans bg-dark text-gray-100 overflow-x-hidden antialiased">

    <?php if (!isset($hideNav) || !$hideNav): ?>

        <!-- ===== LEFT SIDEBAR ===== -->
        <aside
            class="fixed left-0 top-0 bottom-0 w-[70px] bg-dark-100 border-r border-white/5 z-50 flex flex-col items-center py-6 gap-2 hidden lg:flex">
            <!-- Logo -->
            <a href="/" class="mb-6">
                <div class="w-10 h-10 rounded-xl bg-lime flex items-center justify-center">
                    <i data-lucide="tv" class="w-5 h-5 text-dark"></i>
                </div>
            </a>

            <!-- Nav Icons -->
            <a href="/"
                class="sidebar-item w-11 h-11 rounded-xl flex items-center justify-center text-gray-500 <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>"
                title="Home">
                <i data-lucide="home" class="w-5 h-5"></i>
            </a>
            <a href="/browse"
                class="sidebar-item w-11 h-11 rounded-xl flex items-center justify-center text-gray-500 <?= ($activePage ?? '') === 'browse' ? 'active' : '' ?>"
                title="Browse">
                <i data-lucide="compass" class="w-5 h-5"></i>
            </a>
            <a href="/favorites"
                class="sidebar-item w-11 h-11 rounded-xl flex items-center justify-center text-gray-500 <?= ($activePage ?? '') === 'favorites' ? 'active' : '' ?>"
                title="Favorites">
                <i data-lucide="heart" class="w-5 h-5"></i>
            </a>
            <a href="/collections"
                class="sidebar-item w-11 h-11 rounded-xl flex items-center justify-center text-gray-500 <?= ($activePage ?? '') === 'collections' ? 'active' : '' ?>"
                title="Collections">
                <i data-lucide="bookmark" class="w-5 h-5"></i>
            </a>

            <div class="flex-1"></div>

            <!-- Bottom Icons -->
            <?php if (session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
                <a href="/admin"
                    class="sidebar-item w-11 h-11 rounded-xl flex items-center justify-center text-gray-500 <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"
                    title="Admin Panel">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                </a>
            <?php endif; ?>
            <a href="/settings"
                class="sidebar-item w-11 h-11 rounded-xl flex items-center justify-center text-gray-500 <?= ($activePage ?? '') === 'settings' ? 'active' : '' ?>"
                title="Settings">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </a>
        </aside>

        <!-- ===== TOP NAVBAR ===== -->
        <nav id="main-navbar" class="fixed top-0 right-0 left-0 lg:left-[70px] z-40 transition-all duration-300">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between gap-4">

                    <!-- Mobile Logo -->
                    <a href="/" class="lg:hidden flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-lime flex items-center justify-center">
                            <i data-lucide="tv" class="w-4 h-4 text-dark"></i>
                        </div>
                        <span class="font-bold font-display text-white">CineVerse</span>
                    </a>

                    <!-- Search Bar -->
                    <form onsubmit="handleNavSearch(event)" class="hidden sm:flex items-center flex-1 max-w-md">
                        <div class="relative w-full">
                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
                            <input id="nav-search-input" type="text" placeholder="Find movie or show"
                                class="form-input w-full rounded-xl pl-10 pr-4 py-2.5 text-sm" />
                        </div>
                    </form>

                    <!-- Category Tabs -->
                    <div class="hidden md:flex items-center gap-2">
                        <a href="/browse"
                            class="cat-tab px-4 py-2 rounded-full text-xs font-medium text-gray-400 <?= ($activePage ?? '') === 'browse' ? 'active' : '' ?>">Movie</a>
                        <a href="/browse?genre=Animation"
                            class="cat-tab px-4 py-2 rounded-full text-xs font-medium text-gray-400">Animation</a>
                        <a href="/browse?genre=Sci-Fi"
                            class="cat-tab px-4 py-2 rounded-full text-xs font-medium text-gray-400">Sci-Fi</a>
                        <a href="/browse?genre=Drama"
                            class="cat-tab px-4 py-2 rounded-full text-xs font-medium text-gray-400">Drama</a>
                    </div>

                    <!-- Right Side: User Profile / Auth -->
                    <div class="flex items-center gap-3">
                        <?php if (session()->get('isLoggedIn')): ?>
                            <!-- Notification -->
                            <button
                                class="hidden sm:flex w-9 h-9 rounded-xl bg-dark-200 items-center justify-center text-gray-500 hover:text-lime transition-colors relative border border-white/5">
                                <i data-lucide="bell" class="w-4 h-4"></i>
                                <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-lime rounded-full"></span>
                            </button>

                            <!-- Profile Dropdown -->
                            <div class="relative" id="profile-dropdown-wrapper">
                                <button onclick="toggleProfileMenu()"
                                    class="flex items-center gap-2 px-2 py-1.5 rounded-xl hover:bg-dark-200 transition-all">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-lime to-green-500 flex items-center justify-center text-dark font-bold text-xs">
                                        <?= strtoupper(substr(session()->get('userName') ?? 'U', 0, 1)) ?>
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="text-xs font-semibold text-white leading-tight">
                                            <?= esc(session()->get('userName')) ?>
                                        </p>
                                        <p class="text-[10px] text-gray-500"><?= esc(session()->get('userEmail')) ?></p>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-3 h-3 text-gray-500 hidden sm:block"></i>
                                </button>

                                <div id="profile-dropdown"
                                    class="hidden absolute right-0 top-full mt-2 w-48 bg-dark-200 rounded-xl border border-white/5 shadow-2xl overflow-hidden z-50">
                                    <div class="p-3 border-b border-white/5">
                                        <p class="text-xs font-semibold text-white"><?= esc(session()->get('userName')) ?></p>
                                        <p class="text-[10px] text-gray-500 capitalize"><?= esc(session()->get('role')) ?>
                                            account</p>
                                    </div>
                                    <?php if (session()->get('role') === 'admin'): ?>
                                        <a href="/admin"
                                            class="flex items-center gap-2 px-3 py-2.5 text-xs text-gray-400 hover:bg-dark-300 hover:text-lime transition-all">
                                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Admin Dashboard
                                        </a>
                                    <?php endif; ?>
                                    <a href="/settings"
                                        class="flex items-center gap-2 px-3 py-2.5 text-xs text-gray-400 hover:bg-dark-300 hover:text-white transition-all">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i> Profile
                                    </a>
                                    <a href="/logout"
                                        class="flex items-center gap-2 px-3 py-2.5 text-xs text-red-400 hover:bg-dark-300 hover:text-red-300 transition-all">
                                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Logout
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="/login"
                                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-white bg-dark-200 border border-white/5 hover:border-lime/30 transition-all">
                                <i data-lucide="log-in" class="w-4 h-4"></i>
                                <span class="hidden sm:inline">Login</span>
                            </a>
                            <a href="/register"
                                class="btn-lime px-4 py-2 rounded-xl text-sm font-semibold hidden sm:flex items-center gap-1">
                                <i data-lucide="user-plus" class="w-4 h-4"></i> Sign Up
                            </a>
                        <?php endif; ?>

                        <!-- Mobile Menu -->
                        <button onclick="toggleMobileMenu()"
                            class="lg:hidden p-2 rounded-xl text-gray-400 hover:text-white hover:bg-dark-200 transition-all">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu"
                class="hidden lg:hidden bg-dark-100 border-t border-white/5 mx-4 rounded-2xl mb-4 overflow-hidden">
                <div class="p-3 space-y-1">
                    <form onsubmit="handleNavSearch(event)" class="sm:hidden mb-3">
                        <div class="relative">
                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
                            <input type="text" id="mobile-search-input" placeholder="Find movie or show"
                                class="form-input w-full rounded-xl pl-10 pr-4 py-2.5 text-sm" />
                        </div>
                    </form>
                    <a href="/"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-400 hover:bg-dark-300 hover:text-white transition-all"><i
                            data-lucide="home" class="w-4 h-4"></i> Home</a>
                    <a href="/browse"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-400 hover:bg-dark-300 hover:text-white transition-all"><i
                            data-lucide="compass" class="w-4 h-4"></i> Browse</a>
                    <a href="/favorites"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-400 hover:bg-dark-300 hover:text-white transition-all"><i
                            data-lucide="heart" class="w-4 h-4"></i> Favorites</a>
                    <a href="/collections"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-400 hover:bg-dark-300 hover:text-white transition-all"><i
                            data-lucide="bookmark" class="w-4 h-4"></i> Collections</a>
                    <a href="/settings"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-400 hover:bg-dark-300 hover:text-white transition-all"><i
                            data-lucide="settings" class="w-4 h-4"></i> Settings</a>
                    <?php if (session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
                        <a href="/admin"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-lime hover:bg-dark-300 transition-all"><i
                                data-lucide="shield" class="w-4 h-4"></i> Admin Panel</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="fixed top-20 right-4 lg:right-8 z-[60] toast toast-success bg-dark-200 border border-white/5 rounded-xl px-5 py-3 shadow-2xl max-w-sm"
            id="toast-msg">
            <p class="text-sm text-white font-medium"><?= esc(session()->getFlashdata('success')) ?></p>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="fixed top-20 right-4 lg:right-8 z-[60] toast toast-error bg-dark-200 border border-white/5 rounded-xl px-5 py-3 shadow-2xl max-w-sm"
            id="toast-msg">
            <p class="text-sm text-red-400 font-medium"><?= esc(session()->getFlashdata('error')) ?></p>
        </div>
    <?php endif; ?>

    <!-- MAIN CONTENT -->
    <main class="<?= (!isset($hideNav) || !$hideNav) ? 'lg:ml-[70px] pt-[72px]' : '' ?>">
        <?= $this->renderSection('content') ?>
    </main>

    <?php if (!isset($hideNav) || !$hideNav): ?>
        <!-- ===== FOOTER ===== -->
        <footer class="lg:ml-[70px] border-t border-white/5 bg-dark-100/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="col-span-2 md:col-span-1">
                        <a href="/" class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-lime flex items-center justify-center">
                                <i data-lucide="tv" class="w-4 h-4 text-dark"></i>
                            </div>
                            <span class="font-bold font-display text-white">CineVerse</span>
                        </a>
                        <p class="text-xs text-gray-600 leading-relaxed mb-4">Your ultimate destination for premium
                            streaming. Discover and watch cinema like never before.</p>
                        <div class="flex gap-2">
                            <a href="#"
                                class="w-8 h-8 rounded-lg bg-dark-300 flex items-center justify-center text-gray-600 hover:bg-lime/10 hover:text-lime transition-all"><i
                                    data-lucide="twitter" class="w-3.5 h-3.5"></i></a>
                            <a href="#"
                                class="w-8 h-8 rounded-lg bg-dark-300 flex items-center justify-center text-gray-600 hover:bg-lime/10 hover:text-lime transition-all"><i
                                    data-lucide="instagram" class="w-3.5 h-3.5"></i></a>
                            <a href="#"
                                class="w-8 h-8 rounded-lg bg-dark-300 flex items-center justify-center text-gray-600 hover:bg-lime/10 hover:text-lime transition-all"><i
                                    data-lucide="github" class="w-3.5 h-3.5"></i></a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-4">Browse</h4>
                        <ul class="space-y-2">
                            <li><a href="/browse?genre=Action"
                                    class="text-xs text-gray-600 hover:text-lime transition-colors">Action</a></li>
                            <li><a href="/browse?genre=Sci-Fi"
                                    class="text-xs text-gray-600 hover:text-lime transition-colors">Sci-Fi</a></li>
                            <li><a href="/browse?genre=Drama"
                                    class="text-xs text-gray-600 hover:text-lime transition-colors">Drama</a></li>
                            <li><a href="/browse?genre=Thriller"
                                    class="text-xs text-gray-600 hover:text-lime transition-colors">Thriller</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-4">Company</h4>
                        <ul class="space-y-2">
                            <li><a href="/about" class="text-xs text-gray-600 hover:text-lime transition-colors">About</a>
                            </li>
                            <li><a href="/careers"
                                    class="text-xs text-gray-600 hover:text-lime transition-colors">Careers</a></li>
                            <li><a href="/press" class="text-xs text-gray-600 hover:text-lime transition-colors">Press</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-4">Support</h4>
                        <ul class="space-y-2">
                            <li><a href="/help" class="text-xs text-gray-600 hover:text-lime transition-colors">Help
                                    Center</a>
                            </li>
                            <li><a href="/contact"
                                    class="text-xs text-gray-600 hover:text-lime transition-colors">Contact</a></li>
                            <li><a href="/privacy"
                                    class="text-xs text-gray-600 hover:text-lime transition-colors">Privacy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-[10px] text-gray-600">© 2026 CineVerse. All rights reserved.</p>
                    <p class="text-[10px] text-gray-600 flex items-center gap-1">Built with CI4 + Tailwind CSS</p>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <script src="/js/app.js"></script>
    <script>
        lucide.createIcons();
        // Auto-dismiss toast
        const toast = document.getElementById('toast-msg');
        if (toast) setTimeout(() => toast.style.display = 'none', 4000);
    </script>
</body>

</html>
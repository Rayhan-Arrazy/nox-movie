<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - CineVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { lime: { DEFAULT: '#c8ff00', dark: '#a0cc00' }, dark: { DEFAULT: '#0d0d0d', 100: '#141414', 200: '#1a1a1a', 300: '#222' } },
                    fontFamily: { sans: ['Inter', 'sans-serif'], display: ['Space Grotesk', 'sans-serif'] },
                }
            }
        }
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link rel="stylesheet" href="/css/custom.css" />
</head>

<body class="font-sans bg-dark text-gray-100 antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-lime/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-lime/3 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md animate-fade-in-up">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <div class="w-11 h-11 rounded-xl bg-lime flex items-center justify-center">
                    <i data-lucide="tv" class="w-5 h-5 text-dark"></i>
                </div>
                <span class="text-2xl font-bold font-display text-white">CineVerse</span>
            </a>
            <p class="mt-3 text-sm text-gray-600">Welcome back! Sign in to your account.</p>
        </div>

        <!-- Login Form -->
        <div class="bg-dark-200 rounded-2xl p-6 sm:p-8 border border-white/5 shadow-2xl">

            <!-- Errors -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-sm text-red-400">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 px-4 py-3 rounded-xl bg-lime/10 border border-lime/20 text-sm text-lime">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <p class="text-xs text-red-400">
                            <?= esc($err) ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="/login" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                    <div class="relative">
                        <i data-lucide="mail"
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600"></i>
                        <input type="email" name="email" required value="<?= old('email') ?>"
                            placeholder="you@example.com"
                            class="form-input w-full rounded-xl pl-10 pr-4 py-3 text-sm" />
                    </div>
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <i data-lucide="lock"
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600"></i>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="form-input w-full rounded-xl pl-10 pr-4 py-3 text-sm" />
                    </div>
                </div>

                <button type="submit"
                    class="btn-lime w-full py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-600">
                    Don't have an account?
                    <a href="/register" class="text-lime font-semibold hover:underline ml-1">Sign Up</a>
                </p>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-4 bg-dark-200/50 rounded-xl p-4 border border-white/5 text-center">
            <p class="text-[10px] text-gray-600 uppercase tracking-wider font-semibold mb-2">Demo Accounts</p>
            <div class="flex gap-3 justify-center">
                <button onclick="fillLogin('admin@cineverse.com','admin123')"
                    class="text-[11px] text-lime bg-lime/10 px-3 py-1.5 rounded-lg border border-lime/20 hover:bg-lime/20 transition-all font-medium">Admin</button>
                <button onclick="fillLogin('john@example.com','client123')"
                    class="text-[11px] text-gray-400 bg-dark-300 px-3 py-1.5 rounded-lg border border-white/5 hover:bg-dark-400 transition-all font-medium">Client</button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        function fillLogin(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
        }
    </script>
</body>

</html>
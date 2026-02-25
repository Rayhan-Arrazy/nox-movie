<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — NOX Movie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: { DEFAULT: '#7c5cfc', dark: '#5a3de0', light: '#a78bfa' },
                        dark: { DEFAULT: '#06060f', 100: '#0a0a18', 200: '#0f0f22', 300: '#15152d', 400: '#1c1c38' },
                    },
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

<body class="font-sans bg-[#06060f] text-gray-100 antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Ambient Night Glow -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="nox-glow-blob nox-glow-violet absolute top-0 right-0 w-96 h-96"></div>
        <div class="nox-glow-blob nox-glow-indigo absolute bottom-0 left-0 w-80 h-80"></div>
        <div class="nox-glow-blob nox-glow-blue absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64"
            style="opacity:0.08"></div>
    </div>

    <div class="relative w-full max-w-md animate-fade-in-up">
        <!-- NOX Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3">
                <div
                    class="w-12 h-12 rounded-2xl bg-[#7c5cfc] flex items-center justify-center shadow-lg shadow-[rgba(124,92,252,0.4)]">
                    <i data-lucide="moon" class="w-6 h-6 text-white"></i>
                </div>
                <span class="nox-wordmark text-2xl tracking-widest">NOX</span>
            </a>
            <p class="mt-3 text-sm text-[#6b6b9a]">Welcome back. The night awaits.</p>
        </div>

        <!-- Login Form -->
        <div class="bg-[#0f0f22] rounded-2xl p-6 sm:p-8 border border-white/5 shadow-2xl">

            <!-- Errors -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-sm text-red-400">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div
                    class="mb-4 px-4 py-3 rounded-xl bg-[rgba(124,92,252,0.10)] border border-[rgba(124,92,252,0.25)] text-sm text-[#a78bfa]">
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
                    <label
                        class="block text-xs font-semibold text-[#6b6b9a] uppercase tracking-wider mb-2">Email</label>
                    <div class="relative">
                        <i data-lucide="mail"
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#3d3d60]"></i>
                        <input type="email" name="email" required value="<?= old('email') ?>"
                            placeholder="you@example.com"
                            class="form-input w-full rounded-xl pl-10 pr-4 py-3 text-sm" />
                    </div>
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold text-[#6b6b9a] uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <i data-lucide="lock"
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#3d3d60]"></i>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="form-input w-full rounded-xl pl-10 pr-4 py-3 text-sm" />
                    </div>
                </div>

                <button type="submit"
                    class="btn-lime w-full py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Enter the Night
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-[#6b6b9a]">
                    Don't have an account?
                    <a href="/register" class="text-[#a78bfa] font-semibold hover:underline ml-1">Sign Up</a>
                </p>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-4 bg-[#0f0f22]/50 rounded-xl p-4 border border-white/5 text-center">
            <p class="text-[10px] text-[#6b6b9a] uppercase tracking-wider font-semibold mb-2">Demo Accounts</p>
            <div class="flex gap-3 justify-center">
                <button onclick="fillLogin('admin@cineverse.com','admin123')"
                    class="text-[11px] text-[#a78bfa] bg-[rgba(124,92,252,0.10)] px-3 py-1.5 rounded-lg border border-[rgba(124,92,252,0.25)] hover:bg-[rgba(124,92,252,0.20)] transition-all font-medium">Admin</button>
                <button onclick="fillLogin('john@example.com','client123')"
                    class="text-[11px] text-[#6b6b9a] bg-[#15152d] px-3 py-1.5 rounded-lg border border-white/5 hover:bg-[#1c1c38] transition-all font-medium">Client</button>
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
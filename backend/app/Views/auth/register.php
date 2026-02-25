<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register — NOX Movie</title>
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
        <div class="nox-glow-blob nox-glow-indigo absolute top-0 left-1/2 w-96 h-96 -translate-x-1/2"></div>
        <div class="nox-glow-blob nox-glow-violet absolute bottom-0 right-0 w-80 h-80"></div>
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
            <p class="mt-3 text-sm text-[#6b6b9a]">Create your account and step into the night.</p>
        </div>

        <!-- Register Form -->
        <div class="bg-[#0f0f22] rounded-2xl p-6 sm:p-8 border border-white/5 shadow-2xl">

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <p class="text-xs text-red-400">
                            <?= esc($err) ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-[#6b6b9a] uppercase tracking-wider mb-2">Full
                        Name</label>
                    <div class="relative">
                        <i data-lucide="user"
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#3d3d60]"></i>
                        <input type="text" name="name" required value="<?= old('name') ?>" placeholder="Your full name"
                            class="form-input w-full rounded-xl pl-10 pr-4 py-3 text-sm" />
                    </div>
                </div>

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
                        <input type="password" name="password" required placeholder="Min 6 characters"
                            class="form-input w-full rounded-xl pl-10 pr-4 py-3 text-sm" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#6b6b9a] uppercase tracking-wider mb-2">Confirm
                        Password</label>
                    <div class="relative">
                        <i data-lucide="shield-check"
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#3d3d60]"></i>
                        <input type="password" name="confirm_password" required placeholder="Repeat your password"
                            class="form-input w-full rounded-xl pl-10 pr-4 py-3 text-sm" />
                    </div>
                </div>

                <button type="submit"
                    class="btn-lime w-full py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Join NOX
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-[#6b6b9a]">
                    Already have an account?
                    <a href="/login" class="text-[#a78bfa] font-semibold hover:underline ml-1">Sign In</a>
                </p>
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>

</html>
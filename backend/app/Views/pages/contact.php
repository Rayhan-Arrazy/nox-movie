<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <div class="w-14 h-14 rounded-2xl bg-lime/10 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="mail" class="w-7 h-7 text-lime"></i>
            </div>
            <h1 class="text-3xl font-black font-display text-white mb-3">Contact Us</h1>
            <p class="text-sm text-gray-500 max-w-md mx-auto">Have a question or feedback? We'd love to hear from you.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-dark-200 rounded-2xl p-5 border border-white/5 text-center animate-fade-in-up"
                style="animation-delay: 80ms">
                <i data-lucide="mail" class="w-5 h-5 text-lime mx-auto mb-3"></i>
                <h3 class="text-xs font-bold text-white mb-1">Email</h3>
                <p class="text-[11px] text-gray-500">support@cineverse.com</p>
            </div>
            <div class="bg-dark-200 rounded-2xl p-5 border border-white/5 text-center animate-fade-in-up"
                style="animation-delay: 160ms">
                <i data-lucide="map-pin" class="w-5 h-5 text-lime mx-auto mb-3"></i>
                <h3 class="text-xs font-bold text-white mb-1">Location</h3>
                <p class="text-[11px] text-gray-500">Jakarta, Indonesia</p>
            </div>
            <div class="bg-dark-200 rounded-2xl p-5 border border-white/5 text-center animate-fade-in-up"
                style="animation-delay: 240ms">
                <i data-lucide="clock" class="w-5 h-5 text-lime mx-auto mb-3"></i>
                <h3 class="text-xs font-bold text-white mb-1">Hours</h3>
                <p class="text-[11px] text-gray-500">Mon - Fri, 9AM - 5PM</p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-dark-200 rounded-2xl p-5 sm:p-7 border border-white/5 animate-fade-in-up"
            style="animation-delay: 320ms">
            <h2 class="text-sm font-semibold text-white flex items-center gap-2 mb-5">
                <i data-lucide="send" class="w-4 h-4 text-lime"></i> Send a Message
            </h2>
            <form
                onsubmit="event.preventDefault(); document.getElementById('contact-success').classList.remove('hidden'); this.reset();"
                class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Name</label>
                        <input type="text" required placeholder="Your name"
                            class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                        <input type="email" required placeholder="you@example.com"
                            class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                </div>
                <div>
                    <label
                        class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Subject</label>
                    <input type="text" required placeholder="How can we help?"
                        class="form-input w-full rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label
                        class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Message</label>
                    <textarea required rows="4" placeholder="Tell us more..."
                        class="form-input w-full rounded-xl px-4 py-2.5 text-sm resize-none"></textarea>
                </div>
                <button type="submit"
                    class="btn-lime px-6 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i> Send Message
                </button>
            </form>
            <div id="contact-success"
                class="hidden mt-4 px-4 py-3 rounded-xl bg-lime/10 border border-lime/20 text-sm text-lime">
                Thank you! Your message has been sent. We'll get back to you soon.
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
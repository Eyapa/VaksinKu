<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Masuk - VaksinKu</title>
    <!-- Tailwind CDN for the Stitch MCP generated design -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#1A56DB",
                        "primary-fixed": "#dbe1ff",
                        "primary-container": "#1a56db",
                        "success-green": "#0E9F6E",
                        "surface": "#faf8ff",
                        "surface-bright": "#faf8ff",
                        "surface-container-lowest": "#ffffff",
                        "border-light": "#E5E7EB",
                        "on-surface": "#191b23",
                        "on-surface-variant": "#434654",
                        "outline": "#737686",
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "gutter": "1.5rem",
                        "container-max": "1280px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-sm": ["Inter"],
                        "label-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "body-md": ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-surface-bright min-h-screen flex flex-col">
    <!-- Auth Layout Wrapper -->
    <main class="flex-grow flex items-center justify-center p-gutter lg:p-0">
        <div class="w-full max-w-container-max flex flex-col md:flex-row overflow-hidden rounded-xl shadow-2xl bg-surface-container-lowest min-h-[640px]">
            <!-- Left Panel: Branding & Illustration -->
            <div class="hidden md:flex md:w-5/12 bg-primary p-12 flex-col justify-between relative overflow-hidden">
                <!-- Decorative pattern -->
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <div class="absolute -top-24 -left-24 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-white rounded-full blur-3xl"></div>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-12">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[28px]" style="font-variation-settings: 'FILL' 1;">vaccines</span>
                        </div>
                        <span class="text-2xl font-bold text-white">VaksinKu</span>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-6 leading-tight">Layanan Kesehatan Desa Terpercaya.</h1>
                    <p class="text-lg text-white/80 max-w-sm">Lindungi keluarga Anda dengan akses mudah ke riwayat vaksinasi dan jadwal faskes terdekat.</p>
                </div>
                <!-- Illustration Placeholder Card -->
                <div class="relative z-10 mt-auto">
                    <div class="glass-card p-6 rounded-xl border border-white/20 shadow-lg">
                        <img alt="Health Service Illustration" class="rounded-lg w-full h-48 object-cover mb-4" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtKryFhlqST1kTHAIKo6B8U1REt-5WCyAGOV_56_0RCS3CSLySdmFLwoYcv8XsK2-ZAQ6sW0yvGb6flDOSpDBoBxSUgYZ9LF2H68TEbj3JV71FJNcZT_kmzb_cYZROe73B1vsHPbG1Qr2xBRSzp7m_n3jbmnwUjOUwnUQ7SrQMwGSRU7QA6O5gcCXDNJAYYk7v5SDc3dqcKu5zKfFL-J2gRg9n-fmER8Q0FPqDJYv5FUoHnGrZp1Gr198innB7pMX6N5X9JIyYda0g">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-success-green rounded-full animate-pulse"></div>
                            <span class="text-sm text-on-surface-variant">Data terenkripsi dan terlindungi.</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Panel: Login Form -->
            <div class="w-full md:w-7/12 p-8 md:p-16 flex flex-col justify-center bg-surface-container-lowest">
                <div class="max-w-md mx-auto w-full">
                    <div class="md:hidden flex items-center gap-2 mb-8">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">vaccines</span>
                        <span class="text-sm font-bold text-primary">VaksinKu</span>
                    </div>
                    <div class="mb-10">
                        <h2 class="text-2xl font-bold text-on-surface mb-2">Selamat Datang</h2>
                        <p class="text-base text-on-surface-variant">Silakan masuk untuk mengakses jadwal vaksinasi dan riwayat kesehatan keluarga Anda.</p>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-sm text-on-surface font-semibold" for="email">Email address</label>
                            <div class="relative group">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">email</span>
                                <input class="w-full pl-12 pr-4 py-3.5 bg-surface-bright border border-border-light rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-base" id="email" name="email" placeholder="email@example.com" required type="email">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm text-on-surface font-semibold" for="password">Password</label>
                            <div class="relative group">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">lock</span>
                                <input class="w-full pl-12 pr-4 py-3.5 bg-surface-bright border border-border-light rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-base" id="password" name="password" required type="password">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <button class="w-full bg-success-green hover:bg-[#0b8a5e] text-white py-4 px-6 rounded-lg font-semibold flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-lg shadow-success-green/20" type="submit">
                            <span class="material-symbols-outlined">login</span>
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('mousemove', (e) => {
            const amount = 20;
            const x = (e.clientX / window.innerWidth - 0.5) * amount;
            const y = (e.clientY / window.innerHeight - 0.5) * amount;
            
            const cards = document.querySelectorAll('.glass-card');
            cards.forEach(card => {
                card.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin — Vantara Production</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#090c14] text-gray-100 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-[#c59d5f] via-[#dfc48e] to-[#ab8048] p-[1px] shadow-2xl mb-4">
                <div class="w-full h-full bg-[#0b0e14] rounded-2xl flex items-center justify-center">
                    <span class="font-serif text-2xl font-bold text-[#dfc48e]">V</span>
                </div>
            </div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-white">Vantara Production</h1>
            <p class="text-xs text-[#c59d5f] uppercase tracking-[0.25em] font-semibold mt-1">Portal Otentikasi Admin</p>
        </div>

        <!-- Login Card -->
        <div class="p-8 rounded-2xl bg-[#111722] border border-[#c59d5f]/30 shadow-2xl space-y-6">

            @if (session('error'))
                <div class="p-4 bg-rose-950/70 border border-rose-500/50 rounded-xl text-rose-200 text-xs">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="p-4 bg-emerald-950/70 border border-emerald-500/50 rounded-xl text-emerald-200 text-xs">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                        Alamat Email Admin
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="admin@vantara.id"
                           required
                           autofocus
                           class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none transition-colors">
                    @error('email')
                        <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                        Kata Sandi
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="••••••••"
                           required
                           class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white focus:outline-none transition-colors">
                    @error('password')
                        <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-gray-300 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-[#090c14] border-[#242f44] text-[#c59d5f] focus:ring-0">
                        <span>Ingat Sesi Saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black font-bold text-xs uppercase tracking-[0.15em] rounded-lg shadow-xl transition-all hover:scale-[1.02]">
                    Masuk ke Dashboard Admin &rarr;
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-white transition-colors">
                &larr; Kembali ke Website Utama
            </a>
        </div>

    </div>

</body>
</html>

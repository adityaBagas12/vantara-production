<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vantara Production — Rental Sound System, Lighting & Entertainment')</title>
    <meta name="description" content="@yield('meta_description', 'Sewa sound system, lighting, videobooth 360, dan band wedding untuk pernikahan, khitanan, gathering, dan berbagai acara spesial Anda.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#0b0e14] text-[#e2e8f0] font-sans antialiased selection:bg-[#c59d5f] selection:text-black">

    <!-- Top Contact Bar (Inspired by JWS Wedding Header) -->
    <div class="bg-[#07090e] border-b border-[#242f44]/40 text-xs text-gray-400 py-2.5 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-1.5 hover:text-[#c59d5f] transition-colors">
                    <svg class="w-3.5 h-3.5 text-[#c59d5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    WhatsApp: +62 822-8242-2317
                </span>
                <span class="flex items-center gap-1.5 hover:text-[#c59d5f] transition-colors">
                    <svg class="w-3.5 h-3.5 text-[#c59d5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    halo@vantara.id
                </span>
                <span class="flex items-center gap-1.5 text-gray-500">
                    <svg class="w-3.5 h-3.5 text-[#c59d5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Setiap Hari: 08:00 – 21:00 WIB
                </span>
            </div>
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5 text-[11px] tracking-wider uppercase bg-[#c59d5f]/10 text-[#d4ab63] px-2.5 py-0.5 rounded-full border border-[#c59d5f]/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Jadwal Masih Tersedia
                </span>
                <a href="{{ route('availability.index') }}" class="text-[#c59d5f] hover:underline font-medium text-xs">Cek Kalender Jadwal &rarr;</a>
                <a href="{{ route('admin.login') }}" class="text-gray-400 hover:text-[#c59d5f] font-medium text-xs flex items-center gap-1 border-l border-[#242f44] pl-4">
                    🔑 Login Admin
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header x-data="{ mobileMenuOpen: false, scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="scrolled ? 'bg-[#090c14]/95 backdrop-blur-md shadow-2xl border-b border-[#242f44]/70 py-3.5' : 'bg-transparent border-b border-white/10 py-5'"
            class="sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                
                <!-- Brand Logo (Regal Serif & Gold Monogram) -->
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#c59d5f] via-[#dfc48e] to-[#ab8048] p-[1px] shadow-lg shadow-[#c59d5f]/10">
                        <div class="w-full h-full bg-[#0b0e14] rounded-lg flex items-center justify-center group-hover:bg-[#121824] transition-colors">
                            <span class="font-serif text-xl font-bold tracking-tighter text-[#dfc48e]">V</span>
                        </div>
                    </div>
                    <div>
                        <span class="font-serif text-2xl font-bold tracking-[0.2em] text-white block leading-none">VANTARA</span>
                        <span class="text-[9px] tracking-[0.35em] text-[#c59d5f] uppercase block font-semibold mt-1">Production</span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden lg:flex items-center gap-8 text-sm font-medium tracking-wide">
                    <a href="{{ route('home') }}" 
                       class="{{ request()->routeIs('home') ? 'text-[#c59d5f]' : 'text-gray-300 hover:text-white' }} transition-colors uppercase text-xs tracking-wider">
                        Beranda
                    </a>
                    <a href="{{ route('packages.catalog') }}" 
                       class="{{ request()->routeIs('packages.catalog') ? 'text-[#c59d5f]' : 'text-gray-300 hover:text-white' }} transition-colors uppercase text-xs tracking-wider">
                        Katalog Paket
                    </a>
                    <a href="{{ route('cart.index') }}" 
                       class="{{ request()->routeIs('cart.*') ? 'text-[#c59d5f]' : 'text-gray-300 hover:text-white' }} transition-colors uppercase text-xs tracking-wider flex items-center gap-1.5">
                        <span>Keranjang</span>
                        @if (count(session('cart', [])) > 0)
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-[#c59d5f] text-black rounded-full leading-none">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('home') }}#profil" 
                       class="text-gray-300 hover:text-white transition-colors uppercase text-xs tracking-wider">
                        Profil Usaha
                    </a>
                    <a href="{{ route('availability.index') }}" 
                       class="{{ request()->routeIs('availability.index') ? 'text-[#c59d5f] font-bold' : 'text-gray-300 hover:text-white' }} transition-colors uppercase text-xs tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#c59d5f]"></span>
                        Cek Tanggal
                    </a>
                </nav>

                <!-- Header Actions -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="https://wa.me/6282282422317?text=Halo%20Vantara%20Production,%20saya%20ingin%20konsultasi%20paket%20sound%20system%20dan%20entertainment%20untuk%20acara%20saya." 
                       target="_blank"
                       class="relative inline-flex items-center justify-center px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-black transition-all bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] rounded shadow-lg shadow-[#c59d5f]/20 hover:shadow-[#c59d5f]/40 hover:scale-[1.02] active:scale-[0.98]">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.983.536 1.78.814 2.791.814 3.18 0 5.767-2.586 5.767-5.766 0-3.18-2.587-5.766-5.767-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.741 0-3.376-.447-4.802-1.229l-5.198 1.36 1.385-5.064c-.879-1.488-1.385-3.228-1.385-5.067 0-5.514 4.486-10 10-10s10 4.486 10 10z"/></svg>
                            Konsultasi Cepat
                        </span>
                    </a>
                </div>

                <!-- Mobile Menu Toggle Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="lg:hidden p-2 text-gray-300 hover:text-white rounded-lg focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden bg-[#0e131d] border-b border-[#242f44] px-4 pt-4 pb-6 space-y-3 mt-3 shadow-2xl"
             style="display: none;">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-medium text-gray-200 hover:text-[#c59d5f]">Beranda</a>
            <a href="{{ route('packages.catalog') }}" class="block px-3 py-2 text-sm font-medium text-gray-200 hover:text-[#c59d5f]">Katalog Paket Layanan</a>
            <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-sm font-medium text-gray-200 hover:text-[#c59d5f] flex items-center justify-between">
                <span>Keranjang Belanja</span>
                @if (count(session('cart', [])) > 0)
                    <span class="px-2 py-0.5 text-xs font-bold bg-[#c59d5f] text-black rounded-full">
                        {{ count(session('cart', [])) }}
                    </span>
                @endif
            </a>
            <a href="{{ route('availability.index') }}" class="block px-3 py-2 text-sm font-medium text-[#c59d5f] font-semibold">Cek Tanggal</a>
            <a href="{{ route('home') }}#profil" @click="mobileMenuOpen = false" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white">Profil Usaha</a>
            <a href="{{ route('home') }}#keunggulan" @click="mobileMenuOpen = false" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white">Keunggulan Layanan</a>
            <div class="pt-4 border-t border-[#242f44] space-y-2">
                <a href="https://wa.me/6282282422317" target="_blank" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-[#c59d5f] text-black font-semibold rounded text-sm uppercase tracking-wider">
                    Konsultasi via WhatsApp
                </a>
                <a href="{{ route('admin.login') }}" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-[#141b28] text-gray-300 hover:text-white border border-[#242f44] rounded text-xs font-semibold uppercase tracking-wider">
                    🔑 Login Portal Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Main Dynamic Content -->
    <main>
        @yield('content')
    </main>

    <!-- Floating WhatsApp Concierge Button -->
    <div class="fixed bottom-6 right-6 z-50 flex items-center group">
        <a href="https://wa.me/6282282422317?text=Halo%20Vantara%20Production,%20saya%20ingin%20konsultasi%20paket%20layanan." 
           target="_blank" 
           class="relative flex items-center justify-center w-14 h-14 bg-[#25D366] text-white rounded-full shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300 group-hover:ring-4 group-hover:ring-[#25D366]/30">
            <span class="absolute inline-flex h-full w-full rounded-full bg-[#25D366] opacity-75 animate-ping"></span>
            <svg class="w-7 h-7 relative z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        </a>
    </div>

    <!-- Luxury Footer (Inspired by JWS Wedding Footer) -->
    <footer class="bg-[#06080d] border-t border-[#1e2538] pt-16 pb-12 text-sm text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">
                
                <!-- Col 1: Identity & Description (PRD Profil Usaha) -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#c59d5f] to-[#ab8048] flex items-center justify-center text-black font-serif font-bold text-xl">
                            V
                        </div>
                        <div>
                            <span class="font-serif text-2xl font-bold tracking-[0.2em] text-white block leading-none">VANTARA</span>
                            <span class="text-[9px] tracking-[0.35em] text-[#c59d5f] uppercase block font-semibold mt-1">Production</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed max-w-sm">
                        Vantara Production adalah usaha rental sound system dan peralatan entertainment yang melayani berbagai kebutuhan acara — dari akad nikah, pernikahan, khitanan, gathering korporat, hingga konser dan festival. Kami hadir antar-pasang di lokasi acara Anda.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="w-8 h-8 rounded-full bg-[#141b28] border border-[#242f44] flex items-center justify-center text-gray-400 hover:text-[#c59d5f] transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </span>
                        <span class="w-8 h-8 rounded-full bg-[#141b28] border border-[#242f44] flex items-center justify-center text-gray-400 hover:text-[#c59d5f] transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </span>
                        <span class="w-8 h-8 rounded-full bg-[#141b28] border border-[#242f44] flex items-center justify-center text-gray-400 hover:text-[#c59d5f] transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </span>
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h4 class="text-xs uppercase tracking-[0.2em] font-semibold text-[#c59d5f] mb-4">Navigasi Utama</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('packages.catalog') }}" class="hover:text-white transition-colors">Katalog Paket & Harga</a></li>
                        <li><a href="{{ route('availability.index') }}" class="hover:text-white transition-colors text-[#d4ab63] flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#c59d5f]"></span>
                            Cek Jadwal Tanggal
                        </a></li>
                        <li><a href="{{ route('home') }}#profil" class="hover:text-white transition-colors">Profil Usaha</a></li>

                        <li><a href="{{ route('admin.login') }}" class="text-[#c59d5f] hover:underline font-semibold flex items-center gap-1">🔒 Login Admin Vantara</a></li>
                    </ul>
                </div>

                <!-- Col 3: Layanan Kami -->
                <div>
                    <h4 class="text-xs uppercase tracking-[0.2em] font-semibold text-[#c59d5f] mb-4">Layanan Produksi</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="{{ route('packages.catalog', ['category' => 'Sound System']) }}" class="hover:text-white transition-colors">Rental Sound System</a></li>
                        <li><a href="{{ route('packages.catalog', ['category' => 'Lighting & Special Effect']) }}" class="hover:text-white transition-colors">Lighting & Special Effect</a></li>
                        <li><a href="{{ route('packages.catalog', ['category' => 'Videobooth 360']) }}" class="hover:text-white transition-colors">Videobooth 360°</a></li>
                        <li><a href="{{ route('packages.catalog', ['category' => 'Band Wedding']) }}" class="hover:text-white transition-colors">Band Wedding</a></li>
                        <li><a href="{{ route('packages.catalog') }}" class="hover:text-white transition-colors">Item Tambahan (Add-on)</a></li>
                    </ul>
                </div>

                <!-- Col 4: Kontak & Studio -->
                <div>
                    <h4 class="text-xs uppercase tracking-[0.2em] font-semibold text-[#c59d5f] mb-4">Studio & Kontak</h4>
                    <div class="space-y-3 text-xs leading-relaxed">
                        <p class="text-gray-300">
                            <strong class="text-white block">Area Layanan:</strong>
                            Melayani seluruh wilayah dan sekitarnya. Hubungi kami untuk info area coverage lebih lanjut.
                        </p>
                        <p class="text-gray-300">
                            <strong class="text-white block">Hubungi Langsung:</strong>
                            WhatsApp: +62 822-8242-2317<br>
                            Email: halo@vantara.id
                        </p>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Disclaimer -->
            <div class="pt-8 border-t border-[#1a2133] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400">
                <p>&copy; 2026 Vantara Production — Rental Sound System & Entertainment. Seluruh Hak Dilindungi.</p>
                <div class="flex items-center gap-6">

                    <a href="{{ route('availability.index') }}" class="text-[#c59d5f] hover:underline">Cek Jadwal Ketersediaan</a>
                    <a href="{{ route('admin.login') }}" class="text-gray-500 hover:text-[#c59d5f]">Portal Admin</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

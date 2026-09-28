<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Admin — Vantara Production')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#090c14] text-gray-100 font-sans antialiased min-h-screen flex flex-col selection:bg-[#c59d5f] selection:text-black"
      x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-40 w-64 bg-[#0e131d] border-r border-[#1e2538] transition-transform duration-300 flex flex-col justify-between">

            <!-- Sidebar Header & Logo -->
            <div>
                <div class="p-6 border-b border-[#1e2538] flex items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#c59d5f] via-[#dfc48e] to-[#ab8048] p-[1px] shadow-lg">
                            <div class="w-full h-full bg-[#0b0e14] rounded-lg flex items-center justify-center">
                                <span class="font-serif text-lg font-bold text-[#dfc48e]">V</span>
                            </div>
                        </div>
                        <div>
                            <span class="font-serif text-lg font-bold tracking-[0.15em] text-white block leading-none">VANTARA</span>
                            <span class="text-[9px] tracking-[0.3em] text-[#c59d5f] uppercase block font-semibold mt-1">Portal Admin</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                        ✕
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 text-xs font-medium">
                    <div class="text-[10px] uppercase tracking-widest text-[#c59d5f] px-3 pt-3 pb-1 font-semibold">Menu Utama</div>

                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#c59d5f] text-black font-bold shadow-lg shadow-[#c59d5f]/20' : 'text-gray-300 hover:bg-[#141b28] hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard & Analytics</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}"
                       class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-[#c59d5f] text-black font-bold shadow-lg shadow-[#c59d5f]/20' : 'text-gray-300 hover:bg-[#141b28] hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span>Kelola Transaksi / Order</span>
                    </a>

                    <a href="{{ route('admin.packages.index') }}"
                       class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('admin.packages.*') ? 'bg-[#c59d5f] text-black font-bold shadow-lg shadow-[#c59d5f]/20' : 'text-gray-300 hover:bg-[#141b28] hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Kelola Paket Layanan</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}"
                       class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-[#c59d5f] text-black font-bold shadow-lg shadow-[#c59d5f]/20' : 'text-gray-300 hover:bg-[#141b28] hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Laporan Bulanan & PDF</span>
                    </a>

                    <div class="text-[10px] uppercase tracking-widest text-gray-400 px-3 pt-6 pb-1 font-semibold">Tautan Eksternal</div>

                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-[#141b28] transition-all">
                        <svg class="w-4 h-4 text-[#c59d5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Lihat Website Publik</span>
                    </a>
                </nav>
            </div>

            <!-- User Profile & Logout Box -->
            <div class="p-4 border-t border-[#1e2538] bg-[#0b0e14]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 truncate pr-2">
                        <div class="w-8 h-8 rounded-full bg-[#c59d5f]/20 border border-[#c59d5f]/50 flex items-center justify-center text-[#c59d5f] font-bold text-xs shrink-0">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="truncate">
                            <div class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</div>
                            <div class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email ?? 'admin@vantara.id' }}</div>
                        </div>
                    </div>

                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                title="Keluar Akun"
                                class="p-2 text-gray-400 hover:text-rose-400 hover:bg-rose-950/40 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:pl-64">

            <!-- Top Header Bar -->
            <header class="bg-[#0e131d]/90 backdrop-blur-md border-b border-[#1e2538] px-6 py-4 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-300 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h2 class="font-serif text-lg font-bold text-white">
                        @yield('header_title', 'Dashboard Portal Admin')
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    @yield('header_actions')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-[11px] font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sistem Terhubung Real-Time
                    </span>
                </div>
            </header>

            <!-- Scrollable View Body -->
            <main class="flex-1 overflow-y-auto p-6 sm:p-8 bg-[#090c14]">

                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-950/70 border border-emerald-500/50 rounded-xl text-emerald-200 text-xs flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-base">✓</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-rose-950/70 border border-rose-500/50 rounded-xl text-rose-200 text-xs flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-base">✕</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')

            </main>

        </div>

    </div>

</body>
</html>

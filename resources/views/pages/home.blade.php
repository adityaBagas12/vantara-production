@extends('layouts.app')

@section('title', 'Vantara Production — Rental Sound System, Lighting & Hiburan Entertainment')
@section('meta_description', 'Sewa sound system, lighting, videobooth 360, band wedding, dan orgen tunggal untuk pernikahan, khitanan, gathering, dan berbagai acara spesial Anda.')

@section('content')

    <!-- HERO SECTION -->
    <section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden bg-[#0a0d14]">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1920&auto=format&fit=crop"
                 alt="Vantara Production — Event Entertainment Stage"
                 class="w-full h-full object-cover object-center filter brightness-[0.30]">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0e14] via-[#0b0e14]/60 to-transparent"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">

            <!-- Top Tag -->
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-[#c59d5f]/15 border border-[#c59d5f]/40 backdrop-blur-md mb-8">
                <span class="w-2 h-2 rounded-full bg-[#c59d5f] animate-ping"></span>
                <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#dfc48e]">
                    Rental Sound System & Entertainment Terpercaya
                </span>
            </div>

            <!-- Headline -->
            <h1 class="font-serif text-4xl sm:text-6xl md:text-7xl font-bold tracking-tight text-white mb-6 leading-[1.15]">
                Hadirkan Suasana <br class="hidden sm:inline">
                <span class="italic font-normal bg-gradient-to-r from-[#ebdcb9] via-[#c59d5f] to-[#dfc48e] bg-clip-text text-transparent">
                    Acara yang Tak Terlupakan
                </span>
            </h1>

            <!-- Divider -->
            <div class="flex items-center justify-center gap-3 my-6">
                <span class="h-[1px] w-12 bg-gradient-to-r from-transparent to-[#c59d5f]"></span>
                <span class="text-[#c59d5f] text-sm">✦</span>
                <span class="h-[1px] w-12 bg-gradient-to-l from-transparent to-[#c59d5f]"></span>
            </div>

            <!-- Subtitle -->
            <p class="max-w-2xl mx-auto text-base sm:text-lg text-gray-300 font-light leading-relaxed mb-10">
                Vantara Production menyediakan rental <strong class="text-white">sound system</strong>, <strong class="text-white">lighting & special effect</strong>, <strong class="text-white">videobooth 360°</strong>, dan <strong class="text-white">band wedding</strong> untuk pernikahan, khitanan, gathering, konser, dan berbagai acara spesial lainnya.
            </p>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6">
                <a href="{{ route('packages.catalog') }}"
                   class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] text-black font-semibold text-xs uppercase tracking-[0.2em] rounded shadow-xl shadow-[#c59d5f]/25 hover:shadow-[#c59d5f]/40 hover:scale-[1.02] transition-all">
                    Lihat Katalog Paket & Harga
                </a>
                <a href="{{ route('availability.index') }}"
                   class="w-full sm:w-auto px-8 py-4 bg-[#141b28]/80 hover:bg-[#1a2334] text-white border border-[#c59d5f]/50 hover:border-[#c59d5f] font-semibold text-xs uppercase tracking-[0.2em] rounded backdrop-blur-sm transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-[#c59d5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Cek Ketersediaan Tanggal
                </a>
            </div>

            <!-- Trust Points -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-16 pt-8 border-t border-white/10 text-left">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#c59d5f]/10 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-white">Sound System</div>
                        <div class="text-[11px] text-gray-400">600 Watt — 5000 Watt</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#c59d5f]/10 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-white">Lighting & Efek</div>
                        <div class="text-[11px] text-gray-400">Moving Beam, Dry Ice, Firework</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#c59d5f]/10 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-white">Videobooth 360°</div>
                        <div class="text-[11px] text-gray-400">Hiburan Interaktif Tamu</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#c59d5f]/10 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-white">Band Wedding</div>
                        <div class="text-[11px] text-gray-400">4–6 Personil Musisi Live</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- QUICK DATE CHECKER -->
    <section class="relative z-20 -mt-8 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div x-data="calendarWidget()" class="bg-[#121824] border border-[#c59d5f]/30 rounded-xl p-6 shadow-2xl backdrop-blur-xl">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <div class="text-[11px] uppercase tracking-widest text-[#c59d5f] font-semibold mb-1">Cek Ketersediaan Cepat</div>
                    <h3 class="font-serif text-lg font-bold text-white">Tanggal Acara Anda Sudah Tersedia?</h3>
                </div>
                <div class="w-full md:w-auto flex-1 flex flex-col sm:flex-row items-center gap-3">
                    <input type="date"
                           x-model="selectedDate"
                           min="{{ date('Y-m-d') }}"
                           class="w-full sm:w-auto flex-1 px-4 py-3 bg-[#0a0d14] border border-[#242f44] focus:border-[#c59d5f] rounded text-sm text-gray-200 focus:outline-none transition-colors">
                    <button @click="checkSpecificDate(selectedDate)"
                            :disabled="!selectedDate || isCheckingDate"
                            type="button"
                            class="w-full sm:w-auto px-6 py-3 bg-[#c59d5f] hover:bg-[#d4ab63] text-black font-semibold text-xs uppercase tracking-wider rounded transition-all disabled:opacity-50 shrink-0">
                        <span x-show="!isCheckingDate">Cek Sekarang</span>
                        <span x-show="isCheckingDate" style="display:none;">Memeriksa...</span>
                    </button>
                </div>
            </div>
            <div x-show="checkResult"
                 x-transition
                 class="mt-4 p-4 rounded-lg text-xs flex items-start gap-3 border"
                 :class="checkResult?.available ? 'bg-emerald-950/40 border-emerald-500/40 text-emerald-200' : 'bg-rose-950/40 border-rose-500/40 text-rose-200'"
                 style="display:none;">
                <div class="text-base" x-text="checkResult?.available ? '✓' : '⚠'"></div>
                <div class="flex-1">
                    <div class="font-semibold text-sm mb-0.5" x-text="checkResult?.formatted_date"></div>
                    <p x-text="checkResult?.message"></p>
                    <div x-show="checkResult?.available" class="mt-2">
                        <a :href="'{{ route('packages.catalog') }}?event_date=' + selectedDate" class="underline font-semibold text-white">Lihat Paket & Mulai Pemesanan &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <section class="py-20 bg-[#0a0d14] border-b border-[#1b2438]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-[#1e2538]">
                <div class="p-4">
                    <div class="font-serif text-4xl sm:text-5xl font-bold text-[#dfc48e] mb-2">300+</div>
                    <div class="text-xs uppercase tracking-widest text-gray-400 font-medium">Acara Sukses Ditangani</div>
                </div>
                <div class="p-4">
                    <div class="font-serif text-4xl sm:text-5xl font-bold text-[#dfc48e] mb-2">4</div>
                    <div class="text-xs uppercase tracking-widest text-gray-400 font-medium">Kategori Layanan Lengkap</div>
                </div>
                <div class="p-4">
                    <div class="font-serif text-4xl sm:text-5xl font-bold text-[#dfc48e] mb-2">100%</div>
                    <div class="text-xs uppercase tracking-widest text-gray-400 font-medium">Kepuasan Pelanggan</div>
                </div>
                <div class="p-4">
                    <div class="font-serif text-4xl sm:text-5xl font-bold text-[#dfc48e] mb-2">Siap</div>
                    <div class="text-xs uppercase tracking-widest text-gray-400 font-medium">Antar-Pasang di Lokasi Acara</div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROFIL USAHA SECTION -->
    <section id="profil" class="py-24 bg-[#0e131d] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left Image -->
                <div class="lg:col-span-6 relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-[#242f44]">
                        <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1200&auto=format&fit=crop"
                             alt="Vantara Production — Pernikahan Mewah"
                             class="w-full h-[460px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>

                        <!-- Top-Right Feature Badge -->
                        <div class="absolute top-4 right-4 bg-[#0a0d14]/90 border border-[#c59d5f]/60 p-4 rounded-xl shadow-2xl backdrop-blur-md max-w-xs flex items-start gap-3 z-10">
                            <div class="w-9 h-9 rounded-lg bg-[#c59d5f]/20 border border-[#c59d5f]/50 flex items-center justify-center text-[#c59d5f] shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-serif text-sm font-bold text-[#dfc48e]">Layanan Antar & Pasang</div>
                                <div class="text-[11px] text-gray-300 leading-relaxed mt-0.5">Semua peralatan kami antarkan, pasangkan, dan operasikan langsung di lokasi acara Anda.</div>
                            </div>
                        </div>

                        <!-- Bottom Title Overlay -->
                        <div class="absolute bottom-6 left-6 right-6 z-10">
                            <span class="text-xs uppercase tracking-widest text-[#c59d5f] font-semibold">Dokumentasi Acara Pelanggan</span>
                            <h4 class="font-serif text-xl sm:text-2xl font-bold text-white mt-1">Wedding Reception — Ballroom Premium</h4>
                        </div>
                    </div>
                </div>

                <!-- Right Text -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.2em] font-semibold text-[#c59d5f]">
                        <span class="w-6 h-[1px] bg-[#c59d5f]"></span>
                        Tentang Vantara Production
                    </div>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Mitra Hiburan Terpercaya untuk Setiap Acara Spesial Anda
                    </h2>
                    <p class="text-sm text-gray-300 leading-relaxed font-light">
                        Vantara Production adalah usaha rental sound system dan peralatan entertainment yang telah melayani ratusan acara pernikahan, khitanan, gathering, konser, dan acara korporat. Kami hadir untuk memastikan setiap momen berharga Anda diiringi suara dan cahaya terbaik.
                    </p>
                    <p class="text-sm text-gray-300 leading-relaxed font-light">
                        Proses pemesanan kami sederhana: pilih paket di website ini, isi data acara, lalu tim kami akan segera menghubungi Anda melalui WhatsApp untuk diskusi kebutuhan, negosiasi, dan pembayaran. Semua peralatan kami antarkan dan pasangkan di lokasi acara.
                    </p>
                    <div class="space-y-4 pt-2">
                        <div class="p-4 rounded-lg bg-[#141b28] border border-[#242f44] flex items-start gap-4">
                            <div class="w-10 h-10 rounded bg-[#c59d5f]/10 text-[#c59d5f] flex items-center justify-center shrink-0 font-serif font-bold text-lg">01</div>
                            <div>
                                <h4 class="text-sm font-semibold text-white">Pilih Paket & Isi Data Acara</h4>
                                <p class="text-xs text-gray-400 mt-1">Browsing katalog paket, pilih sesuai kebutuhan, lalu isi formulir data diri dan informasi acara Anda melalui website ini.</p>
                            </div>
                        </div>
                        <div class="p-4 rounded-lg bg-[#141b28] border border-[#242f44] flex items-start gap-4">
                            <div class="w-10 h-10 rounded bg-[#c59d5f]/10 text-[#c59d5f] flex items-center justify-center shrink-0 font-serif font-bold text-lg">02</div>
                            <div>
                                <h4 class="text-sm font-semibold text-white">Konfirmasi & Negosiasi via WhatsApp</h4>
                                <p class="text-xs text-gray-400 mt-1">Tim admin Vantara Production akan menghubungi Anda via WhatsApp untuk konfirmasi detail, diskusi kebutuhan tambahan, dan proses pembayaran.</p>
                            </div>
                        </div>
                        <div class="p-4 rounded-lg bg-[#141b28] border border-[#242f44] flex items-start gap-4">
                            <div class="w-10 h-10 rounded bg-[#c59d5f]/10 text-[#c59d5f] flex items-center justify-center shrink-0 font-serif font-bold text-lg">03</div>
                            <div>
                                <h4 class="text-sm font-semibold text-white">Kami Hadir di Hari Acara Anda</h4>
                                <p class="text-xs text-gray-400 mt-1">Kru kami datang, memasang, mengoperasikan seluruh peralatan, dan memastikan acara Anda berjalan dengan sempurna dari awal hingga selesai.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PAKET UNGGULAN SECTION -->
    <section class="py-24 bg-[#0a0d14]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#c59d5f] block mb-2">Paling Banyak Dipesan</span>
                <h2 class="font-serif text-3xl sm:text-5xl font-bold text-white mb-4">Paket Unggulan Kami</h2>
                <div class="flex items-center justify-center gap-3 my-4">
                    <span class="h-[1px] w-10 bg-[#c59d5f]"></span>
                    <span class="text-[#c59d5f] text-xs">✦</span>
                    <span class="h-[1px] w-10 bg-[#c59d5f]"></span>
                </div>
                <p class="text-sm text-gray-400">Paket rental paling populer yang menjadi pilihan utama pelanggan Vantara Production untuk berbagai jenis acara.</p>
            </div>

            <!-- 3-Column Card Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($featuredPackages as $package)
                    <div class="group bg-[#111722] rounded-xl overflow-hidden border border-[#242f44] hover:border-[#c59d5f]/60 transition-all duration-300 shadow-xl flex flex-col hover:-translate-y-1">

                        <!-- Card Image -->
                        <div class="relative h-56 overflow-hidden bg-black">
                            <img src="{{ $package->image_path ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=800&auto=format&fit=crop' }}"
                                 alt="{{ $package->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 brightness-90">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#111722] via-transparent to-transparent"></div>
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-black/70 backdrop-blur-md border border-[#c59d5f]/40 text-[#dfc48e] text-[10px] font-semibold uppercase tracking-wider rounded">
                                    {{ $package->category }}
                                </span>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2.5 py-0.5 bg-gradient-to-r from-[#dfc48e] to-[#c59d5f] text-black text-[10px] font-bold uppercase tracking-wider rounded shadow">
                                    Unggulan
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-serif text-lg font-bold text-white group-hover:text-[#c59d5f] transition-colors mb-2">
                                    {{ $package->name }}
                                </h3>
                                <p class="text-xs text-gray-400 line-clamp-2 mb-4 leading-relaxed">
                                    {{ $package->description }}
                                </p>
                                <div class="space-y-1.5 mb-5 border-t border-[#1e2538] pt-4">
                                    <div class="text-[11px] uppercase tracking-wider text-[#c59d5f] font-semibold mb-2">Termasuk dalam paket:</div>
                                    @foreach (array_slice($package->items ?? [], 0, 4) as $item)
                                        <div class="flex items-center gap-2 text-xs text-gray-300">
                                            <svg class="w-3.5 h-3.5 text-[#c59d5f] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            <span class="truncate">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                    @if (count($package->items ?? []) > 4)
                                        <div class="text-[11px] text-[#c59d5f] italic">+ {{ count($package->items) - 4 }} item lainnya</div>
                                    @endif
                                </div>
                            </div>
                            <div class="pt-4 border-t border-[#1e2538]">
                                <div class="flex items-baseline justify-between mb-4">
                                    <span class="text-xs text-gray-400">Harga Mulai Dari:</span>
                                    <div class="font-serif text-xl font-bold text-[#dfc48e]">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                        <span class="text-[10px] font-sans text-gray-400 font-normal">/ acara</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('packages.show', $package->slug) }}"
                                       class="w-full text-center py-2.5 px-3 bg-[#171f2e] hover:bg-[#202b3f] text-gray-200 border border-[#242f44] hover:border-[#c59d5f] rounded text-xs font-medium uppercase tracking-wider transition-all">
                                        Detail Paket
                                    </a>
                                    <a href="{{ route('packages.show', $package->slug) }}#kalender"
                                       class="w-full text-center py-2.5 px-3 bg-[#c59d5f] hover:bg-[#d4ab63] text-black rounded text-xs font-semibold uppercase tracking-wider transition-all">
                                        Cek Tanggal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-14 text-center">
                <a href="{{ route('packages.catalog') }}"
                   class="inline-flex items-center gap-2 px-8 py-3.5 border border-[#c59d5f] text-[#dfc48e] hover:bg-[#c59d5f] hover:text-black font-semibold text-xs uppercase tracking-[0.2em] rounded transition-all">
                    <span>Lihat Semua Paket & Harga Lengkap</span>
                    <span>&rarr;</span>
                </a>
            </div>

        </div>
    </section>

    <!-- LAYANAN UTAMA (4 Kategori) -->
    <section id="keunggulan" class="py-24 bg-[#0e131d] border-t border-b border-[#1b2438]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#c59d5f] block mb-2">Apa yang Kami Sediakan</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-4">4 Layanan Utama Vantara Production</h2>
                <div class="flex items-center justify-center gap-3 my-4">
                    <span class="h-[1px] w-10 bg-[#c59d5f]"></span>
                    <span class="text-[#c59d5f] text-xs">✦</span>
                    <span class="h-[1px] w-10 bg-[#c59d5f]"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('packages.catalog', ['category' => 'Sound System']) }}"
                   class="group p-6 rounded-xl bg-[#121824] border border-[#242f44] hover:border-[#c59d5f]/60 transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-lg bg-[#c59d5f]/15 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] mb-5 group-hover:bg-[#c59d5f] group-hover:text-black transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-white mb-2 group-hover:text-[#c59d5f] transition-colors">Sound System</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">Dari akad nikah minimalis hingga orgen tunggal 5000 Watt dengan berbagai pilihan musisi dan vokalis live.</p>
                    <div class="mt-4 text-xs text-[#c59d5f] font-semibold">Mulai Rp 600.000 &rarr;</div>
                </a>

                <a href="{{ route('packages.catalog', ['category' => 'Lighting & Special Effect']) }}"
                   class="group p-6 rounded-xl bg-[#121824] border border-[#242f44] hover:border-[#c59d5f]/60 transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-lg bg-[#c59d5f]/15 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] mb-5 group-hover:bg-[#c59d5f] group-hover:text-black transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-white mb-2 group-hover:text-[#c59d5f] transition-colors">Lighting & Special Effect</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">Moving beam, dry ice, cold firework, dan parled warna-warni untuk suasana acara yang dramatis dan memukau.</p>
                    <div class="mt-4 text-xs text-[#c59d5f] font-semibold">Mulai Rp 2.500.000 &rarr;</div>
                </a>

                <a href="{{ route('packages.catalog', ['category' => 'Videobooth 360']) }}"
                   class="group p-6 rounded-xl bg-[#121824] border border-[#242f44] hover:border-[#c59d5f]/60 transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-lg bg-[#c59d5f]/15 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] mb-5 group-hover:bg-[#c59d5f] group-hover:text-black transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-white mb-2 group-hover:text-[#c59d5f] transition-colors">Videobooth 360°</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">Booth 360 derajat dengan iPhone 14, lighting pro, dan monitor preview. Hiburan interaktif yang bikin tamu antusias.</p>
                    <div class="mt-4 text-xs text-[#c59d5f] font-semibold">Rp 1.800.000 &rarr;</div>
                </a>

                <a href="{{ route('packages.catalog', ['category' => 'Band Wedding']) }}"
                   class="group p-6 rounded-xl bg-[#121824] border border-[#242f44] hover:border-[#c59d5f]/60 transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-lg bg-[#c59d5f]/15 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] mb-5 group-hover:bg-[#c59d5f] group-hover:text-black transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-white mb-2 group-hover:text-[#c59d5f] transition-colors">Band Wedding</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">Musisi live band wedding 4–6 personil dengan berbagai pilihan formasi dari VA Harmony hingga VA Royal premium.</p>
                    <div class="mt-4 text-xs text-[#c59d5f] font-semibold">Mulai Rp 5.000.000 &rarr;</div>
                </a>
            </div>
        </div>
    </section>

    <!-- ITEM TAMBAHAN INFO STRIP -->
    <section class="py-12 bg-[#0a0d14] border-b border-[#1b2438]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="p-6 rounded-xl bg-[#111722] border border-[#c59d5f]/20">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div>
                        <div class="text-[11px] uppercase tracking-widest text-[#c59d5f] font-semibold mb-1">Item Tambahan (Add-on)</div>
                        <h3 class="font-serif text-xl font-bold text-white">Butuh Tambahan Peralatan?</h3>
                        <p class="text-xs text-gray-400 mt-1.5 max-w-xl">Kami juga menyediakan item tambahan yang bisa dikombinasikan dengan paket utama sesuai kebutuhan acara Anda.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1.5 bg-[#141b28] border border-[#242f44] rounded text-xs text-gray-300">⚡ Genset mulai Rp 500.000</span>
                        <span class="px-3 py-1.5 bg-[#141b28] border border-[#242f44] rounded text-xs text-gray-300">📺 Videotron Rp 800.000/m</span>
                        <span class="px-3 py-1.5 bg-[#141b28] border border-[#242f44] rounded text-xs text-gray-300">💨 Blower Rp 200.000/unit</span>
                        <span class="px-3 py-1.5 bg-[#141b28] border border-[#242f44] rounded text-xs text-gray-300">💡 Parled & Freshnel (Custom)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- BOTTOM CTA BANNER -->
    <section class="py-20 bg-gradient-to-b from-[#0e131d] to-[#07090e] border-t border-[#242f44]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="p-10 sm:p-14 rounded-2xl bg-gradient-to-r from-[#171f2e] via-[#121824] to-[#171f2e] border border-[#c59d5f]/40 shadow-2xl">
                <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#c59d5f] block mb-3">Konsultasi Gratis</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-4">
                    Ada Pertanyaan atau Kebutuhan Khusus?
                </h2>
                <p class="max-w-2xl mx-auto text-xs sm:text-sm text-gray-300 mb-8 leading-relaxed">
                    Ceritakan konsep acara Anda kepada kami. Tim Vantara Production siap membantu menyusun kombinasi paket terbaik sesuai anggaran dan kebutuhan event Anda.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="https://wa.me/6282282422317?text=Halo%20Vantara%20Production,%20saya%20ingin%20konsultasi%20kebutuhan%20sound%20system%20dan%20entertainment%20untuk%20acara%20saya."
                       target="_blank"
                       class="w-full sm:w-auto px-8 py-3.5 bg-[#25D366] hover:bg-[#20ba5a] text-white font-semibold text-xs uppercase tracking-wider rounded shadow-xl flex items-center justify-center gap-2 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                    <a href="{{ route('packages.catalog') }}"
                       class="w-full sm:w-auto px-8 py-3.5 bg-transparent border border-[#c59d5f] text-[#dfc48e] hover:bg-[#c59d5f] hover:text-black font-semibold text-xs uppercase tracking-wider rounded transition-all">
                        Lihat Seluruh Katalog Paket
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

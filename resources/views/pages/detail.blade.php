@extends('layouts.app')

@section('title', $package->name . ' — Vantara Production')
@section('meta_description', $package->description)

@section('content')

    <!-- Breadcrumb & Header Bar -->
    <div class="bg-[#090c14] border-b border-[#1b2438] py-4 text-xs text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('packages.catalog') }}" class="hover:text-white transition-colors">Katalog Paket</a>
            <span>/</span>
            <span class="text-[#c59d5f] font-semibold truncate">{{ $package->name }}</span>
        </div>
    </div>

    <!-- Main Detail Section -->
    <section class="py-12 bg-[#0b0e14]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <!-- Left Column (Package Specs & Details) - 7 cols -->
                <div class="lg:col-span-7 space-y-10">
                    
                    <!-- Main Image Preview with Luxury Badge -->
                    <div class="relative rounded-2xl overflow-hidden border border-[#242f44] shadow-2xl bg-black">
                        <img src="{{ $package->image_path ?? 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1200&auto=format&fit=crop' }}" 
                             alt="{{ $package->name }}" 
                             class="w-full h-[380px] sm:h-[460px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0e14] via-transparent to-transparent"></div>
                        
                        <div class="absolute top-6 left-6 flex items-center gap-3">
                            <span class="px-3.5 py-1.5 bg-black/80 backdrop-blur-md border border-[#c59d5f]/50 text-[#dfc48e] text-xs font-semibold uppercase tracking-wider rounded">
                                {{ $package->category }}
                            </span>
                            @if ($package->is_featured)
                                <span class="px-3 py-1 bg-gradient-to-r from-[#dfc48e] to-[#c59d5f] text-black text-xs font-bold uppercase tracking-wider rounded shadow">
                                    Paket Unggulan
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Title & Overview -->
                    <div class="space-y-4">
                        <div class="text-xs uppercase tracking-[0.25em] text-[#c59d5f] font-semibold">Spesifikasi Layanan Rental</div>
                        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                            {{ $package->name }}
                        </h1>
                        <div class="flex items-center gap-3 py-2">
                            <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
                            <span class="text-[#c59d5f] text-xs">✦</span>
                            <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
                        </div>
                        <p class="text-sm text-gray-300 leading-relaxed font-light">
                            {{ $package->description }}
                        </p>
                    </div>

                    <!-- Itemized Inclusions (PRD: Item / Layanan yang Didapat) -->
                    <div class="space-y-4">
                        <h3 class="font-serif text-xl font-bold text-white flex items-center gap-2">
                            <span class="text-[#c59d5f]">❖</span>
                            Peralatan & Layanan yang Termasuk
                        </h3>
                        <p class="text-xs text-gray-400">Seluruh peralatan diantar, dipasang, dan diuji langsung oleh kru berpengalaman Vantara Production.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            @foreach ($package->items ?? [] as $item)
                                <div class="p-4 rounded-xl bg-[#121824] border border-[#242f44] flex items-start gap-3">
                                    <div class="w-6 h-6 rounded-full bg-[#c59d5f]/15 border border-[#c59d5f]/40 flex items-center justify-center text-[#c59d5f] shrink-0 mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div class="text-xs font-medium text-gray-200 leading-snug">
                                        {{ $item }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- SOP & Quality Guarantee -->
                    <div class="p-6 rounded-xl bg-[#121824] border border-[#c59d5f]/30 space-y-4">
                        <h3 class="font-serif text-lg font-bold text-[#dfc48e] flex items-center gap-2">
                            <span>🛡</span> Jaminan Layanan Vantara Production
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                            <div class="space-y-1">
                                <strong class="text-white block">Pemasangan Tepat Waktu:</strong>
                                <span class="text-gray-400">Kru tiba dan siap memasang peralatan minimal 2–3 jam sebelum acara dimulai.</span>
                            </div>
                            <div class="space-y-1">
                                <strong class="text-white block">Peralatan High-End:</strong>
                                <span class="text-gray-400">Audio & lighting profesional yang terawat dan siap pakai tanpa kendala teknis.</span>
                            </div>
                            <div class="space-y-1">
                                <strong class="text-white block">Teknisi Standby:</strong>
                                <span class="text-gray-400">Kru operator audio/lighting bertugas penuh selama acara berlangsung hingga selesai.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions (PRD: Batasan serta Harga) -->
                    <div class="space-y-3 border-t border-[#1e2538] pt-6">
                        <h4 class="font-serif text-base font-bold text-white">Ketentuan & Syarat Pemesanan</h4>
                        <ul class="space-y-2 text-xs text-gray-400 list-disc list-inside leading-relaxed">
                            <li>Harga di atas berlaku untuk penggunaan 1 (satu) hari acara / sesi event.</li>
                            <li>Pemasangan & pengantaran peralatan dilakukan langsung oleh kru Vantara Production.</li>
                            <li>Pemesanan dan penetapan tanggal final dilakukan melalui konfirmasi Admin via WhatsApp.</li>
                            <li>Untuk pemesanan Genset, biaya sewa belum termasuk bahan bakar (BBM).</li>
                        </ul>
                    </div>

                </div>

                <!-- Right Column (Sticky Booking & Calendar) - 5 cols -->
                <div class="lg:col-span-5" id="kalender">
                    <div class="sticky top-24 space-y-5">
                        
                        <!-- Pricing & Booking Card with Calendar -->
                        <div class="rounded-2xl bg-gradient-to-b from-[#141b28] to-[#0e131d] border border-[#c59d5f]/40 shadow-2xl overflow-hidden"
                             x-data="calendarWidget({{ $package->id }})"
                             x-init="selectedDate = '{{ old('event_date', request('event_date') ?? request('date') ?? session('event_date')) }}'">

                            <div class="p-5">
                                <!-- Pricing Header -->
                                <div class="flex items-baseline justify-between mb-4">
                                    <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Harga Sewa</span>
                                    <div class="text-right">
                                        <div class="font-serif text-3xl font-bold text-[#dfc48e]">
                                            Rp {{ number_format($package->price, 0, ',', '.') }}
                                        </div>
                                        <div class="text-[11px] text-gray-400">Per Acara</div>
                                    </div>
                                </div>

                                <!-- Status Paket -->
                                <div class="p-3 rounded-lg bg-[#0a0d14] border border-[#1e2538] text-xs text-gray-300 flex items-center justify-between">
                                    <span>Status Paket:</span>
                                    <span class="inline-flex items-center gap-1.5 text-emerald-400 font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Tersedia & Siap Dipesan
                                    </span>
                                </div>
                            </div>

                            <!-- Calendar Section (Below Status Paket) -->
                            <div class="border-t border-[#1e2538]">
                                
                                <!-- Calendar Header -->
                                <div class="px-5 py-3 bg-[#0c1017] flex items-center justify-between">
                                    <div>
                                        <div class="text-[9px] uppercase tracking-widest text-[#c59d5f] font-semibold">Cek Tanggal</div>
                                        <div class="font-serif text-sm font-bold text-white"
                                             x-text="`${monthNames[currentMonth - 1]} ${currentYear}`"></div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button @click="prevMonth()" type="button"
                                                class="p-1.5 rounded bg-[#141b28] hover:bg-[#c59d5f] hover:text-black text-gray-400 transition-colors border border-[#242f44]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                        </button>
                                        <button @click="currentMonth = new Date().getMonth() + 1; currentYear = new Date().getFullYear(); fetchMonthData()"
                                                type="button"
                                                class="px-2 py-1 rounded bg-[#141b28] hover:bg-[#1e2538] text-gray-400 text-[9px] uppercase font-semibold tracking-wider transition-colors border border-[#242f44]">
                                            Bulan Ini
                                        </button>
                                        <button @click="nextMonth()" type="button"
                                                class="p-1.5 rounded bg-[#141b28] hover:bg-[#c59d5f] hover:text-black text-gray-400 transition-colors border border-[#242f44]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Note Petunjuk Tanggal -->
                                <div class="px-5 py-2.5 bg-[#090c14] border-b border-[#1e2538] text-xs text-[#dfc48e] flex items-center justify-between font-medium">
                                    <div class="flex items-center gap-2">
                                        <span class="text-amber-400 text-sm">💡</span>
                                        <span><strong>Catatan:</strong> Pilih tanggal lalu checkout</span>
                                    </div>
                                    <template x-if="selectedDate">
                                        <span class="text-[10px] text-emerald-400 font-semibold bg-emerald-950/60 px-2 py-0.5 rounded border border-emerald-500/40" x-text="`Terpilih: ${selectedDate.split('-').reverse().join('/')}`"></span>
                                    </template>
                                </div>

                                <!-- Loading -->
                                <div x-show="isLoading" class="py-10 flex items-center justify-center text-[#c59d5f]" style="display: none;">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                </div>

                                <!-- Calendar Grid -->
                                <div x-show="!isLoading" class="px-4 py-3">
                                    <!-- Day Names -->
                                    <div class="grid grid-cols-7 gap-1 mb-1">
                                        <template x-for="day in dayNames" :key="day">
                                            <div class="text-center text-[9px] uppercase tracking-wider font-bold text-gray-500 py-1" x-text="day"></div>
                                        </template>
                                    </div>

                                    <!-- Date Cells -->
                                    <div class="grid grid-cols-7 gap-1">
                                        <template x-for="(d, index) in calendarDays" :key="index">
                                            <button type="button"
                                                    @click="onDayClick(d)"
                                                    :disabled="!d.isCurrentMonth || d.isPast"
                                                    :title="d.isBooked ? `Terisi: ${d.bookingInfo?.package_name ?? 'Jadwal Terisi'}` : (d.isPast ? 'Tanggal Telah Berlalu' : `Pilih tanggal ${d.dateString}`)"
                                                    class="relative h-9 rounded flex flex-col items-center justify-center font-medium text-[11px] transition-all"
                                                    :class="{
                                                        'opacity-15 cursor-not-allowed': !d.isCurrentMonth,
                                                        'opacity-25 cursor-not-allowed text-gray-600 bg-transparent': d.isCurrentMonth && d.isPast,
                                                        'bg-rose-950/50 border border-rose-500/40 text-rose-300 hover:bg-rose-900/70 cursor-pointer': d.isCurrentMonth && !d.isPast && d.isBooked,
                                                        'bg-transparent text-gray-300 hover:bg-[#1a2334] cursor-pointer': d.isCurrentMonth && !d.isPast && !d.isBooked && selectedDate !== d.dateString,
                                                        'bg-[#1e2538] border border-[#c59d5f] text-white font-bold ring-1 ring-[#c59d5f]/40': d.isCurrentMonth && selectedDate === d.dateString
                                                    }">
                                                <span x-text="d.day"></span>
                                                <!-- Dot Indicator -->
                                                <span x-show="d.isCurrentMonth && !d.isPast && d.isBooked"
                                                      class="absolute bottom-0.5 w-1 h-1 rounded-full bg-rose-400"></span>
                                                <span x-show="d.isCurrentMonth && !d.isPast && !d.isBooked && selectedDate !== d.dateString"
                                                      class="absolute bottom-0.5 w-1 h-1 rounded-full bg-emerald-400"></span>
                                                <span x-show="d.isCurrentMonth && selectedDate === d.dateString"
                                                      class="absolute bottom-0.5 w-1 h-1 rounded-full bg-emerald-400"></span>
                                            </button>
                                        </template>
                                    </div>

                                    <!-- Legend -->
                                    <div class="flex items-center justify-center gap-4 mt-2 pt-2 border-t border-[#1e2538]/50 text-[9px] text-gray-500">
                                        <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Tersedia</div>
                                        <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Terisi</div>
                                    </div>
                                </div>


                            </div>

                            <!-- CTA Buttons -->
                            <div class="p-5 border-t border-[#1e2538] space-y-3">
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                                    <input type="hidden" name="event_date" :value="selectedDate">
                                    <button type="submit"
                                            class="w-full py-3.5 px-4 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black font-bold text-xs uppercase tracking-[0.15em] rounded-lg shadow-lg flex items-center justify-center gap-2 transition-all hover:scale-[1.01]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        Tambah ke Keranjang
                                    </button>
                                </form>

                                <a :href="`https://wa.me/6282282422317?text=Halo%20Vantara%20Production,%20saya%20tertarik%20dengan%20paket%20${encodeURIComponent('{{ $package->name }}')}${selectedDate ? '%20untuk%20tanggal%20' + selectedDate : ''}.%20Mohon%20info%20ketersediaan%20dan%20pemesanan.`"
                                   target="_blank"
                                   class="w-full py-3 px-4 bg-[#141b28] hover:bg-[#1a2334] text-[#dfc48e] border border-[#c59d5f]/50 hover:border-[#c59d5f] font-semibold text-xs uppercase tracking-wider rounded-lg text-center block transition-all">
                                    Tanya via WA Langsung
                                </a>

                                <a href="{{ route('availability.index', ['package_id' => $package->id]) }}"
                                   class="block w-full text-center py-2 px-4 bg-transparent text-gray-400 hover:text-white text-xs font-medium uppercase tracking-wider transition-all">
                                    Buka Kalender Penuh &rarr;
                                </a>
                            </div>
                        </div>

                        <!-- Direct Help Card -->
                        <div class="p-5 rounded-xl bg-[#111722] border border-[#242f44] text-xs space-y-2">
                            <h4 class="font-serif font-bold text-white text-sm">Konsultasi Custom Request?</h4>
                            <p class="text-gray-400 text-[11px] leading-relaxed">
                                Butuh kombinasi sound system + lighting + videobooth atau peralatan tambahan seperti Genset/Videotron? Konsultasikan langsung dengan tim Vantara Production.
                            </p>
                            <a href="https://wa.me/6282282422317?text=Halo%20Vantara%20Production,%20saya%20ingin%20request%20paket%20custom." 
                               target="_blank"
                               class="inline-block text-[#c59d5f] font-semibold hover:underline pt-1">
                                Tanya Admin via WA &rarr;
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

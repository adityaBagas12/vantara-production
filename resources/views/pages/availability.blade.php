@extends('layouts.app')

@section('title', 'Cek Ketersediaan Jadwal — Vantara Production')
@section('meta_description', 'Periksa ketersediaan tanggal acara Anda secara real-time melalui kalender interaktif Vantara Production.')

@section('content')

    <!-- Page Header -->
    <section class="relative py-20 bg-[#0c1017] border-b border-[#1b2438] overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1600&auto=format&fit=crop"
                 alt="Stage Event" class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#c59d5f] block mb-2">Transparansi Jadwal Real-Time</span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold text-white mb-4">Kalender Ketersediaan Tanggal</h1>
            <div class="flex items-center justify-center gap-3 my-4">
                <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
                <span class="text-[#c59d5f] text-xs">✦</span>
                <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
            </div>
            <p class="max-w-2xl mx-auto text-xs sm:text-sm text-gray-300 leading-relaxed">
                Periksa secara langsung apakah tanggal acara Anda masih tersedia. Sistem kalender ini terintegrasi real-time dengan jadwal operasional Vantara Production.
            </p>
        </div>
    </section>

    <!-- Main Availability Calendar Page -->
    <section class="py-16 bg-[#0a0d14] min-h-screen" 
             x-data="calendarWidget({{ $selectedPackageId ?? 'null' }})">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                <!-- Left: Filter & Package Selector Panel -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Package Selector (PRD: Filter Paket & Search) -->
                    <div class="p-6 rounded-xl bg-[#111722] border border-[#242f44]">
                        <h3 class="font-serif text-lg font-bold text-white mb-1">Filter Paket & Barang</h3>
                        <p class="text-[11px] text-gray-400 mb-4">Cari nama paket, kategori, atau peralatan (misal: Subwoofer, Beam, Keyboard) untuk melihat ketersediaan tanggal.</p>

                        <!-- Search Input Bar -->
                        <div class="relative mb-3">
                            <input type="text"
                                   x-model="searchQuery"
                                   placeholder="Cari paket atau barang..."
                                   class="w-full pl-9 pr-9 py-2.5 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white placeholder-gray-500 focus:outline-none transition-colors">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <button x-show="searchQuery"
                                    @click="searchQuery = ''"
                                    type="button"
                                    class="absolute right-2.5 top-2.5 text-gray-400 hover:text-white p-0.5 rounded-full hover:bg-white/10 transition-colors"
                                    title="Bersihkan pencarian">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="space-y-2 max-h-[380px] overflow-y-auto pr-1">
                            <!-- All Packages Option -->
                            <button x-show="!searchQuery"
                                    @click="setPackage(null)"
                                    type="button"
                                    :class="!packageId ? 'bg-[#c59d5f] text-black font-bold border-[#c59d5f]' : 'bg-[#141b28] text-gray-300 border-[#242f44] hover:border-[#c59d5f]/50'"
                                    class="w-full text-left px-4 py-3 rounded-lg border text-xs font-medium transition-all">
                                <div class="flex items-center justify-between">
                                    <span>Semua Paket Layanan</span>
                                    <span x-show="!packageId" class="text-[10px] uppercase tracking-wider font-bold">Terpilih</span>
                                </div>
                                <div class="text-[10px] mt-0.5 opacity-70">Tampilkan seluruh jadwal terisi</div>
                            </button>

                            @foreach ($packages as $pkg)
                                @php
                                    $itemsText = is_array($pkg->items) ? implode(' ', $pkg->items) : '';
                                    $haystack = strtolower(e($pkg->name . ' ' . $pkg->category . ' ' . $itemsText));
                                @endphp
                                <button x-show="matchesSearch('{{ addslashes($haystack) }}')"
                                        @click="setPackage({{ $pkg->id }})"
                                        type="button"
                                        :class="packageId === {{ $pkg->id }} ? 'bg-[#c59d5f] text-black font-bold border-[#c59d5f]' : 'bg-[#141b28] text-gray-300 border-[#242f44] hover:border-[#c59d5f]/50'"
                                        class="w-full text-left px-4 py-3 rounded-lg border text-xs font-medium transition-all">
                                    <div class="flex items-center justify-between">
                                        <span class="truncate pr-2">{{ $pkg->name }}</span>
                                        <span x-show="packageId === {{ $pkg->id }}" class="text-[10px] uppercase tracking-wider font-bold shrink-0">Terpilih</span>
                                    </div>
                                    <div class="text-[10px] mt-0.5 opacity-70 flex items-center justify-between">
                                        <span>{{ $pkg->category }}</span>
                                        <span>Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Date Check Box (PRD: Cek Tanggal Kosong) -->
                    <div class="p-6 rounded-xl bg-[#111722] border border-[#242f44]">
                        <h3 class="font-serif text-base font-bold text-white mb-1">Cek Tanggal Spesifik</h3>
                        <p class="text-[11px] text-gray-400 mb-4">Masukkan tanggal rencana acara untuk hasil pengecekan instan.</p>

                        <div class="space-y-3">
                            <input type="date"
                                   x-model="selectedDate"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded text-sm text-white focus:outline-none transition-colors">

                            <button @click="checkSpecificDate(selectedDate)"
                                    :disabled="!selectedDate || isCheckingDate"
                                    type="button"
                                    class="w-full py-3 bg-[#c59d5f] hover:bg-[#d4ab63] text-black font-semibold text-xs uppercase tracking-wider rounded transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span x-show="!isCheckingDate">Periksa Ketersediaan</span>
                                <span x-show="isCheckingDate" style="display:none;">Sedang memeriksa...</span>
                            </button>
                        </div>

                        <!-- Date Check Result -->
                        <div x-show="checkResult"
                             x-transition
                             class="mt-4 p-4 rounded-lg text-xs border"
                             :class="checkResult?.available ? 'bg-emerald-950/40 border-emerald-500/50 text-emerald-200' : 'bg-rose-950/40 border-rose-500/50 text-rose-200'"
                             style="display: none;">
                            <div class="font-bold text-sm mb-1.5 flex items-center gap-2">
                                <span x-text="checkResult?.available ? '✓' : '✕'"></span>
                                <span x-text="checkResult?.available ? 'Jadwal Masih Tersedia!' : 'Jadwal Telah Terisi'"></span>
                            </div>
                            <div class="font-semibold text-[11px] opacity-80 mb-1" x-text="checkResult?.formatted_date"></div>
                            <p class="leading-relaxed text-[11px]" x-text="checkResult?.message"></p>

                            <!-- Action if Available -->
                            <div x-show="checkResult?.available" class="mt-3 pt-3 border-t border-white/10">
                                <a :href="'{{ route('packages.catalog') }}?event_date=' + (selectedDate || checkResult?.date)"
                                   class="block w-full text-center py-2 px-4 bg-emerald-700 hover:bg-emerald-600 text-white font-semibold text-[11px] uppercase tracking-wider rounded transition-colors">
                                    Lanjut Pilih Paket & Pesan &rarr;
                                </a>
                            </div>

                            <!-- Action if Booked -->
                            <div x-show="!checkResult?.available && !checkResult?.is_past" class="mt-3 pt-3 border-t border-white/10">
                                <a href="https://wa.me/6282282422317?text=Halo%20Vantara%20Production,%20tanggal%20yang%20saya%20inginkan%20sudah%20terisi.%20Mohon%20bantu%20info%20tanggal%20alternatif."
                                   target="_blank"
                                   class="block w-full text-center py-2 px-4 bg-rose-800 hover:bg-rose-700 text-white font-semibold text-[11px] uppercase tracking-wider rounded transition-colors">
                                    Tanya Tanggal Alternatif via WA
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Legend Card -->
                    <div class="p-5 rounded-xl bg-[#111722] border border-[#242f44] text-xs space-y-3">
                        <h4 class="font-serif font-bold text-white text-sm">Keterangan Kalender</h4>
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-7 rounded bg-emerald-950/70 border border-emerald-500/60 flex items-center justify-center text-[10px] text-emerald-200 shrink-0 font-bold">15</div>
                                <div>
                                    <div class="font-semibold text-white">Tersedia</div>
                                    <div class="text-gray-400 text-[10px]">Tanggal masih bisa dipesan</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-7 rounded bg-rose-950/70 border border-rose-500/60 flex items-center justify-center text-[10px] text-rose-200 shrink-0 font-bold">15</div>
                                <div>
                                    <div class="font-semibold text-white">Terisi / Booked</div>
                                    <div class="text-gray-400 text-[10px]">Jadwal sudah dikonfirmasi</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-7 rounded bg-[#1e2538] border border-[#c59d5f] flex items-center justify-center text-[10px] text-[#c59d5f] shrink-0 font-bold ring-2 ring-[#c59d5f]">15</div>
                                <div>
                                    <div class="font-semibold text-white">Dipilih</div>
                                    <div class="text-gray-400 text-[10px]">Tanggal sedang dicek</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-7 rounded bg-transparent flex items-center justify-center text-[10px] text-gray-500 shrink-0 font-bold opacity-30">15</div>
                                <div>
                                    <div class="font-semibold text-gray-500">Telah Berlalu</div>
                                    <div class="text-gray-500 text-[10px]">Tidak dapat dipesan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right: Full Month Calendar View -->
                <div class="lg:col-span-8">
                    <div class="bg-[#111722] rounded-xl border border-[#242f44] overflow-hidden shadow-2xl">

                        <!-- Calendar Toolbar -->
                        <div class="p-5 bg-[#141b28] border-b border-[#1e2538] flex items-center justify-between">
                            <div>
                                <div class="text-[11px] uppercase tracking-widest text-[#c59d5f] font-semibold mb-0.5">Kalender Jadwal Operasional</div>
                                <div class="font-serif text-xl font-bold text-white"
                                     x-text="`${monthNames[currentMonth - 1]} ${currentYear}`"></div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button @click="prevMonth()"
                                        type="button"
                                        class="p-2.5 rounded-lg bg-[#0a0d14] hover:bg-[#c59d5f] hover:text-black text-gray-300 transition-colors border border-[#242f44] hover:border-[#c59d5f]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button @click="currentMonth = new Date().getMonth() + 1; currentYear = new Date().getFullYear(); fetchMonthData()"
                                        type="button"
                                        class="px-4 py-2 rounded-lg bg-[#0a0d14] hover:bg-[#1e2538] text-gray-300 text-xs uppercase font-semibold tracking-wider transition-colors border border-[#242f44]">
                                    Bulan Ini
                                </button>
                                <button @click="nextMonth()"
                                        type="button"
                                        class="p-2.5 rounded-lg bg-[#0a0d14] hover:bg-[#c59d5f] hover:text-black text-gray-300 transition-colors border border-[#242f44] hover:border-[#c59d5f]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Loading Spinner Overlay -->
                        <div x-show="isLoading"
                             class="py-20 flex items-center justify-center text-[#c59d5f]"
                             style="display: none;">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                <span class="text-xs text-gray-400">Memuat kalender jadwal...</span>
                            </div>
                        </div>

                        <!-- Calendar Grid -->
                        <div x-show="!isLoading" class="p-5">

                            <!-- Day Name Headers -->
                            <div class="grid grid-cols-7 gap-2 mb-3">
                                <template x-for="day in dayNames" :key="day">
                                    <div class="text-center text-[11px] uppercase tracking-wider font-semibold text-gray-400 py-2" x-text="day"></div>
                                </template>
                            </div>

                            <!-- Date Cells Grid -->
                            <div class="grid grid-cols-7 gap-2">
                                <template x-for="(d, index) in calendarDays" :key="index">
                                    <button type="button"
                                            @click="onDayClick(d)"
                                            :disabled="!d.isCurrentMonth || d.isPast"
                                            :title="d.isBooked ? `Terisi: ${d.bookingInfo?.package_name ?? 'Jadwal Terisi'}` : (d.isPast ? 'Tanggal Telah Berlalu' : `Klik untuk cek ${d.dateString}`)"
                                            class="relative h-14 rounded-lg flex flex-col items-center justify-center font-medium transition-all text-sm"
                                            :class="{
                                                'opacity-20 cursor-not-allowed': !d.isCurrentMonth,
                                                'opacity-40 cursor-not-allowed text-gray-500 bg-transparent': d.isCurrentMonth && d.isPast,
                                                'bg-rose-950/60 border border-rose-500/50 text-rose-300 hover:bg-rose-900/80 cursor-pointer': d.isCurrentMonth && !d.isPast && d.isBooked,
                                                'bg-[#141b28] border border-[#1e2538] text-gray-200 hover:border-[#c59d5f]/60 hover:bg-[#1a2334] cursor-pointer': d.isCurrentMonth && !d.isPast && !d.isBooked && selectedDate !== d.dateString,
                                                'bg-[#c59d5f] border-2 border-white text-black font-bold shadow-lg ring-2 ring-[#c59d5f]/50': d.isCurrentMonth && selectedDate === d.dateString
                                            }">
                                        <span x-text="d.day"></span>

                                        <!-- Status Dot Indicator -->
                                        <span x-show="d.isCurrentMonth && !d.isPast && d.isBooked"
                                              class="absolute bottom-1.5 w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        <span x-show="d.isCurrentMonth && !d.isPast && !d.isBooked"
                                              class="absolute bottom-1.5 w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- Selected Date Info Strip -->
                            <div x-show="checkResult"
                                 x-transition
                                 class="mt-5 p-4 rounded-lg text-sm border"
                                 :class="checkResult?.available ? 'bg-emerald-950/40 border-emerald-500/50 text-emerald-200' : 'bg-rose-950/40 border-rose-500/50 text-rose-200'"
                                 style="display: none;">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl" x-text="checkResult?.available ? '✓' : '⚠'"></div>
                                    <div>
                                        <div class="font-bold mb-0.5" x-text="checkResult?.formatted_date"></div>
                                        <p class="text-xs leading-relaxed" x-text="checkResult?.message"></p>
                                        <div x-show="checkResult?.available" class="mt-2">
                                            <a :href="'{{ route('packages.catalog') }}?event_date=' + (selectedDate || checkResult?.date)"
                                               class="text-xs underline font-semibold text-white hover:text-[#c59d5f]">
                                                Pilih Paket & Lanjut Pemesanan &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booked Dates Summary for this Month -->
                            <div x-show="bookedDates.length > 0" class="mt-5 pt-5 border-t border-[#1e2538]">
                                <div class="text-[11px] uppercase tracking-wider font-semibold text-[#c59d5f] mb-3">
                                    Jadwal Terisi Bulan Ini (<span x-text="bookedDates.length"></span> tanggal)
                                </div>
                                <div class="space-y-2">
                                    <template x-for="booked in bookedDates" :key="booked.date">
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-rose-950/20 border border-rose-500/20 text-xs">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-rose-400 shrink-0"></span>
                                                <span class="font-semibold text-rose-200" x-text="booked.date"></span>
                                            </div>
                                            <div class="text-right text-gray-400">
                                                <div x-text="booked.package_name" class="text-rose-200/80"></div>
                                                <div class="text-[10px]" x-text="booked.status_label"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Empty Month State -->
                            <div x-show="!isLoading && bookedDates.length === 0" class="mt-5 pt-5 border-t border-[#1e2538] text-center">
                                <div class="text-[#c59d5f] text-3xl mb-2">🎉</div>
                                <div class="text-sm font-semibold text-white">Semua Tanggal di Bulan Ini Masih Tersedia!</div>
                                <div class="text-xs text-gray-400 mt-1">Jadwal bulan ini belum ada yang terisi. Klik tanggal manapun untuk segera memesannya.</div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Info & CTA Strip -->
            <div class="mt-14 p-8 rounded-xl bg-gradient-to-r from-[#111722] to-[#141b28] border border-[#c59d5f]/30 text-center">
                <h3 class="font-serif text-xl font-bold text-white mb-2">Butuh Bantuan Menentukan Tanggal Terbaik?</h3>
                <p class="text-xs text-gray-400 mb-6 max-w-xl mx-auto">Konsultan Vantara Production siap membantu Anda memilih tanggal yang tepat, paket sesuai kebutuhan, dan memberikan estimasi anggaran secara transparan.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="https://wa.me/6282282422317?text=Halo%20Vantara%20Production,%20saya%20ingin%20konsultasi%20ketersediaan%20tanggal%20acara%20saya."
                       target="_blank"
                       class="px-8 py-3.5 bg-[#25D366] hover:bg-[#20ba5a] text-white font-semibold text-xs uppercase tracking-wider rounded shadow-xl flex items-center gap-2 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Hubungi Konsultan via WhatsApp
                    </a>
                    <a href="{{ route('packages.catalog') }}"
                       class="px-8 py-3.5 border border-[#c59d5f] text-[#dfc48e] hover:bg-[#c59d5f] hover:text-black font-semibold text-xs uppercase tracking-wider rounded transition-all">
                        Lihat Seluruh Paket Layanan
                    </a>
                </div>
            </div>

        </div>
    </section>

@endsection

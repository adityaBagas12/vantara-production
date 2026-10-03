@extends('layouts.app')

@section('title', 'Form Pemesanan & Data Acara — Vantara Production')
@section('meta_description', 'Lengkapi formulir identitas dan tanggal acara Anda untuk melakukan pemesanan sewa peralatan sound system & hiburan Vantara Production.')

@section('content')

    <!-- Page Header -->
    <section class="relative py-16 bg-[#0c1017] border-b border-[#1b2438]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#c59d5f] block mb-2">Langkah 2 dari 2</span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-3">Formulir Data Pemesan & Acara</h1>
            <div class="flex items-center justify-center gap-3 my-3">
                <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
                <span class="text-[#c59d5f] text-xs">✦</span>
                <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
            </div>
            <p class="max-w-xl mx-auto text-xs text-gray-300">
                Lengkapi identitas diri dan detail pelaksanaan acara Anda di bawah ini untuk mengirimkan pengajuan pesanan ke sistem database Vantara Production.
            </p>
        </div>
    </section>

    <!-- Main Checkout Section -->
    <section class="py-16 bg-[#0a0d14]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-8 p-5 bg-rose-950/70 border border-rose-500/60 rounded-xl text-rose-200 text-xs">
                    <div class="font-bold text-sm mb-2 flex items-center gap-2">
                        <span>⚠</span> Silakan periksa kembali data yang Anda masukkan:
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST"
                  x-data="{
                      rentDuration: {{ $rentDuration }},
                      startDate: '{{ old('event_date', request('event_date') ?? request('date') ?? session('event_date')) }}',
                      get calculatedEndDate() {
                          if (!this.startDate) return '';
                          const parts = this.startDate.split('-');
                          if (parts.length !== 3) return this.startDate;
                          const d = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
                          d.setDate(d.getDate() + (this.rentDuration - 1));
                          const year = d.getFullYear();
                          const month = String(d.getMonth() + 1).padStart(2, '0');
                          const day = String(d.getDate()).padStart(2, '0');
                          return `${year}-${month}-${day}`;
                      },
                      get formattedEndDateIndo() {
                          if (!this.calculatedEndDate) return '';
                          const parts = this.calculatedEndDate.split('-');
                          if (parts.length !== 3) return '';
                          return `${parts[2]}/${parts[1]}/${parts[0]}`;
                      }
                  }">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                    <!-- Left: Customer Information Form (7 cols) -->
                    <div class="lg:col-span-7 space-y-8">

                        <!-- Section 1: Identitas Pemesan -->
                        <div class="p-6 sm:p-8 rounded-2xl bg-[#111722] border border-[#242f44] space-y-6">
                            <div class="border-b border-[#1e2538] pb-4">
                                <span class="text-[11px] uppercase tracking-widest text-[#c59d5f] font-semibold">Bagian 1</span>
                                <h3 class="font-serif text-xl font-bold text-white">Identitas Pemesan</h3>
                            </div>

                            <div class="space-y-4">
                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="customer_name" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                        Nama Lengkap <span class="text-rose-400">*</span>
                                    </label>
                                    <input type="text"
                                           id="customer_name"
                                           name="customer_name"
                                           value="{{ old('customer_name') }}"
                                           placeholder="Contoh: Budi Santoso"
                                           required
                                           class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none transition-colors">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Nomor WhatsApp -->
                                    <div>
                                        <label for="customer_phone" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                            Nomor WhatsApp Active <span class="text-rose-400">*</span>
                                        </label>
                                        <input type="text"
                                               id="customer_phone"
                                               name="customer_phone"
                                               value="{{ old('customer_phone') }}"
                                               placeholder="Contoh: 081234567890"
                                               required
                                               class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none transition-colors">
                                        <span class="text-[10px] text-gray-400 mt-1 block">Digunakan untuk konfirmasi & koordinasi tim.</span>
                                    </div>

                                    <!-- Email (Opsional) -->
                                    <div>
                                        <label for="customer_email" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                            Alamat Email <span class="text-gray-500 font-normal">(Opsional)</span>
                                        </label>
                                        <input type="email"
                                               id="customer_email"
                                               name="customer_email"
                                               value="{{ old('customer_email') }}"
                                               placeholder="Contoh: budi@gmail.com"
                                               class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Detail Acara -->
                        <div class="p-6 sm:p-8 rounded-2xl bg-[#111722] border border-[#242f44] space-y-6">
                            <div class="border-b border-[#1e2538] pb-4">
                                <span class="text-[11px] uppercase tracking-widest text-[#c59d5f] font-semibold">Bagian 2</span>
                                <h3 class="font-serif text-xl font-bold text-white">Detail & Tanggal Acara</h3>
                            </div>

                            <div class="space-y-4">
                                <!-- Tanggal Acara Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Tanggal Mulai Acara (Dikunci) -->
                                    <div>
                                        <label for="event_date" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                            Tanggal Mulai Acara <span class="text-emerald-400 font-normal">🔒 (Dikunci)</span>
                                        </label>
                                        <input type="date"
                                               id="event_date"
                                               name="event_date"
                                               x-model="startDate"
                                               value="{{ old('event_date', request('event_date') ?? request('date') ?? session('event_date') ?? date('Y-m-d')) }}"
                                               min="{{ date('Y-m-d') }}"
                                               required
                                               readonly
                                               class="w-full px-4 py-3 bg-[#0a0d14]/70 border border-[#242f44] text-gray-300 rounded-lg text-sm cursor-not-allowed focus:outline-none select-none opacity-80"
                                               title="Tanggal mulai acara dikunci sesuai pilihan dari keranjang/halaman cek tanggal.">
                                        <span class="text-[10px] text-emerald-400/90 mt-1 block font-medium">
                                            Otomatis terisi dari pilihan keranjang.
                                        </span>
                                    </div>

                                    <!-- Tanggal Selesai Acara (Otomatis Dikalkulasi & Dikunci) -->
                                    <div>
                                        <label for="event_end_date" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                            Tanggal Selesai Acara <span class="text-emerald-400 font-normal">🔒 (Dikunci)</span>
                                        </label>
                                        <input type="date"
                                               id="event_end_date"
                                               name="event_end_date"
                                               :value="calculatedEndDate"
                                               readonly
                                               class="w-full px-4 py-3 bg-[#0a0d14]/70 border border-[#242f44] text-gray-300 rounded-lg text-sm cursor-not-allowed focus:outline-none select-none opacity-80"
                                               title="Tanggal selesai dihitung otomatis dari durasi sewa di keranjang dan dikunci.">
                                        <span class="text-[10px] text-emerald-400/90 mt-1 block font-medium">
                                            Otomatis terisi (<span x-text="rentDuration > 1 ? rentDuration + ' Hari Berturut-turut' : 'Selesai di Hari yang Sama'"></span>).
                                        </span>
                                    </div>
                                </div>

                                <!-- Multi-day duration banner -->
                                <div class="p-3.5 rounded-lg bg-[#141b28] border border-[#c59d5f]/40 text-xs text-[#dfc48e] flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span>📅</span>
                                        <span>Durasi Sewa: <strong x-text="`${rentDuration} Hari`"></strong></span>
                                        <span class="text-gray-400">•</span>
                                        <span class="text-gray-300 font-medium" x-text="rentDuration === 1 ? 'Selesai pada hari yang sama' : `Selesai pada tanggal besoknya (${formattedEndDateIndo})`"></span>
                                    </div>
                                </div>

                                <!-- Alamat Lengkap Acara -->
                                <div>
                                    <label for="event_address" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                        Alamat Lengkap Lokasi Acara <span class="text-rose-400">*</span>
                                    </label>
                                    <textarea id="event_address"
                                              name="event_address"
                                              rows="3"
                                              placeholder="Contoh: Gedung Serbaguna H. Nawi, Jl. Raya Pasar Minggu No. 45, Jakarta Selatan"
                                              required
                                              class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none transition-colors">{{ old('event_address') }}</textarea>
                                </div>

                                <!-- Catatan / Request Tambahan -->
                                <div>
                                    <label for="notes" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                        Catatan Khusus / Permintaan Tambahan <span class="text-gray-500 font-normal">(Opsional)</span>
                                    </label>
                                    <textarea id="notes"
                                              name="notes"
                                              rows="3"
                                              placeholder="Contoh: Membutuhkan genset 50 KVA Tambahan, request warna lighting merah emas, atau jam gladi bersih pukul 08:00 WIB."
                                              class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none transition-colors">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Order Summary Sidebar (5 cols) -->
                    <div class="lg:col-span-5">
                        <div class="p-6 sm:p-8 rounded-2xl bg-[#111722] border border-[#c59d5f]/40 shadow-2xl space-y-6 sticky top-24">
                            <h3 class="font-serif text-xl font-bold text-white border-b border-[#1e2538] pb-4">Ringkasan Pesanan</h3>

                            <!-- Package Items Preview List -->
                            <div class="space-y-3 max-h-60 overflow-y-auto pr-1 scrollbar-thin">
                                @foreach ($cart as $item)
                                    <div class="p-3 rounded-lg bg-[#090c14] border border-[#1e2538] flex items-center justify-between text-xs">
                                        <div>
                                            <div class="font-semibold text-white truncate max-w-[200px]">{{ $item['name'] }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $item['quantity'] }} Hari · {{ $item['category'] }}</div>
                                        </div>
                                        <div class="font-serif font-bold text-[#dfc48e] shrink-0">
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Cost Breakdown -->
                            <div class="pt-4 border-t border-[#1e2538] space-y-3 text-xs">
                                <div class="flex items-center justify-between text-gray-300">
                                    <span>Subtotal Paket ({{ $rentDuration }} Hari):</span>
                                    <span class="font-semibold text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-400">
                                    <span>Biaya Pengantaran & Pemasangan:</span>
                                    <span class="text-emerald-400 font-semibold">Gratis / Termasuk</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-400">
                                    <span>Kru Operational Standby:</span>
                                    <span class="text-emerald-400 font-semibold">Gratis / Termasuk</span>
                                </div>
                            </div>

                            <!-- Total Summary -->
                            <div class="pt-4 border-t border-[#1e2538]">
                                <div class="flex items-baseline justify-between mb-2">
                                    <span class="text-xs font-semibold uppercase text-gray-300">Total Estimasi Transaksi</span>
                                    <div class="font-serif text-2xl font-bold text-[#dfc48e]">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 leading-relaxed mb-6">
                                    Setelah formulir dikirim, pesanan Anda akan tercatat ke database kami dan Anda akan langsung diarahkan ke WhatsApp Admin untuk proses konfirmasi.
                                </p>

                                <button type="submit"
                                        class="w-full py-4 px-4 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black font-bold text-xs uppercase tracking-[0.15em] rounded shadow-xl flex items-center justify-center gap-2 transition-all hover:scale-[1.02]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    Kirim Pemesanan & Lanjut WA
                                </button>
                            </div>

                            <!-- Back to cart link -->
                            <div class="text-center pt-2">
                                <a href="{{ route('cart.index') }}" class="text-xs text-gray-400 hover:text-white underline">
                                    &larr; Kembali ke Keranjang
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </section>

@endsection

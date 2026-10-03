@extends('layouts.app')

@section('title', 'Keranjang Pemesanan — Vantara Production')
@section('meta_description', 'Tinjau daftar paket rental sound system dan peralatan hiburan yang telah Anda pilih sebelum melanjutkan ke pengisian data acara.')

@section('content')

    <!-- Header Section -->
    <section class="relative py-16 bg-[#0c1017] border-b border-[#1b2438]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#c59d5f] block mb-2">Langkah 1 dari 2</span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-3">Keranjang Pemesanan Paket</h1>
            <div class="flex items-center justify-center gap-3 my-3">
                <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
                <span class="text-[#c59d5f] text-xs">✦</span>
                <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
            </div>
            <p class="max-w-xl mx-auto text-xs text-gray-300">
                Periksa kembali pilihan paket layanan rental Anda di bawah ini sebelum melanjutkan pengisian data pemesan.
            </p>
        </div>
    </section>

    <!-- Cart Main Content -->
    <section class="py-16 bg-[#0a0d14] min-h-[600px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-950/60 border border-emerald-500/50 rounded-xl text-emerald-200 text-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-base">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-950/60 border border-rose-500/50 rounded-xl text-rose-200 text-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-base">✕</span>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if (empty($cart))
                <!-- Empty Cart State -->
                <div class="text-center py-20 bg-[#111722] rounded-2xl border border-[#242f44] p-12 max-w-2xl mx-auto">
                    <div class="w-20 h-20 mx-auto rounded-full bg-[#c59d5f]/10 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] mb-5 text-3xl">
                        🛒
                    </div>
                    <h3 class="font-serif text-2xl font-bold text-white mb-2">Keranjang Anda Masih Kosong</h3>
                    <p class="text-xs text-gray-400 max-w-md mx-auto mb-8 leading-relaxed">
                        Anda belum menambahkan paket rental ke dalam keranjang. Jelajahi katalog kami untuk memilih paket sound system, lighting, videobooth, atau band wedding.
                    </p>
                    <a href="{{ route('packages.catalog') }}"
                       class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] text-black font-bold text-xs uppercase tracking-wider rounded shadow-lg transition-all hover:scale-105">
                        <span>Pilih Paket Layanan</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                    <!-- Left: Cart Items List (8 cols) -->
                    <div class="lg:col-span-8 space-y-6">

                        <div class="flex items-center justify-between pb-4 border-b border-[#1e2538]">
                            <div class="text-xs text-gray-400">
                                Terdapat <span class="font-bold text-white">{{ count($cart) }}</span> jenis paket dalam keranjang Anda
                            </div>
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                                @csrf
                                <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 hover:underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Kosongkan Keranjang
                                </button>
                            </form>
                        </div>

                        <!-- Item Rows -->
                        <div class="space-y-4">
                            @foreach ($cart as $id => $item)
                                <div class="p-5 rounded-xl bg-[#111722] border border-[#242f44] hover:border-[#c59d5f]/40 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                                    
                                    <!-- Image & Package Info -->
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="w-20 h-20 rounded-lg overflow-hidden bg-black shrink-0 border border-[#242f44]">
                                            <img src="{{ $item['image_path'] ?? 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=400&auto=format&fit=crop' }}"
                                                 alt="{{ $item['name'] }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-semibold text-[#c59d5f] tracking-wider block mb-0.5">{{ $item['category'] }}</span>
                                            <h4 class="font-serif font-bold text-white text-base">
                                                <a href="{{ route('packages.show', $item['slug']) }}" class="hover:text-[#c59d5f] transition-colors">
                                                    {{ $item['name'] }}
                                                </a>
                                            </h4>
                                            <div class="text-xs text-[#dfc48e] font-semibold mt-1">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-[10px] text-gray-400 font-normal">/ hari</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity Form & Subtotal -->
                                    <div class="flex items-center justify-between w-full sm:w-auto gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-[#1e2538]">
                                        <!-- Quantity Form -->
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <label class="text-[11px] text-gray-400 shrink-0">Durasi (Hari):</label>
                                            <input type="number"
                                                   name="quantity"
                                                   value="{{ $item['quantity'] }}"
                                                   min="1"
                                                   onchange="this.form.submit()"
                                                   class="w-16 px-2 py-1.5 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] text-center rounded text-xs text-white focus:outline-none">
                                        </form>

                                        <!-- Subtotal -->
                                        <div class="text-right shrink-0">
                                            <div class="text-[10px] text-gray-400 uppercase tracking-wider">Subtotal</div>
                                            <div class="font-serif font-bold text-white text-base">
                                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <!-- Remove Button -->
                                        <form action="{{ route('cart.destroy', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="Hapus paket"
                                                    class="p-2 text-gray-500 hover:text-rose-400 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <div class="pt-4 flex items-center justify-between">
                            <a href="{{ route('packages.catalog') }}" class="text-xs text-[#c59d5f] hover:underline font-semibold flex items-center gap-1">
                                &larr; Tambah Paket Lain
                            </a>
                        </div>

                    </div>

                    <!-- Right: Summary Card (4 cols) -->
                    <div class="lg:col-span-4">
                        <div class="p-6 rounded-2xl bg-[#111722] border border-[#c59d5f]/40 shadow-2xl space-y-6 sticky top-24">
                            <h3 class="font-serif text-lg font-bold text-white border-b border-[#1e2538] pb-3">Ringkasan Biaya</h3>

                            <div class="space-y-3 text-xs">
                                <div class="flex items-center justify-between text-gray-300">
                                    <span>Total Estimasi Paket:</span>
                                    <span class="font-semibold text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-400">
                                    <span>Pemasangan & Antar:</span>
                                    <span class="text-emerald-400 font-semibold">Termasuk</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-400">
                                    <span>Kru & Operator:</span>
                                    <span class="text-emerald-400 font-semibold">Termasuk</span>
                                </div>
                            </div>

                            <!-- Event Date Selection Block -->
                            <div class="p-3.5 rounded-xl bg-[#090c14] border border-[#242f44] space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="cart_event_date" class="text-[11px] font-semibold text-gray-300 uppercase tracking-wider">
                                        📅 Tanggal Acara Pelaksanaan:
                                    </label>
                                    @if(session('event_date') || request('event_date') || request('date'))
                                        <span class="text-[10px] text-emerald-400 font-semibold lowercase">(terisi)</span>
                                    @endif
                                </div>
                                <form action="{{ route('cart.index') }}" method="GET" class="flex items-center gap-2">
                                    <input type="date"
                                           id="cart_event_date"
                                           name="event_date"
                                           value="{{ old('event_date', request('event_date') ?? request('date') ?? session('event_date')) }}"
                                           min="{{ date('Y-m-d') }}"
                                           onchange="this.form.submit()"
                                           class="w-full px-3 py-2 bg-[#121824] border border-[#242f44] focus:border-[#c59d5f] rounded text-xs text-white focus:outline-none transition-colors">
                                </form>
                                <span class="text-[10px] text-gray-400 block leading-tight">Tanggal ini akan otomatis diisikan pada formulir checkout.</span>
                            </div>

                            <div class="pt-4 border-t border-[#1e2538]">
                                <div class="flex items-baseline justify-between mb-1">
                                    <span class="text-xs font-semibold uppercase text-gray-300">Total Estimasi</span>
                                    <div class="font-serif text-2xl font-bold text-[#dfc48e]">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 leading-relaxed mb-6">
                                    *Penetapan Uang Muka (DP) dan negosiasi akhir dilakukan setelah data acara diisi dan dikonfirmasi via WhatsApp Admin.
                                </p>

                                <a href="{{ route('checkout.index') }}"
                                   class="block w-full text-center py-3.5 px-4 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black font-bold text-xs uppercase tracking-[0.15em] rounded shadow-lg transition-all hover:scale-[1.02]">
                                    Lanjut ke Isi Data Acara &rarr;
                                </a>
                            </div>

                            <!-- Trust guarantees -->
                            <div class="pt-4 border-t border-[#1e2538] space-y-2 text-[11px] text-gray-400">
                                <div class="flex items-center gap-2">
                                    <span class="text-[#c59d5f]">✓</span>
                                    <span>Tanpa Pembayaran Online Langsung</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[#c59d5f]">✓</span>
                                    <span>Negosiasi & Pembayaran Transparan via WA</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </section>

@endsection

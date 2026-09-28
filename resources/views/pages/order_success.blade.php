@extends('layouts.app')

@section('title', 'Pemesanan Berhasil — ' . $order->order_code)
@section('meta_description', 'Rincian pesanan Vantara Production Anda dengan kode ' . $order->order_code . '. Klik tombol untuk menghubungi Admin via WhatsApp.')

@section('content')

    <!-- Success Header Banner -->
    <section class="relative py-16 bg-[#0c1017] border-b border-[#1b2438]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-emerald-950/80 border border-emerald-500/60 flex items-center justify-center text-emerald-400 mb-4 text-3xl shadow-xl">
                ✓
            </div>
            <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#c59d5f] block mb-1">Pemesanan Terdaftar di Database</span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold text-white mb-3">Pemesanan Berhasil Dibuat!</h1>
            <div class="inline-block px-4 py-1.5 rounded-full bg-[#141b28] border border-[#c59d5f]/40 font-mono text-xs text-[#dfc48e] font-bold my-2">
                KODE PESANAN: {{ $order->order_code }}
            </div>
            <p class="max-w-xl mx-auto text-xs sm:text-sm text-gray-300 mt-2 leading-relaxed">
                Langkah terakhir: Silakan klik tombol di bawah untuk melanjutkan koordinasi, negosiasi, dan proses Uang Muka (DP) dengan Admin Vantara Production via WhatsApp.
            </p>
        </div>
    </section>

    <!-- Main Order Details -->
    <section class="py-16 bg-[#0a0d14]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Primary Action Box: WhatsApp Button -->
            <div class="p-8 rounded-2xl bg-gradient-to-r from-[#111722] via-[#161f2e] to-[#111722] border-2 border-[#25D366]/60 shadow-2xl text-center space-y-4">
                <span class="text-xs uppercase tracking-[0.2em] font-semibold text-[#25D366] block">Langkah Penting Selanjutnya</span>
                <h3 class="font-serif text-2xl font-bold text-white">Hubungi Admin Vantara Production via WhatsApp</h3>
                <p class="text-xs text-gray-300 max-w-lg mx-auto leading-relaxed">
                    Pesan otomatis telah disiapkan berisi kode transaksi <strong>{{ $order->order_code }}</strong> dan rincian lengkap pesanan Anda.
                </p>

                <div class="pt-2">
                    <a href="{{ $waUrl }}"
                       target="_blank"
                       class="inline-flex items-center gap-3 px-8 py-4 bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold text-xs uppercase tracking-[0.15em] rounded-xl shadow-xl shadow-[#25D366]/20 transition-all hover:scale-105">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Buka WhatsApp Admin Vantara</span>
                    </a>
                </div>
            </div>

            <!-- Detailed Summary Card -->
            <div class="p-8 rounded-2xl bg-[#111722] border border-[#242f44] space-y-6">

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-[#1e2538] gap-4">
                    <div>
                        <div class="text-[11px] uppercase tracking-wider text-gray-400">Rincian Transaksi</div>
                        <div class="font-serif text-2xl font-bold text-white mt-0.5">{{ $order->order_code }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                    </div>
                    <div>
                        <span class="px-3.5 py-1.5 rounded-full bg-amber-950/70 border border-amber-500/50 text-amber-200 text-xs font-semibold uppercase tracking-wider">
                            ● Status: Pending / Menunggu WA
                        </span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs border-b border-[#1e2538] pb-6">
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-400 block mb-0.5 font-medium">Nama Pemesan:</span>
                            <span class="font-semibold text-white text-sm">{{ $order->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block mb-0.5 font-medium">Nomor WhatsApp:</span>
                            <span class="font-semibold text-[#c59d5f] text-sm">{{ $order->customer_phone }}</span>
                        </div>
                        @if ($order->customer_email)
                            <div>
                                <span class="text-gray-400 block mb-0.5 font-medium">Alamat Email:</span>
                                <span class="text-gray-200">{{ $order->customer_email }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-400 block mb-0.5 font-medium">Tanggal Pelaksanaan Acara:</span>
                            <span class="font-semibold text-emerald-400 text-sm">
                                {{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 block mb-0.5 font-medium">Alamat Lokasi Acara:</span>
                            <span class="text-gray-200 leading-relaxed block">{{ $order->event_address }}</span>
                        </div>
                        @if ($order->notes)
                            <div>
                                <span class="text-gray-400 block mb-0.5 font-medium">Catatan Khusus:</span>
                                <span class="text-gray-300 italic">{{ $order->notes }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items List -->
                <div class="space-y-3">
                    <h4 class="font-serif font-bold text-white text-base">Daftar Paket yang Dipesan</h4>
                    <div class="space-y-2">
                        @foreach ($order->orderItems as $item)
                            <div class="p-4 rounded-xl bg-[#090c14] border border-[#1e2538] flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-bold text-white text-sm">{{ $item->package_name }}</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">Kuantitas: {{ $item->quantity }}x unit · Rp {{ number_format($item->unit_price, 0, ',', '.') }} / unit</div>
                                </div>
                                <div class="font-serif font-bold text-[#dfc48e] text-base">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total Amount Card -->
                <div class="p-5 rounded-xl bg-[#090c14] border border-[#c59d5f]/30 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Total Estimasi Nilai Transaksi</div>
                        <div class="text-[11px] text-gray-400 mt-0.5">Termasuk kru operator standby & antar-pasang</div>
                    </div>
                    <div class="font-serif text-3xl font-bold text-[#dfc48e]">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </div>
                </div>

            </div>

            <!-- Navigation actions -->
            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-white transition-colors">
                    &larr; Kembali ke Beranda
                </a>
                <a href="{{ route('packages.catalog') }}" class="text-xs text-[#c59d5f] hover:underline font-semibold">
                    Lihat Katalog Lainnya &rarr;
                </a>
            </div>

        </div>
    </section>

@endsection

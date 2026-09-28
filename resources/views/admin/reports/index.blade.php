@extends('layouts.admin')

@section('title', 'Analisis & Laporan Rekapitulasi — Admin Vantara Production')
@section('header_title', 'Laporan Bulanan & Rekapitulasi Penjualan')

@section('content')

    <div class="space-y-8">

        <!-- Toolbar: Period Filter & Action Buttons -->
        <div class="p-6 rounded-2xl bg-[#111722] border border-[#242f44] flex flex-col lg:flex-row items-center justify-between gap-6 shadow-xl">

            <!-- Filter Form: Date Range Selection -->
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-400 font-semibold shrink-0">Dari:</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="px-3.5 py-2 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white focus:outline-none [color-scheme:dark]">
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-400 font-semibold shrink-0">Sampai:</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="px-3.5 py-2 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white focus:outline-none [color-scheme:dark]">
                </div>

                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-[#dfc48e] to-[#c59d5f] hover:brightness-110 text-black font-bold text-xs rounded-lg transition-all shrink-0">
                    Terapkan Tanggal
                </button>

                @if (request()->filled('start_date') || request()->filled('end_date'))
                    <a href="{{ route('admin.reports.index') }}" class="text-xs text-gray-400 hover:text-rose-400 underline font-semibold ml-1">
                        Reset Filter
                    </a>
                @endif
            </form>

            <!-- Export Buttons: PDF & Print -->
            <div class="flex items-center gap-3 w-full lg:w-auto justify-end">
                <a href="{{ route('admin.reports.print', ['month' => $month, 'year' => $year, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                   target="_blank"
                   class="px-4 py-2.5 bg-[#141b28] hover:bg-[#1a2334] text-gray-200 border border-[#242f44] hover:border-[#c59d5f] rounded-lg text-xs font-semibold transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#c59d5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Preview
                </a>

                <a href="{{ route('admin.reports.pdf', ['month' => $month, 'year' => $year, 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'stream' => 1]) }}"
                   target="_blank"
                   class="px-5 py-2.5 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black font-bold text-xs uppercase tracking-wider rounded-lg shadow-lg flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Buka PDF
                </a>
            </div>

        </div>

        <!-- Metric Financial Overview Header -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Total Revenue Disetujui -->
            <div class="p-6 rounded-2xl bg-[#111722] border border-[#c59d5f]/40 shadow-xl">
                <div class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold mb-1">Total Omset Acara</div>
                <div class="font-serif text-2xl font-bold text-[#dfc48e]">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="text-[10px] text-gray-400 mt-2">Periode {{ $periodLabel }}</div>
            </div>

            <!-- Total DP Diterima -->
            <div class="p-6 rounded-2xl bg-[#111722] border border-emerald-500/40 shadow-xl">
                <div class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold mb-1">Uang Muka (DP) Terkumpul</div>
                <div class="font-serif text-2xl font-bold text-emerald-400">
                    Rp {{ number_format($totalDp, 0, ',', '.') }}
                </div>
                <div class="text-[10px] text-gray-400 mt-2">Kas Terkonfirmasi</div>
            </div>

            <!-- Sisa Tagihan -->
            <div class="p-6 rounded-2xl bg-[#111722] border border-amber-500/40 shadow-xl">
                <div class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold mb-1">Estimasi Sisa Pelunasan</div>
                <div class="font-serif text-2xl font-bold text-amber-300">
                    Rp {{ number_format($totalRemaining, 0, ',', '.') }}
                </div>
                <div class="text-[10px] text-gray-400 mt-2">Tagihan Pelunasan di Lokasi</div>
            </div>

            <!-- Total Acara -->
            <div class="p-6 rounded-2xl bg-[#111722] border border-[#242f44] shadow-xl">
                <div class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold mb-1">Total Volume Transaksi</div>
                <div class="font-serif text-3xl font-bold text-white">
                    {{ $totalOrdersCount }} <span class="text-xs font-normal text-gray-400">pesanan</span>
                </div>
                <div class="text-[10px] text-gray-400 mt-2">
                    Confirmed: {{ $confirmedCount }} · Completed: {{ $completedCount }}
                </div>
            </div>

        </div>

        <!-- Breakdown Kategori & Tabel Detail Transaksi -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Category Breakdown Summary (4 cols) -->
            <div class="lg:col-span-4 bg-[#111722] rounded-2xl border border-[#242f44] p-6 shadow-xl space-y-4">
                <div class="border-b border-[#1e2538] pb-3">
                    <h3 class="font-serif text-lg font-bold text-white">Pendapatan per Kategori</h3>
                    <p class="text-[11px] text-gray-400">Kontribusi penjualan tiap kelompok layanan.</p>
                </div>

                <div class="space-y-3">
                    @forelse ($categoryBreakdown as $cat)
                        <div class="p-4 rounded-xl bg-[#090c14] border border-[#1e2538]">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-bold text-white">{{ $cat->category }}</span>
                                <span class="text-gray-400">{{ $cat->total_items }} item dipesan</span>
                            </div>
                            <div class="font-serif font-bold text-[#dfc48e] text-base">
                                Rp {{ number_format($cat->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-gray-500">
                            Belum ada transaksi terkonfirmasi di periode ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Detailed Transactions Table (8 cols) -->
            <div class="lg:col-span-8 bg-[#111722] rounded-2xl border border-[#242f44] p-6 shadow-xl space-y-4">
                <div class="border-b border-[#1e2538] pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-serif text-lg font-bold text-white">Rincian Transaksi Periode {{ $periodLabel }}</h3>
                        <p class="text-[11px] text-gray-400">Daftar lengkap acara dan nilai pembayaran.</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ count($orders) }} data</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-300">
                        <thead class="bg-[#141b28] text-gray-400 uppercase tracking-wider text-[10px] border-b border-[#1e2538]">
                            <tr>
                                <th class="py-3 px-4">Kode & Tanggal</th>
                                <th class="py-3 px-4">Pelanggan</th>
                                <th class="py-3 px-4">Nilai Total</th>
                                <th class="py-3 px-4">DP Diterima</th>
                                <th class="py-3 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e2538]">
                            @forelse ($orders as $ord)
                                <tr class="hover:bg-[#141b28]/50">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <a href="{{ route('admin.orders.show', $ord->id) }}" class="font-mono font-bold text-[#dfc48e] hover:underline">
                                            {{ $ord->order_code }}
                                        </a>
                                        <div class="text-[10px] text-emerald-400 font-semibold mt-0.5">
                                            {{ \Carbon\Carbon::parse($ord->event_date)->translatedFormat('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-white">{{ $ord->customer_name }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $ord->customer_phone }}</div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap font-serif font-bold text-white">
                                        Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap text-emerald-400 font-semibold">
                                        Rp {{ number_format($ord->down_payment, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if ($ord->status === 'pending')
                                            <span class="px-2 py-0.5 rounded bg-amber-950 text-amber-300 text-[10px] font-semibold">Pending</span>
                                        @elseif ($ord->status === 'dp_received')
                                            <span class="px-2 py-0.5 rounded bg-blue-950 text-blue-300 text-[10px] font-semibold">DP Received</span>
                                        @elseif ($ord->status === 'confirmed')
                                            <span class="px-2 py-0.5 rounded bg-emerald-950 text-emerald-300 text-[10px] font-semibold">Confirmed</span>
                                        @elseif ($ord->status === 'completed')
                                            <span class="px-2 py-0.5 rounded bg-purple-950 text-purple-300 text-[10px] font-semibold">Completed</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-rose-950 text-rose-300 text-[10px] font-semibold">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-gray-500">
                                        Tidak ada pesanan pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- History Archive Section -->
        <div class="bg-[#111722] rounded-2xl border border-[#242f44] p-6 shadow-xl space-y-4">
            <div class="border-b border-[#1e2538] pb-3">
                <h3 class="font-serif text-lg font-bold text-white">Arsip Riwayat Laporan Bulanan</h3>
                <p class="text-[11px] text-gray-400">Ringkasan performa penjualan 12 bulan terakhir.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($historyMonthly as $h)
                    @php
                        $hMonthName = \Carbon\Carbon::createFromDate($h->year_val, $h->month_val, 1)->translatedFormat('F Y');
                    @endphp
                    <div class="p-4 rounded-xl bg-[#090c14] border border-[#1e2538] space-y-2 hover:border-[#c59d5f]/40 transition-all">
                        <div class="flex items-center justify-between text-xs font-bold text-white">
                            <span>{{ $hMonthName }}</span>
                            <span class="text-[10px] text-gray-400 font-normal">{{ $h->total_orders }} acara</span>
                        </div>
                        <div class="font-serif text-base font-bold text-[#dfc48e]">
                            Rp {{ number_format($h->revenue, 0, ',', '.') }}
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-[#1e2538] text-[10px]">
                            <a href="{{ route('admin.reports.index', ['month' => $h->month_val, 'year' => $h->year_val]) }}" class="text-[#c59d5f] hover:underline font-semibold">
                                Lihat Laporan &rarr;
                            </a>
                            <a href="{{ route('admin.reports.pdf', ['month' => $h->month_val, 'year' => $h->year_val, 'stream' => 1]) }}" target="_blank" class="text-gray-400 hover:text-white font-semibold">
                                👁️ Buka PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

@endsection

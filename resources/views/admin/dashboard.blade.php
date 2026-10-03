@extends('layouts.admin')

@section('title', 'Dashboard Admin — Vantara Production')
@section('header_title', 'Ringkasan Operasional & Penjualan')

@section('header_actions')
    <a href="{{ route('admin.reports.pdf', ['month' => date('n'), 'year' => date('Y'), 'stream' => 1]) }}"
       target="_blank"
       class="px-3.5 py-1.5 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black text-xs font-bold rounded-lg shadow-md flex items-center gap-1.5 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        <span>Buka PDF</span>
    </a>
@endsection

@section('content')

    <div class="space-y-8">

        <!-- 4 Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Metric 1: Total Pendapatan Disetujui -->
            <div class="p-6 rounded-2xl bg-[#111722] border border-[#242f44] shadow-xl relative overflow-hidden">
                <div class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Total Omset Disetujui</div>
                <div class="font-serif text-2xl font-bold text-[#dfc48e]">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="text-[10px] text-gray-400 mt-2">
                    Total DP Diterima: <span class="text-emerald-400 font-semibold">Rp {{ number_format($totalDpCollected, 0, ',', '.') }}</span>
                </div>
                <div class="absolute right-4 top-4 text-[#c59d5f]/20 text-3xl font-serif">💰</div>
            </div>

            <!-- Metric 2: Pesanan Butuh Tindakan (Pending) -->
            <div class="p-6 rounded-2xl bg-[#111722] border border-amber-500/40 shadow-xl relative overflow-hidden">
                <div class="text-[11px] uppercase tracking-wider text-amber-400 font-semibold mb-1">Pesanan Baru (Pending)</div>
                <div class="font-serif text-3xl font-bold text-white">
                    {{ $pendingCount }}
                    <span class="text-xs font-normal text-amber-300">transaksi</span>
                </div>
                <div class="text-[10px] text-gray-400 mt-2">Butuh respon & verifikasi admin</div>
                <div class="absolute right-4 top-4 text-amber-400/20 text-3xl font-serif">⏳</div>
            </div>

            <!-- Metric 3: Pesanan Dikonfirmasi & DP Received -->
            <div class="p-6 rounded-2xl bg-[#111722] border border-emerald-500/40 shadow-xl relative overflow-hidden">
                <div class="text-[11px] uppercase tracking-wider text-emerald-400 font-semibold mb-1">Dikonfirmasi / DP</div>
                <div class="font-serif text-3xl font-bold text-white">
                    {{ $confirmedCount }}
                    <span class="text-xs font-normal text-emerald-300">acara</span>
                </div>
                <div class="text-[10px] text-gray-400 mt-2">Jadwal resmi terkunci</div>
                <div class="absolute right-4 top-4 text-emerald-400/20 text-3xl font-serif">✓</div>
            </div>

            <!-- Metric 4: Total Katalog Paket Aktif -->
            <div class="p-6 rounded-2xl bg-[#111722] border border-[#242f44] shadow-xl relative overflow-hidden">
                <div class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Paket Layanan Aktif</div>
                <div class="font-serif text-3xl font-bold text-white">
                    {{ $activePackagesCount }}
                    <span class="text-xs font-normal text-gray-400">paket</span>
                </div>
                <div class="text-[10px] text-gray-400 mt-2">
                    <a href="{{ route('admin.packages.index') }}" class="text-[#c59d5f] hover:underline">Kelola Katalog &rarr;</a>
                </div>
                <div class="absolute right-4 top-4 text-[#c59d5f]/20 text-3xl font-serif">📦</div>
            </div>

        </div>

        <!-- 2 Column Layout: Recent Orders & Monthly Recap -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left: Recent Orders List (7 cols) -->
            <div class="lg:col-span-7 bg-[#111722] rounded-2xl border border-[#242f44] p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-[#1e2538] pb-4">
                    <div>
                        <h3 class="font-serif text-lg font-bold text-white">Pesanan Masuk Terkini</h3>
                        <p class="text-[11px] text-gray-400">Daftar transaksi terbaru dari pelanggan yang memerlukan tindakan.</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#c59d5f] hover:underline font-semibold">
                        Lihat Semua Pesanan &rarr;
                    </a>
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="text-center py-10 text-gray-500 text-xs">
                        Belum ada pesanan masuk saat ini.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($recentOrders as $ord)
                            <div class="p-4 rounded-xl bg-[#090c14] border border-[#1e2538] hover:border-[#c59d5f]/40 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-mono text-xs font-bold text-[#dfc48e]">{{ $ord->order_code }}</span>
                                        @if ($ord->status === 'pending')
                                            <span class="px-2 py-0.5 rounded-full bg-amber-950/70 border border-amber-500/50 text-amber-300 text-[10px] font-semibold">Pending</span>
                                        @elseif ($ord->status === 'dp_received')
                                            <span class="px-2 py-0.5 rounded-full bg-blue-950/70 border border-blue-500/50 text-blue-300 text-[10px] font-semibold">DP Received</span>
                                        @elseif ($ord->status === 'confirmed')
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-950/70 border border-emerald-500/50 text-emerald-300 text-[10px] font-semibold">Confirmed</span>
                                        @elseif ($ord->status === 'completed')
                                            <span class="px-2 py-0.5 rounded-full bg-purple-950/70 border border-purple-500/50 text-purple-300 text-[10px] font-semibold">Completed</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full bg-rose-950/70 border border-rose-500/50 text-rose-300 text-[10px] font-semibold">Cancelled</span>
                                        @endif
                                    </div>
                                    <div class="text-xs font-semibold text-white">{{ $ord->customer_name }}</div>
                                    <div class="text-[11px] text-gray-400">
                                        📅 Acara: {{ \Carbon\Carbon::parse($ord->event_date)->translatedFormat('d M Y') }} · {{ $ord->customer_phone }}
                                    </div>
                                </div>

                                <div class="text-right shrink-0 w-full sm:w-auto flex sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 pt-2 sm:pt-0 border-[#1e2538]">
                                    <div class="font-serif font-bold text-white text-sm">
                                        Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                                    </div>
                                    <a href="{{ route('admin.orders.show', $ord->id) }}"
                                       class="mt-1 text-xs text-[#c59d5f] hover:underline font-semibold">
                                        Proses Pesanan &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Monthly Sales Performance Table (5 cols) -->
            <div class="lg:col-span-5 bg-[#111722] rounded-2xl border border-[#242f44] p-6 shadow-xl space-y-4">
                <div class="border-b border-[#1e2538] pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-serif text-lg font-bold text-white">Rekap Transaksi Bulanan {{ date('Y') }}</h3>
                        <p class="text-[11px] text-gray-400">Performa transaksi yang disetujui selama tahun berjalan.</p>
                    </div>
                    <a href="{{ route('admin.reports.index') }}" class="text-xs text-[#c59d5f] hover:underline font-semibold shrink-0">
                        Lihat Laporan &rarr;
                    </a>
                </div>

                <div class="space-y-2 max-h-[380px] overflow-y-auto pr-1">
                    @foreach ($monthlyChartData as $m)
                        <div class="p-3 rounded-xl bg-[#090c14] border border-[#1e2538] flex items-center justify-between text-xs hover:border-[#c59d5f]/40 transition-all">
                            <div>
                                <div class="font-bold text-white">{{ $m['month'] }}</div>
                                <div class="text-[10px] text-gray-400">{{ $m['orders'] }} total pesanan</div>
                            </div>
                            <div class="flex items-center gap-3 text-right">
                                <div>
                                    <div class="font-serif font-bold text-[#dfc48e]">
                                        Rp {{ number_format($m['revenue'], 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-emerald-400 font-medium">
                                        DP: Rp {{ number_format($m['dp_collected'], 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 pl-2 border-l border-[#1e2538]">
                                    <a href="{{ route('admin.reports.pdf', ['month' => $m['month_num'], 'year' => date('Y'), 'stream' => 1]) }}"
                                       target="_blank"
                                       title="Buka PDF {{ $m['month'] }}"
                                       class="px-2.5 py-1 rounded bg-[#1e2538] hover:bg-[#c59d5f] hover:text-black text-[10px] font-semibold text-gray-300 transition-all flex items-center gap-1 group">
                                        <svg class="w-3 h-3 text-[#c59d5f] group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Buka PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

@endsection

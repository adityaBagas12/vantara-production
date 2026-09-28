@extends('layouts.admin')

@section('title', 'Kelola Transaksi / Pesanan — Admin Vantara Production')
@section('header_title', 'Daftar Pesanan & Transaksi')

@section('content')

    <div class="space-y-6">

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-[#1e2538] scrollbar-none">
            <a href="{{ route('admin.orders.index', array_filter(['search' => request('search')])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2 {{ !request('status') ? 'bg-[#c59d5f] text-black font-bold shadow-lg' : 'bg-[#111722] text-gray-400 hover:text-white border border-[#242f44]' }}">
                <span>Semua Pesanan</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('status') ? 'bg-black text-[#dfc48e]' : 'bg-[#1a2334] text-gray-300' }}">
                    {{ $statusCounts['all'] }}
                </span>
            </a>

            <a href="{{ route('admin.orders.index', array_filter(['status' => 'pending', 'search' => request('search')])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2 {{ request('status') === 'pending' ? 'bg-amber-500 text-black font-bold shadow-lg' : 'bg-[#111722] text-amber-400 hover:text-amber-300 border border-amber-500/30' }}">
                <span>Pending (Baru)</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-950 text-amber-200">
                    {{ $statusCounts['pending'] }}
                </span>
            </a>

            <a href="{{ route('admin.orders.index', array_filter(['status' => 'dp_received', 'search' => request('search')])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2 {{ request('status') === 'dp_received' ? 'bg-blue-500 text-black font-bold shadow-lg' : 'bg-[#111722] text-blue-400 hover:text-blue-300 border border-blue-500/30' }}">
                <span>DP Received</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-blue-950 text-blue-200">
                    {{ $statusCounts['dp_received'] }}
                </span>
            </a>

            <a href="{{ route('admin.orders.index', array_filter(['status' => 'confirmed', 'search' => request('search')])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2 {{ request('status') === 'confirmed' ? 'bg-emerald-500 text-black font-bold shadow-lg' : 'bg-[#111722] text-emerald-400 hover:text-emerald-300 border border-emerald-500/30' }}">
                <span>Confirmed</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-950 text-emerald-200">
                    {{ $statusCounts['confirmed'] }}
                </span>
            </a>

            <a href="{{ route('admin.orders.index', array_filter(['status' => 'completed', 'search' => request('search')])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2 {{ request('status') === 'completed' ? 'bg-purple-500 text-black font-bold shadow-lg' : 'bg-[#111722] text-purple-400 hover:text-purple-300 border border-purple-500/30' }}">
                <span>Completed</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-purple-950 text-purple-200">
                    {{ $statusCounts['completed'] }}
                </span>
            </a>

            <a href="{{ route('admin.orders.index', array_filter(['status' => 'cancelled', 'search' => request('search')])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2 {{ request('status') === 'cancelled' ? 'bg-rose-500 text-black font-bold shadow-lg' : 'bg-[#111722] text-rose-400 hover:text-rose-300 border border-rose-500/30' }}">
                <span>Cancelled</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-950 text-rose-200">
                    {{ $statusCounts['cancelled'] }}
                </span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="p-5 rounded-2xl bg-[#111722] border border-[#242f44]">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode pesanan, nama pemesan, no WA, atau lokasi..."
                       class="px-4 py-2.5 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white placeholder-gray-500 focus:outline-none flex-1">

                <button type="submit" class="px-6 py-2.5 bg-[#c59d5f] hover:bg-[#d4ab63] text-black font-bold text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0">
                    Cari Pesanan
                </button>

                @if (request('search') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-400 hover:text-white underline shrink-0">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-[#111722] rounded-2xl border border-[#242f44] overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-300">
                    <thead class="bg-[#141b28] text-gray-400 uppercase tracking-wider text-[10px] border-b border-[#1e2538]">
                        <tr>
                            <th class="py-4 px-6">Kode Pesanan</th>
                            <th class="py-4 px-6">Pelanggan & Kontak</th>
                            <th class="py-4 px-6">Tanggal Acara</th>
                            <th class="py-4 px-6">Total Nilai</th>
                            <th class="py-4 px-6 text-center">Status Pesanan</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1e2538]">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-[#141b28]/50 transition-colors">

                                <!-- Order Code -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-[#dfc48e] hover:underline text-sm block">
                                        {{ $order->order_code }}
                                    </a>
                                    <div class="text-[10px] text-gray-500 mt-0.5">
                                        Masuk: {{ $order->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>

                                <!-- Customer -->
                                <td class="py-4 px-6">
                                    <div class="font-bold text-white text-sm">{{ $order->customer_name }}</div>
                                    <div class="text-[11px] text-[#c59d5f] mt-0.5">📱 {{ $order->customer_phone }}</div>
                                    <div class="text-[10px] text-gray-400 truncate max-w-xs mt-0.5">📍 {{ $order->event_address }}</div>
                                </td>

                                <!-- Event Date -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="font-bold text-emerald-400">
                                        {{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-[10px] text-gray-400">
                                        {{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('l') }}
                                    </div>
                                </td>

                                <!-- Price & DP -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="font-serif font-bold text-white text-sm">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </div>
                                    @if ($order->down_payment > 0)
                                        <div class="text-[10px] text-emerald-400 font-semibold mt-0.5">
                                            DP: Rp {{ number_format($order->down_payment, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="text-[10px] text-gray-500 italic mt-0.5">Belum ada DP</div>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    @if ($order->status === 'pending')
                                        <span class="px-3 py-1 rounded-full bg-amber-950/80 border border-amber-500/60 text-amber-300 text-[10px] font-bold uppercase tracking-wider">
                                            ● Pending
                                        </span>
                                    @elseif ($order->status === 'dp_received')
                                        <span class="px-3 py-1 rounded-full bg-blue-950/80 border border-blue-500/60 text-blue-300 text-[10px] font-bold uppercase tracking-wider">
                                            ● DP Diterima
                                        </span>
                                    @elseif ($order->status === 'confirmed')
                                        <span class="px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/60 text-emerald-300 text-[10px] font-bold uppercase tracking-wider">
                                            ✓ Confirmed
                                        </span>
                                    @elseif ($order->status === 'completed')
                                        <span class="px-3 py-1 rounded-full bg-purple-950/80 border border-purple-500/60 text-purple-300 text-[10px] font-bold uppercase tracking-wider">
                                            ✓ Selesai
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-rose-950/80 border border-rose-500/60 text-rose-300 text-[10px] font-bold uppercase tracking-wider">
                                            ✕ Batal
                                        </span>
                                    @endif
                                </td>

                                <!-- Action Link -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="px-3 py-1.5 bg-[#141b28] hover:bg-[#1e2538] text-[#dfc48e] border border-[#242f44] hover:border-[#c59d5f] rounded-lg text-xs font-semibold transition-all">
                                        Proses & Detail &rarr;
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 text-xs">
                                    Tidak ada pesanan yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="p-4 border-t border-[#1e2538]">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection

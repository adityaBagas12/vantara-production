@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_code)
@section('header_title', 'Detail & Pengelolaan Status Transaksi')

@section('content')

    <div class="space-y-8">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-400 hover:text-white transition-colors">
                &larr; Kembali ke Daftar Pesanan
            </a>
            <span class="font-mono text-xs text-[#dfc48e] bg-[#111722] border border-[#242f44] px-3 py-1 rounded-full font-bold">
                {{ $order->order_code }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Column: Customer & Order Items Info (7 cols) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- Order Header Card -->
                <div class="p-6 rounded-2xl bg-[#111722] border border-[#242f44] shadow-xl space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-[#1e2538] gap-3">
                        <div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Informasi Pemesan</div>
                            <h3 class="font-serif text-2xl font-bold text-white mt-0.5">{{ $order->customer_name }}</h3>
                            <div class="text-xs text-gray-400">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                        </div>

                        <!-- Direct WhatsApp Contact Button -->
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}?text=Halo%20Kak%20{{ urlencode($order->customer_name) }},%20kami%20dari%20Vantara%20Production%20menindaklanjuti%20pesanan%20{{ $order->order_code }}."
                           target="_blank"
                           class="px-4 py-2 bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold text-xs rounded-xl shadow flex items-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Hubungi via WhatsApp
                        </a>
                    </div>

                    <!-- Customer Detail Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-gray-400 block font-medium mb-1">Nomor WhatsApp:</span>
                            <span class="font-bold text-[#c59d5f] text-sm">{{ $order->customer_phone }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block font-medium mb-1">Alamat Email:</span>
                            <span class="text-gray-200">{{ $order->customer_email ?? '— Tidak diisi' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block font-medium mb-1">Tanggal Pelaksanaan Acara:</span>
                            <span class="font-bold text-emerald-400 text-sm">
                                {{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 block font-medium mb-1">Lokasi Tempat Acara:</span>
                            <span class="text-gray-200 leading-relaxed block">{{ $order->event_address }}</span>
                        </div>
                    </div>

                    @if ($order->notes)
                        <div class="pt-3 border-t border-[#1e2538] text-xs">
                            <span class="text-gray-400 font-medium block mb-1">Catatan Khusus Pelanggan:</span>
                            <div class="p-3 rounded-lg bg-[#090c14] border border-[#1e2538] text-gray-300 italic">
                                "{{ $order->notes }}"
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Order Items Card -->
                <div class="p-6 rounded-2xl bg-[#111722] border border-[#242f44] shadow-xl space-y-4">
                    <h4 class="font-serif font-bold text-white text-base border-b border-[#1e2538] pb-3">Daftar Paket yang Dipesan</h4>

                    <div class="space-y-3">
                        @foreach ($order->orderItems as $item)
                            <div class="p-4 rounded-xl bg-[#090c14] border border-[#1e2538] flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-bold text-white text-sm">{{ $item->package_name }}</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">
                                        {{ $item->quantity }}x unit · Rp {{ number_format($item->unit_price, 0, ',', '.') }} / unit
                                    </div>
                                    @if ($item->package)
                                        <div class="text-[10px] text-[#c59d5f] mt-1">Kategori: {{ $item->package->category }}</div>
                                    @endif
                                </div>
                                <div class="font-serif font-bold text-[#dfc48e] text-base">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 border-t border-[#1e2538] flex items-center justify-between text-xs">
                        <span class="text-gray-400 font-semibold uppercase">Total Nilai Pesanan:</span>
                        <span class="font-serif text-2xl font-bold text-[#dfc48e]">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

            </div>

            <!-- Right Column: Order Management & Status Update Form (5 cols) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Status Update Form -->
                <div class="p-6 rounded-2xl bg-[#111722] border border-[#c59d5f]/40 shadow-2xl space-y-6 sticky top-24">
                    <div class="border-b border-[#1e2538] pb-3">
                        <span class="text-[11px] uppercase tracking-widest text-[#c59d5f] font-semibold">Tindakan Admin</span>
                        <h3 class="font-serif text-xl font-bold text-white">Kelola Status Transaksi</h3>
                    </div>

                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <!-- Status Select -->
                        <div>
                            <label for="status" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                Status Pesanan <span class="text-rose-400">*</span>
                            </label>
                            <select id="status"
                                    name="status"
                                    required
                                    class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white font-semibold focus:outline-none transition-colors">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>● Pending (Menunggu WA)</option>
                                <option value="dp_received" {{ $order->status === 'dp_received' ? 'selected' : '' }}>● DP Received (Uang Muka Diterima)</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>✓ Confirmed (Jadwal Terkunci)</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✓ Completed (Acara Selesai)</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>✕ Cancelled (Pesanan Dibatalkan)</option>
                            </select>
                        </div>

                        <!-- Catat DP (Down Payment) -->
                        <div>
                            <label for="down_payment" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                Jumlah DP Diterima (Rp)
                            </label>
                            <input type="number"
                                   id="down_payment"
                                   name="down_payment"
                                   value="{{ old('down_payment', $order->down_payment) }}"
                                   placeholder="0"
                                   min="0"
                                   class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white focus:outline-none transition-colors">
                            <span class="text-[10px] text-gray-400 mt-1 block">Sisa pembayaran: <strong>Rp {{ number_format(max(0, $order->total_price - $order->down_payment), 0, ',', '.') }}</strong></span>
                        </div>

                        <!-- Adjust Total Price -->
                        <div>
                            <label for="total_price" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                Penyesuaian Total Biaya (Rp)
                            </label>
                            <input type="number"
                                   id="total_price"
                                   name="total_price"
                                   value="{{ old('total_price', $order->total_price) }}"
                                   min="0"
                                   class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white focus:outline-none transition-colors">
                            <span class="text-[10px] text-gray-400 mt-1 block">Ubah nilai jika ada kesepakatan diskon / penambahan add-on.</span>
                        </div>

                        <!-- Admin Notes -->
                        <div>
                            <label for="admin_notes" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                                Catatan Operasional Admin
                            </label>
                            <textarea id="admin_notes"
                                      name="admin_notes"
                                      rows="3"
                                      placeholder="Catat nomor rekening transfer DP, kru bertugas, atau catatan penting..."
                                      class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white focus:outline-none transition-colors">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                        </div>

                        <!-- Submit Update -->
                        <button type="submit"
                                class="w-full py-3.5 px-4 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black font-bold text-xs uppercase tracking-[0.15em] rounded-lg shadow-xl transition-all hover:scale-[1.01]">
                            Simpan Perubahan Status
                        </button>
                    </form>

                    <!-- Delete Order Option -->
                    <div class="pt-4 border-t border-[#1e2538] flex items-center justify-between text-xs">
                        <span class="text-gray-500">Hapus pesanan ini?</span>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}"
                              method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan {{ $order->order_code }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-300 hover:underline">
                                Hapus Pesanan
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

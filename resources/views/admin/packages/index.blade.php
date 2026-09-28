@extends('layouts.admin')

@section('title', 'Kelola Paket Layanan — Admin Vantara Production')
@section('header_title', 'Kelola Katalog Paket Layanan')

@section('content')

    <div class="space-y-6">

        <!-- Toolbar: Search, Filter Category, & Add New Package -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-5 rounded-2xl bg-[#111722] border border-[#242f44]">

            <!-- Left: Filter & Search Form -->
            <form action="{{ route('admin.packages.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau deskripsi..."
                       class="px-4 py-2.5 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white placeholder-gray-500 focus:outline-none w-full sm:w-64">

                <select name="category"
                        onchange="this.form.submit()"
                        class="px-3 py-2.5 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-gray-300 focus:outline-none w-full sm:w-auto">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2.5 bg-[#1e2538] hover:bg-[#28324a] text-white text-xs font-semibold rounded-lg transition-colors shrink-0 w-full sm:w-auto">
                    Cari
                </button>
            </form>

            <!-- Right: Add New Package Button -->
            <a href="{{ route('admin.packages.create') }}"
               class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black font-bold text-xs uppercase tracking-wider rounded-lg shadow-lg flex items-center justify-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Tambah Paket Baru
            </a>

        </div>

        <!-- Packages Data Table -->
        <div class="bg-[#111722] rounded-2xl border border-[#242f44] overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-300">
                    <thead class="bg-[#141b28] text-gray-400 uppercase tracking-wider text-[10px] border-b border-[#1e2538]">
                        <tr>
                            <th class="py-4 px-6">Paket Layanan</th>
                            <th class="py-4 px-6">Kategori</th>
                            <th class="py-4 px-6">Harga Sewa</th>
                            <th class="py-4 px-6">Item / Layanan</th>
                            <th class="py-4 px-6 text-center">Status Aktif</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1e2538]">
                        @forelse ($packages as $pkg)
                            <tr class="hover:bg-[#141b28]/50 transition-colors">

                                <!-- Package Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-black overflow-hidden border border-[#242f44] shrink-0">
                                            <img src="{{ $pkg->image_path ?? 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=200&auto=format&fit=crop' }}"
                                                 alt="{{ $pkg->name }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="font-bold text-white text-sm flex items-center gap-2">
                                                <span>{{ $pkg->name }}</span>
                                                @if ($pkg->is_featured)
                                                    <span class="px-1.5 py-0.5 rounded bg-[#c59d5f]/20 border border-[#c59d5f]/40 text-[#dfc48e] text-[9px] font-bold uppercase">Unggulan</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-gray-400 truncate max-w-xs mt-0.5">{{ $pkg->description }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full bg-[#141b28] border border-[#242f44] text-[#dfc48e] text-[11px] font-medium">
                                        {{ $pkg->category }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="py-4 px-6 whitespace-nowrap font-serif font-bold text-white text-sm">
                                    Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                </td>

                                <!-- Items Count -->
                                <td class="py-4 px-6">
                                    <span class="text-xs text-gray-300 font-semibold">{{ count($pkg->items ?? []) }} Item</span>
                                    <div class="text-[10px] text-gray-400 truncate max-w-[200px] mt-0.5">
                                        {{ implode(', ', array_slice($pkg->items ?? [], 0, 2)) }}...
                                    </div>
                                </td>

                                <!-- Status Active Toggle -->
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    <form action="{{ route('admin.packages.toggleActive', $pkg->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all {{ $pkg->is_active ? 'bg-emerald-950/80 border border-emerald-500/60 text-emerald-300 hover:bg-emerald-900' : 'bg-rose-950/80 border border-rose-500/60 text-rose-300 hover:bg-rose-900' }}">
                                            {{ $pkg->is_active ? '✓ Aktif' : '✕ Non-Aktif' }}
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.packages.edit', $pkg->id) }}"
                                           class="p-2 rounded-lg bg-[#141b28] hover:bg-[#1e2538] text-gray-300 hover:text-white transition-colors border border-[#242f44]"
                                           title="Edit Paket">
                                            ✏️
                                        </a>

                                        <form action="{{ route('admin.packages.destroy', $pkg->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $pkg->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 rounded-lg bg-[#141b28] hover:bg-rose-950/60 text-gray-400 hover:text-rose-300 transition-colors border border-[#242f44]"
                                                    title="Hapus Paket">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 text-xs">
                                    Tidak ada paket yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if ($packages->hasPages())
                <div class="p-4 border-t border-[#1e2538]">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection

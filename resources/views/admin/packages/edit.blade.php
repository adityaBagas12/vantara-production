@extends('layouts.admin')

@section('title', 'Edit Paket — ' . $package->name)
@section('header_title', 'Edit Paket Layanan')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.packages.index') }}" class="text-xs text-gray-400 hover:text-white transition-colors">
                &larr; Kembali ke Daftar Paket
            </a>
        </div>

        <div class="p-8 rounded-2xl bg-[#111722] border border-[#242f44] shadow-2xl space-y-6"
             x-data="{ items: {{ json_encode(old('items', $package->items ?? [])) }} }">

            <div class="border-b border-[#1e2538] pb-4 flex items-center justify-between">
                <div>
                    <h3 class="font-serif text-xl font-bold text-white">Edit {{ $package->name }}</h3>
                    <p class="text-xs text-gray-400 mt-1">Perbarui rincian, harga, dan ketersediaan paket ini.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-[#141b28] border border-[#242f44] text-[#dfc48e] text-xs font-semibold">
                    ID: #{{ $package->id }}
                </span>
            </div>

            @if ($errors->any())
                <div class="p-4 bg-rose-950/70 border border-rose-500/50 rounded-xl text-rose-200 text-xs space-y-1">
                    <strong class="block font-bold mb-1">Terjadi kesalahan input:</strong>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Nama Paket -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                            Nama Paket Layanan <span class="text-rose-400">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $package->name) }}"
                               required
                               class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white focus:outline-none transition-colors">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                            Kategori Layanan <span class="text-rose-400">*</span>
                        </label>
                        <select id="category"
                                name="category"
                                required
                                class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white focus:outline-none transition-colors">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $package->category) === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="price" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                            Harga Sewa (Rp) <span class="text-rose-400">*</span>
                        </label>
                        <input type="number"
                               id="price"
                               name="price"
                               value="{{ old('price', $package->price) }}"
                               min="0"
                               required
                               class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white focus:outline-none transition-colors">
                    </div>

                    <!-- Upload Foto & Preview Gambar -->
                    <div class="sm:col-span-2 p-5 rounded-xl bg-[#090c14] border border-[#242f44] space-y-4"
                         x-data="{ previewUrl: '{{ old('image_path', $package->image_path) }}' }">
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            Foto / Gambar Paket Layanan
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <!-- Live Image Preview Box -->
                            <div class="md:col-span-1">
                                <div class="w-full h-36 rounded-lg bg-[#141b28] border border-[#242f44] overflow-hidden flex items-center justify-center relative">
                                    <template x-if="previewUrl">
                                        <img :src="previewUrl" alt="Preview Gambar" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!previewUrl">
                                        <div class="text-center p-3 text-gray-500 text-xs">
                                            <svg class="w-8 h-8 mx-auto mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>Belum Ada Foto</span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- File Upload & URL Inputs -->
                            <div class="md:col-span-2 space-y-3">
                                <div>
                                    <label for="image_file" class="block text-[11px] font-medium text-gray-300 mb-1">
                                        📁 Upload Foto Baru dari Komputer <span class="text-[#c59d5f] font-semibold">(Rekomendasi)</span>
                                    </label>
                                    <input type="file"
                                           id="image_file"
                                           name="image_file"
                                           accept="image/*"
                                           @change="const file = $event.target.files[0]; if (file) { previewUrl = URL.createObjectURL(file); }"
                                           class="w-full text-xs text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#c59d5f] file:text-black hover:file:bg-[#d4ab63] cursor-pointer">
                                    <p class="text-[10px] text-gray-500 mt-1">Format: JPG, PNG, WEBP, GIF (Maks. 5 MB)</p>
                                </div>

                                <div class="text-center text-[10px] uppercase font-bold text-gray-500 tracking-wider">--- ATAU ---</div>

                                <div>
                                    <label for="image_path" class="block text-[11px] font-medium text-gray-400 mb-1">
                                        🔗 Atau Tempel Link / URL Gambar Eksternal
                                    </label>
                                    <input type="text"
                                           id="image_path"
                                           name="image_path"
                                           value="{{ old('image_path', $package->image_path) }}"
                                           @input="previewUrl = $event.target.value"
                                           placeholder="https://images.unsplash.com/..."
                                           class="w-full px-3.5 py-2 bg-[#141b28] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white placeholder-gray-500 focus:outline-none transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                            Deskripsi Ringkas Paket <span class="text-rose-400">*</span>
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  required
                                  class="w-full px-4 py-3 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-sm text-white focus:outline-none transition-colors">{{ old('description', $package->description) }}</textarea>
                    </div>

                </div>

                <!-- Dynamic Items Checklist -->
                <div class="space-y-3 pt-4 border-t border-[#1e2538]">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            Rincian Item / Alat / Personel yang Didapatkan <span class="text-rose-400">*</span>
                        </label>
                        <button type="button"
                                @click="items.push('')"
                                class="text-xs text-[#c59d5f] hover:underline font-semibold flex items-center gap-1">
                            + Tambah Baris Item
                        </button>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono text-gray-500 w-6 text-right" x-text="index + 1 + '.'"></span>
                                <input type="text"
                                       :name="`items[${index}]`"
                                       x-model="items[index]"
                                       required
                                       class="flex-1 px-4 py-2.5 bg-[#090c14] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white focus:outline-none transition-colors">
                                <button type="button"
                                        @click="items.splice(index, 1)"
                                        x-show="items.length > 1"
                                        class="p-2 text-rose-400 hover:text-rose-300 text-xs font-bold">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Status Checkboxes -->
                <div class="pt-4 border-t border-[#1e2538] flex flex-wrap gap-6 text-xs">
                    <label class="flex items-center gap-2 text-gray-300 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }} class="rounded bg-[#090c14] border-[#242f44] text-[#c59d5f] focus:ring-0">
                        <span>Tampilkan sebagai <strong>Paket Unggulan</strong> di Beranda</span>
                    </label>

                    <label class="flex items-center gap-2 text-gray-300 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="rounded bg-[#090c14] border-[#242f44] text-[#c59d5f] focus:ring-0">
                        <span>Status Paket <strong>Aktif</strong> di Katalog Publik</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-[#1e2538] flex items-center justify-end gap-3">
                    <a href="{{ route('admin.packages.index') }}" class="px-5 py-3 bg-[#1e2538] hover:bg-[#28324a] text-gray-300 font-semibold text-xs rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-[#dfc48e] via-[#c59d5f] to-[#b88c4b] hover:brightness-110 text-black font-bold text-xs uppercase tracking-wider rounded-lg shadow-xl transition-all">
                        Perbarui Paket
                    </button>
                </div>

            </form>

        </div>

    </div>

@endsection

@extends('layouts.app')

@section('title', 'Katalog Paket Layanan — Vantara Production')
@section('meta_description', 'Pilihan lengkap paket rental sound system, lighting & special effect, videobooth 360, band wedding, dan hiburan orgen tunggal Vantara Production.')

@section('content')

    <!-- Catalog Page Header -->
    <section class="relative py-20 bg-[#0c1017] border-b border-[#1b2438] overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1600&auto=format&fit=crop" 
                 alt="Vantara Production Stage" class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs uppercase tracking-[0.25em] font-semibold text-[#c59d5f] block mb-2">Pilihan Paket Terlengkap</span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold text-white mb-4">Katalog Paket Rental & Hiburan</h1>
            <div class="flex items-center justify-center gap-3 my-4">
                <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
                <span class="text-[#c59d5f] text-xs">✦</span>
                <span class="h-[1px] w-12 bg-[#c59d5f]"></span>
            </div>
            <p class="max-w-2xl mx-auto text-xs sm:text-sm text-gray-300 leading-relaxed">
                Pilih paket sewa sound system, lighting, videobooth 360, dan band wedding yang disesuaikan dengan skala dan kebutuhan acara Anda.
            </p>
        </div>
    </section>

    <!-- Filters & Search Toolbar (PRD Sub-fitur: Filtering & Sorting) -->
    <section class="sticky top-[73px] z-30 bg-[#090c14]/95 backdrop-blur-md border-b border-[#1e2538] py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3.5">
            
            <!-- Category Filter Pills Bar (Row 1: Full width, elevated spacing) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-1 scrollbar-none border-b border-[#1e2538]/60">
                <a href="{{ route('packages.catalog', array_filter(['search' => $currentSearch, 'sort' => $currentSort])) }}" 
                   class="px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-wider transition-all whitespace-nowrap shrink-0 {{ empty($selectedCategory) ? 'bg-[#c59d5f] text-black shadow-lg shadow-[#c59d5f]/20 font-bold' : 'bg-[#141b28] text-gray-300 hover:text-white border border-[#242f44] hover:border-[#c59d5f]/50' }}">
                    Semua Kategori
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('packages.catalog', array_filter(['category' => $cat, 'search' => $currentSearch, 'sort' => $currentSort])) }}" 
                       class="px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-wider transition-all whitespace-nowrap shrink-0 {{ $selectedCategory === $cat ? 'bg-[#c59d5f] text-black shadow-lg shadow-[#c59d5f]/20 font-bold' : 'bg-[#141b28] text-gray-300 hover:text-white border border-[#242f44] hover:border-[#c59d5f]/50' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <!-- Search Bar + Live Dropdown + Sort Controls (Row 2) -->
            <form action="{{ route('packages.catalog') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-3">
                @if ($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif

                <!-- Live Search Box with Autocomplete Dropdown -->
                <div x-data="catalogSearch({{ json_encode($allActivePackages) }}, '{{ addslashes($currentSearch) }}')"
                     @click.outside="open = false"
                     class="relative w-full sm:flex-1 max-w-xl">
                    
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               x-model="search"
                               @focus="open = true"
                               @input="open = true"
                               @keydown.escape="open = false"
                               placeholder="Cari nama paket, alat sound system, lighting..." 
                               autocomplete="off"
                               class="w-full pl-9 pr-8 py-2.5 bg-[#121824] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-white placeholder-gray-500 focus:outline-none transition-colors shadow-inner">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <button x-show="search" @click="search = ''; open = false" type="button" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Autocomplete Dropdown List -->
                    <div x-show="open && search.trim().length > 0"
                         x-transition
                         class="absolute left-0 right-0 mt-2 bg-[#121824] border border-[#c59d5f]/40 rounded-xl shadow-2xl overflow-hidden z-50 divide-y divide-[#1e2538] max-h-80 overflow-y-auto"
                         style="display: none;">
                        
                        <div class="px-3.5 py-2 bg-[#172030] text-[10px] uppercase font-bold tracking-wider text-[#c59d5f] flex justify-between items-center">
                            <span>Rekomendasi Paket</span>
                            <span x-text="`${filteredPackages.length} Hasil`"></span>
                        </div>

                        <template x-for="pkg in filteredPackages" :key="pkg.id">
                            <a :href="`/paket/${pkg.slug}`"
                               class="flex items-center gap-3 p-3 hover:bg-[#1a2334] transition-colors group">
                                <img :src="pkg.image_path || 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=400&auto=format&fit=crop'" 
                                     :alt="pkg.name" 
                                     class="w-10 h-10 object-cover rounded-lg border border-[#242f44] shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-white group-hover:text-[#c59d5f] truncate transition-colors" x-text="pkg.name"></div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 flex items-center gap-2">
                                        <span class="text-[#c59d5f]" x-text="pkg.category"></span>
                                        <span>•</span>
                                        <span class="text-gray-200 font-semibold" x-text="`Rp ${Number(pkg.price).toLocaleString('id-ID')}`"></span>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 group-hover:text-[#c59d5f] group-hover:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </template>

                        <div x-show="filteredPackages.length === 0" class="p-4 text-center text-xs text-gray-400">
                            Tidak ditemukan paket untuk "<span class="text-white font-semibold" x-text="search"></span>"
                        </div>

                        <button type="submit" class="w-full text-center py-2.5 bg-[#172030] hover:bg-[#1f2b40] text-xs text-[#c59d5f] font-semibold transition-colors border-t border-[#1e2538]">
                            Tampilkan Hasil Lengkap di Katalog &rarr;
                        </button>
                    </div>

                </div>

                <!-- Sort & Submit Button -->
                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-end">
                    <select name="sort" 
                            onchange="this.form.submit()" 
                            class="flex-1 sm:flex-initial px-3 py-2.5 bg-[#121824] border border-[#242f44] focus:border-[#c59d5f] rounded-lg text-xs text-gray-300 focus:outline-none transition-colors">
                        <option value="" {{ empty($currentSort) ? 'selected' : '' }}>Urutkan: Unggulan & Harga</option>
                        <option value="price_asc" {{ $currentSort === 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_desc" {{ $currentSort === 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        <option value="latest" {{ $currentSort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    </select>

                    <button type="submit" class="px-5 py-2.5 bg-[#c59d5f] hover:bg-[#d4ab63] text-black font-semibold text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 shadow-md">
                        Cari
                    </button>
                </div>

            </form>
        </div>
    </section>

    <!-- Package Grid Section -->
    <section class="py-16 bg-[#0a0d14] min-h-[500px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Result count & reset if filtered -->
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-[#1b2438] text-xs text-gray-400">
                <div>
                    Menampilkan <span class="font-bold text-white">{{ $packages->total() }}</span> paket layanan
                    @if ($selectedCategory)
                        dalam kategori "<span class="text-[#c59d5f] font-semibold">{{ $selectedCategory }}</span>"
                    @endif
                    @if ($currentSearch)
                        dengan kata kunci "<span class="text-[#c59d5f] font-semibold">{{ $currentSearch }}</span>"
                    @endif
                </div>

                @if ($selectedCategory || $currentSearch || $currentSort)
                    <a href="{{ route('packages.catalog') }}" class="text-[#c59d5f] hover:underline flex items-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reset Filter
                    </a>
                @endif
            </div>

            <!-- Empty State -->
            @if ($packages->isEmpty())
                <div class="text-center py-20 bg-[#111722] rounded-xl border border-[#242f44] p-12">
                    <div class="w-16 h-16 mx-auto rounded-full bg-[#c59d5f]/10 border border-[#c59d5f]/30 flex items-center justify-center text-[#c59d5f] mb-4 text-2xl">
                        🔍
                    </div>
                    <h3 class="font-serif text-xl font-bold text-white mb-2">Paket Tidak Ditemukan</h3>
                    <p class="text-xs text-gray-400 max-w-md mx-auto mb-6">
                        Maaf, kami tidak menemukan paket yang sesuai dengan kriteria pencarian Anda. Silakan coba kata kunci lain atau hubungi tim kami via WhatsApp.
                    </p>
                    <a href="{{ route('packages.catalog') }}" class="px-6 py-2.5 bg-[#c59d5f] text-black font-semibold text-xs uppercase tracking-wider rounded">
                        Lihat Seluruh Paket
                    </a>
                </div>
            @else
                <!-- 3-Column Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($packages as $package)
                        <div class="group bg-[#111722] rounded-xl overflow-hidden border border-[#242f44] hover:border-[#c59d5f]/60 transition-all duration-300 shadow-xl flex flex-col hover:-translate-y-1">
                            
                            <!-- Card Image Banner with Hover Zoom -->
                            <div class="relative h-60 overflow-hidden bg-black">
                                <img src="{{ $package->image_path ?? 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=800&auto=format&fit=crop' }}" 
                                     alt="{{ $package->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 brightness-90">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#111722] via-transparent to-transparent"></div>
                                
                                <!-- Badges -->
                                <div class="absolute top-4 left-4 flex gap-2">
                                    <span class="px-3 py-1 bg-black/70 backdrop-blur-md border border-[#c59d5f]/40 text-[#dfc48e] text-[10px] font-semibold uppercase tracking-wider rounded">
                                        {{ $package->category }}
                                    </span>
                                </div>

                                @if ($package->is_featured)
                                    <div class="absolute top-4 right-4">
                                        <span class="px-2.5 py-0.5 bg-gradient-to-r from-[#dfc48e] to-[#c59d5f] text-black text-[10px] font-bold uppercase tracking-wider rounded shadow">
                                            Unggulan
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-serif text-xl font-bold text-white group-hover:text-[#c59d5f] transition-colors mb-2">
                                        {{ $package->name }}
                                    </h3>

                                    <p class="text-xs text-gray-400 line-clamp-2 mb-4 leading-relaxed">
                                        {{ $package->description }}
                                    </p>

                                    <!-- Inclusions Checklist -->
                                    <div class="space-y-2 mb-6 border-t border-[#1e2538] pt-4">
                                        <div class="text-[11px] uppercase tracking-wider text-[#c59d5f] font-semibold">Termasuk di dalam paket:</div>
                                        @foreach (array_slice($package->items ?? [], 0, 4) as $item)
                                            <div class="flex items-center gap-2 text-xs text-gray-300">
                                                <svg class="w-3.5 h-3.5 text-[#c59d5f] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                <span class="truncate">{{ $item }}</span>
                                            </div>
                                        @endforeach
                                        @if (count($package->items ?? []) > 4)
                                            <div class="text-[11px] text-[#c59d5f] italic">+ {{ count($package->items) - 4 }} item lainnya</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Pricing & Action Buttons -->
                                <div class="pt-4 border-t border-[#1e2538]">
                                    <div class="flex items-baseline justify-between mb-4">
                                        <span class="text-xs text-gray-400">Harga Mulai Dari:</span>
                                        <div class="font-serif text-xl font-bold text-[#dfc48e]">
                                            Rp {{ number_format($package->price, 0, ',', '.') }}
                                            <span class="text-[10px] font-sans text-gray-400 font-normal">/ acara</span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('packages.show', $package->slug) }}" 
                                           class="w-full text-center py-2.5 px-3 bg-[#171f2e] hover:bg-[#202b3f] text-gray-200 border border-[#242f44] hover:border-[#c59d5f] rounded text-xs font-medium uppercase tracking-wider transition-all">
                                            Detail Paket
                                        </a>
                                        <a href="{{ route('packages.show', $package->slug) }}#kalender" 
                                           class="w-full text-center py-2.5 px-3 bg-[#c59d5f] hover:bg-[#d4ab63] text-black rounded text-xs font-semibold uppercase tracking-wider transition-all">
                                            Cek Tanggal
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Custom Pagination Links -->
                <div class="mt-12">
                    {{ $packages->links() }}
                </div>
            @endif

        </div>
    </section>

    <script>
        function catalogSearch(packagesList, initialSearch = '') {
            return {
                search: initialSearch,
                open: false,
                packages: packagesList || [],

                get filteredPackages() {
                    if (!this.search.trim()) return [];
                    const q = this.search.toLowerCase().trim();
                    return this.packages.filter(pkg => {
                        const itemsStr = Array.isArray(pkg.items) ? pkg.items.join(' ') : String(pkg.items || '');
                        const haystack = (pkg.name + ' ' + pkg.category + ' ' + itemsStr).toLowerCase();
                        return haystack.includes(q);
                    }).slice(0, 6);
                }
            };
        }
    </script>

@endsection

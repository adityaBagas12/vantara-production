@extends('layouts.app')

@section('title', 'Katalog Produk — Vantara Production')
@section('meta_description', 'Jelajahi pilihan lengkap paket rental sound system, lighting, videobooth, dan perlengkapan panggung.')

@section('content')
<div style="min-height: 1600px">
  <section class="wrap catalog-header" style="padding-top: 56px; padding-bottom: 32px">
    <span class="eye">Katalog</span>
    <div style="display: flex; align-items: flex-end; gap: 24px; flex-wrap: wrap; margin-top: 8px">
      <h1 class="d" style="font-size: 52px; line-height: 1.05; font-weight: 800; flex-grow: 1">Pilih perlengkapan acara Anda</h1>
      <span style="color: var(--mu)">Menampilkan {{ $packages->total() }} produk</span>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 28px">
      <a class="pill {{ empty($selectedCategory) ? 'on' : '' }}" href="{{ route('packages.catalog', array_filter(['sort' => $currentSort])) }}">Semua</a>
      @foreach($categories as $cat)
        <a class="pill {{ $selectedCategory === $cat ? 'on' : '' }}" href="{{ route('packages.catalog', array_filter(['category' => $cat, 'sort' => $currentSort])) }}">{{ $cat }}</a>
      @endforeach
    </div>
  </section>

  <section class="wrap catalog-layout" style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-start; padding-bottom: 56px">
    <!-- Filter Sidebar -->
    <form action="{{ route('packages.catalog') }}" method="GET" class="card catalog-filter" style="flex: 0 0 280px; padding: 24px; display: flex; flex-direction: column; gap: 20px">
      <strong class="d" style="font-size: 20px">Filter</strong>

      <!-- Urutkan Harga -->
      <div class="fld">
        <label for="sort">Urutkan</label>
        <select id="sort" name="sort" style="background: var(--s2); border: 1px solid #3A3F4C; border-radius: 12px; padding: 12px 14px; color: var(--tx); min-height: 46px">
          <option value="" {{ empty($currentSort) ? 'selected' : '' }}>Rekomendasi</option>
          <option value="price_asc" {{ $currentSort === 'price_asc' ? 'selected' : '' }}>Harga: Termurah</option>
          <option value="price_desc" {{ $currentSort === 'price_desc' ? 'selected' : '' }}>Harga: Termahal</option>
          <option value="latest" {{ $currentSort === 'latest' ? 'selected' : '' }}>Terbaru</option>
        </select>
      </div>

      <!-- Kategori Filter -->
      <div style="display: flex; flex-direction: column; gap: 6px">
        <span style="font-size: 13px; color: var(--mu); margin-bottom: 4px">Kategori</span>
        <label class="chk">
          <input type="radio" name="category" value="" {{ empty($selectedCategory) ? 'checked' : '' }} onchange="this.form.submit()"> Semua Kategori
        </label>
        @foreach($categories as $cat)
          <label class="chk">
            <input type="radio" name="category" value="{{ $cat }}" {{ $selectedCategory === $cat ? 'checked' : '' }} onchange="this.form.submit()"> {{ $cat }}
          </label>
        @endforeach
      </div>

      <button class="btn p" type="submit">Terapkan Filter</button>
      @if($selectedCategory || $currentSort)
        <a href="{{ route('packages.catalog') }}" style="color: var(--mu); font-size: 13px; text-align: center; text-decoration: underline">Reset Filter</a>
      @endif
    </form>

    <!-- Product Grid -->
    <div class="product-grid" style="flex: 999 1 640px; min-width: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 22px">
      @php
        $kStyles = ['', 'k2', 'k3', 'k4'];
      @endphp
      @forelse($packages as $index => $p)
        @php
          $kClass = $kStyles[$index % count($kStyles)];
          $catLower = strtolower($p->category);
        @endphp
        <div class="card product-card" style="overflow: hidden; display: flex; flex-direction: column; transition: border-color .2s" onmouseover="this.style.borderColor='var(--ac)'" onmouseout="this.style.borderColor='var(--line)'">
          <div class="pic product-visual {{ $kClass }}" style="height: 200px">
            <span class="badge"><i class="dot"></i>Tersedia @if(($p->stock ?? 1) > 1) &bull; {{ $p->stock }} Unit @endif</span>
            @if($p->image_path)
              <img src="{{ $p->image_path }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover">
            @else
              @if(str_contains($catLower, 'sound'))
                <svg width="60" height="60" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="12" y="4" width="24" height="40" rx="4"/><circle cx="24" cy="30" r="7"/><circle cx="24" cy="30" r="3"/><circle cx="24" cy="14" r="3"/></svg>
              @elseif(str_contains($catLower, 'light'))
                <svg width="60" height="60" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M16 6h16l4 12H12z"/><path d="M12 18 6 42M36 18l6 24M24 18v24"/></svg>
              @elseif(str_contains($catLower, 'mic') || str_contains($catLower, 'orgen') || str_contains($catLower, 'band'))
                <svg width="60" height="60" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="17" y="4" width="14" height="24" rx="7"/><path d="M9 22a15 15 0 0 0 30 0M24 37v7M16 44h16"/></svg>
              @else
                <svg width="60" height="60" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="4" y="10" width="40" height="28" rx="4"/><path d="M14 18v14M24 18v14M34 18v14"/><circle cx="14" cy="24" r="2.5"/><circle cx="24" cy="30" r="2.5"/><circle cx="34" cy="22" r="2.5"/></svg>
              @endif
            @endif
          </div>
          <div class="product-card-body" style="padding: 20px 22px 22px; display: flex; flex-direction: column; gap: 4px; flex-grow: 1">
            <span style="color: var(--mu); font-size: 14px">{{ $p->category }}</span>
            <strong class="d" style="font-size: 20px; line-height: 1.25">{{ $p->name }}</strong>
            <span class="price" style="margin: 8px 0 14px">Rp {{ number_format($p->price, 0, ',', '.') }} <span style="font-size: 14px; color: var(--mu); font-weight: 500">/ hari</span></span>
            <div style="margin-top: auto">
              <a class="btn sm" href="{{ route('packages.show', $p->slug) }}" style="width: 100%; justify-content: center">Detail Produk</a>
            </div>
          </div>
        </div>
      @empty
        <div class="card" style="grid-column: 1 / -1; padding: 48px; text-align: center">
          <p style="color: var(--mu); font-size: 18px">Tidak ada produk yang sesuai dengan filter.</p>
        </div>
      @endforelse
    </div>
  </section>

  @if($packages->hasPages())
    <section class="wrap" style="padding-bottom: 96px">
      <div class="pg">
        @if(!$packages->onFirstPage())
          <a href="{{ $packages->previousPageUrl() }}" aria-label="Sebelumnya">‹</a>
        @endif
        @foreach($packages->getUrlRange(1, $packages->lastPage()) as $page => $url)
          <a class="{{ $page == $packages->currentPage() ? 'on' : '' }}" href="{{ $url }}">{{ $page }}</a>
        @endforeach
        @if($packages->hasMorePages())
          <a href="{{ $packages->nextPageUrl() }}" aria-label="Berikutnya">›</a>
        @endif
      </div>
    </section>
  @endif
</div>
@endsection

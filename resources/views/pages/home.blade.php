@extends('layouts.app')

@section('title', 'Beranda Vantara Production — Rental Sound System Bengkulu')
@section('meta_description', 'Rental sound system, lighting, dan perlengkapan hiburan di Bengkulu. Suara jernih, panggung yang hidup.')

@section('content')
<div style="min-height: 2000px">
  @if(session('success'))
    <div class="wrap" style="padding-top: 24px">
      <div role="status" style="border: 1px solid #2f9e66; background: rgba(47, 158, 102, .12); color: #8de0b1; border-radius: 12px; padding: 14px 18px">
        {{ session('success') }}
      </div>
    </div>
  @endif

  <!-- Hero Section -->
  <section class="wrap home-hero" style="padding-top: 72px; padding-bottom: 88px; display: flex; flex-wrap: wrap; gap: 56px; align-items: center">
    <div style="flex: 1 1 460px; display: flex; flex-direction: column; gap: 26px">
      <span class="eye">Rental sound system &amp; entertainment · Bengkulu</span>
      <h1 class="d" style="font-size: 68px; line-height: 1.02; font-weight: 800">Suara jernih,<br>panggung yang hidup.</h1>
      <p style="font-size: 19px; color: var(--mu); max-width: 480px">Sewa sound system, lighting, dan perlengkapan hiburan untuk acara Anda. Cek tanggal, kirim booking, lalu selesaikan lewat WhatsApp.</p>
      <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center">
        <a class="btn p lg" href="{{ route('packages.catalog') }}">Lihat Katalog</a>
        <a class="btn wa lg" href="https://wa.me/6282282422317?text=Halo%20Admin%20Vantara%20Production%2C%20saya%20ingin%20konsultasi%20mengenai%20sewa%20sound%20system%20dan%20perlengkapan%20event." target="_blank" rel="noopener" title="Konsultasi cepat langsung via WhatsApp Admin">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.62C9.33 7.62 9 7.7 8.71 8.01C8.42 8.32 7.6 9.09 7.6 10.65C7.6 12.21 8.74 13.72 8.9 13.93C9.06 14.14 11.14 17.34 14.32 18.72C15.08 19.04 15.67 19.24 16.14 19.38C16.9 19.63 17.59 19.59 18.14 19.51C18.75 19.42 20.03 18.73 20.29 17.98C20.56 17.23 20.56 16.59 20.48 16.45C20.4 16.32 20.19 16.24 19.89 16.09C19.58 15.93 18.08 15.2 17.8 15.1C17.52 15 17.32 14.95 17.11 15.25C16.91 15.56 16.34 16.24 16.17 16.44C16 16.65 15.83 16.67 15.52 16.52C15.22 16.37 14.24 16.05 13.08 15.01C12.18 14.2 11.57 13.2 11.41 12.92C11.25 12.65 11.39 12.5 11.55 12.35C11.69 12.21 11.86 11.99 12.01 11.81C12.16 11.64 12.22 11.51 12.32 11.31C12.42 11.1 12.37 10.92 12.29 10.77C12.21 10.62 11.6 9.12 11.35 8.5C11.1 7.9 10.85 7.98 10.66 7.97C10.48 7.97 10.27 7.96 10.07 7.96C9.86 7.96 9.53 7.62 9.53 7.62Z"/>
          </svg>
          <span>Konsultasi Cepat</span>
        </a>
      </div>
      <div style="display: flex; gap: 10px; flex-wrap: wrap">
        <span class="pill"><i class="dot"></i>Cek ketersediaan online</span>
        <span class="pill">Invoice otomatis</span>
        <span class="pill">Negosiasi via WhatsApp</span>
      </div>
    </div>
    <div style="flex: 1 1 460px">
      <div class="pic home-hero-image" style="height: 520px; border-radius: 28px; border: 1px solid var(--line)">
        @if($siteSetting?->hero_image_path)
          <img src="{{ $siteSetting->hero_image_path }}" alt="Vantara Production" style="width: 100%; height: 100%; object-fit: cover">
        @else
          <svg width="150" height="150" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><rect x="12" y="4" width="24" height="40" rx="4"/><circle cx="24" cy="30" r="7"/><circle cx="24" cy="30" r="3"/><circle cx="24" cy="14" r="3"/></svg>
        @endif
      </div>
    </div>
  </section>

  <!-- Layanan Section -->
  <section class="wrap home-services" style="padding-bottom: 96px">
    <div style="display: flex; align-items: flex-end; gap: 24px; margin-bottom: 32px; flex-wrap: wrap">
      <div style="flex-grow: 1"><span class="eye">Layanan</span><h2 class="d" style="font-size: 40px; margin-top: 8px">Satu tim untuk seluruh kebutuhan acara</h2></div>
    </div>
    @php
      $services = [
        ['no' => '01', 'name' => 'Sound System', 'cat' => 'Sound System'],
        ['no' => '02', 'name' => 'Orgen & Entertainment', 'cat' => 'Orgen & Entertainment'],
        ['no' => '03', 'name' => 'Band', 'cat' => 'Band'],
        ['no' => '04', 'name' => 'Professional Lighting', 'cat' => 'Professional Lighting'],
        ['no' => '05', 'name' => 'Special Effect', 'cat' => 'Special Effect'],
        ['no' => '06', 'name' => 'Videobooth 360°', 'cat' => 'Videobooth & Photobooth'],
        ['no' => '07', 'name' => 'Photobooth', 'cat' => 'Videobooth & Photobooth'],
        ['no' => '08', 'name' => 'Event Production', 'cat' => 'Event Production'],
        ['no' => '09', 'name' => 'Videotron', 'cat' => 'Videotron'],
        ['no' => '10', 'name' => 'Blower Kipas', 'cat' => 'Blower Kipas'],
      ];
    @endphp
    <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); border-top: 1px solid var(--line); border-left: 1px solid var(--line)">
      @foreach($services as $s)
        <div class="service-item" style="padding: 26px 24px 30px; border-right: 1px solid var(--line); border-bottom: 1px solid var(--line); display: flex; flex-direction: column; gap: 22px; min-height: 140px">
          <span style="color: var(--ac); font-family: 'Bricolage Grotesque', sans-serif; font-weight: 700">{{ $s['no'] }}</span>
          <strong class="d" style="font-size: 20px; line-height: 1.2">{{ $s['name'] }}</strong>
        </div>
      @endforeach
    </div>
  </section>

  <!-- Produk Unggulan Section -->
  <section class="wrap" style="padding-bottom: 96px">
    <div style="display: flex; align-items: flex-end; gap: 24px; margin-bottom: 32px; flex-wrap: wrap">
      <div style="flex-grow: 1"><span class="eye">Produk unggulan</span><h2 class="d" style="font-size: 40px; margin-top: 8px">Siap naik panggung</h2></div>
      <a class="btn" href="{{ route('packages.catalog') }}">Lihat semua produk →</a>
    </div>
    @php
      $displayFeatured = $featuredPackages->isNotEmpty() ? $featuredPackages : $allPackages->take(4);
      $kStyles = ['', 'k2', 'k3', 'k4'];
    @endphp
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 22px">
      @forelse($displayFeatured as $index => $p)
        @php
          $kClass = $kStyles[$index % count($kStyles)];
          $catLower = strtolower($p->category);
        @endphp
        <a class="card" href="{{ route('packages.show', $p->slug) }}" style="overflow: hidden; display: flex; flex-direction: column; transition: transform .2s, border-color .2s" onmouseover="this.style.borderColor='var(--ac)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--line)'; this.style.transform='none'">
          @if($p->image_path)
            <div class="pic" style="height: 210px">
              <img src="{{ $p->image_path }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover">
            </div>
          @else
            <div class="pic {{ $kClass }}" style="height: 210px">
              @if(str_contains($catLower, 'sound'))
                <svg width="64" height="64" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="12" y="4" width="24" height="40" rx="4"/><circle cx="24" cy="30" r="7"/><circle cx="24" cy="30" r="3"/><circle cx="24" cy="14" r="3"/></svg>
              @elseif(str_contains($catLower, 'light'))
                <svg width="64" height="64" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M16 6h16l4 12H12z"/><path d="M12 18 6 42M36 18l6 24M24 18v24"/></svg>
              @elseif(str_contains($catLower, 'mic') || str_contains($catLower, 'orgen') || str_contains($catLower, 'band'))
                <svg width="64" height="64" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="17" y="4" width="14" height="24" rx="7"/><path d="M9 22a15 15 0 0 0 30 0M24 37v7M16 44h16"/></svg>
              @else
                <svg width="64" height="64" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="4" y="10" width="40" height="28" rx="4"/><path d="M14 18v14M24 18v14M34 18v14"/><circle cx="14" cy="24" r="2.5"/><circle cx="24" cy="30" r="2.5"/><circle cx="34" cy="22" r="2.5"/></svg>
              @endif
            </div>
          @endif
          <div style="padding: 20px 22px 24px; display: flex; flex-direction: column; gap: 6px">
            <span style="color: var(--mu); font-size: 14px">{{ $p->category }}</span>
            <strong class="d" style="font-size: 20px; line-height: 1.25">{{ $p->name }}</strong>
            <span class="price" style="margin-top: 8px">Rp {{ number_format($p->price, 0, ',', '.') }} <span style="font-size: 14px; color: var(--mu); font-weight: 500">/ hari</span></span>
          </div>
        </a>
      @empty
        <p style="color: var(--mu)">Belum ada produk unggulan.</p>
      @endforelse
    </div>
  </section>

  <!-- Cara Booking Section -->
  <section style="background: var(--s1); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line)">
    <div class="wrap" style="padding-top: 80px; padding-bottom: 88px">
      <span class="eye">Cara booking</span>
      <h2 class="d" style="font-size: 40px; margin: 8px 0 40px">Empat langkah, tanpa ribet</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 22px">
        <div style="display: flex; flex-direction: column; gap: 12px; padding-top: 22px; border-top: 2px solid var(--ac)">
          <span class="d" style="font-size: 44px; font-weight: 800; color: var(--ac); line-height: 1">01</span>
          <strong class="d" style="font-size: 22px">Pilih produk</strong>
          <span style="color: var(--mu)">Jelajahi katalog sound system dan peralatan hiburan.</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; padding-top: 22px; border-top: 2px solid var(--ac)">
          <span class="d" style="font-size: 44px; font-weight: 800; color: var(--ac); line-height: 1">02</span>
          <strong class="d" style="font-size: 22px">Cek tanggal</strong>
          <span style="color: var(--mu)">Pilih tanggal acara dan lihat ketersediaan produk.</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; padding-top: 22px; border-top: 2px solid var(--ac)">
          <span class="d" style="font-size: 44px; font-weight: 800; color: var(--ac); line-height: 1">03</span>
          <strong class="d" style="font-size: 22px">Isi form booking</strong>
          <span style="color: var(--mu)">Kirim data diri dan kebutuhan acara Anda.</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; padding-top: 22px; border-top: 2px solid var(--ac)">
          <span class="d" style="font-size: 44px; font-weight: 800; color: var(--ac); line-height: 1">04</span>
          <strong class="d" style="font-size: 22px">Lanjut ke WhatsApp</strong>
          <span style="color: var(--mu)">Sepakati kebutuhan, harga, dan pembayaran bersama Admin.</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Tentang Kami Section -->
  <section class="wrap" style="padding-top: 96px; padding-bottom: 96px; display: flex; flex-wrap: wrap; gap: 56px; align-items: center">
    <div class="pic k2 home-about-image" style="flex: 1 1 420px; height: 380px; border-radius: 24px; border: 1px solid var(--line)">
      @if($siteSetting?->about_image_path)
        <img src="{{ $siteSetting->about_image_path }}" alt="Tim Vantara Production" style="width: 100%; height: 100%; object-fit: cover">
      @else
        <svg width="110" height="110" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M16 6h16l4 12H12z"/><path d="M12 18 6 42M36 18v24"/></svg>
      @endif
    </div>
    <div style="flex: 1 1 420px; display: flex; flex-direction: column; gap: 18px">
      <span class="eye">Tentang kami</span>
      <h2 class="d" style="font-size: 40px; line-height: 1.1">Dari orgen tunggal, kini tim produksi acara lengkap</h2>
      <p style="color: var(--mu); font-size: 18px">Vantara Production berawal dari usaha orgen tunggal yang melayani acara masyarakat. Dari pengalaman menangani beragam acara, layanan kami berkembang mencakup sound system, lighting, hiburan, hingga produksi event.</p>
    </div>
  </section>

  <!-- CTA Banner Section -->
  <section class="wrap" style="padding-bottom: 96px">
    <div style="background: var(--ac); color: var(--acx); border-radius: 28px; padding: 48px 56px; display: flex; flex-wrap: wrap; gap: 32px; align-items: center; justify-content: space-between">
      <div style="flex: 1 1 400px">
        <h2 class="d" style="font-size: 40px; line-height: 1.1; font-weight: 800">Punya acara dalam waktu dekat?</h2>
        <p style="margin-top: 10px; font-size: 17px; opacity: 0.9">Cek ketersediaan tanggal atau konsultasikan langsung kebutuhan audio visual Anda bersama tim kami.</p>
      </div>
      <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap">
        <a class="btn" href="{{ route('checkout.index') }}" style="background: var(--acx); border-color: var(--acx); color: #fff; min-height: 48px; padding: 0 24px">Booking Sekarang</a>
        <a class="btn wa" href="https://wa.me/6282282422317?text=Halo%20Admin%20Vantara%20Production%2C%20saya%20ingin%20konsultasi%20mengenai%20sewa%20sound%20system%20dan%20perlengkapan%20event." target="_blank" rel="noopener" style="min-height: 48px; padding: 0 22px">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.62C9.33 7.62 9 7.7 8.71 8.01C8.42 8.32 7.6 9.09 7.6 10.65C7.6 12.21 8.74 13.72 8.9 13.93C9.06 14.14 11.14 17.34 14.32 18.72C15.08 19.04 15.67 19.24 16.14 19.38C16.9 19.63 17.59 19.59 18.14 19.51C18.75 19.42 20.03 18.73 20.29 17.98C20.56 17.23 20.56 16.59 20.48 16.45C20.4 16.32 20.19 16.24 19.89 16.09C19.58 15.93 18.08 15.2 17.8 15.1C17.52 15 17.32 14.95 17.11 15.25C16.91 15.56 16.34 16.24 16.17 16.44C16 16.65 15.83 16.67 15.52 16.52C15.22 16.37 14.24 16.05 13.08 15.01C12.18 14.2 11.57 13.2 11.41 12.92C11.25 12.65 11.39 12.5 11.55 12.35C11.69 12.21 11.86 11.99 12.01 11.81C12.16 11.64 12.22 11.51 12.32 11.31C12.42 11.1 12.37 10.92 12.29 10.77C12.21 10.62 11.6 9.12 11.35 8.5C11.1 7.9 10.85 7.98 10.66 7.97C10.48 7.97 10.27 7.96 10.07 7.96C9.86 7.96 9.53 7.62 9.53 7.62Z"/>
          </svg>
          <span>Konsultasi Cepat</span>
        </a>
      </div>
    </div>
  </section>
</div>
@endsection

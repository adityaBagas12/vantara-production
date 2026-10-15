<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Beranda Vantara Production')</title>
  <meta name="description" content="@yield('meta_description', 'Rental sound system & entertainment di Bengkulu. Suara jernih, panggung yang hidup.')">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column">

  <nav class="nav">
    <a class="brand" href="{{ route('home') }}">
      <span class="mark">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
          <rect x="1" y="6" width="2" height="4" rx="1"/>
          <rect x="5" y="2" width="2" height="12" rx="1"/>
          <rect x="9" y="4" width="2" height="8" rx="1"/>
          <rect x="13" y="7" width="2" height="2" rx="1"/>
        </svg>
      </span>
      VANTARA
    </a>

    <div class="nav-links">
      <a class="nl {{ request()->routeIs('home') ? 'on' : '' }}" href="{{ route('home') }}">Beranda</a>
      <a class="nl {{ request()->routeIs('packages.catalog*') ? 'on' : '' }}" href="{{ route('packages.catalog') }}">Katalog</a>
      <a class="nl {{ request()->routeIs('availability.*') ? 'on' : '' }}" href="{{ route('availability.index') }}">Cek Ketersediaan</a>
      <a class="nl {{ request()->routeIs('cart.*') ? 'on' : '' }}" href="{{ route('cart.index') }}">
        Keranjang
        @if(count(session('cart', [])) > 0)
          <span class="nav-cart-badge">{{ count(session('cart', [])) }}</span>
        @endif
      </a>
    </div>

    <div class="nav-actions">
      <!-- Konsultasi Cepat via WhatsApp Admin -->
      <a class="btn wa sm" href="https://wa.me/6282282422317?text=Halo%20Admin%20Vantara%20Production%2C%20saya%20ingin%20konsultasi%20mengenai%20sewa%20sound%20system%20dan%20perlengkapan%20event." target="_blank" rel="noopener" title="Konsultasi cepat langsung via WhatsApp Admin">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.62C9.33 7.62 9 7.7 8.71 8.01C8.42 8.32 7.6 9.09 7.6 10.65C7.6 12.21 8.74 13.72 8.9 13.93C9.06 14.14 11.14 17.34 14.32 18.72C15.08 19.04 15.67 19.24 16.14 19.38C16.9 19.63 17.59 19.59 18.14 19.51C18.75 19.42 20.03 18.73 20.29 17.98C20.56 17.23 20.56 16.59 20.48 16.45C20.4 16.32 20.19 16.24 19.89 16.09C19.58 15.93 18.08 15.2 17.8 15.1C17.52 15 17.32 14.95 17.11 15.25C16.91 15.56 16.34 16.24 16.17 16.44C16 16.65 15.83 16.67 15.52 16.52C15.22 16.37 14.24 16.05 13.08 15.01C12.18 14.2 11.57 13.2 11.41 12.92C11.25 12.65 11.39 12.5 11.55 12.35C11.69 12.21 11.86 11.99 12.01 11.81C12.16 11.64 12.22 11.51 12.32 11.31C12.42 11.1 12.37 10.92 12.29 10.77C12.21 10.62 11.6 9.12 11.35 8.5C11.1 7.9 10.85 7.98 10.66 7.97C10.48 7.97 10.27 7.96 10.07 7.96C9.86 7.96 9.53 7.62 9.53 7.62Z"/>
        </svg>
        <span>Konsultasi Cepat</span>
      </a>

      @auth
        <a class="btn sm" href="{{ route('admin.dashboard') }}">Panel Admin</a>
      @else
        <a class="btn sm" href="{{ route('admin.login') }}" style="color: var(--mu)">Login Admin</a>
      @endauth

      <a class="btn p sm" href="{{ route('checkout.index') }}">Booking Sekarang</a>
    </div>
  </nav>

  <main style="flex: 1 0 auto">
    @yield('content')
  </main>

  <footer style="border-top: 1px solid var(--line); margin-top: auto">
    <div class="wrap" style="padding-top: 48px; padding-bottom: 48px; display: flex; flex-wrap: wrap; gap: 40px">
      <div style="flex: 2 1 320px; display: flex; flex-direction: column; gap: 12px">
        <span class="brand">
          <span class="mark">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
              <rect x="1" y="6" width="2" height="4" rx="1"/>
              <rect x="5" y="2" width="2" height="12" rx="1"/>
              <rect x="9" y="4" width="2" height="8" rx="1"/>
              <rect x="13" y="7" width="2" height="2" rx="1"/>
            </svg>
          </span>
          VANTARA
        </span>
        <p style="color: var(--mu); max-width: 360px">Jl. Merapi Ujung No.31, Kel. Panorama, Kec. Singaran Pati, Kota Bengkulu</p>
      </div>
      <div style="flex: 1 1 180px; display: flex; flex-direction: column; gap: 8px">
        <strong style="color: var(--tx)">Navigasi</strong>
        <a class="nl" href="{{ route('packages.catalog') }}">Katalog</a>
        <a class="nl" href="{{ route('availability.index') }}">Cek Ketersediaan</a>
        <a class="nl" href="{{ route('cart.index') }}">Keranjang</a>
        <a class="nl" href="{{ route('checkout.index') }}">Booking</a>
        <a class="nl" href="{{ route('admin.login') }}">Login Admin</a>
      </div>
      <div style="flex: 1 1 240px; display: flex; flex-direction: column; gap: 12px">
        <strong style="color: var(--tx)">Konsultasi Cepat</strong>
        <p style="color: var(--mu); font-size: 14px; margin: 0">Butuh rekomendasi teknis atau paket sound system sesuai venue?</p>
        <a href="https://wa.me/6282282422317?text=Halo%20Admin%20Vantara%20Production%2C%20saya%20ingin%20konsultasi%20mengenai%20sewa%20sound%20system%20dan%20perlengkapan%20event." target="_blank" rel="noopener" class="btn wa sm" style="width: fit-content">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.62C9.33 7.62 9 7.7 8.71 8.01C8.42 8.32 7.6 9.09 7.6 10.65C7.6 12.21 8.74 13.72 8.9 13.93C9.06 14.14 11.14 17.34 14.32 18.72C15.08 19.04 15.67 19.24 16.14 19.38C16.9 19.63 17.59 19.59 18.14 19.51C18.75 19.42 20.03 18.73 20.29 17.98C20.56 17.23 20.56 16.59 20.48 16.45C20.4 16.32 20.19 16.24 19.89 16.09C19.58 15.93 18.08 15.2 17.8 15.1C17.52 15 17.32 14.95 17.11 15.25C16.91 15.56 16.34 16.24 16.17 16.44C16 16.65 15.83 16.67 15.52 16.52C15.22 16.37 14.24 16.05 13.08 15.01C12.18 14.2 11.57 13.2 11.41 12.92C11.25 12.65 11.39 12.5 11.55 12.35C11.69 12.21 11.86 11.99 12.01 11.81C12.16 11.64 12.22 11.51 12.32 11.31C12.42 11.1 12.37 10.92 12.29 10.77C12.21 10.62 11.6 9.12 11.35 8.5C11.1 7.9 10.85 7.98 10.66 7.97C10.48 7.97 10.27 7.96 10.07 7.96C9.86 7.96 9.53 7.62 9.53 7.62Z"/>
          </svg>
          <span>Chat WhatsApp Admin</span>
        </a>
        <span style="color: var(--mu); font-size: 13px">WhatsApp: 0822-8242-2317</span>
      </div>
    </div>
  </footer>

  @stack('scripts')
</body>
</html>

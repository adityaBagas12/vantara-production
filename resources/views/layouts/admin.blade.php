<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Vantara Production')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body class="admin" style="min-height: 100vh">

<div style="display: flex; flex-wrap: wrap; min-height: 100vh">
  <aside class="side" aria-label="Menu admin">
    <a class="brand" href="{{ route('home') }}" style="padding: 4px 14px 24px">
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

    <!-- Dashboard -->
    <a class="sa {{ request()->routeIs('admin.dashboard') ? 'on' : '' }}" href="{{ route('admin.dashboard') }}">
      <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M2 2h5v5H2zM9 2h5v5H9zM2 9h5v5H2zM9 9h5v5H9z"/>
      </svg>
      Dashboard
    </a>

    <!-- Produk -->
    <a class="sa {{ request()->routeIs('admin.packages.*') ? 'on' : '' }}" href="{{ route('admin.packages.index') }}">
      <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M2 5l6-3 6 3v6l-6 3-6-3zM2 5l6 3 6-3M8 8v6"/>
      </svg>
      Produk
    </a>

    <a class="sa {{ request()->routeIs('admin.landing-page.*') ? 'on' : '' }}" href="{{ route('admin.landing-page.edit') }}">
      <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="2" y="2" width="12" height="12" rx="2"/>
        <circle cx="5.5" cy="5.5" r="1"/>
        <path d="m3 12 3.5-3 2.5 2 1.5-1.5 2.5 2.5"/>
      </svg>
      Tampilan Landing
    </a>

    <!-- Booking -->
    <a class="sa {{ request()->routeIs('admin.orders.*') ? 'on' : '' }}" href="{{ route('admin.orders.index') }}">
      <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M3 3h10a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zM2 7h12M5 1.5v3M11 1.5v3"/>
      </svg>
      Booking
    </a>

    <!-- Laporan Bulanan -->
    <a class="sa {{ request()->routeIs('admin.reports.*') ? 'on' : '' }}" href="{{ route('admin.reports.index') }}">
      <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M2 14V8M6 14V4M10 14V9M14 14V2"/>
      </svg>
      Laporan Bulanan
    </a>

    <!-- Keluar Form -->
    <form action="{{ route('admin.logout') }}" method="POST" style="margin-top: auto; width: 100%">
      @csrf
      <button type="submit" class="sa" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left; font-size: inherit; font-family: inherit">
        <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M6 2H3v12h3M10 5l3 3-3 3M13 8H6"/>
        </svg>
        Keluar
      </button>
    </form>
  </aside>

  <main style="flex: 999 1 600px; min-width: 0; padding: 36px 44px; display: flex; flex-direction: column; gap: 28px">
    @if(session('success'))
      <div style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; background: #E4F6EA; border: 1px solid #A9DDBB; color: #17653A; font-size: 14px; font-weight: 500">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="9" r="8"/><path d="m5.5 9.5 2.5 2.5 4.5-5"/></svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; background: #FDECEC; border: 1px solid #F3B9B9; color: #8F1D1D; font-size: 14px; font-weight: 500">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="9" r="8"/><path d="M9 5v5M9 13v.5"/></svg>
        {{ session('error') }}
      </div>
    @endif

    @yield('content')
  </main>
</div>

@stack('scripts')
</body>
</html>

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
  <style>
    .admin-menu-toggle,
    .admin-menu-overlay { display: none; }

    @media (max-width: 768px) {
      body.admin > div { display: block !important; }
      .admin-menu-toggle {
        display: flex;
        position: fixed;
        z-index: 1102;
        top: 14px;
        right: 14px;
        width: 42px;
        height: 42px;
        padding: 10px;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        border: 1px solid #3A3F4C;
        border-radius: 10px;
        background: #1C1F27;
        color: #F2EFE9;
        cursor: pointer;
      }
      .admin-menu-toggle span {
        display: block;
        width: 100%;
        height: 2px;
        border-radius: 2px;
        background: currentColor;
        transition: transform .2s ease, opacity .2s ease;
      }
      .admin-menu-overlay {
        display: block;
        position: fixed;
        z-index: 1099;
        inset: 0;
        background: rgba(0, 0, 0, .55);
        opacity: 0;
        pointer-events: none;
        transition: opacity .2s ease;
      }
      body.admin .side {
        position: fixed !important;
        z-index: 1100;
        inset: 0 auto 0 0;
        width: min(82vw, 290px) !important;
        min-width: 0 !important;
        max-width: none !important;
        height: 100vh;
        flex: none !important;
        display: flex !important;
        flex-direction: column;
        align-items: stretch;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        padding: 20px 14px !important;
        gap: 6px !important;
        transform: translateX(-105%);
        transition: transform .2s ease;
        box-shadow: 16px 0 40px rgba(0, 0, 0, .35);
      }
      body.admin .side > .brand {
        padding: 4px 10px 26px !important;
        font-size: 19px;
      }
      body.admin .side > .sa,
      body.admin .side > form {
        min-width: 0;
        width: 100% !important;
        margin-top: 0 !important;
      }
      body.admin .side > .sa {
        min-height: 44px;
        padding: 0 12px;
        gap: 10px;
        font-size: 14px;
        white-space: nowrap;
      }
      body.admin .side > form { margin-top: auto !important; }
      body.admin .side > form .sa { justify-content: flex-start; }
      body.admin main { width: 100% !important; min-width: 0 !important; padding: 78px 16px 28px !important; }
      body.admin.admin-menu-open .side { transform: translateX(0); }
      body.admin.admin-menu-open .admin-menu-overlay { opacity: 1; pointer-events: auto; }
      body.admin.admin-menu-open .admin-menu-toggle span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
      body.admin.admin-menu-open .admin-menu-toggle span:nth-child(2) { opacity: 0; }
      body.admin.admin-menu-open .admin-menu-toggle span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }
      body.admin.admin-menu-lock { overflow: hidden; }
      body.admin table {
        display: table !important;
        width: 100% !important;
        min-width: 680px !important;
        overflow: visible !important;
        table-layout: auto;
        font-size: 11px;
      }
      body.admin table.admin-product-table {
        min-width: 680px !important;
      }
      body.admin th,
      body.admin td {
        padding: 7px 4px !important;
        white-space: normal !important;
        overflow-wrap: anywhere;
        word-break: break-word;
      }
      body.admin main > *,
      body.admin main .card,
      body.admin main form,
      body.admin main aside {
        min-width: 0 !important;
        max-width: 100% !important;
      }
      body.admin main .card { overflow: hidden; }
      body.admin main img { max-width: 100%; }
      body.admin main .btn {
        max-width: 100%;
        white-space: normal;
        text-align: center;
        line-height: 1.2;
      }
      body.admin main svg { max-width: 100%; }

      body.admin {
        overflow-x: hidden;
        font-size: 14px;
      }
      body.admin h1.d {
        font-size: 30px !important;
        line-height: 1.12 !important;
      }
      body.admin h2.d {
        font-size: 23px !important;
        line-height: 1.2 !important;
      }
      body.admin h3.d {
        font-size: 19px !important;
        line-height: 1.25 !important;
      }
      body.admin .card {
        border-radius: 14px;
      }
      body.admin .btn {
        min-height: 42px;
        padding-left: 14px;
        padding-right: 14px;
        font-size: 13px;
      }
      body.admin input:not([type="checkbox"]):not([type="radio"]),
      body.admin select,
      body.admin textarea {
        font-size: 16px !important;
      }

      body.admin .orders-filters {
        align-items: stretch !important;
      }
      body.admin .orders-filters .chip {
        flex: 1 1 auto;
        justify-content: center;
        text-align: center;
      }
      body.admin .orders-search {
        width: 100%;
      }
      body.admin .orders-search input {
        flex: 1;
        min-width: 0;
      }
      body.admin .orders-table-wrap {
        overflow: visible !important;
      }
      body.admin .orders-table {
        display: block !important;
        width: 100% !important;
        min-width: 0 !important;
        table-layout: auto !important;
      }
      body.admin .orders-table thead {
        display: none;
      }
      body.admin .orders-table tbody,
      body.admin .orders-table tr {
        display: block;
      }
      body.admin .orders-table tr {
        margin-bottom: 12px;
        padding: 10px 12px;
        border: 1px solid var(--admin-line);
        border-radius: 12px;
        background: #fff;
      }
      body.admin .orders-table td[data-label] {
        display: flex !important;
        width: 100%;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding: 8px 0 !important;
        border-bottom: 1px solid #F0EEE9;
        text-align: right;
        white-space: normal !important;
        overflow-wrap: anywhere;
      }
      body.admin .orders-table td[data-label]::before {
        content: attr(data-label);
        flex: 0 0 auto;
        color: var(--admin-mu);
        font-size: 12px;
        font-weight: 600;
        text-align: left;
      }
      body.admin .orders-table td[data-label]:last-child {
        border-bottom: 0;
        align-items: center;
      }
      body.admin .orders-table td[data-label]:last-child > div {
        justify-content: flex-end;
        flex-wrap: wrap;
      }
      body.admin .orders-table td[data-label] .tg {
        max-width: 60%;
        text-align: center;
      }
      body.admin .orders-table td[colspan] {
        display: block !important;
        text-align: center;
      }

      body.admin .order-show {
        gap: 18px !important;
      }
      body.admin .order-show-header {
        align-items: flex-start !important;
        gap: 12px !important;
      }
      body.admin .order-show-header h1 {
        max-width: 190px;
        font-size: 26px !important;
        line-height: 1.05 !important;
        overflow-wrap: anywhere;
      }
      body.admin .order-show-actions {
        width: 100%;
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px !important;
      }
      body.admin .order-show-actions > * {
        width: 100%;
        min-width: 0;
      }
      body.admin .order-show-actions .btn {
        width: 100%;
        padding-left: 8px;
        padding-right: 8px;
        white-space: normal;
      }
      body.admin .order-show-columns {
        display: block !important;
      }
      body.admin .order-show-left,
      body.admin .order-show-right {
        width: 100% !important;
        min-width: 0 !important;
        gap: 16px !important;
      }
      body.admin .order-show-right {
        margin-top: 16px;
      }
      body.admin .order-detail-card,
      body.admin .invoice-card {
        padding: 16px !important;
      }
      body.admin .order-detail-card .kv {
        display: grid;
        grid-template-columns: 104px minmax(0, 1fr);
        gap: 8px;
        font-size: 13px;
      }
      body.admin .order-detail-card .kv span:first-child {
        flex: none;
        font-size: 12px;
      }
      body.admin .order-detail-card table,
      body.admin .invoice-card table {
        display: block !important;
        min-width: 0 !important;
        width: 100% !important;
      }
      body.admin .order-detail-card table thead,
      body.admin .invoice-card table thead {
        display: none;
      }
      body.admin .order-detail-card table tbody,
      body.admin .order-detail-card table tr,
      body.admin .invoice-card table tbody,
      body.admin .invoice-card table tr {
        display: block;
      }
      body.admin .order-detail-card table tr,
      body.admin .invoice-card table tr {
        padding: 8px 0;
        border-bottom: 1px solid var(--admin-line);
      }
      body.admin .order-detail-card table td[data-label],
      body.admin .invoice-card table td[data-label] {
        display: flex !important;
        justify-content: space-between;
        gap: 10px;
        width: 100%;
        padding: 6px 0 !important;
        border: 0;
        text-align: right !important;
        white-space: normal !important;
        overflow-wrap: anywhere;
      }
      body.admin .order-detail-card table td[data-label]::before,
      body.admin .invoice-card table td[data-label]::before {
        content: attr(data-label);
        flex: 0 0 auto;
        color: var(--admin-mu);
        font-size: 11px;
        font-weight: 600;
        text-align: left;
      }
      body.admin .order-detail-card table + div,
      body.admin .invoice-card table + div {
        align-self: stretch !important;
        width: 100% !important;
      }
      body.admin .invoice-card > div:first-child > div:last-child {
        text-align: left !important;
      }
      body.admin .invoice-card > div:first-child > div:last-child h2 {
        font-size: 26px !important;
      }
    }
  </style>
  @stack('styles')
</head>
<body class="admin" style="min-height: 100vh">

<div style="display: flex; flex-wrap: wrap; min-height: 100vh">
  <button class="admin-menu-toggle" type="button" aria-label="Buka menu admin" aria-expanded="false" aria-controls="admin-sidebar">
    <span></span><span></span><span></span>
  </button>
  <div class="admin-menu-overlay" data-admin-menu-close></div>

  <aside class="side" id="admin-sidebar" aria-label="Menu admin">
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

<script>
  (() => {
    const body = document.body;
    const toggle = document.querySelector('.admin-menu-toggle');
    const side = document.querySelector('.side');
    if (!toggle || !side) return;

    const setOpen = (open) => {
      body.classList.toggle('admin-menu-open', open);
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute('aria-label', open ? 'Tutup menu admin' : 'Buka menu admin');
      body.classList.toggle('admin-menu-lock', open);
    };

    toggle.addEventListener('click', () => setOpen(!body.classList.contains('admin-menu-open')));
    document.querySelectorAll('[data-admin-menu-close], .side .sa').forEach((el) => {
      el.addEventListener('click', () => setOpen(false));
    });
  })();
</script>

@stack('scripts')
</body>
</html>

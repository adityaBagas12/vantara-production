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
  <style>
    @media (max-width: 768px) {
      .cart-layout {
        display: block !important;
      }

      .cart-layout > * {
        width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box;
      }

      .cart-layout > .cart-summary {
        margin-top: 24px;
        padding: 20px !important;
      }

      .cart-item {
        display: grid !important;
        grid-template-columns: 72px minmax(0, 1fr);
        gap: 14px !important;
        padding: 16px !important;
        align-items: start !important;
      }

      .cart-item .pic {
        width: 72px !important;
        height: 72px !important;
      }

      .cart-item-info {
        min-width: 0;
      }

      .cart-item-info h3 {
        font-size: 17px !important;
        line-height: 1.25;
        overflow-wrap: anywhere;
      }

      .cart-item-actions {
        grid-column: 1 / -1;
        width: 100%;
        margin-left: 0 !important;
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto auto;
        gap: 10px !important;
        align-items: center;
      }

      .cart-item-actions > div {
        min-width: 0 !important;
        text-align: right !important;
      }

      .cart-item-actions > div > div {
        font-size: 14px !important;
        white-space: nowrap;
      }

      .cart-item-actions input {
        width: 56px !important;
      }

      .summary-line {
        align-items: baseline;
        gap: 12px;
      }

      .summary-line > span:last-child {
        text-align: right;
        white-space: nowrap;
      }

      .summary-total {
        align-items: center !important;
        gap: 12px;
      }

      .summary-total strong:last-child {
        font-size: 19px !important;
        white-space: nowrap;
      }

      .cart-summary {
        padding: 16px !important;
        gap: 14px !important;
      }
      .cart-summary .summary-lines {
        gap: 8px !important;
        font-size: 14px !important;
      }
      .cart-summary .summary-line {
        gap: 8px;
      }
      .cart-summary .summary-line > span:last-child {
        font-size: 14px;
      }
      .cart-summary > div[style*="background: var(--s2)"] {
        padding: 12px !important;
        gap: 6px !important;
      }
      .cart-summary > div[style*="background: var(--s2)"] input[type="date"] {
        min-height: 42px;
        padding: 8px 10px !important;
        display: block;
        width: 100% !important;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
      }
      .cart-summary > div[style*="background: var(--s2)"] form {
        width: 100%;
        min-width: 0;
      }
      #cart_event_date,
      #cart_event_end_date {
        -webkit-appearance: none;
        appearance: none;
        display: block;
        width: calc(100% - 2px) !important;
        max-width: calc(100% - 2px) !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
        overflow: hidden;
        font-size: 14px !important;
      }
      .cart-summary .summary-total {
        padding-top: 14px !important;
      }
      .cart-summary .summary-total strong:first-child {
        font-size: 14px;
      }
      .cart-summary > .btn {
        min-height: 46px;
      }

      .wrap > h1[style*="font-size: 52px"] {
        font-size: 34px !important;
        margin-bottom: 24px !important;
      }

      body {
        font-size: 15px;
        overflow-x: hidden;
      }

      .nav-actions .btn.wa span {
        display: none !important;
      }

      .wrap {
        padding-left: 18px !important;
        padding-right: 18px !important;
      }

      .footer-inner {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 24px 18px !important;
        padding-top: 28px !important;
        padding-bottom: 28px !important;
      }
      .footer-brand {
        grid-column: 1 / -1;
        gap: 8px !important;
      }
      .footer-brand .brand { font-size: 22px; }
      .footer-brand p {
        font-size: 13px !important;
        line-height: 1.45;
        margin: 0;
      }
      .footer-nav,
      .footer-contact {
        min-width: 0 !important;
        gap: 7px !important;
      }
      .footer-nav .nl,
      .footer-contact p,
      .footer-contact span {
        font-size: 13px !important;
        line-height: 1.4;
      }
      .footer-contact .btn {
        width: 100% !important;
        justify-content: center;
        padding-left: 8px !important;
        padding-right: 8px !important;
      }

      .booking-steps {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr) auto minmax(0, 1fr);
        gap: 8px !important;
        margin-bottom: 24px !important;
      }
      .booking-steps .step {
        min-width: 0;
        display: grid;
        grid-template-rows: 32px auto;
        justify-items: center;
        align-content: start;
        gap: 6px;
        text-align: center;
        font-size: 11px;
        line-height: 1.2;
      }
      .booking-steps .step b {
        width: 32px;
        height: 32px;
        align-self: start;
      }
      .booking-steps .step.on {
        transform: none;
      }
      .booking-steps .rule {
        display: block;
        width: auto;
        height: auto;
        background: none;
        align-self: start;
        margin-top: 6px;
        color: var(--ac);
        font-size: 20px;
        line-height: 1;
      }
      .booking-steps .rule::after {
        content: '→';
      }

      .checkout-form {
        padding: 20px !important;
        gap: 14px !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden;
      }
      .checkout-form h2.d {
        font-size: 21px !important;
        margin: 0 !important;
      }
      .checkout-form h2.d[style*="border-top"] {
        margin-top: 8px !important;
        padding-top: 18px !important;
      }
      .checkout-form .row {
        gap: 12px !important;
      }
      .checkout-form > .row:first-of-type {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
      }
      .checkout-form > .row:first-of-type .fld label {
        min-height: 36px;
        display: flex;
        align-items: flex-end;
        line-height: 1.35;
      }
      .checkout-form > .row:first-of-type .fld input {
        font-size: 14px !important;
      }
      .checkout-form .fld {
        gap: 4px;
      }
      .checkout-form .fld input,
      .checkout-form .fld textarea {
        min-height: 42px;
        padding: 9px 11px !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
      }
      .checkout-form #tgl,
      .checkout-form #tgl_selesai {
        width: calc(100% - 36px) !important;
        min-width: 0 !important;
        max-width: calc(100% - 36px) !important;
        margin-left: 0 !important;
        margin-right: 36px !important;
      }
      .checkout-form .fld textarea {
        min-height: 84px;
      }
      .checkout-form > .row:nth-of-type(2) {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 12px !important;
      }
      .checkout-form > .row:nth-of-type(2) > .fld:last-child {
        grid-column: 1 / -1;
      }
      .checkout-summary {
        padding: 20px !important;
        gap: 14px !important;
      }

      h1.d {
        font-size: 34px !important;
        line-height: 1.12 !important;
      }

      h2.d {
        font-size: 26px !important;
        line-height: 1.18 !important;
      }

      h3.d {
        font-size: 20px !important;
        line-height: 1.25 !important;
      }

      .card {
        border-radius: 16px;
      }

      .btn {
        min-height: 44px;
        padding-left: 16px;
        padding-right: 16px;
        font-size: 14px;
      }

      input:not([type="checkbox"]):not([type="radio"]),
      select,
      textarea {
        font-size: 16px !important;
      }

      .catalog-header {
        padding-top: 32px !important;
        padding-bottom: 24px !important;
      }
      .catalog-header > div:first-of-type {
        gap: 8px !important;
      }
      .catalog-header > div:first-of-type > span {
        width: 100%;
      }
      .catalog-layout {
        display: block !important;
        padding-bottom: 48px !important;
      }
      .catalog-filter {
        width: 100% !important;
        padding: 18px !important;
        margin-bottom: 20px;
      }
      .catalog-filter .chk {
        min-height: 36px;
      }
      .product-grid {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 16px !important;
      }
      .product-card {
        min-width: 0;
        border-radius: 14px;
      }
      .product-visual {
        height: 170px !important;
      }
      .product-card-body {
        padding: 16px !important;
      }
      .product-card-body strong {
        font-size: 18px !important;
        line-height: 1.25 !important;
        overflow-wrap: anywhere;
      }
      .product-card-body .price {
        font-size: 18px !important;
        margin: 6px 0 12px !important;
      }
      .home-hero {
        padding-top: 34px !important;
        padding-bottom: 48px !important;
        gap: 28px !important;
      }
      .home-hero > div:first-child {
        gap: 18px !important;
      }
      .home-hero h1 {
        font-size: 42px !important;
        line-height: 1.05 !important;
      }
      .home-hero p {
        font-size: 16px !important;
        line-height: 1.5;
      }
      .home-hero-image {
        height: 270px !important;
        border-radius: 18px !important;
      }
      .home-services {
        padding-bottom: 56px !important;
      }
      .home-services h2 {
        font-size: 28px !important;
        line-height: 1.18 !important;
      }
      .services-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
      }
      .service-item {
        min-height: 104px !important;
        padding: 16px 12px 18px !important;
        gap: 12px !important;
      }
      .service-item span {
        font-size: 13px;
      }
      .service-item strong {
        font-size: 16px !important;
        line-height: 1.2 !important;
        overflow-wrap: anywhere;
      }
      .home-about-image {
        height: 250px !important;
        border-radius: 18px !important;
      }

      .product-main-image {
        height: 280px !important;
        border-radius: 18px !important;
      }
      .product-gallery {
        gap: 8px !important;
      }
      .product-gallery-image {
        height: 68px !important;
        border-radius: 10px !important;
      }

      /* Detail produk: susun kalender dan hasil pengecekan agar nyaman di HP. */
      #cek-tanggal-acara > .card {
        display: block !important;
        padding: 16px !important;
        overflow: hidden;
      }

      #cek-tanggal-acara > .card > div {
        width: 100% !important;
        min-width: 0 !important;
        flex: none !important;
        box-sizing: border-box;
      }

      #cek-tanggal-acara > .card > div + div {
        margin-top: 20px;
        padding: 18px !important;
      }

      #cek-tanggal-acara .cal {
        width: 100%;
        gap: 4px !important;
      }
      #cek-tanggal-acara .cal .h {
        font-size: 11px !important;
        padding-bottom: 2px;
      }
      #cek-tanggal-acara .cal .c {
        min-height: 38px !important;
        border-radius: 9px;
        font-size: 13px;
      }
      #cek-tanggal-acara > .card {
        padding: 12px !important;
      }
      #cek-tanggal-acara h2 {
        font-size: 26px !important;
        margin-bottom: 16px !important;
      }
      #cek-tanggal-acara > .card > div:first-child > div:first-of-type .btn.ic {
        width: 38px;
        min-width: 38px;
        height: 38px;
        min-height: 38px;
      }
      #cek-tanggal-acara > .card > div:first-child > div:first-of-type strong {
        font-size: 18px !important;
      }
      #cek-tanggal-acara > .card > div:first-child > div:last-child {
        gap: 10px !important;
        margin-top: 14px !important;
        font-size: 11px !important;
      }
    }
  </style>
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

    </div>
  </nav>

  <main style="flex: 1 0 auto">
    @yield('content')
  </main>

  <footer style="border-top: 1px solid var(--line); margin-top: auto">
    <div class="wrap footer-inner" style="padding-top: 48px; padding-bottom: 48px; display: flex; flex-wrap: wrap; gap: 40px">
      <div class="footer-brand" style="flex: 2 1 320px; display: flex; flex-direction: column; gap: 12px">
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
      <div class="footer-nav" style="flex: 1 1 180px; display: flex; flex-direction: column; gap: 8px">
        <strong style="color: var(--tx)">Navigasi</strong>
        <a class="nl" href="{{ route('packages.catalog') }}">Katalog</a>
        <a class="nl" href="{{ route('cart.index') }}">Keranjang</a>
        <a class="nl" href="{{ route('checkout.index') }}">Booking</a>
        <a class="nl" href="{{ route('admin.login') }}">Login Admin</a>
      </div>
      <div class="footer-contact" style="flex: 1 1 240px; display: flex; flex-direction: column; gap: 12px">
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

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin — Vantara Production</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin" style="min-height: 100vh">

<div style="display: flex; flex-wrap: wrap; min-height: 100vh">
  <!-- Left Side: Dark Hero Panel -->
  <div style="flex: 1 1 520px; background: radial-gradient(90% 70% at 80% 0%, rgba(245,165,36,.30), transparent 60%), linear-gradient(160deg,#1F2330,#0D0E12); color: #F2EFE9; padding: 56px; display: flex; flex-direction: column; justify-content: space-between; gap: 48px">
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
    <div>
      <h1 class="d" style="font-size: 56px; line-height: 1.04; font-weight: 800; max-width: 460px">Kelola rental, tanpa catatan berserakan.</h1>
      <p style="margin-top: 18px; font-size: 18px; color: #A9ADB9; max-width: 420px">Produk, booking, transaksi, invoice, dan laporan bulanan dalam satu panel.</p>
    </div>
    <p style="color: #A9ADB9; font-size: 14px">Vantara Production · Kota Bengkulu</p>
  </div>

  <!-- Right Side: Clean Login Form -->
  <div style="flex: 1 1 480px; display: grid; place-items: center; padding: 48px 32px; background: #F5F4F0">
    <form action="{{ route('admin.login.submit') }}" method="POST" style="width: 100%; max-width: 400px; display: flex; flex-direction: column; gap: 20px">
      @csrf
      <div>
        <h2 class="d" style="font-size: 36px; font-weight: 800">Masuk Admin</h2>
        <p style="color: var(--mu); margin-top: 6px">Gunakan akun admin Vantara Production.</p>
      </div>

      <div class="fld">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}" placeholder="Masukkan email admin">
      </div>

      <div class="fld">
        <label for="pw">Password</label>
        <div style="position: relative">
          <input id="pw" name="password" type="password" autocomplete="current-password" required placeholder="••••••••" style="padding-right: 48px">
          <button type="button" id="toggle-password" aria-label="Tampilkan password" title="Tampilkan password" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 36px; height: 36px; border: 0; background: transparent; color: #7A7D87; cursor: pointer; display: grid; place-items: center">
            <svg id="eye-icon" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
              <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
              <circle cx="12" cy="12" r="2.5"/>
            </svg>
          </button>
        </div>
      </div>

      @if ($errors->any() || session('error'))
        <div role="alert" style="display: flex; gap: 10px; align-items: flex-start; padding: 12px 14px; border-radius: 12px; background: #FDECEC; border: 1px solid #F3B9B9; color: #8F1D1D; font-size: 14px; line-height: 1.45">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true" style="flex: none"><circle cx="9" cy="9" r="7.5"/><path d="M9 5v5M9 12.5v.5"/></svg>
          <span>
            @if ($errors->any())
              {{ $errors->first() }}
            @else
              {{ session('error') }}
            @endif
          </span>
        </div>
      @endif

      <button class="btn p" type="submit" style="width: 100%; font-size: 16px; border-radius: 12px">Masuk</button>
      <a href="{{ route('home') }}" style="color: var(--mu); text-align: center; text-decoration: underline; min-height: 44px; display: grid; place-items: center">← Kembali ke website</a>
    </form>
  </div>
</div>

<script>
  document.getElementById('toggle-password')?.addEventListener('click', function () {
    const password = document.getElementById('pw');
    const visible = password.type === 'text';
    password.type = visible ? 'password' : 'text';
    this.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
    this.setAttribute('title', visible ? 'Tampilkan password' : 'Sembunyikan password');
  });
</script>

</body>
</html>

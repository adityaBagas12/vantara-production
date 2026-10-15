@extends('layouts.admin')

@section('title', 'Dashboard Admin — Vantara Production')

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px">
  <!-- Header Title -->
  <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap">
    <div style="flex-grow: 1">
      <h1 class="d" style="font-size: 36px; font-weight: 800">Dashboard</h1>
      <p style="color: var(--admin-mu)">Ringkasan Operasional &amp; Penjualan · {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <span style="display: inline-flex; align-items: center; gap: 10px; background: #fff; border: 1px solid var(--admin-line); border-radius: 999px; padding: 6px 16px 6px 6px">
      <span style="width: 34px; height: 34px; border-radius: 50%; background: #0D0E12; color: var(--ac); display: grid; place-items: center; font-weight: 700">A</span>
      <strong>Admin Vantara</strong>
    </span>
  </div>

  <!-- 4 Stat Cards -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px">
    <!-- 1. Pendapatan kotor -->
    <div class="card" style="display: flex; flex-direction: column; gap: 10px">
      <span class="lbl">Pendapatan kotor</span>
      <strong class="d" style="font-size: 30px; line-height: 1; color: var(--admin-tx)">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
      <span style="font-size: 13px; color: var(--admin-mu)">transaksi ACC &amp; selesai</span>
    </div>

    <!-- 2. Pesanan pending -->
    <div class="card" style="display: flex; flex-direction: column; gap: 10px">
      <span class="lbl">Pesanan pending</span>
      <strong class="d" style="font-size: 34px; line-height: 1; color: #8A5300">{{ $pendingCount }}</strong>
      <span style="font-size: 13px; color: var(--admin-mu)">butuh tindakan admin</span>
    </div>

    <!-- 3. Acara mendatang -->
    <div class="card" style="display: flex; flex-direction: column; gap: 10px">
      <span class="lbl">Acara mendatang</span>
      <strong class="d" style="font-size: 34px; line-height: 1; color: #17653A">{{ $confirmedCount }}</strong>
      <span style="font-size: 13px; color: var(--admin-mu)">terjadwal &amp; DP masuk</span>
    </div>

    <!-- 4. Total pesanan -->
    <div class="card" style="display: flex; flex-direction: column; gap: 10px">
      <span class="lbl">Total pesanan bulan ini</span>
      <strong class="d" style="font-size: 34px; line-height: 1; color: var(--admin-tx)">{{ $totalOrdersCount }}</strong>
      <span style="font-size: 13px; color: var(--admin-mu)">seluruh histori pesanan</span>
    </div>
  </div>

  <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-start">
    <!-- Tabel Booking Terbaru -->
    <div class="card" style="flex: 2 1 460px; padding: 24px 24px 12px">
      <div style="display: flex; align-items: center; margin-bottom: 8px">
        <h2 class="d" style="font-size: 22px; flex-grow: 1">Booking terbaru</h2>
        <a href="{{ route('admin.orders.index') }}" style="font-weight: 600; text-decoration: underline">Lihat semua</a>
      </div>
      <div style="overflow-x: auto">
        <table>
          <thead>
            <tr>
              <th>Customer</th>
              <th>Tanggal acara</th>
              <th>Produk</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentOrders as $order)
              @php
                $statusClass = match($order->status) {
                  'pending' => 'w',
                  'dp_received' => 'b',
                  'confirmed' => 'a',
                  'completed' => 'c',
                  'cancelled' => 'r',
                  default => 'w',
                };
                $statusLabel = match($order->status) {
                  'pending' => 'Pending',
                  'dp_received' => 'DP Masuk',
                  'confirmed' => 'Dikonfirmasi',
                  'completed' => 'Selesai',
                  'cancelled' => 'Batal',
                  default => ucfirst($order->status),
                };
              @endphp
              <tr>
                <td style="font-weight: 600">
                  <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->customer_name }}</a>
                </td>
                <td style="white-space: nowrap">{{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('d M Y') }}</td>
                <td style="color: var(--admin-mu)">{{ $order->orderItems->first()?->package_name ?? 'Layanan Vantara' }}</td>
                <td><span class="tg {{ $statusClass }}">{{ $statusLabel }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; color: var(--admin-mu)">Belum ada booking masuk.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Grafik Pendapatan Batang SVG -->
    <div class="card" style="flex: 1 1 320px; display: flex; flex-direction: column; gap: 14px">
      <div style="display: flex; align-items: center">
        <h2 class="d" style="font-size: 22px; flex-grow: 1">Pendapatan {{ date('Y') }}</h2>
        <a href="{{ route('admin.reports.index') }}" style="font-weight: 600; text-decoration: underline">Laporan</a>
      </div>
      @php
        $maxRev = collect($monthlyChartData)->max('revenue') ?: 1;
        $currentM = (int) date('n');
        $mInitials = ['J','F','M','A','M','J','J','A','S','O','N','D'];
      @endphp
      <svg viewBox="0 0 372 190" width="100%" role="img" aria-label="Grafik batang pendapatan per bulan tahun {{ date('Y') }}">
        <line x1="0" y1="160" x2="372" y2="160" stroke="#E4E1D9"/>
        @foreach($monthlyChartData as $i => $bar)
          @php
            $barH = $bar['revenue'] > 0 ? max(8, round(($bar['revenue'] / $maxRev) * 120)) : 4;
            $bx = $i * 31 + 4;
            $by = 160 - $barH;
            $btx = $i * 31 + 15;
            $fill = ($bar['month_num'] === $currentM) ? '#F5A524' : '#D9D5CA';
          @endphp
          <rect x="{{ $bx }}" y="{{ $by }}" width="22" height="{{ $barH }}" rx="5" fill="{{ $fill }}">
            <title>{{ $bar['month'] }}: Rp {{ number_format($bar['revenue'], 0, ',', '.') }}</title>
          </rect>
          <text x="{{ $btx }}" y="180" font-size="11" text-anchor="middle" fill="#62656F">{{ $mInitials[$i] }}</text>
        @endforeach
      </svg>
      <p style="font-size: 13px; color: var(--admin-mu)">
        Hanya transaksi yang sudah di-ACC / memiliki DP yang dihitung.
      </p>
    </div>
  </div>
</div>
@endsection

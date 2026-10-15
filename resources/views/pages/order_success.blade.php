@extends('layouts.app')

@section('title', 'Invoice Pesanan ' . $order->order_code . ' — Vantara Production')
@section('meta_description', 'Invoice dan bukti pemesanan sewa sound system dan perlengkapan hiburan Vantara Production.')

@section('content')
<div style="min-height: 1300px">
  <section class="wrap" style="padding-top: 56px; padding-bottom: 96px">
    <!-- Step Indicator -->
    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 36px">
      <span class="step ok"><b>✓</b>Pilih produk &amp; tanggal</span><i class="rule"></i>
      <span class="step ok"><b>✓</b>Data diri &amp; acara</span><i class="rule"></i>
      <span class="step on"><b>3</b>Invoice &amp; WhatsApp</span>
    </div>

    <!-- Success Alert Banner -->
    <div style="display: flex; align-items: center; gap: 16px; padding: 18px 24px; border: 1px solid rgba(74,222,128,.35); background: rgba(74,222,128,.08); border-radius: 16px; margin-bottom: 32px; flex-wrap: wrap">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4ADE80" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m7.5 12.5 3 3 6-6.5"/></svg>
      <div style="flex: 1 1 300px">
        <strong style="color: #F2EFE9">Booking berhasil dikirim.</strong>
        <span style="color: var(--mu)">Data Anda sudah tercatat. Lanjutkan ke WhatsApp untuk menyepakati harga dan pembayaran.</span>
      </div>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-start">
      <!-- Paper Invoice -->
      <article class="paper" style="flex: 2 1 580px; display: flex; flex-direction: column; gap: 28px">
        <div style="display: flex; flex-wrap: wrap; gap: 20px">
          <div style="flex-grow: 1">
            <span class="d" style="font-weight: 800; font-size: 20px; letter-spacing: .06em">VANTARA PRODUCTION</span>
            <p style="color: #4A4D57; font-size: 14px; margin-top: 4px">Jl. Merapi Ujung No.31, Kel. Panorama,<br>Kec. Singaran Pati, Kota Bengkulu</p>
          </div>
          <div style="text-align: right">
            <h1 class="d" style="font-size: 36px; font-weight: 800; letter-spacing: .02em">INVOICE</h1>
            <p style="color: #17181C; font-size: 15px; font-weight: 700; font-family: var(--font-mono)">{{ $order->order_code }}</p>
            <p style="color: #4A4D57; font-size: 14px">Diterbitkan {{ $order->created_at->translatedFormat('d F Y') }}</p>
          </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 32px; border-top: 1px solid #E4E1D9; border-bottom: 1px solid #E4E1D9; padding: 22px 0">
          <div style="flex: 1 1 180px">
            <span class="lb">Ditagihkan kepada</span>
            <p style="margin-top: 6px; font-weight: 600">{{ $order->customer_name }}</p>
            <p style="color: #4A4D57; font-size: 14px">{{ $order->customer_phone }}</p>
            @if($order->customer_email)
              <p style="color: #4A4D57; font-size: 13px">{{ $order->customer_email }}</p>
            @endif
          </div>
          <div style="flex: 1 1 160px">
            <span class="lb">Tanggal acara</span>
            <p style="margin-top: 6px; font-weight: 600">{{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('d F Y') }}</p>
            <p style="color: #4A4D57; font-size: 14px">{{ $order->event_address }}</p>
          </div>
          <div style="flex: 1 1 160px">
            <span class="lb">Status</span>
            <p style="margin-top: 6px">
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
                  'pending' => 'Menunggu konfirmasi',
                  'dp_received' => 'DP Diterima',
                  'confirmed' => 'Dikonfirmasi',
                  'completed' => 'Selesai',
                  'cancelled' => 'Dibatalkan',
                  default => ucfirst($order->status),
                };
              @endphp
              <span class="tg {{ $statusClass }}">{{ $statusLabel }}</span>
            </p>
          </div>
        </div>

        <div style="overflow-x: auto">
          <table>
            <thead>
              <tr>
                <th>Produk</th>
                <th>Deskripsi</th>
                <th>Jml</th>
                <th style="text-align: right">Harga</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->orderItems as $item)
                <tr>
                  <td style="font-weight: 600">{{ $item->package_name }}</td>
                  <td style="color: #4A4D57">Sewa {{ $item->quantity }} hari</td>
                  <td>1</td>
                  <td style="text-align: right; font-family: var(--font-mono); font-weight: 600">Rp {{ number_format($order->negotiated_subtotals[$item->id] ?? $item->subtotal, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="align-self: flex-end; width: 300px; display: flex; flex-direction: column; gap: 10px">
          <div style="display: flex; color: #4A4D57">
            <span style="flex-grow: 1">Subtotal</span>
            <span style="font-family: var(--font-mono)">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
          </div>
          @if($order->down_payment > 0)
            <div style="display: flex; color: #17653A">
              <span style="flex-grow: 1">Uang Muka (DP)</span>
              <span style="font-family: var(--font-mono)">- Rp {{ number_format($order->down_payment, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; color: #4A4D57">
              <span style="flex-grow: 1">Sisa Tagihan</span>
              <span style="font-family: var(--font-mono)">Rp {{ number_format($order->total_price - $order->down_payment, 0, ',', '.') }}</span>
            </div>
          @endif
          <div style="display: flex; align-items: baseline; border-top: 2px solid #17181C; padding-top: 12px">
            <strong style="flex-grow: 1">Total tagihan</strong>
            <strong class="d" style="font-size: 26px; font-family: var(--font-mono)">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
          </div>
        </div>

        <p style="font-size: 13px; color: #6A6D77">
          Total berupa estimasi. Harga akhir disepakati bersama Admin sebelum transaksi di-ACC.
        </p>
      </article>

      <!-- Action Card -->
      <aside class="card" style="flex: 1 1 340px; padding: 32px; display: flex; flex-direction: column; gap: 18px">
        <span class="eye">Langkah berikutnya</span>
        <h2 class="d" style="font-size: 28px; line-height: 1.15">Lanjutkan transaksi lewat WhatsApp</h2>
        <p style="color: var(--mu)">Diskusikan kebutuhan, harga akhir, dan pembayaran langsung dengan Admin Vantara Production.</p>
        <a class="btn wa" href="{{ $waUrl }}" target="_blank" style="text-align: center">Buka WhatsApp Admin Vantara</a>
        <a href="{{ route('home') }}" style="color: var(--mu); text-align: center; text-decoration: underline; min-height: 44px; display: grid; place-items: center">Kembali ke Beranda</a>
      </aside>
    </div>
  </section>
</div>
@endsection

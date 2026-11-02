@extends('layouts.app')

@section('title', 'Form Booking — Vantara Production')
@section('meta_description', 'Lengkapi data diri dan rincian acara Anda untuk menyelesaikan pemesanan paket rental Vantara Production.')

@section('content')
<div style="min-height: 1500px">
  <section class="wrap" style="padding-top: 56px; padding-bottom: 96px">
    <span class="eye">Booking</span>
    <h1 class="d" style="font-size: 52px; line-height: 1.05; font-weight: 800; margin: 8px 0 28px">Form Booking</h1>

    <!-- Steps -->
    <div class="booking-steps" style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 40px">
      <span class="step ok"><b>✓</b>Pilih produk &amp; tanggal</span><i class="rule"></i>
      <span class="step on"><b>2</b>Data diri &amp; acara</span><i class="rule"></i>
      <span class="step"><b>3</b>Invoice &amp; WhatsApp</span>
    </div>

    @if ($errors->any())
      <div style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; background: #FDECEC; border: 1px solid #F3B9B9; color: #8F1D1D; font-size: 14px; margin-bottom: 24px">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10" cy="10" r="8"/><path d="M10 6v5M10 14v.5"/></svg>
        <div>
          <strong>Mohon periksa data Anda:</strong>
          <ul style="margin: 4px 0 0 16px; padding: 0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    <div style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-start">
      <!-- Form Input Data -->
      <form action="{{ route('checkout.store') }}" method="POST" class="card checkout-form" style="flex: 2 1 580px; padding: 40px; display: flex; flex-direction: column; gap: 22px">
        @csrf

        <h2 class="d" style="font-size: 24px">Data diri</h2>
        <div class="row">
          <div class="fld">
            <label for="nama">Nama lengkap</label>
            <input id="nama" name="customer_name" type="text" placeholder="Nama sesuai identitas" required value="{{ old('customer_name') }}">
          </div>
          <div class="fld">
            <label for="telp">Nomor telepon / WhatsApp</label>
            <input id="telp" name="customer_phone" type="tel" placeholder="08xx xxxx xxxx" required value="{{ old('customer_phone') }}">
          </div>
        </div>
        <div class="row">
          <div class="fld">
            <label for="email">Email (opsional)</label>
            <input id="email" name="customer_email" type="email" placeholder="nama@email.com" value="{{ old('customer_email') }}">
          </div>
        </div>

        <h2 class="d" style="font-size: 24px; margin-top: 18px; padding-top: 28px; border-top: 1px solid var(--line)">Data acara</h2>
        <div class="row">
          <div class="fld">
            <label for="tgl">Tanggal mulai acara</label>
            <input id="tgl" name="event_date" type="date" required min="{{ date('Y-m-d') }}" value="{{ old('event_date', request('event_date') ?? session('event_date') ?? '') }}">
          </div>
          <div class="fld">
            <label for="tgl_selesai">Tanggal selesai <span style="color: var(--mu); font-weight: 400">(opsional)</span></label>
            <input id="tgl_selesai" name="event_end_date" type="date" min="{{ old('event_date', request('event_date') ?? session('event_date') ?? date('Y-m-d')) }}" value="{{ old('event_end_date', request('event_end_date') ?? session('event_end_date') ?? '') }}">
          </div>
          <div class="fld">
            <label for="alamat">Alamat / lokasi acara</label>
            <input id="alamat" name="event_address" type="text" placeholder="Nama tempat atau alamat lengkap acara" required value="{{ old('event_address') }}">
          </div>
        </div>

        <div class="fld">
          <label for="cat">Catatan (opsional)</label>
          <textarea id="cat" name="notes" rows="4" placeholder="Jenis acara, jam mulai, kebutuhan khusus…">{{ old('notes') }}</textarea>
        </div>

        <div style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 8px">
          <button class="btn p" type="submit">Kirim Booking</button>
          <a class="btn" href="{{ route('cart.index') }}">Kembali ke Keranjang</a>
        </div>
      </form>

      <!-- Sidebar Ringkasan Pesanan -->
      <aside class="card checkout-summary" style="flex: 1 1 340px; padding: 28px; display: flex; flex-direction: column; gap: 20px">
        <span class="eye">Ringkasan pesanan</span>

        @forelse($cart as $item)
          <div style="display: flex; gap: 16px; align-items: center">
            @if(!empty($item['image_path']))
              <div class="pic" style="width: 92px; height: 92px; border-radius: 16px; flex: none; border: 1px solid var(--line)">
                <img src="{{ $item['image_path'] }}" alt="{{ $item['name'] }}" style="width: 100%; height: 100%; object-fit: cover">
              </div>
            @else
              <div class="pic" style="width: 92px; height: 92px; border-radius: 16px; flex: none; border: 1px solid var(--line)">
                <svg width="40" height="40" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="12" y="4" width="24" height="40" rx="4"/><circle cx="24" cy="30" r="7"/><circle cx="24" cy="30" r="3"/><circle cx="24" cy="14" r="3"/></svg>
              </div>
            @endif
            <div>
              <strong class="d" style="font-size: 18px; line-height: 1.25">{{ $item['name'] }}</strong>
              <p style="color: var(--mu); font-size: 14px">{{ $item['category'] ?? 'Layanan' }}</p>
            </div>
          </div>
          <div style="border-top: 1px solid var(--line); padding-top: 14px; display: flex; flex-direction: column; gap: 8px">
            <div style="display: flex"><span style="flex-grow: 1; color: var(--mu)">Harga / hari</span><span>Rp {{ number_format($item['price'], 0, ',', '.') }}</span></div>
            <div style="display: flex"><span style="flex-grow: 1; color: var(--mu)">Jumlah unit</span><span>{{ $item['quantity'] }} unit</span></div>
            <div style="display: flex"><span style="flex-grow: 1; color: var(--mu)">Subtotal ({{ $rentDuration }} hari)</span><span style="font-weight: 600">Rp {{ number_format($item['price'] * $item['quantity'] * $rentDuration, 0, ',', '.') }}</span></div>
          </div>
        @empty
          <p style="color: var(--mu)">Keranjang kosong.</p>
        @endforelse

        <div style="border-top: 1px solid var(--line); padding-top: 18px; display: flex; align-items: baseline">
          <strong style="flex-grow: 1">Estimasi total</strong>
          <strong class="d" style="font-size: 26px; color: var(--ac)">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</strong>
        </div>

        <p style="font-size: 14px; color: var(--mu); background: var(--s2); border-radius: 12px; padding: 12px 14px">
          Harga akhir disepakati bersama Admin lewat WhatsApp setelah booking terkirim.
        </p>
      </aside>
    </div>
  </section>
</div>
@endsection

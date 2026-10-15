@extends('layouts.app')

@section('title', 'Keranjang Pemesanan — Vantara Production')
@section('meta_description', 'Tinjau daftar paket rental sound system dan peralatan hiburan Anda.')

@section('content')
<div style="min-height: 1200px">
  <section class="wrap" style="padding-top: 56px; padding-bottom: 96px">
    <span class="eye">Keranjang</span>
    <h1 class="d" style="font-size: 52px; line-height: 1.05; font-weight: 800; margin: 8px 0 36px">Keranjang Pemesanan</h1>

    @if (session('success'))
      <div style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; background: rgba(74,222,128,.1); border: 1px solid rgba(74,222,128,.35); color: #86EFAC; font-size: 14px; margin-bottom: 24px">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10" cy="10" r="8"/><path d="m6.5 10.5 2.5 2.5 4.5-5"/></svg>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if (session('error'))
      <div style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; background: #FDECEC; border: 1px solid #F3B9B9; color: #8F1D1D; font-size: 14px; margin-bottom: 24px">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10" cy="10" r="8"/><path d="M10 6v5M10 14v.5"/></svg>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    @if (empty($cart))
      <div class="card" style="padding: 64px 32px; text-align: center; max-width: 600px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; gap: 16px">
        <div class="pic" style="width: 80px; height: 80px; border-radius: 20px; border: 1px solid var(--line)">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <h2 class="d" style="font-size: 26px">Keranjang Anda masih kosong</h2>
        <p style="color: var(--mu); max-width: 420px">Anda belum memilih peralatan atau paket acara. Silakan jelajahi katalog kami.</p>
        <a class="btn p" href="{{ route('packages.catalog') }}" style="margin-top: 8px">Lihat Katalog Paket</a>
      </div>
    @else
      <div style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-start">
        <!-- Cart Items List -->
        <div style="flex: 2 1 580px; display: flex; flex-direction: column; gap: 20px">
          <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px">
            <span style="color: var(--mu); font-size: 15px">Terdapat <strong style="color: var(--tx)">{{ count($cart) }}</strong> paket di keranjang</span>
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
              @csrf
              <button type="submit" style="background: transparent; border: 0; color: #FCA5A5; font: inherit; font-size: 14px; cursor: pointer; text-decoration: underline">
                Kosongkan Keranjang
              </button>
            </form>
          </div>

          @foreach ($cart as $id => $item)
            <div class="card" style="padding: 24px; display: flex; flex-wrap: wrap; gap: 20px; align-items: center">
              @if(!empty($item['image_path']))
                <div class="pic" style="width: 88px; height: 88px; border-radius: 16px; flex: none; border: 1px solid var(--line)">
                  <img src="{{ $item['image_path'] }}" alt="{{ $item['name'] }}" style="width: 100%; height: 100%; object-fit: cover">
                </div>
              @else
                <div class="pic" style="width: 88px; height: 88px; border-radius: 16px; flex: none; border: 1px solid var(--line)">
                  <svg width="40" height="40" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="12" y="4" width="24" height="40" rx="4"/><circle cx="24" cy="30" r="7"/><circle cx="24" cy="30" r="3"/><circle cx="24" cy="14" r="3"/></svg>
                </div>
              @endif

              <div style="flex: 1 1 200px">
                <span style="color: var(--mu); font-size: 13px">{{ $item['category'] ?? 'Layanan' }}</span>
                <h3 class="d" style="font-size: 20px">
                  <a href="{{ route('packages.show', $item['slug']) }}">{{ $item['name'] }}</a>
                </h3>
                <span class="price" style="font-size: 17px; margin-top: 4px; display: inline-block">
                  Rp {{ number_format($item['price'], 0, ',', '.') }} <span style="font-size: 13px; color: var(--mu); font-weight: 500">/ hari</span>
                </span>
              </div>

              <!-- Quantity & Subtotal -->
              <div style="display: flex; align-items: center; gap: 20px; margin-left: auto">
                <form action="{{ route('cart.update', $id) }}" method="POST" style="display: flex; align-items: center; gap: 8px">
                  @csrf
                  @method('PATCH')
                  <label for="qty_{{ $id }}" style="font-size: 13px; color: var(--mu)">Unit:</label>
                  <input id="qty_{{ $id }}" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 99 }}" onchange="this.form.submit()"
                         title="Maksimal {{ $item['stock'] ?? 1 }} unit"
                         style="width: 64px; background: var(--s2); border: 1px solid #3A3F4C; border-radius: 10px; padding: 6px 10px; color: var(--tx); font-family: var(--font-mono); text-align: center">
                </form>

                <div style="text-align: right; min-width: 120px">
                  <span style="font-size: 12px; color: var(--mu); text-transform: uppercase">Subtotal</span>
                  <div style="font-family: var(--font-mono); font-weight: 700; font-size: 17px; color: var(--ac)">
                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                  </div>
                </div>

                <form action="{{ route('cart.destroy', $id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" aria-label="Hapus paket" style="background: transparent; border: 0; color: #8F1D1D; cursor: pointer; padding: 8px; font-size: 18px" title="Hapus">
                    ✕
                  </button>
                </form>
              </div>
            </div>
          @endforeach

          <a href="{{ route('packages.catalog') }}" style="color: var(--mu); font-size: 14px; text-decoration: underline; margin-top: 8px">
            ← Tambah paket lain dari katalog
          </a>
        </div>

        <!-- Summary Aside -->
        <aside class="card" style="flex: 1 1 340px; padding: 32px; display: flex; flex-direction: column; gap: 20px">
          <span class="eye">Ringkasan Biaya</span>

          <div style="display: flex; flex-direction: column; gap: 12px; font-size: 15px">
            <div style="display: flex"><span style="flex-grow: 1; color: var(--mu)">Total paket</span><span style="font-family: var(--font-mono)">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
            <div style="display: flex"><span style="flex-grow: 1; color: var(--mu)">Kru &amp; Teknisi</span><span style="color: #4ADE80; font-weight: 600">Termasuk</span></div>
            <div style="display: flex"><span style="flex-grow: 1; color: var(--mu)">Instalasi &amp; Antar</span><span style="color: #4ADE80; font-weight: 600">Termasuk</span></div>
          </div>

          <!-- Tanggal Acara -->
          <div style="background: var(--s2); border: 1px solid var(--line); border-radius: 14px; padding: 16px; display: flex; flex-direction: column; gap: 8px">
            <label for="cart_event_date" style="font-size: 13px; color: var(--mu); font-weight: 600">Tanggal Mulai Acara:</label>
            <form action="{{ route('cart.index') }}" method="GET">
              <input type="date" id="cart_event_date" name="event_date"
                     value="{{ old('event_date', request('event_date') ?? request('date') ?? session('event_date')) }}"
                     min="{{ date('Y-m-d') }}" onchange="this.form.submit()"
                     style="width: 100%; background: var(--s1); border: 1px solid #3A3F4C; border-radius: 10px; padding: 10px 12px; color: var(--tx); font-family: var(--font-mono)">
              <label for="cart_event_end_date" style="font-size: 13px; color: var(--mu); font-weight: 600; margin-top: 10px; display: block">Tanggal Selesai (opsional):</label>
              <input type="date" id="cart_event_end_date" name="event_end_date"
                     value="{{ old('event_end_date', request('event_end_date') ?? session('event_end_date')) }}"
                     min="{{ old('event_date', request('event_date') ?? session('event_date') ?? date('Y-m-d')) }}" onchange="this.form.submit()"
                     style="width: 100%; background: var(--s1); border: 1px solid #3A3F4C; border-radius: 10px; padding: 10px 12px; color: var(--tx); font-family: var(--font-mono)">
            </form>
            <span style="font-size: 12px; color: var(--mu)">Pilih tanggal mulai dan selesai jika acara berlangsung beberapa hari.</span>
          </div>

          <div style="border-top: 1px solid var(--line); padding-top: 18px; display: flex; align-items: baseline">
            <strong style="flex-grow: 1">Estimasi Total</strong>
            <strong class="d" style="font-size: 26px; color: var(--ac); font-family: var(--font-mono)">
              Rp {{ number_format($total, 0, ',', '.') }}
            </strong>
          </div>

          <a class="btn p" href="{{ route('checkout.index') }}" style="width: 100%; text-align: center">
            Lanjut ke Form Booking
          </a>

          <p style="font-size: 13px; color: var(--mu); text-align: center">
            Pemesanan tanpa pembayaran langsung. Negosiasi dan pembayaran DP via WhatsApp.
          </p>
        </aside>
      </div>
    @endif
  </section>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola Booking & Transaksi — Admin Vantara Production')

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px">
  <div>
    <h1 class="d" style="font-size: 36px; font-weight: 800">Kelola Booking &amp; Transaksi</h1>
    <p style="color: var(--admin-mu)">Pantau booking masuk, hasil kesepakatan WhatsApp, dan perbarui statusnya.</p>
  </div>

  <div class="card" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 16px">
    <!-- Filter Chips & Search -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center">
      <a class="chip {{ empty(request('status')) ? 'on' : '' }}" href="{{ route('admin.orders.index') }}">
        Semua · {{ $statusCounts['all'] }}
      </a>
      <a class="chip {{ request('status') === 'pending' ? 'on' : '' }}" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
        Pending · {{ $statusCounts['pending'] }}
      </a>
      <a class="chip {{ request('status') === 'dp_received' ? 'on' : '' }}" href="{{ route('admin.orders.index', ['status' => 'dp_received']) }}">
        DP Masuk · {{ $statusCounts['dp_received'] }}
      </a>
      <a class="chip {{ request('status') === 'confirmed' ? 'on' : '' }}" href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}">
        Dikonfirmasi · {{ $statusCounts['confirmed'] }}
      </a>
      <a class="chip {{ request('status') === 'completed' ? 'on' : '' }}" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">
        Selesai · {{ $statusCounts['completed'] }}
      </a>
      <a class="chip {{ request('status') === 'cancelled' ? 'on' : '' }}" href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">
        Batal · {{ $statusCounts['cancelled'] }}
      </a>

      <span style="flex-grow: 1"></span>

      <!-- Search Input -->
      <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: 8px">
        @if(request('status'))
          <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..."
               style="background: #fff; border: 1px solid #CFCBC0; border-radius: 12px; padding: 8px 14px; font-size: 14px; min-height: 40px">
        <button class="btn" type="submit" style="min-height: 40px; padding: 0 14px">Cari</button>
      </form>
    </div>

    <!-- Table of Orders -->
    <div style="overflow-x: auto">
      <table>
        <thead>
          <tr>
            <th>Kode</th>
            <th>Customer</th>
            <th>Tanggal acara</th>
            <th>Produk</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $ord)
            @php
              $statusClass = match($ord->status) {
                'pending' => 'w',
                'dp_received' => 'b',
                'confirmed' => 'a',
                'completed' => 'c',
                'cancelled' => 'r',
                default => 'w',
              };
              $statusLabel = match($ord->status) {
                'pending' => 'Pending',
                'dp_received' => 'DP Diterima',
                'confirmed' => 'Dikonfirmasi',
                'completed' => 'Selesai',
                'cancelled' => 'Batal',
                default => ucfirst($ord->status),
              };
            @endphp
            <tr>
              <td style="color: var(--admin-mu); font-family: var(--font-mono); font-weight: 600">
                <a href="{{ route('admin.orders.show', $ord->id) }}">{{ $ord->order_code }}</a>
              </td>
              <td style="font-weight: 600">
                <a href="{{ route('admin.orders.show', $ord->id) }}">{{ $ord->customer_name }}</a>
                <p style="font-weight: 400; font-size: 13px; color: var(--admin-mu)">{{ $ord->customer_phone }}</p>
              </td>
              <td style="white-space: nowrap">{{ \Carbon\Carbon::parse($ord->event_date)->translatedFormat('d M Y') }}</td>
              <td style="color: var(--admin-mu)">
                {{ $ord->orderItems->first()?->package_name ?? 'Layanan' }}
                @if($ord->orderItems->count() > 1)
                  <span style="font-size: 12px">(+{{ $ord->orderItems->count() - 1 }})</span>
                @endif
              </td>
              <td style="white-space: nowrap; font-family: var(--font-mono)">
                Rp {{ number_format($ord->total_price, 0, ',', '.') }}
              </td>
              <td>
                <span class="tg {{ $statusClass }}">{{ $statusLabel }}</span>
              </td>
              <td>
                <div x-data="{ confirming: false }" style="display: flex; gap: 6px; align-items: center">
                  <a class="btn sm" href="{{ route('admin.orders.show', $ord->id) }}">
                    Kelola →
                  </a>
                  <button type="button" x-show="!confirming" @click="confirming = true" class="btn ic dg" aria-label="Hapus pesanan" title="Hapus pesanan {{ $ord->order_code }}">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4h11M6 4V2.5h4V4M4 4l.7 9.5h6.6L12 4"/></svg>
                  </button>
                  <div x-show="confirming" style="display: flex; gap: 4px; align-items: center">
                    <form action="{{ route('admin.orders.destroy', $ord->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button class="btn sm dg" type="submit" style="background: #E02424; color: #fff; padding: 4px 8px; font-size: 12px; font-weight: 700" title="Konfirmasi hapus {{ $ord->order_code }}">
                        Hapus!
                      </button>
                    </form>
                    <button type="button" @click="confirming = false" class="btn sm" style="padding: 4px 8px; font-size: 12px" title="Batal">
                      ✕
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align: center; color: var(--admin-mu); padding: 32px">
                Tidak ada data pesanan yang sesuai filter.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($orders->hasPages())
      <div style="margin-top: 12px">
        {{ $orders->links() }}
      </div>
    @endif
  </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Detail Booking ' . $order->order_code . ' — Admin Vantara Production')

@push('styles')
<style>
@media print {
  body.admin aside.side,
  body.admin .no-print,
  body.admin .btn,
  body.admin nav,
  body.admin footer {
    display: none !important;
  }
  body.admin main {
    padding: 0 !important;
  }
  body.admin {
    background: #fff !important;
    color: #000 !important;
  }
  #printable-invoice {
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    width: 100% !important;
  }
  .card:not(#printable-invoice) {
    display: none !important;
  }
}
</style>
@endpush

@section('content')
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
    'dp_received' => 'DP Diterima',
    'confirmed' => 'Dikonfirmasi',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan',
    default => ucfirst($order->status),
  };
  $phoneDigits = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->customer_phone));
  $operationalCostsTotal = $order->operationalCosts->sum('amount');
  $netRevenue = max(0, $order->total_price - $operationalCostsTotal);
@endphp

<div style="display: flex; flex-direction: column; gap: 28px">
  <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px">
    <div>
      <div style="display: flex; align-items: center; gap: 12px">
        <h1 class="d" style="font-size: 32px; font-weight: 800">{{ $order->order_code }}</h1>
        <span class="tg {{ $statusClass }}">{{ $statusLabel }}</span>
      </div>
      <p style="color: var(--admin-mu)">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap">
      <a class="btn" href="{{ route('admin.orders.index') }}">← Kembali ke Daftar</a>
      <a class="btn" href="#printable-invoice" style="background: #fff; border-color: #CFCBC0; color: var(--admin-tx); display: inline-flex; align-items: center; gap: 8px">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 2h8v12l-2-1-2 1-2-1-2 1zM6 6h4M6 9h4"/></svg>
        Lihat Invoice
      </a>
      <button type="button" onclick="window.print()" class="btn" style="background: #fff; border-color: #CFCBC0; color: var(--admin-tx); display: inline-flex; align-items: center; gap: 8px" title="Cetak Lembar Invoice">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5V2h8v3M4 12H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-2M4 9h8v5H4z"/></svg>
        Cetak Invoice
      </button>
      <a class="btn wa" href="https://wa.me/{{ $phoneDigits }}?text=Halo%20{{ urlencode($order->customer_name) }},%20kami%20dari%20Vantara%20Production%20terkait%20pesanan%20{{ $order->order_code }}." target="_blank">
        Chat via WhatsApp
      </a>
      <div x-data="{ confirming: false }" style="display: inline-flex; align-items: center">
        <button type="button" x-show="!confirming" @click="confirming = true" class="btn dg" title="Hapus pesanan ini">
          <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4h11M6 4V2.5h4V4M4 4l.7 9.5h6.6L12 4"/></svg>
          Hapus Pesanan
        </button>
        <div x-show="confirming" style="display: flex; gap: 8px; align-items: center">
          <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn dg" type="submit" style="background: #E02424; color: #fff; border-color: #C81E1E">
              Ya, Hapus Sekarang
            </button>
          </form>
          <button type="button" @click="confirming = false" class="btn">Batal</button>
        </div>
      </div>
    </div>
  </div>

  <div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-start">
    <!-- Left: Order Details & Items Card -->
    <div style="flex: 2 1 480px; display: flex; flex-direction: column; gap: 24px">
      <div class="card" style="display: flex; flex-direction: column; gap: 18px">
        <h2 class="d" style="font-size: 22px">Data Pemesan &amp; Acara</h2>
        <div style="display: flex; flex-direction: column; gap: 12px">
          <div class="kv"><span>Customer</span><strong style="color: var(--admin-tx)">{{ $order->customer_name }}</strong></div>
          <div class="kv"><span>Telepon / WA</span><span>{{ $order->customer_phone }}</span></div>
          <div class="kv"><span>Email</span><span>{{ $order->customer_email ?? '-' }}</span></div>
          <div class="kv"><span>Tanggal Acara</span><strong>{{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('l, d F Y') }}</strong></div>
          <div class="kv"><span>Lokasi / Alamat</span><span>{{ $order->event_address }}</span></div>
          <div class="kv"><span>Catatan Klien</span><span>{{ $order->notes ?? 'Tidak ada catatan khusus.' }}</span></div>
        </div>
      </div>

      <div class="card" style="display: flex; flex-direction: column; gap: 16px">
        <h2 class="d" style="font-size: 22px">Rincian Paket &amp; Peralatan Disewa</h2>
        <div style="overflow-x: auto">
          <table>
            <thead>
              <tr>
                <th>Paket Layanan</th>
                <th>Durasi</th>
                <th>Harga / hari</th>
                <th style="text-align: right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->orderItems as $item)
                <tr>
                  <td style="font-weight: 600">{{ $item->package_name }}</td>
                  <td>{{ $item->quantity }} Hari</td>
                  <td style="font-family: var(--font-mono)">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                  <td style="text-align: right; font-family: var(--font-mono); font-weight: 700">Rp {{ number_format($order->negotiated_subtotals[$item->id] ?? $item->subtotal, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="align-self: flex-end; width: 320px; display: flex; flex-direction: column; gap: 8px; border-top: 1px solid var(--admin-line); padding-top: 14px">
          <div style="display: flex; color: var(--admin-mu)"><span style="flex-grow: 1">Total Nilai Sewa</span><span style="font-family: var(--font-mono)">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
          <div style="display: flex; color: #17653A"><span style="flex-grow: 1">DP Diterima</span><span style="font-family: var(--font-mono)">- Rp {{ number_format($order->down_payment, 0, ',', '.') }}</span></div>
          <div style="display: flex; align-items: baseline; border-top: 2px solid var(--admin-tx); padding-top: 8px">
            <strong style="flex-grow: 1">Sisa Tagihan</strong>
            <strong class="d" style="font-size: 22px; font-family: var(--font-mono)">Rp {{ number_format(max(0, $order->total_price - $order->down_payment), 0, ',', '.') }}</strong>
          </div>
        </div>
      </div>

      <!-- Card 3: Lembar Invoice Resmi Pesanan -->
      <div id="printable-invoice" class="paper" style="border: 1px solid var(--admin-line); box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 24px">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px">
          <div>
            <span class="d" style="font-weight: 800; font-size: 20px; letter-spacing: .06em; color: #17181C">VANTARA PRODUCTION</span>
            <p style="color: #6A6D77; font-size: 13px; margin-top: 4px; line-height: 1.4">
              Jl. Merapi Ujung No.31, Kel. Panorama,<br>Kec. Singaran Pati, Kota Bengkulu · 0822-8242-2317
            </p>
          </div>
          <div style="text-align: right">
            <h2 class="d" style="font-size: 32px; font-weight: 800; letter-spacing: .02em; color: #17181C">INVOICE</h2>
            <p style="color: #17181C; font-size: 15px; font-weight: 700; font-family: var(--font-mono)">{{ $order->order_code }}</p>
            <p style="color: #6A6D77; font-size: 13px">Diterbitkan {{ $order->created_at->translatedFormat('d F Y') }}</p>
          </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 24px; border-top: 1px solid #E4E1D9; border-bottom: 1px solid #E4E1D9; padding: 18px 0">
          <div style="flex: 1 1 180px">
            <span class="lb">Ditagihkan Kepada</span>
            <p style="margin-top: 4px; font-weight: 700; color: #17181C">{{ $order->customer_name }}</p>
            <p style="color: #6A6D77; font-size: 13px">{{ $order->customer_phone }}</p>
            @if($order->customer_email)
              <p style="color: #6A6D77; font-size: 13px">{{ $order->customer_email }}</p>
            @endif
          </div>
          <div style="flex: 1 1 160px">
            <span class="lb">Tanggal Acara</span>
            <p style="margin-top: 4px; font-weight: 700; color: #17181C">{{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('l, d F Y') }}</p>
            <p style="color: #6A6D77; font-size: 13px">{{ $order->event_address }}</p>
          </div>
          <div style="flex: 1 1 140px">
            <span class="lb">Status Booking</span>
            <p style="margin-top: 4px">
              <span class="tg {{ $statusClass }}">{{ $statusLabel }}</span>
            </p>
          </div>
        </div>

        <div style="overflow-x: auto">
          <table>
            <thead>
              <tr>
                <th>Item Layanan</th>
                <th>Durasi</th>
                <th>Harga Satuan</th>
                <th style="text-align: right">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->orderItems as $item)
                <tr>
                  <td style="font-weight: 600; color: #17181C">{{ $item->package_name }}</td>
                  <td style="color: #6A6D77">Sewa {{ $item->quantity }} Hari</td>
                  <td style="font-family: var(--font-mono); color: #6A6D77">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                  <td style="text-align: right; font-family: var(--font-mono); font-weight: 700; color: #17181C">Rp {{ number_format($order->negotiated_subtotals[$item->id] ?? $item->subtotal, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="align-self: flex-end; width: 320px; display: flex; flex-direction: column; gap: 8px; border-top: 1px solid #E4E1D9; padding-top: 14px">
          <div style="display: flex; color: #6A6D77"><span style="flex-grow: 1">Total Nilai Sewa</span><span style="font-family: var(--font-mono)">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
          <div style="display: flex; color: #17653A"><span style="flex-grow: 1">DP Diterima (Uang Muka)</span><span style="font-family: var(--font-mono)">- Rp {{ number_format($order->down_payment, 0, ',', '.') }}</span></div>
          <div style="display: flex; align-items: baseline; border-top: 2px solid #17181C; padding-top: 8px">
            <strong style="flex-grow: 1; color: #17181C">Sisa Tagihan</strong>
            <strong class="d" style="font-size: 22px; font-family: var(--font-mono); color: #17181C">Rp {{ number_format(max(0, $order->total_price - $order->down_payment), 0, ',', '.') }}</strong>
          </div>
        </div>

        <div class="no-print" style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E4E1D9; padding-top: 16px; flex-wrap: wrap; gap: 12px">
          <span style="font-size: 13px; color: #6A6D77">Bukti invoice resmi pesanan Vantara Production Bengkulu.</span>
          <button type="button" onclick="window.print()" class="btn p sm" style="font-size: 13px">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5V2h8v3M4 12H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-2M4 9h8v5H4z"/></svg>
            Cetak Invoice Ini
          </button>
        </div>
      </div>
    </div>

    <!-- Right: Status & Transaction Management Card -->
    <div style="flex: 1 1 360px; display: flex; flex-direction: column; gap: 20px">
      <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="card" style="display: flex; flex-direction: column; gap: 18px">
        @csrf
        @method('PATCH')

        <h2 class="d" style="font-size: 22px">Perbarui Status &amp; Transaksi</h2>

        <div class="fld">
          <label style="font-weight: 700; color: var(--admin-tx); margin-bottom: 8px; display: block">Status Pesanan (5 Tahap)</label>
          <div class="seg">
            <label>
              <div style="display: flex; align-items: center; gap: 12px">
                <input type="radio" name="status" value="pending" {{ $order->status === 'pending' ? 'checked' : '' }}>
                <span>Menunggu Konfirmasi</span>
              </div>
              <span class="tg w">Pending</span>
            </label>
            <label>
              <div style="display: flex; align-items: center; gap: 12px">
                <input type="radio" name="status" value="dp_received" {{ $order->status === 'dp_received' ? 'checked' : '' }}>
                <span>DP Diterima (Uang Muka)</span>
              </div>
              <span class="tg b">DP Masuk</span>
            </label>
            <label>
              <div style="display: flex; align-items: center; gap: 12px">
                <input type="radio" name="status" value="confirmed" {{ $order->status === 'confirmed' ? 'checked' : '' }}>
                <span>Dikonfirmasi (ACC &amp; Jadwal)</span>
              </div>
              <span class="tg a">Dikonfirmasi</span>
            </label>
            <label>
              <div style="display: flex; align-items: center; gap: 12px">
                <input type="radio" name="status" value="completed" {{ $order->status === 'completed' ? 'checked' : '' }}>
                <span>Selesai (Acara Berakhir)</span>
              </div>
              <span class="tg c">Selesai</span>
            </label>
            <label>
              <div style="display: flex; align-items: center; gap: 12px">
                <input type="radio" name="status" value="cancelled" {{ $order->status === 'cancelled' ? 'checked' : '' }}>
                <span>Dibatalkan</span>
              </div>
              <span class="tg r">Batal</span>
            </label>
          </div>
        </div>

        <div class="fld">
          <label for="total_price_display">Total Kesepakatan (Rp)</label>
          <input id="total_price_display" name="total_price_display" value="{{ number_format((int) old('total_price', $order->total_price), 0, ',', '.') }}" data-money-input data-money-target="total-price-value" inputmode="numeric">
          <input id="total-price-value" type="hidden" name="total_price" value="{{ old('total_price', $order->total_price) }}">
        </div>

        <div class="fld">
          <label for="down_payment_display">Uang Muka / DP Masuk (Rp)</label>
          <input id="down_payment_display" name="down_payment_display" value="{{ number_format((int) old('down_payment', $order->down_payment), 0, ',', '.') }}" data-money-input data-money-target="down-payment-value" inputmode="numeric">
          <input id="down-payment-value" type="hidden" name="down_payment" value="{{ old('down_payment', $order->down_payment) }}">
          <span style="font-size: 12px; color: var(--admin-mu)">Mengisi DP > 0 akan otomatis menandai transaksi masuk.</span>
        </div>

        <div class="fld">
          <label for="admin_notes">Catatan Internal Admin</label>
          <textarea id="admin_notes" name="admin_notes" rows="3" placeholder="Contoh: DP ditransfer via BCA, pelunasan saat loading in.">{{ old('admin_notes', $order->admin_notes) }}</textarea>
        </div>

        <button class="btn p" type="submit" style="width: 100%">Simpan Perubahan</button>
      </form>

      <div class="card" style="display: flex; flex-direction: column; gap: 16px">
        <div>
          <h2 class="d" style="font-size: 22px">Biaya Operasional</h2>
          <p style="font-size: 13px; color: var(--admin-mu); margin-top: 6px">Data internal admin, tidak ditampilkan pada invoice customer.</p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px">
          @forelse($order->operationalCosts as $cost)
            <div style="border: 1px solid var(--admin-line); border-radius: 10px; padding: 10px; display: flex; flex-direction: column; gap: 8px">
              <form action="{{ route('admin.orders.operational-costs.update', [$order->id, $cost->id]) }}" method="POST" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap">
                @csrf
                @method('PATCH')
                <input name="name" value="{{ $cost->name }}" aria-label="Nama biaya" required style="flex: 1 1 140px; min-width: 0">
                <input name="amount_display" value="{{ number_format($cost->amount, 0, ',', '.') }}" data-money-input data-money-target="cost-amount-{{ $cost->id }}" inputmode="numeric" aria-label="Nominal biaya" required style="flex: 0 1 140px; min-width: 0">
                <input id="cost-amount-{{ $cost->id }}" type="hidden" name="amount" value="{{ $cost->amount }}">
                <button class="btn sm" type="submit">Simpan</button>
              </form>
              <form action="{{ route('admin.orders.operational-costs.destroy', [$order->id, $cost->id]) }}" method="POST" onsubmit="return confirm('Hapus biaya operasional ini?')">
                @csrf
                @method('DELETE')
                <button class="btn dg sm" type="submit">Hapus</button>
              </form>
            </div>
          @empty
            <span style="font-size: 13px; color: var(--admin-mu)">Belum ada biaya operasional.</span>
          @endforelse
        </div>

        <form action="{{ route('admin.orders.operational-costs.store', $order->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 8px; border-top: 1px solid var(--admin-line); padding-top: 14px">
          @csrf
          <strong style="font-size: 14px">Tambah biaya</strong>
          <input name="name" placeholder="Contoh: Transportasi" required>
          <input name="amount_display" data-money-input data-money-target="new-cost-amount" inputmode="numeric" placeholder="Nominal (Rp)" required>
          <input id="new-cost-amount" type="hidden" name="amount">
          <button class="btn" type="submit">Tambah Biaya</button>
        </form>

        <div style="border-top: 1px solid var(--admin-line); padding-top: 12px; display: flex; flex-direction: column; gap: 7px">
          <div style="display: flex; justify-content: space-between; color: var(--admin-mu)">
            <span>Pendapatan kotor</span>
            <span style="font-family: var(--font-mono)">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; color: #9B1C1C">
            <span>Total biaya</span>
            <span style="font-family: var(--font-mono)">- Rp {{ number_format($operationalCostsTotal, 0, ',', '.') }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-weight: 700; border-top: 1px solid var(--admin-line); padding-top: 8px">
            <span>Pendapatan bersih</span>
            <span style="font-family: var(--font-mono); color: #17653A">Rp {{ number_format($netRevenue, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>

      <!-- Danger Delete Form -->
      <div class="card" style="border-color: #F8B4B4; background: #FFF5F5; display: flex; flex-direction: column; gap: 12px">
        <div style="display: flex; align-items: center; gap: 8px">
          <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="#9B1C1C" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4h11M6 4V2.5h4V4M4 4l.7 9.5h6.6L12 4"/></svg>
          <strong style="color: #9B1C1C">Hapus Pesanan Ini?</strong>
        </div>
        <p style="font-size: 13px; color: #771D1D; line-height: 1.4">Pesanan <strong>{{ $order->order_code }}</strong> akan dihapus dari sistem. Gunakan opsi ini jika ada pesanan fiktif atau kesalahan data.</p>
        <div x-data="{ confirming: false }">
          <button type="button" x-show="!confirming" @click="confirming = true" class="btn dg" style="width: 100%; justify-content: center">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4h11M6 4V2.5h4V4M4 4l.7 9.5h6.6L12 4"/></svg>
            Hapus Pesanan Secara Permanen
          </button>
          <div x-show="confirming" style="display: flex; gap: 10px; align-items: center">
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="flex: 1">
              @csrf
              @method('DELETE')
              <button class="btn dg" type="submit" style="width: 100%; justify-content: center; background: #E02424; color: #fff; border-color: #C81E1E">
                Konfirmasi Hapus Sekarang
              </button>
            </form>
            <button type="button" @click="confirming = false" class="btn" style="flex: 1; justify-content: center">
              Batal
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-money-input]').forEach((input) => {
    const hiddenInput = document.getElementById(input.dataset.moneyTarget);

    const syncMoney = () => {
      const digits = input.value.replace(/\D/g, '');
      hiddenInput.value = digits;
      input.value = digits ? new Intl.NumberFormat('id-ID').format(Number(digits)) : '';
    };

    input.addEventListener('input', syncMoney);
  });
</script>
@endpush

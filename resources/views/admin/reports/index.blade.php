@extends('layouts.admin')

@section('title', 'Laporan Bulanan — Admin Vantara Production')

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px">
  <!-- Header & Filter Periode -->
  <div style="display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap">
    <div style="flex-grow: 1">
      <h1 class="d" style="font-size: 36px; font-weight: 800">Laporan Bulanan</h1>
      <p style="color: var(--admin-mu)">Rekap transaksi yang sudah di-ACC &amp; terlaksana.</p>
    </div>
    <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap">
      <div class="fld">
        <label for="bln">Pilih Bulan &amp; Tahun</label>
        <div style="display: flex; gap: 8px">
          <select name="month" id="bln" style="background: #fff; border: 1px solid #CFCBC0; border-radius: 12px; padding: 10px 14px; color: var(--admin-tx); min-height: 44px">
            @for ($m = 1; $m <= 12; $m++)
              <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
              </option>
            @endfor
          </select>
          <select name="year" style="background: #fff; border: 1px solid #CFCBC0; border-radius: 12px; padding: 10px 14px; color: var(--admin-tx); min-height: 44px">
            @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
              <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
        </div>
      </div>
      <button class="btn p" type="submit">Tampilkan</button>
    </form>
  </div>

  <!-- Summary Cards -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px">
    <div class="card" style="display: flex; flex-direction: column; gap: 10px">
      <span class="lbl">Periode</span>
      <strong class="d" style="font-size: 30px; line-height: 1">{{ $periodLabel }}</strong>
    </div>
    <div class="card" style="display: flex; flex-direction: column; gap: 10px">
      <span class="lbl">Total transaksi</span>
      <strong class="d" style="font-size: 30px; line-height: 1">{{ $totalOrdersCount }}</strong>
    </div>
    <div class="card" style="display: flex; flex-direction: column; gap: 10px; background: #0D0E12; color: #F2EFE9; border-color: #0D0E12">
      <span class="lbl" style="color: #A9ADB9">Pendapatan kotor</span>
      <strong class="d" style="font-size: 30px; line-height: 1; color: var(--ac); font-family: var(--font-mono)">
        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
      </strong>
    </div>
    <div class="card" style="display: flex; flex-direction: column; gap: 10px">
      <span class="lbl">Biaya operasional</span>
      <strong class="d" style="font-size: 30px; line-height: 1; color: #9B1C1C; font-family: var(--font-mono)">
        Rp {{ number_format($totalOperationalCosts, 0, ',', '.') }}
      </strong>
    </div>
    <div class="card" style="display: flex; flex-direction: column; gap: 10px; background: #E4F6EA; border-color: #A9DDBB">
      <span class="lbl">Pendapatan bersih</span>
      <strong class="d" style="font-size: 30px; line-height: 1; color: #17653A; font-family: var(--font-mono)">
        Rp {{ number_format($totalNetRevenue, 0, ',', '.') }}
      </strong>
    </div>
  </div>

  <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-start">
    <!-- Tabel Rincian Transaksi -->
    <div class="card" style="flex: 2 1 480px; padding: 20px 24px; display: flex; flex-direction: column; gap: 12px">
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap">
        <h2 class="d" style="font-size: 22px; flex-grow: 1">Rincian transaksi</h2>
        <a class="btn" href="{{ route('admin.reports.print', ['month' => $month, 'year' => $year]) }}" target="_blank">
          Cetak
        </a>
        <a class="btn p" href="{{ route('admin.reports.pdf', ['month' => $month, 'year' => $year]) }}">
          PDF
        </a>
      </div>

      <div style="overflow-x: auto">
        <table>
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>ID</th>
              <th>Customer</th>
              <th>Status</th>
              <th style="text-align: right">Kotor</th>
              <th style="text-align: right">Biaya</th>
              <th style="text-align: right">Bersih</th>
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
                <td style="white-space: nowrap">{{ \Carbon\Carbon::parse($ord->event_date)->translatedFormat('d M Y') }}</td>
                <td style="color: var(--admin-mu); font-family: var(--font-mono)">
                  <a href="{{ route('admin.orders.show', $ord->id) }}">{{ $ord->order_code }}</a>
                </td>
                <td style="font-weight: 600">{{ $ord->customer_name }}</td>
                <td><span class="tg {{ $statusClass }}">{{ $statusLabel }}</span></td>
                <td style="text-align: right; white-space: nowrap; font-family: var(--font-mono)">
                  Rp {{ number_format($ord->recognizedRevenue(), 0, ',', '.') }}
                </td>
                <td style="text-align: right; white-space: nowrap; font-family: var(--font-mono); color: #9B1C1C">
                  Rp {{ number_format($ord->operationalCosts->sum('amount'), 0, ',', '.') }}
                </td>
                <td style="text-align: right; white-space: nowrap; font-family: var(--font-mono); font-weight: 700; color: #17653A">
                  Rp {{ number_format($ord->netRecognizedRevenue(), 0, ',', '.') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align: center; color: var(--admin-mu); padding: 24px">
                  Tidak ada transaksi pada periode {{ $periodLabel }}.
                </td>
              </tr>
            @endforelse

            @if($orders->isNotEmpty())
              <tr>
                <td colspan="4" style="font-weight: 700; border-bottom: 0; padding-top: 16px">
                  Rekap transaksi ACC ({{ $orders->whereIn('status', ['dp_received', 'confirmed', 'completed'])->count() }} transaksi)
                </td>
                <td style="text-align: right; font-weight: 800; border-bottom: 0; white-space: nowrap; font-family: var(--font-mono); padding-top: 16px">
                  Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
                <td style="text-align: right; font-weight: 800; border-bottom: 0; white-space: nowrap; font-family: var(--font-mono); color: #9B1C1C; padding-top: 16px">
                  Rp {{ number_format($totalOperationalCosts, 0, ',', '.') }}
                </td>
                <td style="text-align: right; font-weight: 800; border-bottom: 0; white-space: nowrap; font-family: var(--font-mono); color: #17653A; padding-top: 16px">
                  Rp {{ number_format($totalNetRevenue, 0, ',', '.') }}
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <p style="font-size: 13px; color: var(--admin-mu)">
        Menampilkan {{ $orders->count() }} transaksi untuk periode {{ $periodLabel }}.
      </p>
    </div>

    <!-- Grafik Pendapatan Per Minggu -->
    <div class="card" style="flex: 1 1 320px; display: flex; flex-direction: column; gap: 14px">
      <h2 class="d" style="font-size: 22px">Pendapatan bersih per minggu</h2>
      @php
        $weeklyRevenues = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach($orders as $ord) {
          if(in_array($ord->status, ['dp_received', 'confirmed', 'completed'])) {
            $day = (int) \Carbon\Carbon::parse($ord->event_date)->format('j');
            $w = min(5, (int) ceil($day / 7));
            $weeklyRevenues[$w] += $ord->netRecognizedRevenue();
          }
        }
        $maxW = max(collect($weeklyRevenues)->max(), 1);
      @endphp
      <svg viewBox="0 0 340 200" width="100%" role="img" aria-label="Grafik batang pendapatan per minggu {{ $periodLabel }} dalam juta rupiah">
        <line x1="0" y1="165" x2="340" y2="165" stroke="#E4E1D9"/>
        @foreach($weeklyRevenues as $wNum => $wVal)
          @php
            $barH = $wVal > 0 ? max(10, round(($wVal / $maxW) * 110)) : 4;
            $bx = ($wNum - 1) * 64 + 20;
            $btx = ($wNum - 1) * 64 + 42;
            $by = 165 - $barH;
            $bvy = 165 - $barH - 8;
            $fill = ($wVal === max($weeklyRevenues) && $wVal > 0) ? '#F5A524' : '#D9D5CA';
            $millions = round($wVal / 1000000, 1);
          @endphp
          <rect x="{{ $bx }}" y="{{ $by }}" width="44" height="{{ $barH }}" rx="7" fill="{{ $fill }}">
            <title>Minggu {{ $wNum }}: Rp {{ number_format($wVal, 0, ',', '.') }}</title>
          </rect>
          @if($wVal > 0)
            <text x="{{ $btx }}" y="{{ $bvy }}" font-size="12" font-weight="700" text-anchor="middle" fill="#17181C">{{ $millions }}</text>
          @endif
          <text x="{{ $btx }}" y="188" font-size="12" text-anchor="middle" fill="#62656F">M{{ $wNum }}</text>
        @endforeach
      </svg>
      <p style="font-size: 13px; color: var(--admin-mu)">Dalam juta rupiah setelah biaya operasional. Hanya transaksi yang sudah di-ACC dihitung.</p>
    </div>
  </div>
</div>
@endsection

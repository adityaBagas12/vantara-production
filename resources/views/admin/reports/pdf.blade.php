<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Vantara Production — {{ $periodLabel }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.4;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .kop-surat {
            border-bottom: 3px double #1a1a1a;
            padding-bottom: 12px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }

        .kop-logo {
            display: table-cell;
            vertical-align: middle;
            width: 70%;
        }

        .kop-title {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #0c1017;
            text-transform: uppercase;
        }

        .kop-sub {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .kop-info {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 9px;
            color: #444;
        }

        .doc-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .doc-title h2 {
            font-size: 14px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .doc-title p {
            font-size: 10px;
            color: #555;
            margin: 0;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-box {
            border: 1px solid #dcdcdc;
            padding: 10px;
            background: #f9f9f9;
            text-align: center;
        }

        .summary-box .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 4px;
        }

        .summary-box .value {
            font-size: 14px;
            font-weight: bold;
            color: #0c1017;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        table.data-table th {
            background-color: #0c1017;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #0c1017;
        }

        table.data-table td {
            padding: 7px 6px;
            border: 1px solid #e2e2e2;
            vertical-align: top;
        }

        table.data-table tr:nth-child(even) td {
            background-color: #fcfcfc;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-dp { background: #cce5ff; color: #004085; }
        .badge-confirmed { background: #d4edda; color: #155724; }
        .badge-completed { background: #e2e3e5; color: #383d41; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }

        .signature-section {
            margin-top: 40px;
            width: 100%;
            display: table;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: 10px;
        }

        .signature-space {
            height: 60px;
        }

        .footer-note {
            margin-top: 30px;
            border-top: 1px solid #e0e0e0;
            padding-top: 8px;
            font-size: 8px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Vantara Production -->
    <div class="kop-surat">
        <div class="kop-logo">
            <div class="kop-title">Vantara Production</div>
            <div class="kop-sub">Rental Sound System, Lighting, Videobooth 360 & Band Wedding</div>
        </div>
        <div class="kop-info">
            <div>WhatsApp: +62 822-8242-2317</div>
            <div>Email: halo@vantara.id</div>
            <div>Website: www.vantara.id</div>
        </div>
    </div>

    <!-- Judul Dokumen Laporan -->
    <div class="doc-title">
        <h2>Laporan Rekapitulasi Penjualan & Transaksi</h2>
        <p>PERIODE: {{ strtoupper($periodLabel) }}</p>
    </div>

    <!-- Ringkasan Finansial -->
    <table class="summary-grid">
        <tr>
            <td class="summary-box" width="25%">
                <div class="label">Total Volume Acara</div>
                <div class="value">{{ $totalOrdersCount }} Order</div>
            </td>
            <td class="summary-box" width="25%">
                <div class="label">Total Omset Disetujui</div>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="25%">
                <div class="label">Total DP Diterima</div>
                <div class="value" style="color: #155724;">Rp {{ number_format($totalDp, 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="25%">
                <div class="label">Estimasi Pelunasan</div>
                <div class="value" style="color: #856404;">Rp {{ number_format($totalRemaining, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <!-- Tabel Rincian Transaksi -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Kode Transaksi</th>
                <th width="14%">Tanggal Acara</th>
                <th width="22%">Nama Pemesan & Kontak</th>
                <th width="23%">Paket Layanan</th>
                <th width="12%" class="text-right">Total (Rp)</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $idx => $ord)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $ord->order_code }}</td>
                    <td>{{ \Carbon\Carbon::parse($ord->event_date)->translatedFormat('d/m/Y') }}</td>
                    <td>
                        <div class="font-bold">{{ $ord->customer_name }}</div>
                        <div style="font-size: 8px; color: #555;">{{ $ord->customer_phone }}</div>
                    </td>
                    <td>
                        @foreach ($ord->orderItems as $item)
                            <div>• {{ $item->package_name }} ({{ $item->quantity }}x)</div>
                        @endforeach
                    </td>
                    <td class="text-right font-bold">
                        {{ number_format($ord->total_price, 0, ',', '.') }}
                        @if ($ord->down_payment > 0)
                            <div style="font-size: 8px; color: #155724;">DP: {{ number_format($ord->down_payment, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($ord->status === 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif ($ord->status === 'dp_received')
                            <span class="badge badge-dp">DP Received</span>
                        @elseif ($ord->status === 'confirmed')
                            <span class="badge badge-confirmed">Confirmed</span>
                        @elseif ($ord->status === 'completed')
                            <span class="badge badge-completed">Completed</span>
                        @else
                            <span class="badge badge-cancelled">Cancelled</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">
                        Tidak ada transaksi tercatat pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan & Konfirmasi -->
    <div class="signature-section">
        <div class="signature-box">
            <!-- Empty left column -->
        </div>
        <div class="signature-box">
            <div>Dicetak pada: {{ date('d F Y, H:i') }} WIB</div>
            <div>Penanggung Jawab Operasional,</div>
            <div class="signature-space"></div>
            <div class="font-bold"><u>Admin Vantara Production</u></div>
            <div style="font-size: 9px; color: #666;">Sistem Informasi Penyewaan Vantara</div>
        </div>
    </div>

    <div class="footer-note">
        Dokumen laporan resmi ini dihasilkan secara otomatis oleh Sistem Informasi Rental Vantara Production.
    </div>

    @if (isset($isPrint) && $isPrint)
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    @endif

</body>
</html>

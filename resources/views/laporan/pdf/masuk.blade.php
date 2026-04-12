<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1E293B;
            line-height: 1.5;
        }

        /* ── Print Bar ── */
        .print-bar {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .print-bar span {
            font-size: 11px;
            color: #64748B;
            flex: 1;
        }

        .btn-print {
            padding: 7px 18px;
            background: #2563EB;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-print:hover {
            background: #1D4ED8;
        }

        .btn-close {
            padding: 7px 14px;
            background: #fff;
            color: #64748B;
            border: 1px solid #CBD5E1;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-close:hover {
            background: #F1F5F9;
        }

        @media print {
            .print-bar {
                display: none;
            }

            body {
                font-size: 10px;
            }
        }

        /* ── Header ── */
        .header {
            border-bottom: 2px solid #2563EB;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1E293B;
        }

        .company-sub {
            font-size: 10px;
            color: #64748B;
            margin-top: 2px;
        }

        .report-title {
            text-align: right;
        }

        .report-title h2 {
            font-size: 14px;
            font-weight: bold;
            color: #2563EB;
            margin-bottom: 2px;
        }

        .report-title p {
            font-size: 10px;
            color: #64748B;
        }

        /* ── Info Box ── */
        .info-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            display: flex;
            gap: 32px;
        }

        .info-item label {
            font-size: 9px;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .info-item span {
            font-size: 11px;
            font-weight: bold;
            color: #1E293B;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        thead tr {
            background: #2563EB;
            color: #fff;
        }

        thead th {
            padding: 8px 10px;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
            letter-spacing: 0.3px;
        }

        tbody tr:nth-child(even) {
            background: #F8FAFC;
        }

        tbody tr:nth-child(odd) {
            background: #FFFFFF;
        }

        tbody td {
            padding: 7px 10px;
            font-size: 10.5px;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: top;
        }

        tbody td.mono {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 10px;
            color: #64748B;
        }

        /* ── Tfoot / Total ── */
        tfoot tr {
            background: #EFF6FF;
            border-top: 2px solid #BFDBFE;
        }

        tfoot td {
            padding: 8px 10px;
            font-size: 11px;
            font-weight: bold;
        }

        /* ── Footer ── */
        .footer {
            border-top: 1px solid #E2E8F0;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #94A3B8;
        }

        .text-green {
            color: #059669;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Laporan Stok Masuk - CV Santri Abadi</title>
</head>

<body>

    <div class="print-bar">
        <span>Pratinjau Laporan Stok Masuk — Klik <strong>Print / Save PDF</strong> untuk mencetak atau menyimpan
            sebagai PDF.</span>
        <button class="btn-print" onclick="window.print()">🖨️ Print / Save PDF</button>
    </div>

    <div class="header">
        <div class="header-top">
            <div>
                <div class="company-name">CV Santri Abadi Indonesia</div>
                <div class="company-sub">Sedayu, RT.19/RW.03, Kentang, Kalangan, Boyolali, Jawa Tengah 57385</div>
            </div>
            <div class="report-title">
                <h2>Laporan Stok Masuk</h2>
                <p>Dicetak: {{ now()->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="info-box">
        <div class="info-item">
            <label>Periode</label>
            <span>{{ $periode }}</span>
        </div>
        <div class="info-item">
            <label>Total Transaksi</label>
            <span>{{ $data->count() }}</span>
        </div>
        <div class="info-item">
            <label>Total Kuantitas</label>
            <span>{{ number_format($totalJumlah, 2, ',', '.') }} kg</span>
        </div>
        <div class="info-item">
            <label>Total Nilai</label>
            <span>Rp {{ number_format($data->sum(fn($i) => $i->jumlah * $i->harga_beli), 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:110px;">No. Transaksi</th>
                <th>Jenis Beras</th>
                <th>Supplier</th>
                <th style="width:70px; text-align:right;">Jumlah (kg)</th>
                <th style="width:80px; text-align:right;">Harga/Kg</th>
                <th style="width:90px; text-align:right;">Total Nilai</th>
                <th style="width:75px;">Tgl Masuk</th>
                <th style="width:70px;">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="mono">{{ $item->no_transaksi }}</td>
                    <td>
                        <strong>{{ $item->jenisBeras->nama_beras }}</strong><br>
                        <span style="font-size:9px;color:#94A3B8;">{{ $item->jenisBeras->kode_beras }}</span>
                    </td>
                    <td>{{ $item->supplier->nama_supplier }}</td>
                    <td class="text-right text-green">{{ number_format($item->jumlah, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
                    <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                    <td style="font-size:9.5px;">{{ $item->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;color:#64748B;">TOTAL:</td>
                <td class="text-right text-green">{{ number_format($totalJumlah, 2, ',', '.') }}</td>
                <td></td>
                <td class="text-right">Rp
                    {{ number_format($data->sum(fn($i) => $i->jumlah * $i->harga_beli), 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <span>SiPadi — Sistem Informasi Persediaan Beras CV Santri Abadi Indonesia</span>
        <span>Laporan ini digenerate otomatis oleh sistem pada {{ now()->format('d M Y H:i:s') }}</span>
    </div>

</body>

</html>

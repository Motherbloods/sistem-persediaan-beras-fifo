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
            background: #FFF5F5;
            border: 1px solid #FECACA;
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
            background: #DC2626;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-print:hover {
            background: #B91C1C;
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

        .header {
            border-bottom: 2px solid #DC2626;
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
            color: #DC2626;
            margin-bottom: 2px;
        }

        .report-title p {
            font-size: 10px;
            color: #64748B;
        }

        .info-box {
            background: #FFF7ED;
            border: 1px solid #FED7AA;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        thead tr {
            background: #DC2626;
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

        tfoot tr {
            background: #FEF2F2;
            border-top: 2px solid #FECACA;
        }

        tfoot td {
            padding: 8px 10px;
            font-size: 11px;
            font-weight: bold;
        }

        .footer {
            border-top: 1px solid #E2E8F0;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #94A3B8;
        }

        .text-red {
            color: #DC2626;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }
    </style>
    <title>Laporan Stok Keluar - CV Santri Abadi</title>
</head>

<body>

    <div class="print-bar">
        <span>Pratinjau Laporan Stok Keluar — Klik <strong>Print / Save PDF</strong> untuk mencetak atau menyimpan
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
                <h2>Laporan Stok Keluar</h2>
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
            <label>Total Distribusi</label>
            <span>{{ $data->count() }}</span>
        </div>
        <div class="info-item">
            <label>Total Keluar</label>
            <span>{{ number_format($totalJumlah, 2, ',', '.') }} kg</span>
        </div>
        <div class="info-item">
            <label>Metode</label>
            <span>FIFO Otomatis</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:110px;">No. Transaksi</th>
                <th>Jenis Beras</th>
                <th style="width:75px; text-align:right;">Jumlah (kg)</th>
                <th>Tujuan Distribusi</th>
                <th style="width:75px;">Tgl Keluar</th>
                <th style="width:75px;">Petugas</th>
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
                    <td class="text-right text-red">{{ number_format($item->jumlah, 2, ',', '.') }}</td>
                    <td>{{ $item->tujuan_distribusi ?? '-' }}</td>
                    <td>{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
                    <td style="font-size:9.5px;">{{ $item->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;color:#64748B;">TOTAL KELUAR:</td>
                <td class="text-right text-red">{{ number_format($totalJumlah, 2, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    <div
        style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:4px;padding:8px 12px;margin-bottom:12px;font-size:10px;color:#1E40AF;">
        <strong>Catatan:</strong> Pengurangan stok dilakukan secara otomatis menggunakan metode
        <strong>FIFO (First In, First Out)</strong> — batch yang pertama masuk ke gudang
        dikeluarkan terlebih dahulu.
    </div>

    <div class="footer">
        <span>SiPadi — Sistem Informasi Persediaan Beras CV Santri Abadi Indonesia</span>
        <span>Laporan ini digenerate otomatis oleh sistem pada {{ now()->format('d M Y H:i:s') }}</span>
    </div>

</body>

</html>

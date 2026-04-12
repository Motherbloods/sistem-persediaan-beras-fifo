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
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
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
            background: #059669;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-print:hover {
            background: #047857;
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
            border-bottom: 2px solid #059669;
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
            color: #059669;
            margin-bottom: 2px;
        }

        .report-title p {
            font-size: 10px;
            color: #64748B;
        }

        .info-box {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
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
            background: #059669;
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
        }

        tbody td.mono {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 10px;
            color: #64748B;
        }

        tfoot tr {
            background: #F0FDF4;
            border-top: 2px solid #BBF7D0;
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

        .badge-aman {
            background: #DCFCE7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-menipis {
            background: #FEF9C3;
            color: #92400E;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-habis {
            background: #FEE2E2;
            color: #991B1B;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .text-green {
            color: #059669;
            font-weight: bold;
        }

        .text-orange {
            color: #D97706;
            font-weight: bold;
        }

        .text-red {
            color: #DC2626;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
    <title>Laporan Persediaan - {{ $periode }}</title>
</head>

<body>

    <div class="print-bar">
        <span>Pratinjau Laporan Persediaan Beras — Klik <strong>Print / Save PDF</strong> untuk mencetak atau menyimpan
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
                <h2>Laporan Persediaan Beras</h2>
                <p>Dicetak: {{ now()->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    @php
        $totalStok = $data->sum('stok_tersedia');
        $totalAman = $data->filter(fn($b) => $b->status_stok === 'aman')->count();
        $totalMenipis = $data->filter(fn($b) => $b->status_stok === 'menipis')->count();
        $totalHabis = $data->filter(fn($b) => $b->status_stok === 'habis')->count();
    @endphp

    <div class="info-box">
        <div class="info-item">
            <label>Periode Data</label>
            <span>{{ $periode }}</span>
        </div>
        <div class="info-item">
            <label>Total Jenis Beras</label>
            <span>{{ $data->count() }} jenis</span>
        </div>
        <div class="info-item">
            <label>Total Stok Tersedia</label>
            <span>{{ number_format($totalStok, 2, ',', '.') }} kg</span>
        </div>
        <div class="info-item">
            <label>Stok Aman / Menipis / Habis</label>
            <span>{{ $totalAman }} / {{ $totalMenipis }} / {{ $totalHabis }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:65px;">Kode</th>
                <th>Nama Beras</th>
                <th style="width:50px;">Satuan</th>
                <th style="width:90px; text-align:right;">Stok Tersedia</th>
                <th style="width:80px; text-align:right;">Stok Minimum</th>
                <th style="width:90px; text-align:right;">Selisih</th>
                <th style="width:65px; text-align:center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $beras)
                @php
                    $stok = $beras->stok_tersedia;
                    $min = $beras->stok_minimum;
                    $selisih = $stok - $min;
                    $status = $beras->status_stok;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="mono">{{ $beras->kode_beras }}</td>
                    <td><strong>{{ $beras->nama_beras }}</strong></td>
                    <td>{{ $beras->satuan }}</td>
                    <td
                        class="text-right {{ $status === 'aman' ? 'text-green' : ($status === 'menipis' ? 'text-orange' : 'text-red') }}">
                        {{ number_format($stok, 2, ',', '.') }}
                    </td>
                    <td class="text-right" style="color:#64748B;">{{ number_format($min, 2, ',', '.') }}</td>
                    <td class="text-right {{ $selisih >= 0 ? 'text-green' : 'text-red' }}">
                        {{ $selisih >= 0 ? '+' : '' }}{{ number_format($selisih, 2, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if ($status === 'aman')
                            <span class="badge-aman">Aman</span>
                        @elseif($status === 'menipis')
                            <span class="badge-menipis">Menipis</span>
                        @else
                            <span class="badge-habis">Habis</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;color:#64748B;">TOTAL STOK:</td>
                <td class="text-right text-green">{{ number_format($totalStok, 2, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    <div style="display:flex;gap:16px;margin-bottom:12px;font-size:10px;">
        <div><span class="badge-aman">Aman</span> = Stok di atas batas minimum</div>
        <div><span class="badge-menipis">Menipis</span> = Stok di bawah/sama dengan batas minimum</div>
        <div><span class="badge-habis">Habis</span> = Tidak ada stok tersedia</div>
    </div>

    <div class="footer">
        <span>SiPadi — Sistem Informasi Persediaan Beras CV Santri Abadi Indonesia</span>
        <span>Laporan ini digenerate otomatis oleh sistem pada {{ now()->format('d M Y H:i:s') }}</span>
    </div>

</body>

</html>

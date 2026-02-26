<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $gajihPokok->branchUser->user->name }}</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #000;
            background: #fff;
            padding: 12mm 14mm;
        }

        /* ===== HEADER ===== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
            padding: 4px 6px;
        }

        .logo-cell {
            width: 75px;
            text-align: center;
        }

        .logo-circle {
            width: 65px;
            height: 65px;
            border: 2.5px solid #1a7a1a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0 auto;
        }

        .logo-circle span {
            font-size: 7.5pt;
            color: #1a7a1a;
            font-weight: bold;
            text-align: center;
            line-height: 1.3;
        }

        .company-info {
            text-align: center;
            padding: 0 10px;
        }

        .company-name {
            font-size: 15pt;
            font-weight: bold;
            color: #1a7a1a;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .company-subtitle {
            font-size: 8pt;
            color: #1a7a1a;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .company-address {
            font-size: 7.5pt;
            color: #444;
            margin-top: 2px;
            line-height: 1.5;
        }

        /* ===== SEPARATOR ===== */
        .separator {
            border: none;
            border-top: 2.5px solid #1a7a1a;
            margin: 8px 0;
        }

        .separator-thin {
            border: none;
            border-top: 1px solid #ccc;
            margin: 6px 0;
        }

        /* ===== SLIP TITLE ===== */
        .slip-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1a7a1a;
            margin: 8px 0 10px 0;
            text-transform: uppercase;
        }

        /* ===== EMPLOYEE INFO ===== */
        .emp-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }

        .emp-table td {
            padding: 3px 6px;
        }

        .emp-label {
            font-weight: bold;
            width: 140px;
            color: #333;
        }

        .emp-colon {
            width: 12px;
            text-align: center;
        }

        .emp-value {
            font-size: 12pt;
            font-weight: bold;
        }

        .emp-name-box {
            font-size: 12pt;
            font-weight: bold;
            border: 1.5px solid #999;
            padding: 3px 10px;
            display: inline-block;
            min-width: 220px;
            background: #f9fff9;
        }

        .emp-value.jabatan,
        .emp-value.golongan {
            font-size: 10pt;
            font-weight: bold;
        }

        /* ===== PERIODE BOX ===== */
        .periode-box {
            float: right;
            border: 1.5px solid #1a7a1a;
            background: #f0fff0;
            padding: 4px 12px;
            font-size: 9pt;
            font-weight: bold;
            color: #1a7a1a;
            border-radius: 4px;
            margin-top: -4px;
        }

        /* ===== MAIN SALARY TABLE ===== */
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 9pt;
        }

        .salary-table td,
        .salary-table th {
            border: 1px solid #bbb;
            padding: 5px 10px;
        }

        .salary-table .col-label {
            width: 34%;
        }

        .salary-table .col-a {
            width: 16%;
        }

        .salary-table .col-b {
            width: 14%;
        }

        .salary-table .col-c {
            width: 10%;
        }

        .salary-table .col-total {
            width: 18%;
        }

        .row-label {
            font-weight: normal;
        }

        .row-label.bold {
            font-weight: bold;
        }

        .num {
            text-align: right;
        }

        .row-green {
            background-color: #92d050;
            font-weight: bold;
        }

        .row-yellow {
            background-color: #ffff00;
            font-weight: bold;
            font-size: 10pt;
        }

        .row-grand {
            background-color: #92d050;
            font-weight: bold;
            font-size: 10pt;
        }

        .row-header {
            background-color: #1a7a1a;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .text-danger {
            color: #cc0000;
        }

        /* ===== SIGNATURE AREA ===== */
        .signature-wrap {
            overflow: hidden;
            margin-top: 12px;
            margin-bottom: 4px;
        }

        .signature-area {
            float: right;
            text-align: center;
            width: 240px;
        }

        .signature-area p {
            font-size: 9pt;
            margin-bottom: 55px;
        }

        .sig-name {
            font-weight: bold;
            font-size: 9pt;
            border-top: 1.5px solid #000;
            padding-top: 3px;
        }

        .sig-title {
            font-size: 9pt;
            color: #444;
        }

        /* ===== DETAIL TABLE ===== */
        .detail-section-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #1a7a1a;
            margin: 14px 0 4px 0;
            letter-spacing: 0.5px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            clear: both;
        }

        .detail-table th {
            background-color: #1a7a1a;
            color: #fff;
            font-weight: bold;
            padding: 6px 8px;
            border: 1px solid #1a7a1a;
            text-align: left;
        }

        .detail-table td {
            border: 1px solid #ccc;
            padding: 4px 8px;
        }

        .detail-table tr:nth-child(even) td {
            background: #f5faf5;
        }

        .detail-table .neg-cell {
            color: #cc0000;
            text-align: right;
            font-weight: bold;
        }

        .detail-table .pos-cell {
            text-align: right;
            color: #1a7a1a;
            font-weight: bold;
        }

        .detail-table .text-right {
            text-align: right;
        }

        /* ===== PRINT SPECIFIC ===== */
        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .detail-table,
            .salary-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    @php
    $bulanId = [
    'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
    'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
    'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
    ];
    $periodeArr = explode(' ', $gajihPokok->periode);
    $periodeBulanNum = $bulanId[$periodeArr[0]] ?? 1;
    $periodeTahun = $periodeArr[1] ?? date('Y');
    $periodeCarbon = \Carbon\Carbon::createFromDate($periodeTahun, $periodeBulanNum, 1);
    $periodeTanggal = $periodeCarbon->format('00/m/Y');
    @endphp

    <!-- ===== HEADER ===== -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <div class="logo-circle">
                    <span>INTIRA<br>SEJAHTERA</span>
                </div>
            </td>
            <td class="company-info">
                <div class="company-name">PT SOLUSI INTIRA SEJAHTERA</div>
                <div class="company-subtitle">SLIP GAJI KARYAWAN</div>
                <div class="company-address">
                    Head Office Jl. Komplek Agraria I No.045 RT.025 RW.003, Desa/Kelurahan Telaga Biru,<br>
                    Kec. Banjarmasin Barat, Kota Banjarmasin, Provinsi Kalimantan Selatan &nbsp;|&nbsp; Kode Pos: 70119
                </div>
            </td>
            <td class="logo-cell">
                <div class="logo-circle">
                    <span>INTIRA<br>SEJAHTERA</span>
                </div>
            </td>
        </tr>
    </table>

    <hr class="separator">

    <!-- ===== INFO KARYAWAN ===== -->
    <div style="overflow:hidden; margin-bottom: 8px;">
        <div class="periode-box">
            PERIODE &nbsp;:&nbsp; {{ strtoupper($gajihPokok->periode) }}
        </div>
    </div>

    <table class="emp-table">
        <tr>
            <td class="emp-label">NAMA KARYAWAN</td>
            <td class="emp-colon">:</td>
            <td>
                <span class="emp-name-box">{{ $gajihPokok->branchUser->user->name }}</span>
            </td>
        </tr>
        <tr>
            <td class="emp-label">JABATAN</td>
            <td class="emp-colon">:</td>
            <td class="emp-value jabatan">
                @foreach($gajihPokok->branchUser->user->roles as $role)
                {{ strtoupper($role->name) }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
                @if($gajihPokok->branchUser->is_manager) (AREA MANAGER)@endif
            </td>
        </tr>
        <tr>
            <td class="emp-label">GOLONGAN</td>
            <td class="emp-colon">:</td>
            <td class="emp-value golongan">{{ $gajihPokok->golongan ?? 'N/A' }}</td>
        </tr>
    </table>

    <hr class="separator-thin">

    <!-- ===== TABEL GAJI UTAMA ===== -->
    <table class="salary-table">
        <thead>
            <tr class="row-header">
                <td class="col-label">KOMPONEN GAJI</td>
                <td class="col-a num">NILAI / HARI</td>
                <td class="col-b num">PER HARI</td>
                <td class="col-c num"></td>
                <td class="col-total num">JUMLAH (Rp)</td>
            </tr>
        </thead>
        <tbody>
            <!-- Gaji Pokok -->
            <tr>
                <td class="row-label">GAJI POKOK</td>
                <td class="num">{{ number_format($gajihPokok->amount, 0, ',', '.') }}</td>
                <td class="num"></td>
                <td></td>
                <td class="num">{{ number_format($gajihPokok->amount, 0, ',', '.') }}</td>
            </tr>
            <!-- Makan -->
            <tr>
                <td class="row-label">T. MAKAN</td>
                <td class="num">{{ $gajihPokok->hari_kerja }} HARI x</td>
                <td class="num">{{ number_format($gajihPokok->tunjangan_makan / $gajihPokok->hari_kerja, 0, ',', '.') }}
                </td>
                <td></td>
                <td class="num">{{ number_format($gajihPokok->tunjangan_makan, 0, ',', '.') }}</td>
            </tr>
            <!-- Transport -->
            <tr>
                <td class="row-label">T. TRANSPORT</td>
                <td class="num">{{ $gajihPokok->hari_kerja }} HARI x</td>
                <td class="num">{{ number_format($gajihPokok->tunjangan_transportasi / $gajihPokok->hari_kerja, 0, ',',
                    '.') }}</td>
                <td></td>
                <td class="num">{{ number_format($gajihPokok->tunjangan_transportasi, 0, ',', '.') }}</td>
            </tr>
            <!-- Komunikasi -->
            <tr>
                <td class="row-label">T. KOMUNIKASI</td>
                <td class="num">{{ number_format($gajihPokok->tunjangan_komunikasi, 0, ',', '.') }}</td>
                <td class="num"></td>
                <td></td>
                <td class="num">{{ number_format($gajihPokok->tunjangan_komunikasi, 0, ',', '.') }}</td>
            </tr>
            <!-- T. Jabatan -->
            <tr >
                <td class="row-label bold">T. JABATAN</td>
                <td class="num">{{ number_format($gajihPokok->tunjangan_jabatan, 0, ',', '.') }}</td>
                <td class="num"></td>
                <td></td>
                <td class="num">{{ number_format($gajihPokok->tunjangan_jabatan, 0, ',', '.') }}</td>
            </tr>
            <!-- Bonus Revenue -->
            <tr>
                <td class="row-label bold">BONUS REVENUE</td>
                <td class="num">{{ number_format($gajihPokok->bonus_revenue / ($gajihPokok->persentase_revenue / 100),
                    0, ',', '.') }}</td>
                <td class="num">{{ $gajihPokok->persentase_revenue }}%</td>
                <td></td>
                <td class="num">{{ number_format($gajihPokok->bonus_revenue, 0, ',', '.') }}</td>
            </tr>

            {{-- tambahkan potongan dan tambahan di sini --}}
           
                 <tr>
                <td class="row-label">TAMBAHAN</td>
                <td class="num">{{ number_format($totalTambahan, 0, ',', '.') }}</td>
                <td class="num"></td>
                <td></td>
                <td class="num">{{ number_format($totalTambahan, 0, ',', '.') }}</td>
            </tr>
           
            <tr>
                <td colspan="5"
                    style="padding:2px; background:#f0f0f0; font-size:7.5pt; color:#666; font-style:italic; padding-left:10px;">
                    — POTONGAN —</td>
            </tr>

            <!-- Potongan BPJS Kesehatan -->
            <tr>
                <td class="row-label">POTONGAN BPJS KESEHATAN</td>
                <td class="num text-danger">-</td>
                <td class="num text-danger">{{ number_format($gajihPokok->ptg_bpjs_kesehatan, 0, ',', '.') }}</td>
                <td></td>
                <td class="num text-danger">- {{ number_format($gajihPokok->ptg_bpjs_kesehatan, 0, ',', '.') }}</td>
            </tr>
            <!-- Potongan BPJS Ketenagakerjaan -->
            <tr>
                <td class="row-label">POTONGAN BPJS KETENAGAKERJAAN</td>
                <td class="num text-danger">-</td>
                <td class="num text-danger">{{ number_format($gajihPokok->ptg_bpjs_ketenagakerjaan, 0, ',', '.') }}</td>
                <td></td>
                <td class="num text-danger">- {{ number_format($gajihPokok->ptg_bpjs_ketenagakerjaan, 0, ',', '.') }}
                </td>
            </tr>
            <!-- Simpanan -->
            <tr>
                <td class="row-label">SIMPANAN</td>
                <td class="num text-danger">-</td>
                <td class="num text-danger">{{ number_format($gajihPokok->simpanan, 0, ',', '.') }}</td>
                <td></td>
                <td class="num text-danger">- {{ number_format($gajihPokok->simpanan, 0, ',', '.') }}</td>
            </tr>
            <!-- KPI -->
            <tr>
                <td class="row-label">KPI</td>
                <td class="num text-danger">{{ $gajihPokok->persentase_kpi }}%</td>
                <td class="num text-danger">{{ number_format($gajihPokok->bonus_kpi, 0, ',', '.') }}</td>
                <td class="num text-danger">-</td>
                <td class="num text-danger">- {{ number_format($gajihPokok->bonus_kpi - ($gajihPokok->bonus_kpi *
                    ($gajihPokok->persentase_kpi / 100)), 0, ',', '.') }}</td>
            </tr>
             <tr>
                <td class="row-label">POTONGAN</td>
                <td class="num text-danger">-</td>
                <td class="num text-danger">{{ number_format($totalPotongan, 0, ',', '.') }}</td>
                <td></td>
                <td class="num text-danger">- {{ number_format($totalPotongan, 0, ',', '.') }}</td>
            </tr>
         
            <!-- Grand Total -->
            <tr class="row-grand">
                <td class="row-label bold" colspan="4">GRAND TOTAL</td>
                <td class="num">Rp {{ number_format($gajiBersih, 0, ',', '.') }}</td>
            </tr>
            <!-- Take Home Pay -->
            <tr class="row-yellow">
                <td class="row-label bold" colspan="4">TAKE HOME PAY</td>
                <td class="num">Rp {{ number_format($gajiBersih, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- ===== TANDA TANGAN ===== -->
    <div class="clearfix" style="margin-top:10px;">
        <div class="signature-area">
            <p>Banjarmasin, {{ $tanggalCetak }}</p>
            <div class="sig-name">AULIA USPIHANI YUSRAN</div>
            <div class="sig-title">HO SDM</div>
        </div>
    </div>

    <div style="clear:both; margin-top: 10px;"></div>

    <div style="clear:both; margin-top: 8px;"></div>

    <!-- ===== TABEL DETAIL BREAKDOWN ===== -->
    @if(count($potongans) > 0 || count($dataPotonganTerlambat) > 0)
    <hr class="separator">
    <div class="detail-section-title">RINCIAN POTONGAN &amp; TAMBAHAN</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width:4%; text-align:center;">NO</th>
                <th style="width:16%;">NAMA</th>
                <th style="width:22%;">KATEGORI</th>
                <th style="width:14%;" class="text-right">TAMBAHAN (Rp)</th>
                <th style="width:14%;" class="text-right">POTONGAN (Rp)</th>
                <th style="width:18%;">KETERANGAN</th>
                <th style="width:12%;">TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($potongans as $i => $item)
            <tr>
                <td style="text-align:center;">{{ $i + 1 }}</td>
                <td>{{ $gajihPokok->branchUser->user->name }}</td>
                <td>{{ $item->divisi }}</td>
                <td class="pos-cell">{{ $item->jenis === 'tambahan' ? number_format($item->amount, 0, ',', '.') : '-' }}
                </td>
                <td class="neg-cell">{{ $item->jenis === 'potongan' ? number_format($item->amount, 0, ',', '.') : '-' }}
                </td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->tanggal->format('d/m/Y') }}</td>
            </tr>
            @endforeach
            @foreach($dataPotonganTerlambat as $j => $item)
            <tr>
                <td style="text-align:center;">{{ count($potongans) + $j + 1 }}</td>
                <td>{{ $gajihPokok->branchUser->user->name }}</td>
                <td>{{ $item['keterangan'] }}</td>
                <td class="pos-cell">-</td>
                <td class="neg-cell">{{ number_format($item['potongan'], 0, ',', '.') }}</td>
                <td>{{ $item['keterangan'] }}</td>
                <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
            </tr>
            @endforeach
            {{-- setelah @endforeach kedua, sebelum
        </tbody> --}}
        <tr style="background-color: #e8f5e9; font-weight: bold;">
            <td colspan="3" style="text-align:center; font-weight:bold; padding: 5px 10px;">TOTAL</td>
            <td class="pos-cell" style="font-weight:bold; color:#1a7a1a;">
                {{ number_format($totalTambahan, 0, ',', '.') }}
            </td>
            <td class="neg-cell" style="font-weight:bold;">
                {{ number_format($totalPotongan, 0, ',', '.') }}
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr style="background-color: #ffff00; font-weight: bold;">
            <td colspan="3" style="text-align:center; font-weight:bold; padding: 5px 10px;">NET (TAMBAHAN - POTONGAN)
            </td>
            <td colspan="2" class="text-right" style="font-weight:bold; 
        color: {{ ($totalTambahan - $totalPotongan) >= 0 ? '#1a7a1a' : '#cc0000' }};">
                {{ ($totalTambahan - $totalPotongan) >= 0 ? '' : '- ' }}
                {{ number_format(abs($totalTambahan - $totalPotongan), 0, ',', '.') }}
            </td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    @endif

</body>

</html>
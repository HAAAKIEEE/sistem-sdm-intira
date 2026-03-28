<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Presensi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PresensiUserExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $user;
    protected $startDate;
    protected $endDate;
    protected $rekapTotal;

    public function __construct(User $user, string $startDate, string $endDate)
    {
        $this->user      = $user;
        $this->startDate = Carbon::parse($startDate)->startOfDay();
        $this->endDate   = Carbon::parse($endDate)->endOfDay();
    }

    public function collection()
    {
        $period = CarbonPeriod::create($this->startDate, $this->endDate);
        $dates  = collect($period)->map(fn($d) => $d->format('Y-m-d'));

        // Ambil semua presensi user dalam range
        $allPresensis = Presensi::where('user_id', $this->user->id)
            ->whereBetween('tanggal', [
                $this->startDate->toDateString(),
                $this->endDate->toDateString(),
            ])
            ->get()
            ->groupBy(fn($p) => $p->tanggal->format('Y-m-d'));

        $rows = collect();

        $rekapTotal = [
            'hadir'     => 0,
            'terlambat' => 0,
            'izin'      => 0,
            'sakit'     => 0,
            'alpha'     => 0,
        ];

        $branch = $this->user->branches->first()?->name ?? '-';

        foreach ($dates as $date) {
            $dayData = $allPresensis->get($date, collect());
            $grouped = $dayData->keyBy('status');

            $checkIn      = $grouped->get('CHECK_IN');
            $istirahatOut = $grouped->get('ISTIRAHAT_OUT');
            $istirahatIn  = $grouped->get('ISTIRAHAT_IN');
            $checkOut     = $grouped->get('CHECK_OUT');

            $carbonDate = Carbon::parse($date);
            $hariNama   = $this->hariIndonesia($carbonDate->dayOfWeek);

            // ========================
            // TIDAK ADA DATA
            // ========================
            if ($dayData->isEmpty()) {
                if ($carbonDate->isPast() && !$carbonDate->isToday()) {
                    $rekapTotal['alpha']++;
                    $rows->push([
                        'tanggal'         => $carbonDate->format('d/m/Y'),
                        'hari'            => $hariNama,
                        'check_in'        => '-',
                        'istirahat_out'   => '-',
                        'istirahat_in'    => '-',
                        'check_out'       => '-',
                        'foto_check_in'   => '-',
                        'foto_check_out'  => '-',
                        'status'          => 'Tidak Absen',
                        'menit_terlambat' => '-',
                        'potongan'        => '-',
                        'keterangan'      => 'Tidak ada data absensi',
                    ]);
                } else {
                    $rows->push([
                        'tanggal'         => $carbonDate->format('d/m/Y'),
                        'hari'            => $hariNama,
                        'check_in'        => '-',
                        'istirahat_out'   => '-',
                        'istirahat_in'    => '-',
                        'check_out'       => '-',
                        'foto_check_in'   => '-',
                        'foto_check_out'  => '-',
                        'status'          => '-',
                        'menit_terlambat' => '-',
                        'potongan'        => '-',
                        'keterangan'      => '-',
                    ]);
                }
                continue;
            }

            // ========================
            // ADA DATA
            // ========================
            $jamCI  = $checkIn      ? Carbon::parse($checkIn->jam)->format('H:i')      : '-';
            $jamIO  = $istirahatOut ? Carbon::parse($istirahatOut->jam)->format('H:i')  : '-';
            $jamII  = $istirahatIn  ? Carbon::parse($istirahatIn->jam)->format('H:i')   : '-';
            $jamCO  = $checkOut     ? Carbon::parse($checkOut->jam)->format('H:i')      : '-';

            // Foto: cek semua status
            $fotoCi = $checkIn?->photo  ? 'Ada' : 'Tidak Ada';
            $fotoCo = $checkOut?->photo ? 'Ada' : 'Tidak Ada';

            $keterangan = $checkIn?->keterangan ?? '-';
            $ketLower   = strtolower($keterangan);

            // Hitung potongan
            $potonganData   = $checkIn ? $checkIn->hitungPotonganTerlambat() : null;
            $menitTerlambat = $potonganData ? $potonganData['menit_terlambat'] : 0;
            $potongan       = $potonganData ? $potonganData['potongan'] : 0;

            // Status label
            if (str_contains($ketLower, 'sakit')) {
                $statusLabel = 'Sakit';
                $rekapTotal['sakit']++;
            } elseif (str_contains($ketLower, 'izin') || str_contains($ketLower, 'cuti')) {
                $statusLabel = 'Izin/Cuti';
                $rekapTotal['izin']++;
            } elseif ($checkIn && $checkOut) {
                $statusLabel = 'Hadir Lengkap';
                $rekapTotal['hadir']++;
                if ($menitTerlambat > 0) $rekapTotal['terlambat']++;
            } else {
                $statusLabel = 'Tidak Lengkap';
                $rekapTotal['hadir']++;
                if ($menitTerlambat > 0) $rekapTotal['terlambat']++;
            }

            $rows->push([
                'tanggal'         => $carbonDate->format('d/m/Y'),
                'hari'            => $hariNama,
                'check_in'        => $jamCI,
                'istirahat_out'   => $jamIO,
                'istirahat_in'    => $jamII,
                'check_out'       => $jamCO,
                'foto_check_in'   => $fotoCi,
                'foto_check_out'  => $fotoCo,
                'status'          => $statusLabel,
                'menit_terlambat' => $menitTerlambat > 0 ? $menitTerlambat . ' mnt' : '-',
                'potongan'        => $potongan > 0 ? 'Rp ' . number_format($potongan, 0, ',', '.') : '-',
                'keterangan'      => $keterangan,
            ]);
        }

        $this->rekapTotal = $rekapTotal;

        return $rows->map(fn($r) => array_values($r));
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Hari',
            'Check In',
            'Istirahat Out',
            'Istirahat In',
            'Check Out',
            'Foto Check In',
            'Foto Check Out',
            'Status',
            'Menit Terlambat',
            'Potongan',
            'Keterangan',
        ];
    }

    public function title(): string
    {
        return 'Presensi ' . $this->user->name;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 13, // Tanggal
            'B' => 12, // Hari
            'C' => 12, // Check In
            'D' => 14, // Istirahat Out
            'E' => 13, // Istirahat In
            'F' => 12, // Check Out
            'G' => 15, // Foto CI
            'H' => 15, // Foto CO
            'I' => 16, // Status
            'J' => 16, // Menit Terlambat
            'K' => 18, // Potongan
            'L' => 30, // Keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header info user di atas tabel
        $sheet->insertNewRowBefore(1, 3);

        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'LAPORAN PRESENSI - ' . strtoupper($this->user->name));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F4E79']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        $sheet->mergeCells('A2:L2');
        $sheet->setCellValue(
            'A2',
            'Periode: ' . $this->startDate->format('d/m/Y') . ' s/d ' . $this->endDate->format('d/m/Y') .
                '   |   Branch: ' . ($this->user->branches->first()?->name ?? '-')
        );
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => false, 'size' => 11, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2E75B6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(22);

        // Row 3 → kosong sebagai spacer
        $sheet->getRowDimension(3)->setRowHeight(5);

        // Heading row (row 4 setelah insert)
        $sheet->getStyle('A4:L4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F4E79']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(30);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet    = $event->sheet->getDelegate();
                $lastRow  = $sheet->getHighestRow();

                // Row data dimulai dari 5 (setelah 3 baris info + 1 heading)
                for ($row = 5; $row <= $lastRow; $row++) {
                    $status = $sheet->getCell('I' . $row)->getValue();

                    $bgColor = match (true) {
                        str_contains((string)$status, 'Tidak Absen')   => 'FFFF0000',
                        str_contains((string)$status, 'Sakit')         => 'FFFFC000',
                        str_contains((string)$status, 'Izin')          => 'FF0070C0',
                        str_contains((string)$status, 'Tidak Lengkap') => 'FFFF7F00',
                        str_contains((string)$status, 'Hadir Lengkap') => 'FF00B050',
                        default                                         => null,
                    };

                    if ($bgColor) {
                        $sheet->getStyle('I' . $row)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                            'font' => ['color' => ['argb' => 'FFFFFFFF'], 'bold' => true],
                        ]);
                    }

                    // Zebra
                    if ($row % 2 === 0) {
                        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L'] as $col) {
                            $sheet->getStyle($col . $row)->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF2F2F2']],
                            ]);
                        }
                    }

                    // Border
                    $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD0D0D0']]],
                    ]);

                    // Center alignment kolom tertentu
                    foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'] as $col) {
                        $sheet->getStyle($col . $row)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                            ->setVertical(Alignment::VERTICAL_CENTER);
                    }
                }

                // ================================
                // REKAP SUMMARY
                // ================================
                $summaryRow = $lastRow + 2;

                $sheet->mergeCells('A' . $summaryRow . ':C' . $summaryRow);
                $sheet->setCellValue('A' . $summaryRow, 'REKAP PRIBADI');
                $sheet->getStyle('A' . $summaryRow . ':L' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F4E79']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $summaryRow++;
                $rekapData = [
                    ['Total Hadir',     $this->rekapTotal['hadir']     ?? 0, 'FF00B050'],
                    ['Total Terlambat', $this->rekapTotal['terlambat'] ?? 0, 'FFFFC000'],
                    ['Total Izin/Cuti', $this->rekapTotal['izin']      ?? 0, 'FF0070C0'],
                    ['Total Sakit',     $this->rekapTotal['sakit']     ?? 0, 'FFFF7F00'],
                    ['Total Alpha',     $this->rekapTotal['alpha']     ?? 0, 'FFFF0000'],
                ];

                foreach ($rekapData as [$label, $value, $color]) {
                    $sheet->setCellValue('A' . $summaryRow, $label);
                    $sheet->setCellValue('B' . $summaryRow, $value);
                    $sheet->getStyle('A' . $summaryRow)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF2F2F2']],
                    ]);
                    $sheet->getStyle('B' . $summaryRow)->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['argb' => $color]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF2F2F2']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $summaryRow++;
                }

                // Freeze pane
                $sheet->freezePane('A5');

                // Auto filter dari heading
                $sheet->setAutoFilter('A4:L4');
            },
        ];
    }

    private function hariIndonesia(int $dayOfWeek): string
    {
        return match ($dayOfWeek) {
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        };
    }
}

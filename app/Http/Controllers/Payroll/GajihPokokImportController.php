<?php

namespace App\Http\Controllers\Payroll;

use App\Imports\GajihPokokImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class GajihPokokImportController extends Controller
{
    /**
     * Menampilkan form import
     */
    // public function create()
    // {
    //     return view('gaji_pokok.import');
    // }

    /**
     * Proses import gaji pokok
     */
    public function store(Request $request)
    {
        Log::info('Import gaji pokok started', [
            'file_name' => $request->file('file')
                ? $request->file('file')->getClientOriginalName()
                : 'No file'
        ]);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB
        ]);

        try {
            $import = new GajihPokokImport();

            Log::info('Starting Excel import (gaji pokok)');

            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $failures     = $import->failures();

            Log::info('Import gaji pokok completed', [
                'success_count' => $successCount,
                'failure_count' => count($failures)
            ]);

            // ===============================
            // JIKA ADA ERROR PARSIAL
            // ===============================
            if (count($failures) > 0) {
                $errorMessages = [];

                foreach ($failures as $failure) {
                    $errorMessages[] =
                        "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                }

                return response()->json([
                    'success'   => false,
                    'inserted'  => $successCount,
                    'message'   => implode(' ', $errorMessages),
                ], 200); // 200 biar JS tetap kebaca
            }

            // ===============================
            // FULL SUCCESS
            // ===============================
            return response()->json([
                'success'  => true,
                'message'  => "Berhasil mengimport {$successCount} data gaji pokok.",
                'inserted' => $successCount
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            Log::error('ValidationException import gaji pokok', [
                'errors'   => $e->errors(),
                'failures' => $e->failures()
            ]);

            $errors = [];
            foreach ($e->failures() as $failure) {
                $errors[] =
                    "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return response()->json([
                'success' => false,
                'message' => implode(' ', $errors)
            ], 422);
        } catch (\Exception $e) {

            Log::error('Import gaji pokok error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template Excel
     */
    // public function template()
    // {
    //     $templatePath = storage_path('app/templates/template_import_gaji_pokok.xlsx');

    //     if (!file_exists($templatePath)) {
    //         return $this->generateTemplate();
    //     }

    //     return response()->download(
    //         $templatePath,
    //         'template_import_gaji_pokok.xlsx'
    //     );
    // }

    // /**
    //  * Generate template Excel secara dinamis
    //  */
    // private function generateTemplate()
    // {
    //     $data = [
    //         [
    //             'email_karyawan'          => 'akramm1782@gmail.com',
    //             'kode_cabang'             => 'BJM-009',
    //             'nominal_gaji'            => 1200000,
    //             'tunjangan_makan'         => 30000,
    //             'tunjangan_transportasi'  => 25000,
    //             'tunjangan_jabatan'       => 20000,
    //             'tunjangan_komunikasi'    => 15000,
    //             'bulan'                   => 'jan',
    //             'tahun'                   => 2026,
    //             'keterangan'              => 'selesai',
    //         ],
    //         [
    //             'email_karyawan'          => 'juhratun.nissa@icloud.com',
    //             'kode_cabang'             => 'HSU-001',
    //             'nominal_gaji'            => 1200000,
    //             'tunjangan_makan'         => 30000,
    //             'tunjangan_transportasi'  => 25000,
    //             'tunjangan_jabatan'       => 20000,
    //             'tunjangan_komunikasi'    => 15000,
    //             'bulan'                   => 'jan',
    //             'tahun'                   => 2026,
    //             'keterangan'              => 'selesai',
    //         ],
    //     ];

    //     return Excel::download(
    //         new class($data) implements
    //             \Maatwebsite\Excel\Concerns\FromArray,
    //             \Maatwebsite\Excel\Concerns\WithHeadings {

    //             private $data;

    //             public function __construct($data)
    //             {
    //                 $this->data = $data;
    //             }

    //             public function array(): array
    //             {
    //                 return $this->data;
    //             }

    //             public function headings(): array
    //             {
    //                 return [
    //                     'email_karyawan',
    //                     'kode_cabang',
    //                     'nominal_gaji',
    //                     'tunjangan_makan',
    //                     'tunjangan_transportasi',
    //                     'tunjangan_jabatan',
    //                     'tunjangan_komunikasi',
    //                     'bulan',
    //                     'tahun',
    //                     'keterangan',
    //                 ];
    //             }
    //         },
    //         'template_import_gaji_pokok.xlsx'
    //     );
    // }

    public function template()
{
    return Excel::download(
        new class implements
            \Maatwebsite\Excel\Concerns\FromArray,
            \Maatwebsite\Excel\Concerns\WithHeadings {

            public function array(): array
            {
                return [
                    [
                        'email'                          => 'isyevira02@gmail.com',
                        'golongan'                       => 'A',
                        'gaji pokok'                     => 5000000,
                        'tunjangan makan'                => 500000,
                        'tunjangan jabatan'              => 1000000,
                        'tunjangan transportasi'         => 300000,
                        'tunjangan komunikasi'           => 200000,
                        'potongan bpjs ketenagakerjaan'  => 150000,
                        'persentase revenue'             => 10,
                        'bonuse revenue'                 => 500000,
                        'total revenue'                  => 5500000,
                        'persentase kpi'                 => 80,
                        'bonus kpi'                      => 400000,
                        'total kpi'                      => 400000,
                        'simpanan'                       => 100000,
                        'bulan'                          => 1,
                        'tahun'                          => 2026,
                        'keterangan'                     => 'gaji januari',
                        'potongan bpjs kesehatan'        => 100000,
                        'hari kerja'                     => 22,
                    ],
                    [
                        'email'                          => 'rekhasyavira1@gmail.com',
                        'golongan'                       => 'B',
                        'gaji pokok'                     => 6000000,
                        'tunjangan makan'                => 600000,
                        'tunjangan jabatan'              => 1200000,
                        'tunjangan transportasi'         => 400000,
                        'tunjangan komunikasi'           => 250000,
                        'potongan bpjs ketenagakerjaan'  => 180000,
                        'persentase revenue'             => 12,
                        'bonuse revenue'                 => 720000,
                        'total revenue'                  => 6720000,
                        'persentase kpi'                 => 85,
                        'bonus kpi'                      => 510000,
                        'total kpi'                      => 510000,
                        'simpanan'                       => 120000,
                        'bulan'                          => 1,
                        'tahun'                          => 2026,
                        'keterangan'                     => 'gaji januari',
                        'potongan bpjs kesehatan'        => 120000,
                        'hari kerja'                     => 22,
                    ],
                ];
            }

            public function headings(): array
            {
                return [
                    'email',
                    'golongan',
                    'gaji pokok',
                    'tunjangan makan',
                    'tunjangan jabatan',
                    'tunjangan transportasi',
                    'tunjangan komunikasi',
                    'potongan bpjs ketenagakerjaan',
                    'persentase revenue',
                    'bonuse revenue',
                    'total revenue',
                    'persentase kpi',
                    'bonus kpi',
                    'total kpi',
                    'simpanan',
                    'bulan',
                    'tahun',
                    'keterangan',
                    'potongan bpjs kesehatan',
                    'hari kerja',
                ];
            }
        },
        'template_import_gaji_pokok.xlsx'
    );
}

}

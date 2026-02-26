<?php

namespace App\Http\Controllers;

use App\Imports\PresensiImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PresensiImportController extends Controller
{
    /**
     * Menampilkan form import
     */
    // public function create()
    // {
    //     return view('gaji_pokok.import');
    // }

    /**
     * Proses import presensi
     */
    public function store(Request $request)
    {
        Log::info('Import presensi started', [
            'file_name' => $request->file('file')
                ? $request->file('file')->getClientOriginalName()
                : 'No file'
        ]);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB
        ]);

        try {
            $import = new PresensiImport();

            Log::info('Starting Excel import (presensi)');

            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $failures     = $import->failures();

            Log::info('Import presensi completed', [
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
                'message'  => "Berhasil mengimport {$successCount} data presensi.",
                'inserted' => $successCount
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            Log::error('ValidationException import presensi', [
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

            Log::error('Import presensi error', [
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
    //     $templatePath = storage_path('app/templates/template_import_presensi.xlsx');

    //     if (!file_exists($templatePath)) {
    //         return $this->generateTemplate();
    //     }

    //     return response()->download(
    //         $templatePath,
    //         'template_import_presensi.xlsx'
    //     );
    // }

    // /**
    //  * Generate template Excel secara dinamis
    //  */
    // private function generateTemplate()
    // {
    //     $data = [
    //         [
    //             'nama'                    => 'Juhratun Nissa',
    //             'tanggal'                 => '2026-01-01',
    //             'status'                  => 'CHECK_IN',
    //             'jam'                     => '08:00',
    //             'wilayah'                 => 'Jakarta',
    //         ],
    //         [
    //             'nama'                    => 'Juhratun Nissa',
    //             'tanggal'                 => '2026-01-01',
    //             'status'                  => 'ISTIRAHAT_IN',
    //             'jam'                     => '12:00',
    //             'wilayah'                 => 'Jakarta',
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
    //                     'nama',
    //                     'tanggal',
    //                     'status',
    //                     'jam',
    //                     'wilayah',
    //                     'keterangan',
    //                 ];
    //             }
    //         },
    //         'template_import_presensi.xlsx'
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
                        'tanggal'    => '2026-01-15',
                        'nama'       => 'Isyevira',
                        'status'     => 'CHECK_IN',
                        'jam'        => '08:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => '',
                    ],
                    [
                        'tanggal'    => '2026-01-15',
                        'nama'       => 'Isyevira',
                        'status'     => 'ISTIRAHAT_OUT',
                        'jam'        => '12:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => '',
                    ],
                    [
                        'tanggal'    => '2026-01-15',
                        'nama'       => 'Isyevira',
                        'status'     => 'ISTIRAHAT_IN',
                        'jam'        => '13:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => '',
                    ],
                    [
                        'tanggal'    => '2026-01-15',
                        'nama'       => 'Isyevira',
                        'status'     => 'CHECK_OUT',
                        'jam'        => '17:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => '',
                    ],
                    // ✅ Contoh Telat Masuk
                    [
                        'tanggal'    => '2026-01-16',
                        'nama'       => 'Rekhasyavira',
                        'status'     => 'CHECK_IN',
                        'jam'        => '09:30:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => 'Telat Masuk',
                    ],
                    [
                        'tanggal'    => '2026-01-16',
                        'nama'       => 'Rekhasyavira',
                        'status'     => 'CHECK_OUT',
                        'jam'        => '17:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => '',
                    ],
                    // ✅ Contoh Izin/Cuti
                    [
                        'tanggal'    => '2026-01-17',
                        'nama'       => 'Budi Santoso',
                        'status'     => 'CHECK_IN',
                        'jam'        => '08:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => 'Izin/Cuti',
                    ],
                    [
                        'tanggal'    => '2026-01-17',
                        'nama'       => 'Budi Santoso',
                        'status'     => 'CHECK_OUT',
                        'jam'        => '17:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => '',
                    ],
                    // ✅ Contoh Sakit
                    [
                        'tanggal'    => '2026-01-18',
                        'nama'       => 'Siti Rahayu',
                        'status'     => 'CHECK_IN',
                        'jam'        => '08:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => 'Sakit',
                    ],
                    [
                        'tanggal'    => '2026-01-18',
                        'nama'       => 'Siti Rahayu',
                        'status'     => 'CHECK_OUT',
                        'jam'        => '17:00:00',
                        'wilayah'    => 'WITA',
                        'keterangan' => '',
                    ],
                ];
            }

            public function headings(): array
            {
                return [
                    'tanggal',
                    'nama',
                    'status',
                    'jam',
                    'wilayah',
                    'keterangan',
                ];
            }
        },
        'template_import_presensi.xlsx'
    );
}
}

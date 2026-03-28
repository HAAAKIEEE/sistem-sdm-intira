<?php

// namespace App\Http\Controllers;

// use App\Exports\PresensiAllExport;
// use App\Exports\PresensiUserExport;
// use App\Models\User;
// use Carbon\Carbon;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Str;
// use Maatwebsite\Excel\Facades\Excel;

// class PresensiExportController extends Controller
// {
//     // ============================================================
//     // EXPORT ALL - mulai job & kembalikan progressKey
//     // ============================================================

//     public function exportAll(Request $request)
//     {
//         $request->validate([
//             'start_date' => 'required|date',
//             'end_date'   => 'required|date|after_or_equal:start_date',
//         ]);

//         $startDate = Carbon::parse($request->start_date);
//         $endDate   = Carbon::parse($request->end_date);

//         // Batasi maksimal 7 hari
//         if ($startDate->diffInDays($endDate) > 7) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Maksimal range export adalah 7 hari.',
//             ], 422);
//         }

//         // Generate progress key unik
//         $progressKey = 'export_progress_' . Str::random(12);

//         // Init progress
//         Cache::put($progressKey, 0, now()->addMinutes(15));

//         try {
//             $fileName = 'presensi_all_'
//                 . $startDate->format('Ymd')
//                 . '_'
//                 . $endDate->format('Ymd')
//                 . '.xlsx';

//             // Generate file (synchronous — karena range max 7 hari masih aman)
//             $export = new PresensiAllExport(
//                 $request->start_date,
//                 $request->end_date,
//                 $progressKey
//             );

//             // Simpan ke storage temporary
//             $filePath = 'exports/' . $progressKey . '_' . $fileName;
//             Excel::store($export, $filePath, 'local');

//             // Simpan path ke cache agar bisa di-download
//             Cache::put('export_file_' . $progressKey, [
//                 'path'     => $filePath,
//                 'filename' => $fileName,
//             ], now()->addMinutes(15));

//             Cache::put($progressKey, 100, now()->addMinutes(15));

//             return response()->json([
//                 'success'      => true,
//                 'progress_key' => $progressKey,
//                 'message'      => 'Export selesai.',
//             ]);
//         } catch (\Exception $e) {
//             Cache::put($progressKey, -1, now()->addMinutes(5)); // -1 = error
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
//             ], 500);
//         }
//     }

//     // ============================================================
//     // CEK PROGRESS
//     // ============================================================

//     public function progress($progressKey)
//     {
//         $progress = Cache::get($progressKey, 0);

//         return response()->json([
//             'progress' => $progress,
//             'done'     => $progress >= 100,
//             'error'    => $progress === -1,
//         ]);
//     }

//     // ============================================================
//     // DOWNLOAD FILE SETELAH SELESAI
//     // ============================================================

//     public function download($progressKey)
//     {
//         $fileInfo = Cache::get('export_file_' . $progressKey);

//         if (!$fileInfo) {
//             return back()->with('error', 'File tidak ditemukan atau sudah expired.');
//         }

//         $fullPath = storage_path('app/' . $fileInfo['path']);

//         if (!file_exists($fullPath)) {
//             return back()->with('error', 'File export tidak ditemukan.');
//         }

//         return response()->download($fullPath, $fileInfo['filename'])->deleteFileAfterSend(true);
//     }

//     // ============================================================
//     // EXPORT PERORANGAN (dari halaman show)
//     // ============================================================

//     public function exportUser(Request $request, $userId)
//     {
//         $request->validate([
//             'start_date' => 'required|date',
//             'end_date'   => 'required|date|after_or_equal:start_date',
//         ]);

//         $user = User::where('is_active', true)
//             ->with('branches')
//             ->findOrFail($userId);

//         $startDate = Carbon::parse($request->start_date);
//         $endDate   = Carbon::parse($request->end_date);

//         // Batasi max 31 hari untuk perorangan
//         if ($startDate->diffInDays($endDate) > 31) {
//             return back()->with('error', 'Maksimal range export perorangan adalah 31 hari.');
//         }

//         $fileName = 'presensi_'
//             . Str::slug($user->name)
//             . '_'
//             . $startDate->format('Ymd')
//             . '_'
//             . $endDate->format('Ymd')
//             . '.xlsx';

//         return Excel::download(
//             new PresensiUserExport($user, $request->start_date, $request->end_date),
//             $fileName
//         );
//     }
// }

namespace App\Http\Controllers;

use App\Exports\PresensiAllExport;
use App\Exports\PresensiUserExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PresensiExportController extends Controller
{
    // ============================================================
    // EXPORT ALL — generate langsung, simpan ke storage, return URL
    // ============================================================

    public function exportAll(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);

        if ($startDate->diffInDays($endDate) > 7) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal range export adalah 7 hari.',
            ], 422);
        }

        try {
            $token    = Str::random(16);
            $fileName = 'presensi_all_'
                . $startDate->format('Ymd')
                . '_'
                . $endDate->format('Ymd')
                . '_' . $token . '.xlsx';

            $filePath = 'exports/' . $fileName;

            // Pastikan folder ada
            Storage::disk('local')->makeDirectory('exports');

            // Generate Excel — synchronous
            Excel::store(
                new PresensiAllExport($request->start_date, $request->end_date),
                $filePath,
                'local'
            );

            // Pastikan file benar-benar ada
            if (!Storage::disk('local')->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File gagal dibuat. Coba lagi.',
                ], 500);
            }

            // Simpan info file ke cache selama 15 menit
            Cache::put('export_file_' . $token, [
                'path'     => $filePath,
                'filename' => 'Rekap_Presensi_'
                    . $startDate->format('d-m-Y')
                    . '_sd_'
                    . $endDate->format('d-m-Y')
                    . '.xlsx',
            ], now()->addMinutes(15));

            return response()->json([
                'success'      => true,
                'download_url' => route('presensi.export.download', $token),
                'message'      => 'Export selesai.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ============================================================
    // DOWNLOAD FILE
    // ============================================================

    public function download($token)
    {
        $fileInfo = Cache::get('export_file_' . $token);

        // DEBUG SEMENTARA - hapus setelah fix
        // dd([
        //     'token'     => $token,
        //     'cache_key' => 'export_file_' . $token,
        //     'cache_val' => Cache::get('export_file_' . $token),
        //     'all_keys'  => Cache::get('export_file_' . $token) ? 'ADA' : 'TIDAK ADA',
        // ]);

        if (!$fileInfo) {
            abort(404, 'File tidak ditemukan atau sudah expired.');
        }

        // ✅ SESUDAH
        if (!Storage::disk('local')->exists($fileInfo['path'])) {
            abort(404, 'File export tidak ditemukan di server.');
        }
        $fullPath = Storage::disk('local')->path($fileInfo['path']);


        return response()->download($fullPath, $fileInfo['filename'])->deleteFileAfterSend(true);
    }

    // ============================================================
    // EXPORT PERORANGAN
    // ============================================================

    public function exportUser(Request $request, $userId)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $user = User::where('is_active', true)
            ->with('branches')
            ->findOrFail($userId);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);

        if ($startDate->diffInDays($endDate) > 31) {
            return back()->with('error', 'Maksimal range export perorangan adalah 31 hari.');
        }

        $fileName = 'Presensi_'
            . Str::slug($user->name)
            . '_'
            . $startDate->format('d-m-Y')
            . '_sd_'
            . $endDate->format('d-m-Y')
            . '.xlsx';

        return Excel::download(
            new PresensiUserExport($user, $request->start_date, $request->end_date),
            $fileName
        );
    }
}

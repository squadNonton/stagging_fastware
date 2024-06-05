<?php

namespace App\Http\Controllers;

use App\Imports\WOImport;
use App\Jobs\ProcessExcelJob;
use App\Models\HeatTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class HeatTreatmentController extends Controller
{
    public function dashboardImportWO()
    {
        $heattreatments = HeatTreatment::orderBy('updated_at', 'desc')->get();
        return view('wo_heat.importWO', compact('heattreatments'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function dashboardTracingWO()
    {
        return view('wo_heat.tracingWO');
    }

    // public function importWO(Request $request)
    // {
    //     $request->validate([
    //         'excelFile' => 'required|mimes:xlsx,xls',
    //     ]);

    //     // Dapatkan jalur file sementara
    //     $temporaryFilePath = $request->file('excelFile')->getRealPath();

    //     // Kirim pekerjaan pemrosesan dengan menggunakan jalur sementara
    //     ProcessExcelJob::dispatch($temporaryFilePath);

    //     return back()->with('success', 'File is being processed.');
    // }

    // Controller Method
    public function importWO(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // Mendapatkan path dari file yang diupload
        $filePath = $request->file('excelFile')->getRealPath();

        // Melakukan import data
        try {
            Excel::import(new WOImport(), $filePath);
            return back()->with('success', 'Data telah berhasil diimpor');
        } catch (\Exception $e) {
            Log::error("Error importing file: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data. Silakan coba lagi.');
        }
    }

    public function searchWO(Request $request)
    {
        $searchWO = $request->input('searchWO');
        if ($searchWO) {
            $data = HeatTreatment::where('cust', 'LIKE', '%' . $searchWO . '%')->get()->groupBy('cust');

            $response = $data->map(function ($items, $key) {
                return $items->map(function ($item) {
                    return [
                        'no_wo' => $item->no_wo,
                        'cust' => $item->cust,
                        'tgl_wo' => $item->tgl_wo,
                        'status_wo' => $item->status_wo,
                        'status_do' => $item->status_do,
                        'proses' => $item->proses,
                        'batch' => $item->batch_heating,
                        'mesin' => $item->mesin_heating,
                        'tgl_heating' => $item->tgl_heating,
                        'batch_temper1' => $item->batch_temper1,
                        'mesin_temper1' => $item->mesin_temper1,
                        'tgl_temper1' => $item->tgl_temper1,
                        'batch_temper2' => $item->batch_temper2,
                        'mesin_temper2' => $item->mesin_temper2,
                        'tgl_temper2' => $item->tgl_temper2,
                        'batch_temper3' => $item->batch_temper3,
                        'mesin_temper3' => $item->mesin_temper3,
                        'tgl_temper3' => $item->tgl_temper3,
                        'no_do' => $item->no_do,
                        'tgl_st' => $item->tgl_st,
                        'supir' => $item->supir,
                        'penerima' => $item->penerima,
                        'tgl_terima' => $item->tgl_terima,
                        'pcs' => $item->pcs,
                        'kg' => $item->kg,
                    ];
                });
            });

            return response()->json($response);
        }
        return response()->json(['message' => 'No data found'], 404);
    }
}

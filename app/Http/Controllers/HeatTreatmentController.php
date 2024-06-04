<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessExcelJob;
use App\Models\HeatTreatment;
use Illuminate\Http\Request;
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

    public function importWO(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls',
        ]);

        // Dapatkan jalur file sementara
        $temporaryFilePath = $request->file('excelFile')->getPathname();

        // Kirim pekerjaan pemrosesan dengan menggunakan jalur sementara
        ProcessExcelJob::dispatch($temporaryFilePath);

        return back()->with('success', 'File is being processed.');
    }


    public function searchWO(Request $request)
    {
        $searchWO = $request->input('searchWO');
        if ($searchWO) {
            $data = HeatTreatment::where('cust', 'LIKE', '%' . $searchWO . '%')->get()->groupBy('cust');

            $response = $data->map(function ($items, $key) {
                return [
                    'no_wo' => $items->pluck('no_wo')->unique()->implode(', '),
                    'cust' => $items->pluck('cust')->unique()->implode(', '),
                    'tgl_wo' => $items->pluck('tgl_wo')->unique()->implode(', '),
                    'status_wo' => $items->pluck('status_wo')->unique()->implode(', '),
                    'status_do' => $items->pluck('status_do')->unique()->implode(', '),
                    'proses' => $items->pluck('proses')->unique()->implode(', '),
                    'batch' => $items->pluck('batch_heating')->unique()->implode(', '),
                    'mesin' => $items->pluck('mesin_heating')->unique()->implode(', '),
                    'tgl_heating' => $items->pluck('tgl_heating')->unique()->implode(', '),
                    'batch_temper1' => $items->pluck('batch_temper1')->unique()->implode(', '),
                    'mesin_temper1' => $items->pluck('mesin_temper1')->unique()->implode(', '),
                    'tgl_temper1' => $items->pluck('tgl_temper1')->unique()->implode(', '),
                    'batch_temper2' => $items->pluck('batch_temper2')->unique()->implode(', '),
                    'mesin_temper2' => $items->pluck('mesin_temper2')->unique()->implode(', '),
                    'tgl_temper2' => $items->pluck('tgl_temper2')->unique()->implode(', '),
                    'batch_temper3' => $items->pluck('batch_temper3')->unique()->implode(', '),
                    'mesin_temper3' => $items->pluck('mesin_temper3')->unique()->implode(', '),
                    'tgl_temper3' => $items->pluck('tgl_temper3')->unique()->implode(', '),
                    'no_do' => $items->pluck('no_do')->unique()->implode(', '),
                    'tgl_st' => $items->pluck('tgl_st')->unique()->implode(', '),
                    'supir' => $items->pluck('supir')->unique()->implode(', '),
                    'penerima' => $items->pluck('penerima')->unique()->implode(', '),
                    'tgl_terima' => $items->pluck('tgl_terima')->unique()->implode(', '),
                    'pcs' => $items->sum('pcs'),
                    'kg' => $items->sum('kg'),
                ];
            });

            return response()->json($response);
        }
        return response()->json(['message' => 'No data found'], 404);
    }

    public function fetchDetailProses(Request $request)
    {
        // Lakukan sesuatu untuk mendapatkan data detail berdasarkan cellId
        $cellText = $request->input('cellText');

        // Contoh pengembalian data
        $detailData = [
            'tgl_wo' => 'Tanggal WO dari ' . $cellText,
            'status_wo' => 'Status WO dari ' . $cellText,
            'status_do' => 'Status DO dari ' . $cellText,
            'proses' => 'Proses dari ' . $cellText,
            'customer' => 'Customer dari ' . $cellText,
            'pcs' => 'PCS dari ' . $cellText,
            'tonase' => 'Tonase dari ' . $cellText,
        ];

        // Kembalikan data dalam format JSON
        return response()->json($detailData);
    }
}

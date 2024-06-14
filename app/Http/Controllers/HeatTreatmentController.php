<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessExcelJob;
use App\Models\HeatTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function WOHeat(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls', // Validasi file hanya boleh berformat Excel
        ]);

        try {
            $file = $request->file('excelFile');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            foreach ($rows as $index => $row) {
                if ($index < 2) {
                    // Skip the first two rows
                    continue;
                }

                // Normalize numeric values to remove unnecessary decimals
                foreach ($row as $key => $value) {
                    if (is_numeric($value)) {
                        $row[$key] = (string)intval($value);
                    }
                }

                // Check if record exists
                $existingRecord = HeatTreatment::where('no_wo', $row[0])->where('no_so', $row[1])->first();

                $data = [
                    'no_wo' => $row[0],
                    'no_so' => $row[1],
                    'tgl_wo' => $row[2],
                    'deskripsi' => $row[3],
                    'area' => $row[4],
                    'kode' => $row[5],
                    'cust' => $row[6],
                    'proses' => $row[7],
                    'pcs' => $row[8],
                    'kg' => $row[9],
                    'batch_heating' => $row[10],
                    'mesin_heating' => $row[11],
                    'tgl_heating' => $row[12],
                    'batch_temper1' => $row[13],
                    'mesin_temper1' => $row[14],
                    'tgl_temper1' => $row[15],
                    'batch_temper2' => $row[16],
                    'mesin_temper2' => $row[17],
                    'tgl_temper2' => $row[18],
                    'batch_temper3' => $row[19],
                    'mesin_temper3' => $row[20],
                    'tgl_temper3' => $row[21],
                    'status_wo' => $row[22],
                    'no_do' => $row[23],
                    'status_do' => $row[24],
                    'tgl_st' => $row[25],
                    'supir' => $row[26],
                    'penerima' => $row[27],
                    'tgl_terima' => $row[28],
                ];

                if ($existingRecord) {
                    // Update the existing record
                    $existingRecord->update($data);
                } else {
                    // Create a new record
                    HeatTreatment::create($data);
                }
            }

            return response()->json(['success' => true, 'message' => 'Data berhasil diimpor.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }




    public function searchWO(Request $request)
    {
        // Mengambil nilai input dari request
        $searchWO = $request->input('searchWO');
        $searchStatusWO = $request->input('searchStatusWO');
        $searchStatusDO = $request->input('searchStatusDO');
        $startMonth = $request->input('startMonth');
        $endMonth = $request->input('endMonth');

        // Membuat query dasar untuk model HeatTreatment
        $query = HeatTreatment::query();

        // Tambahkan kondisi pencarian
        $query->where(function ($q) use ($searchWO, $searchStatusWO, $searchStatusDO, $startMonth, $endMonth) {
            if ($searchWO) {
                $q->where('cust', 'LIKE', '%' . $searchWO . '%');
            }
            if ($searchStatusWO && $searchStatusWO != 'All') {
                $q->where('status_wo', 'LIKE', '%' . $searchStatusWO . '%');
            }
            if ($searchStatusDO && $searchStatusDO != 'All') {
                $q->where('status_do', 'LIKE', '%' . $searchStatusDO . '%');
            }
            // Tambahkan kondisi untuk menangani filter berdasarkan bulan (ambil bagian bulan dari format dd-mm)
            if ($startMonth && $endMonth) {
                $q->whereBetween(DB::raw('SUBSTRING(tgl_wo, 4, 2)'), [$startMonth, $endMonth]);
            } elseif ($startMonth) {
                $q->where(DB::raw('SUBSTRING(tgl_wo, 4, 2)'), $startMonth);
            } elseif ($endMonth) {
                $q->where(DB::raw('SUBSTRING(tgl_wo, 4, 2)'), $endMonth);
            }
        });

        // Tambahkan distinct() untuk menghindari duplikat
        $query->distinct();

        // Mendapatkan data berdasarkan kondisi pencarian dan mengelompokkannya berdasarkan customer
        $data = $query->get()->groupBy('cust');

        // Transformasi data yang dikelompokkan ke dalam struktur yang diinginkan
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
                    'mesin_heating' => $item->mesin_heating,
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

        // Mengembalikan data dalam format JSON
        return response()->json($response);
    }

    public function getBatchData(Request $request)
    {
        $batch = $request->input('batch');

        $workOrders = collect([]);

        $heatingQuery = HeatTreatment::where('batch_heating', $batch);
        $temper1Query = HeatTreatment::where('batch_temper1', $batch);
        $temper2Query = HeatTreatment::where('batch_temper2', $batch);
        $temper3Query = HeatTreatment::where('batch_temper3', $batch);

        $workOrders->push($heatingQuery->get());
        $workOrders->push($temper1Query->get());
        $workOrders->push($temper2Query->get());
        $workOrders->push($temper3Query->get());

        $mergedWorkOrders = $workOrders->collapse();

        if ($mergedWorkOrders->isEmpty()) {
            return response()->json(['message' => 'No data found for the batch ' . $batch], 404);
        }

        return response()->json($mergedWorkOrders);
    }
}

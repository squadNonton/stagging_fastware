<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\HeatTreatment;
use Carbon\Carbon;


class ProcessExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $data = [];
        $mergeCells = $sheet->getMergeCells();

        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                $cellValue = $cell->getValue();

                // Skip merged cells
                foreach ($mergeCells as $mergeRange) {
                    $range = Coordinate::extractAllCellReferencesInRange($mergeRange);
                    if (in_array($cell->getCoordinate(), $range)) {
                        continue 2; // Skip to the next cell
                    }
                }

                $rowData[] = $cellValue;
            }

            // Check if the row data is not empty
            if (!empty($rowData)) {
                $data[] = $rowData;
            }
        }

        // Assuming each row of data represents a record in your HeatTreatment model
        foreach ($data as $rowData) {
            // Check if the row data has enough elements
            if (count($rowData) >= 28) {
                $existingRecord = HeatTreatment::where('no_wo', $rowData[0])
                    ->where('no_so', $rowData[1])
                    ->first();

                if ($existingRecord) {
                    // Update the existing record
                    $existingRecord->update([
                        'tgl_wo' => $rowData[2],
                        'area' => $rowData[3],
                        'kode' => $rowData[4],
                        'cust' => $rowData[5],
                        'proses' => $rowData[6],
                        'pcs' => $rowData[7],
                        'kg' => $rowData[8],
                        'batch_heating' => $rowData[9],
                        'mesin_heating' => $rowData[10],
                        'tgl_heating' => $rowData[11],
                        'batch_temper1' => $rowData[12],
                        'mesin_temper1' => $rowData[13],
                        'tgl_temper1' => $rowData[14],
                        'batch_temper2' => $rowData[15],
                        'mesin_temper2' => $rowData[16],
                        'tgl_temper2' => $rowData[17],
                        'batch_temper3' => $rowData[18],
                        'mesin_temper3' => $rowData[19],
                        'tgl_temper3' => $rowData[20],
                        'status_wo' => $rowData[21],
                        'no_do' => $rowData[22],
                        'status_do' => $rowData[23],
                        'tgl_st' => $rowData[24],
                        'supir' => $rowData[25],
                        'penerima' => $rowData[26],
                        'tgl_terima' => $rowData[27],
                    ]);
                } else {
                    // Create a new record
                    HeatTreatment::create([
                        'no_wo' => $rowData[0],
                        'no_so' => $rowData[1],
                        'tgl_wo' => $rowData[2],
                        'area' => $rowData[3],
                        'kode' => $rowData[4],
                        'cust' => $rowData[5],
                        'proses' => $rowData[6],
                        'pcs' => $rowData[7],
                        'kg' => $rowData[8],
                        'batch_heating' => $rowData[9],
                        'mesin_heating' => $rowData[10],
                        'tgl_heating' => $rowData[11],
                        'batch_temper1' => $rowData[12],
                        'mesin_temper1' => $rowData[13],
                        'tgl_temper1' => $rowData[14],
                        'batch_temper2' => $rowData[15],
                        'mesin_temper2' => $rowData[16],
                        'tgl_temper2' => $rowData[17],
                        'batch_temper3' => $rowData[18],
                        'mesin_temper3' => $rowData[19],
                        'tgl_temper3' => $rowData[20],
                        'status_wo' => $rowData[21],
                        'no_do' => $rowData[22],
                        'status_do' => $rowData[23],
                        'tgl_st' => $rowData[24],
                        'supir' => $rowData[25],
                        'penerima' => $rowData[26],
                        'tgl_terima' => $rowData[27],
                    ]);
                }
            }
        }
    }
}
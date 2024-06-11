<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\HeatTreatment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WOImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // Get the header row from the first row
        $header = $collection->first()->toArray();

        // Skip the header row for processing data rows
        $rows = $collection->skip(1);

        DB::transaction(function () use ($rows, $header) {
            foreach ($rows as $row) {
                try {
                    // Ensure the row has at least 28 columns
                    if (count($row) >= 28) {
                        // Trim all values and check for emptiness
                        $row = $row->map(function ($value) {
                            return trim($value);
                        });

                        // Skip rows where mandatory fields are empty (e.g., 'no_wo' or 'no_so')
                        $no_wo = $row[array_search('No.WO', $header)];
                        $no_so = $row[array_search('No.SO', $header)];

                        if (empty($no_wo) || (!isset($no_so) && $no_so !== '0')) {
                            Log::warning("Skipping row with empty mandatory fields: " . json_encode($row));
                            continue;
                        }

                        $existingRecord = null;

                        // Check if no_so is not 0, then apply the filter
                        if ($no_so !== '0') {
                            $existingRecord = HeatTreatment::where('no_wo', $no_wo)
                                ->where('no_so', $no_so)
                                ->first();
                        }

                        // Function to handle date values and format as dd/mm/yyyy
                        $parseDate = function ($date) {
                            if (empty($date)) {
                                return null;
                            }

                            // Try to parse it as 'd-m' format and convert to 'd/m/Y'
                            try {
                                return Carbon::createFromFormat('d-m', $date)->format('d/m/Y');
                            } catch (\Exception $e) {
                                Log::warning("Date parsing failed for: $date");
                                return null;
                            }
                        };

                        // Log parsed dates for debugging
                        $parsedDates = [
                            'tgl_wo' => $parseDate($row[array_search('TGL WO', $header)]),
                            'tgl_heating' => $parseDate($row[array_search('Tgl', $header)]),
                            'tgl_temper1' => $parseDate($row[array_search('Tgl', $header) + 1]), // Adjusted to match the second occurrence
                            'tgl_temper2' => $parseDate($row[array_search('Tgl', $header) + 2]), // Adjusted to match the third occurrence
                            'tgl_temper3' => $parseDate($row[array_search('Tgl', $header) + 3]), // Adjusted to match the fourth occurrence
                            'tgl_st' => $parseDate($row[array_search('TGL ST', $header)]),
                            'tgl_terima' => $parseDate($row[array_search('TGL TERIMA', $header)])
                        ];
                        Log::info("Parsed dates: " . json_encode($parsedDates));

                        $recordData = [
                            'tgl_wo' => $parsedDates['tgl_wo'],
                            'area' => $row[array_search('AREA', $header)] ?: null,
                            'kode' => $row[array_search('KODE', $header)] ?: null,
                            'cust' => $row[array_search('CUST', $header)] ?: null,
                            'proses' => $row[array_search('PROSES', $header)] ?: null,
                            'pcs' => $row[array_search('PCS', $header)] ?: null,
                            'kg' => $row[array_search('KG', $header)] ?: null,
                            'batch_heating' => $row[array_search('Batch', $header)] ?: null,
                            'mesin_heating' => $row[array_search('Mesin', $header)] ?: null,
                            'tgl_heating' => $parsedDates['tgl_heating'],
                            'batch_temper1' => $row[array_search('Batch', $header) + 1] ?: null, // Adjusted to match the second occurrence
                            'mesin_temper1' => $row[array_search('Mesin', $header) + 1] ?: null, // Adjusted to match the second occurrence
                            'tgl_temper1' => $parsedDates['tgl_temper1'],
                            'batch_temper2' => $row[array_search('Batch', $header) + 2] ?: null, // Adjusted to match the third occurrence
                            'mesin_temper2' => $row[array_search('Mesin', $header) + 2] ?: null, // Adjusted to match the third occurrence
                            'tgl_temper2' => $parsedDates['tgl_temper2'],
                            'batch_temper3' => $row[array_search('Batch', $header) + 3] ?: null, // Adjusted to match the fourth occurrence
                            'mesin_temper3' => $row[array_search('Mesin', $header) + 3] ?: null, // Adjusted to match the fourth occurrence
                            'tgl_temper3' => $parsedDates['tgl_temper3'],
                            'status_wo' => $row[array_search('STATUS WO', $header)] ?: null,
                            'no_do' => $row[array_search('NO DO', $header)] ?: null,
                            'status_do' => $row[array_search('STATUS DO', $header)] ?: null,
                            'tgl_st' => $parsedDates['tgl_st'],
                            'supir' => $row[array_search('SUPIR', $header)] ?: null,
                            'penerima' => $row[array_search('PENERIMA', $header)] ?: null,
                            'tgl_terima' => $parsedDates['tgl_terima'],
                        ];

                        if ($existingRecord) {
                            // Log update attempt
                            Log::info("Updating record: no_wo={$no_wo}, no_so={$no_so}");
                            // Update the existing record
                            $existingRecord->update($recordData);
                        } else {
                            // Log creation attempt
                            Log::info("Creating new record: no_wo={$no_wo}, no_so={$no_so}");
                            // Create a new record
                            HeatTreatment::create(array_merge([
                                'no_wo' => $no_wo,
                                'no_so' => $no_so
                            ], $recordData));
                        }
                    } else {
                        // Log row data issue
                        Log::warning("Row data does not have enough elements: " . json_encode($row));
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing row: " . json_encode($row) . " - " . $e->getMessage());
                }
            }
        });
    }
}

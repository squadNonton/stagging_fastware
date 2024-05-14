<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FormFPP;
use App\Models\Handling;
use App\Models\Mesin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function getRepairMaintenance(Request $request)
    {
        // Ambil parameter tahun dan bagian dari permintaan HTTP
        $selectedYear = $request->input('year', date('Y')); // Jika tidak ada parameter tahun, gunakan tahun saat ini sebagai default
        $selectedSection = $request->input('section', 'All');

        if ($selectedSection === 'All') {
            $summaryData2 = FormFPP::join('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                ->selectRaw('YEAR(form_f_p_p_s.created_at) as year,
                    MONTH(form_f_p_p_s.created_at) as month, SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_hour')
                ->whereYear('form_f_p_p_s.created_at', $selectedYear)
                ->where('form_f_p_p_s.status', 3)
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        } else {
            $summaryData2 = FormFPP::join('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                ->selectRaw('mesin.no_mesin, YEAR(form_f_p_p_s.created_at) as year,
                    MONTH(form_f_p_p_s.created_at) as month, SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_hour')
                ->whereYear('form_f_p_p_s.created_at', $selectedYear)
                ->where('form_f_p_p_s.section', $selectedSection)
                ->where('form_f_p_p_s.status', 3)
                ->groupBy('mesin.no_mesin', 'year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        }

        // Buat array lengkap dari label bulan
        $fullMonthLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // Inisialisasi array kosong untuk data
        $data2 = array_fill(0, 12, 0);

        // Iterasi melalui data yang diterima dari database
        foreach ($summaryData2 as $dataPoint) {
            $monthIndex = $dataPoint->month - 1; // Index bulan dimulai dari 0
            $data2[$monthIndex] += $dataPoint->total_hour; // Menambahkan total jam ke data yang sudah ada
        }

        // Kirim data sebagai respons JSON
        return response()->json(['labels' => $fullMonthLabels, 'data2' => $data2]);
    }

    public function getRepairAlatBantu(Request $request)
    {
        // Ambil parameter tahun dan bagian dari permintaan HTTP
        $selectedYear = $request->input('year', date('Y')); // Jika tidak ada parameter tahun, gunakan tahun saat ini sebagai default
        $selectedSection = $request->input('section', 'All');

        if ($selectedSection === 'All') {
            $summaryAlat = FormFPP::leftJoin('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                ->selectRaw('YEAR(form_f_p_p_s.created_at) as year,
                    MONTH(form_f_p_p_s.created_at) as month, SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_hour')
                ->whereYear('form_f_p_p_s.created_at', $selectedYear)
                ->where('form_f_p_p_s.status', 3)
                ->whereNull('mesin.no_mesin') // Hanya data yang tidak terkait dengan mesin
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        } else {
            $summaryAlat = FormFPP::leftJoin('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                ->selectRaw('mesin.no_mesin, YEAR(form_f_p_p_s.created_at) as year,
                    MONTH(form_f_p_p_s.created_at) as month, SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_hour')
                ->whereYear('form_f_p_p_s.created_at', $selectedYear)
                ->where('form_f_p_p_s.section', $selectedSection)
                ->where('form_f_p_p_s.status', 3)
                ->whereNull('mesin.no_mesin') // Hanya data yang tidak terkait dengan mesin
                ->groupBy('mesin.no_mesin', 'year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        }

        // Buat array lengkap dari label bulan
        $fullMonthLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // Inisialisasi array kosong untuk data
        $data2 = array_fill(0, 12, 0);

        // Iterasi melalui data yang diterima dari database
        foreach ($summaryAlat as $dataPoint) {
            $monthIndex = $dataPoint->month - 1; // Index bulan dimulai dari 0
            $data2[$monthIndex] += $dataPoint->total_hour; // Menambahkan total jam ke data yang sudah ada
        }

        // Kirim data sebagai respons JSON
        return response()->json(['labels' => $fullMonthLabels, 'data2' => $data2]);
    }


    public function getPeriodeWaktuPengerjaan(Request $request)
    {
        $selectedSection = $request->input('section', 'All');
        $startMonth = $request->input('start_month2');
        $endMonth = $request->input('end_month2');
        $selectedYear = $request->input('year', date('Y'));

        // Jika bagian yang dipilih adalah 'All' dan tidak ada tanggal mulai dan akhir yang diberikan
        if ($selectedSection === 'All' && empty($startMonth) && empty($endMonth)) {
            $periodeWaktuPengerjaan = FormFPP::join('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_minute')
                ->whereYear('form_f_p_p_s.created_at', $selectedYear)
                ->where('form_f_p_p_s.status', 3)
                ->first();
        } else {
            // Jika bagian yang dipilih adalah 'All' dan tanggal mulai dan akhir diberikan
            if ($selectedSection === 'All') {
                $periodeWaktuPengerjaan = FormFPP::join('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                    ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_minute')
                    ->whereBetween('form_f_p_p_s.created_at', [$startMonth, $endMonth])
                    ->where('form_f_p_p_s.status', 3)
                    ->first();
            } else {
                // Jika bagian yang dipilih bukan 'All' dan tanggal mulai dan akhir diberikan
                if (empty($startMonth) && empty($endMonth)) {
                    $periodeWaktuPengerjaan = FormFPP::join('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                        ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_minute')
                        ->whereYear('form_f_p_p_s.created_at', $selectedYear)
                        ->where('form_f_p_p_s.section', $selectedSection)
                        ->where('form_f_p_p_s.status', 3)
                        ->first();
                } else {
                    $periodeWaktuPengerjaan = FormFPP::join('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                        ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_minute')
                        ->where('form_f_p_p_s.section', $selectedSection)
                        ->whereBetween('form_f_p_p_s.created_at', [$startMonth, $endMonth])
                        ->where('form_f_p_p_s.status', 3)
                        ->first();
                }
            }
        }

        // Return data as JSON response
        return response()->json($periodeWaktuPengerjaan);
    }

    public function getPeriodeWaktuAlat(Request $request)
    {
        $selectedSection = $request->input('section', 'All');
        $startMonth = $request->input('start_month_alat');
        $endMonth = $request->input('end_month_alat');
        $selectedYear = $request->input('year', date('Y'));

        // Jika bagian yang dipilih adalah 'All' dan tidak ada tanggal mulai dan akhir yang diberikan
        if ($selectedSection === 'All' && empty($startMonth) && empty($endMonth)) {
            $periodeWaktuAlat = FormFPP::leftJoin('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_minute')
                ->whereYear('form_f_p_p_s.created_at', $selectedYear)
                ->where('form_f_p_p_s.status', 3)
                ->whereNull('mesin.no_mesin') // Hanya data yang tidak terkait dengan mesin
                ->first();
        } else {
            // Jika bagian yang dipilih adalah 'All' dan tanggal mulai dan akhir diberikan
            if ($selectedSection === 'All') {
                $periodeWaktuAlat = FormFPP::leftJoin('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                    ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_minute')
                    ->whereBetween('form_f_p_p_s.created_at', [$startMonth, $endMonth])
                    ->where('form_f_p_p_s.status', 3)
                    ->whereNull('mesin.no_mesin') // Hanya data yang tidak terkait dengan mesin
                    ->first();
            } else {
                // Jika bagian yang dipilih bukan 'All' dan tanggal mulai dan akhir diberikan
                if (empty($startMonth) && empty($endMonth)) {
                    $periodeWaktuAlat = FormFPP::leftJoin('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                        ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_minute')
                        ->whereYear('form_f_p_p_s.created_at', $selectedYear)
                        ->where('form_f_p_p_s.section', $selectedSection)
                        ->where('form_f_p_p_s.status', 3)
                        ->whereNull('mesin.no_mesin') // Hanya data yang tidak terkait dengan mesin
                        ->first();
                } else {
                    $periodeWaktuAlat = FormFPP::leftJoin('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                        ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) as total_minute')
                        ->where('form_f_p_p_s.section', $selectedSection)
                        ->whereBetween('form_f_p_p_s.created_at', [$startMonth, $endMonth])
                        ->where('form_f_p_p_s.status', 3)
                        ->whereNull('mesin.no_mesin') // Hanya data yang tidak terkait dengan mesin
                        ->first();
                }
            }
        }

        // Return data as JSON response
        return response()->json($periodeWaktuAlat);
    }

    public function getPeriodeMesin(Request $request)
    {
        $section = $request->input('section', 'All');
        $startDate = $request->input('start_mesin');
        $endDate = $request->input('end_mesin');

        // Memeriksa apakah opsi "All" dipilih
        if ($section === 'All' && empty($startDate) && empty($endDate)) {
            $periodeMesin = DB::table('mesin')
                ->leftJoin('form_f_p_p_s', function ($join) {
                    $join->on('mesin.no_mesin', '=', 'form_f_p_p_s.mesin')
                        ->where('form_f_p_p_s.status', 3)
                        ->whereYear('form_f_p_p_s.created_at', date('Y'));
                })
                ->select('mesin.no_mesin', 'mesin.section', DB::raw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) AS total_minutes'))
                ->orderByRaw("SUBSTRING(mesin.no_mesin FROM 1 FOR 1), CAST(REGEXP_SUBSTR(mesin.no_mesin, '[0-9]+') AS UNSIGNED)")
                ->whereIn('mesin.section', ['cutting', 'machining', 'heat treatment', 'machining custom'])
                ->groupBy('mesin.no_mesin', 'mesin.section')
                ->get();
        } else {
            // Jika section yang dipilih bukan 'All' dan tidak ada tanggal mulai dan akhir yang diberikan
            if (empty($startDate) && empty($endDate)) {
                $periodeMesin = DB::table('mesin')
                    ->leftJoin('form_f_p_p_s', function ($join) use ($section) {
                        $join->on('mesin.no_mesin', '=', 'form_f_p_p_s.mesin')
                            ->where('form_f_p_p_s.status', 3)
                            ->where('form_f_p_p_s.section', $section);
                    })
                    ->select('mesin.no_mesin', 'mesin.section', DB::raw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) AS total_minutes'))
                    ->where('mesin.section', $section)
                    ->orderByRaw("SUBSTRING(mesin.no_mesin FROM 1 FOR 1), CAST(REGEXP_SUBSTR(mesin.no_mesin, '[0-9]+') AS UNSIGNED)")
                    ->whereIn('mesin.section', ['cutting', 'machining', 'heat treatment', 'machining custom'])
                    ->groupBy('mesin.no_mesin', 'mesin.section')
                    ->get();
            } else {
                $startDate = Carbon::parse($startDate);
                $endDate = Carbon::parse($endDate);

                if ($section === 'All') {
                    $periodeMesin = DB::table('mesin')
                        ->leftJoin('form_f_p_p_s', function ($join) use ($startDate, $endDate) {
                            $join->on('mesin.no_mesin', '=', 'form_f_p_p_s.mesin')
                                ->where('form_f_p_p_s.status', 3)
                                ->whereBetween('form_f_p_p_s.created_at', [$startDate, $endDate]);
                        })
                        ->select('mesin.no_mesin', 'mesin.section', DB::raw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) AS total_minutes'))
                        ->orderByRaw("SUBSTRING(mesin.no_mesin FROM 1 FOR 1), CAST(REGEXP_SUBSTR(mesin.no_mesin, '[0-9]+') AS UNSIGNED)")
                        ->whereIn('mesin.section', ['cutting', 'machining', 'heat treatment', 'machining custom'])
                        ->groupBy('mesin.no_mesin', 'mesin.section')
                        ->get();
                } else {
                    $periodeMesin = DB::table('mesin')
                        ->leftJoin('form_f_p_p_s', function ($join) use ($section, $startDate, $endDate) {
                            $join->on('mesin.no_mesin', '=', 'form_f_p_p_s.mesin')
                                ->where('form_f_p_p_s.status', 3)
                                ->where('form_f_p_p_s.section', $section)
                                ->whereBetween('form_f_p_p_s.created_at', [$startDate, $endDate]);
                        })
                        ->select('mesin.no_mesin', 'mesin.section', DB::raw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) AS total_minutes'))
                        ->where('mesin.section', $section)
                        ->orderByRaw("SUBSTRING(mesin.no_mesin FROM 1 FOR 1), CAST(REGEXP_SUBSTR(mesin.no_mesin, '[0-9]+') AS UNSIGNED)")
                        ->whereIn('mesin.section', ['cutting', 'machining', 'heat treatment', 'machining custom'])
                        ->groupBy('mesin.no_mesin', 'mesin.section')
                        ->get();
                }
            }
        }

        return response()->json($periodeMesin);
    }

    public function getPeriodeAlat(Request $request)
    {
        $section_alat = $request->input('section_alat', 'All');
        $startDate_alat = $request->input('start_alat');
        $endDate_alat = $request->input('end_alat');

        if ($section_alat === 'All') {
            $periodeAlat = DB::table('form_f_p_p_s')
                ->leftJoin('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                ->whereNull('mesin.no_mesin')
                ->where('form_f_p_p_s.status', 3)
                ->whereYear('form_f_p_p_s.created_at', date('Y'));

            if (!empty($startDate_alat) && !empty($endDate_alat)) {
                $periodeAlat->whereBetween('form_f_p_p_s.created_at', [$startDate_alat, $endDate_alat]);
            }

            if ($section_alat !== 'All') {
                $periodeAlat->where('form_f_p_p_s.section', $section_alat);
            }

            $periodeAlat = $periodeAlat->whereIn('form_f_p_p_s.section', ['cutting', 'machining', 'heat treatment', 'machining custom'])
                ->select('form_f_p_p_s.mesin', 'form_f_p_p_s.section', DB::raw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) AS total_minutes_alat'))
                ->orderByRaw("SUBSTRING(form_f_p_p_s.mesin FROM 1 FOR 1), CAST(REGEXP_SUBSTR(form_f_p_p_s.mesin, '[0-9]+') AS UNSIGNED)")
                ->groupBy('form_f_p_p_s.mesin', 'form_f_p_p_s.section')
                ->get();
        } else {
            $query = DB::table('form_f_p_p_s')
                ->leftJoin('mesin', 'form_f_p_p_s.mesin', '=', 'mesin.no_mesin')
                ->where('form_f_p_p_s.status', 3)
                ->whereYear('form_f_p_p_s.created_at', date('Y'));

            if (!empty($startDate_alat) && !empty($endDate_alat)) {
                $query->whereBetween('form_f_p_p_s.created_at', [$startDate_alat, $endDate_alat]);
            }

            if ($section_alat !== 'All') {
                $query->where('form_f_p_p_s.section', $section_alat);
            }

            $periodeAlat = $query->whereIn('form_f_p_p_s.section', ['cutting', 'machining', 'heat treatment', 'machining custom'])
                ->whereNull('mesin.no_mesin')
                ->select('form_f_p_p_s.mesin', 'form_f_p_p_s.section', DB::raw('SUM(TIMESTAMPDIFF(SECOND, form_f_p_p_s.created_at, form_f_p_p_s.updated_at) / 60) AS total_minutes_alat'))
                ->orderByRaw("SUBSTRING(form_f_p_p_s.mesin FROM 1 FOR 1), CAST(REGEXP_SUBSTR(form_f_p_p_s.mesin, '[0-9]+') AS UNSIGNED)")
                ->groupBy('form_f_p_p_s.mesin', 'form_f_p_p_s.section')
                ->get();
        }

        return response()->json($periodeAlat);
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\SafetyExport;
use App\Models\SafetyPatrol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SafetyController extends Controller
{
    public function listSafetyPatrol()
    {
        $patrols = SafetyPatrol::orderBy('updated_at', 'desc')->get();

        return view('safety_patrol.list', compact('patrols'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function listSafetyPatrolPIC()
    {
        $patrols = SafetyPatrol::orderBy('updated_at', 'desc')->get();

        return view('safety_patrol.piclist', compact('patrols'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function reportPatrol()
    {
        $patrols = SafetyPatrol::orderBy('updated_at', 'desc')->get();

        return view('safety_patrol.report', compact('patrols'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function getAreaPatrol()
    {
        // Query untuk menghitung total entri per PIC Area
        $areaPatrolsData = SafetyPatrol::select('area_patrol', DB::raw('COUNT(*) as area_patrols'))
            ->groupBy('area_patrol')
            ->get();

        // Siapkan data untuk digunakan oleh HighchartsJS
        $labels = $areaPatrolsData->pluck('area_patrol');
        $areaPatrols = $areaPatrolsData->pluck('area_patrols');

        // Kirim data sebagai respons JSON
        return response()->json([
            'labels' => $labels,
            'area_patrols' => $areaPatrols
        ]);
    }

    public function getKategoriPatrol()
    {
        // Define category labels
        $labels = [
            'Kelengkapan Alat 5S / 5R' => 'kategori_1',
            'Kerapihan Area Kerja' => 'kategori_2',
            'Kondisi Lingkungan Kerja' => 'kategori_3',
            'Penempatan Alat / Barang' => 'kategori_4',
            'Praktik 5S / 5R Oleh Pekerja' => 'kategori_5'
        ];

        // Query to get category counts per area
        $kategoriCounts = [];

        // Loop through labels to get counts for each label
        foreach ($labels as $label => $kategori) {
            $counts = [];
            for ($i = 1; $i <= 5; $i++) {
                $counts["kategori_$i"] = SafetyPatrol::where($kategori, $i)->count();
            }
            $kategoriCounts[$label] = $counts;
        }

        return response()->json([
            'kategori_counts' => $kategoriCounts
        ]);
    }

    public function getSafetyPatrol()
    {
        // Define category labels
        $labels = [
            'Checksheet APAR' => 'safety_1',
            'Penggunaan APD' => 'safety_2',
            'Potensi Bahaya' => 'safety_3',
            'Pemeliharaan APD' => 'safety_4',
            'Kelengkapan APD' => 'safety_5'
        ];

        // Query to get category counts per area
        $safetyCounts = [];

        // Loop through labels to get counts for each label
        foreach ($labels as $label => $safety) {
            $counts = [];
            for ($i = 1; $i <= 5; $i++) {
                $counts["safety_$i"] = SafetyPatrol::where($safety, $i)->count();
            }
            $safetyCounts[$label] = $counts;
        }

        return response()->json([
            'safety_counts' => $safetyCounts
        ]);
    }

    public function getLingkunganPatrol()
    {
        // Define category labels
        $labels = [
            'Pengelolaan Jenis & Kriteria Limbah' => 'lingkungan_1',
            'Kebersihan Lingkungan' => 'lingkungan_2',
            'Penyimpanan Limbah' => 'lingkungan_3',
            'Tempat Sampah' => 'lingkungan_4',
        ];

        // Query to get category counts per area
        $lingkunganCounts = [];

        // Loop through labels to get counts for each label
        foreach ($labels as $label => $lingkungan) {
            $counts = [];
            for ($i = 1; $i <= 5; $i++) {
                $counts["safety_$i"] = SafetyPatrol::where($lingkungan, $i)->count();
            }
            $lingkunganCounts[$label] = $counts;
        }

        return response()->json([
            'lingkungan_counts' => $lingkunganCounts
        ]);
    }

    public function getPICArea()
    {
        // Query untuk menghitung total entri per PIC Area
        $picAreasData = SafetyPatrol::select('pic_area', DB::raw('COUNT(*) as total_entries'))
            ->groupBy('pic_area')
            ->get();

        // Siapkan data untuk digunakan oleh HighchartsJS
        $labels = $picAreasData->pluck('pic_area');
        $totalEntries = $picAreasData->pluck('total_entries');

        // Query untuk menghitung total form berdasarkan area patrol
        $areaPatrolData = SafetyPatrol::select('pic_area', 'area_patrol', DB::raw('COUNT(*) as total_forms'))
            ->groupBy('pic_area', 'area_patrol')
            ->get()
            ->groupBy('pic_area')
            ->map(function ($item) {
                return $item->pluck('total_forms', 'area_patrol');
            });

        // Kirim data sebagai respons JSON
        return response()->json([
            'labels' => $labels,
            'total_entries' => $totalEntries,
            'area_patrol_data' => $areaPatrolData
        ]);
    }


    public function buatFormSafety()
    {
        return view('safety_patrol.form');
    }

    public function detailPatrol(SafetyPatrol $patrol)
    {
        return view('safety_patrol.detail', compact('patrol'));
    }

    public function simpanPatrol(Request $request)
    {
        // Simpan data mesin beserta path foto dan sparepart ke database
        SafetyPatrol::create([
            'tanggal_patrol' => $request->tanggal_patrol,
            'area_patrol' => $request->area_patrol,
            'pic_area' => $request->pic_area,
            'petugas_patrol' => $request->petugas_patrol,
            'kategori_1' => $request->kategori_1,
            'kategori_2' => $request->kategori_2,
            'kategori_3' => $request->kategori_3,
            'kategori_4' => $request->kategori_4,
            'kategori_5' => $request->kategori_5,
            'kategori_catatan' => $request->kategori_catatan,
            'safety_1' => $request->safety_1,
            'safety_2' => $request->safety_2,
            'safety_3' => $request->safety_3,
            'safety_4' => $request->safety_4,
            'safety_5' => $request->safety_5,
            'safety_catatan' => $request->safety_catatan,
            'lingkungan_1' => $request->lingkungan_1,
            'lingkungan_2' => $request->lingkungan_2,
            'lingkungan_3' => $request->lingkungan_3,
            'lingkungan_4' => $request->lingkungan_4,
            'lingkungan_catatan' => $request->lingkungan_catatan,
        ]);

        return redirect()->route('listpatrol');
    }

    public function exportPatrol()
    {
        // Set locale for Carbon
        setlocale(LC_TIME, 'en_US.UTF-8');

        // Format date as "28 May 2020"
        $date = Carbon::now()->formatLocalized('%d %B %Y');
        $filename = "safety-patrol-{$date}.xlsx";

        return Excel::download(new SafetyExport, $filename);
    }
}

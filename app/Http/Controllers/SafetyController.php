<?php

namespace App\Http\Controllers;

use App\Models\SafetyPatrol;
use Illuminate\Http\Request;

class SafetyController extends Controller
{
    public function listSafetyPatrol()
    {
        $patrols = SafetyPatrol::orderBy('updated_at', 'desc')->get();

        return view('safety_patrol.list', compact('patrols'))->with('i', (request()->input('page', 1) - 1) * 5);
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
}

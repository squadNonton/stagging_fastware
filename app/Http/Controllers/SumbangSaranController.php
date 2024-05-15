<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\SumbangSaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// import Facade "Storage"

class SumbangSaranController extends Controller
{
    //
    // showpage
    public function showSS()
    {
        $data = SumbangSaran::with('users')
    ->whereIn('sumbang_sarans.status', [1, 2, 3]) // Tambahkan alias untuk kolom status
    ->orderByRaw('FIELD(sumbang_sarans.status, 3, 2, 1)') // Tambahkan alias untuk kolom status
    ->orderByDesc('sumbang_sarans.created_at') // Tambahkan alias untuk kolom created_at
    ->paginate();

        // Ambil hanya id user untuk menghindari "N + 1" query
        $userIds = $data->pluck('id_user')->unique()->toArray();

        // Ambil data peran (role) berdasarkan user ids
        $usersRoles = User::whereIn('users.id', $userIds)
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->pluck('roles.role', 'users.id');

        return view('ss.createSS', compact('data', 'usersRoles'));
    }

    public function simpanSS(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $request->validate([
            'tgl_pengajuan_ide' => 'required|date',
            'lokasi_ide' => 'required|string',
            'tgl_diterapkan' => 'nullable|date',
            'judul' => 'required|string',
            'keadaan_sebelumnya' => 'required|string',
            'usulan_ide' => 'required|string',
        ]);

        // Simpan data
        $sumbangSaran = new SumbangSaran();
        $sumbangSaran->id_user = $request->user()->id;
        $sumbangSaran->tgl_pengajuan_ide = $request->tgl_pengajuan_ide;
        $sumbangSaran->lokasi_ide = $request->lokasi_ide;
        $sumbangSaran->tgl_diterapkan = $request->tgl_diterapkan;
        $sumbangSaran->judul = $request->judul;
        $sumbangSaran->keadaan_sebelumnya = $request->keadaan_sebelumnya;
        $sumbangSaran->usulan_ide = $request->usulan_ide;
        $sumbangSaran->keuntungan_ide = $request->keuntungan_ide;
        $sumbangSaran->status = 1;

        // Simpan gambar pertama
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->hashName();
            $imageOriginalName = $image->getClientOriginalName();
            $image->move(public_path('assets/image'), $imagePath);
            $sumbangSaran->image = $imagePath;
            $sumbangSaran->file_name = $imageOriginalName;
        }

        // Simpan gambar kedua
        if ($request->hasFile('image_2')) {
            $image2 = $request->file('image_2');
            $imagePath2 = $image2->hashName();
            $imageOriginalName2 = $image2->getClientOriginalName();
            $image2->move(public_path('assets/image'), $imagePath2);
            $sumbangSaran->image_2 = $imagePath2;
            $sumbangSaran->file_name_2 = $imageOriginalName2;
        }

        $sumbangSaran->save();

        // Anda dapat mengembalikan respons JSON jika Anda menginginkannya
        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }

    public function editSS($id)
    {
        // Retrieve the sumbang saran data by ID
        $sumbangSaran = SumbangSaran::findOrFail($id);

        // Include file paths in the response if available
        $sumbangSaran->edit_image_url = $sumbangSaran->image ? asset('assets/image/'.$sumbangSaran->image) : null;
        $sumbangSaran->edit_image_2_url = $sumbangSaran->image_2 ? asset('assets/image/'.$sumbangSaran->image_2) : null;

        // Send data to the view to be displayed in the edit modal
        return response()->json($sumbangSaran);
    }

    public function getSumbangSaran($id)
    {
        // Retrieve the sumbang saran data by ID
        $sumbangSaran = SumbangSaran::findOrFail($id);

        // // Include file paths in the response if available
        // $sumbangSaran->edit_image_url = $sumbangSaran->image ? asset('assets/image/'.$sumbangSaran->image) : null;
        // $sumbangSaran->edit_image_2_url = $sumbangSaran->image_2 ? asset('assets/image/'.$sumbangSaran->image_2) : null;

        // Send data to the view to be displayed in the edit modal
        return response()->json($sumbangSaran);
    }

    public function updateSS(Request $request)
    {
        $id = $request->input('id');
        $sumbangSaran = SumbangSaran::findOrFail($id);

        // Handle the first image upload
        if ($request->hasFile('edit_image')) {
            $image = $request->file('edit_image');
            $imagePath = $image->hashName(); // Get hashed filename for storage
            $imageOriginalName = $image->getClientOriginalName(); // Get original filename
            $image->move(public_path('assets/image'), $imagePath);
            $sumbangSaran->image = $imagePath;
            $sumbangSaran->file_name = $imageOriginalName;
        } elseif ($request->input('edit_image_url')) {
            // Use the existing file if no new file is uploaded
            $sumbangSaran->image = basename($request->input('edit_image_url'));
            $sumbangSaran->file_name = basename($request->input('edit_image_url'));
        }

        // Handle the second image upload
        if ($request->hasFile('edit_image_2')) {
            $image2 = $request->file('edit_image_2');
            $imagePath2 = $image2->hashName(); // Get hashed filename for storage
            $imageOriginalName2 = $image2->getClientOriginalName(); // Get original filename
            $image2->move(public_path('assets/image'), $imagePath2);
            $sumbangSaran->image_2 = $imagePath2;
            $sumbangSaran->file_name_2 = $imageOriginalName2;
        } elseif ($request->input('edit_image_2_url')) {
            // Use the existing file if no new file is uploaded
            $sumbangSaran->image_2 = basename($request->input('edit_image_2_url'));
            $sumbangSaran->file_name_2 = basename($request->input('edit_image_2_url'));
        }

        // Save other fields as usual
        $sumbangSaran->tgl_pengajuan_ide = $request->input('tgl_pengajuan_ide');
        $sumbangSaran->lokasi_ide = $request->input('lokasi_ide');
        $sumbangSaran->tgl_diterapkan = $request->input('tgl_diterapkan');
        $sumbangSaran->judul = $request->input('judul');
        $sumbangSaran->keadaan_sebelumnya = $request->input('keadaan_sebelumnya');
        $sumbangSaran->usulan_ide = $request->input('usulan_ide');
        $sumbangSaran->keuntungan_ide = $request->input('keuntungan_ide');

        $sumbangSaran->save();

        // Return a success response
        return response()->json(['success' => 'Data updated successfully']);
    }

    public function deleteSS($id)
    {
        // Temukan data berdasarkan ID
        $sumbangSaran = SumbangSaran::findOrFail($id);

        // Ubah status menjadi 0
        $sumbangSaran->status = 0;

        // Simpan perubahan
        $sumbangSaran->save();

        // Kembalikan respons JSON
        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}

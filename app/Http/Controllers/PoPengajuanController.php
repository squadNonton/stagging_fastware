<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\MstPoPengajuan;
use App\Models\TrsPoPengajuan;

class PoPengajuanController extends Controller
{
    //
    // Method untuk menampilkan data ke view
    public function indexPoPengajuan()
    {

        // Mendapatkan nama user yang sedang login
        $loggedInUserName = auth()->user()->name;

        $data = MstPoPengajuan::where('mst_po_pengajuans.modified_at', $loggedInUserName) // Filter berdasarkan modified_at
            ->whereIn('mst_po_pengajuans.id', function ($query) {
                $query->select(DB::raw('MAX(id)')) // Ambil id maksimal (yang terbaru)
                    ->from('mst_po_pengajuans') // Dari mst_po_pengajuans
                    ->groupBy('no_fpb'); // Kelompokkan berdasarkan no_fpb
            })
            ->leftJoin('trs_po_pengajuans as trs', 'trs.id_fpb', '=', 'mst_po_pengajuans.id') // Menggunakan LEFT JOIN agar data mst_po_pengajuans tetap ditampilkan
            ->select(
                'mst_po_pengajuans.no_fpb',
                DB::raw('MAX(mst_po_pengajuans.id) as id'),
                DB::raw('MAX(mst_po_pengajuans.modified_at) as modified_at'), // Ambil nilai modified_at terbaru
                DB::raw('MAX(mst_po_pengajuans.kategori_po) as kategori_po'), // Ambil kategori_po terbaru
                DB::raw('MAX(mst_po_pengajuans.catatan) as catatan'), // Ambil catatan_po terbaru
                DB::raw('MAX(mst_po_pengajuans.status_1) as status_1'),
                DB::raw('MAX(mst_po_pengajuans.status_2) as status_2'),
                DB::raw('COALESCE(MAX(trs.updated_at), "-") as trs_updated_at') // COALESCE untuk menangani null pada trs_po_pengajuans
            )
            ->groupBy('mst_po_pengajuans.no_fpb') // Kelompokkan berdasarkan no_fpb untuk mendapatkan data unik
            ->orderBy(DB::raw('MAX(mst_po_pengajuans.id)'), 'desc') // Urutkan berdasarkan id maksimal secara descending
            ->get();


        // Mengirim data ke view
        return view('po_pengajuan.index_po_pengajuan', compact('data'));
    }

    public function indexPoDeptHead()
    {
        // Mendapatkan role_id dari pengguna yang sedang login
        $roleId = auth()->user()->role_id;

        // Array mapping antara role_id dan nama yang diizinkan untuk ditampilkan
        $allowedNames = [];

        // Logika pemilihan nama berdasarkan role_id
        if ($roleId == 11) {
            $allowedNames = ['JESSICA PAUNE', 'SITI MARIA ULFA', 'MUHAMMAD DINAR FARISI'];
        } elseif ($roleId == 5) {
            $allowedNames = ['MUGI PRAMONO', 'ABDUR RAHMAN AL FAAIZ', 'RAGIL ISHA RAHMANTO'];
        } elseif ($roleId == 2) {
            $allowedNames = ['ILHAM CHOLID', 'JUN JOHAMIN PD', 'HERY HERMAWAN', 'WULYO EKO PRASETYO', 'SENDY PRABOWO', 'YAN WELEM MANGINSELA', 'HEXAPA DARMADI', 'SARAH EGA BUDI ASTUTI', 'SONY STIAWAN', 'DIMAS ADITYA PRIANDANA', 'DANIA ISNAWATI', 'FRISILIA CLAUDIA HUTAMA', 'DWI KUNTORO', 'YUNASIS PALGUNADI', 'RISFAN FAISAL'];
        } elseif ($roleId == 7) {
            $allowedNames = ['RANGGA FADILLAH'];
        }

        // Mengambil data dari model MstPoPengajuan berdasarkan role_id dan nama yang diperbolehkan
        if (!empty($allowedNames)) {
            // Mengambil data no_fpb unik dengan updated_at terbaru menggunakan subquery
            $data = MstPoPengajuan::whereIn('mst_po_pengajuans.modified_at', $allowedNames) // Filter berdasarkan nama yang diizinkan
                ->whereIn('mst_po_pengajuans.id', function ($query) {
                    $query->select(DB::raw('MAX(id)')) // Ambil id maksimal (yang terbaru)
                        ->from('mst_po_pengajuans')
                        ->groupBy('no_fpb'); // Kelompokkan berdasarkan no_fpb
                })
                ->join('trs_po_pengajuans as trs', 'trs.id_fpb', '=', 'mst_po_pengajuans.id') // Join dengan TrsPoPengajuan untuk mendapatkan updated_at
                ->select(
                    'mst_po_pengajuans.no_fpb',
                    DB::raw('MAX(mst_po_pengajuans.id) as id'),
                    DB::raw('MAX(mst_po_pengajuans.modified_at) as modified_at'), // Ambil nilai modified_at terbaru
                    DB::raw('MAX(mst_po_pengajuans.kategori_po) as kategori_po'), // Ambil kategori_po terbaru
                    DB::raw('MAX(mst_po_pengajuans.catatan) as catatan_po'), // Ambil catatan_po terbaru
                    DB::raw('MAX(mst_po_pengajuans.status_1) as status_1'),
                    DB::raw('MAX(mst_po_pengajuans.status_2) as status_2'),
                    DB::raw('MAX(trs.updated_at) as trs_updated_at') // Ambil updated_at terbaru dari trs_po_pengajuans
                )
                ->groupBy('mst_po_pengajuans.no_fpb') // Kelompokkan berdasarkan no_fpb untuk mendapatkan data unik
                ->orderBy('mst_po_pengajuans.no_fpb', 'desc') // Urutkan berdasarkan updated_at terbaru
                ->get();
        } else {
            // Jika role_id tidak sesuai dengan yang ditentukan, return data kosong atau handle sesuai kebutuhan
            $data = collect(); // atau bisa return redirect dengan pesan error
        }


        // Mengirim data ke view
        return view('po_pengajuan.index_po_deptHead', compact('data'));
    }

    public function indexPoUser()
    {
        // Mendapatkan role_id dari pengguna yang sedang login
        $roleId = auth()->user()->role_id;

        // Array untuk menyimpan kategori yang diperbolehkan untuk ditampilkan
        $allowedCategories = [];

        // Logika pemilihan kategori_po berdasarkan role_id
        if (in_array($roleId, [50, 30])) {
            // Untuk role_id 50 & 30, hanya menampilkan kategori Consumable, Subcont, Spareparts
            $allowedCategories = ['Consumable', 'Subcont', 'Spareparts'];
        } elseif (in_array($roleId, [40, 14])) {
            // Untuk role_id 40 & 14, hanya menampilkan kategori IT
            $allowedCategories = ['IT'];
        } elseif (in_array($roleId, [39, 14])) {
            // Untuk role_id 39 & 14, hanya menampilkan kategori GA
            $allowedCategories = ['GA'];
        }

        // Mengambil data dari model MstPoPengajuan berdasarkan role_id dan kategori_po yang diperbolehkan
        if (!empty($allowedCategories)) {
            // Mengambil data dengan filter kategori PO yang diizinkan
            $data = MstPoPengajuan::whereIn('mst_po_pengajuans.kategori_po', $allowedCategories)
                ->whereIn('mst_po_pengajuans.id', function ($query) {
                    $query->select(DB::raw('MAX(id)')) // Ambil id maksimal (yang terbaru)
                        ->from('mst_po_pengajuans') // Dari mst_po_pengajuans
                        ->groupBy('no_fpb'); // Kelompokkan berdasarkan no_fpb
                })
                ->leftJoin('trs_po_pengajuans as trs', 'trs.id_fpb', '=', 'mst_po_pengajuans.id') // LEFT JOIN agar tetap ambil mst_po_pengajuans meskipun trs kosong
                ->select(
                    'mst_po_pengajuans.no_fpb',
                    DB::raw('MAX(mst_po_pengajuans.id) as id'),
                    DB::raw('MAX(mst_po_pengajuans.no_po) as no_po'), // Ambil no_po terbaru
                    DB::raw('MAX(mst_po_pengajuans.kategori_po) as kategori_po'), // Ambil kategori_po terbaru
                    DB::raw('MAX(mst_po_pengajuans.nama_barang) as nama_barang'), // Ambil nama_barang terbaru
                    DB::raw('MAX(mst_po_pengajuans.qty) as qty'), // Ambil qty terbaru
                    DB::raw('MAX(mst_po_pengajuans.pcs) as pcs'), // Ambil pcs terbaru
                    DB::raw('MAX(mst_po_pengajuans.price_list) as price_list'), // Ambil price_list terbaru
                    DB::raw('MAX(mst_po_pengajuans.total_harga) as total_harga'), // Ambil total_harga terbaru
                    DB::raw('MAX(mst_po_pengajuans.spesifikasi) as spesifikasi'), // Ambil spesifikasi terbaru
                    DB::raw('MAX(mst_po_pengajuans.file) as file'), // Ambil file terbaru
                    DB::raw('MAX(mst_po_pengajuans.file_name) as file_name'), // Ambil file_name terbaru
                    DB::raw('MAX(mst_po_pengajuans.amount) as amount'), // Ambil amount terbaru
                    DB::raw('MAX(mst_po_pengajuans.rekomendasi) as rekomendasi'), // Ambil rekomendasi terbaru
                    DB::raw('MAX(mst_po_pengajuans.due_date) as due_date'), // Ambil due_date terbaru
                    DB::raw('MAX(mst_po_pengajuans.target_cost) as target_cost'), // Ambil target_cost terbaru
                    DB::raw('MAX(mst_po_pengajuans.lead_time) as lead_time'), // Ambil lead_time terbaru
                    DB::raw('MAX(mst_po_pengajuans.nama_customer) as nama_customer'), // Ambil nama_customer terbaru
                    DB::raw('MAX(mst_po_pengajuans.nama_project) as nama_project'), // Ambil nama_project terbaru
                    DB::raw('MAX(mst_po_pengajuans.catatan) as catatan'), // Ambil catatan terbaru
                    DB::raw('MAX(mst_po_pengajuans.status_1) as status_1'), // Ambil status_1 terbaru
                    DB::raw('MAX(mst_po_pengajuans.status_2) as status_2'), // Ambil status_2 terbaru
                    DB::raw('MAX(mst_po_pengajuans.created_at) as created_at'), // Ambil created_at terbaru
                    DB::raw('MAX(mst_po_pengajuans.modified_at) as modified_at'), // Ambil modified_at terbaru
                    DB::raw('COALESCE(MAX(trs.updated_at), "-") as trs_updated_at') // Gunakan COALESCE untuk menangani null pada trs_po_pengajuans
                )
                ->groupBy('mst_po_pengajuans.no_fpb') // Kelompokkan berdasarkan no_fpb untuk mendapatkan data unik
                ->orderBy(DB::raw('MAX(mst_po_pengajuans.id)'), 'desc') // Urutkan berdasarkan id maksimal secara descending
                ->get();

            // Jika terdapat banyak entri untuk no_fpb yang sama, ambil hanya yang pertama
            $data = $data->unique('no_fpb'); // Ambil hanya entri unik berdasarkan no_fpb
        } else {
            // Jika role_id tidak sesuai dengan yang ditentukan, return data kosong atau handle sesuai kebutuhan
            $data = collect(); // atau bisa return redirect dengan pesan error
        }


        // Mengirim data ke view
        return view('po_pengajuan.index_po_user', compact('data'));
    }

    public function indexPoFinance()
    {
        // Mengambil data dari MstPoPengajuan dan TrsPoPengajuan
        $data = MstPoPengajuan::join('trs_po_pengajuans as trs', 'trs.id_fpb', '=', 'mst_po_pengajuans.id')
            ->select(
                'mst_po_pengajuans.no_fpb',
                DB::raw('MAX(mst_po_pengajuans.id) as id'), // Ambil ID maksimal dari MstPoPengajuan
                DB::raw('MAX(mst_po_pengajuans.no_po) as no_po'),
                DB::raw('MAX(mst_po_pengajuans.kategori_po) as kategori_po'),
                DB::raw('MAX(mst_po_pengajuans.nama_barang) as nama_barang'),
                DB::raw('MAX(mst_po_pengajuans.qty) as qty'),
                DB::raw('MAX(mst_po_pengajuans.pcs) as pcs'),
                DB::raw('MAX(mst_po_pengajuans.price_list) as price_list'),
                DB::raw('MAX(mst_po_pengajuans.total_harga) as total_harga'),
                DB::raw('MAX(mst_po_pengajuans.spesifikasi) as spesifikasi'),
                DB::raw('MAX(mst_po_pengajuans.file) as file'),
                DB::raw('MAX(mst_po_pengajuans.file_name) as file_name'),
                DB::raw('MAX(mst_po_pengajuans.amount) as amount'),
                DB::raw('MAX(mst_po_pengajuans.rekomendasi) as rekomendasi'),
                DB::raw('MAX(mst_po_pengajuans.due_date) as due_date'),
                DB::raw('MAX(mst_po_pengajuans.target_cost) as target_cost'),
                DB::raw('MAX(mst_po_pengajuans.lead_time) as lead_time'),
                DB::raw('MAX(mst_po_pengajuans.nama_customer) as nama_customer'),
                DB::raw('MAX(mst_po_pengajuans.nama_project) as nama_project'),
                DB::raw('MAX(mst_po_pengajuans.catatan) as catatan'),
                DB::raw('MAX(mst_po_pengajuans.status_1) as status_1'),
                DB::raw('MAX(mst_po_pengajuans.status_2) as status_2'),
                DB::raw('MAX(mst_po_pengajuans.created_at) as created_at'),
                DB::raw('MAX(mst_po_pengajuans.modified_at) as modified_at'),
                DB::raw('MAX(trs.updated_at) as trs_updated_at') // Ambil updated_at terbaru dari TrsPoPengajuan
            )
            ->groupBy('mst_po_pengajuans.no_fpb')
            ->orderBy('mst_po_pengajuans.no_fpb', 'desc')  // Kelompokkan berdasarkan no_fpb untuk mendapatkan data unik
            ->get();

        // Mengirim data ke view
        return view('po_pengajuan.index_po_finance', compact('data'));
    }

    public function indexPoProcurement()
    {
        // Mengambil data dari MstPoPengajuan dan TrsPoPengajuan
        $data = MstPoPengajuan::join('trs_po_pengajuans as trs', 'trs.id_fpb', '=', 'mst_po_pengajuans.id')
            ->select(
                'mst_po_pengajuans.no_fpb',
                DB::raw('MAX(mst_po_pengajuans.id) as id'), // Ambil ID maksimal dari MstPoPengajuan
                DB::raw('MAX(mst_po_pengajuans.no_po) as no_po'),
                DB::raw('MAX(mst_po_pengajuans.kategori_po) as kategori_po'),
                DB::raw('MAX(mst_po_pengajuans.nama_barang) as nama_barang'),
                DB::raw('MAX(mst_po_pengajuans.qty) as qty'),
                DB::raw('MAX(mst_po_pengajuans.pcs) as pcs'),
                DB::raw('MAX(mst_po_pengajuans.price_list) as price_list'),
                DB::raw('MAX(mst_po_pengajuans.total_harga) as total_harga'),
                DB::raw('MAX(mst_po_pengajuans.spesifikasi) as spesifikasi'),
                DB::raw('MAX(mst_po_pengajuans.file) as file'),
                DB::raw('MAX(mst_po_pengajuans.file_name) as file_name'),
                DB::raw('MAX(mst_po_pengajuans.amount) as amount'),
                DB::raw('MAX(mst_po_pengajuans.rekomendasi) as rekomendasi'),
                DB::raw('MAX(mst_po_pengajuans.due_date) as due_date'),
                DB::raw('MAX(mst_po_pengajuans.target_cost) as target_cost'),
                DB::raw('MAX(mst_po_pengajuans.lead_time) as lead_time'),
                DB::raw('MAX(mst_po_pengajuans.nama_customer) as nama_customer'),
                DB::raw('MAX(mst_po_pengajuans.nama_project) as nama_project'),
                DB::raw('MAX(mst_po_pengajuans.catatan) as catatan'),
                DB::raw('MAX(mst_po_pengajuans.status_1) as status_1'),
                DB::raw('MAX(mst_po_pengajuans.status_2) as status_2'),
                DB::raw('MAX(mst_po_pengajuans.created_at) as created_at'),
                DB::raw('MAX(mst_po_pengajuans.modified_at) as modified_at'),
                DB::raw('MAX(trs.updated_at) as trs_updated_at') // Ambil updated_at terbaru dari TrsPoPengajuan
            )
            ->groupBy('mst_po_pengajuans.no_fpb')
            ->orderBy('mst_po_pengajuans.no_fpb', 'desc')  // Kelompokkan berdasarkan no_fpb untuk mendapatkan data unik
            ->get();

        // Mencari data yang memiliki catatan "Terdapat Reject Item"
        $pengajuanCancel = $data->filter(function ($item) {
            return strpos($item->catatan, 'Terdapat Reject Item') !== false;
        })->pluck('no_fpb')->toArray(); // Mengambil no_fpb untuk data yang memiliki catatan "Cancel Item"

        // Mencari pengajuan terbaru dengan status_1 = 5
        $pengajuanTerbaru = $data->firstWhere('status_1', 5); // Mencari pengajuan terbaru dari data yang sudah diambil
        $noFpbTerbaru = $pengajuanTerbaru ? $pengajuanTerbaru->no_fpb : null;

        // Mengirim data ke view
        return view('po_pengajuan.index_po_procurment', compact('data', 'pengajuanCancel', 'noFpbTerbaru'));
    }

    public function indexPoProcurement2()
    {
        // Mengambil data dari MstPoPengajuan dengan join ke TrsPoPengajuan
        $data = MstPoPengajuan::join('trs_po_pengajuans as trs', 'trs.id_fpb', '=', 'mst_po_pengajuans.id')
            ->select(
                'mst_po_pengajuans.no_fpb',
                'mst_po_pengajuans.id',
                'mst_po_pengajuans.no_po',
                'mst_po_pengajuans.kategori_po',
                'mst_po_pengajuans.nama_barang',
                'mst_po_pengajuans.qty',
                'mst_po_pengajuans.pcs',
                'mst_po_pengajuans.price_list',
                'mst_po_pengajuans.total_harga',
                'mst_po_pengajuans.spesifikasi',
                'mst_po_pengajuans.file',
                'mst_po_pengajuans.file_name',
                'mst_po_pengajuans.amount',
                'mst_po_pengajuans.rekomendasi',
                'mst_po_pengajuans.due_date',
                'mst_po_pengajuans.target_cost',
                'mst_po_pengajuans.lead_time',
                'mst_po_pengajuans.nama_customer',
                'mst_po_pengajuans.nama_project',
                'mst_po_pengajuans.catatan',
                'mst_po_pengajuans.status_1',
                'mst_po_pengajuans.status_2',
                'mst_po_pengajuans.created_at',
                'mst_po_pengajuans.updated_at',
                'mst_po_pengajuans.modified_at',
                'trs.updated_at as trs_updated_at' // Ambil updated_at dari TrsPoPengajuan
            )
            ->orderBy('mst_po_pengajuans.no_fpb', 'desc')
            ->get();

        // Mencari data yang memiliki catatan "Terdapat Reject Item" dan status_1 != 9
        $pengajuanCancel = $data->filter(function ($item) {
            return strpos($item->catatan, 'Terdapat Reject Item') !== false && $item->status_1 != 9;
        })->pluck('no_fpb')->toArray(); // Mengambil no_fpb untuk data yang memiliki catatan "Cancel Item"

        // Mencari pengajuan terbaru dengan status_1 = 5 dari data yang sudah diambil
        $pengajuanTerbaru = $data->firstWhere('status_1', 5);
        $noFpbTerbaru = $pengajuanTerbaru ? $pengajuanTerbaru->no_fpb : null;

        // Mengirim data ke view
        return view('po_pengajuan.index_po_procurment', compact('data', 'pengajuanCancel', 'noFpbTerbaru'));
    }


    public function showFPBForm($id)
    {
        // Mengambil data berdasarkan id
        $poPengajuan = MstPoPengajuan::find($id);

        if (!$poPengajuan) {
            return abort(404); // Jika data dengan ID tersebut tidak ditemukan
        }

        // Mengambil semua data berdasarkan no_fpb yang sama
        $mstPoPengajuans = MstPoPengajuan::where('no_fpb', $poPengajuan->no_fpb)->get();

        // Tentukan Dept. Head berdasarkan nilai modified_at
        $deptHead = '';
        if (in_array($poPengajuan->modified_at, ['JESSICA PAUNE', 'SITI MARIA ULFA', 'MUHAMMAD DINAR FARISI'])) {
            $deptHead = 'MARTINUS CAHYO RAHASTO';
        } elseif (in_array($poPengajuan->modified_at, ['MUGI PRAMONO', 'ABDUR RAHMAN AL FAAIZ', 'RAGIL ISHA RAHMANTO'])) {
            $deptHead = 'ARY RODJO PRASETYO';
        } elseif (in_array($poPengajuan->modified_at, ['ILHAM CHOLID', 'JUN JOHAMIN PD', 'HERY HERMAWAN', 'WULYO EKO PRASETYO', 'SENDY PRABOWO', 'YAN WELEM MANGINSELA', 'HEXAPA DARMADI', 'SARAH EGA BUDI ASTUTI', 'SONY STIAWAN', 'DIMAS ADITYA PRIANDANA'])) {
            $deptHead = 'YULMAI RIDO WINANDA';
        } elseif (in_array($poPengajuan->modified_at, ['DANIA ISNAWATI', 'FRISILIA CLAUDIA HUTAMA', 'DWI KUNTORO', 'YUNASIS PALGUNADI', 'RISFAN FAISAL'])) {
            $deptHead = 'ANDIK TOTOK SISWOYO';
        } elseif ($poPengajuan->modified_at == 'RANGGA FADILLAH') {
            $deptHead = 'VITRI HANDAYANI';
        }

        $userAccHeader = '';
        $userAccbody = '';
        $trsPoPengajuan = null; // Deklarasikan $trsPoPengajuan di awal sebagai null

        if ($poPengajuan->kategori_po == 'IT') {
            $userAccHeader = 'IT';
            $userAccbody = 'MEDI KRISNANTO';
        } elseif ($poPengajuan->kategori_po == 'GA') {
            $userAccHeader = 'GA';
            $userAccbody = 'MUHAMMAD DINAR FARISI';
        } elseif (in_array($poPengajuan->kategori_po, ['Consumable', 'Subcont', 'Spareparts', 'Indirect Material'])) {
            $userAccHeader = 'Warehouse';

            // Mengambil data dari TrsPoPengajuan berdasarkan status 4 dan id_fpb yang diambil dari id di $poPengajuan
            $trsPoPengajuan = TrsPoPengajuan::join('mst_po_pengajuans', 'trs_po_pengajuans.id_fpb', '=', 'mst_po_pengajuans.id')
                ->where('trs_po_pengajuans.status', 4)
                ->where('trs_po_pengajuans.id_fpb', $poPengajuan->id)
                ->select('trs_po_pengajuans.*', 'mst_po_pengajuans.kategori_po')
                ->first();

            // Logging the query and results
            Log::info('Fetching TrsPoPengajuan for Warehouse', [
                'kategori_po' => $poPengajuan->kategori_po,
                'status' => 4,
                'id_fpb' => $poPengajuan->id,
                'modified_at' => $trsPoPengajuan ? $trsPoPengajuan->modified_at : null
            ]);

            if ($trsPoPengajuan) {
                // Set userAccbody to modified_at field for status 4 without any other text
                $userAccbody = $trsPoPengajuan->modified_at;
            } else {
                // Set userAccbody to empty if no record with status 4 is found
                $userAccbody = '';
            }
        }
        $matchingTrsPoPengajuans = TrsPoPengajuan::where('id_fpb', $poPengajuan->id)
            ->select('created_at', 'status') // Pastikan kolom 'status' benar-benar ada
            ->get();

        // Mengirimkan data ke view, termasuk $trsPoPengajuan
        return view('po_pengajuan.view_form_FPB', compact('mstPoPengajuans', 'deptHead', 'userAccHeader', 'userAccbody', 'trsPoPengajuan', 'matchingTrsPoPengajuans'));
    }

    public function showFPBForm2($id)
    {
        // Mengambil data berdasarkan id
        $poPengajuan = MstPoPengajuan::find($id);

        if (!$poPengajuan) {
            return abort(404); // Jika data dengan ID tersebut tidak ditemukan
        }

        // Mengambil semua data berdasarkan no_fpb yang sama
        $mstPoPengajuans = MstPoPengajuan::where('no_fpb', $poPengajuan->no_fpb)->get();

        // Tentukan Dept. Head berdasarkan nilai modified_at
        $deptHead = '';
        if (in_array($poPengajuan->modified_at, ['JESSICA PAUNE', 'SITI MARIA ULFA', 'MUHAMMAD DINAR FARISI'])) {
            $deptHead = 'MARTINUS CAHYO RAHASTO';
        } elseif (in_array($poPengajuan->modified_at, ['MUGI PRAMONO', 'ABDUR RAHMAN AL FAAIZ', 'RAGIL ISHA RAHMANTO'])) {
            $deptHead = 'ARY RODJO PRASETYO';
        } elseif (in_array($poPengajuan->modified_at, ['ILHAM CHOLID', 'JUN JOHAMIN PD', 'HERY HERMAWAN', 'WULYO EKO PRASETYO', 'SENDY PRABOWO', 'YAN WELEM MANGINSELA', 'HEXAPA DARMADI', 'SARAH EGA BUDI ASTUTI', 'SONY STIAWAN', 'DIMAS ADITYA PRIANDANA'])) {
            $deptHead = 'YULMAI RIDO WINANDA';
        } elseif (in_array($poPengajuan->modified_at, ['DANIA ISNAWATI', 'FRISILIA CLAUDIA HUTAMA', 'DWI KUNTORO', 'YUNASIS PALGUNADI', 'RISFAN FAISAL'])) {
            $deptHead = 'ANDIK TOTOK SISWOYO';
        } elseif ($poPengajuan->modified_at == 'RANGGA FADILLAH') {
            $deptHead = 'VITRI HANDAYANI';
        }

        // Tentukan User Acc berdasarkan kategori_po
        $userAccHeader = '';
        $userAccbody = '';

        if ($poPengajuan->kategori_po == 'IT') {
            $userAccHeader = 'IT';
            $userAccbody = 'MEDI KRISNANTO';
        } elseif ($poPengajuan->kategori_po == 'GA') {
            $userAccHeader = 'GA';
            $userAccbody = 'MUHAMMAD DINAR FARISI';
        } elseif (in_array($poPengajuan->kategori_po, ['Consumable', 'Subcont', 'Spareparts', 'Indirect Material'])) {
            $userAccHeader = 'Warehouse';

            // Mengambil data dari TrsPoPengajuan berdasarkan status 4 dan id_fpb yang diambil dari id di $poPengajuan
            $trsPoPengajuan = TrsPoPengajuan::join('mst_po_pengajuans', 'trs_po_pengajuans.id_fpb', '=', 'mst_po_pengajuans.id')
                ->where('trs_po_pengajuans.status', 4)
                ->where('trs_po_pengajuans.id_fpb', $poPengajuan->id)
                ->select('trs_po_pengajuans.*', 'mst_po_pengajuans.kategori_po')
                ->first();

            // Logging the query and results
            Log::info('Fetching TrsPoPengajuan for Warehouse', [
                'kategori_po' => $poPengajuan->kategori_po,
                'status' => 4,
                'id_fpb' => $poPengajuan->id,
                'modified_at' => $trsPoPengajuan ? $trsPoPengajuan->modified_at : null
            ]);

            if ($trsPoPengajuan) {
                // Set userAccbody to modified_at field for status 4 without any other text
                $userAccbody = $trsPoPengajuan->modified_at;
            } else {
                // Set userAccbody to empty if no record with status 4 is found
                $userAccbody = '';
            }
        }

        $matchingTrsPoPengajuans = TrsPoPengajuan::where('id_fpb', $poPengajuan->id)
            ->select('created_at', 'status') // Pastikan kolom 'status' benar-benar ada
            ->get();

        // Mengirimkan data ke view
        return view('po_pengajuan.view_form_FPB_dept', compact('mstPoPengajuans', 'deptHead', 'userAccHeader', 'userAccbody', 'matchingTrsPoPengajuans'));
    }

    public function showFPBForm3($id)
    {
        // Mengambil data berdasarkan id
        $poPengajuan = MstPoPengajuan::find($id);

        if (!$poPengajuan) {
            return abort(404); // Jika data dengan ID tersebut tidak ditemukan
        }

        // Mengambil semua data berdasarkan no_fpb yang sama
        $mstPoPengajuans = MstPoPengajuan::where('no_fpb', $poPengajuan->no_fpb)->get();

        // Tentukan Dept. Head berdasarkan nilai modified_at
        $deptHead = '';
        if (in_array($poPengajuan->modified_at, ['JESSICA PAUNE', 'SITI MARIA ULFA', 'MUHAMMAD DINAR FARISI'])) {
            $deptHead = 'MARTINUS CAHYO RAHASTO';
        } elseif (in_array($poPengajuan->modified_at, ['MUGI PRAMONO', 'ABDUR RAHMAN AL FAAIZ', 'RAGIL ISHA RAHMANTO'])) {
            $deptHead = 'ARY RODJO PRASETYO';
        } elseif (in_array($poPengajuan->modified_at, ['ILHAM CHOLID', 'JUN JOHAMIN PD', 'HERY HERMAWAN', 'WULYO EKO PRASETYO', 'SENDY PRABOWO', 'YAN WELEM MANGINSELA', 'HEXAPA DARMADI', 'SARAH EGA BUDI ASTUTI', 'SONY STIAWAN', 'DIMAS ADITYA PRIANDANA'])) {
            $deptHead = 'YULMAI RIDO WINANDA';
        } elseif (in_array($poPengajuan->modified_at, ['DANIA ISNAWATI', 'FRISILIA CLAUDIA HUTAMA', 'DWI KUNTORO', 'YUNASIS PALGUNADI', 'RISFAN FAISAL'])) {
            $deptHead = 'ANDIK TOTOK SISWOYO';
        } elseif ($poPengajuan->modified_at == 'RANGGA FADILLAH') {
            $deptHead = 'VITRI HANDAYANI';
        }

        // Tentukan User Acc berdasarkan kategori_po
        $userAccHeader = '';
        $userAccbody = '';

        if ($poPengajuan->kategori_po == 'IT') {
            $userAccHeader = 'IT';
            $userAccbody = 'MEDI KRISNANTO';
        } elseif ($poPengajuan->kategori_po == 'GA') {
            $userAccHeader = 'GA';
            $userAccbody = 'MUHAMMAD DINAR FARISI';
        } elseif (in_array($poPengajuan->kategori_po, ['Consumable', 'Subcont', 'Spareparts', 'Indirect Material'])) {
            $userAccHeader = 'Warehouse';

            // Mengambil data dari TrsPoPengajuan berdasarkan status 4 dan id_fpb yang diambil dari id di $poPengajuan
            $trsPoPengajuan = TrsPoPengajuan::join('mst_po_pengajuans', 'trs_po_pengajuans.id_fpb', '=', 'mst_po_pengajuans.id')
                ->where('trs_po_pengajuans.status', 4)
                ->where('trs_po_pengajuans.id_fpb', $poPengajuan->id)
                ->select('trs_po_pengajuans.*', 'mst_po_pengajuans.kategori_po')
                ->first();

            // Logging the query and results
            Log::info('Fetching TrsPoPengajuan for Warehouse', [
                'kategori_po' => $poPengajuan->kategori_po,
                'status' => 4,
                'id_fpb' => $poPengajuan->id,
                'modified_at' => $trsPoPengajuan ? $trsPoPengajuan->modified_at : null
            ]);

            if ($trsPoPengajuan) {
                // Set userAccbody to modified_at field for status 4 without any other text
                $userAccbody = $trsPoPengajuan->modified_at;
            } else {
                // Set userAccbody to empty if no record with status 4 is found
                $userAccbody = '';
            }
        }

        $matchingTrsPoPengajuans = TrsPoPengajuan::where('id_fpb', $poPengajuan->id)
            ->select('created_at', 'status') // Pastikan kolom 'status' benar-benar ada
            ->get();

        // Mengirimkan data ke view
        return view('po_pengajuan.view_form_FPB_user', compact('mstPoPengajuans', 'deptHead', 'userAccHeader', 'userAccbody', 'matchingTrsPoPengajuans'));
    }

    public function showFPBForm4($id)
    {
        // Mengambil data berdasarkan id
        $poPengajuan = MstPoPengajuan::find($id);

        if (!$poPengajuan) {
            return abort(404); // Jika data dengan ID tersebut tidak ditemukan
        }

        // Mengambil semua data berdasarkan no_fpb yang sama
        $mstPoPengajuans = MstPoPengajuan::where('no_fpb', $poPengajuan->no_fpb)->get();

        // Tentukan Dept. Head berdasarkan nilai modified_at
        $deptHead = '';
        if (in_array($poPengajuan->modified_at, ['JESSICA PAUNE', 'SITI MARIA ULFA', 'MUHAMMAD DINAR FARISI'])) {
            $deptHead = 'MARTINUS CAHYO RAHASTO';
        } elseif (in_array($poPengajuan->modified_at, ['MUGI PRAMONO', 'ABDUR RAHMAN AL FAAIZ', 'RAGIL ISHA RAHMANTO'])) {
            $deptHead = 'ARY RODJO PRASETYO';
        } elseif (in_array($poPengajuan->modified_at, ['ILHAM CHOLID', 'JUN JOHAMIN PD', 'HERY HERMAWAN', 'WULYO EKO PRASETYO', 'SENDY PRABOWO', 'YAN WELEM MANGINSELA', 'HEXAPA DARMADI', 'SARAH EGA BUDI ASTUTI', 'SONY STIAWAN', 'DIMAS ADITYA PRIANDANA'])) {
            $deptHead = 'YULMAI RIDO WINANDA';
        } elseif (in_array($poPengajuan->modified_at, ['DANIA ISNAWATI', 'FRISILIA CLAUDIA HUTAMA', 'DWI KUNTORO', 'YUNASIS PALGUNADI', 'RISFAN FAISAL'])) {
            $deptHead = 'ANDIK TOTOK SISWOYO';
        } elseif ($poPengajuan->modified_at == 'RANGGA FADILLAH') {
            $deptHead = 'VITRI HANDAYANI';
        }

        // Tentukan User Acc berdasarkan kategori_po
        $userAccHeader = '';
        $userAccbody = '';

        if ($poPengajuan->kategori_po == 'IT') {
            $userAccHeader = 'IT';
            $userAccbody = 'MEDI KRISNANTO';
        } elseif ($poPengajuan->kategori_po == 'GA') {
            $userAccHeader = 'GA';
            $userAccbody = 'MUHAMMAD DINAR FARISI';
        } elseif (in_array($poPengajuan->kategori_po, ['Consumable', 'Subcont', 'Spareparts', 'Indirect Material'])) {
            $userAccHeader = 'Warehouse';

            // Mengambil data dari TrsPoPengajuan berdasarkan status 4 dan id_fpb yang diambil dari id di $poPengajuan
            $trsPoPengajuan = TrsPoPengajuan::join('mst_po_pengajuans', 'trs_po_pengajuans.id_fpb', '=', 'mst_po_pengajuans.id')
                ->where('trs_po_pengajuans.status', 4)
                ->where('trs_po_pengajuans.id_fpb', $poPengajuan->id)
                ->select('trs_po_pengajuans.*', 'mst_po_pengajuans.kategori_po')
                ->first();

            // Logging the query and results
            Log::info('Fetching TrsPoPengajuan for Warehouse', [
                'kategori_po' => $poPengajuan->kategori_po,
                'status' => 4,
                'id_fpb' => $poPengajuan->id,
                'modified_at' => $trsPoPengajuan ? $trsPoPengajuan->modified_at : null
            ]);

            if ($trsPoPengajuan) {
                // Set userAccbody to modified_at field for status 4 without any other text
                $userAccbody = $trsPoPengajuan->modified_at;
            } else {
                // Set userAccbody to empty if no record with status 4 is found
                $userAccbody = '';
            }
        }

        $matchingTrsPoPengajuans = TrsPoPengajuan::where('id_fpb', $poPengajuan->id)
            ->select('created_at', 'status') // Pastikan kolom 'status' benar-benar ada
            ->get();

        // Mengirimkan data ke view
        return view('po_pengajuan.view_form_FPB_finn', compact('mstPoPengajuans', 'deptHead', 'userAccHeader', 'userAccbody', 'matchingTrsPoPengajuans'));
    }

    public function showFPBProc($id)
    {
        // Mengambil data berdasarkan id
        $poPengajuan = MstPoPengajuan::find($id);

        if (!$poPengajuan) {
            return abort(404); // Jika data dengan ID tersebut tidak ditemukan
        }

        // Mengambil semua data berdasarkan no_fpb yang sama
        $mstPoPengajuans = MstPoPengajuan::where('no_fpb', $poPengajuan->no_fpb)->get();

        // Tentukan Dept. Head berdasarkan nilai modified_at
        $deptHead = '';
        if (in_array($poPengajuan->modified_at, ['JESSICA PAUNE', 'SITI MARIA ULFA', 'MUHAMMAD DINAR FARISI'])) {
            $deptHead = 'MARTINUS CAHYO RAHASTO';
        } elseif (in_array($poPengajuan->modified_at, ['MUGI PRAMONO', 'ABDUR RAHMAN AL FAAIZ', 'RAGIL ISHA RAHMANTO'])) {
            $deptHead = 'ARY RODJO PRASETYO';
        } elseif (in_array($poPengajuan->modified_at, ['ILHAM CHOLID', 'JUN JOHAMIN PD', 'HERY HERMAWAN', 'WULYO EKO PRASETYO', 'SENDY PRABOWO', 'YAN WELEM MANGINSELA', 'HEXAPA DARMADI', 'SARAH EGA BUDI ASTUTI', 'SONY STIAWAN', 'DIMAS ADITYA PRIANDANA'])) {
            $deptHead = 'YULMAI RIDO WINANDA';
        } elseif (in_array($poPengajuan->modified_at, ['DANIA ISNAWATI', 'FRISILIA CLAUDIA HUTAMA', 'DWI KUNTORO', 'YUNASIS PALGUNADI', 'RISFAN FAISAL'])) {
            $deptHead = 'ANDIK TOTOK SISWOYO';
        } elseif ($poPengajuan->modified_at == 'RANGGA FADILLAH') {
            $deptHead = 'VITRI HANDAYANI';
        }

        $userAccHeader = '';
        $userAccbody = '';

        if ($poPengajuan->kategori_po == 'IT') {
            $userAccHeader = 'IT';
            $userAccbody = 'MEDI KRISNANTO';
        } elseif ($poPengajuan->kategori_po == 'GA') {
            $userAccHeader = 'GA';
            $userAccbody = 'MUHAMMAD DINAR FARISI';
        } elseif (in_array($poPengajuan->kategori_po, ['Consumable', 'Subcont', 'Spareparts', 'Indirect Material'])) {
            $userAccHeader = 'Warehouse';

            // Mengambil data dari TrsPoPengajuan berdasarkan status 4 dan id_fpb yang diambil dari id di $poPengajuan
            $trsPoPengajuan = TrsPoPengajuan::join('mst_po_pengajuans', 'trs_po_pengajuans.id_fpb', '=', 'mst_po_pengajuans.id')
                ->where('trs_po_pengajuans.status', 4)
                ->where('trs_po_pengajuans.id_fpb', $poPengajuan->id)
                ->select('trs_po_pengajuans.*', 'mst_po_pengajuans.kategori_po')
                ->first();

            // Logging the query and results
            Log::info('Fetching TrsPoPengajuan for Warehouse', [
                'kategori_po' => $poPengajuan->kategori_po,
                'status' => 4,
                'id_fpb' => $poPengajuan->id,
                'modified_at' => $trsPoPengajuan ? $trsPoPengajuan->modified_at : null
            ]);

            if ($trsPoPengajuan) {
                // Set userAccbody to modified_at field for status 4 without any other text
                $userAccbody = $trsPoPengajuan->modified_at;
            } else {
                // Set userAccbody to empty if no record with status 4 is found
                $userAccbody = '';
            }
        }

        $matchingTrsPoPengajuans = TrsPoPengajuan::where('id_fpb', $poPengajuan->id)
            ->select('created_at', 'status') // Pastikan kolom 'status' benar-benar ada
            ->get();

        // Mengirimkan data ke view
        return view('po_pengajuan.trs_po_procurment', compact('mstPoPengajuans', 'deptHead',  'userAccHeader', 'userAccbody', 'matchingTrsPoPengajuans'));
    }

    public function createPoPengajuan()
    {

        // Mengirim data ke view
        return view('po_pengajuan.create_po_pengajuan');
    }

    public function edit($id)
    {
        // Find the record by id
        $pengajuanPo = MstPoPengajuan::findOrFail($id);

        // Fetch all records with the same no_fpb
        $pengajuanPoList = MstPoPengajuan::where('no_fpb', $pengajuanPo->no_fpb)->get();

        return view('po_pengajuan.edit_po_pengajuan', compact('pengajuanPo', 'pengajuanPoList'));
    }

    public function editDept($id)
    {
        // Find the record by id
        $pengajuanPo = MstPoPengajuan::findOrFail($id);

        // Fetch all records with the same no_fpb
        $pengajuanPoList = MstPoPengajuan::where('no_fpb', $pengajuanPo->no_fpb)->get();

        return view('po_pengajuan.edit_po_deptHead', compact('pengajuanPo', 'pengajuanPoList'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Received request data for purchase order:', $request->all()); // Log all incoming data

            $validatedData = $request->validate([
                'kategori_po' => 'required|string',
                'nama_barang.*' => 'required|string',
                'spesifikasi.*' => 'required|string',
                'pcs.*' => 'required|integer',
                'file.*' => 'nullable|file|max:50048',
                'price_list.*' => 'nullable|numeric',
                'total_harga.*' => 'nullable|numeric',
                'target_cost.*' => 'nullable|numeric',
                'lead_time.*' => 'nullable|date',
                'rekomendasi.*' => 'nullable|string',
                'nama_customer.*' => 'nullable|string',
                'nama_project.*' => 'nullable|string',
            ]);

            Log::info('Validated data:', $validatedData); // Log the validated data

            // Generate no_fpb
            $currentYear = date('Y');
            $latestPo = MstPoPengajuan::whereYear('created_at', $currentYear)
                ->orderBy('id', 'desc')
                ->first();

            $newPoNumber = 1; // Default value if no existing PO
            if ($latestPo) {
                $lastPoNumber = (int)substr($latestPo->no_fpb, strrpos($latestPo->no_fpb, '/') + 1);
                $newPoNumber = $lastPoNumber + 1;
            }

            $no_fpb = 'FPB/' . $currentYear . '/' . str_pad($newPoNumber, 5, '0', STR_PAD_LEFT); // Format to 00001
            Log::info('Generated PO number: ' . $no_fpb); // Log the generated PO number

            // Check if the generated no_fpb already exists in the database
            while (MstPoPengajuan::where('no_fpb', $no_fpb)->exists()) {
                Log::warning('Duplicate PO number found, generating a new one.');
                // If it exists, generate a new no_fpb
                $newPoNumber++;
                $no_fpb = 'FPB/' . $currentYear . '/' . str_pad($newPoNumber, 5, '0', STR_PAD_LEFT);
                Log::info('Newly generated PO number: ' . $no_fpb); // Log the newly generated PO number
            }

            foreach ($validatedData['nama_barang'] as $index => $nama_barang) {
                $purchaseOrder = new MstPoPengajuan();
                $purchaseOrder->no_fpb = $no_fpb; // Store generated no_fpb
                $purchaseOrder->kategori_po = $validatedData['kategori_po'];
                $purchaseOrder->nama_barang = $nama_barang;
                $purchaseOrder->spesifikasi = $validatedData['spesifikasi'][$index];
                $purchaseOrder->pcs = $validatedData['pcs'][$index];
                $purchaseOrder->price_list = isset($validatedData['price_list'][$index]) ?
                    str_replace(',', '', $validatedData['price_list'][$index]) : null;

                // Menghitung total harga (pcs * price_list)
                if (isset($validatedData['pcs'][$index]) && isset($validatedData['price_list'][$index])) {
                    $purchaseOrder->total_harga = $validatedData['pcs'][$index] * str_replace(',', '', $validatedData['price_list'][$index]);
                } else {
                    $purchaseOrder->total_harga = null;
                }

                Log::info('Creating purchase order for item:', [
                    'no_fpb' => $no_fpb,
                    'kategori_po' => $validatedData['kategori_po'],
                    'nama_barang' => $nama_barang,
                    'spesifikasi' => $validatedData['spesifikasi'][$index],
                ]); // Removed qty reference from log

                // Handle file upload with UUID
                if ($request->hasFile('file.' . $index)) {
                    $file = $request->file('file.' . $index);
                    // Generate a unique file name
                    $hashedName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/pre_order'), $hashedName);
                    $purchaseOrder->file = $hashedName; // Store the hashed file name
                    $purchaseOrder->file_name = $file->getClientOriginalName(); // Original file name (optional)
                    Log::info('Uploaded file for item:', [
                        'file_name' => $file->getClientOriginalName(),
                        'hashed_name' => $hashedName,
                    ]);
                }

                // Handle Subcont fields if they are set
                if ($validatedData['kategori_po'] === 'Subcont') {
                    $purchaseOrder->target_cost = isset($validatedData['target_cost'][$index]) ?
                        str_replace(',', '', $validatedData['target_cost'][$index]) : null;
                    $purchaseOrder->lead_time = $validatedData['lead_time'][$index] ?? null;
                    $purchaseOrder->rekomendasi = $validatedData['rekomendasi'][$index] ?? null;
                    $purchaseOrder->nama_customer = $validatedData['nama_customer'][$index] ?? null;
                    $purchaseOrder->nama_project = $validatedData['nama_project'][$index] ?? null;

                    Log::info('Subcont fields for item:', [
                        'target_cost' => $purchaseOrder->target_cost,
                        'lead_time' => $purchaseOrder->lead_time,
                        'rekomendasi' => $purchaseOrder->rekomendasi,
                        'nama_customer' => $purchaseOrder->nama_customer,
                        'nama_project' => $purchaseOrder->nama_project,
                    ]);
                }

                $purchaseOrder->status_1 = 1;
                $purchaseOrder->modified_at = $request->user()->name;

                // Attempt to save the purchase order
                try {
                    $purchaseOrder->save();
                    Log::info('Purchase order saved successfully:', ['no_fpb' => $no_fpb]);
                } catch (\Exception $e) {
                    Log::error('Failed to save purchase order: ' . $e->getMessage(), [
                        'no_fpb' => $no_fpb,
                        'data' => json_encode($purchaseOrder->toArray()), // Convert to string for logging
                    ]);
                    return redirect()->route('index.PO')->with('error', 'Data failed to save: ' . $e->getMessage());
                }
            }

            return redirect()->route('index.PO')->with('success', 'Data successfully saved!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors())); // Convert errors to string
            return redirect()->route('index.PO')->with('error', 'Validation failed: ' . implode(', ', $e->errors()));
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return redirect()->route('index.PO')->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Retrieve PO submission data by ID
        $pengajuanPo = MstPoPengajuan::findOrFail($id);

        // Update MstPoPengajuan data (kategori_po column)
        $pengajuanPo->kategori_po = $request->kategori_po;
        $pengajuanPo->save();

        // Loop to update or add new items via addRow
        if ($request->has('id')) {
            foreach ($request->id as $index => $itemId) {
                if (!empty($itemId)) {
                    // If item has an ID, update the existing data
                    $pengajuanPoItem = MstPoPengajuan::find($itemId);
                    if ($pengajuanPoItem) {
                        $pengajuanPoItem->kategori_po = $pengajuanPo->kategori_po; // Update kategori_po
                        $pengajuanPoItem->nama_barang = $request->nama_barang[$index];
                        $pengajuanPoItem->spesifikasi = $request->spesifikasi[$index];
                        $pengajuanPoItem->pcs = $request->pcs[$index];

                        // Allow null price_list if kategori_po is 'Subcont'
                        if ($pengajuanPo->kategori_po === 'Subcont') {
                            $pengajuanPoItem->price_list = $request->price_list[$index] ?? null;
                        } else {
                            $pengajuanPoItem->price_list = $request->price_list[$index];
                        }

                        // Calculate total_harga if price_list is available
                        if ($pengajuanPoItem->price_list !== null) {
                            $pengajuanPoItem->total_harga = $request->pcs[$index] * $pengajuanPoItem->price_list;
                        } else {
                            $pengajuanPoItem->total_harga = null; // Or handle this as you prefer
                        }

                        // Handle file uploads
                        if ($request->hasFile('file.' . $index)) {
                            if ($pengajuanPoItem->file) {
                                Storage::delete('public/assets/pre_order/' . $pengajuanPoItem->file);
                            }
                            $file = $request->file('file.' . $index);
                            $hashedName = uniqid() . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('assets/pre_order'), $hashedName);

                            $pengajuanPoItem->file = $hashedName;
                            $pengajuanPoItem->file_name = $file->getClientOriginalName();
                        }

                        // Special handling for Subcont category
                        if ($pengajuanPo->kategori_po === 'Subcont') {
                            $pengajuanPoItem->target_cost = $request->target_cost[$index] ?? null;
                            $pengajuanPoItem->lead_time = $request->lead_time[$index] ?? null;
                            $pengajuanPoItem->rekomendasi = $request->rekomendasi[$index] ?? null;
                            $pengajuanPoItem->nama_customer = $request->nama_customer[$index] ?? null;
                            $pengajuanPoItem->nama_project = $request->nama_project[$index] ?? null;
                        }

                        $pengajuanPoItem->save();
                    }
                } else {
                    // Add new items if no ID is provided
                    $pengajuanPoItem = new MstPoPengajuan();
                    $pengajuanPoItem->no_fpb = $pengajuanPo->no_fpb;
                    $pengajuanPoItem->kategori_po = $pengajuanPo->kategori_po;
                    $pengajuanPoItem->nama_barang = $request->nama_barang[$index];
                    $pengajuanPoItem->spesifikasi = $request->spesifikasi[$index];
                    $pengajuanPoItem->pcs = $request->pcs[$index];

                    // Allow null price_list if kategori_po is 'Subcont'
                    if ($pengajuanPo->kategori_po === 'Subcont') {
                        $pengajuanPoItem->price_list = $request->price_list[$index] ?? null;
                    } else {
                        $pengajuanPoItem->price_list = $request->price_list[$index];
                    }

                    // Calculate total_harga if price_list is available
                    if ($pengajuanPoItem->price_list !== null) {
                        $pengajuanPoItem->total_harga = $request->pcs[$index] * $pengajuanPoItem->price_list;
                    } else {
                        $pengajuanPoItem->total_harga = null; // Or handle this as you prefer
                    }

                    $pengajuanPoItem->status_1 = 1;
                    $pengajuanPoItem->modified_at = $request->user()->name;

                    // Handle file upload for new item
                    if ($request->hasFile('file.' . $index)) {
                        $file = $request->file('file.' . $index);
                        $hashedName = uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('assets/pre_order'), $hashedName);

                        $pengajuanPoItem->file = $hashedName;
                        $pengajuanPoItem->file_name = $file->getClientOriginalName();
                    }

                    // Special handling for Subcont category
                    if ($pengajuanPo->kategori_po === 'Subcont') {
                        $pengajuanPoItem->target_cost = $request->target_cost[$index] ?? null;
                        $pengajuanPoItem->lead_time = $request->lead_time[$index] ?? null;
                        $pengajuanPoItem->rekomendasi = $request->rekomendasi[$index] ?? null;
                        $pengajuanPoItem->nama_customer = $request->nama_customer[$index] ?? null;
                        $pengajuanPoItem->nama_project = $request->nama_project[$index] ?? null;
                    }

                    $pengajuanPoItem->save();
                }
            }
        }

        return redirect()->route('index.PO')->with('success', 'Data PO berhasil diperbarui.');
    }

    public function deletePoPengajuanMultiple(Request $request)
    {
        $ids = $request->ids; // Get the array of IDs from the request

        if (empty($ids)) {
            return response()->json(['message' => 'No items selected'], 400); // If no IDs were sent
        }

        // Find the records by IDs
        $pengajuanItems = MstPoPengajuan::whereIn('id', $ids)->get();

        if ($pengajuanItems->isEmpty()) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Perform the deletion
        MstPoPengajuan::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Selected items deleted successfully']);
    }

    public function updateDept(Request $request, $id)
    {
        // Retrieve PO submission data by ID
        $pengajuanPo = MstPoPengajuan::findOrFail($id);

        // Update MstPoPengajuan data (kategori_po column)
        $pengajuanPo->kategori_po = $request->kategori_po;
        $pengajuanPo->save();

        // Loop to update or add new items via addRow
        if ($request->has('id')) {
            foreach ($request->id as $index => $itemId) {
                if (!empty($itemId)) {
                    // If item has an ID, update the existing data
                    $pengajuanPoItem = MstPoPengajuan::find($itemId);
                    if ($pengajuanPoItem) {
                        $pengajuanPoItem->kategori_po = $pengajuanPo->kategori_po; // Update kategori_po
                        $pengajuanPoItem->nama_barang = $request->nama_barang[$index];
                        $pengajuanPoItem->spesifikasi = $request->spesifikasi[$index];
                        $pengajuanPoItem->pcs = $request->pcs[$index];

                        // Allow null price_list if kategori_po is 'Subcont'
                        if ($pengajuanPo->kategori_po === 'Subcont') {
                            $pengajuanPoItem->price_list = $request->price_list[$index] ?? null;
                        } else {
                            $pengajuanPoItem->price_list = $request->price_list[$index];
                        }

                        // Calculate total_harga if price_list is available
                        if ($pengajuanPoItem->price_list !== null) {
                            $pengajuanPoItem->total_harga = $request->pcs[$index] * $pengajuanPoItem->price_list;
                        } else {
                            $pengajuanPoItem->total_harga = null; // Or handle this as you prefer
                        }

                        // Handle file uploads
                        if ($request->hasFile('file.' . $index)) {
                            if ($pengajuanPoItem->file) {
                                Storage::delete('public/assets/pre_order/' . $pengajuanPoItem->file);
                            }
                            $file = $request->file('file.' . $index);
                            $hashedName = uniqid() . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('assets/pre_order'), $hashedName);

                            $pengajuanPoItem->file = $hashedName;
                            $pengajuanPoItem->file_name = $file->getClientOriginalName();
                        }

                        // Special handling for Subcont category
                        if ($pengajuanPo->kategori_po === 'Subcont') {
                            $pengajuanPoItem->target_cost = $request->target_cost[$index] ?? null;
                            $pengajuanPoItem->lead_time = $request->lead_time[$index] ?? null;
                            $pengajuanPoItem->rekomendasi = $request->rekomendasi[$index] ?? null;
                            $pengajuanPoItem->nama_customer = $request->nama_customer[$index] ?? null;
                            $pengajuanPoItem->nama_project = $request->nama_project[$index] ?? null;
                        }

                        $pengajuanPoItem->save();
                    }
                } else {
                    // Add new items if no ID is provided
                    $pengajuanPoItem = new MstPoPengajuan();
                    $pengajuanPoItem->no_fpb = $pengajuanPo->no_fpb;
                    $pengajuanPoItem->kategori_po = $pengajuanPo->kategori_po;
                    $pengajuanPoItem->nama_barang = $request->nama_barang[$index];
                    $pengajuanPoItem->spesifikasi = $request->spesifikasi[$index];
                    $pengajuanPoItem->pcs = $request->pcs[$index];

                    // Allow null price_list if kategori_po is 'Subcont'
                    if ($pengajuanPo->kategori_po === 'Subcont') {
                        $pengajuanPoItem->price_list = $request->price_list[$index] ?? null;
                    } else {
                        $pengajuanPoItem->price_list = $request->price_list[$index];
                    }

                    // Calculate total_harga if price_list is available
                    if ($pengajuanPoItem->price_list !== null) {
                        $pengajuanPoItem->total_harga = $request->pcs[$index] * $pengajuanPoItem->price_list;
                    } else {
                        $pengajuanPoItem->total_harga = null; // Or handle this as you prefer
                    }

                    $pengajuanPoItem->status_1 = 2;
                    $pengajuanPoItem->status_2 = 2;
                    // Cari data terakhir berdasarkan no_fpb yang sama
                    $previousPengajuan = MstPoPengajuan::where('no_fpb', $pengajuanPoItem->no_fpb)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($previousPengajuan) {
                        // Jika ditemukan, gunakan nilai modified_at dari data sebelumnya
                        $pengajuanPoItem->modified_at = $previousPengajuan->modified_at;
                    } else {
                        // Jika tidak ditemukan, Anda dapat menetapkan nilai default atau tetap menggunakan user yang login
                        $pengajuanPoItem->modified_at = $request->user()->name;
                    }

                    // Handle file upload for new item
                    if ($request->hasFile('file.' . $index)) {
                        $file = $request->file('file.' . $index);
                        $hashedName = uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('assets/pre_order'), $hashedName);

                        $pengajuanPoItem->file = $hashedName;
                        $pengajuanPoItem->file_name = $file->getClientOriginalName();
                    }

                    // Special handling for Subcont category
                    if ($pengajuanPo->kategori_po === 'Subcont') {
                        $pengajuanPoItem->target_cost = $request->target_cost[$index] ?? null;
                        $pengajuanPoItem->lead_time = $request->lead_time[$index] ?? null;
                        $pengajuanPoItem->rekomendasi = $request->rekomendasi[$index] ?? null;
                        $pengajuanPoItem->nama_customer = $request->nama_customer[$index] ?? null;
                        $pengajuanPoItem->nama_project = $request->nama_project[$index] ?? null;
                    }

                    $pengajuanPoItem->save();

                    // Menambahkan data ke model TrsPoPengajuan
                    $trsPoPengajuan = new TrsPoPengajuan();
                    $trsPoPengajuan->id_fpb = $pengajuanPoItem->id; // Mengisi id_fpb dari MstPoPengajuan
                    $trsPoPengajuan->keterangan = "Item Telah ditambahkan"; // Mengisi field keterangan
                    $trsPoPengajuan->modified_at = $request->user()->name;
                    $trsPoPengajuan->status = 2; // Mengisi field status
                    $trsPoPengajuan->save();
                }
            }
        }

        return redirect()->route('index.PO.Dept')->with('success', 'Data PO berhasil diperbarui.');
    }

    //Sec.Head
    public function updateStatusByNoFPB($no_fpb)
    {
        // Log no_fpb yang diterima
        \Log::info('Received no_fpb: ' . $no_fpb);

        // Ubah kembali tanda minus (-) menjadi garis miring (/)
        $no_fpb = str_replace('-', '/', $no_fpb);
        \Log::info('Transformed no_fpb: ' . $no_fpb);

        // Cari data berdasarkan no_fpb yang telah diubah
        $pengajuanList = MstPoPengajuan::where('no_fpb', $no_fpb)->get();

        if ($pengajuanList->isEmpty()) {
            \Log::error('No data found for no_fpb: ' . $no_fpb); // Log jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data not found');
        }

        // Update status data yang sesuai
        foreach ($pengajuanList as $pengajuan) {
            // Cek jika status_1 atau status_2 bernilai 8
            if ($pengajuan->status_1 == 8 || $pengajuan->status_2 == 8) {
                \Log::warning('Skipping update for no_fpb: ' . $no_fpb . ' because status is 8');
                continue; // Lewati iterasi ini jika status_1 atau status_2 adalah 8
            }

            // Update status_1 dan status_2 menjadi 2
            $pengajuan->update(['status_1' => 2, 'status_2' => 2]);

            // Tambah data ke dalam model TrsPoPengajuan
            TrsPoPengajuan::create([
                'id_fpb' => $pengajuan->id,
                'nama_barang' => $pengajuan->nama_barang, // Menggunakan id dari setiap data MstPoPengajuan
                'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
                'status' => 2,
            ]);
        }

        return redirect()->back()->with('success', 'Status updated successfully for no_fpb: ' . $no_fpb);
    }

    //Dept.Head
    public function updateStatusByDeptHead($no_fpb)
    {
        // Log no_fpb yang diterima
        \Log::info('Received no_fpb: ' . $no_fpb);

        // Ubah kembali tanda minus (-) menjadi garis miring (/)
        $no_fpb = str_replace('-', '/', $no_fpb);
        \Log::info('Transformed no_fpb: ' . $no_fpb);

        // Cari data berdasarkan no_fpb yang telah diubah
        $pengajuanList = MstPoPengajuan::where('no_fpb', $no_fpb)->get();

        if ($pengajuanList->isEmpty()) {
            \Log::error('No data found for no_fpb: ' . $no_fpb); // Log jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data not found');
        }

        // Update status data yang sesuai
        foreach ($pengajuanList as $pengajuan) {
            if ($pengajuan->status_2 == 8) {
                // Jika status_2 adalah 8, update hanya status_1
                $pengajuan->update(['status_1' => 3]);

                \Log::info('Only updated status_1 for no_fpb: ' . $no_fpb . ' because status_2 is 8');
            } else {
                // Jika status_2 bukan 8, update status_1
                $pengajuan->update(['status_1' => 3]);
                \Log::info('Updated status_1 for no_fpb: ' . $no_fpb);

                // Tambah data ke dalam model TrsPoPengajuan
                TrsPoPengajuan::create([
                    'id_fpb' => $pengajuan->id,
                    'nama_barang' => $pengajuan->nama_barang, // Menggunakan id dari setiap data MstPoPengajuan
                    'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
                    'status' => 3,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status updated successfully for no_fpb: ' . $no_fpb);
    }

    //user
    public function updateStatusByUser($no_fpb)
    {
        // Log no_fpb yang diterima
        \Log::info('Received no_fpb: ' . $no_fpb);

        // Ubah kembali tanda minus (-) menjadi garis miring (/)
        $no_fpb = str_replace('-', '/', $no_fpb);
        \Log::info('Transformed no_fpb: ' . $no_fpb);

        // Cari data berdasarkan no_fpb yang telah diubah
        $pengajuanList = MstPoPengajuan::where('no_fpb', $no_fpb)->get();

        if ($pengajuanList->isEmpty()) {
            \Log::error('No data found for no_fpb: ' . $no_fpb); // Log jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data not found');
        }

        // Update status data yang sesuai
        foreach ($pengajuanList as $pengajuan) {
            if ($pengajuan->status_2 == 8) {
                // Jika status_2 adalah 8, update hanya status_1
                $pengajuan->update(['status_1' => 4]);

                \Log::info('Only updated status_1 for no_fpb: ' . $no_fpb . ' because status_2 is 8');
            } else {
                // Jika status_2 bukan 8, update status_1
                $pengajuan->update(['status_1' => 4]);
                \Log::info('Updated status_1 for no_fpb: ' . $no_fpb);

                // Tambah data ke dalam model TrsPoPengajuan
                TrsPoPengajuan::create([
                    'id_fpb' => $pengajuan->id,
                    'nama_barang' => $pengajuan->nama_barang, // Menggunakan id dari setiap data MstPoPengajuan
                    'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
                    'status' => 4,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status updated successfully for no_fpb: ' . $no_fpb);
    }

    //Finance
    public function updateStatusByFinance($no_fpb)
    {
        // Log no_fpb yang diterima
        \Log::info('Received no_fpb: ' . $no_fpb);

        // Ubah kembali tanda minus (-) menjadi garis miring (/)
        $no_fpb = str_replace('-', '/', $no_fpb);
        \Log::info('Transformed no_fpb: ' . $no_fpb);

        // Cari data berdasarkan no_fpb yang telah diubah
        $pengajuanList = MstPoPengajuan::where('no_fpb', $no_fpb)->get();

        if ($pengajuanList->isEmpty()) {
            \Log::error('No data found for no_fpb: ' . $no_fpb); // Log jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data not found');
        }

        // Update status data yang sesuai
        foreach ($pengajuanList as $pengajuan) {
            if ($pengajuan->status_2 == 8) {
                // Jika status_2 adalah 8, update hanya status_1
                $pengajuan->update(['status_1' => 5]);

                \Log::info('Only updated status_1 for no_fpb: ' . $no_fpb . ' because status_2 is 8');
            } else {
                // Jika status_2 bukan 8, update status_1
                $pengajuan->update(['status_1' => 5]);
                \Log::info('Updated status_1 for no_fpb: ' . $no_fpb);

                // Tambah data ke dalam model TrsPoPengajuan
                TrsPoPengajuan::create([
                    'id_fpb' => $pengajuan->id,
                    'nama_barang' => $pengajuan->nama_barang, // Menggunakan id dari setiap data MstPoPengajuan
                    'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
                    'status' => 5,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status updated successfully for no_fpb: ' . $no_fpb);
    }

    //1 procurment
    public function updateStatusByProcurement($no_fpb)
    {
        // Log no_fpb yang diterima
        \Log::info('Received no_fpb: ' . $no_fpb);

        // Ubah kembali tanda minus (-) menjadi garis miring (/)
        $no_fpb = str_replace('-', '/', $no_fpb);
        \Log::info('Transformed no_fpb: ' . $no_fpb);

        // Cari data berdasarkan no_fpb yang telah diubah
        $pengajuanList = MstPoPengajuan::where('no_fpb', $no_fpb)->get();

        if ($pengajuanList->isEmpty()) {
            \Log::error('No data found for no_fpb: ' . $no_fpb); // Log jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data not found');
        }

        // Update status data yang sesuai
        foreach ($pengajuanList as $pengajuan) {
            if ($pengajuan->status_2 == 8) {
                // Jika status_2 adalah 8, update hanya status_1
                $pengajuan->update(['status_1' => 6]);

                \Log::info('Only updated status_1 for no_fpb: ' . $no_fpb . ' because status_2 is 8');
            } else {
                // Jika status_2 bukan 8, update status_1
                $pengajuan->update(['status_1' => 6]);
                \Log::info('Updated status_1 for no_fpb: ' . $no_fpb);

                // Tambah data ke dalam model TrsPoPengajuan
                TrsPoPengajuan::create([
                    'id_fpb' => $pengajuan->id,
                    'nama_barang' => $pengajuan->nama_barang, // Menggunakan id dari setiap data MstPoPengajuan
                    'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
                    'status' => 6,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status updated successfully for no_fpb: ' . $no_fpb);
    }

    //2 save procurment
    public function updateConfirmByProcurment($id)
    {
        // Validate the incoming request
        request()->validate([
            'keterangan' => 'required|string',
            'no_po' => 'nullable|string', // Tambahkan validasi opsional untuk no_po
        ]);

        // Log id yang diterima
        \Log::info('Received id: ' . $id);

        // Cari data berdasarkan id
        $pengajuan = MstPoPengajuan::find($id);

        if (!$pengajuan) {
            \Log::error('No data found for id: ' . $id); // Log jika data tidak ditemukan
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Jika no_po diisi, ubah status menjadi 7
        if (request()->filled('no_po')) {
            \Log::info('No PO filled for id: ' . $id);
            $pengajuan->update([
                'no_po' => request('no_po'),
                'status_1' => 7, // Ubah status menjadi 7
                'status_2' => 7,
            ]);
        } else {
            // Jika no_po tidak diisi, tetap simpan keterangan dan status
            $pengajuan->update([
                'status_1' => 6,
                'status_2' => 6, // Ubah status menjadi 6
            ]);
        }

        // Save the keterangan in TrsPoPengajuan
        TrsPoPengajuan::create([
            'id_fpb' => $pengajuan->id,
            'no_po' => $pengajuan->no_po, // Tambahkan no_po di sini
            'nama_barang' => $pengajuan->nama_barang,
            'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
            'keterangan' => request('keterangan'), // Get keterangan from request body
            'status' => $pengajuan->status_1, // Simpan status yang terbaru
        ]);

        return response()->json(['message' => 'Status updated successfully for id: ' . $id]);
    }

    //cancel by procurment
    public function rejectItem($id)
    {
        // Validate the incoming request
        request()->validate([
            'keterangan' => 'required|string',
            'no_po' => 'nullable|string', // Tambahkan validasi opsional untuk no_po
        ]);

        // Log id yang diterima
        \Log::info('Received id: ' . $id);

        // Cari data berdasarkan id
        $pengajuan = MstPoPengajuan::find($id);

        if (!$pengajuan) {
            \Log::error('No data found for id: ' . $id); // Log jika data tidak ditemukan
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Jika no_po diisi, ubah status menjadi 7
        if (request()->filled('no_po')) {
            \Log::info('No PO filled for id: ' . $id);
            $pengajuan->update([
                'no_po' => request('no_po'),
                'status_2' => 8,
            ]);
        } else {
            // Jika no_po tidak diisi, tetap simpan keterangan dan status
            $pengajuan->update([
                'status_2' => 8, // Ubah status menjadi 6
            ]);
        }

        // Save the keterangan in TrsPoPengajuan
        TrsPoPengajuan::create([
            'id_fpb' => $pengajuan->id,
            'nama_barang' => $pengajuan->nama_barang,
            'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
            'keterangan' => request('keterangan'), // Get keterangan from request body
            'status' => $pengajuan->status_2, // Simpan status yang terbaru
        ]);

        return response()->json(['message' => 'Status updated successfully for id: ' . $id]);
    }

    //cancel by procurment
    public function updateCancelByProcurment($id)
    {
        // Validate the incoming request
        request()->validate([
            'keterangan' => 'required|string',
            'no_po' => 'nullable|string', // Tambahkan validasi opsional untuk no_po
        ]);

        // Log id yang diterima
        \Log::info('Received id: ' . $id);

        // Cari data berdasarkan id
        $pengajuan = MstPoPengajuan::find($id);

        if (!$pengajuan) {
            \Log::error('No data found for id: ' . $id); // Log jika data tidak ditemukan
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Jika no_po diisi, ubah status menjadi 7
        if (request()->filled('no_po')) {
            \Log::info('No PO filled for id: ' . $id);
            $pengajuan->update([
                'no_po' => request('no_po'),
                'status_2' => 8,
            ]);
        } else {
            // Jika no_po tidak diisi, tetap simpan keterangan dan status
            $pengajuan->update([
                'status_2' => 8, // Ubah status menjadi 6
            ]);
        }

        // Save the keterangan in TrsPoPengajuan
        TrsPoPengajuan::create([
            'id_fpb' => $pengajuan->id,
            'nama_barang' => $pengajuan->nama_barang,
            'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
            'keterangan' => request('keterangan'), // Get keterangan from request body
            'status' => $pengajuan->status_2, // Simpan status yang terbaru
        ]);

        return response()->json(['message' => 'Status updated successfully for id: ' . $id]);
    }
    //pengajuan Cancel
    public function updateCancelBySecHead($id)
    {
        // Validate the incoming request
        request()->validate([
            'keterangan' => 'required|string',
            'no_po' => 'nullable|string', // Tambahkan validasi opsional untuk no_po
        ]);

        // Log id yang diterima
        \Log::info('Received id: ' . $id);

        // Cari data berdasarkan id
        $pengajuan = MstPoPengajuan::find($id);

        if (!$pengajuan) {
            \Log::error('No data found for id: ' . $id); // Log jika data tidak ditemukan
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Update status hanya untuk data dengan ID tertentu
        if (request()->filled('no_po')) {
            \Log::info('No PO filled for id: ' . $id);
            $pengajuan->update([
                'status_1' => 10, // Ubah status menjadi 11 (spesifik untuk ID ini saja)
                'status_2' => 10,
            ]);
        } else {
            $pengajuan->update([
                'status_1' => 10, // Ubah status menjadi 10 (spesifik untuk ID ini saja)
                'status_2' => 10,
            ]);
        }

        // Cari semua data yang memiliki no_fpb yang sama dan update catatan saja
        MstPoPengajuan::where('no_fpb', $pengajuan->no_fpb)
            ->update(['catatan' => 'Terdapat Reject Item']); // Update catatan untuk semua entri dengan no_fpb yang sama

        // Save the keterangan in TrsPoPengajuan untuk baris yang diperbarui
        TrsPoPengajuan::create([
            'id_fpb' => $pengajuan->id,
            'nama_barang' => $pengajuan->nama_barang,
            'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
            'keterangan' => request('keterangan'), // Get keterangan from request body
            'status' => $pengajuan->status_1, // Simpan status yang terbaru
        ]);

        return response()->json(['message' => 'Status updated successfully for id: ' . $id . ' and catatan updated for no_fpb: ' . $pengajuan->no_fpb]);
    }

    public function updateFinishByProcurment($no_fpb)
    {
        // Log no_fpb yang diterima
        \Log::info('Received no_fpb: ' . $no_fpb);

        // Ubah kembali tanda minus (-) menjadi garis miring (/)
        $no_fpb = str_replace('-', '/', $no_fpb);
        \Log::info('Transformed no_fpb: ' . $no_fpb);

        // Cari data berdasarkan no_fpb yang telah diubah
        $pengajuanList = MstPoPengajuan::where('no_fpb', $no_fpb)->get();

        if ($pengajuanList->isEmpty()) {
            \Log::error('No data found for no_fpb: ' . $no_fpb); // Log jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data not found');
        }

        // Update status data yang sesuai
        foreach ($pengajuanList as $pengajuan) {
            $pengajuan->update([
                'status_1' => 9,
                'catatan' => '', // Atau gunakan '' untuk string kosong
            ]);

            // Tambah data ke dalam model TrsPoPengajuan untuk setiap id yang ditemukan
            TrsPoPengajuan::create([
                'id_fpb' => $pengajuan->id,
                'nama_barang' => $pengajuan->nama_barang, // Menggunakan id dari setiap data MstPoPengajuan
                'modified_at' => auth()->user()->name, // Menyimpan nama user yang login
                'status' => 9,
            ]);
        }

        return redirect()->route('index.PO.procurement')->with('success', 'Status updated successfully for no_fpb: ' . $no_fpb);
    }

    public function downloadFile($id)
    {
        // Cari data berdasarkan ID
        $mstPoPengajuan = MstPoPengajuan::find($id);

        if (!$mstPoPengajuan) {
            return abort(404, 'File tidak ditemukan.');
        }

        // Dapatkan nama file dari model
        $fileName = $mstPoPengajuan->file;

        if (!$fileName) {
            return abort(404, 'Tidak ada file yang terlampir.');
        }

        // Tentukan path file di direktori public/assets/pre_order
        $filePath = public_path('assets/pre_order/' . $fileName);

        // Cek apakah file ada di server
        if (!file_exists($filePath)) {
            return abort(404, 'File tidak ditemukan di server.');
        }

        // Kembalikan file sebagai response download
        return response()->download($filePath, $fileName);
    }

    public function getPoHistory($id)
    {
        $history = DB::table('mst_po_pengajuans as mst')
            ->join('trs_po_pengajuans as trs', 'mst.id', '=', 'trs.id_fpb')
            ->select('mst.no_fpb', 'trs.no_po', 'mst.nama_barang', 'trs.keterangan', 'trs.status', 'trs.modified_at', 'trs.created_at')
            ->where('mst.id', $id)
            ->orderBy('trs.created_at', 'desc') // Urutkan berdasarkan modified_at secara descending
            ->get();

        return response()->json(['data' => $history]);
    }
}

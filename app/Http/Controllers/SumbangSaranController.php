<?php

namespace App\Http\Controllers;

use App\Models\PenilaianSS;
use App\Models\Role;
use App\Models\SumbangSaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SumbangSaranController extends Controller
{
    //
    // showpage
    public function showSS()
    {
        // Ambil ID pengguna yang sedang login
        $userLogin = Auth::id();

        // Query MySQL untuk menggabungkan data dari SumbangSaran, Penilaians, dan Users
        $data = DB::select('
            SELECT 
                sumbang_sarans.id,
                sumbang_sarans.id_user,
                sumbang_sarans.tgl_pengajuan_ide,
                sumbang_sarans.lokasi_ide,
                sumbang_sarans.tgl_diterapkan,
                sumbang_sarans.judul,
                sumbang_sarans.keadaan_sebelumnya,
                sumbang_sarans.image,
                sumbang_sarans.usulan_ide,
                sumbang_sarans.image_2,
                sumbang_sarans.keuntungan_ide,
                sumbang_sarans.tgl_verifikasi,
                sumbang_sarans.status,
                sumbang_sarans.updated_at,
                penilaians.id_users,
                penilaians.ss_id,
                penilaians.nilai,
                penilaians.tambahan_nilai,
                ((penilaians.nilai + COALESCE(penilaians.tambahan_nilai, 0))) AS total_nilai,
                (((penilaians.nilai + COALESCE(penilaians.tambahan_nilai, 0))) * 2000) AS hasil_akhir,
                users.name,
                users.npk
            FROM 
                sumbang_sarans
            LEFT JOIN 
                penilaians ON sumbang_sarans.id = penilaians.ss_id
            LEFT JOIN 
                users ON sumbang_sarans.id_user = users.id
            WHERE 
                sumbang_sarans.id_user = ? 
                AND sumbang_sarans.status IN (1, 2, 3, 4, 5, 6, 7)
            ORDER BY 
                FIELD(sumbang_sarans.status, 7, 6, 5, 4, 3, 2, 1),
                sumbang_sarans.updated_at DESC
        ', [$userLogin]);

        // Ambil hanya id user untuk menghindari "N + 1" query
        $userIds = collect($data)->pluck('id_user')->unique()->toArray();

        // Ambil data peran (role) berdasarkan user ids
        $usersRoles = User::whereIn('users.id', $userIds)
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->pluck('roles.role', 'users.id');

        return view('ss.createSS', compact('data', 'usersRoles'));
    }

    public function dashboardSS()
    {
        return view('ss.dashboardSS');
    }

    public function forumSS(Request $request)
    {
        // Mulai dengan membangun kueri dasar
        $query = SumbangSaran::with('user');

        // Menangani pencarian jika ada
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('judul', 'like', '%'.$search.'%')
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                });
        }

        // Menangani pengurutan
        if ($request->has('sort')) {
            if ($request->input('sort') === 'likesLow') {
                $query->orderByRaw('CAST(suka AS UNSIGNED) ASC');
            } elseif ($request->input('sort') === 'likesHigh') {
                $query->orderByRaw('CAST(suka AS UNSIGNED) DESC');
            } elseif ($request->input('sort') === 'newest') {
                $query->orderBy('tgl_pengajuan', 'desc');
            } elseif ($request->input('sort') === 'oldest') {
                $query->orderBy('tgl_pengajuan', 'asc');
            }
        } else {
            // Jika tidak ada parameter sort yang diberikan, urutkan berdasarkan like terbanyak secara default
            $query->orderByRaw('CAST(suka AS UNSIGNED) DESC');
        }

        // Tambahkan kondisi untuk memastikan tgl_pengajuan tidak kosong
        $query->whereNotNull('tgl_pengajuan');

        // Ambil data berdasarkan query yang telah dibuat
        $data = $query->get();

        return view('ss.forumSS', compact('data'));
    }

    public function showKonfirmasiForeman()
    {
        $data = SumbangSaran::with('user')
        ->whereIn('sumbang_sarans.status', [2, 3, 4, 5]) // Tambahkan alias untuk kolom status
        ->orderByRaw('FIELD(sumbang_sarans.status, 5, 4, 3, 2)') // Tambahkan alias untuk kolom status
        ->orderByDesc('sumbang_sarans.created_at') // Tambahkan alias untuk kolom created_at
        ->paginate();

        // Ambil hanya id user untuk menghindari "N + 1" query
        $userIds = $data->pluck('id_user')->unique()->toArray();

        // Ambil data peran (role) berdasarkan user ids
        $usersRoles = User::whereIn('users.id', $userIds)
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->pluck('roles.role', 'users.id');

        return view('ss.konfirmForeman', compact('data', 'usersRoles'));
    }

    public function showKonfirmasiDeptHead()
    {
        $data = SumbangSaran::with('user')
    ->whereIn('sumbang_sarans.status', [3, 4, 5]) // Tambahkan alias untuk kolom status
    ->orderByRaw('FIELD(sumbang_sarans.status, 5, 4, 3)') // Tambahkan alias untuk kolom status
    ->orderByDesc('sumbang_sarans.created_at') // Tambahkan alias untuk kolom created_at
    ->paginate();

        // Ambil hanya id user untuk menghindari "N + 1" query
        $userIds = $data->pluck('id_user')->unique()->toArray();

        // Ambil data peran (role) berdasarkan user ids
        $usersRoles = User::whereIn('users.id', $userIds)
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->pluck('roles.role', 'users.id');

        return view('ss.konfimDeptHead', compact('data', 'usersRoles'));
    }

    public function showKonfirmasiKomite()
    {
        $data = DB::select('
            SELECT 
                sumbang_sarans.id,
                sumbang_sarans.id_user,
                sumbang_sarans.tgl_pengajuan_ide,
                sumbang_sarans.lokasi_ide,
                sumbang_sarans.tgl_diterapkan,
                sumbang_sarans.judul,
                sumbang_sarans.keadaan_sebelumnya,
                sumbang_sarans.image,
                sumbang_sarans.usulan_ide,
                sumbang_sarans.image_2,
                sumbang_sarans.keuntungan_ide,
                sumbang_sarans.status,
                sumbang_sarans.created_at,
                penilaians.id_users,
                penilaians.ss_id,
                penilaians.nilai,
                users.name,
                users.npk
            FROM 
                sumbang_sarans
            JOIN 
                penilaians ON sumbang_sarans.id = penilaians.ss_id
            JOIN 
                users ON sumbang_sarans.id_user = users.id
            WHERE 
                sumbang_sarans.status IN (4, 5, 6, 7)
            ORDER BY 
                FIELD(sumbang_sarans.status, 7, 6, 5, 4),
                sumbang_sarans.created_at DESC
        ');

        // Ambil hanya id user untuk menghindari "N + 1" query
        $userIds = collect($data)->pluck('id_user')->unique()->toArray();

        // Ambil data peran (role) berdasarkan user ids
        $usersRoles = User::whereIn('users.id', $userIds)
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->pluck('roles.role', 'users.id');

        return view('ss.konfirmKomite', compact('data', 'usersRoles'));
    }

    public function showKonfirmasiHRGA()
    {
        // Query MySQL untuk gabungkan data SumbangSaran, Penilaians, dan Users
        $data = DB::select('
            SELECT 
                sumbang_sarans.id,
                sumbang_sarans.id_user,
                sumbang_sarans.tgl_pengajuan_ide,
                sumbang_sarans.lokasi_ide,
                sumbang_sarans.tgl_diterapkan,
                sumbang_sarans.judul,
                sumbang_sarans.keadaan_sebelumnya,
                sumbang_sarans.image,
                sumbang_sarans.usulan_ide,
                sumbang_sarans.image_2,
                sumbang_sarans.keuntungan_ide,
                sumbang_sarans.tgl_verifikasi,
                sumbang_sarans.status,
                sumbang_sarans.created_at,
                penilaians.id_users,
                penilaians.ss_id,
                penilaians.nilai,
                penilaians.tambahan_nilai,
                ((penilaians.nilai + COALESCE(penilaians.tambahan_nilai, 0))) AS total_nilai,
                (((penilaians.nilai + COALESCE(penilaians.tambahan_nilai, 0))) * 2000) AS hasil_akhir,
                users.name,
                users.npk
            FROM 
                sumbang_sarans
            JOIN 
                penilaians ON sumbang_sarans.id = penilaians.ss_id
            JOIN 
                users ON sumbang_sarans.id_user = users.id
            WHERE 
                sumbang_sarans.status IN (5, 6, 7)
            ORDER BY 
                FIELD(sumbang_sarans.status, 5, 6, 7),
                sumbang_sarans.created_at DESC
        ');

        // Ambil hanya id user untuk menghindari "N + 1" query
        $userIds = collect($data)->pluck('id_user')->unique()->toArray();

        // Ambil data peran (role) berdasarkan user ids
        $usersRoles = User::whereIn('users.id', $userIds)
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->pluck('roles.role', 'users.id');

        return view('ss.konfirmHRGA', compact('data', 'usersRoles'));
    }

    public function chartSection(Request $request)
    {
        // Eksekusi query untuk mengambil data dari database
        $dataFromSQL = SumbangSaran::whereIn('modified_by', ['DH Finance', 'Engineering', 'DH Sales', 'DH Productions'])
        ->selectRaw("MONTH(tgl_pengajuan) as month,
                 CASE 
                    WHEN modified_by = 'DH Finance' THEN 'FIN ACC HRGA IT'
                    WHEN modified_by = 'Engineering' THEN 'HT'
                    WHEN modified_by = 'DH Sales' THEN 'Sales'
                    WHEN modified_by = 'DH Productions' THEN 'Supply Chain & Productions'
                    ELSE modified_by
                 END AS modified_by,
                 COUNT(*) as jumlah")
        ->groupBy('month', 'modified_by')
        ->orderBy('month')
        ->get();

        // Format hasil query menjadi format yang dapat digunakan oleh Highcharts
        $categories = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];

        $sections = [
            'FIN ACC HRGA IT' => array_fill(0, 12, 0),
            'HT' => array_fill(0, 12, 0),
            'Sales' => array_fill(0, 12, 0),
            'Supply Chain & Productions' => array_fill(0, 12, 0),
        ];

        foreach ($dataFromSQL as $item) {
            $sections[$item->modified_by][$item->month - 1] = $item->jumlah;
        }

        $series = [];
        foreach ($sections as $section => $data) {
            $series[] = [
                'name' => $section,
                'data' => $data,
            ];
        }

        // Mengembalikan data dalam format JSON
        return response()->json([
            'categories' => $categories,
            'series' => $series,
        ]);
    }

    public function chartEmployee(Request $request)
    {
        $roles = $request->input('roles');

        // Hitung total data sumbang saran
        $totalData = DB::table('sumbang_sarans')->count();

        // Query untuk mengambil data peran yang sesuai
        $data = DB::table('sumbang_sarans')
            ->join('users', 'sumbang_sarans.id_user', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->selectRaw('MONTH(tgl_pengajuan) as month, roles.role, COUNT(*) as count')
            ->when(!empty($roles), function ($query) use ($roles) {
                return $query->whereIn('roles.role', $roles);
            })
            ->groupBy('roles.role', 'month')
            ->get();

        // Format data untuk grafik
        $categories = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];

        $monthlyData = [];
        $totalMonthlyData = array_fill(0, 12, 0);

        foreach ($data as $item) {
            $role = $item->role;
            $month = $item->month - 1; // Karena index bulan dimulai dari 0
            $count = $item->count;

            if (!isset($monthlyData[$role])) {
                $monthlyData[$role] = array_fill(0, 12, 0);
            }

            $monthlyData[$role][$month] = $count;
            $totalMonthlyData[$month] += $count;
        }

        $series = [];

        foreach ($monthlyData as $role => $data) {
            $series[] = [
                'name' => $role,
                'type' => 'column',
                'data' => $data,
            ];
        }

        $series[] = [
            'name' => 'Total Sumbang Saran',
            'type' => 'line',
            'data' => $totalMonthlyData,
        ];

        return response()->json([
            'categories' => $categories,
            'series' => $series,
        ]);
    }

    public function chartUser(Request $request)
    {
        $employeeType = $request->input('employeeType');
        $userId = auth()->user()->id;
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Debugging: Log input yang diterima
        \Log::info("Employee Type: $employeeType, User ID: $userId, Start Date: $startDate, End Date: $endDate");

        $query = DB::table('sumbang_sarans')
            ->join('users', 'sumbang_sarans.id_user', '=', 'users.id');

        if ($startDate && $endDate) {
            $query->whereBetween('sumbang_sarans.tgl_pengajuan', [
                date('Y-m-01', strtotime($startDate)),
                date('Y-m-t', strtotime($endDate)),
            ]);
        }

        // Debugging: Log query yang akan dieksekusi
        \Log::info('SQL Query: '.$query->toSql());

        if ($employeeType === 'AllEmployee') {
            $data = $query->select('users.id as user_id', 'users.name as user_name', DB::raw('count(sumbang_sarans.id) as submission_count'))
                          ->groupBy('users.id', 'users.name')
                          ->orderBy('submission_count', 'DESC')
                          ->havingRaw('count(sumbang_sarans.id) > 0')
                          ->get();
        } elseif ($employeeType === 'User') {
            $data = $query->select('users.id as user_id', 'users.name as user_name', DB::raw('count(sumbang_sarans.id) as submission_count'))
                          ->where('users.id', $userId)
                          ->groupBy('users.id', 'users.name')
                          ->get();
        } else {
            $data = collect(); // Pastikan $data selalu terdefinisi sebagai collection kosong jika tidak ada kondisi yang terpenuhi
        }

        // Debugging: Log hasil query
        \Log::info('Query Result: '.json_encode($data));

        $formattedData = $data->map(function ($item) {
            return ['name' => $item->user_name, 'y' => $item->submission_count];
        });

        return response()->json(['data' => $formattedData]);
    }

    public function chartMountEmployee(Request $request)
    {
        $startMonth = $request->input('start_periode');
        $endMonth = $request->input('end_periode');

        // Define categories
        $categories = [
            'Sales' => ['DH Sales', 'SC Sales', 'UR Sales'],
            'HT' => ['Engineering', 'UR Maintenance'],
            'SupplyChainProduction' => ['UR Productions', 'SC Productions'],
            'FinnAccHrgaIT' => ['DH Finance', 'UR Finance', 'SC Finance', 'SC HRGA', 'UR HRGA'],
        ];

        $query = DB::table('sumbang_sarans')
            ->select('modified_by', DB::raw('count(id) as submission_count'))
            ->groupBy('modified_by');

        if ($startMonth && $endMonth) {
            $query->whereBetween('tgl_pengajuan', [
                date('Y-m-d', strtotime($startMonth.'-01')),
                date('Y-m-t', strtotime($endMonth)),
            ]);
        }

        $data = $query->get();

        // Initialize counts for each category
        $groupedData = [];
        foreach ($categories as $category => $values) {
            $groupedData[$category] = 0;
        }

        // Group data by categories
        foreach ($data as $item) {
            foreach ($categories as $category => $values) {
                if (in_array($item->modified_by, $values)) {
                    $groupedData[$category] += $item->submission_count;
                    break;
                }
            }
        }

        $totalCount = array_sum($groupedData);

        // Format data for Highcharts
        $formattedData = [];
        foreach ($groupedData as $category => $count) {
            $formattedData[] = ['name' => $category, 'y' => $count];
        }

        return response()->json([
            'total' => $totalCount,
            'data' => $formattedData,
        ]);
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
        $sumbangSaran->tgl_pengajuan = Carbon::now();
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
        $sumbangSaran = SumbangSaran::with('user')->findOrFail($id);

        return response()->json([
            'user' => $sumbangSaran->user,
            'tgl_pengajuan_ide' => $sumbangSaran->tgl_pengajuan_ide,
            'lokasi_ide' => $sumbangSaran->lokasi_ide,
            'tgl_diterapkan' => $sumbangSaran->tgl_diterapkan,
            'judul' => $sumbangSaran->judul,
            'keadaan_sebelumnya' => $sumbangSaran->keadaan_sebelumnya,
            'usulan_ide' => $sumbangSaran->usulan_ide,
            'keuntungan_ide' => $sumbangSaran->keuntungan_ide,
            'file_name' => $sumbangSaran->file_name,
            'image' => $sumbangSaran->image,
            'file_name_2' => $sumbangSaran->file_name_2,
            'image_2' => $sumbangSaran->image_2,
        ]);
    }

    public function getSumbangSaran($id)
    {
        try {
            // Retrieve the sumbang saran data by ID
            $sumbangSaran = SumbangSaran::with('user')->findOrFail($id);

            // Send data to the view to be displayed in the edit modal
            return response()->json($sumbangSaran);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where no query results are found
            return response()->json(['error' => 'Sumbang Saran not found for ID: '.$id], 404);
        } catch (\Exception $e) {
            // Handle other types of exceptions
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getPenilaians($id)
    {
        // Retrieve the sumbang saran data by ID
        $sumbangSaran = SumbangSaran::findOrFail($id);

        // Retrieve related penilaians
        $penilaians = PenilaianSS::where('ss_id', $id)->get();

        // Include file paths in the response if available
        $sumbangSaran->edit_image_url = $sumbangSaran->image ? asset('assets/image/'.$sumbangSaran->image) : null;
        $sumbangSaran->edit_image_2_url = $sumbangSaran->image_2 ? asset('assets/image/'.$sumbangSaran->image_2) : null;

        // Prepare the data to return as JSON
        $response = [
            'sumbangSaran' => $sumbangSaran,
            'penilaians' => $penilaians,
        ];

        // Return the data as JSON
        return response()->json($response);
    }

    public function showSecHead($id)
    {
        $data = SumbangSaran::with('user')->findOrFail($id);

        if ($data) {
            return response()->json([
                'user' => $data->user,
                'tgl_pengajuan_ide' => $data->tgl_pengajuan_ide,
                'lokasi_ide' => $data->lokasi_ide,
                'tgl_diterapkan' => $data->tgl_diterapkan,
                'judul' => $data->judul,
                'keadaan_sebelumnya' => $data->keadaan_sebelumnya,
                'usulan_ide' => $data->usulan_ide,
                'keuntungan_ide' => $data->keuntungan_ide,
                'file_name' => $data->file_name,
                'image' => $data->image,
                'file_name_2' => $data->file_name_2,
                'image_2' => $data->image_2,
                'id' => $data->id,
            ]);
        }

        return response()->json(['message' => 'Data not found'], 404);
    }

    public function getNilai($id)
    {
        // Log the incoming ID
        Log::info('Fetching sumbang saran with ID:', ['id' => $id]);

        // Retrieve the sumbang saran data by ID
        $sumbangSaran = SumbangSaran::findOrFail($id);

        // Retrieve related penilaian
        $penilaian = PenilaianSS::where('ss_id', $id)->first();

        // Log the penilaian data
        Log::info('Penilaian data:', ['penilaian' => $penilaian]);

        // Check if $penilaian is null before trying to access its properties
        $nilai = $penilaian ? $penilaian->nilai : null;

        // Log the nilai
        Log::info('Nilai:', ['nilai' => $nilai]);

        // Prepare the data to return as JSON
        $response = [
            'sumbangSaran' => $sumbangSaran,
            'nilai' => $nilai, // Send 'nilai' if available
        ];

        // Log the response data
        Log::info('Response:', ['response' => $response]);

        // Return the data as JSON
        return response()->json($response);
    }

    public function getTambahNilai($id)
    {
        // Log the incoming ID
        Log::info('Fetching sumbang saran with ID:', ['id' => $id]);

        // Retrieve the sumbang saran data by ID
        $sumbangSaran = SumbangSaran::with('user')->findOrFail($id);

        // Retrieve related penilaian
        $penilaian = PenilaianSS::where('ss_id', $id)->first();

        // Log the penilaian data
        Log::info('Penilaian data:', ['penilaian' => $penilaian]);

        // Check if $penilaian is null before trying to access its properties
        $tambahan_nilai = $penilaian ? $penilaian->tambahan_nilai : null;

        // Log the nilai
        Log::info('tambahan_nilai:', ['tambahan_nilai' => $tambahan_nilai]);

        // Prepare the data to return as JSON
        $response = [
            'sumbangSaran' => $sumbangSaran,
            'tambahan_nilai' => $tambahan_nilai, // Send 'nilai' if available
        ];

        // Log the response data
        Log::info('Response:', ['response' => $response]);

        // Return the data as JSON
        return response()->json($response);
    }

    public function updateSS(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sumbang_sarans,id',
            'tgl_pengajuan_ide' => 'required|date',
            'lokasi_ide' => 'required|string|max:255',
            'tgl_diterapkan' => 'nullable|date',
            'judul' => 'required|string|max:255',
            'keadaan_sebelumnya' => 'required|string',
            'usulan_ide' => 'required|string',
            'keuntungan_ide' => 'nullable|string',
            'edit_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
            'edit_image_2' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
        ]);

        $sumbangSaran = SumbangSaran::find($request->id);

        if (!$sumbangSaran) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $sumbangSaran->tgl_pengajuan_ide = $request->tgl_pengajuan_ide;
        $sumbangSaran->lokasi_ide = $request->lokasi_ide;
        $sumbangSaran->tgl_diterapkan = $request->tgl_diterapkan;
        $sumbangSaran->judul = $request->judul;
        $sumbangSaran->keadaan_sebelumnya = $request->keadaan_sebelumnya;
        $sumbangSaran->usulan_ide = $request->usulan_ide;
        $sumbangSaran->keuntungan_ide = $request->keuntungan_ide;

        if ($request->hasFile('edit_image')) {
            if ($sumbangSaran->image) {
                Storage::delete($sumbangSaran->image);
            }
            $path = $request->file('edit_image')->store('uploads');
            $sumbangSaran->image = $path;
        }

        if ($request->hasFile('edit_image_2')) {
            if ($sumbangSaran->image_2) {
                Storage::delete($sumbangSaran->image_2);
            }
            $path2 = $request->file('edit_image_2')->store('uploads');
            $sumbangSaran->image_2 = $path2;
        }

        $sumbangSaran->save();

        return response()->json(['success' => 'Data berhasil diperbarui.']);
    }

    public function kirimSS($id)
    {
        // Temukan data berdasarkan ID
        $sumbangSaran = SumbangSaran::findOrFail($id);

        // Ubah status menjadi 0
        $sumbangSaran->status = 2;

        // Simpan perubahan
        $sumbangSaran->save();

        // Kembalikan respons JSON
        return response()->json(['message' => 'Data berhasil dikirim'], 200);
    }

    public function kirimSS2($id)
    {
        // Temukan data berdasarkan ID
        $sumbangSaran = SumbangSaran::findOrFail($id);

        // Ubah status menjadi 0
        $sumbangSaran->status = 3;

        // Simpan perubahan
        $sumbangSaran->save();

        // Kembalikan respons JSON
        return response()->json(['message' => 'Data berhasil dikirim'], 200);
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

    public function simpanPenilaian(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $request->validate([
            'ss_id' => 'required|integer',
            'telah_direvisi' => 'nullable|boolean',
            'belum_diterapkan' => 'nullable|boolean',
            'sedang_diterapkan' => 'nullable|boolean',
            'sudah_diterapkan' => 'nullable|boolean',
            'tidak_bisa_diterapkan' => 'nullable|boolean',
            'keterangan' => 'nullable|string',
        ]);

        // Buat instance PenilaianSS baru
        $penilaian = new PenilaianSS();

        // Atur atribut berdasarkan data yang diterima dari permintaan
        if (isset($request->telah_direvisi)) {
            $penilaian->telah_direvisi = $request->telah_direvisi;
        }
        if (isset($request->belum_diterapkan)) {
            $penilaian->belum_diterapkan = $request->belum_diterapkan;
        }
        if (isset($request->sedang_diterapkan)) {
            $penilaian->sedang_diterapkan = $request->sedang_diterapkan;
        }
        if (isset($request->sudah_diterapkan)) {
            $penilaian->sudah_diterapkan = $request->sudah_diterapkan;
        }
        if (isset($request->tidak_bisa_diterapkan)) {
            $penilaian->tidak_bisa_diterapkan = $request->tidak_bisa_diterapkan;
        }
        $penilaian->keterangan = $request->keterangan;
        $penilaian->ss_id = $request->ss_id;
        $penilaian->modified_by = $request->user()->name;

        $penilaian->id_users = $request->user()->id;
        // Simpan data Penilaian
        $penilaian->save();

        // Set status pada model SumbangSaran menjadi 4
        $sumbangSaran = SumbangSaran::findOrFail($request->ss_id);
        $sumbangSaran->status = 4;
        $sumbangSaran->tgl_verifikasi = Carbon::now(); // Simpan tanggal verifikasi
        $sumbangSaran->modified_by = $request->user()->roles->role;
        $sumbangSaran->save();

        // Jika penyimpanan berhasil, kembalikan respons berhasil
        return response()->json(['message' => 'Data Penilaian berhasil disimpan'], 200);
    }

    public function submitNilai(Request $request)
    {
        Log::info('submitNilai dipanggil', $request->all()); // Log request data

        // Validasi data yang diterima dari formulir
        $request->validate([
            'ss_id' => 'required|integer',
            'nilai' => 'required|integer',
        ]);

        // Cari entri PenilaianSS yang ada berdasarkan ss_id
        $penilaian = PenilaianSS::where('ss_id', $request->ss_id)->first();

        if ($penilaian) {
            // Jika entri ditemukan, update data
            $penilaian->nilai = $request->nilai;
            $penilaian->id_users = $request->user()->id;
            $penilaian->modified_by = $request->user()->name;
            $penilaian->save();
        } else {
            // Jika entri tidak ditemukan, buat entri baru (opsional)
            $penilaian = new PenilaianSS();
            $penilaian->nilai = $request->nilai;
            $penilaian->ss_id = $request->ss_id;
            $penilaian->id_users = $request->user()->id;
            $penilaian->modified_by = $request->user()->name;
            $penilaian->save();
        }

        // Update status pada model SumbangSaran menjadi 5
        $sumbangSaran = SumbangSaran::findOrFail($request->ss_id);
        $sumbangSaran->status = 5;
        $sumbangSaran->save();

        // Jika penyimpanan berhasil, kembalikan respons berhasil
        return response()->json(['message' => 'Data Penilaian berhasil disimpan'], 200);
    }

    public function submitTambahNilai(Request $request)
    {
        Log::info('submitTambahNilai dipanggil', $request->all()); // Log request data

        // Validasi data yang diterima dari formulir
        $validated = $request->validate([
            'ss_id' => 'required|integer',
            'tambahan_nilai' => 'required|integer',
        ]);

        // Cari entri PenilaianSS yang ada berdasarkan ss_id
        $penilaian = PenilaianSS::where('ss_id', $validated['ss_id'])->first();

        if ($penilaian) {
            // Jika entri ditemukan, update data
            $penilaian->tambahan_nilai = $validated['tambahan_nilai'];
            $penilaian->id_users = $request->user()->id;
            $penilaian->modified_by = $request->user()->name;

            $penilaian->save();
            Log::info('Penilaian diperbarui', ['penilaian' => $penilaian]);
        } else {
            // Jika entri tidak ditemukan, buat entri baru
            $penilaian = new PenilaianSS();
            $penilaian->tambahan_nilai = $validated['tambahan_nilai'];
            $penilaian->ss_id = $validated['ss_id'];
            $penilaian->id_users = $request->user()->id;
            $penilaian->modified_by = $request->user()->name;
            $penilaian->save();
            Log::info('Penilaian baru dibuat', ['penilaian' => $penilaian]);
        }

        // Update status pada model SumbangSaran menjadi 6
        $sumbangSaran = SumbangSaran::findOrFail($validated['ss_id']);
        $sumbangSaran->status = 6;
        $sumbangSaran->save();

        Log::info('Status SumbangSaran diperbarui', ['sumbangSaran' => $sumbangSaran]);

        // Jika penyimpanan berhasil, kembalikan respons berhasil
        return response()->json([
            'message' => 'Data Penilaian berhasil disimpan',
            'tambahan_nilai' => $penilaian->tambahan_nilai,
            'ss_id' => $penilaian->ss_id,
        ], 200);
    }

    public function updateStatusToBayar(Request $request)
    {
        Log::info('updateStatusToBayar dipanggil', $request->all()); // Log request data

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $ids = $request->ids;

        $updated = SumbangSaran::whereIn('id', $ids)->update(['status' => 7]);

        if ($updated) {
            Log::info('Status diperbarui', ['ids' => $ids]);

            return response()->json([
                'message' => 'Status berhasil diperbarui',
                'updated_ids' => $ids,
            ], 200);
        } else {
            return response()->json(['message' => 'Tidak ada status yang diperbarui'], 400);
        }
    }

    public function like($id)
    {
        $post = SumbangSaran::findOrFail($id);
        ++$post->suka;
        $post->save();

        return response()->json(['suka' => $post->suka]);
    }

    public function unlike($id)
    {
        $post = SumbangSaran::findOrFail($id);

        // Pastikan suka tidak negatif
        if ($post->suka > 0) {
            --$post->suka;
            $post->save();
        }

        return response()->json(['suka' => $post->suka]);
    }

    // Add this method to your controller
    public function exportKonfirmasiHRGA(Request $request)
    {
        $startPeriode = Carbon::parse($request->input('start_periode'))->format('Y-m-d');
        $endPeriode = Carbon::parse($request->input('end_periode'))->format('Y-m-d');

        // Query MySQL untuk menggabungkan data dari SumbangSaran, Penilaians, dan Users
        $data = DB::select('
        SELECT 
            sumbang_sarans.id,
            sumbang_sarans.id_user,
            sumbang_sarans.tgl_pengajuan_ide,
            sumbang_sarans.lokasi_ide,
            sumbang_sarans.tgl_diterapkan,
            sumbang_sarans.judul,
            sumbang_sarans.keadaan_sebelumnya,
            sumbang_sarans.image,
            sumbang_sarans.usulan_ide,
            sumbang_sarans.image_2,
            sumbang_sarans.keuntungan_ide,
            sumbang_sarans.tgl_verifikasi,
            sumbang_sarans.status,
            penilaians.id_users,
            penilaians.ss_id,
            penilaians.nilai,
            penilaians.tambahan_nilai,
            ((penilaians.nilai + COALESCE(penilaians.tambahan_nilai, 0))) AS total_nilai,
            (((penilaians.nilai + COALESCE(penilaians.tambahan_nilai, 0))) * 2000) AS hasil_akhir,
            users.name,
            users.npk,
            roles.role AS bagian -- Ambil peran (role) dari tabel roles
        FROM 
            sumbang_sarans
        JOIN 
            penilaians ON sumbang_sarans.id = penilaians.ss_id
        JOIN 
            users ON sumbang_sarans.id_user = users.id
        LEFT JOIN
            roles ON users.role_id = roles.id -- Gabungkan dengan tabel roles untuk mendapatkan peran (role)
        WHERE 
            sumbang_sarans.status IN (5, 6, 7)
            AND DATE(sumbang_sarans.tgl_verifikasi) BETWEEN ? AND ? 
        ORDER BY 
            FIELD(sumbang_sarans.status, 5, 6, 7),
            sumbang_sarans.tgl_verifikasi DESC
    ', [$startPeriode, $endPeriode]);

        // Mengembalikan data sebagai respons JSON
        return response()->json($data);
    }

    public function download($filename)
    {
        $filePath = public_path('assets/image/'.$filename);

        if (!file_exists($filePath)) {
            return abort(404, 'File not found.');
        }

        $fileContents = file_get_contents($filePath);

        return Response::make($fileContents, 200, [
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => 'attachment; filename="'.basename($filePath).'"',
        ]);
    }
}

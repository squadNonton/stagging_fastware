<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Handling;
use App\Models\ScheduleVisit;
use App\Models\TypeMaterial;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
// import Facade "Storage"
use Illuminate\View\View;

class DeptManController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // viewSubmission
    public function submission()
    {
        $view1 = Handling::with(['customers', 'type_materials', 'user']) // Menggunakan array untuk parameter with
            ->where('status', 0) // Menggunakan operator default '=' secara implisit
            ->orderBy('created_at', 'desc') // Urutkan secara descending berdasarkan kolom 'created_at'
            ->paginate();

        $view2 = Handling::with('customers', 'type_materials', 'user')
        ->whereIn('status', [1, 2, 3]) // Filter berdasarkan status 1, 2, dan 3
        ->orderByRaw('FIELD(status, 1, 2, 3)') // Urutkan berdasarkan urutan status yang diinginkan
        ->orderByDesc('created_at') // Jika perlu, urutkan secara descending berdasarkan kolom 'created_at' atau sesuaikan dengan kolom yang sesuai
        ->paginate();

        return view('deptman.submission', compact('view1', 'view2'));
    }

    public function scheduleVisit()
    {
        // Ambil semua data ScheduleVisit dari database
        // $scheduleVisits = ScheduleVisit::all();
        $scheduleVisits = DB::table('handlings')
                            ->join('customers', 'handlings.customer_id', '=', 'customers.id')
                            ->join('schedule_visits', 'handlings.id', '=', 'schedule_visits.handling_id')
                            ->select(
                                'handlings.id AS handling_id',
                                'customers.id AS customer_id',
                                'customers.name_customer',
                                'schedule_visits.schedule',
                                'schedule_visits.results',
                                'schedule_visits.due_date',
                                'schedule_visits.pic'
                            )
                            ->get();

        return view('deptman.scheduleVisit', compact('scheduleVisits'));
    }

    public function showHistoryClaimComplain()
    {
        $data2 = Handling::select(
            'handlings.id',
            'handlings.no_wo',
            'customers.customer_code',
            'customers.name_customer',
            'customers.area',
            'type_materials.type_name',
            'handlings.thickness',
            'handlings.weight',
            'handlings.outer_diameter',
            'handlings.inner_diameter',
            'handlings.length',
            'handlings.qty',
            'handlings.pcs',
            'handlings.category',
            'handlings.process_type',
            'handlings.type_1',
            'handlings.type_2',
            'handlings.image',
            'schedule_visits.schedule',
            'schedule_visits.results',
            'schedule_visits.due_date',
            'schedule_visits.pic',
            'handlings.status',
            'handlings.created_at',
            'users.name'
        )
        ->join('schedule_visits', 'handlings.id', '=', 'schedule_visits.handling_id')
        ->join('customers', 'handlings.customer_id', '=', 'customers.id')
        ->join('type_materials', 'handlings.type_id', '=', 'type_materials.id')
        ->join('users', 'users.id', '=', 'handlings.user_id')
        ->where('handlings.status', 3)
        ->orderByDesc('handlings.created_at')
        ->get();

        return view('deptman.historyClaimComplain', compact('data2'));
    }

    /**
     * edit.
     *
     * @param mixed $id
     */
    public function showConfirm(string $id): View
    {
        // Mengambil data handling berdasarkan ID
        $handlings = Handling::with(['customers', 'type_materials', 'user'])->findOrFail($id);

        // Mengambil semua data pelanggan
        $customers = Customer::all();

        // Mengambil semua data tipe bahan
        $type_materials = TypeMaterial::all();

        $user = User::all();

        // render view with handlings
        return view('deptman.confirm', compact('handlings', 'customers', 'type_materials', 'user'));
    }

    public function showFollowUp(string $id): View
    {
        // Mengambil data handling berdasarkan ID
        $handlings = Handling::with(['customers', 'type_materials', 'user'])->findOrFail($id);

        // Mengambil semua data pelanggan
        $customers = Customer::all();

        // Mengambil semua data tipe bahan
        $type_materials = TypeMaterial::all();

        $user = User::all();

        // Mengambil data schedule visit berdasarkan handling_id
        $data = ScheduleVisit::where('handling_id', $id)->get();

        // Cek apakah ada schedule visit dengan history_type bernilai 1
        $hasHistoryType1 = ScheduleVisit::where('handling_id', $id)->where('history_type', 1)->exists();

        // Mengambil semua data schedule visit
        $scheduleVisit = ScheduleVisit::all();

        // render view with handlings
        return view('deptman.followup', compact('handlings', 'customers', 'type_materials', 'data', 'user', 'scheduleVisit', 'hasHistoryType1'));
    }

    public function showHistoryProgres(string $id): View
    {
        // Mengambil data handling berdasarkan ID
        $handling = Handling::with(['customers', 'type_materials', 'user'])->findOrFail($id);

        // Mengambil semua data pelanggan
        $customers = Customer::all();

        // Mengambil semua data tipe bahan
        $type_materials = TypeMaterial::all();

        $user = User::all();

        // Mengambil data schedule visit berdasarkan handling_id
        $data = ScheduleVisit::where('handling_id', $id)->get();

        // Mengembalikan view 'deptman.historyProgres' dengan data yang dibutuhkan
        return view('deptman.historyProgres', compact('handling', 'customers', 'type_materials', 'data', 'user'));
    }

    public function showCloseProgres(string $id): View
    {
        $handlings = Handling::findOrFail($id);
        $customers = Customer::all();
        $type_materials = TypeMaterial::all();
        $data = ScheduleVisit::where('handling_id', $id)
                            ->whereHas('handlings', function ($query) {
                                $query->where('type_1', 'Komplain')
                                      ->orWhere('type_2', 'Klaim');
                            })
                            ->with('handlings')
                            ->get();

        return view('deptman.showCloseProgres', compact('handlings', 'customers', 'type_materials', 'data'));
    }

    /**
     * update.
     *
     * @param mixed $request
     */
    public function updateConfirm(Request $request, $id): RedirectResponse
    {
        // get post by ID
        $handlings = Handling::findOrFail($id);

        // Update post
        $handlings->update([
            'status' => 1,
        ]);

        // redirect to index
        return redirect()->route('submission')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function updateFollowUp(Request $request, $id)
    {
        // Validate for "finish" action
        if ($request->action == 'finish') {
            $request->validate([
                'results' => 'required|string|max:255',
                'file' => 'required|file|mimes:jpg,jpeg,png,pdf,xlsx,xls,ppt,pptx|max:10048',
            ]);
        }

        // Save file with a hash name
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->hashName();
            $originalFilename = $file->getClientOriginalName();
            $path = $file->move(public_path('assets/image'), $filename);
        } else {
            $filename = null;
            $originalFilename = null;
        }

        $scheduleVisit = new ScheduleVisit();
        if (isset($request->schedule)) {
            $scheduleVisit->schedule = date('Y-m-d H:i:s', strtotime($request->schedule));
        }
        $scheduleVisit->results = $request->results;
        if (isset($request->due_date)) {
            $scheduleVisit->due_date = date('Y-m-d H:i:s', strtotime($request->due_date));
        }
        $scheduleVisit->pic = $request->pic;
        $scheduleVisit->file = $filename;
        $scheduleVisit->file_name = $originalFilename;
        $scheduleVisit->handling_id = $request->handling_id;
        $scheduleVisit->users_id = $request->user()->id;

        if ($request->action == 'save') {
            $scheduleVisit->status = '1';
            $scheduleVisit->save();

            return response()->json(['message' => 'Data Berhasil Disimpan!', 'refresh' => true]);
        } elseif ($request->action == 'finish') {
            $scheduleVisit->status = '3';
            $scheduleVisit->save();

            // Temukan entitas Penanganan berdasarkan ID
            $handlings = Handling::findOrFail($request->handling_id);

            // Perbarui status Penanganan menjadi 2
            $handlings->update(['status' => 2]);

            return response()->json(['message' => 'Data Berhasil Disimpan!', 'redirect' => route('submission')]);
        } elseif ($request->action == 'claim') {
            $scheduleVisit->status = '1';
            $scheduleVisit->history_type = '1';
            $scheduleVisit->save();

            // Temukan entitas Penanganan berdasarkan ID
            $handlings = Handling::findOrFail($request->handling_id);

            // Perbarui status Penanganan menjadi 1
            $handlings->update([
                'type_1' => '',
                'type_2' => 'Klaim',
                'status' => 1,
            ]);

            return response()->json(['message' => 'Data Berhasil Disimpan!', 'refresh' => true]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Handling;
use App\Models\ScheduleVisit;
use App\Models\TypeMaterial;
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
        $view1 = Handling::with('customers', 'type_materials')
            ->where('status', '=', 0) // Filter berdasarkan status '0'
            ->orderByDesc('created_at') // Urutkan secara descending berdasarkan kolom 'created_at' atau sesuaikan dengan kolom yang sesuai
            ->paginate();

        $view2 = Handling::with('customers', 'type_materials')
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

    public function showHistoryCLaimComplain()
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
            'handlings.created_at'
        )
        ->join('schedule_visits', 'handlings.id', '=', 'schedule_visits.handling_id')
        ->join('customers', 'handlings.customer_id', '=', 'customers.id')
        ->join('type_materials', 'handlings.type_id', '=', 'type_materials.id')
        ->where('schedule_visits.status', 3)
        ->orderByDesc('handlings.created_at')
        ->get();

        // dd($data2);
        return view('deptman.historyClaimComplain', compact('data2'));
    }

    /**
     * edit.
     *
     * @param mixed $id
     */
    public function showConfirm(string $id): View
    {
        // get handlings by ID
        $handlings = Handling::findOrFail($id);
        $customers = Customer::all();
        $type_materials = TypeMaterial::all();

        // render view with handlings
        return view('deptman.confirm', compact('handlings', 'customers', 'type_materials'));
    }

    public function showFollowUp(string $id): View
    {
        // get handlings by ID
        $handlings = Handling::findOrFail($id);

        $customers = Customer::all();
        $type_materials = TypeMaterial::all();

        $data = ScheduleVisit::where('handling_id', $id)->with('handlings')->get();

        // render view with handlings
        return view('deptman.followup', compact('handlings', 'customers', 'type_materials', 'data'));
    }

    public function showHistoryProgres(string $id): View
    {
        // Mendapatkan data handling berdasarkan ID
        $handling = Handling::findOrFail($id);

        // Mengambil data customers dan type materials
        $customers = Customer::all();
        $type_materials = TypeMaterial::all();

        // Mengambil data schedule visit yang terkait dengan handling tersebut
        $data = ScheduleVisit::where('handling_id', $id)->with('handlings')->get();

        // Mengembalikan view 'deptman.historyProgres' dengan data yang dibutuhkan
        return view('deptman.historyProgres', compact('handling', 'customers', 'type_materials', 'data'));
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

    // followup/tindak lanjut
    public function updateFollowUp(Request $request, $id): RedirectResponse
    {
        // Lakukan sesuatu untuk aksi "Save"
        // Simpan file ke dalam sistem file dengan nama hash
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->hashName(); // Mendapatkan nama hash untuk file
            $originalFilename = $file->getClientOriginalName();
            $path = $file->move(public_path('assets/image'), $filename); // Simpan file dengan nama hash di direktori 'files'
        } else {
            $filename = null; // Atur nama file menjadi null jika tidak ada file yang diunggah
            $originalFilename = null;
        }

        if ($request->action == 'save') {
            // Lakukan sesuatu untuk aksi "Save"
            // Simpan data schedule_visit ke dalam tabel schedule_visit
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
            $scheduleVisit->status = '1';
            $scheduleVisit->handling_id = $request->handling_id; // Mengambil ID handling
            $scheduleVisit->users_id = $request->user()->id;
            $scheduleVisit->save();

            return redirect()->route('showFollowUp', ['id' => $id])->with(['success' => 'Data Berhasil Diubah!']);
        } elseif ($request->action == 'finish') {
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
            $scheduleVisit->status = '3';
            $scheduleVisit->handling_id = $request->handling_id; // Mengambil ID handling
            $scheduleVisit->users_id = $request->user()->id;
            $scheduleVisit->save();
            // Temukan entitas Handling berdasarkan ID
            $handlings = Handling::findOrFail($request->handling_id);

            // Update status Handling menjadi 3
            $handlings->update([
                'status' => 2,
            ]);

            // Simpan perubahan
            $handlings->save();

            return redirect()->route('submission')->with(['success' => 'Data Berhasil Diubah!']);
        } elseif ($request->action == 'claim') {
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
            $scheduleVisit->history_type = '1';
            $scheduleVisit->status = '1';
            $scheduleVisit->handling_id = $request->handling_id; // Mengambil ID handling
            $scheduleVisit->users_id = $request->user()->id;
            $scheduleVisit->save();

            // Temukan entitas Handling berdasarkan ID
            $handlings = Handling::findOrFail($request->handling_id);

            // Update status Handling menjadi 3
            $handlings->update([
                'type_1' => '',
                'type_2' => 'Klaim',
                'status' => 1,
            ]);

            // Simpan perubahan
            $handlings->save();

            return redirect()->route('showFollowUp', ['id' => $id])->with(['success' => 'Data Berhasil Diubah!']);
        }
    }
}

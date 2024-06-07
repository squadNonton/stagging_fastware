<?php

namespace App\Http\Controllers;

use App\Models\FormFPP;
use App\Models\TindakLanjut;
use App\Models\Mesin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class FormFPPController extends Controller
{
    public function HistoryFPP()
    {
        $formperbaikans = FormFPP::where('status', 3)->orderBy('updated_at', 'desc')->get();

        return view('fpps.history', compact('formperbaikans'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function DashboardProduction()
    {
        // Mengambil semua data FormFPP diurutkan berdasarkan updated_at terbaru
        $formperbaikans = FormFPP::orderBy('updated_at', 'desc')->get();

        // Mengambil semua data TindakLanjut diurutkan berdasarkan updated_at terbaru
        $tindaklanjuts = TindakLanjut::orderBy('updated_at', 'desc')->get();

        return view('fpps.index', compact('formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function DashboardMaintenance()
    {
        // Mengambil semua data FormFPP diurutkan berdasarkan updated_at terbaru
        $formperbaikans = FormFPP::orderBy('updated_at', 'desc')->get();

        // Mengambil semua data TindakLanjut diurutkan berdasarkan updated_at terbaru
        $tindaklanjuts = TindakLanjut::orderBy('updated_at', 'desc')->get();

        return view('maintenance.index', compact('formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function DashboardMaintenanceGA()
    {
        // Mengambil semua data FormFPP diurutkan berdasarkan updated_at terbaru
        $formperbaikans = FormFPP::orderBy('updated_at', 'desc')->get();

        // Mengambil semua data TindakLanjut diurutkan berdasarkan updated_at terbaru
        $tindaklanjuts = TindakLanjut::orderBy('updated_at', 'desc')->get();

        return view('ga.dashboardga', compact('formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function DashboardDeptMTCE()
    {
        // Mengambil semua data FormFPP diurutkan berdasarkan updated_at terbaru
        $formperbaikans = FormFPP::orderBy('updated_at', 'desc')->get();

        // Mengambil semua data TindakLanjut diurutkan berdasarkan updated_at terbaru
        $tindaklanjuts = TindakLanjut::orderBy('updated_at', 'desc')->get();

        return view('deptmtce.index', compact('formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function DashboardFPPGA()
    {
        // Mengambil semua data FormFPP diurutkan berdasarkan updated_at terbaru
        $formperbaikans = FormFPP::orderBy('updated_at', 'desc')->get();

        // Mengambil semua data TindakLanjut diurutkan berdasarkan updated_at terbaru
        $tindaklanjuts = TindakLanjut::orderBy('updated_at', 'desc')->get();

        return view('ga.approvedfpp', compact('formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function DashboardFPPSales()
    {
        // Mengambil semua data FormFPP diurutkan berdasarkan updated_at terbaru
        $formperbaikans = FormFPP::orderBy('updated_at', 'desc')->get();

        // Mengambil semua data TindakLanjut diurutkan berdasarkan updated_at terbaru
        $tindaklanjuts = TindakLanjut::orderBy('updated_at', 'desc')->get();

        return view('sales.index', compact('formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create(Mesin $mesin)
    {
        // Mendapatkan informasi pengguna yang sedang login
        $loggedInUser = Auth::user();

        $mesins = Mesin::orderBy('updated_at', 'asc')->get();
        return view('fpps.create', compact('mesins', 'loggedInUser'));
    }

    public function LihatMaintenance(FormFPP $formperbaikan, TindakLanjut $tindaklanjut)
    {
        $formperbaikans = FormFPP::latest()->get();
        $tindaklanjuts = TindakLanjut::latest()->get();

        $status = $formperbaikan->status;

        return view('maintenance.lihat', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts', 'tindaklanjut'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function LihatFPPSales(FormFPP $formperbaikan)
    {
        // Mengambil semua data FormFPP
        $formperbaikans = FormFPP::latest()->get();

        // Mengambil semua data TindakLanjut
        $tindaklanjuts = TindakLanjut::latest()->get();

        // // Assuming $formperbaikan is an instance of the FormFPP model
        $status = $formperbaikan->status;

        return view('sales.lihat', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function EditMaintenance(FormFPP $formperbaikan, TindakLanjut $tindaklanjut)
    {        // Mengambil semua data FormFPP
        $formperbaikans = FormFPP::latest()->get();
        // Mengambil semua data TindakLanjut
        $tindaklanjuts = TindakLanjut::latest()->get();

        $status = $formperbaikan->status;

        if ($status === 0) {
            return view('maintenance.edit', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts', 'tindaklanjut'))->with('i', (request()->input('page', 1) - 1) * 5);
        } elseif ($status === 2 || $status === 3) {
            return view('maintenance.lihat', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts', 'tindaklanjut'))->with('i', (request()->input('page', 1) - 1) * 5);
        } else if ($status === 1) {
            return view('maintenance.create', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts', 'tindaklanjut'))->with('i', (request()->input('page', 1) - 1) * 5);
        }
    }

    public function LihatFPP(FormFPP $formperbaikan, TindakLanjut $tindaklanjut)
    {
        // Mengambil semua data FormFPP
        $formperbaikans = FormFPP::latest()->get();

        // Mengambil semua data TindakLanjut
        $tindaklanjuts = TindakLanjut::latest()->get();

        // Mendapatkan status FormFPP
        $status = $formperbaikan->status;

        // Tentukan view berdasarkan status
        switch ($status) {
            case 0:
            case 1:
            case 3:
                return view('fpps.show', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
                break;
            case 2:
                // Periksa apakah ada catatan 'Dikonfirmasi Dept.Maintenance' dalam TindakLanjut
                $confirmed = $tindaklanjut
                    ->where('status', 2)
                    ->where('note', "Dikonfirmasi Dept.Head Maintenance")
                    ->count();
                if ($confirmed > 0) {
                    return view('fpps.closed', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
                } else {
                    return view('fpps.show', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
                }
                break;
            default:
                return view('fpps.index', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
                break;
        }
    }



    public function LihatDeptMTCE(FormFPP $formperbaikan)
    {
        // Mengambil semua data FormFPP
        $formperbaikans = FormFPP::latest()->get();

        // Mengambil semua data TindakLanjut
        $tindaklanjuts = TindakLanjut::latest()->get();

        // // Assuming $formperbaikan is an instance of the FormFPP model
        $status = $formperbaikan->status;

        // Determine view based on status
        if ($formperbaikan->status === 0 || $formperbaikan->status === 3 || $formperbaikan->status === 1) {
            return view('deptmtce.lihatfpp', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
        } elseif ($formperbaikan->status === 2) {
            return view('deptmtce.show', compact('formperbaikan', 'formperbaikans', 'tindaklanjuts'))->with('i', (request()->input('page', 1) - 1) * 5);
        } else {
            $viewName = 'deptmtce.index';
        }

        return view($viewName, compact('formperbaikan'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:4096', // Hanya menerima format gambar dengan ukuran maksimal 4MB
        ]);

        // Mendapatkan ID terakhir dari tabel FormFPP
        $lastFPPId = FormFPP::latest('id')->first()->id ?? 0;

        // Membuat nomor FPP dengan format FPXXXX, misalnya FP0001
        $id_fpp = 'FPP' . str_pad($lastFPPId + 1, 4, '0', STR_PAD_LEFT);

        // Menambahkan id_fpp ke dalam request
        $request->merge(['id_fpp' => $id_fpp]);

        // Mendapatkan informasi pengguna yang sedang login
        $loggedInUser = Auth::user();

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $gambarName = $gambar->getClientOriginalName();
            $gambar->move(public_path('assets/gambar'), $gambarName);
            $gambarPath = 'assets/gambar/' . $gambarName;
        } else {
            $gambarPath = null;
        }

        // Set nilai default untuk bidang status
        $request->merge(['status' => 0]);
        $request->merge(['status_2' => 0]);
        $request->merge(['note' => 'Form FPP Dibuat']);

        // Menentukan nilai 'mesin' berdasarkan opsi yang dipilih
        $mesin = $request->mesin;
        if ($request->mesin === "Others") {
            // Gunakan nilai dari bidang input 'namaAlatBantu'
            $mesin = $request->namaAlatBantu;
        } else {
            // Gunakan opsi yang dipilih untuk 'mesin'
            $mesin = $request->mesin;
        }

        // Membuat rekaman FormFPP
        $createdFormFPP = FormFPP::create([
            'id_fpp' => $request->id_fpp,
            'pemohon' => $request->pemohon,
            'tanggal' => $request->tanggal,
            'mesin' => $mesin,
            'section' => $request->section,
            'lokasi' => $request->lokasi,
            'kendala' => $request->kendala,
            'gambar' => $gambarPath,
            'status' => $request->status,
            'status_2' => $request->status_2,
        ]);

        // Membuat rekaman TindakLanjut terkait
        TindakLanjut::create([
            'id_fpp' => $createdFormFPP->id_fpp,
            'status' => $request->status,
            'note' => $request->note,
            'pic' => $loggedInUser->name, // Menyimpan nama PIC berdasarkan pengguna yang login
        ]);

        $users = User::whereIn('role_id', [5, 6, 7, 8, 9])->whereNotNull('email')->get();
        if ($users) {
            $emails = $users->pluck('email')->toArray();
            Mail::send('emails.buat_fpp', ['createdFormFPP' => $createdFormFPP], function ($message) use ($emails, $createdFormFPP) {
                $message->to($emails)
                    ->subject('Form Permintaan Perbaikan: ' . $createdFormFPP->id_fpp . ' telah dibuat');
            });
        }

        return redirect()->route('fpps.index')->with('success', 'Form FPP berhasil dibuat.');
    }



    public function edit(FormFPP $formperbaikan)
    {
        // Determine view based on status
        if ($formperbaikan->status === '0') {
            $viewName = 'maintenance.edit';
        } elseif ($formperbaikan->status === '2' || $formperbaikan->status === '3') {
            $viewName = 'maintenance.lihat';
        } else {
            $viewName = 'maintenance.create';
        }

        return view($viewName, compact('formperbaikan'));
    }

    public function update(Request $request, FormFPP $formperbaikan, TindakLanjut $tindaklanjut): RedirectResponse
    {
        // Handle file upload/update for attachment_file
        if ($request->hasFile('attachment_file')) {
            $attachmentFile = $request->file('attachment_file');
            $attachmentFileName = $attachmentFile->getClientOriginalName();
            $attachmentFile->move(public_path('assets/attachment'), $attachmentFileName);
            $attachmentFilePath = 'assets/attachment/' . $attachmentFileName;
        } else {
            // Jika tidak ada file yang diunggah, gunakan nilai attachmentFilePath dari TindakLanjut sebelumnya
            $attachmentFilePath = $tindaklanjut->attachment_file ?? '';
        }

        // Replicate model TindakLanjut yang ada
        $newTindakLanjut = $tindaklanjut->replicate();

        // Isi kolom 'pic' dengan nama pengguna yang sedang login
        $loggedInUser = Auth::user();
        $newTindakLanjut->pic = $loggedInUser->name;

        // Assign fields that need to be updated
        $newTindakLanjut->id_fpp = $formperbaikan->id_fpp;
        $newTindakLanjut->tindak_lanjut = $request->input('tindak_lanjut', $tindaklanjut->tindak_lanjut);
        $newTindakLanjut->due_date = $request->input('due_date', $tindaklanjut->due_date);
        $newTindakLanjut->schedule_pengecekan = $request->input('schedule_pengecekan', $tindaklanjut->schedule_pengecekan);
        $newTindakLanjut->status = $request->input('status', $tindaklanjut->status);
        $newTindakLanjut->note = $request->input('note', $tindaklanjut->note);
        $newTindakLanjut->attachment_file = $attachmentFilePath;

        // Save the updated model
        $newTindakLanjut->save();

        // Update other form data
        $formperbaikan->update($request->all());

        // Update updated_at for FormPerbaikan
        $formperbaikan->touch();

        // Update the form data
        $confirmedFinish = $request->input('confirmed_finish'); // Ubah status jadi finish
        $confirmedFinish2 = $request->input('confirmed_finish2'); // Dikonfimasi oleh Dept.MTCE
        $confirmedFinish3 = $request->input('confirmed_finish3'); // Ubah status menjadi Closed
        $confirmedFinish4 = $request->input('confirmed_finish4'); // Ubah status menjadi On Progress ketika Check Again
        $confirmedFinish5 = $request->input('confirmed_finish5'); // Save Tidak mengubah status
        $confirmedFinish6 = $request->input('confirmed_finish6'); // Save Tidak mengubah status
        $confirmedFinish7 = $request->input('confirmed_finish7'); // Save Tidak mengubah status

        // Check the confirmed_finish conditions
        if ($confirmedFinish === "1") {
            // Update the status and note accordingly in the original TindakLanjut
            $formperbaikan->update(['status' => '2']);
            $newTindakLanjut->update([
                'note' => 'Disubmit Maintenance',
                'status' => '2',
            ]);

            $users = User::whereIn('role_id', [5, 6, 7, 8, 9])->whereNotNull('email')->get();
            if ($users) {
                $emails = $users->pluck('email')->toArray();
                Mail::send('emails.submit_fpp', ['formperbaikan' => $formperbaikan], function ($message) use ($emails, $formperbaikan) {
                    $message->to($emails)
                        ->subject('Form Permintaan Perbaikan: ' . $formperbaikan->id_fpp . ' telah disubmit Maintenance');
                });
            }

            return redirect()->route('maintenance.index')->with('success', 'Form FPP updated successfully');
        } elseif ($confirmedFinish2 === "1") {
            // Update the status and note accordingly in the original TindakLanjut
            $formperbaikan->update(['status' => '2']);
            $newTindakLanjut->update([
                'note' => 'Dikonfirmasi Dept.Head Maintenance',
                'status' => '2',
            ]);

            $users = User::whereIn('role_id', [5, 6, 7, 8, 9])->whereNotNull('email')->get();
            if ($users) {
                $emails = $users->pluck('email')->toArray();
                Mail::send('emails.konfirmasi2_fpp', ['formperbaikan' => $formperbaikan], function ($message) use ($emails, $formperbaikan) {
                    $message->to($emails)
                        ->subject('Form Permintaan Perbaikan: ' . $formperbaikan->id_fpp . ' telah dikonfirmasi oleh Dept.Head Maintenance');
                });
            }

            return redirect()->route('deptmtce.index')->with('success', 'Form FPP updated successfully');
        } elseif ($confirmedFinish3 === "1") {
            // Update the status and note accordingly in the original TindakLanjut
            $formperbaikan->update(['status' => '3']);
            $newTindakLanjut->update(['note' => 'Diclosed Production', 'status' => '3']);

            $users = User::whereIn('role_id', [5, 6, 7, 8, 9])->whereNotNull('email')->get();
            if ($users) {
                $emails = $users->pluck('email')->toArray();
                Mail::send('emails.closed_fpp', ['formperbaikan' => $formperbaikan], function ($message) use ($emails, $formperbaikan) {
                    $message->to($emails)
                        ->subject('Form Permintaan Perbaikan: ' . $formperbaikan->id_fpp . ' telah diclosed oleh Production');
                });
            }

            return redirect()->route('fpps.index')->with('success', 'Form FPP updated successfully');
        } elseif ($confirmedFinish4 === "1") {
            // Update the status and note accordingly in the original TindakLanjut
            $formperbaikan->update(['status' => '1']);
            $newTindakLanjut->update(['status' => '1']);
            return redirect()->route('deptmtce.index')->with('success', 'Form FPP updated successfully');
        } elseif ($confirmedFinish5 === "1") {
            // Update the status and note accordingly in the original TindakLanjut
            $formperbaikan->update(['status' => '1']);
            $newTindakLanjut->update(['note' => 'Dikonfirmasi Maintenance']);

            $users = User::whereIn('role_id', [5, 6, 7, 8, 9])->whereNotNull('email')->get();
            if ($users) {
                $emails = $users->pluck('email')->toArray();
                Mail::send('emails.konfirmasi_fpp', ['formperbaikan' => $formperbaikan], function ($message) use ($emails, $formperbaikan) {
                    $message->to($emails)
                        ->subject('Form Permintaan Perbaikan: ' . $formperbaikan->id_fpp . ' telah dikonfirmasi Maintenance');
                });
            }

            return redirect()->route('maintenance.index')->with('success', 'Form FPP updated successfully');
        } elseif ($confirmedFinish6 === "1") {
            // Update the status and note accordingly in the original TindakLanjut
            $formperbaikan->update(['status' => '1']);
            $newTindakLanjut->update(['note' => 'Sedang Ditindaklanjuti', 'status' => '1']);
            return redirect()->route('maintenance.index')->with('success', 'Form FPP updated successfully');
        } elseif ($confirmedFinish7 === "1") {
            // Update the status and note accordingly in the original TindakLanjut
            $formperbaikan->update(['status' => '1']);
            $newTindakLanjut->update(['status' => '1']);
            return redirect()->route('fpps.index')->with('success', 'Form FPP updated successfully');
        } else {
            $newTindakLanjut->update(['note' => 'Sedang Ditindaklanjuti', 'status' => '1']);
            $formperbaikan->update(['status' => '1']);
            return redirect()->route('maintenance.index')->with('success', 'Form FPP updated successfully');
        }
    }
    public function downloadAttachment(TindakLanjut $tindaklanjut)
    {
        // Pastikan file attachment_file ada sebelum mencoba mengunduh
        if ($tindaklanjut->attachment_file) {
            $filePath = public_path($tindaklanjut->attachment_file);

            // Pastikan file ada di dalam direktori publik
            if (file_exists($filePath)) {
                // Ambil base filename dari attachment
                $baseFileName = basename($tindaklanjut->attachment_file);

                return response()->download($filePath, $baseFileName);
            } else {
                // File tidak ditemukan, arahkan kembali dengan pesan kesalahan
                return redirect()->back()->with('error', 'File not found.');
            }
        } else {
            // Handle case when no file is attached
            return redirect()->back()->with('error', 'No file attached.');
        }
    }

    public function downtimeExport()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul di atas tabel
        $sheet->setCellValue('A1', 'DOWNTIME REPAIR MAINTENANCE'); // Judul
        $sheet->mergeCells('A1:M1'); // Gabung sel untuk judul
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('left'); // Pusatkan judul
        $sheet->getStyle('A1')->getFont()->setBold(true); // Jadikan tebal judul

        // Set judul kolom
        $sheet->setCellValue('A2', 'NO');
        $sheet->setCellValue('B2', 'TIKET FPP');
        $sheet->setCellValue('C2', 'USER REQ');
        $sheet->setCellValue('D2', 'PIC. MTCN');
        $sheet->setCellValue('E2', 'LOCATION');
        $sheet->setCellValue('F2', 'SECTION');
        $sheet->setCellValue('G2', 'MESIN');
        $sheet->setCellValue('H2', 'ISSUE');
        $sheet->setCellValue('I2', 'START');
        $sheet->setCellValue('J2', 'DUE DATE');
        $sheet->setCellValue('K2', 'DOWNTIME');

        // Gunakan join untuk menggabungkan data dari FormFPP dan TindakLanjut
        $formFPPData = FormFPP::select(
            'form_f_p_p_s.id_fpp',
            'form_f_p_p_s.pemohon',
            'form_f_p_p_s.lokasi',
            'form_f_p_p_s.section',
            'form_f_p_p_s.mesin',
            'form_f_p_p_s.kendala',
            'form_f_p_p_s.created_at',
            'form_f_p_p_s.updated_at',
            'form_f_p_p_s.status',
            DB::raw('GROUP_CONCAT(DISTINCT 
                CASE
                    WHEN tindak_lanjuts.status = 1 THEN tindak_lanjuts.pic
                    WHEN tindak_lanjuts.status = 2 AND tindak_lanjuts.note = "Disubmit Maintenance" THEN tindak_lanjuts.pic
                    ELSE NULL
                END SEPARATOR ", ") AS pic')
        )
            ->leftJoin('tindak_lanjuts', 'form_f_p_p_s.id_fpp', '=', 'tindak_lanjuts.id_fpp')
            ->groupBy(
                'form_f_p_p_s.id_fpp',
                'form_f_p_p_s.pemohon',
                'form_f_p_p_s.lokasi',
                'form_f_p_p_s.section',
                'form_f_p_p_s.mesin',
                'form_f_p_p_s.kendala',
                'form_f_p_p_s.created_at',
                'form_f_p_p_s.updated_at',
                'form_f_p_p_s.status'
            )
            ->get();

        $row = 3; // Mulai dari baris ketiga karena baris kedua sudah diisi dengan judul kolom

        foreach ($formFPPData as $data) {
            // Hilangkan karakter '|' dari tanggal
            $createdAt = str_replace('|', '', $data->created_at);
            $updatedAt = str_replace('|', '', $data->updated_at);

            // Hitung downtime jika status sudah 3
            if ($data->status == 3) {
                $start = Carbon::parse($createdAt);
                $due_date = Carbon::parse($updatedAt);
                $downtimeMinutes = $start->diffInMinutes($due_date);
                $hours = intdiv($downtimeMinutes, 60);
                $minutes = $downtimeMinutes % 60;
                $downtime = "{$hours} Jam {$minutes} Menit";
            } else {
                $start = Carbon::parse($createdAt);
                $due_date = null;
                $downtime = '-'; // Atau nilai default lain yang Anda inginkan
            }

            $sheet->setCellValue('A' . $row, $row - 2); // Nomor berurutan dimulai dari 1
            $sheet->setCellValue('B' . $row, $data->id_fpp);
            $sheet->setCellValue('C' . $row, $data->pemohon);
            $sheet->setCellValue('D' . $row, $data->pic ? $data->pic : '-');
            $sheet->setCellValue('E' . $row, $data->lokasi);
            $sheet->setCellValue('F' . $row, $data->section);
            $sheet->setCellValue('G' . $row, $data->mesin);
            $sheet->setCellValue('H' . $row, $data->kendala);
            $sheet->setCellValue('I' . $row, $start->format('Y-m-d'));
            $sheet->setCellValue('J' . $row, $due_date ? $due_date->format('Y-m-d') : '-');
            $sheet->setCellValue('K' . $row, $downtime);

            $row++;
        }

        // Menambahkan keterangan di bawah tabel
        $lastRow = $row;
        $sheet->setCellValue('A' . ($lastRow + 1), 'Note :');
        $sheet->setCellValue('A' . ($lastRow + 2), 'PIC.MTCH');
        $sheet->setCellValue('B' . ($lastRow + 2), 'User maintenance yang melakukan perbaikan'); // Menyesuaikan dengan kolom PIC Maintenance
        $sheet->setCellValue('A' . ($lastRow + 3), 'NPK');
        $sheet->setCellValue('B' . ($lastRow + 3), 'NPK karyawan'); // Menyesuaikan dengan kolom NPK
        $sheet->setCellValue('A' . ($lastRow + 4), 'LOCATION');
        $sheet->setCellValue('B' . ($lastRow + 4), 'Lokasi mesin diperbaiki'); // Menyesuaikan dengan kolom Lokasi
        $sheet->setCellValue('A' . ($lastRow + 5), 'SECTION');
        $sheet->setCellValue('B' . ($lastRow + 5), 'Section yang mengajukan perbaikan'); // Menyesuaikan dengan kolom Section
        $sheet->setCellValue('A' . ($lastRow + 6), 'MESIN');
        $sheet->setCellValue('B' . ($lastRow + 6), 'Nomor dan Nama Mesin'); // Menyesuaikan dengan kolom Mesin
        $sheet->setCellValue('A' . ($lastRow + 7), 'ISSUE');
        $sheet->setCellValue('B' . ($lastRow + 7), 'Masalah yang ditemukan'); // Menyesuaikan dengan kolom Kendala
        $sheet->setCellValue('A' . ($lastRow + 8), 'START');
        $sheet->setCellValue('B' . ($lastRow + 8), 'Tgl dimulai repair'); // Menyesuaikan dengan kolom Start
        $sheet->setCellValue('A' . ($lastRow + 9), 'DUE DATE');
        $sheet->setCellValue('B' . ($lastRow + 9), 'Tgl selesai repair'); // Menyesuaikan dengan kolom Due Date

        // Menyesuaikan ukuran kolom secara otomatis
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Membuat border untuk seluruh tabel
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A2:K' . ($row - 1))->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'downtime_maintenance.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}

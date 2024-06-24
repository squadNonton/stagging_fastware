<?php

namespace App\Http\Controllers;

use App\Models\DetailInquiry; // Impor model InquirySales
use App\Models\InquirySales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquirySalesController extends Controller
{
    public function createInquirySales()
    {
        $statuses = [0, 1, 2, 3, 1, 4, 5, 6, 7];
        $inquiries = InquirySales::whereIn('status', $statuses)
            ->orderByRaw('FIELD(status, 2, 3, 1, 0, 4, 5, 6, 7)')
            ->get()
            ->unique('kode_inquiry');

        return view('inquiry.create', compact('inquiries'));
    }

    public function showFormSS($id)
    {
        // Cari inquiry berdasarkan ID
        $inquiry = InquirySales::findOrFail($id);

        // Cari semua detail inquiry berdasarkan id_inquiry dari inquiry utama
        $materials = DetailInquiry::where('id_inquiry', $inquiry->id)->get();

        return view('inquiry.showFormSS', compact('inquiry', 'materials'));
    }

    public function historyFormSS($id)
    {
        // Cari inquiry berdasarkan ID
        $inquiry = InquirySales::findOrFail($id);

        // Cari semua detail inquiry berdasarkan id_inquiry dari inquiry utama
        $materials = DetailInquiry::where('id_inquiry', $inquiry->id)->get();

        return view('inquiry.historyFormSS', compact('inquiry', 'materials'));
    }

    public function konfirmInquiry()
    {
        $statuses = [3, 1, 4, 5, 6, 7];
        $inquiries = InquirySales::whereIn('status', $statuses)
            ->orderByRaw('FIELD(status, 3, 1, 4, 5, 6, 7)')
            ->get();

        return view('inquiry.konfirmInquiry', compact('inquiries'));
    }

    public function validasiInquiry()
    {
        $statuses = [2, 3, 1, 4, 5, 6, 7];
        $inquiries = InquirySales::whereIn('status', $statuses)
            ->orderByRaw('FIELD(status, 2, 3, 1, 0, 4, 5, 6, 7)')
            ->get();

        return view('inquiry.validasi', compact('inquiries'));
    }

    public function reportInquiry()
    {
        $statuses = [2, 3, 1, 4, 5, 6, 7];
        $inquiries = InquirySales::whereIn('status', $statuses)
            ->orderByRaw('FIELD(status, 2, 3, 1, 0, 4, 5, 6, 7)')
            ->get();

        return view('inquiry.report', compact('inquiries'));
    }

    public function formulirInquiry($id)
    {
        $inquiry = InquirySales::findOrFail($id);

        // Cari semua detail inquiry berdasarkan id_inquiry dari inquiry utama
        $materials = DetailInquiry::where('id_inquiry', $inquiry->id)->get();

        return view('inquiry.formulirInquiry', compact('inquiry', 'materials'));
    }

    public function storeInquirySales(Request $request)
    {
        $request->validate([
            'jenis_inquiry' => 'required',
            'supplier' => 'required',
            'order_from' => 'required',
        ]);

        // Menghasilkan kode inquiry
        $jenisInquiry = $request->jenis_inquiry;
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');

        // Cek apakah ada inquiry yang sudah ada dengan kombinasi jenis_inquiry, supplier, dan order_from yang sama
        $lastInquiry = InquirySales::where('jenis_inquiry', $jenisInquiry)
            ->where('supplier', $request->supplier)
            ->where('order_from', $request->order_from)
            ->first();

        if ($lastInquiry) {
            // Jika sudah ada inquiry dengan kombinasi yang sama, kita tidak akan membuat yang baru
            // Tergantung pada kebutuhan Anda, Anda mungkin akan memperbarui inquiry yang sudah ada di sini
            return redirect()->route('createinquiry')->with('info', 'Inquiry dengan kombinasi yang sama sudah ada.');
        } else {
            // Jika belum ada inquiry dengan kombinasi yang sama, buat inquiry baru
            $lastKodeInquiry = InquirySales::where('jenis_inquiry', $jenisInquiry)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->orderBy('kode_inquiry', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastKodeInquiry) {
                $lastKodeParts = explode('/', $lastKodeInquiry->kode_inquiry);
                $nextNumber = intval(end($lastKodeParts)) + 1;
            }

            $kodeInquiry = sprintf('%s/%02d/%04d/%03d', $jenisInquiry, $currentMonth, $currentYear, $nextNumber);

            $inquiry = new InquirySales();
            $inquiry->kode_inquiry = $kodeInquiry;
            $inquiry->jenis_inquiry = $jenisInquiry; // Menambahkan baris ini untuk menyimpan jenis_inquiry
            $inquiry->supplier = $request->supplier;
            $inquiry->order_from = $request->order_from;
            $inquiry->to_approve = 'Waiting';
            $inquiry->to_validate = 'Waiting';
            $inquiry->status = 2;
            $inquiry->create_by = Auth::user()->name;
            $inquiry->save();
        }

        return redirect()->route('createinquiry')->with('success', 'Inquiry berhasil disimpan.');
    }

    public function editInquiry($id)
    {
        $inquiries = InquirySales::find($id);

        if (!$inquiries) {
            return response()->json(['error' => 'Inquiry tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $inquiries->id,
            'kode_inquiry' => $inquiries->kode_inquiry,
            'supplier' => $inquiries->supplier,
            'order_from' => $inquiries->order_from,
            'create_by' => $inquiries->create_by,
            'to_approve' => $inquiries->to_approve,
            'to_validate' => $inquiries->to_validate,
            'note' => $inquiries->note,
            'attach_file' => $inquiries->attach_file,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_inquiry' => 'required',
            'supplier' => 'required',
            'order_from' => 'required',
        ]);

        $inquiry = InquirySales::findOrFail($id);

        // Generate kode inquiry
        $jenisInquiry = $request->jenis_inquiry; // RO atau SPOR
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $lastInquiry = InquirySales::where('jenis_inquiry', $jenisInquiry)->orderBy('id', 'desc')->first();
        $nextNumber = $lastInquiry ? intval(substr($lastInquiry->kode_inquiry, -3)) + 1 : 1;
        $kodeInquiry = sprintf('%s/%02d/%04d/%03d', $jenisInquiry, $currentMonth, $currentYear, $nextNumber);

        $inquiry->kode_inquiry = $kodeInquiry;
        $inquiry->to_approve = 'Waiting';
        $inquiry->to_validate = 'Waiting';
        $inquiry->status = 2;

        // Assign the name of the logged-in user to the create_by field
        $inquiry->create_by = Auth::user()->name;

        $inquiry->update($request->all());

        return redirect()->route('createinquiry')->with('success', 'Inquiry updated successfully');
    }

    public function delete($id)
    {
        // Temukan data berdasarkan ID
        $inquiry = InquirySales::findOrFail($id);

        // Ubah status menjadi 0
        $inquiry->status = 0;

        // Simpan perubahan
        $inquiry->save();

        return response()->json(['success' => 'Inquiry deleted successfully']);
    }

    public function approvedInquiry(Request $request, $id)
    {
        $inquiry = InquirySales::findOrFail($id);

        $inquiry->to_validate = 'Waiting';

        if ($request->action_type == 'approved') {
            $inquiry->to_approve = 'Approved';
            $inquiry->status = 4;
        } elseif ($request->action_type == 'not_approved') {
            $inquiry->to_approve = 'Not Approved';
            $inquiry->status = 0; // Or any other status code you want to set for not approved
        }

        $inquiry->save(); // Save the inquiry to update the database

        return redirect()->route('konfirmInquiry')->with('success', 'Inquiry updated successfully');
    }

    public function validateInquiry(Request $request, $id)
    {
        $inquiry = InquirySales::findOrFail($id);

        if ($request->action_type == 'validated') {
            $inquiry->to_validate = 'Validated';
            $inquiry->status = 5;

            // Save note
            $inquiry->note = $request->note;

            // Save attachment file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName(); // Menggunakan nama asli file
                $file->move(public_path('assets/files'), $fileName); // Simpan di direktori public/assets/files
                $inquiry->attach_file = $fileName;
            }
        } elseif ($request->action_type == 'not_validated') {
            $inquiry->to_validate = 'Not Validated';
            $inquiry->status = 0;
        }

        $inquiry->save(); // Simpan perubahan ke database

        return redirect()->route('validasiInquiry')->with('success', 'Inquiry updated successfully');
    }

    public function previewSS(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_inquiry' => 'required|integer',
            'materials' => 'required|array',
            'materials.*.nama_material' => 'required|string',
            'materials.*.jenis' => 'required|string', // Validate jenis
            'materials.*.thickness' => 'nullable|string',
            'materials.*.weight' => 'nullable|string',
            'materials.*.length' => 'nullable|string',
            'materials.*.pcs' => 'nullable|string',
            'materials.*.qty' => 'nullable|string',
        ]);

        // Ambil id_inquiry dari request
        $id_inquiry = $request->id_inquiry;

        // Iterasi dan simpan atau update material
        foreach ($request->materials as $material) {
            // Cari detail inquiry berdasarkan id_inquiry dan nama_material
            $detailInquiry = DetailInquiry::where('id_inquiry', $id_inquiry)
                                          ->where('nama_material', $material['nama_material'])
                                          ->first();

            if ($detailInquiry) {
                // Jika detail inquiry ditemukan, perbarui data detail inquiry
                $detailInquiry->update([
                    'jenis' => $material['jenis'],
                    'thickness' => $material['thickness'],
                    'weight' => $material['weight'],
                    'length' => $material['length'],
                    'pcs' => $material['pcs'],
                    'qty' => $material['qty'],
                    // Field lainnya jika ada
                ]);
            } else {
                // Jika detail inquiry tidak ditemukan, buat detail inquiry baru
                DetailInquiry::create([
                    'id_inquiry' => $id_inquiry,
                    'nama_material' => $material['nama_material'],
                    'jenis' => $material['jenis'],
                    'thickness' => $material['thickness'],
                    'weight' => $material['weight'],
                    'length' => $material['length'],
                    'pcs' => $material['pcs'],
                    'qty' => $material['qty'],
                    // Field lainnya jika ada
                ]);
            }
        }

        // Cari inquiry berdasarkan id_inquiry
        $inquiry = InquirySales::find($id_inquiry);

        // Periksa apakah inquiry ditemukan
        if ($inquiry) {
            // Perbarui status inquiry menjadi 3
            $inquiry->status = 3;
            $inquiry->save();
        }

        return response()->json(['message' => 'Detail Inquiry updated or created successfully']);
    }
}

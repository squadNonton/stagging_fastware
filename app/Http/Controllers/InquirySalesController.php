<?php

namespace App\Http\Controllers;

use App\Models\InquirySales; // Impor model InquirySales
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
            ->get();

        return view('inquiry.create', compact('inquiries'));
    }

    public function konfirmInquiry()
    {
        $statuses = [2, 3, 1, 4, 5, 6, 7];
        $inquiries = InquirySales::whereIn('status', $statuses)
            ->orderByRaw('FIELD(status, 2, 3, 1, 4, 5, 6, 7)')
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

    public function storeInquirySales(Request $request)
    {
        $request->validate([
            'jenis_inquiry' => 'required',
            'supplier' => 'required',
            'qty' => 'required|integer',
            'order_from' => 'required',
            'type.*' => 'required', // Validasi untuk multiple input type
            'size.*' => 'required', // Validasi untuk multiple input size
        ]);

        // Generate kode inquiry
        $jenisInquiry = $request->jenis_inquiry;
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $lastInquiry = InquirySales::where('jenis_inquiry', $jenisInquiry)
            ->where('supplier', $request->supplier)
            ->where('qty', $request->qty)
            ->where('order_from', $request->order_from)
            ->first();

        if ($lastInquiry) {
            // Jika sudah ada inquiry dengan kombinasi yang sama, gabungkan type dan size yang baru ke inquiry yang sudah ada
            foreach ($request->type as $key => $type) {
                // Cek apakah kombinasi type dan size sudah ada dalam inquiry yang ada
                $typeSizeExists = $lastInquiry->where('type', $type)
                    ->where('size', $request->size[$key])
                    ->exists();

                if (!$typeSizeExists) {
                    // Jika belum ada, tambahkan dengan format yang diinginkan
                    $lastInquiry->type .= ', ' . $type;
                    $lastInquiry->size .= ', ' . $request->size[$key];
                }
            }
            $lastInquiry->save();
        } else {
            // Jika belum ada inquiry dengan kombinasi yang sama, buat inquiry baru
            $nextNumber = InquirySales::where('jenis_inquiry', $jenisInquiry)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->count() + 1;

            $kodeInquiry = sprintf('%s/%02d/%04d/%03d', $jenisInquiry, $currentMonth, $currentYear, $nextNumber);

            // Gabungkan semua type dan size menjadi satu baris dengan format yang diinginkan
            $types = implode(', ', $request->type);
            $sizes = implode(', ', $request->size);

            $inquiry = new InquirySales();
            $inquiry->kode_inquiry = $kodeInquiry;
            $inquiry->jenis_inquiry = $request->jenis_inquiry;
            $inquiry->type = $types;
            $inquiry->size = $sizes;
            $inquiry->supplier = $request->supplier;
            $inquiry->qty = $request->qty;
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
        $inquiry = InquirySales::find($id);

        return response()->json([
            'id' => $inquiry->id,
            'kode_inquiry' => $inquiry->kode_inquiry,
            'jenis_inquiry' => $inquiry->jenis_inquiry,
            'type' => $inquiry->type,
            'size' => $inquiry->size,
            'supplier' => $inquiry->supplier,
            'qty' => $inquiry->qty,
            'order_from' => $inquiry->order_from,
            'create_by' => $inquiry->create_by,
            'to_approve' => $inquiry->to_approve,
            'to_validate' => $inquiry->to_validate,
            'note' => $inquiry->note,
            'attach_file' => $inquiry->attach_file,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_inquiry' => 'required',
            'type' => 'required',
            'size' => 'required',
            'supplier' => 'required',
            'qty' => 'required|integer',
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
            $inquiry->status = 3;
        } elseif ($request->action_type == 'not_approved') {
            $inquiry->to_approve = 'Not Approved';
            $inquiry->status = 1; // Or any other status code you want to set for not approved
        }

        $inquiry->save(); // Save the inquiry to update the database

        return redirect()->route('konfirmInquiry')->with('success', 'Inquiry updated successfully');
    }

    public function validateInquiry(Request $request, $id)
    {
        $inquiry = InquirySales::findOrFail($id);

        if ($request->action_type == 'validated') {
            $inquiry->to_validate = 'Validated';
            $inquiry->status = 4;

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
            $inquiry->status = 1;
        }

        $inquiry->save(); // Simpan perubahan ke database

        return redirect()->route('validasiInquiry')->with('success', 'Inquiry updated successfully');
    }
}

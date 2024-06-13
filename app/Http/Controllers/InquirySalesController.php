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

    public function storeInquirySales(Request $request)
    {
        $request->validate([
            'jenis_inquiry' => 'required',
            'type' => 'required',
            'size' => 'required',
            'supplier' => 'required',
            'qty' => 'required|integer',
            'order_from' => 'required',
        ]);

        // Generate kode inquiry
        $jenisInquiry = $request->jenis_inquiry; // RO atau SPOR
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $lastInquiry = InquirySales::where('jenis_inquiry', $jenisInquiry)->orderBy('id', 'desc')->first();
        $nextNumber = $lastInquiry ? intval(substr($lastInquiry->kode_inquiry, -3)) + 1 : 1;
        $kodeInquiry = sprintf('%s/%02d/%04d/%03d', $jenisInquiry, $currentMonth, $currentYear, $nextNumber);

        $inquiry = new InquirySales($request->all());
        $inquiry->kode_inquiry = $kodeInquiry;
        $inquiry->to_approve = 'Waiting';
        $inquiry->to_validate = 'Waiting';
        $inquiry->status = 2;

        // Assign the name of the logged-in user to the create_by field
        $inquiry->create_by = Auth::user()->name;

        $inquiry->save();

        return redirect()->route('createinquiry');
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
}

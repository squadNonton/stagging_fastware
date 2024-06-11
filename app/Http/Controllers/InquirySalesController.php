<?php

namespace App\Http\Controllers;

use App\Models\InquirySales; // Impor model InquirySales
use Carbon\Carbon;
use Illuminate\Http\Request;

class InquirySalesController extends Controller
{
    public function createInquirySales()
    {
        $inquiries = InquirySales::all();

        return view('inquiry.create', compact('inquiries'));
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
            'create_by' => 'required',
            'to_approve' => 'required',
            'to_validate' => 'required',
            'note' => 'nullable',
            'attach_file' => 'nullable|file',
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

        if ($request->hasFile('attach_file')) {
            $filePath = $request->file('attach_file')->store('attachments');
            $inquiry->attach_file = $filePath;
        }

        $inquiry->save();

        return redirect()->route('createinquiry');
    }

public function editInquiry($id)
{
    \Log::info('Attempting to edit inquiry with ID: ' . $id);

    $inquiry = InquirySales::find($id);

    if (!$inquiry) {
        \Log::error('Inquiry not found with ID: ' . $id);
        return response()->json(['error' => 'Inquiry not found'], 404);
    }

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
        $inquiry = InquirySales::findOrFail($id); // Pastikan nama modelnya benar
        $inquiry->update($request->all());

        if ($request->hasFile('attach_file')) {
            $file = $request->file('attach_file');
            $filePath = $file->store('uploads', 'public');
            $inquiry->attach_file = $filePath;
            $inquiry->save();
        }

        return redirect()->route('inquiry.create')->with('success', 'Inquiry updated successfully');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InquirySales;
use Carbon\Carbon;

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
            'attach_file' => 'nullable|file'
        ]);

        // Generate kode inquiry
        $jenisInquiry = $request->jenis_inquiry; // RO atau SPOR
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $lastInquiry = InquirySales::where('jenis_inquiry', $jenisInquiry)->orderBy('id', 'desc')->first();
        $nextNumber = $lastInquiry ? intval(substr($lastInquiry->kode_inquiry, -3)) + 1 : 1;
        $kodeInquiry = sprintf("%s/%02d/%04d/%03d", $jenisInquiry, $currentMonth, $currentYear, $nextNumber);

        $inquiry = new InquirySales($request->all());
        $inquiry->kode_inquiry = $kodeInquiry;

        if ($request->hasFile('attach_file')) {
            $filePath = $request->file('attach_file')->store('attachments');
            $inquiry->attach_file = $filePath;
        }

        $inquiry->save();

        return redirect()->route('createinquiry');
    }
}

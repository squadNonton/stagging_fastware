<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeatTreatmentController extends Controller
{
    public function dashboardImportWO()
    {
        return view('wo_heat.importWO');
    }

    public function dashboardTracingWO()
    {
        return view('wo_heat.tracingWO');
    }


}

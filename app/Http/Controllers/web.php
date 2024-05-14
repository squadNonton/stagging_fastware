<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\http\Controllers\DeptManController;
use App\Http\Controllers\DetailPreventiveController;
use App\http\Controllers\DsController;
use App\Http\Controllers\EventController;
use App\http\Controllers\ExcelController;
use App\Http\Controllers\FormFPPController;
use App\Http\Controllers\HandlingController;
use App\Http\Controllers\MesinController;
use App\http\Controllers\PDFController;
use App\Http\Controllers\PreventiveController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Proses login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login_post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('full-calender', [EventController::class, 'blokMaintanence'])->name('blokMaintanence');
    Route::get('full-calenderDept', [EventController::class, 'blokDeptMaintenance'])->name('blokDeptMaintenance');

    Route::post('full-calender-AJAX', [EventController::class, 'ajax']);
    Route::get('generate-pdf/{mesin}', [PDFController::class, 'generatePDF'])->name('pdf.mesin');
    Route::get('dashboardMaintenance', [EventController::class, 'dashboardMaintenance'])->name('dashboardMaintenance');

    Route::resource('mesins', MesinController::class);
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('formperbaikans', FormFPPController::class);
    Route::resource('receivedfpps', FormFPPController::class);
    Route::resource('approvedfpps', FormFPPController::class);
    Route::resource('tindaklanjuts', FormFPPController::class);
    Route::resource('preventives', PreventiveController::class);
    Route::resource('detailpreventive', DetailPreventiveController::class);
    Route::resource('events', EventController::class);
    Route::resource('spareparts', SparepartController::class);

    // Admin
    Route::get('dashboardusers', [UserController::class, 'index'])->name('dashboardusers');
    Route::get('dashboardcustomers', [CustomerController::class, 'index'])->name('dashboardcustomers');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

    // Preventive
    Route::get('dashpreventive', [PreventiveController::class, 'maintenanceDashPreventive'])
        ->name('maintenance.dashpreventive');
    Route::get('deptmtcepreventive', [PreventiveController::class, 'deptmtceDashPreventive'])
        ->name('deptmtce.dashpreventive');
    Route::get('deptmtce/editpreventive/{mesin}', [PreventiveController::class, 'EditDeptMTCEPreventive'])
        ->name('deptmtce.editpreventive');

    // Production
    Route::get('dashboardproduction', [FormFPPController::class, 'DashboardProduction'])->name('fpps.index');
    Route::get('historyfpp', [FormFPPController::class, 'HistoryFPP'])->name('fpps.history');
    Route::get('lihatform/{formperbaikan}', [FormFPPController::class, 'LihatFPP'])
        ->name('fpps.show');
    Route::get('closedform/{formperbaikan}', [FormFPPController::class, 'ClosedFormProduction'])
        ->name('fpps.closed');

    // Maintenance
    Route::get('dashboardmaintenance', [FormFPPController::class, 'DashboardMaintenance'])
        ->name('maintenance.index');
    Route::get('lihatmaintenance/{formperbaikan}', [FormFPPController::class, 'LihatMaintenance'])
        ->name('maintenance.lihat');
    Route::get('editmaintenance/{formperbaikan}', [FormFPPController::class, 'EditMaintenance'])
        ->name('maintenance.edit');
    Route::get('preventives/edit-issue/{preventive}', [PreventiveController::class, 'editIssue'])
        ->name('preventives.editpreventive');
    Route::get('preventives/lihat-issue/{preventive}', [PreventiveController::class, 'lihatIssue'])
        ->name('preventives.lihatpreventive');
    Route::put('preventives/update-issue/{preventive}', [PreventiveController::class, 'updateIssue'])
        ->name('preventives.updateIssue');

    Route::get('dashboardmesins', [MesinController::class, 'index'])->name('dashboardmesins');

    Route::put('mesins/{mesin}/update-issue', [DetailPreventiveController::class, 'updateIssue'])
        ->name('detailpreventives.updateIssue');
    Route::put('mesins/{mesin}/update-perbaikan', [DetailPreventiveController::class, 'updatePerbaikan'])
        ->name('detailpreventives.updatePerbaikan');

    // Dept Maintenance
    Route::get('dashboarddeptmtce', [FormFPPController::class, 'DashboardDeptMTCE'])
        ->name('deptmtce.index');
    Route::get('lihatdeptmtce/{formperbaikan}', [FormFPPController::class, 'LihatDeptMTCE'])
        ->name('deptmtce.show');
    Route::get('editdeptmtcepreventive/{mesin}', [PreventiveController::class, 'EditDeptMTCEPreventive'])
        ->name('deptmtce.lihatpreventive');
    Route::get('dashboardPreventive', [PreventiveController::class, 'dashboardPreventive'])->name('dashboardPreventive');
    Route::get('dashboardPreventiveMaintenance', [PreventiveController::class, 'dashboardPreventiveMaintenance'])->name('dashboardPreventiveMaintenance');
    Route::get('formpreventif', [PreventiveController::class, 'create'])->name('preventives.create');
    Route::get('editpreventive', [PreventiveController::class, 'edit'])->name('preventives.edit');
    Route::post('sparepart-import', [SparepartController::class, 'import'])->name('spareparts.import');
    Route::get('/spareparts/export/{nomor_mesin}', [SparepartController::class, 'export'])->name('spareparts.export');

    Route::put('/update-preventive', [PreventiveController::class, 'update'])->name('updatePreventive');

    // Sales
    Route::get('dashboardfppsales', [FormFPPController::class, 'DashboardFPPSales'])
        ->name('sales.index');
    Route::get('lihatfppsales/{formperbaikan}', [FormFPPController::class, 'LihatFPPSales'])
        ->name('sales.lihat');

    // Download File
    Route::get('download-excel/{tindaklanjut}', [FormFPPController::class, 'downloadAttachment'])->name('download.attachment');
    // DashboardforALL
    Route::get('dashboardHandling', [DsController::class, 'dashboardHandling'])->name('dashboardHandling');
    Route::get('/getChartData', [HandlingController::class, 'getChartData']);
    Route::get('/get-data-by-year', [HandlingController::class, 'getDataByYear']);
    Route::get('/getRepairMaintenance', [DsController::class, 'getRepairMaintenance']);
    Route::get('/getPeriodeWaktuPengerjaan', [DsController::class, 'getPeriodeWaktuPengerjaan']);
    Route::get('/api/filter-pie-chart-tipe', [HandlingController::class, 'FilterPieChartTipe']);
    Route::get('/api/filter-tipe-all', [HandlingController::class, 'FilterTipeAll']);
    Route::get('/api/FilterPieChartProses', [HandlingController::class, 'FilterPieChartProses']);

    Route::get('/getPeriodeMesin', [DsController::class, 'getPeriodeMesin']);

    Route::get('handling', [HandlingController::class, 'index'])->name('index');
    Route::get('create', [HandlingController::class, 'create'])->name('create');
    Route::post('store', [HandlingController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [HandlingController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [HandlingController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [HandlingController::class, 'delete'])->name('delete');
    Route::patch('/changeStatus/{id}', [HandlingController::class, 'changeStatus'])->name('changeStatus');
    Route::get('/showHistory/{id}', [HandlingController::class, 'showHistory'])->name('showHistory');

    // deptMan
    Route::get('deptMan', [DeptManController::class, 'submission'])->name('submission');
    Route::get('/showConfirm/{id}', [DeptManController::class, 'showConfirm'])->name('showConfirm');
    Route::put('/updateConfirm/{id}', [DeptManController::class, 'updateConfirm'])->name('updateConfirm');
    Route::get('/showFollowUp/{id}', [DeptManController::class, 'showFollowUp'])->name('showFollowUp');
    Route::get('/showHistoryProgres/{id}', [DeptManController::class, 'showHistoryProgres'])->name('showHistoryProgres');
    Route::put('/updateFollowUp/{id}', [DeptManController::class, 'updateFollowUp'])->name('updateFollowUp');
    Route::get('scheduleVisit', [DeptManController::class, 'scheduleVisit'])->name('scheduleVisit');
    Route::get('showHistoryCLaimComplain', [DeptManController::class, 'showHistoryCLaimComplain'])->name('showHistoryCLaimComplain');
    Route::get('/export-excel', [ExcelController::class, 'exportExcel'])->name('export.excel');
    Route::get('/showCloseProgres/{id}', [DeptManController::class, 'showCloseProgres'])->name('showCloseProgres');
    // Tambahkan rute lainnya di sini
});

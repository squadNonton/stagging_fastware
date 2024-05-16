<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DetailPreventiveController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FormFPPController;
use App\Http\Controllers\HandlingController;
use App\Http\Controllers\MesinController;
use App\Http\Controllers\PreventiveController;
use App\Http\Controllers\SafetyController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SumbangSaranController;
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

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login_post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('full-calender', [EventController::class, 'blokMaintanence'])->name('blokMaintanence');
    Route::get('full-calenderDept', [EventController::class, 'blokDeptMaintenance'])->name('blokDeptMaintenance');

    Route::post('full-calender-AJAX', [EventController::class, 'ajax']);
    Route::get('generate-pdf/{mesin}', 'App\Http\Controllers\PDFController@generatePDF')->name('pdf.mesin');
    Route::get('dashboardMaintenance', [EventController::class, 'dashboardMaintenance'])->name('dashboardMaintenance');

    // Change Pass
    Route::get('/showDataDiri', 'App\Http\Controllers\AuthController@showDataDiri')->name('showDataDiri');
    Route::post('/ubahPassword', 'App\Http\Controllers\AuthController@ubahPassword')->name('ubahPassword');

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
    Route::get('dashboardmaintenancega', [FormFPPController::class, 'DashboardMaintenanceGA'])
        ->name('ga.dashboardga');
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
    Route::get('dashboardgamesin', [MesinController::class, 'dashboardGAMesin'])->name('dashboardgamesin');
    Route::get('/mesins/showMesinGA/{mesin}', [MesinController::class, 'showMesinGA'])->name('mesins.showMesinGA');

    Route::put('mesins/{mesin}/update-issue', [DetailPreventiveController::class, 'updateIssue'])
        ->name('detailpreventives.updateIssue');
    Route::put('mesins/{mesin}/update-perbaikan', [DetailPreventiveController::class, 'updatePerbaikan'])
        ->name('detailpreventives.updatePerbaikan');

    // Dept Maintenance
    Route::get('dashboarddeptmtce', [FormFPPController::class, 'DashboardDeptMTCE'])
        ->name('deptmtce.index');
    Route::get('dashboardapprovedga', [FormFPPController::class, 'DashboardFPPGA'])
        ->name('ga.approvedfpp');
    Route::get('lihatdeptmtce/{formperbaikan}', [FormFPPController::class, 'LihatDeptMTCE'])
        ->name('deptmtce.show');
    Route::get('editdeptmtcepreventive/{mesin}', [PreventiveController::class, 'EditDeptMTCEPreventive'])
        ->name('deptmtce.lihatpreventive');
    Route::get('dashboardPreventive', [PreventiveController::class, 'dashboardPreventive'])->name('dashboardPreventive');
    Route::get('dashboardPreventiveMaintenance', [PreventiveController::class, 'dashboardPreventiveMaintenance'])->name('dashboardPreventiveMaintenance');
    Route::get('dashboardPreventiveMaintenanceGA', [PreventiveController::class, 'dashboardPreventiveMaintenanceGA'])->name('dashboardPreventiveMaintenanceGA');
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
    Route::get('/dashboardHandling', 'App\Http\Controllers\DsController@dashboardHandling')->name('dashboardHandling');
    Route::get('/getChartData', 'App\Http\Controllers\HandlingController@getChartData');
    Route::get('/get-data-by-year', 'App\Http\Controllers\HandlingController@getDataByYear');
    Route::get('/getRepairMaintenance', 'App\Http\Controllers\MaintenanceController@getRepairMaintenance');
    Route::get('/getRepairAlatBantu', 'App\Http\Controllers\MaintenanceController@getRepairAlatBantu');
    Route::get('/getPeriodeWaktuPengerjaan', 'App\Http\Controllers\MaintenanceController@getPeriodeWaktuPengerjaan');
    Route::get('/getPeriodeWaktuAlat', 'App\Http\Controllers\MaintenanceController@getPeriodeWaktuAlat');
    Route::get('/api/filter-pie-chart-tipe', 'App\Http\Controllers\HandlingController@FilterPieChartTipe');
    Route::get('/api/filter-tipe-all', 'App\Http\Controllers\HandlingController@FilterTipeAll');
    Route::get('/api/FilterPieChartProses', 'App\Http\Controllers\HandlingController@FilterPieChartProses');

    Route::get('/getPeriodeMesin', 'App\Http\Controllers\MaintenanceController@getPeriodeMesin');
    Route::get('/getPeriodeAlat', 'App\Http\Controllers\MaintenanceController@getPeriodeAlat');

    Route::get('handling', [HandlingController::class, 'index'])->name('index');
    Route::get('create', [HandlingController::class, 'create'])->name('create');
    Route::post('store', [HandlingController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [HandlingController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [HandlingController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [HandlingController::class, 'delete'])->name('delete');
    Route::patch('/changeStatus/{id}', [HandlingController::class, 'changeStatus'])->name('changeStatus');
    Route::get('/showHistory/{id}', [HandlingController::class, 'showHistory'])->name('showHistory');

    // deptMan
    Route::get('/deptMan', 'App\Http\Controllers\DeptManController@submission')->name('submission');
    Route::get('/showConfirm/{id}', 'App\Http\Controllers\DeptManController@showConfirm')->name('showConfirm');
    Route::put('/updateConfirm/{id}', 'App\Http\Controllers\DeptManController@updateConfirm')->name('updateConfirm');
    Route::get('/showFollowUp/{id}', 'App\Http\Controllers\DeptManController@showFollowUp')->name('showFollowUp');
    Route::get('/showHistoryProgres/{id}', 'App\Http\Controllers\DeptManController@showHistoryProgres')->name('showHistoryProgres');
    Route::put('/updateFollowUp/{id}', 'App\Http\Controllers\DeptManController@updateFollowUp')->name('updateFollowUp');
    Route::get('scheduleVisit', 'App\Http\Controllers\DeptManController@scheduleVisit')->name('scheduleVisit');
    Route::get('showHistoryCLaimComplain', 'App\Http\Controllers\DeptManController@showHistoryCLaimComplain')->name('showHistoryCLaimComplain');
    Route::get('/showCloseProgres/{id}', 'App\Http\Controllers\DeptManController@showCloseProgres')->name('showCloseProgres');

    // SS
    Route::get('/showSS', 'App\Http\Controllers\SumbangSaranController@showSS')->name('showSS');
    Route::get('/showKonfirmasiForeman', 'App\Http\Controllers\SumbangSaranController@showKonfirmasiForeman')->name('showKonfirmasiForeman');
    Route::get('/showKonfirmasiDeptHead', 'App\Http\Controllers\SumbangSaranController@showKonfirmasiDeptHead')->name('showKonfirmasiDeptHead');

    Route::post('/simpanSS', 'App\Http\Controllers\SumbangSaranController@simpanSS')->name('simpanSS');
    Route::post('/simpanPenilaian', 'App\Http\Controllers\SumbangSaranController@simpanPenilaian')->name('simpanPenilaian');

    Route::get('/editSS/{id}', [SumbangSaranController::class, 'editSS'])->name('editSS');
    Route::post('/updateSS', [SumbangSaranController::class, 'updateSS'])->name('updateSS');
    Route::get('/getPenilaians/{id}', [SumbangSaranController::class, 'getPenilaians'])->name('getPenilaians');
    Route::post('/updateSS', [SumbangSaranController::class, 'updateSS'])->name('updateSS');
    Route::delete('/delete-ss/{id}', [SumbangSaranController::class, 'deleteSS'])->name('deleteSS');
    Route::post('/kirim-ss/{id}', [SumbangSaranController::class, 'kirimSS'])->name('kirimSS');
    Route::post('/kirim-ss2/{id}', [SumbangSaranController::class, 'kirimSS2'])->name('kirimSS2');
    Route::get('/getSumbangSaran/{id}', [SumbangSaranController::class, 'getSumbangSaran'])->name('getSumbangSaran');

    // Safety Patrol
    Route::get('listpatrol', [SafetyController::class, 'listSafetyPatrol'])->name('listpatrol');
    Route::get('listpatrolpic', [SafetyController::class, 'listSafetyPatrolPIC'])->name('listpatrolpic');
    Route::get('reportpatrol', [SafetyController::class, 'reportPatrol'])->name('reportpatrol');
    Route::get('buatsafetypatrol', [SafetyController::class, 'buatFormSafety'])->name('patrols.buatFormSafety');
    Route::post('simpanPatrol', [SafetyController::class, 'simpanPatrol'])->name('patrols.simpanPatrol');
    Route::get('detailPatrol/{patrol}', [SafetyController::class, 'detailPatrol'])->name('patrols.detailPatrol');
    Route::get('/get-pic-area', [SafetyController::class, 'getPICArea']);
    Route::get('/get-area-patrol', [SafetyController::class, 'getAreaPatrol']);
    Route::get('/get-kategori-patrol', [SafetyController::class, 'getKategoriPatrol']);
    Route::get('/get-safety-patrol', [SafetyController::class, 'getSafetyPatrol']);
    Route::get('/get-lingkungan-patrol', [SafetyController::class, 'getLingkunganPatrol']);
    Route::get('/safetypatrol/export/', [SafetyController::class, 'exportPatrol'])->name('patrol.export');
});

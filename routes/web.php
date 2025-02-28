<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OmsetController;
use App\Http\Controllers\ProgressProjectController;
use App\Http\Controllers\SuratMarketingController;
use App\Http\Controllers\MaintenanceController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class);

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// CMS Omset
Route::get('/omsets', [OmsetController::class, 'index'])->name('omsets.index');
Route::get('/omsets/create', [OmsetController::class, 'create'])->name('omsets.create');
Route::post('/omsets', [OmsetController::class, 'store'])->name('omsets.store');
Route::get('/omsets/{omset}/edit', [OmsetController::class, 'edit'])->name('omsets.edit');
Route::put('/omsets/{omset}', [OmsetController::class, 'update'])->name('omsets.update');
Route::delete('/omsets/{omset}', [OmsetController::class, 'destroy'])->name('omsets.destroy');

Route::get('/omsets/rekap', [OmsetController::class, 'rekapBulanan'])->name('omsets.rekap');
Route::get('omsets/export', [OmsetController::class, 'exportToExcel'])->name('omsets.export');
Route::get('/omset/download-pdf', [OmsetController::class, 'downloadPDF'])->name('omset.download-pdf');
Route::post('/omset/upload-chart', [OmsetController::class, 'uploadChart'])->name('omset.upload-chart');

// CMS Progress Project
Route::get('/progress_projects', [ProgressProjectController::class, 'index'])->name('progress_projects.index');
Route::get('/progress_projects/create', [ProgressProjectController::class, 'create'])->name('progress_projects.create');
Route::post('/progress_projects', [ProgressProjectController::class, 'store'])->name('progress_projects.store');
Route::get('/progress_projects/{progressProject}/edit', [ProgressProjectController::class, 'edit'])->name('progress_projects.edit');
Route::put('/progress_projects/{progressProject}', [ProgressProjectController::class, 'update'])->name('progress_projects.update');
Route::delete('/progress_projects/{progressProject}', [ProgressProjectController::class, 'destroy'])->name('progress_projects.destroy');
Route::get('/progress-projects/download', [ProgressProjectController::class, 'downloadPdf'])->name('progress_projects.downloadPdf');
Route::post('progress_projects/hapusBulan', [ProgressProjectController::class, 'hapusBulan'])->name('progress_projects.hapusBulan');


// Form untuk generate surat
Route::get('/surat-marketing/generate', [SuratMarketingController::class, 'index'])->name('surat.marketing.generate.form');
Route::post('/surat-marketing/generate', [SuratMarketingController::class, 'generate'])->name('surat.marketing.generate');

// Menampilkan daftar surat marketing
Route::get('/digital-marketing/list', [SuratMarketingController::class, 'list'])->name('surat.digital_marketing.list');


// Route untuk update status pengajuan surat marketing
Route::put('/surat/marketing/updateStatusPengajuan/{id}', [SuratMarketingController::class, 'updateStatusPengajuan'])->name('surat.marketing.updateStatusPengajuan');
// Download file surat
Route::get('/surat-marketing/download/{id}', [SuratMarketingController::class, 'downloadfile'])->name('surat.marketing.downloadfile');

// View file PDF surat
Route::get('/surat-marketing/view/{id}', [SuratMarketingController::class, 'viewPDF'])->name('surat.marketing.viewPDF');

// Edit data surat
Route::get('/surat-marketing/{id}/edit', [SuratMarketingController::class, 'edit'])->name('surat.marketing.edit');

// Update data surat
Route::put('/surat-marketing/{id}', [SuratMarketingController::class, 'update'])->name('surat.marketing.update');

// Hapus data surat
Route::delete('/surat-marketing/{id}', [SuratMarketingController::class, 'destroy'])->name('surat.marketing.destroy');

Route::get('/surat/marketing/create', [SuratMarketingController::class, 'create'])->name('surat.marketing.create');
Route::get('/dashboard', [SuratMarketingController::class, 'dashboard'])->name('dashboard');




Route::get('/maintenances', [MaintenanceController::class, 'index'])->name('maintenances.index');
Route::get('/maintenances/create', [MaintenanceController::class, 'create'])->name('maintenances.create');
Route::post('/maintenances', [MaintenanceController::class, 'store'])->name('maintenances.store');
Route::get('/maintenances/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('maintenances.edit');
Route::put('/maintenances/{maintenance}', [MaintenanceController::class, 'update'])->name('maintenances.update');
Route::delete('/maintenances/{maintenance}', [MaintenanceController::class, 'destroy'])->name('maintenances.destroy');
Route::get('/maintenances/download', [MaintenanceController::class, 'downloadPdf'])->name('maintenances.downloadPdf');
Route::post('maintenances/hapusBulan', [MaintenanceController::class, 'hapusBulan'])->name('maintenances.hapusBulan');


use App\Http\Controllers\SuratFinanceController;

// Route untuk halaman daftar surat Finance
Route::get('/surat/finance', [SuratFinanceController::class, 'index'])->name('surat.finance.index');

// Route untuk membuat surat Finance
Route::get('/surat/finance/create', [SuratFinanceController::class, 'create'])->name('surat.finance.create');
Route::post('/surat/finance/generate', [SuratFinanceController::class, 'generate'])->name('surat.finance.generate');

// Route untuk mengunduh file surat Finance
Route::get('/surat/finance/downloadfile/{id}', [SuratFinanceController::class, 'downloadfile'])->name('surat.finance.downloadfile');

// Route untuk melihat PDF surat Finance
Route::get('/surat/finance/viewpdf/{id}', [SuratFinanceController::class, 'viewPDF'])->name('surat.finance.viewPDF');

// Route untuk mengedit surat Finance
Route::get('/surat/finance/edit/{id}', [SuratFinanceController::class, 'edit'])->name('surat.finance.edit');
Route::put('/surat/finance/update/{id}', [SuratFinanceController::class, 'update'])->name('surat.finance.update');

// Route untuk menghapus surat Finance
Route::delete('/surat/finance/{id}', [SuratFinanceController::class, 'destroy'])->name('surat.finance.destroy');

// Route untuk dashboard Surat Finance
Route::get('/surat/finance/dashboard', [SuratFinanceController::class, 'dashboard'])->name('surat.finance.dashboard');


// Update status pengajuan surat
Route::put('/surat-marketing/{id}/update-status', [SuratFinanceController::class, 'updateStatusPengajuan'])->name('surat.finance.updateStatusPengajuan'); 


use App\Http\Controllers\SuratAdminController;

// Route untuk halaman daftar surat Admin
Route::get('/surat/admin', [SuratAdminController::class, 'index'])->name('surat.admin.index');

// Route untuk membuat surat Admin
Route::get('/surat/admin/create', [SuratAdminController::class, 'create'])->name('surat.admin.create');
Route::post('/surat/admin/generate', [SuratAdminController::class, 'generate'])->name('surat.admin.generate');

// Route untuk mengunduh file surat Admin
Route::get('/surat/admin/downloadfile/{id}', [SuratAdminController::class, 'downloadfile'])->name('surat.admin.downloadfile');

// Route untuk melihat PDF surat Admin
Route::get('/surat/admin/viewpdf/{id}', [SuratAdminController::class, 'viewPDF'])->name('surat.admin.viewPDF');

// Route untuk mengedit surat Admin
Route::get('/surat/admin/edit/{id}', [SuratAdminController::class, 'edit'])->name('surat.admin.edit');
Route::put('/surat/admin/update/{id}', [SuratAdminController::class, 'update'])->name('surat.admin.update');

// Route untuk menghapus surat Admin
Route::delete('/surat/admin/{id}', [SuratAdminController::class, 'destroy'])->name('surat.admin.destroy');

// Route untuk dashboard Surat Admin
Route::get('/surat/admin/dashboard', [SuratAdminController::class, 'dashboard'])->name('surat.admin.dashboard');

// Update status pengajuan surat
Route::put('/surat-admin/{id}/update-status', [SuratAdminController::class, 'updateStatusPengajuan'])->name('surat.admin.updateStatusPengajuan');


use App\Http\Controllers\SuratWarehouseController;

Route::get('/surat-warehouse', [SuratWarehouseController::class, 'index'])->name('surat.warehouse.index');
Route::post('/surat-warehouse/generate', [SuratWarehouseController::class, 'generate'])->name('surat.warehouse.generate');
Route::get('/surat-warehouse/download/{id}', [SuratWarehouseController::class, 'downloadfile'])->name('surat.warehouse.download');
Route::put('/surat-warehouse/status/{id}', [SuratWarehouseController::class, 'updateStatusPengajuan'])->name('surat.warehouse.updateStatus');
Route::get('/surat-warehouse/view/{id}', [SuratWarehouseController::class, 'viewPDF'])->name('surat.warehouse.view');
Route::get('/surat-warehouse/edit/{id}', [SuratWarehouseController::class, 'edit'])->name('surat.warehouse.edit');
Route::put('/surat-warehouse/update/{id}', [SuratWarehouseController::class, 'update'])->name('surat.warehouse.update');
Route::delete('/surat-warehouse/delete/{id}', [SuratWarehouseController::class, 'destroy'])->name('surat.warehouse.destroy');
Route::get('/surat-warehouse/create', [SuratWarehouseController::class, 'create'])->name('surat.warehouse.create');

use App\Http\Controllers\SuratPurchasingController;

// Rute untuk halaman utama daftar surat
Route::get('/surat/purchasing', [SuratPurchasingController::class, 'index'])->name('surat.purchasing.index');

// Rute untuk generate nomor surat
Route::post('/surat/purchasing/generate', [SuratPurchasingController::class, 'generate'])->name('surat.purchasing.generate');

// Rute untuk mendownload file surat
Route::get('/surat/purchasing/download/{id}', [SuratPurchasingController::class, 'downloadfile'])->name('surat.purchasing.download');

// Rute untuk memperbarui status pengajuan surat
Route::put('/surat/purchasing/update-status/{id}', [SuratPurchasingController::class, 'updateStatusPengajuan'])->name('surat.purchasing.updateStatus');

// Rute untuk melihat file PDF di halaman web
Route::get('/surat/purchasing/view/{id}', [SuratPurchasingController::class, 'viewPDF'])->name('surat.purchasing.view');

// Rute untuk form edit surat
Route::get('/surat/purchasing/edit/{id}', [SuratPurchasingController::class, 'edit'])->name('surat.purchasing.edit');

// Rute untuk mengupdate surat
Route::put('/surat/purchasing/update/{id}', [SuratPurchasingController::class, 'update'])->name('surat.purchasing.update');

// Rute untuk menghapus surat
Route::delete('/surat/purchasing/destroy/{id}', [SuratPurchasingController::class, 'destroy'])->name('surat.purchasing.destroy');

// Rute untuk menampilkan form tambah surat
Route::get('/surat/purchasing/create', [SuratPurchasingController::class, 'create'])->name('surat.purchasing.create');

// Rute untuk dashboard finance
Route::get('/surat/finance/dashboard', [SuratPurchasingController::class, 'dashboard'])->name('surat.finance.dashboard');

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



// Form untuk generate surat
Route::get('/surat-marketing/generate', [SuratMarketingController::class, 'index'])->name('surat.marketing.generate.form');
Route::post('/surat-marketing/generate', [SuratMarketingController::class, 'generate'])->name('surat.marketing.generate');

// Menampilkan daftar surat marketing
Route::get('/digital-marketing/list', [SuratMarketingController::class, 'list'])->name('surat.digital_marketing.list');


// Update status pengajuan surat
Route::put('/surat-marketing/{id}/update-status', [SuratMarketingController::class, 'updateStatusPengajuan'])->name('surat.marketing.updateStatusPengajuan');

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



Route::get('/maintenances', [MaintenanceController::class, 'index'])->name('maintenances.index');
Route::get('/maintenances/create', [MaintenanceController::class, 'create'])->name('maintenances.create');
Route::post('/maintenances', [MaintenanceController::class, 'store'])->name('maintenances.store');
Route::get('/maintenances/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('maintenances.edit');
Route::put('/maintenances/{maintenance}', [MaintenanceController::class, 'update'])->name('maintenances.update');
Route::delete('/maintenances/{maintenance}', [MaintenanceController::class, 'destroy'])->name('maintenances.destroy');
Route::get('/maintenances/download', [MaintenanceController::class, 'downloadPdf'])->name('maintenances.downloadPdf');


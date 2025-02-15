<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OmsetController;


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



<?php

use App\Http\Controllers\BukBesController;
use App\Http\Controllers\COAController;
use App\Http\Controllers\JurnalAkunController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\konsepController;
use App\Http\Controllers\labarugiController;
use App\Http\Controllers\lapNeracController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\penyesuaianController;
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



Route::get('/beranda', [COAController::class, 'index']);
Route::get('/cTambahData', [COAController::class, 'create']);
Route::get('/cStore', [COAController::class, 'store']);
Route::get('/{id}/edit', [COAController::class, 'edit']);
Route::get('/{id}/update', [COAController::class, 'update']);
Route::delete('/{id}', [COAController::class, 'destroy']);


Route::get('/jurnal', [JurnalController::class, 'index']);
Route::get('/jTambahData', [JurnalController::class, 'create']);
Route::get('/jStore', [JurnalController::class, 'store'])->name('tambahJurnal');
Route::get('/{id}/editJ', [JurnalController::class, 'edit']);
Route::get('/{id}/updateJ', [JurnalController::class, 'update']);
Route::delete('/j/{id}', [JurnalController::class, 'destroy']);
//UPDATE JURNAL
Route::get('/jTambahDataDebit', [JurnalAkunController::class, 'storeDebit'])->name('jTambahDebit');
Route::get('/jTambahDataKredit', [JurnalAkunController::class, 'storeKredit'])->name('jTambahKredit');
Route::get('/jTambahData/{bukti}/{tgl}/{ktr}/{tr}', [JurnalAkunController::class, 'create'])->name('jTambahData');
Route::get('/jD/{id}/{bukti}/{tgl}/{ktr}/{tr}', [JurnalAkunController::class, 'DeleteDebit']);
Route::get('/jK/{id}/{bukti}/{tgl}/{ktr}/{tr}', [JurnalAkunController::class, 'DeleteKredit']);


//LOGIN
Route::get('/', [LoginController::class, 'create']);
Route::get('/check', [LoginController::class, 'lihat_data']);
Route::get('/loginCheck', [LoginController::class, 'LoginCheck']);
Route::get('/store', [LoginController::class, 'store']);
Route::get('/logout', [LoginController::class, 'logout']);

//BUKU BESAR
Route::get('/bukubesar', [BukBesController::class, 'index']);
Route::get('/bukubesar/{id}', [BukBesController::class, 'show']);

//NERACA
Route::get('/neracalajur', [NeracaController::class, 'index']);

//PENYESUAIAN
Route::get('/penyesuaian', [penyesuaianController::class, 'index']);
Route::get('/pTambahData', [penyesuaianController::class, 'create']);
Route::get('/pStore', [penyesuaianController::class, 'store']);
Route::get('/{id}/editP', [penyesuaianController::class, 'edit']);
Route::get('/{id}/updateP', [penyesuaianController::class, 'update']);
Route::delete('/p/{id}', [penyesuaianController::class, 'destroy']);

//KONSEP
Route::get('/konsep', [konsepController::class, 'index']);

//NERACALAPORAN
Route::get('/laporanneraca', [lapNeracController::class, 'index']);

//LABARUGI
Route::get('/labarugi', [labarugiController::class, 'index']);

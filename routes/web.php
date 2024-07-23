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
use App\Http\Controllers\penyesuaianAkunController;
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
Route::get('/{id}/{jurnalid}/{idakun}/{bukti}/{tgl}/{ktr}/{tr}/editJ', [JurnalController::class, 'edit']);
Route::get('/{id}/{jurnalid}/{idakun}/updateJ', [JurnalController::class, 'update']);
Route::delete('/j/{id}', [JurnalController::class, 'destroy']);

//UPDATE JURNAL
Route::get('/jTambahDataDebit', [JurnalAkunController::class, 'storeDebit'])->name('jTambahDebit');
Route::get('/jTambahDataKredit', [JurnalAkunController::class, 'storeKredit'])->name('jTambahKredit');
Route::get('/jTambahData/{jurnalid}/{bukti}/{tgl}/{ktr}/{tr}', [JurnalAkunController::class, 'create'])->name('jTambahData');
Route::get('/jD/{id}/{jurnalid}/{bukti}/{tgl}/{ktr}/{tr}', [JurnalAkunController::class, 'DeleteDebit']);
Route::get('/jK/{id}/{jurnalid}/{bukti}/{tgl}/{ktr}/{tr}', [JurnalAkunController::class, 'DeleteKredit']);
Route::get('/{id}/{jurnalid}/{bukti}/{tgl}/{ktr}/{tr}/editDebit', [JurnalAkunController::class, 'editDebit']);
Route::get('/{id}/{jurnalid}/{bukti}/{tgl}/{ktr}/{tr}/editKredit', [JurnalAkunController::class, 'editKredit']);
Route::get('/{id}/{jurnalid}/{bukti}/{tgl}/{ktr}/{tr}/updateD', [JurnalAkunController::class, 'UpdateDebit']);
Route::get('/{id}/{jurnalid}/{bukti}/{tgl}/{ktr}/{tr}/updateK', [JurnalAkunController::class, 'UpdateKredit']);

Route::post('/reset-jurnal/{jurnalid}', [JurnalController::class, 'resetJurnal'])->name('resetJurnal');
Route::post('/kembali-jurnal/{jurnalid}', [JurnalController::class, 'kembaliJurnal'])->name('kembaliJurnal');

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
Route::get('/pStore', [penyesuaianController::class, 'store'])->name('tambahPenyesuaian');
Route::get('/{id}/{penyesuaianid}/{idakun}/{bukti}/{tgl}/{tr}/editP', [penyesuaianController::class, 'edit']);
Route::get('/{id}/{penyesuaianid}/{idakun}/updateJ', [penyesuaianController::class, 'update']);
Route::delete('/p/{id}', [penyesuaianController::class, 'destroy']);

//Update Penyesuaian
Route::get('/pTambahDataDebit', [penyesuaianAkunController::class, 'storeDebit'])->name('pTambahDebit');
Route::get('/pTambahDataKredit', [penyesuaianAkunController::class, 'storeKredit'])->name('pTambahKredit');
Route::get('/pTambahData/{penyesuaianid}/{bukti}/{tgl}/{tr}', [penyesuaianAkunController::class, 'create'])->name('pTambahData');
Route::get('/pD/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}', [penyesuaianAkunController::class, 'DeleteDebit']);
Route::get('/pK/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}', [penyesuaianAkunController::class, 'DeleteKredit']);
Route::get('/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}/editDebitP', [penyesuaianAkunController::class, 'editDebit']);
http://127.0.0.1:8000/1/1/20202/2020-02-02/10202/editKreditP
Route::get('/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}/editKreditP', [penyesuaianAkunController::class, 'editKredit']);
Route::get('/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}/updateDP', [penyesuaianAkunController::class, 'UpdateDebit']);
Route::get('/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}/updateKP', [penyesuaianAkunController::class, 'UpdateKredit']);

Route::post('/reset-penyesuaian/{penyesuaianid}', [penyesuaianController::class, 'resetPenyesuaian'])->name('resetPenyesuaian');
Route::post('/kembali-penyesuaian/{penyesuaianid}', [penyesuaianController::class, 'kembaliPenyesuaian'])->name('kembaliPenyesuaian');


//KONSEP
Route::get('/konsep', [konsepController::class, 'index']);

//NERACALAPORAN
Route::get('/laporanneraca', [lapNeracController::class, 'index']);

//LABARUGI
Route::get('/labarugi', [labarugiController::class, 'index']);

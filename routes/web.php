<?php

use App\Http\Controllers\Auth\SocialiteController;
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
use App\Http\Controllers\resumeController;
use App\Http\Controllers\stockController;
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



Route::get('/beranda', [COAController::class, 'index'])->name('login');
Route::get('/cTambahData', [COAController::class, 'create']);
Route::get('/cStore', [COAController::class, 'store']);
Route::get('/{id}/edit', [COAController::class, 'edit']);
Route::get('/{id}/update', [COAController::class, 'update']);
Route::delete('/{id}', [COAController::class, 'destroy']);


Route::get('/jurnal', [JurnalController::class, 'index']);
Route::get('/jTambahData', [JurnalController::class, 'create']);
Route::get('/jStore', [JurnalController::class, 'store'])->name('tambahJurnal');
Route::get('/{id}/{jurnalid}/{idakun}/{bukti}/{tgl}/{ktr}/{tr}/editJ', [JurnalController::class, 'edit'])->name('jEdit');
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
Route::get('register/verify/{verify_key}', [LoginController::class, 'verify'])->name('verify')->middleware('auth');
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
Route::get('/{id}/{penyesuaianid}/{idakun}/updateP', [penyesuaianController::class, 'update']);
Route::delete('/p/{id}', [penyesuaianController::class, 'destroy']);

//Update Penyesuaian
Route::get('/pTambahDataDebit', [penyesuaianAkunController::class, 'storeDebit'])->name('pTambahDebit');
Route::get('/pTambahDataKredit', [penyesuaianAkunController::class, 'storeKredit'])->name('pTambahKredit');
Route::get('/pTambahData/{penyesuaianid}/{bukti}/{tgl}/{tr}', [penyesuaianAkunController::class, 'create'])->name('pTambahData');
Route::get('/pD/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}', [penyesuaianAkunController::class, 'DeleteDebit']);
Route::get('/pK/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}', [penyesuaianAkunController::class, 'DeleteKredit']);
Route::get('/{id}/{penyesuaianid}/{bukti}/{tgl}/{tr}/editDebitP', [penyesuaianAkunController::class, 'editDebit']);
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


//Ekspor Impor
Route::get('/export-jurnal', [JurnalController::class, 'export']);
Route::post('/import-jurnal', [JurnalController::class, 'import']);
Route::post('import-coa', [COAController::class, 'import'])->name('import.coa');
Route::post('/import-penye', [penyesuaianController::class, 'import'])->name('import.penye');
Route::post('import-stock', [resumeController::class, 'import'])->name('import.stock');

//BARANG
Route::get('/stock', [stockController::class, 'index']);
Route::get('/bStore', [stockController::class, 'store']);
Route::get('/inputstock', [stockController::class, 'createbarangmasuk']);
Route::get('/{jurnalid}/inputstock', [stockController::class, 'createBM']);
Route::delete('/{id}/bDelete', [stockController::class, 'delete']);
Route::get('/{id}/bEdit', [stockController::class, 'edit'])->name('bEdit');
Route::get('/{id}/bUpdate', [stockController::class, 'updateBM']);

Route::get('/stock-out', [stockController::class, 'indexBarangKeluar']);
Route::get('/bOutStore', [stockController::class, 'storeBarangKeluar']);
Route::delete('/{id}/bOutDelete', [stockController::class, 'deleteBarangKeluar']);
Route::get('/inputstockkeluar', [stockController::class, 'createbarangkeluar']);
Route::get('/{jurnalid}/inputstock-out', [stockController::class, 'createBK']);
Route::get('/{id}/bkEdit', [stockController::class, 'editBK'])->name('bkEdit');
Route::get('/{id}/bkUpdate', [stockController::class, 'updateBK']);

//RESUME
Route::get('/resume', [resumeController::class, 'index']);
Route::get('/LaporanStockKeluar', [resumeController::class, 'store']);
Route::delete('/{id}/itemsdelete', [resumeController::class, 'destroy']);

//LOGIN API
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
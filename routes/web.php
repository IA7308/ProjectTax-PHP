<?php

use App\Http\Controllers\COAController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LoginController;
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
Route::get('/jStore', [JurnalController::class, 'store']);
Route::get('/{id}/editJ', [JurnalController::class, 'edit']);
Route::get('/{id}/updateJ', [JurnalController::class, 'update']);
Route::delete('/j/{id}', [JurnalController::class, 'destroy']);

//LOGIN
Route::get('/', [LoginController::class, 'create']);
Route::get('/check', [LoginController::class, 'lihat_data']);
Route::get('/loginCheck', [LoginController::class, 'LoginCheck']);
Route::get('/store', [LoginController::class, 'store']);
Route::get('/logout', [LoginController::class, 'logout']);


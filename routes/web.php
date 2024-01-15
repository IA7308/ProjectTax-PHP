<?php

use App\Http\Controllers\COAController;
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



Route::get('/', [COAController::class, 'index']);
Route::get('/cTambahData', [COAController::class, 'create']);
Route::get('/cStore', [COAController::class, 'store']);

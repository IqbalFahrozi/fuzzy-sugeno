<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
Route::post('/terima-karyawan', [KaryawanController::class, 'terimaKaryawan'])->name('terima-karyawan');

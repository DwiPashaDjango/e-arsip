<?php

use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemberitahuanController;
use App\Http\Controllers\Petugas\ArsipController;
use App\Http\Controllers\ResetPasswordSekolah;
use App\Http\Controllers\Sekolah\FileArsipController;
use App\Http\Controllers\Sekolah\MyArsipController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'v_login')->name('login');
    Route::post('/login', 'login')->name('post.login');
    Route::post('/logout', 'logout')->name('logout');
});

// dashboard
Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::post('/dashboard/bios', 'storeBios')->name('dashboard.bios');
    Route::post('/dashboard/regencie', 'getRegencie')->name('dashboard.getRegencie');
    Route::post('/dashboard/district', 'getDistrict')->name('dashboard.getDistrict');
    Route::post('/dashboard/village', 'getVillage')->name('dashboard.getVillage');
});


Route::group(['middleware' => ['auth', 'cekRole:admin']], function () {
    Route::controller(PetugasController::class)->group(function () {
        Route::get('/petugas', 'index')->name('petugas');
        Route::get('/petugas/{id}', 'edit')->name('petugas.edit');
        Route::post('/petugas', 'store')->name('petugas.store');
        Route::put('/petugas/banned/{id}', 'banned')->name('petugas.banned');
        Route::put('/petugas/unbanned/{id}', 'unbanned')->name('petugas.unbanned');
        Route::put('/petugas/{id}', 'update')->name('petugas.update');
        Route::delete('/petugas/{id}', 'destroy')->name('petugas.destroy');
    });

    Route::controller(UserController::class)->group(function () {
        Route::put('/users/update/{id}', 'update')->name('users.update');
        Route::put('/users/banned/{id}', 'banned')->name('users.banned');
        Route::put('/users/unbanned/{id}', 'unbanned')->name('users.unbanned');
        Route::delete('/users/delete/{id}', 'destroy')->name('users.delete');
    });

    // reset sekolah
    Route::controller(ResetPasswordSekolah::class)->group(function () {
        Route::get('/reset-sekolah', 'index')->name('sekolah.reset');
        Route::get('/getSekolah/{id}', 'edit')->name('sekolah.show');
        Route::put('/reset-sekolah/{id}', 'update')->name('sekolah.change');
    });
});

// users
Route::group(['middleware' => ['auth', 'cekRole:admin,petugas']], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/{id}', 'edit')->name('users.edit');
        Route::get('/users/show/{id}', 'show')->name('user.show');
        Route::post('/users', 'store')->name('users.store');
        Route::put('/users/update/{id}', 'update')->name('users.update');
        Route::put('/users/banned/{id}', 'banned')->name('users.banned');
        Route::put('/users/unbanned/{id}', 'unbanned')->name('users.unbanned');
        Route::delete('/users/delete/{id}', 'destroy')->name('users.delete');
    });
});

// arsip
Route::controller(ArsipController::class)->group(function () {
    Route::get('/arsip', 'index')->name('arsip.index');
    Route::get('/arsip/json', 'getJson')->name('arsip.json');
    Route::get('/arsip/&title={title}&arsip={id}', 'show')->name('arsip.show');
    Route::get('/arsip/edit/{id}', 'edit')->name('arsip.edit');
    Route::get('/arsip/json/search', 'search')->name('arsip.search');
    Route::post('/arsip/filter', 'filterByYear')->name('arsip.filter');
    Route::post('/arsip', 'store')->name('arsip.store');
    Route::put('/arsip/{id}', 'update')->name('arsip.update');
    Route::delete('/arsip/delete/{id}', 'destroy')->name('arsip.delete');
});

// sekolah
Route::controller(FileArsipController::class)->group(function () {
    Route::post('/save/arsip/', 'store')->name('arsip.save');
});
Route::controller(MyArsipController::class)->group(function () {
    Route::get('/my-arsip', 'index');
});

// pemberitahuan
Route::controller(PemberitahuanController::class)->group(function () {
    Route::get('/pemberitahuan', 'getJsonPemberitahuan')->name('json.pemberitahuan');
    Route::post('/pemberitahuan', 'store')->name('json.pemberitahuan.store');
    Route::delete('/pemberitahuan/{id}', 'destroy')->name('json.pemberitahuan.delete');
});

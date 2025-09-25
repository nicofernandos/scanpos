<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/getsaleorder', [UserController::class,'getsaleorder'])->name('getsaleorder');

Route::post('/midtrans/notification', [UserController::class, 'midtransNotification'])
    ->name('midtrans.notification');

Route::get('/getcetakreservasi/{id}',[UserController::class,'getcetakreservasi'])->name('getcetakreservasi');

Route::get('/getlistsaleorder/{id}',[UserController::class,'getlistsaleorder'])->name('getlistsaleorder');

Route::get('/getshowreservasi/{id}',[UserController::class,'getshowreservasi'])->name('getshowreservasi');
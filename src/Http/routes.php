<?php

use Liumenggit\PaySet\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Liumenggit\PaySet\Http\Controllers\FileUpdateController;
use Liumenggit\PaySet\Http\Controllers\PaySetController;

//Route::get('pay-set', Controllers\PaySetController::class.'@index');

Route::resource('pay-set', Controllers\PaySetController::class);
Route::resource('pay-test', Controllers\PayTestController::class);
//Route::any('pay-file-update', [PaySetController::class, 'handle']);

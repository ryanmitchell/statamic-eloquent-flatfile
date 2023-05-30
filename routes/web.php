<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Statamic\Facades;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::statamic('reset-password', 'reset_password');

Route::get('test', [TestController::class, 'index']);

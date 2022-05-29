<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/', [UserController::class, 'index']);

Route::get('user/export', [UserController::class, 'export'])->name('user.export');
Route::get('user/import-template', [UserController::class, 'importTemplate'])->name('user.import.template');
Route::get('user/import', [UserController::class, 'importForm'])->name('user.import');
Route::post('user/import', [UserController::class, 'importStore'])->name('user.import');
Route::resource('user', UserController::class)->parameters([
    'user' => 'data'
]);

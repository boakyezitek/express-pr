<?php

use App\Http\Controllers\StaffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

require __DIR__.'/auth.php';

Route::get('/staff/visible-on-website', [StaffController::class, 'visibleOnWebsite']);
Route::get('/staff', [StaffController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/staff/{staff}', [StaffController::class, 'show'])->middleware(['auth:sanctum']);
Route::post('/staff', [StaffController::class, 'create'])->middleware(['auth:sanctum']);
Route::put('/staff/{staff}', [StaffController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->middleware(['auth:sanctum']);


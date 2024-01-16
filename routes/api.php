<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\VendorController;
use App\Models\Tenant;
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

Route::get('/clients', [ClientController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/clients/{client}', [ClientController::class, 'show'])->middleware(['auth:sanctum']);
Route::post('/clients', [ClientController::class, 'create'])->middleware(['auth:sanctum']);
Route::put('/clients/{client}', [ClientController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->middleware(['auth:sanctum']);

Route::get('/tenants', [TenantController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->middleware(['auth:sanctum']);
Route::post('/tenants', [TenantController::class, 'create'])->middleware(['auth:sanctum']);
Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->middleware(['auth:sanctum']);

Route::get('/vendors', [VendorController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/vendors/{vendor}', [VendorController::class, 'show'])->middleware(['auth:sanctum']);
Route::post('/vendors', [VendorController::class, 'create'])->middleware(['auth:sanctum']);
Route::put('/vendors/{vendor}', [VendorController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/vendors/{vendor}', [VendorController::class, 'destroy'])->middleware(['auth:sanctum']);


Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/{property}', [PropertyController::class, 'show'])->middleware(['auth:sanctum']);
Route::post('/properties', [PropertyController::class, 'create'])->middleware(['auth:sanctum']);
Route::put('/properties/{property}', [PropertyController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->middleware(['auth:sanctum']);

Route::get('/expenses', [ExpensesController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/expenses/{expense}', [ExpensesController::class, 'show'])->middleware(['auth:sanctum']);
Route::post('/expenses', [ExpensesController::class, 'create'])->middleware(['auth:sanctum']);
Route::put('/expenses/{expense}', [ExpensesController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/expenses/{expense}', [ExpensesController::class, 'destroy'])->middleware(['auth:sanctum']);

Route::get('/payments', [PaymentController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/payments/{payment}', [PaymentController::class, 'show'])->middleware(['auth:sanctum']);
Route::post('/payments', [PaymentController::class, 'create'])->middleware(['auth:sanctum']);
Route::put('/payments/{payment}', [PaymentController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->middleware(['auth:sanctum']);


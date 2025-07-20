<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\user\AuthController as UserAuthController;
use App\Http\Controllers\admin\CategorieController;
use App\Http\Controllers\admin\evenementController;
use App\Http\Controllers\user\reservationController;
use App\Http\Controllers\admin\reservationController as AdminReservationController;

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
Route::post('/admin/login', [AuthController::class, 'login']);

Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reservation/create', [reservationController::class, 'create']);
    Route::get('/reservation/list', [reservationController::class, 'index']);
    Route::delete('/reservation/delete/{id}', [reservationController::class, 'delete']);
});

Route::middleware(['auth:sanctum', 'est_admin'])->group(function () {
    Route::post('/admin/categories/create', [CategorieController::class, 'create']);
    Route::get('/admin/categories/list', [CategorieController::class, 'index']);
    Route::put('/admin/categories/update/{id}', [CategorieController::class, 'update']);
    Route::delete('/admin/categories/delete/{id}', [CategorieController::class, 'delete']);

    Route::post('/admin/evenements/create', [evenementController::class, 'create']);
    Route::get('/admin/evenements/list', [evenementController::class, 'index']);

    Route::get('/admin/reservations/list', [AdminReservationController::class, 'index']);
});


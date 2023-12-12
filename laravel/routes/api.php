<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\VcardController;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
  Route::post('logout', [AuthController::class, 'logout']);
  Route::get('users/me', [UserController::class, 'show_me']);
});

Route::get('users', [UserController::class, 'index']);
Route::get('vcards', [VcardController::class, 'index']);
Route::get('transactions', [TransactionController::class, 'index']);

Route::get('users/{user}', [UserController::class, 'show']);
Route::get('vcards/{vcard}', [VcardController::class, 'show']);
Route::get('transactions/{transaction}', [TransactionController::class, 'show']);

Route::post('users', [UserController::class, 'store']);
Route::post('vcards', [VcardController::class, 'store']);
Route::post('transactions', [TransactionController::class, 'store']);

Route::put('users/{user}', [UserController::class, 'update']);
Route::put('vcards/{vcard}', [VcardController::class, 'update']);
Route::put('transactions/{transaction}', [TransactionController::class, 'update']);

Route::delete('users/{user}', [UserController::class, 'destroy']);
Route::delete('vcards/{vcard}', [VcardController::class, 'destroy']);
Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy']);

Route::post('login', [AuthController::class, 'login']);



  // dont delete yet
  /*
  Route::get('users/{user}', [UserController::class, 'show'])
    ->middleware('can:view,user');
  Route::put('users/{user}', [UserController::class, 'update'])
    ->middleware('can:update,user');
  Route::patch('users/{user}/password', [UserController::class, 'update_password'])
    ->middleware('can:updatePassword,user');
  */
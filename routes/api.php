<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CollectionController;
use App\Http\Controllers\API\TransfertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('requesttopay', [CollectionController::class, 'requestToPay'])->name('requesttopay');
    Route::post('maketransaction/bank', [TransfertController::class, 'makeTransfertbank'])->name('makeTransfertbank');
    Route::post('maketransaction/mobile', [TransfertController::class, 'makeTransfert'])->name('makeTransfert');
});
Route::controller(AuthController::class)->group(function(){

    Route::post('register', 'register');

    Route::post('authenticate', 'login');

});

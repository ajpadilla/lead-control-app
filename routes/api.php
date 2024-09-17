<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeadController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => 'lead-control/v1', 'namespace' => 'Api'], function () {
    Route::post('auth', [AuthController::class, 'login']);
    Route::post('leads', [LeadController::class, 'store'])->middleware(['auth:api', 'checkRolePermission:create-lead']);
    Route::get('leads/{id}', [LeadController::class, 'show'])->middleware(['auth:api']);
    Route::get('leads', [LeadController::class, 'index'])->middleware(['auth:api']);
});


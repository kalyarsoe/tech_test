<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\InternetServiceProviderController;

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

Route::post('login', [LoginController::class, 'login']); 

Route::middleware('auth:ctj-api')->group(function () {
    
    Route::get('posts', [PostController::class, 'list']);
    Route::post('posts/reaction', [PostController::class, 'toggleReaction']);

    Route::post('{entity}/invoice-amount', [InternetServiceProviderController::class, 'getInvoiceAmount']);

    Route::post('job/apply', [JobController::class, 'apply']);

    Route::post('staff/salary', [StaffController::class, 'payroll']);
}); 


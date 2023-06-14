<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrganizationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('members', [MemberController::class, 'index']);
Route::get('members/{id}', [MemberController::class, 'show']);
Route::post('members', [MemberController::class, 'store']);
Route::put('members/{id}', [MemberController::class, 'update']);
Route::delete('members/{id}', [MemberController::class, 'delete']);

Route::get('organization', [OrganizationController::class, 'index']);
Route::put('organization', [OrganizationController::class, 'update']);

Route::get('contact', [ContactController::class, 'index']);
Route::get('contact/{id}', [ContactController::class, 'show']);
Route::post('contact', [ContactController::class, 'store']);
Route::put('contact/{id}', [ContactController::class, 'update']);
Route::delete('contact/{id}', [ContactController::class, 'delete']);

Route::fallback(function () {
    return response()->json([
        'message' => 'Rute Not Found.'
    ], 404);
});

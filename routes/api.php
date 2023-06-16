<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocialMediaItemController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserController;
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

Route::get('contacts', [ContactController::class, 'index']);
Route::get('contacts/{id}', [ContactController::class, 'show']);
Route::post('contacts', [ContactController::class, 'store']);
Route::put('contacts/{id}', [ContactController::class, 'update']);
Route::delete('contacts/{id}', [ContactController::class, 'delete']);


Route::get('testimonials', [TestimonialController::class, 'index']);
Route::get('testimonials/{id}', [TestimonialController::class, 'show']);
Route::post('testimonials', [TestimonialController::class, 'store']);
Route::put('testimonials/{id}', [TestimonialController::class, 'update']);
Route::delete('testimonials/{id}', [TestimonialController::class, 'delete']);


Route::get('socialMediaItems', [SocialMediaItemController::class, 'index']);
Route::get('socialMediaItems/{id}', [SocialMediaItemController::class, 'show']);
Route::post('socialMediaItems', [SocialMediaItemController::class, 'store']);
Route::put('socialMediaItems/{id}', [SocialMediaItemController::class, 'update']);
Route::delete('socialMediaItems/{id}', [SocialMediaItemController::class, 'delete']);

Route::get('projects', [ProjectController::class, 'index']);
Route::get('projects/{id}', [ProjectController::class, 'show']);
Route::post('projects', [ProjectController::class, 'store']);
Route::put('projects/{id}', [ProjectController::class, 'update']);
Route::delete('projects/{id}', [ProjectController::class, 'delete']);


Route::get('roles', [RoleController::class, 'index']);
Route::get('roles/{id}', [RoleController::class, 'show']);
Route::put('roles/{id}', [RoleController::class, 'update']);

Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::patch('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'delete']);

Route::fallback(function () {
    return response()->json([
        'message' => 'Rute Not Found.'
    ], 404);
});

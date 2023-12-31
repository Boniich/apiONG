<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SlideController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

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


Route::get('socialmediaitems', [SocialMediaItemController::class, 'index']);
Route::get('socialmediaitems/{id}', [SocialMediaItemController::class, 'show']);
Route::post('socialmediaitems', [SocialMediaItemController::class, 'store']);
Route::put('socialmediaitems/{id}', [SocialMediaItemController::class, 'update']);
Route::delete('socialmediaitems/{id}', [SocialMediaItemController::class, 'delete']);

Route::get('projects', [ProjectController::class, 'index']);
Route::get('projects/{id}', [ProjectController::class, 'show']);
Route::post('projects', [ProjectController::class, 'store']);
Route::put('projects/{id}', [ProjectController::class, 'update']);
Route::delete('projects/{id}', [ProjectController::class, 'delete']);


Route::get('roles', [RoleController::class, 'index']);
Route::get('roles/{id}', [RoleController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('roles/{id}', [RoleController::class, 'update']);
});

Route::get('users/{id}', [UserController::class, 'show']);
Route::get('users/', [UserController::class, 'index']);

Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::patch('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'delete']);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::post('categories', [CategoryController::class, 'store']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
Route::patch('categories/{id}', [CategoryController::class, 'update']);
Route::delete('categories/{id}', [CategoryController::class, 'delete']);

Route::get('slides', [SlideController::class, 'index']);
Route::get('slides/{id}', [SlideController::class, 'show']);
Route::post('slides', [SlideController::class, 'store']);
Route::put('slides/{id}', [SlideController::class, 'update']);
Route::patch('slides/{id}', [SlideController::class, 'update']);
Route::delete('slides/{id}', [SlideController::class, 'delete']);

Route::get('activities', [ActivityController::class, 'index']);
Route::get('activities/{id}', [ActivityController::class, 'show']);
Route::post('activities', [ActivityController::class, 'store']);
Route::put('activities/{id}', [ActivityController::class, 'update']);
Route::patch('activities/{id}', [ActivityController::class, 'update']);
Route::delete('activities/{id}', [ActivityController::class, 'delete']);

Route::get('news', [NewsController::class, 'index']);
Route::get('news/{id}', [NewsController::class, 'show']);
Route::post('news', [NewsController::class, 'store']);
Route::put('news/{id}', [NewsController::class, 'update']);
Route::patch('news/{id}', [NewsController::class, 'update']);
Route::delete('news/{id}', [NewsController::class, 'delete']);


Route::get('comments', [CommentController::class, 'index']);
Route::get('comments/{id}', [CommentController::class, 'show']);
Route::post('comments', [CommentController::class, 'store']);
Route::put('comments/{id}', [CommentController::class, 'update']);
Route::patch('comments/{id}', [CommentController::class, 'update']);
Route::delete('comments/{id}', [CommentController::class, 'delete']);

Route::fallback(function () {
    return response()->json([
        'message' => 'Rute Not Found.'
    ], 404);
});

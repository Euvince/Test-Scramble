<?php

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

$verificationLimiter = config('fortify.limiters.verification', '6,1');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Route::post('register', [App\Http\Controllers\API\Auth\AuthController::class, 'register'])->name('register');
Route::post('login', [App\Http\Controllers\API\Auth\AuthController::class, 'login'])->name('login'); */

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource(name : 'roles', controller : App\Http\Controllers\API\RoleController::class);
    Route::apiResource(name : 'users', controller : App\Http\Controllers\API\UserController::class);
    Route::apiResource(name : 'posts', controller : App\Http\Controllers\API\PostController::class);
    Route::apiResource(name : 'posts.comments', controller : App\Http\Controllers\API\CommentController::class);

    /* Route::get('logout', [App\Http\Controllers\API\Auth\AuthController::class, 'logout'])->name('logout'); */
});

Route::post(uri : 'email/verification-notification', action : [App\Http\Controllers\API\Auth\EmailVerificationNotificationController::class, 'store'])
    ->middleware([
        'auth:sanctum',
        'throttle:'.$verificationLimiter
    ]);

Route::post(uri : 'update-profile-picture', action : [App\Http\Controllers\API\Auth\ProfilePictureController::class, 'update']);
Route::post(uri : 'remove-profile-picture', action : [App\Http\Controllers\API\Auth\ProfilePictureController::class, 'delete']);


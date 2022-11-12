<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// auth

Route::prefix('v1')->group(function(){
    Route::group(['prefix' => "auth"], function(){
        Route::group(['middleware' => ['guest']], function(){
            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/register', [AuthController::class, 'register']);
        });
        
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
        Route::get('/me/{id}', [AuthController::class, 'showForms'])->middleware('auth:sanctum');
    });

    Route::group(['middleware' => 'auth:sanctum'], function(){

        Route::apiResource('/forms', FormController::class);
        Route::apiResource('/forms.questions', QuestionController::class)->middleware('formfound&iscreator');
        Route::delete('/forms/{form}/questions', [QuestionController::class, 'destroyAll'])->middleware('formfound&iscreator');

        Route::get('/forms/{form}/responses', [ResponseController::class, 'index'])->middleware(['formfound&iscreator']);
        Route::get('/forms/{form}/responses/{id}', [ResponseController::class, 'show'])->middleware(['formfound&iscreator']);
        Route::post('/forms/{form}/responses', [ResponseController::class, 'store'])->middleware('allowed');
        Route::get('/forms/{form}/checkAllowed', [ResponseController::class, 'checkAllowed'])->middleware('allowed');
    });
});

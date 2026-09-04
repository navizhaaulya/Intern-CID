<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrudController;
// use App\Http\Controllers\CustomController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\bannerController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\VotingController;



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


    Route::get('/no-auth/banners', [PublicController::class, 'banners']);
    Route::get('/no-auth/about', [PublicController::class, 'about']);
    Route::get('/no-auth/vision-mission', [PublicController::class, 'visionMission']);
    Route::get('/no-auth/footer', [PublicController::class, 'footer']);

    Route::get('/no-auth/events', [PublicController::class, 'events']);
    Route::get('/no-auth/events/{slug}', [PublicController::class, 'eventDetail']);

    Route::get('votings', [PublicController::class, 'votings']);
    
    Route::get('feedback-categories', [PublicController::class, 'feedbackCategories']);
    Route::post('feedbacks', [PublicController::class, 'submitFeedback']);

    Route::get('/no-auth/majors', [PublicController::class, 'majors']);
    Route::get('/no-auth/majors/{slug}', [PublicController::class, 'majorDetail']);

   Route::get('/no-auth/votings', [PublicController::class, 'voting']);
    Route::get('/no-auth/votings/{slug}', [PublicController::class, 'votingDetail']);
   
    Route::get('/no-auth/news', [PublicController::class, 'news']);
    Route::get('/no-auth/news/{id}', [PublicController::class, 'NewsDetail']);

Route::middleware(['setguard:api','auth.rest'])->group(function(){
 Route::get('/feedbacks', [PublicController::class, 'feedbacks']);

 Route::post( '/votings/{id}/vote', [VotingController::class, 'vote']);
 Route::put('/votings/highlight', [VotingController::class, 'updateHighlight']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

});


Route::group([
    'middleware' => ['setguard:api', 'auth.rest']
], function () {

    Route::get('/{model}', [CrudController::class, 'index']);
    Route::get('/{model}/dataset', [CrudController::class, 'dataset']);
    Route::post('/{model}', [CrudController::class, 'create']);
    Route::put('/{model}/{id}', [CrudController::class, 'update']);
    Route::delete('/{model}/{id}', [CrudController::class, 'delete']);
    Route::get('/{model}/{id}', [CrudController::class, 'show']);

    // Route::post('upload', [UploadController::class, 'upload'])->name("upload")->middleware('auth.rest');


    Route::get('/gen-lang/lang', [CrudController::class, 'lang']);
    Route::get('/gen-model/{model}', [CrudController::class, 'generate']);
    Route::get('/gen-module/listmodule', [CrudController::class, 'listModule']);
});

Route::group([
    'middleware' => ['setguard:api']
], function () {

    Route::get('file/{model}/{field}/{id}/{time}', [UploadController::class, 'getFile']);
    Route::get('file/{model}/{field}/{id}/{time}/download', [UploadController::class, 'downloadFile']);
    Route::get('tumb-file/{model}/{field}/{id}/{time}', [UploadController::class, 'getTumbnailFile']);
    Route::get('temp-file/{path}/{time}/{ext}', [UploadController::class, 'getTempFile']);
    Route::get('tumb-temp-file/{path}/{time}/{ext}', [UploadController::class, 'getThumbTempFile']);
    
    Route::post('upload', [UploadController::class, 'upload'])->name("upload");
});
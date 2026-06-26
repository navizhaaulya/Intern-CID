<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrudController;
// use App\Http\Controllers\CustomController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisionMissionController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MajorController;
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

Route::get('/vision-mission', [VisionMissionController::class, 'index']);
Route::post('/vision-mission', [VisionMissionController::class, 'store']);
Route::delete('/vision-mission/{id}', [VisionMissionController::class, 'delete']);
Route::put('/vision-mission/{id}', [VisionMissionController::class, 'update']);

Route::get('/news', [NewsController::class, 'index']);
Route::post('/news', [NewsController::class, 'store']);
Route::delete('/news/{id}', [NewsController::class, 'delete']);
Route::put('/news/{id}', [NewsController::class, 'update']);

Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::delete('/events/{id}', [EventController::class, 'delete']);
Route::put('/events/{id}', [EventController::class, 'update']);

Route::get('/banners', [BannerController::class, 'index']);
Route::post('/banners', [BannerController::class, 'store']);
Route::delete('/banners/{id}', [BannerController::class, 'delete']);
Route::put('/banners/{id}', [BannerController::class, 'update']);

Route::get('/majors', [MajorController::class, 'index']);
Route::post('/majors', [MajorController::class, 'store']);
Route::delete('/majors/{id}', [MajorController::class, 'delete']);
Route::put('/majors/{id}', [MajorController::class, 'update']);

Route::get('/votings', [VotingController::class, 'index']);
Route::post('/votings', [VotingController::class, 'store']);
Route::get('/votings/{id}', [VotingController::class, 'delete']);
Route::post('/votings/{id}', [VotingController::class, 'update']);


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
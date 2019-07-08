<?php

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

Route::prefix('auth')->group(function () {
    Route::post('login', 'ApiController@doLogin');
});

Route::middleware('api-auth')->group(function () {
    Route::post('image/get', 'ApiController@doFetchImages');
    Route::post('image/upload', 'ApiController@doUpload');
    Route::post('image/update', 'ApiController@updateImage');
    Route::post('search', 'ApiController@doSearch');
    Route::post('jobnumbers/get', 'ApiController@getJobNumbers');
    Route::post('company-list/get', 'ApiController@getCompanyList');
});

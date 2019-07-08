<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Example Routes
//Route::view('/', 'landing');
//Route::view('/dashboard', 'dashboard');
//Route::view('/examples/plugin', 'examples.plugin');
//Route::view('/examples/blank', 'examples.blank');

Route::get('/', 'AdminController@index');
Route::get('/login', 'AdminController@index');
Route::post('/login', 'AdminController@doLogin');
Route::get('/logout', 'AdminController@logout');

Route::middleware('admin-auth')->group(function () {

    Route::post('/editProfile', 'AdminController@editProfile');

    Route::prefix('admin')->group(function () {
        Route::get('/', 'AdminController@showAdminlistPage');
        Route::post('add', 'AdminController@addAdmin');
        Route::post('edit', 'AdminController@editAdmin');
        Route::post('del', 'AdminController@delAdmin');
        Route::post('get', 'AdminController@getAdmin');
    });

    Route::prefix('user')->group(function () {
        Route::get('/', 'AdminController@showUserlistPage');
        Route::post('add', 'AdminController@addUser');
        Route::post('edit', 'AdminController@editUser');
        Route::post('del', 'AdminController@delUser');
        Route::post('get', 'AdminController@getUser');
    });

    Route::prefix('company')->group(function () {
        Route::get('/', 'AdminController@showCompanylistPage');
        Route::post('add', 'AdminController@addCompany');
        Route::post('edit', 'AdminController@editCompany');
        Route::post('del', 'AdminController@delCompany');
        Route::post('get', 'AdminController@getCompany');
    });

    Route::prefix('jobnumber')->group(function () {
        Route::get('/', 'AdminController@showJobNumberPage');
        Route::post('add', 'AdminController@addJobNumber');
        Route::post('edit', 'AdminController@editJobNumber');
        Route::post('del', 'AdminController@delJobNumber');
        Route::post('get', 'AdminController@getJobNumber');
    });

    Route::prefix('image')->group(function () {
        Route::get('/', 'AdminController@showImagelistPage');
        Route::post('edit', 'AdminController@editImage');
        Route::post('del', 'AdminController@delImage');
        Route::post('get', 'AdminController@getImage');
    });

});
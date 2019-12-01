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
Route::middleware(['auth'])->group(function(){
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('/movie', 'MovieController', ['names' => 'movie']);
    Route::resource('/member', 'MemberController', ['names' => 'member']);
    Route::prefix('/lending-movie')->name('lending-movie.')->group(function () {
        Route::get('/', 'LendingMovieController@index')->name('index');
        Route::get('/data', 'LendingMovieController@data')->name('data');
        Route::get('/members', 'LendingMovieController@members')->name('members');
        Route::get('/{movie_id}/create', 'LendingMovieController@create')->name('create');
        Route::put('/store', 'LendingMovieController@store')->name('store');
    });
});

Auth::routes();

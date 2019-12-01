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
    Route::get('/', function () {
        return redirect()->route('home.index');
    });

    Route::prefix('/home')->name('home.')->group(function () {
        Route::get('/', 'HomeController@index')->name('index');
        Route::get('/total-movie', 'HomeController@totalMovie')->name('total-movie');
        Route::get('/total-member', 'HomeController@totalMember')->name('total-member');
        Route::get('/lending-chart', 'HomeController@lendingChart')->name('lending-chart');
    });

    Route::resource('/movie', 'MovieController', ['names' => 'movie']);
    Route::resource('/member', 'MemberController', ['names' => 'member']);
    Route::prefix('/lending-movie')->name('lending-movie.')->group(function () {
        Route::get('/', 'LendingMovieController@index')->name('index');
        Route::get('/data', 'LendingMovieController@data')->name('data');
        Route::get('/members', 'LendingMovieController@members')->name('members');
        Route::get('/{movie_id}/create', 'LendingMovieController@create')->name('create');
        Route::post('/store', 'LendingMovieController@store')->name('store');
    });

    Route::prefix('/return-movie')->name('return-movie.')->group(function () {
        Route::get('/', 'ReturnMovieController@index')->name('index');
        Route::get('/data', 'ReturnMovieController@data')->name('data');
        Route::get('/{id}/edit', 'ReturnMovieController@edit')->name('edit');
        Route::put('/{id}/update', 'ReturnMovieController@update')->name('update');
    });
});

Auth::routes();

<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'App\Http\Controllers\AppController@initiate');
Route::get('/search', 'App\Http\Controllers\AppController@redirectSearch');

Route::get('/id/{id}.jsonld', 'App\Http\Controllers\dbi\dbiController@provideJson');
Route::get('/id/{id}.txt', 'App\Http\Controllers\dbi\dbiController@provideTxt');
Route::get('/id/{id}', 'App\Http\Controllers\dbi\dbiController@redirectID');

Route::get('/id-{id}.jsonld', 'App\Http\Controllers\dbi\dbiController@provideJson');
Route::get('/pir-id-{id}.jsonld', 'App\Http\Controllers\dbi\dbiController@provideJson');

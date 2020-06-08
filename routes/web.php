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
// Route::resource('contact', 'ContactController');
Route::get('/','ContactController@index')->name('home');
Route::delete('/contact/delete/{id}','ContactController@destroy')->name('delete');
Route::post('/contact/create/','ContactController@store')->name('create');
Route::get('/contact/get/{id}','ContactController@edit')->name('get-contact');
Route::put('/contact/update/{id}','ContactController@update')->name('update');
Route::get('/contact/search','ContactController@show')->name('search');

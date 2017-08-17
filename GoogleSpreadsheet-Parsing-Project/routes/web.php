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

Route::get('/', 'IndexController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin', 'AdminController@index')->name('admin');
Route::post('/admin/parse', 'AdminController@parsefolder');

Route::get('auth/callback', 'IndexController@regcode');
Route::get('auth/google', 'GoogleController@redirectToProvider')->name('google.login');
Route::get('auth/google/callback', 'GoogleController@handleProviderCallback');

Route::get('/settings', 'SettingsController@sheet')->name('settingsheet');
Route::get('/selectid', 'SettingsController@selectid')->name('selectid');
Route::get('/status', 'SettingsController@status')->name('settingstatus');
Route::get('/dashboard', 'DashBoardController@index')->name('dashboard');

Route::post('/savestatus', 'SettingsController@savestatus')->name('savestatus');
Route::post('/savesheet', 'SettingsController@savesheet')->name('savesheet');
Route::post('/analfolder', 'AdminController@analyzefolder')->name('analfolder');
Route::get('/reorderstatus', 'SettingsController@reorderstatus')->name('reorderstatus');
<?php

Route::get('auth/google', 'Auth\GoogleController@redirectToProvider')->name('google.login');
?>
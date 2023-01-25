<?php

use Illuminate\Support\Facades\Route;

Route::get('/oscar/main', 'Oscar\Massive\Controllers\MainController@index');

Route::post('/oscar/massive/', 'Oscar\Massive\Controllers\MassiveController@create');
Route::put('/oscar/massive/', 'Oscar\Massive\Controllers\MassiveController@update');
Route::delete('/oscar/massive/', 'Oscar\Massive\Controllers\MassiveController@delete');


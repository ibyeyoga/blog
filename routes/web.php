<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::controller(App\Http\Controllers\PostController::class)->prefix('post')->group(function () {
    Route::get('/list', 'list')->name('post.list');
    Route::get('/edit/{id?}', 'edit')->name('post.edit');
    Route::post('/post', 'post')->name('post.post');
    Route::post('/del/{id}', 'del')->name('post.del');
});


Route::controller(App\Http\Controllers\LogController::class)->prefix('log')->group(function () {
    Route::get('/list', 'list')->name('log.list');
});
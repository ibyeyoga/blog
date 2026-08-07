<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::controller(App\Http\Controllers\PostController::class)->prefix('post')->group(function () {
    Route::get('/list', 'list')->name('post.list');
    Route::get('/edit/{id?}', 'edit')->name('post.edit');
    Route::post('/post', 'post')->name('post.post');
    Route::post('/del/{id}', 'del')->name('post.del');
});
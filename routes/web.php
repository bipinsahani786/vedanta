<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/about', 'about')->name('about');
Route::view('/services', 'services')->name('services');
Route::view('/jobs', 'jobs')->name('jobs');
Route::view('/hiring-process', 'hiring')->name('hiring');
Route::view('/contact', 'contact')->name('contact');
Route::view('/apply', 'apply')->name('apply');
Route::view('/post-job', 'post-job')->name('post-job');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/media', 'media')->name('media');

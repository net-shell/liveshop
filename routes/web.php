<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/source/{source}', function ($source, $feed = null) {
    return $source->apiData();
})->name('source');

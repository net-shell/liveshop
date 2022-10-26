<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/client', function () {
    $sources = App\Models\Source::all();
    return view('client', compact('sources'));
})->name('client');

Route::get('/source/{source}/{feed?}', function ($source, Request $request, $feed = null) {
    $url = $request->input('url');
    if ($feed && $url) {
        $source->api_url = $url;
        $source->content_type = $feed;
    }
    return $source->apiData();
})->name('source');

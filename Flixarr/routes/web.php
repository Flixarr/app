<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Setup Routes
|--------------------------------------------------------------------------|
*/

Route::name('setup')->prefix('setup')->middleware(['setup.incomplete'])->group(function () {
    Route::name('.plex-auth')->get('/plex/authentication', App\Http\Pages\Setup\PlexAuth::class);
    Route::name('.plex-servers')->get('/plex/servers', App\Http\Pages\Setup\PlexServers::class);
    Route::name('.services')->get('/services', App\Http\Pages\Setup\Services::class);
    Route::redirect('/', route('setup.plex-auth'));
});

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------|
*/

Route::view('/', 'pages.index.index-page')->name('home');

// Route::get('/setup', App\Http\Pages\Setup\SetupIndex::class)->name('setup');

/*
|--------------------------------------------------------------------------
| Utility Routes
|--------------------------------------------------------------------------|
*/

Route::view('/loading', 'pages.loading')->name('loading');

Route::get('/test', function () {
    return 'test';
});

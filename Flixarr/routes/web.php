<?php

use App\Services\PlexApi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Setup Routes
|--------------------------------------------------------------------------|
*/

Route::name('setup.')->prefix('setup')->middleware(['setup.incomplete'])->group(function () {
    Route::name('index')->get('/', function () {
        return redirect()->route('setup.plex-auth');
    });

    // Step one
    Route::name('plex-auth')->get('/plex/authentication', App\Http\Pages\Setup\PlexAuth::class);
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
    return (new PlexApi())->plexTvCall('/api/v2/ping');
});

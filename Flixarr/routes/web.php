<?php

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

    Route::name('plex-auth')->get('/plex/authentication', App\Http\Pages\Setup\PlexAuth::class);
    Route::name('plex-servers')->get('/plex/servers', App\Http\Pages\Setup\PlexServers::class);
    Route::name('services')->get('/services', App\Http\Pages\Setup\Services::class);
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
    return (new \App\Services\PlexTv())->call('/pms/:/ip');
    if ((new \App\Services\PlexTv())->verifyAuth()) {
        return "yes";
    } else {
        return "no";
    }
});

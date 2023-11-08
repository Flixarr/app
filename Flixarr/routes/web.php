<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'pages.index.index-page');
Route::get('/setup', App\Http\Pages\Setup\SetupPage::class);

Route::view('/loading', 'pages.loading');

Route::get('/test', function () {
});

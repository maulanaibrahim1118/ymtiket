<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index'])
    ->middleware('guest')->name('login.index');
Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.auth');
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')->name('login.out');

// Route Dashboard
Route::get('/dashboard{id}-{role}', [DashboardController::class, 'index'])
    ->middleware('auth')->name('dashboard.index');

// Route Client
Route::resource('/clients', ClientController::class)
    ->middleware('auth');

// Route User
Route::resource('/users', UserController::class)
    ->middleware('auth');

// Route Location
Route::resource('/locations', LocationController::class)
    ->middleware('auth');

// Route Asset
Route::resource('/assets', AssetController::class)
    ->middleware('auth');

// Route Category Asset
Route::resource('/category-assets', CategoryAssetController::class)
    ->middleware('auth');

// Route Category Ticket
Route::resource('/category-tickets', CategoryTicketController::class)
    ->middleware('auth');
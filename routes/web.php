<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketDetailController;

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

// Route Ticket
Route::get('/tickets{id}-{role}', [TicketController::class, 'index'])
    ->middleware('auth')->name('ticket.index');
Route::get('/tickets/create{id}-{role}', [TicketController::class, 'create'])
    ->middleware('auth')->name('ticket.create');
Route::post('/tickets', [TicketController::class, 'store'])
    ->middleware('auth')->name('ticket.store');
Route::get('/tickets{id}-{role}/{ticket}/edit', [TicketController::class, 'edit'])
    ->middleware('auth')->name('ticket.edit');
Route::put('/update-tickets/{id}', [TicketController::class, 'update'])
    ->middleware('auth')->name('ticket.update');
Route::put('/tickets/{id}', [TicketController::class, 'delete'])
    ->middleware('auth')->name('ticket.delete');

// Route Dropdown Getting Ticket
Route::get('/tickets/create2{id}', [TicketController::class, 'getClient'])
    ->middleware('auth')->name('getClient');
    Route::get('/tickets/create3{id}', [TicketController::class, 'getLocation'])
    ->middleware('auth')->name('getLocation');
    Route::get('/tickets/create4{id}', [TicketController::class, 'getAssets'])
    ->middleware('auth')->name('getAssets');
    
// Route Ticket Detail
Route::get('/ticket-details/{id}', [TicketDetailController::class, 'index'])
    ->middleware('auth')->name('ticket-detail.index');
Route::get('/ticket-details/{id}/create', [TicketDetailController::class, 'create'])
    ->middleware('auth')->name('ticket-detail.create');
Route::post('/ticket-details', [TicketDetailController::class, 'store'])
    ->middleware('auth')->name('ticket-detail.store');
Route::get('/ticket-details/{id}/edit', [TicketDetailController::class, 'edit'])
    ->middleware('auth')->name('ticket-detail.edit');
Route::put('/ticket-details/{id}', [TicketDetailController::class, 'update'])
    ->middleware('auth')->name('ticket-detail.update');
Route::put('/ticket-details/{id}/pending', [TicketDetailController::class, 'pending'])
    ->middleware('auth')->name('ticket-detail.pending');
Route::put('/ticket-details/{id}/reProcess', [TicketDetailController::class, 'reProcess'])
    ->middleware('auth')->name('ticket-detail.reProcess');
Route::put('/ticket-details/{id}/assign', [TicketDetailController::class, 'assign'])
    ->middleware('auth')->name('ticket-detail.assign');

// Route Dropdown Getting Category Ticket
Route::get('/ticket-details/{id}/create1', [TicketDetailController::class, 'getSubCategoryTicket'])
    ->middleware('auth')->name('getSubCategoryTicket');
    
// Route Ticket Comment
Route::resource('/ticket-comments', TicketCommentController::class)
    ->middleware('auth');

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

// Route Sub Category Ticket
Route::resource('/category-sub-tickets', SubCategoryTicketController::class)
    ->middleware('auth');
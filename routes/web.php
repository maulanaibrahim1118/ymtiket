<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SearchTicketController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketDetailController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CategoryTicketController;
use App\Http\Controllers\SubCategoryTicketController;

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

// Route Login
Route::get('/', [LoginController::class, 'index'])
    ->middleware('guest')->name('login.index');
Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.auth');
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')->name('login.out');

// Route Cari Ticket
Route::post('/search-ticket', [SearchTicketController::class, 'show'])
    ->middleware('guest')->name('search.ticket');

// Route Dashboard
Route::get('/dashboard/{id}-{role}', [DashboardController::class, 'index'])
    ->middleware('auth')->name('dashboard.index');

// Route Filter
Route::get('/dashboard/{filter}/{id}-{role}', [FilterController::class, 'filterDashboard'])
    ->middleware('auth')->name('dashboard.filter');
Route::get('/tickets/{status}-{filter}-{id}-{role}', [DashboardController::class, 'ticket'])
    ->middleware('auth')->name('ticket.filter');
Route::get('/agents/{status}-{filter}-{location}', [FilterController::class, 'filterAgent'])
    ->middleware('auth')->name('agent.filter');
Route::get('/assets/{status}-{filter}-{id}-{role}', [DashboardController::class, 'asset'])
    ->middleware('auth', 'service.desk')->name('asset.filter');

// Route Ticket
Route::get('/tickets/{id}-{role}', [TicketController::class, 'index'])
    ->middleware('auth')->name('ticket.index');
Route::get('/tickets/asset{asset}', [TicketController::class, 'asset'])
    ->middleware('auth')->name('ticket.asset');
    
Route::middleware(['auth', 'manage.ticket'])->group(function () {
    Route::get('/tickets/{id}-{role}/create', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('/tickets/store', [TicketController::class, 'store'])->name('ticket.store');
    Route::get('/tickets/{id}-{role}/edit{ticket}', [TicketController::class, 'edit'])->name('ticket.edit');
    Route::put('/tickets/update{id}', [TicketController::class, 'update'])->name('ticket.update');
    Route::put('/tickets/delete{id}', [TicketController::class, 'delete'])->name('ticket.delete');
});
Route::middleware(['auth', 'agent.info'])->group(function () {
    Route::put('/tickets/{id}/process1', [TicketController::class, 'process1'])->name('ticket.process1');
    Route::put('/tickets/{id}/process2', [TicketController::class, 'process2'])->name('ticket.process2');
    Route::put('/tickets/queue{id}', [TicketController::class, 'queue'])->name('ticket.queue');
    Route::put('/tickets/assign', [TicketController::class, 'assign'])->name('ticket.assign');
    Route::put('/tickets/assign2', [TicketController::class, 'assign2'])->name('ticket.assign2');
    Route::put('/tickets/pending{id}', [TicketController::class, 'pending'])->name('ticket.pending');
    Route::put('/tickets/{id}/reProcess1', [TicketController::class, 'reProcess1'])->name('ticket.reProcess1');
    Route::get('/tickets/{id}/reProcess2', [TicketController::class, 'reProcess2'])->name('ticket.reProcess2');
    Route::put('/tickets/resolved{id}', [TicketController::class, 'resolved'])->name('ticket.resolved');
});
Route::put('/tickets/finished{id}', [TicketController::class, 'finished'])
    ->middleware('auth', 'client')->name('ticket.finished');
    
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
    
Route::middleware(['auth', 'agent.info'])->group(function () {
    Route::get('/ticket-details/{id}/create', [TicketDetailController::class, 'create'])->name('ticket-detail.create');
    Route::post('/ticket-details/process', [TicketDetailController::class, 'store'])->name('ticket-detail.store');
    Route::get('/ticket-details/{id}/edit', [TicketDetailController::class, 'edit'])->name('ticket-detail.edit');
    Route::put('/ticket-details/update{id}', [TicketDetailController::class, 'update'])->name('ticket-detail.update');
});

// Route Dropdown Getting Category Ticket
Route::get('/ticket-details/{id}/create1', [TicketDetailController::class, 'getSubCategoryTicket'])
    ->middleware('auth')->name('getSubCategoryTicket');
    
// Route Ticket Comment
Route::resource('/ticket-comments', TicketCommentController::class)
    ->middleware('auth');

// Route Agent
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/agents/{location}', [AgentController::class, 'index'])
        ->name('agent.index');
    Route::post('/agents-update{id}', [AgentController::class, 'update'])
        ->name('agent.update');
    Route::get('/agents/refresh/{id}', 'AgentController@agentsRefresh');
});

// Route Client
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::resource('/clients', ClientController::class);
});

// Route User
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::resource('/users', UserController::class);
});

// Route Location
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::resource('/locations', LocationController::class);
});

// Route Asset
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::resource('/assets', AssetController::class);
});

// Route Category Asset
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::resource('/category-assets', CategoryAssetController::class);
});

// Route Category Ticket
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/category-tickets/{id}', [CategoryTicketController::class, 'index'])->name('ct.index');
    Route::get('/category-tickets/{id}/create', [CategoryTicketController::class, 'create'])->name('ct.create');
    Route::post('/category-tickets', [CategoryTicketController::class, 'store'])->name('ct.store');
    Route::get('/category-tickets/{id}/edit{category_ticket}', [CategoryTicketController::class, 'edit'])->name('ct.edit');
    Route::put('/category-tickets/{category_ticket}', [CategoryTicketController::class, 'update'])->name('ct.update');
});

// Route Sub Category Ticket
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/category-sub-tickets/{id}', [SubCategoryTicketController::class, 'index'])->name('sct.index');
    Route::get('/category-sub-tickets/{id}/create', [SubCategoryTicketController::class, 'create'])->name('sct.create');
    Route::post('/category-sub-tickets', [SubCategoryTicketController::class, 'store'])->name('sct.store');
    Route::get('/category-sub-tickets/{id}/edit{category_sub_ticket}', [SubCategoryTicketController::class, 'edit'])->name('sct.edit');
    Route::put('/category-sub-tickets/{category_sub_ticket}', [SubCategoryTicketController::class, 'update'])->name('sct.update');
});

Route::view('/error-403-authenticated', 'contents.error.403-authenticated')->name('403.authenticated');
Route::view('/error-403-unauthorized', 'contents.error.403-unauthorized')->name('403.unauthorized');
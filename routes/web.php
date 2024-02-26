<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
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

Route::get('/', [LoginController::class, 'index'])
    ->middleware('guest')->name('login.index');
Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.auth');
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')->name('login.out');

// Route Dashboard
Route::get('/dashboard/{id}-{role}', [DashboardController::class, 'index'])
    ->middleware('auth')->name('dashboard.index');
Route::get('/dashboard/{filter}/{id}-{role}', [DashboardController::class, 'filter'])
    ->middleware('auth')->name('dashboard.filter');
Route::get('/tickets/{status}-{filter}-{id}-{role}', [DashboardController::class, 'ticket'])
    ->middleware('auth')->name('ticket.filter');

// Route Ticket
Route::get('/tickets/{id}-{role}', [TicketController::class, 'index'])
    ->middleware('auth')->name('ticket.index');
Route::get('/tickets/{id}-{role}/create', [TicketController::class, 'create'])
    ->middleware('auth')->name('ticket.create');
Route::post('/tickets/store', [TicketController::class, 'store'])
    ->middleware('auth')->name('ticket.store');
Route::get('/tickets/{id}-{role}/edit{ticket}', [TicketController::class, 'edit'])
    ->middleware('auth')->name('ticket.edit');
Route::put('/tickets/update{id}', [TicketController::class, 'update'])
    ->middleware('auth')->name('ticket.update');
Route::put('/tickets/delete{id}', [TicketController::class, 'delete'])
    ->middleware('auth')->name('ticket.delete');
Route::put('/tickets/{id}/process1', [TicketController::class, 'process1'])
    ->middleware('auth')->name('ticket.process1');
Route::put('/tickets/{id}/process2', [TicketController::class, 'process2'])
    ->middleware('auth')->name('ticket.process2');
Route::put('/tickets/queue{id}', [TicketController::class, 'queue'])
    ->middleware('auth')->name('ticket.queue');
Route::put('/tickets/assign', [TicketController::class, 'assign'])
    ->middleware('auth')->name('ticket.assign');
Route::put('/tickets/assign2', [TicketController::class, 'assign2'])
    ->middleware('auth')->name('ticket.assign2');
Route::put('/tickets/pending{id}', [TicketController::class, 'pending'])
    ->middleware('auth')->name('ticket.pending');
Route::put('/tickets/{id}/reProcess1', [TicketController::class, 'reProcess1'])
    ->middleware('auth')->name('ticket.reProcess1');
Route::get('/tickets/{id}/reProcess2', [TicketController::class, 'reProcess2'])
    ->middleware('auth')->name('ticket.reProcess2');
Route::put('/tickets/resolved{id}', [TicketController::class, 'resolved'])
    ->middleware('auth')->name('ticket.resolved');
Route::put('/tickets/finished{id}', [TicketController::class, 'finished'])
    ->middleware('auth')->name('ticket.finished');
    
// Route::put('/tickets/{id}/process2', [TicketController::class, 'process2'])
//     ->middleware('auth')->name('ticket.process2');
// Route::get('/tickets/{id}-{nik}/process3', [TicketController::class, 'process3'])
//     ->middleware('auth')->name('ticket.process3');

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
Route::post('/ticket-details/process', [TicketDetailController::class, 'store'])
    ->middleware('auth')->name('ticket-detail.store');
Route::get('/ticket-details/{id}/edit', [TicketDetailController::class, 'edit'])
    ->middleware('auth')->name('ticket-detail.edit');
Route::put('/ticket-details/update{id}', [TicketDetailController::class, 'update'])
    ->middleware('auth')->name('ticket-detail.update');

// Route::put('/ticket-details/{id}/reProcess', [TicketDetailController::class, 'reProcess'])
//     ->middleware('auth')->name('ticket-detail.reProcess');
// Route::put('/ticket-details/{id}/assign1', [TicketDetailController::class, 'assign1'])
//     ->middleware('auth')->name('ticket-detail.assign1');
// Route::put('/ticket-details/{id}/assign2', [TicketDetailController::class, 'assign2'])
//     ->middleware('auth')->name('ticket-detail.assign2');

// Route Dropdown Getting Category Ticket
Route::get('/ticket-details/{id}/create1', [TicketDetailController::class, 'getSubCategoryTicket'])
    ->middleware('auth')->name('getSubCategoryTicket');
    
// Route Ticket Comment
Route::resource('/ticket-comments', TicketCommentController::class)
    ->middleware('auth');

// Route Agent
Route::get('/agents/{location}', [AgentController::class, 'index'])
    ->middleware('auth')->name('agent.index');
Route::post('/agents-update{id}', [AgentController::class, 'update'])
    ->middleware('auth')->name('agent.update');
    Route::get('/agents/refresh/{id}', 'AgentController@agentsRefresh');

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
Route::get('/category-tickets/{id}', [CategoryTicketController::class, 'index'])
    ->middleware('auth')->name('ct.index');
Route::get('/category-tickets/{id}/create', [CategoryTicketController::class, 'create'])
    ->middleware('auth')->name('ct.create');
Route::post('/category-tickets', [CategoryTicketController::class, 'store'])
    ->middleware('auth')->name('ct.store');
Route::get('/category-tickets/{id}/edit{category_ticket}', [CategoryTicketController::class, 'edit'])
    ->middleware('auth')->name('ct.edit');
Route::put('/category-tickets/{category_ticket}', [CategoryTicketController::class, 'update'])
    ->middleware('auth')->name('ct.update');

// Route Sub Category Ticket
Route::get('/category-sub-tickets/{id}', [SubCategoryTicketController::class, 'index'])
    ->middleware('auth')->name('sct.index');
Route::get('/category-sub-tickets/{id}/create', [SubCategoryTicketController::class, 'create'])
    ->middleware('auth')->name('sct.create');
Route::post('/category-sub-tickets', [SubCategoryTicketController::class, 'store'])
    ->middleware('auth')->name('sct.store');
Route::get('/category-sub-tickets/{id}/edit{category_sub_ticket}', [SubCategoryTicketController::class, 'edit'])
    ->middleware('auth')->name('sct.edit');
Route::put('/category-sub-tickets/{category_sub_ticket}', [SubCategoryTicketController::class, 'update'])
    ->middleware('auth')->name('sct.update');
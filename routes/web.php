<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportAgentController;
use App\Http\Controllers\SearchTicketController;
use App\Http\Controllers\TicketDetailController;
use App\Http\Controllers\CategoryAssetController;
use App\Http\Controllers\CategoryTicketController;
use App\Http\Controllers\TicketApprovalController;
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
Route::get('/', [LoginController::class, 'index'])->middleware('guest')->name('login.index');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth');
Route::post('/search-ticket', [SearchTicketController::class, 'show'])->middleware('guest')->name('search.ticket');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('login.out');

// Route Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index');

// Route Filter
Route::post('/dashboard/filter/', [FilterController::class, 'filterDashboard'])->middleware('auth')->name('dashboard.filter');

// Route Ticket (semua user)
Route::get('/tickets-dashboard', [TicketController::class, 'ticketDashboard'])->middleware('auth')->name('ticket.dashboard');
Route::get('/tickets', [TicketController::class, 'index'])->middleware('auth')->name('ticket.index');
Route::get('/tickets/asset', [TicketController::class, 'ticketAsset'])->middleware('auth')->name('ticket.asset');

// Route Ticket (Role = Client/Service Desk)
Route::middleware(['auth', 'manage.ticket'])->group(function () {
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('/tickets/store', [TicketController::class, 'store'])->name('ticket.store');
    Route::get('/tickets/edit', [TicketController::class, 'edit'])->name('ticket.edit');
    Route::put('/tickets/update', [TicketController::class, 'update'])->name('ticket.update');
    Route::put('/tickets/delete', [TicketController::class, 'delete'])->name('ticket.delete');
});

// Route Ticket (Role = Agent/Service Desk)
Route::middleware(['auth', 'agent.info'])->group(function () {
    Route::put('/tickets/first-process', [TicketController::class, 'process1'])->name('ticket.process1');
    Route::put('/tickets/process-assigned', [TicketController::class, 'process2'])->name('ticket.process2');
    Route::put('/tickets/process-approved', [TicketController::class, 'process3'])->name('ticket.process3');
    Route::put('/tickets/queue', [TicketController::class, 'queue'])->name('ticket.queue');
    Route::put('/tickets/assign', [TicketController::class, 'assign'])->name('ticket.assign');
    Route::put('/tickets/assign2', [TicketController::class, 'assign2'])->name('ticket.assign2');
    Route::put('/tickets/pull', [TicketController::class, 'pull'])->name('ticket.pull');
    Route::put('/tickets/pending', [TicketController::class, 'pending'])->name('ticket.pending');
    Route::put('/tickets/reProcess1', [TicketController::class, 'reProcess1'])->name('ticket.reProcess1');
    Route::get('/tickets/reProcess2', [TicketController::class, 'reProcess2'])->name('ticket.reProcess2');
    Route::put('/tickets/resolved', [TicketController::class, 'resolved'])->name('ticket.resolved');
});

// Route Ticket (Role = Client)
Route::put('/tickets/finished', [TicketController::class, 'finished'])->middleware('auth', 'manage.ticket')->name('ticket.finished');

// Route Ticket (Dropdown JQuery)
Route::get('/tickets/create2{id}', [TicketController::class, 'getClient'])->middleware('auth')->name('getClient');
Route::get('/tickets/create3{id}', [TicketController::class, 'getLocation'])->middleware('auth')->name('getLocation');
Route::get('/tickets/create4{id}', [TicketController::class, 'getAssets'])->middleware('auth')->name('getAssets');

// Route Ticket Detail (semua user)
Route::get('/ticket-details', [TicketDetailController::class, 'index'])->middleware('auth')->name('ticket-detail.index');

// Route Ticket Detail (Role = Agent/Service Desk)
Route::middleware(['auth', 'agent.info'])->group(function () {
    Route::get('/ticket-details/create', [TicketDetailController::class, 'create'])->name('ticket-detail.create');
    Route::post('/ticket-details/process', [TicketDetailController::class, 'store'])->name('ticket-detail.store');
    Route::get('/ticket-details/edit', [TicketDetailController::class, 'edit'])->name('ticket-detail.edit');
    Route::put('/ticket-details/update', [TicketDetailController::class, 'update'])->name('ticket-detail.update');
});

// Route Ticket Detail (Dropdown JQuery)
Route::get('/ticket-details/{id}/create1', [TicketDetailController::class, 'getSubCategoryTicket'])->middleware('auth')->name('getSubCategoryTicket');

// Route Ticket Comment
Route::resource('/ticket-comments', TicketCommentController::class)->middleware('auth');

// Route Ticket Approval (Khusus Korwil)
Route::put('/ticket-approval', [TicketApprovalController::class, 'update'])->middleware('auth')->name('ticket-approval.store');

// Route Agent
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/agents', [AgentController::class, 'index'])->name('agent.index');
    Route::post('/agents/update/{id}', [AgentController::class, 'update'])->name('agent.update');
    Route::get('/agents/refresh/{id}', 'AgentController@agentsRefresh');
});

// Route User
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users', [UserController::class, 'update'])->name('user.update');
});

// Route Location
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/locations', [LocationController::class, 'index'])->name('location.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('location.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('location.store');
    Route::get('/locations/edit', [LocationController::class, 'edit'])->name('location.edit');
    Route::put('/locations', [LocationController::class, 'update'])->name('location.update');
});

// Route Asset
Route::get('/assets-dashboard', [AssetController::class, 'assetDashboard'])->middleware('auth', 'service.desk')->name('asset.dashboard');
Route::middleware(['auth', 'manage.ticket'])->group(function () {
    Route::get('/assets', [AssetController::class, 'index'])->name('asset.index');
    Route::get('/assets/create', [AssetController::class, 'create'])->name('asset.create');
    Route::post('/assets', [AssetController::class, 'store'])->name('asset.store');
    Route::get('/assets/edit', [AssetController::class, 'edit'])->name('asset.edit');
    Route::put('/assets', [AssetController::class, 'update'])->name('asset.update');
});

// Route Category Asset
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/category-assets', [CategoryAssetController::class, 'index'])->name('ca.index');
    Route::get('/category-assets/create', [CategoryAssetController::class, 'create'])->name('ca.create');
    Route::post('/category-assets', [CategoryAssetController::class, 'store'])->name('ca.store');
    Route::get('/category-assets/edit', [CategoryAssetController::class, 'edit'])->name('ca.edit');
    Route::put('/category-assets', [CategoryAssetController::class, 'update'])->name('ca.update');
});

// Route Category Ticket
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/category-tickets', [CategoryTicketController::class, 'index'])->name('ct.index');
    Route::get('/category-tickets/create', [CategoryTicketController::class, 'create'])->name('ct.create');
    Route::post('/category-tickets', [CategoryTicketController::class, 'store'])->name('ct.store');
    Route::get('/category-tickets/edit', [CategoryTicketController::class, 'edit'])->name('ct.edit');
    Route::put('/category-tickets', [CategoryTicketController::class, 'update'])->name('ct.update');
});

// Route Sub Category Ticket
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/category-sub-tickets', [SubCategoryTicketController::class, 'index'])->name('sct.index');
    Route::get('/category-sub-tickets/create', [SubCategoryTicketController::class, 'create'])->name('sct.create');
    Route::post('/category-sub-tickets', [SubCategoryTicketController::class, 'store'])->name('sct.store');
    Route::get('/category-sub-tickets/edit', [SubCategoryTicketController::class, 'edit'])->name('sct.edit');
    Route::put('/category-sub-tickets', [SubCategoryTicketController::class, 'update'])->name('sct.update');
    Route::get('/category-sub-tickets-dashboard', [SubCategoryTicketController::class, 'kendalaDashboard'])->name('kendala.dashboard');
});

// Route Report
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/report-agents', [ReportAgentController::class, 'index'])->name('report.agent');
});

Route::get('/settings/change-password', [UserController::class, 'showChangePasswordForm'])->middleware('auth')->name('setting.change');
Route::post('/settings/change-password', [UserController::class, 'changePassword'])->middleware('auth')->name('change.password');

Route::view('/error-419-csrf-error', 'contents.error.419-csrf-error')->name('csrf.error');
Route::view('/error-403-unauthorized', 'contents.error.403-unauthorized')->name('403.unauthorized');
Route::view('/error-404-underconstruction', 'contents.error.404-underconstruction')->name('404.underconstruction');
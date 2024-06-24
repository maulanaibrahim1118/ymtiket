<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportAgentController;
use App\Http\Controllers\SubDivisionController;
use App\Http\Controllers\SearchTicketController;
use App\Http\Controllers\TicketDetailController;
use App\Http\Controllers\CategoryAssetController;
use App\Http\Controllers\CategoryTicketController;
use App\Http\Controllers\ReportLocationController;
use App\Http\Controllers\TicketApprovalController;
use App\Http\Controllers\ReportSubCategoryController;
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
Route::get('/ticket-shared', [SearchTicketController::class, 'shared'])->middleware('guest')->name('ticket.shared');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('login.out');

// Route Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index');

// Route Filter
Route::post('/dashboard/filter', [FilterController::class, 'dashboard'])->middleware('auth')->name('dashboard.filter');
Route::post('/report-agents/filter', [FilterController::class, 'reportAgent'])->middleware(['auth', 'service.desk'])->name('reportAgent.filter');
Route::post('/report-locations/filter', [FilterController::class, 'reportLocation'])->middleware(['auth', 'service.desk'])->name('reportLocation.filter');
Route::post('/report-sub-categories/filter', [FilterController::class, 'reportSubCategory'])->middleware(['auth', 'service.desk'])->name('reportSubCategory.filter');

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

Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::put('/tickets/assignAnother', [TicketController::class, 'assignAnother'])->name('ticket.assignAnother');
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
    Route::put('/users/switch', [UserController::class, 'switch'])->name('user.switch');
    Route::get('/users/create1{id}', [UserController::class, 'getSubDivisions'])->middleware('auth')->name('getSubDivisions');
});

// Route Location
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/locations', [LocationController::class, 'index'])->name('location.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('location.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('location.store');
    Route::get('/locations/edit', [LocationController::class, 'edit'])->name('location.edit');
    Route::put('/locations', [LocationController::class, 'update'])->name('location.update');
    Route::put('/locations/close', [LocationController::class, 'close'])->name('location.close');
    Route::put('/locations/activate', [LocationController::class, 'activate'])->name('location.activate');
    Route::get('/get-detail-wilayah/{id}', [LocationController::class, 'getDetailWilayah']);
    
    Route::get('/location-sub-divisions', [SubDivisionController::class, 'index'])->name('subDivision.index');
    Route::get('/location-sub-divisions/create', [SubDivisionController::class, 'create'])->name('subDivision.create');
    Route::post('/location-sub-divisions', [SubDivisionController::class, 'store'])->name('subDivision.store');
    Route::get('/location-sub-divisions/edit', [SubDivisionController::class, 'edit'])->name('subDivision.edit');
    Route::put('/location-sub-divisions', [SubDivisionController::class, 'update'])->name('subDivision.update');
    
    Route::get('/location-areas', [AreaController::class, 'index'])->name('area.index');
    Route::get('/location-areas/create', [AreaController::class, 'create'])->name('area.create');
    Route::post('/location-areas', [AreaController::class, 'store'])->name('area.store');
    Route::get('/location-areas/edit', [AreaController::class, 'edit'])->name('area.edit');
    Route::put('/location-areas', [AreaController::class, 'update'])->name('area.update');
    Route::put('/location-areas/delete', [AreaController::class, 'destroy'])->name('area.delete');

    Route::get('/location-regionals', [RegionalController::class, 'index'])->name('regional.index');
    Route::get('/location-regionals/create', [RegionalController::class, 'create'])->name('regional.create');
    Route::post('/location-regionals', [RegionalController::class, 'store'])->name('regional.store');
    Route::put('/location-regionals', [RegionalController::class, 'update'])->name('regional.update');
    Route::put('/location-regionals/delete', [RegionalController::class, 'destroy'])->name('regional.delete');
    
    Route::get('/location-wilayahs', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('/location-wilayahs/create', [WilayahController::class, 'create'])->name('wilayah.create');
    Route::post('/location-wilayahs', [WilayahController::class, 'store'])->name('wilayah.store');
    Route::put('/location-wilayahs', [WilayahController::class, 'update'])->name('wilayah.update');
    Route::put('/location-wilayahs/delete', [WilayahController::class, 'destroy'])->name('wilayah.delete');
    Route::get('/get-detail-regional/{id}', [WilayahController::class, 'getDetailRegional']);
});

// Route Item
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/asset-items', [ItemController::class, 'index'])->name('item.index');
    Route::get('/asset-items/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('/asset-items', [ItemController::class, 'store'])->name('item.store');
    Route::get('/asset-items/edit', [ItemController::class, 'edit'])->name('item.edit');
    Route::put('/asset-items', [ItemController::class, 'update'])->name('item.update');
});

// Route Asset
Route::get('/assets-dashboard', [AssetController::class, 'assetDashboard'])->middleware('auth', 'service.desk')->name('asset.dashboard');
Route::middleware(['auth', 'manage.ticket'])->group(function () {
    Route::get('/assets', [AssetController::class, 'index'])->name('asset.index');
    Route::get('/assets/create', [AssetController::class, 'create'])->name('asset.create');
    Route::post('/assets', [AssetController::class, 'store'])->name('asset.store');
    Route::get('/assets/edit', [AssetController::class, 'edit'])->name('asset.edit');
    Route::put('/assets', [AssetController::class, 'update'])->name('asset.update');
    Route::get('/assets/{id}/create1', [AssetController::class, 'getItem'])->middleware('auth')->name('getItem');
});

// Route Asset Category
Route::middleware(['auth', 'service.desk'])->group(function () {
    Route::get('/asset-categories', [CategoryAssetController::class, 'index'])->name('ca.index');
    Route::get('/asset-categories/create', [CategoryAssetController::class, 'create'])->name('ca.create');
    Route::post('/asset-categories', [CategoryAssetController::class, 'store'])->name('ca.store');
    Route::get('/asset-categories/edit', [CategoryAssetController::class, 'edit'])->name('ca.edit');
    Route::put('/asset-categories', [CategoryAssetController::class, 'update'])->name('ca.update');
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
    Route::get('/report-locations', [ReportLocationController::class, 'index'])->name('report.location');
    Route::get('/report-sub-categories', [ReportSubCategoryController::class, 'index'])->name('report.subCategory');
    Route::get('/categories/export', [ReportSubCategoryController::class, 'export'])->name('export.reportSubCategory');
});

// Route::get('/settings-change-password', [UserController::class, 'showChangePasswordForm'])->middleware('auth')->name('setting.password');
// Route::post('/profile-check-password', [UserController::class, 'checkPassword'])->middleware('auth')->name('profile.checkPassword');
Route::get('/profile', [UserController::class, 'profile'])->middleware('auth')->name('profile.index');
Route::post('/profile-update', [UserController::class, 'updateProfile'])->middleware('auth')->name('profile.update');
Route::post('/profile-change-password', [UserController::class, 'changePassword'])->middleware('auth')->name('change.password');

Route::view('/error-403-unauthorized', 'contents.error.403-unauthorized')->name('403.unauthorized');
Route::view('/error-404-underconstruction', 'contents.error.404-underconstruction')->name('404.underconstruction');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MutationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;


// ========================================
// HALAMAN LOGIN
// ========================================

Route::get('/', [LoginController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.process');


// ========================================
// HALAMAN SETELAH LOGIN
// ========================================

Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    // LOGOUT
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');


    // ========================================
    // DATA RUANGAN
    // ========================================

    Route::get('/ruangan', [RoomController::class, 'index'])
        ->name('rooms.index');

    Route::get('/ruangan/tambah', [RoomController::class, 'create'])
        ->name('rooms.create');

    Route::post('/ruangan', [RoomController::class, 'store'])
        ->name('rooms.store');

    Route::patch('/ruangan/{room}/status', [RoomController::class, 'toggleStatus'])
        ->name('rooms.toggleStatus');

    Route::get('/ruangan/{room}/edit', [RoomController::class, 'edit'])
        ->name('rooms.edit');

    Route::put('/ruangan/{room}', [RoomController::class, 'update'])
        ->name('rooms.update');


    // ========================================
    // KATEGORI BARANG
    // ========================================

    Route::get('/kategori', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::get('/kategori/tambah', [CategoryController::class, 'create'])
        ->name('categories.create');

    Route::post('/kategori', [CategoryController::class, 'store'])
        ->name('categories.store');

    Route::post('/kategori/quick-store', [CategoryController::class, 'quickStore'])
        ->name('categories.quickStore');

    Route::get('/kategori/{category}/edit', [CategoryController::class, 'edit'])
        ->name('categories.edit');

    Route::put('/kategori/{category}', [CategoryController::class, 'update'])
        ->name('categories.update');

    Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');


    // ========================================
    // INVENTARIS RUANGAN
    // ========================================

    Route::get('/inventaris', [InventoryController::class, 'index'])
        ->name('inventories.index');

    Route::get('/inventaris/tambah', [InventoryController::class, 'create'])
        ->name('inventories.create');

    Route::post('/inventaris', [InventoryController::class, 'store'])
        ->name('inventories.store');

    Route::get('/inventaris/ruangan/{room}', [InventoryController::class, 'room'])
        ->name('inventories.room');

    Route::get('/inventaris/{roomInventory}/edit', [InventoryController::class, 'edit'])
        ->name('inventories.edit');

    Route::put('/inventaris/{roomInventory}', [InventoryController::class, 'update'])
        ->name('inventories.update');

    Route::delete('/inventaris/{roomInventory}', [InventoryController::class, 'destroy'])
        ->name('inventories.destroy');


    // ========================================
    // MUTASI BARANG
    // ========================================

    Route::get('/mutasi', [MutationController::class, 'index'])
        ->name('mutations.index');

    Route::get('/mutasi/tambah', [MutationController::class, 'create'])
        ->name('mutations.create');

    Route::get('/mutasi/ruangan/{room}/barang', [MutationController::class, 'getInventories'])
        ->name('mutations.inventories');

    Route::post('/mutasi', [MutationController::class, 'store'])
        ->name('mutations.store');

    Route::post('/mutasi/{mutation}/setujui', [MutationController::class, 'approve'])
        ->name('mutations.approve');

    Route::post('/mutasi/{mutation}/tolak', [MutationController::class, 'reject'])
        ->name('mutations.reject');

    // ========================================
    // LAPORAN INVENTARIS
    // ========================================

    Route::get('/laporan', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/laporan/seluruh-ruangan', [ReportController::class, 'all'])
        ->name('reports.all');

    Route::get('/laporan/ruangan/{room}', [ReportController::class, 'room'])
        ->name('reports.room');
});

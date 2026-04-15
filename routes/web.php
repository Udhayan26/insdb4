<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\CitationController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\MapController;

// Public routes
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/map', [MapController::class, 'index'])->name('map.index');

// Admin routes (protected)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData']);
    
    // Series management
    Route::resource('series', SeriesController::class);
    
    // Books management
    Route::get('/books/create', [BookController::class, 'create'])->name('admin.books.create');
    Route::post('/books', [BookController::class, 'store'])->name('admin.books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('admin.books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('admin.books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');
    Route::post('/books/import', [BookController::class, 'import'])->name('admin.books.import');
    Route::get('/books/export', [BookController::class, 'export'])->name('admin.books.export');
    Route::post('/books/{book}/publish', [BookController::class, 'publish'])->name('admin.books.publish');
    Route::post('/books/bulk-publish', [BookController::class, 'bulkPublish'])->name('admin.books.bulk-publish');
    
    // Inscriptions management
    Route::resource('inscriptions', InscriptionController::class);
    Route::post('/inscriptions/import', [InscriptionController::class, 'import'])->name('admin.inscriptions.import');
    
    // Citations management
    Route::resource('citations', CitationController::class);
    
    // Locations management
    Route::resource('locations', LocationController::class);
    
    // Reports & Analytics
    Route::get('/reports/statistics', [ReportController::class, 'statistics'])->name('admin.reports.statistics');
    Route::get('/reports/citation-network', [ReportController::class, 'citationNetwork'])->name('admin.reports.citations');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('admin.reports.export');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/inscriptions/search', [InscriptionController::class, 'search']);
    Route::get('/locations/geojson', [MapController::class, 'geojson']);
    Route::get('/statistics/dashboard', [DashboardController::class, 'getStats']);
});

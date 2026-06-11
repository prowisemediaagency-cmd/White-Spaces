<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\SectorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('events', EventController::class)->except(['show']);

Route::get('/sectors', [SectorController::class, 'index'])->name('sectors.index');
Route::post('/sectors', [SectorController::class, 'store'])->name('sectors.store');
Route::delete('/sectors/{sector}', [SectorController::class, 'destroy'])->name('sectors.destroy');

Route::get('/matrix', [MatrixController::class, 'index'])->name('matrix.index');
Route::post('/matrix/assessments', [MatrixController::class, 'saveAssessments'])->name('matrix.assessments');
Route::post('/matrix/weights', [MatrixController::class, 'saveWeights'])->name('matrix.weights');

Route::post('/analysis/run', [AnalysisController::class, 'run'])->name('analysis.run');

Route::get('/opportunities', [OpportunityController::class, 'index'])->name('opportunities.index');

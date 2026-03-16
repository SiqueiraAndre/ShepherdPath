<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresencaController;

Route::get('/', [PresencaController::class, 'index'])->name('presenca.index');
Route::post('/presenca', [PresencaController::class, 'store'])->name('presenca.store');

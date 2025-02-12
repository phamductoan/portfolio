<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogInOutMiniAppController;
use App\Http\Controllers\VocabularyController;

// Routes đăng nhập, đăng ký, đăng xuất
Route::get('/login', [LogInOutMiniAppController::class, 'showForm'])->name('login.form');
Route::post('/login', [LogInOutMiniAppController::class, 'login'])->name('login');
Route::post('/register', [LogInOutMiniAppController::class, 'register'])->name('register');
Route::post('/logout', [LogInOutMiniAppController::class, 'logout'])->name('logout');

// Các routes khác được bảo vệ bởi middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/', [VocabularyController::class, 'showForm'])->name('home');
    Route::post('vocabulary/add', [VocabularyController::class, 'addVocabulary'])->name('vocabulary.add');
    Route::get('vocabulary/index', [VocabularyController::class, 'index'])->name('vocabulary.index');
    Route::get('/api/units', [VocabularyController::class, 'getUnits']);
    Route::get('/reading', [VocabularyController::class,'reading'])->name('reading');
    Route::get('vocabulary/{id}', [VocabularyController::class, 'show'])->name('vocabulary.show');
    Route::get('vocabulary/{id}/edit', [VocabularyController::class, 'edit'])->name('vocabulary.edit');
    Route::put('vocabulary/{id}', [VocabularyController::class, 'update'])->name('vocabulary.update');
    Route::delete('vocabulary/{id}', [VocabularyController::class, 'destroy'])->name('vocabulary.destroy');
    Route::get('/vocabulary', [VocabularyController::class, 'index'])->name('vocabulary.index');
    Route::get('/vocabularies/export', [VocabularyController::class, 'export'])->name('vocabularies.export');
});

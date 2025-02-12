<?php

use App\Http\Controllers\VocabularyController;

// Route::middleware('api.token')->group(['namespace' => 'App\Http\Controllers'], function()
// {
//     Route::get('/', [VocabularyController::class, 'showForm'])->name('home');
//     Route::post('vocabulary/add', [VocabularyController::class, 'addVocabulary'])->name('vocabulary.add');
//     Route::get('vocabulary/index', [VocabularyController::class, 'index'])->name('vocabulary.index');
//     Route::get('/api/units', [VocabularyController::class, 'getUnits']);
//     Route::get('/reading', [VocabularyController::class,'reading'])->name('reading');
//     Route::get('vocabulary/{id}', [VocabularyController::class, 'show'])->name('vocabulary.show');
//     Route::get('vocabulary/{id}/edit', [VocabularyController::class, 'edit'])->name('vocabulary.edit');
//     Route::put('vocabulary/{id}', [VocabularyController::class, 'update'])->name('vocabulary.update');
//     Route::delete('vocabulary/{id}', [VocabularyController::class, 'destroy'])->name('vocabulary.destroy');
//     Route::get('/vocabulary', [VocabularyController::class, 'index'])->name('vocabulary.index');
//     Route::get('/vocabularies/export', [VocabularyController::class, 'export'])->name('vocabularies.export');
// });


Route::group(['namespace' => 'App\Http\Controllers'], function()
{
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

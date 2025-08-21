<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', [ContactController::class, 'index'])->name('contacts.index');

Route::post('/contacts/import', [ContactController::class, 'import'])->name('contacts.import');
Route::get('/contacts/list', [ContactController::class, 'list'])->name('contacts.list');

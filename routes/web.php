<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('jwt.auth')->group(function () {
    Route::get('/api/notes', [NoteController::class, 'index']);
    Route::get('/api/notes/{id}', [NoteController::class, 'show']);
    Route::post('/api/notes/create', [NoteController::class,'create']);
    Route::post('/api/notes/update/{id}', [NoteController::class,'update']);
    Route::post('/api/notes/delete/{id}', [NoteController::class,'destroy']);
});


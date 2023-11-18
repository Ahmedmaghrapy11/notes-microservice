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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('jwt.auth')->group(function () {
    Route::get('/notes', [NoteController::class, 'index']);
    Route::get('/notes/{id}', [NoteController::class, 'show']);
    Route::post('/notes/create', [NoteController::class,'create']);
    Route::post('/notes/update/{id}', [NoteController::class,'update']);
    Route::post('/notes/delete/{id}', [NoteController::class,'destroy']);
});


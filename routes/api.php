<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// -------------------------------------------------- //

// Route::get('/notes', [NoteController::class, 'index']);
// Route::get('/notes/{id}', [NoteController::class, 'show']);
// Route::post('/notes/create', [NoteController::class,'create']);
// Route::post('/notes/update/{id}', [NoteController::class,'update']);
// Route::post('/notes/delete/{id}', [NoteController::class,'destroy']);

// -------------------------------------------------- //

Route::middleware('auth:api')->group(function () {

});

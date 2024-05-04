<?php
use App\Http\Controllers\TaskController;

use Illuminate\Support\Facades\Route;

Route::get('',[TaskController::class, 'index']);
Route::post('save',[TaskController::class, 'savedata']);
Route::get('edit/{id}',[TaskController::class, 'editdata']);
Route::get('delete/{id}',[TaskController::class, 'delete']);
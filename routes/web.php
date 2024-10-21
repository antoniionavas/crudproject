<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('usuarios', UserController::class)->names('usuarios')->parameters(['usuarios' => 'user']);

    Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/proyectos/list', [ProjectController::class, 'getProjects'])->name('projects.list');
    Route::get('/proyectos/create', [ProjectController::class, 'createProjects'])->name('projects.create');
    Route::get('/tasks', [ProjectController::class, 'load'])->name('tasks.index');
    Route::post('/tasks/crear', [ProjectController::class, 'storeTask'])->name('tasks.store');

});

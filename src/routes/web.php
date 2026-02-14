<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Project routes
    Route::resource('projects', ProjectController::class);
    Route::post(
        'projects/{project}/members',
        [ProjectController::class, 'addMember']
    )
        ->name('projects.members.add');

    Route::delete(
        'projects/{project}/members/{user}',
        [ProjectController::class, 'removeMember']
    )
        ->name('projects.members.remove');

    Route::get(
        '/projects/{project}/files/{file}/download',
        [ProjectFileController::class, 'download']
    )->name('projects.files.download');

    Route::post('/projects/{project}/files', [ProjectFileController::class, 'store'])
        ->name('projects.files.store');

    Route::delete('/projects/{project}/files/{file}', [ProjectFileController::class, 'destroy'])
        ->name('projects.files.destroy');


    // Task routes
    Route::resource('tasks', TaskController::class);
});

require __DIR__ . '/auth.php';

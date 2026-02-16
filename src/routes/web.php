<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Project routes
    Route::resource('projects', ProjectController::class);

    // Project members routes
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.store');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.members.destroy');

    // Project files routes
    Route::post('/projects/{project}/files', [ProjectFileController::class, 'store'])->name('projects.files.store');
    Route::get('/projects/{project}/files/{file}/download', [ProjectFileController::class, 'download'])->name('projects.files.download');
    Route::delete('/projects/{project}/files/{file}', [ProjectFileController::class, 'destroy'])->name('projects.files.destroy');


    // Task routes
    Route::resource('projects.tasks', TaskController::class);

    //  Task toggle status route
    Route::patch('/projects/{project}/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('projects.tasks.toggle');

    // Task files routes
    Route::get('/projects/{project}/tasks/{task}/files/{file}/download', [TaskFileController::class, 'download'])->name('projects.tasks.files.download');
    Route::post('/projects/{project}/tasks/{task}/files', [TaskFileController::class, 'store'])->name('projects.tasks.files.store');
    Route::delete('/projects/{project}/tasks/{task}/files/{file}', [TaskFileController::class, 'destroy'])->name('projects.tasks.files.destroy');
});

require __DIR__ . '/auth.php';

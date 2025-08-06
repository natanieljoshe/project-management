<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Http\Controllers\SocialiteController;

Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('google.callback');

Route::view('/', 'welcome');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('projects', \App\Livewire\Projects\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('projects');

Route::get('projects/{project}', \App\Livewire\Projects\Show::class)
    ->middleware(['auth', 'verified'])
    ->name('projects.show');

require __DIR__.'/auth.php';

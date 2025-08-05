<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;

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

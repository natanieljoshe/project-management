<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('projects', \App\Livewire\Projects\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('projects');

require __DIR__.'/auth.php';

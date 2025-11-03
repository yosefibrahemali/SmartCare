<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/reminder/{reminder}/confirm', [ReminderController::class, 'confirm'])->name('reminder.confirm');
// Route::get('/reminder/{reminder}/delay', [ReminderController::class, 'delay'])->name('reminder.delay');
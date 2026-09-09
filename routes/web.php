<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\UserManagement;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/usuarios', UserManagement::class)->name('admin.usuarios');
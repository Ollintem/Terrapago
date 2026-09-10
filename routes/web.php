<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\UserPermissions;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/usuarios', UserManagement::class)->name('admin.usuarios');
Route::get('/admin/usuarios/{user}/permisos', UserPermissions::class)->name('admin.permisos');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

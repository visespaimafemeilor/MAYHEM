<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/explore', [DashboardController::class, 'explore'])->name('explore');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/create', [PostController::class, 'create']);
Route::get('/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::post('/edit', [PostController::class, 'edit']);
Route::get('/delete', [PostController::class, 'delete'])->name('posts.delete');

Route::post('/reblog', [PostController::class, 'reblog'])->name('posts.reblog');

Route::get('/profile/{username}', [ProfileController::class, 'index'])->name('profile');
Route::post('/follow', [ProfileController::class, 'follow'])->name('follow');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/settings', [SettingsController::class, 'index']);
Route::post('/settings/avatar', [SettingsController::class, 'updateAvatar'])->name('settings.avatar');
Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');

Route::post('/like', [PostController::class, 'like'])->name('like');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::get('/notifications/count', [NotificationController::class, 'unreadCount'])->name('notifications.count');

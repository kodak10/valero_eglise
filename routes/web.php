<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembresController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;










Route::get('/', function () {
    return view('pages.login');
});

Route::resource('evenements', EvenementController::class);
Route::get('/presence/scan/{id}', function ($id) {
    return "Scan reçu pour l'événement $id"; // à adapter
})->name('presence.scan');

Route::get('evenements/{id}/download-qr-code', [EvenementController::class, 'downloadQrCode'])->name('evenements.downloadQrCode');


Route::get('/participation/{slug}', [ParticipationController::class, 'show'])->name('participation.form');
Route::post('/participation/{evenement}', [ParticipationController::class, 'submit'])->name('participation.submit');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs.index');
Route::post('/utilisateurs', [UserController::class, 'store'])->name('utilisateurs.store');
Route::get('/utilisateurs/{id}/toggle-status', [UserController::class, 'updateStatus'])->name('utilisateurs.toggleStatus');

Route::get('/profil', [UserController::class, 'profil'])->name('profil.edit');
Route::post('/profil', [UserController::class, 'updateProfile'])->name('profil.update');

Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');

Route::get('/participants', [ParticipantsController::class, 'index'])->name('participants.index');
Route::post('/participants/{evenement}', [ParticipantsController::class, 'store'])->name('participants.store');

Route::get('/membres', [MembresController::class, 'index'])->name('membres.index');


Route::get('/users', [ParticipantsController::class, 'index'])->name('users.index');
Route::post('/users', [ParticipantsController::class, 'update'])->name('users.update');


Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

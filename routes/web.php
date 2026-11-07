<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPropertyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\PropertyVisitController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:admin,agent,client'])->group(function () {
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.read-all');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

    Route::middleware(['role:admin,agent'])->group(function () {
        Route::resource('dashboard/properties', PropertyController::class)->names('dashboard.properties');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('dashboard/property-types', PropertyTypeController::class);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('/dashboard/users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update'])->names('users');
        Route::patch('/dashboard/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('dashboard/clients', ClientController::class);
    });

    Route::middleware(['role:client'])->group(function () {
        Route::get('/client/properties', [ClientPropertyController::class, 'index'])->name('client.properties.index');
        Route::get('/client/properties/{property}', [ClientPropertyController::class, 'show'])->name('client.properties.show');
        Route::post('/client/properties/{property}/interest', [ClientPropertyController::class, 'interest'])->name('client.properties.interest');
        Route::delete('/client/properties/{property}/interest', [ClientPropertyController::class, 'uninterest'])->name('client.properties.uninterest');
        Route::get('/client/interests', [ClientPropertyController::class, 'interests'])->name('client.interests');
        Route::get('/client/visits', [PropertyVisitController::class, 'clientIndex'])->name('client.visits.index');
        Route::post('/client/properties/{property}/visits', [PropertyVisitController::class, 'store'])->name('client.visits.store');
        Route::patch('/client/visits/{visit}/cancel', [PropertyVisitController::class, 'cancel'])->name('client.visits.cancel');
        Route::patch('/client/visits/{visit}/proposal/accept', [PropertyVisitController::class, 'acceptProposal'])->name('client.visits.proposal.accept');
        Route::patch('/client/visits/{visit}/proposal/decline', [PropertyVisitController::class, 'declineProposal'])->name('client.visits.proposal.decline');
    });

    Route::middleware(['role:admin,agent'])->group(function () {
        Route::get('/dashboard/visits', [PropertyVisitController::class, 'agenda'])->name('dashboard.visits.index');
        Route::patch('/dashboard/visits/{visit}', [PropertyVisitController::class, 'update'])->name('dashboard.visits.update');
    });

    Route::middleware(['role:admin,agent'])->group(function () {
        Route::get('/dashboard/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/dashboard/reports/properties/pdf', [ReportController::class, 'propertiesPdf'])->name('reports.properties.pdf');
    });
});

Route::get('/properties', [PublicController::class, 'properties'])->name('public.properties.index');
Route::get('/properties/{property}', [PublicController::class, 'show'])->name('public.properties.show');

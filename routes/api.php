<?php

use App\Http\Controllers\Api\AbonnementController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CoachController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CoursController;
use App\Http\Controllers\Api\SalleController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\StripeController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ── Public ────────────────────────────────────────────
Route::get('/salles',        [SalleController::class, 'index']);
Route::get('/salles/{slug}', [SalleController::class, 'show']);
Route::get('/villes',        [SalleController::class, 'villes']);

Route::get('/cours',         [CoursController::class, 'index']);
Route::get('/cours/{slug}',  [CoursController::class, 'show']);
Route::get('/planning',      [CoursController::class, 'planning']);

Route::get('/abonnements',   [AbonnementController::class, 'index']);

Route::get('/coaches',       [CoachController::class, 'index']);
Route::get('/coaches/{id}',  [CoachController::class, 'show']);

Route::post('/contact',      [ContactController::class, 'store']);

// ── Stripe webhook (pas d'auth — Stripe appelle directement) ──
Route::post('/stripe/webhook', [StripeController::class, 'webhook']);

// ── Protected (auth:sanctum) ──────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',               [AuthController::class, 'logout']);
    Route::get('/me',                    [AuthController::class, 'me']);
    Route::post('/abonnements/souscrire',[AbonnementController::class, 'souscrire']);
    Route::get('/mon-abonnement',        [AbonnementController::class, 'monAbonnement']);

    // ── Stripe Checkout ──
    Route::post('/stripe/checkout',  [StripeController::class, 'createCheckout']);
    Route::get('/stripe/confirm',    [StripeController::class, 'confirm']);
    Route::get('/mes-seances',           [CoachController::class, 'mesSeances']);

    // ── Réservations ──
    Route::post('/reservations',              [ReservationController::class, 'reserver']);
    Route::delete('/reservations/{coursId}',  [ReservationController::class, 'annuler']);
    Route::get('/mes-reservations',           [ReservationController::class, 'mesReservations']);

    // ── Profil ──
    Route::put('/profil', [AuthController::class, 'updateProfil']);

    // ── Admin (role=admin) ────────────────────────────
    Route::middleware('App\Http\Middleware\AdminMiddleware')->prefix('admin')->group(function () {

        // Stats
        Route::get('/stats', [AdminController::class, 'stats']);

        // Utilisateurs
        Route::get('/users',          [AdminController::class, 'users']);
        Route::get('/users/{id}',     [AdminController::class, 'showUser']);
        Route::put('/users/{id}',     [AdminController::class, 'updateUser']);
        Route::delete('/users/{id}',  [AdminController::class, 'deleteUser']);

        // Coaches
        Route::get('/coaches',          [AdminController::class, 'coaches']);
        Route::post('/coaches',         [AdminController::class, 'createCoach']);
        Route::put('/coaches/{id}',     [AdminController::class, 'updateCoach']);
        Route::delete('/coaches/{id}',  [AdminController::class, 'deleteCoach']);

        // Cours
        Route::get('/cours',            [AdminController::class, 'listCours']);
        Route::post('/cours',           [AdminController::class, 'createCours']);
        Route::put('/cours/{id}',       [AdminController::class, 'updateCours']);
        Route::delete('/cours/{id}',    [AdminController::class, 'deleteCours']);

        // Salles
        Route::get('/salles',           [AdminController::class, 'listSalles']);
        Route::post('/salles',          [AdminController::class, 'createSalle']);
        Route::put('/salles/{id}',      [AdminController::class, 'updateSalle']);
        Route::delete('/salles/{id}',   [AdminController::class, 'deleteSalle']);

        // Abonnements
        Route::get('/abonnements',          [AdminController::class, 'listAbonnements']);
        Route::post('/abonnements',         [AdminController::class, 'createAbonnement']);
        Route::put('/abonnements/{id}',     [AdminController::class, 'updateAbonnement']);
        Route::delete('/abonnements/{id}',  [AdminController::class, 'deleteAbonnement']);

        // Inscriptions
        Route::get('/inscriptions',         [AdminController::class, 'inscriptions']);

        // Contacts
        Route::get('/contacts',             [AdminController::class, 'contacts']);
        Route::put('/contacts/{id}',        [AdminController::class, 'updateContact']);
        Route::delete('/contacts/{id}',     [AdminController::class, 'deleteContact']);
    });
});

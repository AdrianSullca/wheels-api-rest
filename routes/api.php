<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // ANNOUNCEMENTS
    Route::get('/announcements', [AnnouncementController::class, 'getAllAnnouncements'])->name('announcements.all');
    Route::post('/announcements/create', [AnnouncementController::class, 'createAnnouncement'])->name('announcements.create');
    Route::get('/announcements/{id}', [AnnouncementController::class, 'getAnnouncement'])->name('announcements.get');
    Route::patch('/announcements/{id}', [AnnouncementController::class, 'updateAnnouncement'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'deleteAnnouncement'])->name('announcements.delete');

    // PROFILE
    Route::get('/profile/{id}/stats', [ProfileController::class, 'getProfileStats'])->name('profile.stats');
    Route::get('/profile/{id}/announcements', [ProfileController::class, 'getUserAnnouncements'])->name('profile.announcements');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/{id}/reviews', [ProfileController::class, 'getUserReviews'])->name('profile.reviews');

    // FAVORITES
    Route::get('/favorites', [FavoriteController::class, 'getAllFavorites'])->name('favorites.all');
    Route::post('/favorites/{id}', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');

    // TRANSACTIONS
    Route::get('/transactions', [TransactionController::class, 'getAllTransactions'])->name('transactions.all');
    Route::post('/transactions/{id}/create', [TransactionController::class, 'createTransaction'])->name('transactions.create');
    Route::get('/transactions/{id}', [TransactionController::class, 'getTransaction'])->name('transactions.get');

    // REVIEWS
    Route::post('/reviews/{id}/create', [ReviewController::class, 'createReview'])->name('reviews.create');
    Route::patch('/reviews/{id}', [ReviewController::class, 'updateReview'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'deleteReview'])->name('reviews.delete');

    // MESSAGES


});

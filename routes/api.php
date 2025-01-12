<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // USERS
    Route::get('/user', [UserController::class, 'getUserByToken'])->name('user.get');
    Route::match(['post', 'patch'], '/user/updateGeneralInformation', [UserController::class, 'updateGeneralInformation'])->name('user.updateGeneralInformation');
    Route::patch('/user/updateSecurity', [UserController::class, 'updateSecurity'])->name('user.updateSecurity');

    // ANNOUNCEMENTS
    Route::get('/announcements', [AnnouncementController::class, 'getAllAnnouncements'])->name('announcements.all');
    Route::post('/announcements/create', [AnnouncementController::class, 'createAnnouncement'])->name('announcements.create');
    Route::get('/announcements/{id}', [AnnouncementController::class, 'getAnnouncement'])->name('announcements.get');
    Route::match(['post', 'patch'], '/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'deleteAnnouncement'])->name('announcements.delete');

    // PROFILE
    Route::get('/profile/{id}/stats', [ProfileController::class, 'getProfileStats'])->name('profile.stats');
    Route::get('/profile/{id}/announcements', [ProfileController::class, 'getUserAnnouncements'])->name('profile.announcements');
    Route::get('/profile/{id}/reviews', [ProfileController::class, 'getUserReviews'])->name('profile.reviews');

    // FAVORITES
    Route::get('/favorites', [FavoriteController::class, 'getAllFavorites'])->name('favorites.all');
    Route::post('/favorites/{announcement_id}/toggle', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');

    // TRANSACTIONS
    Route::get('/transactions', [TransactionController::class, 'getAllTransactions'])->name('transactions.all');
    Route::post('/transactions/{id}/create', [TransactionController::class, 'createTransaction'])->name('transactions.create');
    Route::get('/transactions/{id}', [TransactionController::class, 'getTransaction'])->name('transactions.get');

    // REVIEWS
    Route::post('/reviews/{id}/create', [ReviewController::class, 'createReview'])->name('reviews.create');
    Route::patch('/reviews/{id}', [ReviewController::class, 'updateReview'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'deleteReview'])->name('reviews.delete');

    // ADMIN
    Route::get('/admin/users', [AdminController::class, 'getAllUsers'])->name('users.all');
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
    Route::patch('/admin/user/{id}', [AdminController::class, 'updateUser'])->name('user.update');
    Route::post('/admin/user/create', [AdminController::class, 'createUser'])->name('user.create');
    Route::get('/admin/reviews', [AdminController::class, 'getAllReviews'])->name('reviews.all');
    Route::delete('/admin/review/{id}', [AdminController::class, 'deleteReview'])->name('review.delete');
});

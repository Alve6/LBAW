<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteNotificationController;
use App\Http\Controllers\CommentNotificationController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\FollowController;
use App\Http\Controllers\Admin\CategoryManagementController;

use App\Http\Controllers\CategoryFollowController;

// Home
Route::get('/', [NewsController::class, 'homepage'])->name('homepage');

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
});

Route::controller(LogoutController::class)->group(function () {
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(NewsController::class)->group(function () {
    Route::get('/news', 'showAllNews')->name('allNews');
    Route::get('/news/create', 'create')->name('news.create')->middleware('auth');
    Route::post('/news/create', 'store')->name('news.store')->middleware('auth');
    Route::get('/news/edit/{news}', 'edit')->name('news.edit')->middleware('auth');
    Route::patch('/news/edit/{news}', 'update')->name('news.update')->middleware('auth');
    Route::get('/news/delete/{news}', 'delete')->name('news.delete')->middleware('auth');
    Route::delete('/news/delete/{news}', 'destroy')->name('news.destroy')->middleware('auth');
    Route::get('/news/{news}', 'show')->name('news.show');
});

// Comment route
Route::post('/news/{news}/comment', [NewsController::class, 'storeComment'])->name('news.comment')->middleware('auth');
Route::patch('/comment/{comment}/edit', [NewsController::class, 'editComment'])->name('comment.edit')->middleware('auth');
Route::delete('/comment/{comment}/delete', [NewsController::class, 'deleteComment'])->name('comment.delete')->middleware('auth');

// Vote routes
Route::post('/news/{news}/vote', [NewsController::class, 'toggleVote'])->name('news.vote')->middleware('auth');
Route::post('/comment/{comment}/vote', [NewsController::class, 'toggleCommentVote'])->name('comment.vote')->middleware('auth');

// Moderator routes
Route::get('/user/{user}/timeout', [UserController::class, 'showTimeoutForm'])->name('user.timeout');
Route::post('/news/{news}/checkmark', [NewsController::class, 'toggleCheckmark'])->name('news.checkmark')->middleware('auth');
Route::post('/user/{user}/timeout', [UserController::class, 'applyTimeout'])->name('user.applytimeout')->middleware('auth');

Route::controller(UserController::class)->group(function () {
    Route::get('/user/{user}', 'show')->name('user.show');
    Route::get('/user/edit/{user}', 'edit')->name('user.edit')->middleware('auth');
    Route::get('/user/delete/{user}', 'deleteConfirmation')->name('user.delete')->middleware('auth');
    Route::patch('/user/edit/{user}', 'update')->name('user.update')->middleware('auth');
    Route::delete('/user/delete/{user}', 'destroy')->name('user.destroy')->middleware('auth');
    Route::get('/recovery', 'showRecoveryForm')->name('showRecoveryForm');
    Route::get('/recovery/form', 'recoverPasswordForm')->name('recoverPasswordForm');
    Route::post('/recovery/form', 'recoverPassword')->name('recoverPassword');
    Route::get('/user/promoteToModerator/{user}', 'showPromoteToModeratorForm')->name('user.showPromoteToModerator')->middleware('auth');
    Route::post('/user/promoteToModerator/{user}', 'promoteToModerator')->name('user.promoteToModerator')->middleware('auth');
    Route::get('/user/promoteToAdmin/{user}', 'showPromoteToAdminForm')->name('user.showPromoteToAdmin')->middleware('auth');
    Route::post('/user/promoteToAdmin/{user}', 'promoteToAdmin')->name('user.promoteToAdmin')->middleware('auth');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/search/category/{category}', 'getNews')->name('search.category');
});

Route::controller(NotificationController::class)->group(function () {
    Route::get('/notifications/{user}', 'index')->name('notifications.index')->middleware('auth');
    Route::post('/notifications/{notification}/markseen', 'markSeen')->name('notifications.markSeen')->middleware('auth');
    Route::delete('/notifications/{notification}/delete', 'destroy')->name('notifications.destroy')->middleware('auth');
});

Route::post('/votenotification/store', [VoteNotificationController::class, 'store'])->name('votenotification.store')->middleware('auth');
Route::post('/commentnotification/store', [CommentNotificationController::class, 'store'])->name('commentnotification.store')->middleware('auth'); 

Route::post('/send', [MailController::class, 'send'])->name('send');

Route::view('/aboutus', 'pages.static_pages.aboutus')->name('aboutus');
Route::view('/contacts', 'pages.static_pages.contacts')->name('contacts');
Route::view('/features', 'pages.static_pages.mainfeatures')->name('features');

// Follow/Unfollow
Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])
        ->name('user.follow');

    Route::delete('/users/{user}/follow', [FollowController::class, 'unfollow'])
        ->name('user.unfollow');
});
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/tags', [CategoryManagementController::class, 'index'])->name('admin.tags.index');
    Route::post('/tags', [CategoryManagementController::class, 'store'])->name('admin.tags.store');
    Route::put('/tags/{category}', [CategoryManagementController::class, 'update'])->name('admin.tags.update');
    Route::delete('/tags/{category}', [CategoryManagementController::class, 'destroy'])->name('admin.tags.destroy');
    Route::put('/tags/{id}/approve', [CategoryManagementController::class, 'approve'])->name('admin.tags.approve');
});

Route::controller(ReportController::class)->group(function () {
    Route::get('/reports', 'index')->name('reports.index')->middleware('auth');
    Route::get('/report/create', 'create')->name('report.create')->middleware('auth');
    Route::post('/report/create', 'store')->name('report.store')->middleware('auth');
    Route::get('/report/{report}', 'show')->name('report.show')->middleware('auth');
    Route::post('/report/acknowledge/{report}', 'markAckownledged')->name('report.acknowledge')->middleware('auth');
});

// Category Follow/Unfollow
Route::middleware('auth')->group(function () {
    Route::post('/categories/{category}/follow', [CategoryFollowController::class, 'follow'])
        ->name('category.follow');

    // Unfollow
    Route::delete('/categories/{category}/follow', [CategoryFollowController::class, 'unfollow'])
        ->name('category.unfollow');
});

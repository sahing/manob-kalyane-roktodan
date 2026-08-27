<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\PledgeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [DonorController::class, 'search'])->name('donors.search');
Route::get('/donor/{id}', [DonorController::class, 'show'])->name('donors.show');
Route::post('/inquiry', [DonorController::class, 'submitInquiry'])->name('inquiry.submit');

Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');

Route::get('/donate', [PledgeController::class, 'index'])->name('donate');
Route::post('/donate', [PledgeController::class, 'store'])->name('donate.store');

Route::get('/members', [MemberController::class, 'index'])->name('members');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Donor Stories & Interactions
Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
Route::get('/stories/{id}', [StoryController::class, 'show'])->name('stories.show');
Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
Route::post('/stories/{id}/like', [StoryController::class, 'toggleLike'])->name('stories.like');
Route::post('/stories/{id}/share', [StoryController::class, 'trackShare'])->name('stories.share');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Public Verification Portal Routes
Route::get('/verify', [VerificationController::class, 'index'])->name('verify.index');
Route::get('/verify/{code}', [VerificationController::class, 'verify'])->name('verify.show');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::post('/requests/{id}/status', [RequestController::class, 'updateStatus'])->name('requests.update-status');

    // Story Comments
    Route::post('/stories/{id}/comments', [StoryController::class, 'storeComment'])->name('stories.comments.store');
    Route::put('/stories/comments/{commentId}', [StoryController::class, 'updateComment'])->name('stories.comments.update');
    Route::delete('/stories/comments/{commentId}', [StoryController::class, 'deleteComment'])->name('stories.comments.delete');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::get('/dashboard/card', [DashboardController::class, 'card'])->name('dashboard.card');
    Route::get('/dashboard/certificate/{id}', [DashboardController::class, 'showCertificate'])->name('dashboard.certificate');
});

// Admin Routes
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/inquiries/live', [AdminController::class, 'getLiveInquiries'])->name('admin.inquiries.live');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
    Route::post('/donations/record', [AdminController::class, 'recordDonation'])->name('admin.donations.record');
    Route::post('/slides', [AdminController::class, 'storeSlide'])->name('admin.slides.store');
    Route::delete('/slides/{id}', [AdminController::class, 'deleteSlide'])->name('admin.slides.delete');
    Route::post('/gallery', [AdminController::class, 'storeGallery'])->name('admin.gallery.store');
    Route::delete('/gallery/{id}', [AdminController::class, 'deleteGallery'])->name('admin.gallery.delete');
    Route::delete('/stories/{id}', [AdminController::class, 'deleteStory'])->name('admin.stories.delete');
    
    // Blog & SEO & Analytics Management
    Route::post('/blog', [AdminController::class, 'storeBlogPost'])->name('admin.blog.store');
    Route::delete('/blog/{id}', [AdminController::class, 'deleteBlogPost'])->name('admin.blog.delete');
    Route::post('/seo/{id}', [AdminController::class, 'updateSeoSetting'])->name('admin.seo.update');
    
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});

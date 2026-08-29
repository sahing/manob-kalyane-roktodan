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
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\ChatController;
use App\Http\Middleware\AdminMiddleware;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/analytics/log-click', [AnalyticsController::class, 'logClick'])->name('analytics.log-click');

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
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('/support/account-issue', [AuthController::class, 'submitAccountSupport'])->name('support.account-issue');
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

    // Gallery Upload
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::get('/dashboard/card', [DashboardController::class, 'card'])->name('dashboard.card');
    Route::get('/dashboard/certificate/{id}', [DashboardController::class, 'showCertificate'])->name('dashboard.certificate');
    Route::post('/dashboard/notifications/{id}/read', [DashboardController::class, 'markNotificationAsRead'])->name('dashboard.notifications.read');
});

// Admin Routes
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/inquiries/live', [AdminController::class, 'getLiveInquiries'])->name('admin.inquiries.live');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/reminders/bulk', [AdminController::class, 'sendBulkReminder'])->name('admin.reminders.bulk');
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
    
    // Financial Pledges Management
    Route::post('/pledges/{id}/status', [AdminController::class, 'updatePledgeStatus'])->name('admin.pledges.status');
    Route::post('/pledges/record', [AdminController::class, 'recordPledge'])->name('admin.pledges.record');

    // Role-Based Access Control (RBAC) Management
    Route::post('/roles', [AdminController::class, 'storeRole'])->name('admin.roles.store');
    Route::delete('/roles/{id}', [AdminController::class, 'deleteRole'])->name('admin.roles.delete');
    Route::post('/users/{id}/assign-role', [AdminController::class, 'assignUserRole'])->name('admin.users.assign-role');

    // CMS & Website Customization Suite Routes
    Route::post('/branding', [CmsController::class, 'updateBranding'])->name('admin.branding.update');
    Route::delete('/branding/logo/{type}', [CmsController::class, 'removeLogo'])->name('admin.branding.remove-logo');
    Route::post('/menu-items', [CmsController::class, 'storeMenuItem'])->name('admin.menu.store');
    Route::delete('/menu-items/{id}', [CmsController::class, 'deleteMenuItem'])->name('admin.menu.delete');
    Route::post('/homepage-sections', [CmsController::class, 'storeSection'])->name('admin.sections.store');
    Route::delete('/homepage-sections/{id}', [CmsController::class, 'deleteSection'])->name('admin.sections.delete');
    Route::post('/cms-pages', [CmsController::class, 'storePage'])->name('admin.pages.store');
    Route::delete('/cms-pages/{id}', [CmsController::class, 'deletePage'])->name('admin.pages.delete');
    Route::post('/media-assets', [CmsController::class, 'storeMedia'])->name('admin.media.store');
    Route::delete('/media-assets/{id}', [CmsController::class, 'deleteMedia'])->name('admin.media.delete');
    Route::post('/custom-code', [CmsController::class, 'updateCustomCode'])->name('admin.custom-code.update');
});

// Dynamic Public CMS Page Route
Route::get('/p/{slug}', [CmsController::class, 'showPage'])->name('pages.show');

// In-Portal Chat & Messaging Routes
Route::get('/api/chat/messages', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
Route::post('/api/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');


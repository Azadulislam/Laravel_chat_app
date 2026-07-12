<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MentionController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');
});

Route::middleware(['auth', \App\Http\Middleware\EnsureOnboardingCompleted::class])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/proxy', [ProjectController::class, 'proxy'])->name('projects.proxy');
    Route::get('/projects/{project}/asset-proxy', [ProjectController::class, 'assetProxy'])->name('projects.asset-proxy');
    Route::post('/projects/{project}/comments', [CommentController::class, 'store'])->name('projects.comments.store');
    
    // Chat routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/conversations/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/conversations/{conversation}/messages', [ChatController::class, 'storeMessage'])->name('messages.store');
        Route::post('/conversations/{conversation}/typing', [ChatController::class, 'typing'])->name('typing');
        Route::post('/conversations/{conversation}/read', [ChatController::class, 'markAsRead'])->name('read');
        Route::post('/conversations', [ChatController::class, 'startConversation'])->name('conversations.start');
        Route::get('/users/search', [ChatController::class, 'searchUsers'])->name('users.search');
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
        
        // Group routes
        Route::post('/groups', [ChatController::class, 'createGroup'])->name('groups.create');
        Route::post('/groups/{group}/members', [ChatController::class, 'addGroupMember'])->name('groups.members.add');
        Route::delete('/groups/{group}/members/{user}', [ChatController::class, 'removeGroupMember'])->name('groups.members.remove');
    });

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.destroy');
        Route::get('/projects', [App\Http\Controllers\AdminController::class, 'projects'])->name('projects');
        Route::get('/projects/{project}/edit', [App\Http\Controllers\AdminController::class, 'editProject'])->name('projects.edit');
        Route::put('/projects/{project}', [App\Http\Controllers\AdminController::class, 'updateProject'])->name('projects.update');
        Route::delete('/projects/{project}', [App\Http\Controllers\AdminController::class, 'deleteProject'])->name('projects.destroy');
    });

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::post('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
Route::post('/comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::get('/mentions', [MentionController::class, 'search'])->name('mentions.search');

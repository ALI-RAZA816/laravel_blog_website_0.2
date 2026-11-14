<?php

use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\PublicCategoryController;
use App\Http\Controllers\PublicCommentController;
use App\Http\Controllers\PublicPostController;
use App\Http\Controllers\PublicSettingController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;


/* ========== PUBLIC PAGES ========== */
Route::middleware('throttle:pages')->group(function () {
    Route::view('/', 'pages.home');
    Route::view('/about', 'pages.about');
    Route::view('/contact', 'pages.contact');
    Route::view('/login', 'pages.login');
    Route::view('/register', 'pages.register');
    Route::view('/single-view', 'pages.singleview');
    Route::view('/forgotton', 'pages.forgotton');
    Route::view('/verify', 'pages.verifyOTP');
    Route::view('/reset-password', 'pages.resetpassword');
});

/* ========== PUBLIC APIs ========== */
Route::middleware('throttle:public-api')->group(function () {
    Route::get('/public-setting', [PublicSettingController::class, 'publicSetting'])->name('public.setting');
    Route::get('/public-post', [PublicPostController::class, 'publicPosts'])->name('public.post');
    Route::get('/public-category', [PublicCategoryController::class, 'publicCategory'])->name('public.category');
    Route::get('/public-single-post/{id}', [PublicPostController::class, 'singleView'])->name('public.single.post');
    Route::get('/fetch-post-comments/{id}', [CommentController::class, 'fetchPostComments'])->name('fetchpost.comment');
});

Route::middleware('throttle:auth')->group(function () {
    Route::post('/registerUser', [AuthController::class, 'createAccount'])->name('register.submit');
    Route::post('/loginUser', [AuthController::class, 'loginAccount'])->name('login.submit');
    Route::post('/forgot-password', [ResetPasswordController::class, 'getResetLink'])->name('get.link');
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resetpassword', [ResetPasswordController::class, 'changePassword'])->name('reset.password');
    Route::get('/fetch-record', [ResetPasswordController::class, 'fetchRecord'])->name('fetch.record');
});

Route::middleware('throttle:forms')->group(function () {
    Route::post('/send-message', [MessageController::class, 'sendMessage'])->name('send.message');
    Route::post('/newsletter-subscribe', [NewsLetterController::class, 'Subscribed'])->name('newsletter.subscribe');
});

Route::middleware('throttle:pages')->group(function () {
    Route::get('/newsletter/confirm/{id}', [NewsLetterController::class, 'confirm'])
        ->middleware('signed')->name('newsletter.confirm');
    Route::get('/newsletter/unsubscribe/{id}', [NewsLetterController::class, 'unsubscribe'])
        ->middleware('signed')->name('newsletter.unsubscribe');
});

/* ========== LOGGED-IN (permissions are checked with can:...) ========== */
Route::middleware(['auth', 'throttle:panel'])->group(function () {

    // any logged-in role
    Route::post('/logoutUser', [AuthController::class, 'logoutAccount'])->name('logout.user');

    /* ---------- Users (admin) ---------- */
    Route::middleware('can:manage-users')->group(function () {
        Route::view('/users', 'pages.adminuser');
        Route::view('/add-user', 'pages.adminadduser');
        Route::view('/edit-user', 'pages.adminedituser');

        Route::post('/addUser', [UserController::class, 'addUser'])->name('add.user');
        Route::get('/fetchusers', [UserController::class, 'fetchUser'])->name('fetch.user');
        Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser'])->name('delete.user');
        Route::get('/fetchedituser/{id}', [UserController::class, 'fetchEditUser'])->name('fetchedit.user');
        Route::put('/edituser/{id}', [UserController::class, 'updateUser'])->name('edit.user');
        Route::put('/updateImage/{id}', [UserController::class, 'updatePhoto'])->name('photo.user');
    });

    /* ---------- Settings (admin) ---------- */
    Route::middleware('can:manage-settings')->group(function () {
        Route::view('/setting', 'pages.adminsetting');

        Route::post('/savesettings', [SettingController::class, 'updateSetting'])->name('save.settings');
        Route::get('/fetchsettings', [SettingController::class, 'fetchSetting'])->name('fetch.settings');
        Route::delete('/removelogo', [SettingController::class, 'removeLogo'])->name('remove.logo');
    });

    /* ---------- Messages (admin) ---------- */
    Route::middleware('can:manage-messages')->group(function () {
        Route::view('/messages', 'pages.adminmessage');

        Route::get('/fetch-messages', [AdminMessageController::class, 'fetchMessages'])->name('fetch.message');
        Route::get('/fetch-single-message/{id}', [AdminMessageController::class, 'fetchSingle'])->name('fetches.message');
        Route::put('/mark-as-read/{id}', [AdminMessageController::class, 'markAsRead'])->name('mark-as-read.message');
        Route::put('/mark-as-unread/{id}', [AdminMessageController::class, 'markAsUnread'])->name('mark-as-unread.message');
        Route::delete('/delete-message/{id}', [AdminMessageController::class, 'deleteMessage'])->name('delete.message');
        Route::post('/send-reply', [AdminMessageController::class, 'sendReply'])->name('send.reply');
    });

    /* ---------- Comment moderation (admin) ---------- */
    Route::middleware('can:moderate-comments')->group(function () {
        Route::put('/update-comment-status/{id}', [CommentController::class, 'updateComment'])->name('update.comment.status');
    });

    /* ---------- Categories (admin + editor) ---------- */
    Route::middleware('can:manage-categories')->group(function () {
        Route::view('/categories', 'pages.admincategory');

        Route::post('/addCategory', [CategoryController::class, 'addCategory'])->name('add.category');
        Route::get('/fetch-single-category/{id}', [CategoryController::class, 'fetchSingle'])->name('single.category');
        Route::put('/updateCategory/{id}', [CategoryController::class, 'updateCategory'])->name('edit.category');
        Route::delete('/deleteCategory/{id}', [CategoryController::class, 'deleteCategory'])->name('delete.category');
    });

    /* ---------- Dashboard + Posts (admin, editor, author) ---------- */
    Route::middleware('can:write-posts')->group(function () {
        Route::view('/dashboard', 'pages.dashboard');
        Route::view('/posts', 'pages.post');
        Route::view('/add-post', 'pages.addpost');
        Route::view('/post-preview', 'pages.post-preview');
        Route::view('/edit-post', 'pages.edit-post');

        Route::get('/dashboardData', [DashboardController::class, 'DashboardAnalysis'])->name('dashboard.data');

        // categories list for the post form (read only)
        Route::get('/fetchCategories', [CategoryController::class, 'fetchCategory'])->name('fetch.category');
        Route::get('/postcategories', [CategoryController::class, 'fetchCategory'])->name('postcat.category');

        Route::post('/addpost', [PostController::class, 'addPost'])->name('add.post');
        Route::get('/fetchpost', [PostController::class, 'fetchPosts'])->name('fetch.post');
        Route::get('/fetchSingle/{id}', [PostController::class, 'fetchSingle'])->name('fetchsingle.post');
        Route::put('/updatepost/{id}', [PostController::class, 'editPost'])->name('update.post');
        Route::delete('/deletepost/{id}', [PostController::class, 'deletePost'])->name('delete.post');
        Route::delete('/deleteposts', [PostController::class, 'multiDeletePost'])->name('delete.posts');
    });

    /* ---------- Comments (admin, editor, author, user) ---------- */
    Route::middleware('can:use-comments')->group(function () {
        Route::view('/comments', 'pages.admincomment');
        Route::get('/fetchComment', [CommentController::class, 'fetchComments'])->name('fetch.comment');

        Route::middleware('throttle:comments')->group(function () {
            Route::post('/add-comment', [PublicCommentController::class, 'addComment'])->name('add.comment');
            Route::put('/update-comment/{id}', [PublicCommentController::class, 'updateComment'])->name('update.comment');
            Route::delete('/delete-comment/{id}', [PublicCommentController::class, 'deleteComment'])->name('delete.comment');
        });
    });
});
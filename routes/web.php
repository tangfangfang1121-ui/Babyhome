<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UploadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 首页路由：使用 PostController 的 index 方法
Route::get('/', [PostController::class, 'index'])->name('home');

// 需要登录且验证邮箱后才能访问的路由
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 仪表盘 (Dashboard)
    Route::get('/dashboard', function () {
        // 获取当前登录用户发布的帖子
        $posts = Auth::user()->posts()->with('upload')->latest()->get();
        return view('dashboard', compact('posts'));
    })->name('dashboard');
    
    // 个人资料 (Breeze 自带)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 帖子管理的资源路由
    Route::resource('posts', PostController::class);
    
    // 评论路由
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// 【Lecture 07 核心】读取私有存储图片的路由
// 必须放在 auth 中间件外面，否则游客看不到图片
Route::get('/file/{upload}', [UploadController::class, 'show'])->name('file.show');

// ============================================================
// 关键点：这一行必须存在！它引入了 login, register 等路由
// ============================================================
require __DIR__.'/auth.php';

// 管理员路由组
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // 后台首页
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    
    // 删除用户路由
    Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    
    // 删除帖子路由 (我们直接复用 PostController 的 destroy 方法，因为我们在那里写了 || role === 'admin' 的判断)
    // 所以这里不需要额外写，直接在视图里调用 posts.destroy 即可
});

Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
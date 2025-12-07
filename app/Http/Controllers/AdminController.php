<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // 后台首页
    public function index()
    {
        // 获取所有用户
        $users = User::all();
        // 获取所有帖子
        $posts = Post::with('user')->latest()->get();

        return view('admin.dashboard', compact('users', 'posts'));
    }

    // 删除用户 (级联删除会自动删除该用户发布的所有帖子，因为我们在 migration 里写了 cascade)
    public function destroyUser(User $user)
    {
        // 防止删除自己
        if ($user->id === auth()->id()) {
            return back()->with('error', '你不能删除自己！');
        }

        $user->delete();
        return back()->with('success', '用户已封禁/删除');
    }
}
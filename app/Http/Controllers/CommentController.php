<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 注意这里：它继承自 (extends) 第一步里恢复的 Controller
class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // 1. 验证输入
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // 2. 创建评论
        $comment = new Comment();
        $comment->body = $validated['body'];
        $comment->user_id = Auth::id(); // 当前登录用户
        $comment->post_id = $post->id;  // 关联当前帖子
        $comment->save();

        // 3. 反馈
        session()->flash('success', '线索已提交，感谢您的帮助！');

        // 返回上一页
        return back();
    }
}
<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * 谁可以修改这篇帖子？
     * 逻辑：作者本人 OR 管理员
     */
    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }

    /**
     * 谁可以删除这篇帖子？
     * 逻辑：同上
     */
    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }
}
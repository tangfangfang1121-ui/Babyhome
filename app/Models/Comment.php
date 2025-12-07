<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'post_id'];

    // 关联：一个评论属于一个用户 (这就是报错缺失的部分)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 关联：一个评论属于一个帖子
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
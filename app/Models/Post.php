<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // 允许批量赋值的字段
    protected $fillable = ['title', 'description', 'location', 'status', 'user_id', 'type', 'age', 'dob', 'birth_place'];
    // 关联：一个帖子属于一个用户
    public function user() {
        return $this->belongsTo(User::class);
    }

    // 关联：一个帖子有一个主要图片
    public function upload() {
        return $this->hasOne(Upload::class);
    }

    // 关联：一个帖子有多个评论 [cite: 1542]
    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
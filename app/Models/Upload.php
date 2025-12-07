<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;
    
    // 允许批量赋值的字段
    protected $fillable = ['originalName', 'path', 'mimeType', 'post_id'];

    public function post() {
        return $this->belongsTo(Post::class);
    }
}
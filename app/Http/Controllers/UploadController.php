<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function show(Upload $upload)
    {
        // 从 storage/app/ 下读取文件并返回给浏览器
        // 路径对应 database 中的 path 字段，例如 "uploads/xg7s8d6f.jpg"
        return response()->file(storage_path('app/' . $upload->path));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
public function index(Request $request)
    {
        $query = Post::with('upload');

        // 1. 关键词搜索
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('location', 'like', "%{$keyword}%")
                  ->orWhere('birth_place', 'like', "%{$keyword}%"); // 新增：支持搜籍贯
            });
        }

        // 2. 年龄搜索
        if ($request->filled('age')) {
            $query->where('age', $request->age);
        }

        // 3. 籍贯单独搜索 (新增)
        if ($request->filled('birth_place')) {
            $query->where('birth_place', 'like', "%{$request->birth_place}%");
        }

        // 4. 类型筛选
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $results = $query->latest()->paginate(12);

        return view('search.index', compact('results'));
    }
}
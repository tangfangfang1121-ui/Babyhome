<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // 用于删除文件

class PostController extends Controller
{

    public function index()
    {

        $parentsSeeking = Post::with(['upload', 'user'])
                            ->where('type', 0)
                            ->latest()
                            ->take(6) 
                            ->get();


        $childrenSeeking = Post::with(['upload', 'user'])
                            ->where('type', 1)
                            ->latest()
                            ->take(6)
                            ->get();

        return view('welcome', compact('parentsSeeking', 'childrenSeeking'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:0,1',
            'age'         => 'required|integer|min:0|max:100',
            'dob'         => 'nullable|date',          
            'birth_place' => 'nullable|string|max:255',
            'location'    => 'required|string|max:255',
            'description' => 'required|string',
            'photo'       => 'required|file|image|max:2048',
        ]);

        $post = new Post($validated);
        $post->user_id = Auth::id();
        $post->save(); 

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->store('uploads'); 

            $upload = new Upload();
            $upload->originalName = $file->getClientOriginalName();
            $upload->mimeType = $file->getMimeType();
            $upload->path = $path;
            $upload->post_id = $post->id;
            $upload->save();
        }

        session()->flash('success', 'Report created successfully.');

        return redirect()->route('dashboard');
    }
    
    /**
     * 显示详情页
     */
    public function show(Post $post)
    {
        // 根据用户的请求，在数据库中查找并显示一篇完整的帖子内容
        $post->load(['upload', 'comments.user']);
        
        return view('posts.show', compact('post'));
    }

    /**
     * 删除逻辑 (需要权限控制)
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        if ($post->upload) {
            Storage::delete($post->upload->path);
            $post->upload->delete(); // 删除 uploads 表记录
        }

        // 删除帖子
        $post->delete();

        session()->flash('success', '记录已删除。');
        // 如果是管理员，跳转回管理员后台；如果是普通用户，跳转回 Dashboard
        if (Auth::user()->role === 'admin' && request()->routeIs('admin.*')) {
             return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('dashboard');    
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    /**
     * 更新逻辑
     */
    public function update(Request $request, Post $post)
    {

        if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin') {
            abort(403, '无权操作');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'status'      => 'required|in:missing,found',
            'age'         => 'required|integer|min:0|max:100',
            'dob'         => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'location'    => 'required|string|max:255',
            'description' => 'required|string',
            'photo'       => 'nullable|file|image|max:2048', // 照片是可选的
        ]);

        // 更新文本基础信息
        $post->fill($validated);
        $post->save();
        if ($request->hasFile('photo')) {
            if ($post->upload) {
                Storage::delete($post->upload->path);
                
                $post->upload->delete();
            }

            $file = $request->file('photo');
            $path = $file->store('uploads'); 
            $post->upload()->create([
                'path' => $path,
                'originalName' => $file->getClientOriginalName(),
                'mimeType' => $file->getMimeType()
            ]);
        }

        return redirect()->route('posts.show', $post->id)->with('success', 'Report updated successfully.');
    }
}
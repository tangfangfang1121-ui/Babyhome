<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // 用于删除文件

class PostController extends Controller
{
    /**
     * 首页/列表页：展示寻人启事卡片
     */
    public function index()
    {
        // 获取家寻子 (Type 0)
        $parentsSeeking = Post::with(['upload', 'user'])
                            ->where('type', 0)
                            ->latest()
                            ->take(6) // 首页只显示最新6条，避免太长
                            ->get();

        // 获取子寻家 (Type 1)
        $childrenSeeking = Post::with(['upload', 'user'])
                            ->where('type', 1)
                            ->latest()
                            ->take(6)
                            ->get();

        return view('welcome', compact('parentsSeeking', 'childrenSeeking'));
    }
    
    /**
     * 显示发布表单
     */
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // 1. [Lecture 08] 验证输入
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:0,1',
            'age'         => 'required|integer|min:0|max:100', // 必须包含
            'dob'         => 'nullable|date',                   // 允许为空
            'birth_place' => 'nullable|string|max:255',         // 允许为空
            'location'    => 'required|string|max:255',
            'description' => 'required|string',
            'photo'       => 'required|file|image|max:2048',
        ]);

        // 2. 创建 Post 记录 [关键修改点]
        // 使用 $validated 数组直接赋值，这样 age, dob, birth_place 都会自动填入
        // 前提是：你已经在 app/Models/Post.php 的 $fillable 数组里加了这些字段
        $post = new Post($validated);
        $post->user_id = Auth::id(); // user_id 不在表单里，需要单独赋值
        $post->save(); 

        // 3. [Lecture 07 核心] 处理文件上传
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

        // 4. 反馈
        session()->flash('success', 'Report created successfully.');

        return redirect()->route('dashboard');
    }
    
    /**
     * 显示详情页
     */
    public function show(Post $post)
    {
        // 预加载关联的 Upload 信息，以及评论和评论作者的信息
        $post->load(['upload', 'comments.user']);
        
        return view('posts.show', compact('post'));
    }

    /**
     * 删除逻辑 (需要权限控制)
     */
    public function destroy(Post $post)
    {
        // [Lecture 08/Auth] 简单的权限检查：只有作者或管理员能删除
        $this->authorize('delete', $post);

        // 1. 删除关联的图片文件 (清理磁盘空间)
        if ($post->upload) {
            Storage::delete($post->upload->path);
            $post->upload->delete(); // 删除 uploads 表记录
        }

        // 2. 删除帖子
        $post->delete();

        session()->flash('success', '记录已删除。');
        // 如果是管理员，跳转回管理员后台；如果是普通用户，跳转回 Dashboard
        if (Auth::user()->role === 'admin' && request()->routeIs('admin.*')) {
             return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('dashboard');    
    }

    /**
     * 显示编辑表单
     */
    public function edit(Post $post)
    {
        // 旧代码：手动 if 判断...
        // 新代码：一行搞定
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    /**
     * 更新逻辑
     */
    public function update(Request $request, Post $post)
    {
        // 1. 权限检查
        if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin') {
            abort(403, '无权操作');
        }

        // 2. 验证输入
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

        // 3. 更新文本基础信息
        $post->fill($validated);
        $post->save();

        // 4. [Lecture 07] 处理文件替换逻辑
        // 只有当用户上传了新照片时，才执行删除旧图、上传新图的操作
        if ($request->hasFile('photo')) {
            
            // 4.1 检查是否存在旧照片
            if ($post->upload) {
                // 步骤 A: 删除物理文件 (清理磁盘)
                Storage::delete($post->upload->path);
                
                // 步骤 B: 删除数据库中的旧条目 (彻底移除这一行数据)
                $post->upload->delete();
            }

            // 4.2 上传新照片
            $file = $request->file('photo');
            // store() 会自动生成一个新的哈希文件名
            $path = $file->store('uploads'); 

            // 4.3 在数据库创建一条全新的 Upload 记录
            // 这里使用 create 而不是 update，确保是全新的 ID
            $post->upload()->create([
                'path' => $path,
                'originalName' => $file->getClientOriginalName(),
                'mimeType' => $file->getMimeType()
            ]);
        }

        return redirect()->route('posts.show', $post->id)->with('success', 'Report updated successfully.');
    }
}
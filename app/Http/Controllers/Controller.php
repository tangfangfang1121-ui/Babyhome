<?php

namespace App\Http\Controllers;

// 引入这两个核心 Trait
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // 启用授权、任务分发和验证功能
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
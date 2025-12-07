<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            // 这里的 post_id 关联到 posts 表
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); 
            $table->string('originalName', 100); // 原始文件名
            $table->string('path', 100);         // 存储在 storage 里的路径
            $table->string('mimeType', 100)->nullable(); // 文件类型
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
};

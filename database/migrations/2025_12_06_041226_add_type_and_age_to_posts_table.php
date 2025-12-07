<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // type: 0 = 家寻子 (默认), 1 = 子寻家
            $table->tinyInteger('type')->default(0)->after('user_id'); 
            // age: 走失时的年龄
            $table->integer('age')->nullable()->after('type'); 
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['type', 'age']);
        });
    }
};

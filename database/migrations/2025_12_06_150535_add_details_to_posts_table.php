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
        Schema::table('posts', function (Blueprint $table) {
            // 在 age 字段后面追加出生日期，允许为空
            $table->date('dob')->nullable()->after('age');
            // 在 dob 后面追加出生地点
            $table->string('birth_place')->nullable()->after('dob');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['dob', 'birth_place']);
        });
    }
};

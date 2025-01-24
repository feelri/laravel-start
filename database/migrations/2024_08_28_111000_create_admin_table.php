<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('account', 20)->comment('账号');
            $table->string('mobile', 20)->nullable()->comment('手机号');
            $table->string('email', 100)->nullable()->comment('邮箱');
            $table->string('password', 100)->nullable()->comment('密码');
            $table->string('name', 50)->nullable()->comment('姓名');
            $table->string('nickname', 50)->nullable()->comment('昵称');
            $table->string('avatar')->nullable()->comment('头像文件路径');
            $table->string('gender')->default(0)->comment('性别：0-女、1-男');
            $table->dateTime('last_login_at')->nullable()->comment('上次登录时间');
            $table->tinyInteger('is_disable')->default(0)->nullable()->comment('是否禁用：0-否、1-是');
            $table->dateTime('created_at')->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->nullable()->comment('修改时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');

			$table->unique(['account', 'deleted_at']);
        });
        DB::unprepared('ALTER TABLE `admin` comment "管理员表"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};

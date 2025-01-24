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
        Schema::create('user_oauth', function (Blueprint $table) {
            $table->id();
			$table->string('from', 50)->comment('应用来源：wechat_mini_program-微信小程序');
			$table->integer('user_id')->comment('用户编号：关联 user.id');
			$table->string('openid', 50)->default('')->comment('应用唯一编号：类似微信中的 openid');
			$table->string('unionid', 50)->default('')->comment('应用全局唯一编号：类似微信中的 unionid');
			$table->dateTime('un_oauth_at')->nullable()->comment('解除授权时间');
			$table->dateTime('created_at')->nullable()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->comment('修改时间');

			$table->unique(['from', 'user_id']);
			$table->unique(['unionid']);
        });

		DB::unprepared('ALTER TABLE `user_oauth` comment "第三方授权"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_oauth');
    }
};

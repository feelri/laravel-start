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
        Schema::create('oauth', function (Blueprint $table) {
            $table->id();
			$table->string('from', 50)->comment('应用来源：wechat_mini_program-微信小程序');
			$table->string('source_type', 50)->comment('资源类型，多态关联类型');
			$table->integer('source_id')->comment('资源编号，多态关联编号');
			$table->string('openid', 50)->default('')->comment('应用唯一编号：类似微信中的 openid');
			$table->string('unionid', 50)->default('')->comment('应用全局唯一编号：类似微信中的 unionid');
			$table->dateTime('un_oauth_at')->nullable()->comment('解除授权时间');
			$table->dateTime('created_at')->nullable()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->comment('修改时间');

			$table->unique(['source_type', 'source_id', 'unionid']);
        });

		DB::unprepared('ALTER TABLE `oauth` comment "第三方授权"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth');
    }
};

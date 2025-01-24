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
        Schema::create('sms_log', function (Blueprint $table) {
            $table->id();
			$table->string('mobile', 20)->comment('手机号');
			$table->string('driver', 20)->comment('短信驱动：alibaba-阿里云、tencent-腾讯云、huawei-华为云、qiniu-七牛云');
			$table->json('request')->nullable()->comment('请求参数');
			$table->json('response')->nullable()->comment('相应参数');
			$table->tinyInteger('status')->nullable()->comment('状态：10-成功、20-失败');
			$table->dateTime('created_at')->nullable()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->comment('修改时间');
        });

		DB::unprepared('ALTER TABLE `sms_log` comment "短信发送日志"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_log');
    }
};

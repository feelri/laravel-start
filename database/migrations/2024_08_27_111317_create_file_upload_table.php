<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('file_upload', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment("编号");
			$table->integer('category_id')->nullable()->comment('分类编号：关联 category.id');
			$table->string('from', 20)->default('local')->comment('文件来源：local-本地、aliyun-阿里云OSS、qiniu-七牛云');
            $table->string('marker', 50)->comment('文件流唯一标识')->unique();
            $table->string('name')->default('')->comment('文件名');
            $table->string('mime', 100)->comment("文件 mime 类型");
            $table->string('suffix', 50)->default('')->comment('文件后缀');
            $table->string('path')->default('')->comment('文件相对路径');
            $table->bigInteger('size')->default(0)->comment('文件大小：单位字节');
            $table->dateTime('created_at')->nullable()->comment('创建时间');
        });
        DB::unprepared('ALTER TABLE `file_upload` comment "文件上传表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('upload');
    }
};

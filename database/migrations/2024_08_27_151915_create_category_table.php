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
        Schema::create('category', function (Blueprint $table) {
            $table->id();
			$table->string('type', 50)->default('file_upload')->comment('类型：file_upload-文件上传、dictionary-字典');
			$table->integer('parent_id')->nullable()->comment('父级编号');
			$table->integer('left')->comment('区间');
			$table->integer('right')->comment('区间');
			$table->string('name', 100)->default('')->comment('名称');
			$table->string('icon', 50)->default('')->comment('icon');
			$table->bigInteger('rank')->default(0)->comment('排序，最大越靠前');
			$table->dateTime('created_at')->nullable()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->comment('修改时间');
			$table->dateTime('deleted_at')->nullable()->comment('删除时间');

			$table->index(['left', 'right']);
		});

		DB::unprepared('ALTER TABLE `category` comment "分类表"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};

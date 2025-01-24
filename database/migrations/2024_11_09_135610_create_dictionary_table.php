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
        Schema::create('dictionary', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable()->comment('分类编号：关联 category.id');
			$table->string('key')->nullable()->comment('唯一值');
			$table->string('name')->default('')->comment('名称');
			$table->string('description')->default('')->comment('描述');
			$table->dateTime('created_at')->nullable()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->comment('修改时间');

			$table->unique(['key']);
        });

		DB::unprepared('ALTER TABLE `dictionary` comment "字典"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictionary');
    }
};

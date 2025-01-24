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
        Schema::create('role', function (Blueprint $table) {
            $table->id();
			$table->string('name', 50)->default('')->comment('名称');
			$table->string('description')->default('')->comment('描述');
			$table->bigInteger('rank')->default(0)->comment('菜单排序，最大越靠前');
			$table->tinyInteger('is_top_level')->default(0)->comment('是否未最高权限角色：0-否、1-是');
			$table->dateTime('created_at')->nullable()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->comment('修改时间');
			$table->dateTime('deleted_at')->nullable()->comment('删除时间');
        });

		DB::unprepared('ALTER TABLE `role` comment "角色表"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};

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
        Schema::create('permission', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable()->comment('父级编号');
			$table->integer('left')->comment('区间');
			$table->integer('right')->comment('区间');
			$table->tinyInteger('type')->default(1)->comment('类型：1-菜单、2-权限');
			$table->string('name', 50)->default('')->comment('名称');
			$table->string('icon', 50)->default('')->comment('icon');
			$table->string('uri', 100)->default('')->comment('后端路由');
			$table->string('path', 100)->default('')->comment('前端路由');
			$table->string('method', 20)->default('')->comment('请求方式：GET、POST、PUT、DELETE');
			$table->string('component', 100)->default('')->comment('对应前端组件路径（不包含后缀）');
			$table->tinyInteger('is_show')->default(1)->comment('是否显示：0-否、1-是');
			$table->tinyInteger('is_disable')->default(0)->comment('是否禁用：0-否、1-是');
			$table->bigInteger('rank')->default(0)->comment('菜单排序，最大越靠前');
			$table->dateTime('created_at')->nullable()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->comment('修改时间');
			$table->dateTime('deleted_at')->nullable()->comment('删除时间');

			$table->index(['left', 'right']);
		});
		DB::unprepared('ALTER TABLE `permission` comment "权限菜单表"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission');
    }
};

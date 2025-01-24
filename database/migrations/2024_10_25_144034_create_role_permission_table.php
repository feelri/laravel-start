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
		Schema::create('role_permission', function (Blueprint $table) {
			$table->id();
			$table->tinyInteger('role_id')->comment('管理员编号：关联 admin.id');
			$table->tinyInteger('permission_id')->comment('权限菜单编号：关联 permission.id');

			$table->unique(['role_id', 'permission_id']);
		});

		DB::unprepared('ALTER TABLE `role_permission` comment "角色权限中间表"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission');
    }
};

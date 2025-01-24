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
        Schema::create('admin_role', function (Blueprint $table) {
            $table->id();
			$table->tinyInteger('admin_id')->comment('管理员编号：关联 admin.id');
			$table->tinyInteger('role_id')->comment('角色编号：关联 role.id');

			$table->unique(['admin_id', 'role_id']);
        });

		DB::unprepared('ALTER TABLE `admin_role` comment "管理员角色中间表"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_role');
    }
};

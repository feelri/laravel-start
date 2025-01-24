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
        Schema::create('dictionary_item', function (Blueprint $table) {
            $table->id();
            $table->integer('dictionary_id')->comment('字典编号：关联 dictionary.id');
			$table->integer('parent_id')->nullable()->comment('父级编号');
			$table->integer('left')->comment('区间');
			$table->integer('right')->comment('区间');
			$table->string('key', 50)->default('')->comment('键');
			$table->string('value')->nullable()->comment('值');
			$table->string('label', 50)->default('')->comment('名称');
			$table->dateTime('created_at')->nullable()->comment('创建时间');
			$table->dateTime('updated_at')->nullable()->comment('修改时间');

			$table->unique(['dictionary_id', 'key']);
			$table->index(['left', 'right']);
        });

		DB::unprepared('ALTER TABLE `dictionary_item` comment "字典明细"');
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictionary_item');
    }
};

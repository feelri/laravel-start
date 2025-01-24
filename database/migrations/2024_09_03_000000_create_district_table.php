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
        Schema::create('district', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('level')->default(1)->comment('层级');
            $table->integer('parent_id')->nullable()->comment('父级编号');
			$table->integer('left')->comment('区间');
			$table->integer('right')->comment('区间');
            $table->mediumInteger('area_code')->default(0)->comment('行政代码');
            $table->mediumInteger('zip_code')->default(0)->comment('邮政编码');
            $table->mediumInteger('city_code')->default(0)->comment('区号');
            $table->string('name', 50)->default('')->comment('名称');
            $table->string('short_name', 50)->default('')->comment('简称');
            $table->string('merger_name', 50)->default('')->comment('组合名');
            $table->string('pinyin', 50)->default('')->comment('拼音');
            $table->decimal('longitude', 10, 6)->default(0)->comment('经度');
            $table->decimal('latitude', 10, 6)->default(0)->comment('纬度');

			$table->index(['left', 'right']);
		});
        DB::unprepared('ALTER TABLE `district` comment "中国行政地区表"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district');
    }
};

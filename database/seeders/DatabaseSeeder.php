<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use App\Models\Admin\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$role = Role::query()->create([
			'name'         => '超级管理员',
			'is_top_level' => 1,
		]);

		$admin = Admin::query()->create([
			'account'  => 'admin',
			'name'     => 'Admin',
			'nickname' => '超级管理员',
			'password' => Hash::make('123456'),
		]);
		$admin->roles()->attach([$role->id]);

		User::query()->create([
			'account'  => 'user',
			'nickname' => 'user',
			'password' => Hash::make('123456'),
		]);

		// 权限
		$sql = file_get_contents(database_path('permission.sql'));
		if (!empty($sql)) {
			DB::unprepared($sql);
		}

		// 地区树
		$sql = file_get_contents(database_path('district.sql'));
		if (!empty($sql)) {
			DB::unprepared($sql);
		}
	}
}

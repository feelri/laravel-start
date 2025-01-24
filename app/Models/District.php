<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

class District extends Model
{
    use NodeTrait, HasFactory;

    public $timestamps = false;

	protected $hidden = ['left', 'right', 'created_at', 'updated_at'];

	public function getLftName(): string
	{
		return 'left';
	}

	public function getRgtName(): string
	{
		return 'right';
	}

	public function getParentIdName(): string
	{
		return 'parent_id';
	}
}

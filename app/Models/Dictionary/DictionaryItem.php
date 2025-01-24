<?php

namespace App\Models\Dictionary;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NodeTrait;

class DictionaryItem extends Model
{
	use NodeTrait;

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

	/**
	 * 关联字典表
	 * @return BelongsTo
	 */
	public function dictionary(): BelongsTo
	{
		return $this->belongsTo(Dictionary::class);
	}
}

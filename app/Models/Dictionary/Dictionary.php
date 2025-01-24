<?php

namespace App\Models\Dictionary;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dictionary extends Model
{
	/**
	 * 明细
	 * @return HasMany
	 */
	public function items(): HasMany
	{
		return $this->hasMany(DictionaryItem::class);
	}
}

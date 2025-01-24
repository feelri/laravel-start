<?php

namespace App\Models\User;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOauth extends Model
{
	/**
	 * 关联用户
	 * @return BelongsTo
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}

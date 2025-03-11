<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Oauth extends Model
{
	/**
	 * 资源
	 * @return MorphTo
	 */
	public function source(): MorphTo
	{
		return $this->morphTo();
	}
}

<?php

namespace App\Broadcasting;

use App\Models\User\User;
use Illuminate\Support\Facades\Log;

class TestChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
		Log::error("校验是否有访问频道权限: ");
	}

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user): array|bool
    {
		Log::error("校验是否有访问频道权限: ");
		return false;
    }
}

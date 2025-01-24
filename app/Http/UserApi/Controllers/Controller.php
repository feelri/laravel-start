<?php

namespace App\Http\UserApi\Controllers;

use App\Traits\ResponseTrait;

abstract class Controller extends \Illuminate\Routing\Controller
{
    use ResponseTrait;
}

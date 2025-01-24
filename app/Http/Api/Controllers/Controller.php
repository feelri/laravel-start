<?php

namespace App\Http\Api\Controllers;

use App\Traits\ResponseTrait;

abstract class Controller extends \Illuminate\Routing\Controller
{
    use ResponseTrait;
}

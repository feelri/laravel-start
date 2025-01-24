<?php

namespace App\Http\AdminApi\Controllers;

use App\Traits\ResponseTrait;

abstract class Controller extends \Illuminate\Routing\Controller
{
    use ResponseTrait;
}

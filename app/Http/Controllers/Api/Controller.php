<?php

namespace App\Http\Controllers\Api;

use App\Interfaces\HttpCodeInterface;
use App\Traits\Utilities\ApiResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController implements HttpCodeInterface
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use ApiResponse;
}

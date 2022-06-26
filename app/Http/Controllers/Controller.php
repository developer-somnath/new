<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $object = [];
    protected $data   = [];
    protected $ApiUserId;
    protected $ApiUserDeviceToken;
    protected $ApiUserPhone;
    protected $ApiUserEmail;
}

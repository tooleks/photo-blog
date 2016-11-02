<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as CoreController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

/**
 * Class Controller
 * @property Request request
 * @property Guard guard
 * @package App\Http\Controllers
 */
abstract class Controller extends CoreController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Controller constructor.
     *
     * @param Request $request
     * @param Guard $guard
     */
    public function __construct(Request $request, Guard $guard)
    {
        $this->request = $request;
        $this->guard = $guard;
    }
}

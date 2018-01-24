<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\View\Factory as ViewFactory;

/**
 * Class IndexController.
 *
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * IndexController constructor.
     *
     * @param ViewFactory $viewFactory
     */
    public function __construct(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return $this->viewFactory->make('app.index');
    }
}

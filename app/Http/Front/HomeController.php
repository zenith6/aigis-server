<?php
namespace Aigis\Http\Front;

use Aigis\Http\BaseController;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(HomeIndexRequest $request)
    {
        return view('front.home.index');
    }
}

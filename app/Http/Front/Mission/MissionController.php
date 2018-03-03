<?php

namespace Aigis\Http\Front\Mission;

use Aigis\Game\Mission;
use Aigis\Http\BaseController;

class MissionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(MissionIndexRequest $request)
    {
        $missions = Mission::query()
            ->orderBy('weight')
            ->get();

        return view('front.missions.index')
            ->with([
                'missions' => $missions,
            ]);
    }
}

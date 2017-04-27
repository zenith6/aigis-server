<?php

namespace Aigis\Http\Api\Mission;

use Aigis\Game\Mission;
use Aigis\Http\BaseController;
use Illuminate\Database\Eloquent\Builder;

class DropClearController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:api');
    }

    public function store(DropClearStoreRequest $request, Mission $mission)
    {
        \DB::beginTransaction();

        /** @var \Aigis\Account\Player $player */
        $player = $request->user();

        $player->drops()
            ->whereHas('map', function (Builder $query) use ($mission) {
                $query->where('maps.mission_id', $mission->id);
            })
            ->delete();

        \DB::commit();

        return response()->json(['success' => true]);
    }
}

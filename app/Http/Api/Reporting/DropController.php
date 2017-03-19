<?php

namespace Aigis\Http\Api\Reporting;

use Aigis\Game\Drop;
use Aigis\Game\Map;
use Aigis\Http\BaseController;
use Illuminate\Support\Collection;

class DropController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:api');
    }

    public function store(DropStoreRequest $request)
    {
        \DB::beginTransaction();

        /** @var \Aigis\Account\Player $player */
        $player = $request->user();

        $data = $request->input('map', []);

        /** @var \Illuminate\Database\Eloquent\Collection $drops */
        $drops = $player
            ->drops()
            ->lockForUpdate()
            ->whereIn('map_id', array_pluck($data, 'id'))
            ->get()
            ->keyBy('map_id');

        /** @var \Aigis\Game\Map[]|\Illuminate\Database\Eloquent\Collection $maps */
        $maps = Map::query()->get()->keyBy('id');

        (new Collection($data))
            ->map(function ($data) use ($player, $drops, $maps) {
                /** @var \Aigis\Game\Drop $drop */
                $drop = $drops->get($data['id']);

                /** @var \Aigis\Game\Map $map */
                $map = $maps->get($data['id']);

                if (!$drop) {
                    $drop = new Drop();
                    $drop->map()->associate($data['id']);
                    $drop->player()->associate($player);
                }

                $drop->fill($data);

                $drop->verified = (int)($drop->lap > 0 && $drop->quantity / $drop->lap <= $map->max_drops);
                $drop->save();
            });

        \DB::commit();

        return response()->json(['success' => true]);
    }

    public function delete(DropDeleteRequest $request)
    {
        \DB::beginTransaction();

        /** @var \Aigis\Account\Player $player */
        $player = $request->user();

        $player->drops()->delete();

        \DB::commit();

        return response()->json(['success' => true]);
    }
}

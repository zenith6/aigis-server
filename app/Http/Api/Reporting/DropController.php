<?php

namespace Aigis\Http\Api\Reporting;

use Aigis\Game\Drop;
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

        (new Collection($data))
            ->map(function ($data) use ($player, $drops) {
                $drop = $drops->get($data['id']);

                if (!$drop) {
                    $drop = new Drop();
                    $drop->map()->associate($data['id']);
                    $drop->player()->associate($player);
                }

                $drop->fill($data);

                if ($drop->lap > 0) {
                    $drop->save();
                }
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

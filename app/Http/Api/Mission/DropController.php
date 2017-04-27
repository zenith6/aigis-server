<?php

namespace Aigis\Http\Api\Mission;

use Aigis\Game\Drop;
use Aigis\Game\Mission;
use Aigis\Http\BaseController;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class DropController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:api', ['except' => 'index']);
    }

    public function index(DropIndexRequest $request, Mission $mission)
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $mission->drops()
            ->with([
                'player',
                'map',
            ])
            ->orderBy('updated_at', 'desc');

        /** @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator $drops */
        $drops = $query->paginate(20);

        $histories = $drops->map(function (Drop $drop) {
            return [
                'player_uuid'            => $drop->player->uuid,
                'map'                    => $drop->map->name,
                'lap'                    => $drop->lap,
                'drop'                   => $drop->quantity,
                'rate'                   => $drop->rate,
                'updated_at'             => $drop->updated_at->format(Carbon::ATOM),
                'verified'               => (bool)$drop->verified,
                'contains_initial_bonus' => $drop->contains_initial_bonus,
            ];
        });

        return response()->json([
            'data'  => $histories,
            'total' => $drops->total(),
        ]);
    }

    public function store(DropStoreRequest $request, Mission $mission)
    {
        \DB::beginTransaction();

        /** @var \Aigis\Account\Player $player */
        $player = $request->user();

        $data = $request->input('drops', []);

        /** @var \Aigis\Game\Map[]|\Illuminate\Database\Eloquent\Collection $maps */
        $maps = $mission->maps()
            ->get()
            ->keyBy('id');

        /** @var \Illuminate\Database\Eloquent\Collection $drops */
        $drops = $player
            ->drops()
            ->lockForUpdate()
            ->whereIn('map_id', $maps->keys())
            ->get()
            ->keyBy('map_id');

        (new Collection($data))
            ->each(function ($data) use ($player, $drops, $maps) {
                /** @var \Aigis\Game\Drop $drop */
                $drop = $drops->get($data['map_id']);

                /** @var \Aigis\Game\Map $map */
                $map = $maps->get($data['map_id']);

                if (!$map) {
                    \DB::rollBack();

                    abort(400);
                }

                if (!$drop) {
                    $drop = new Drop();
                    $drop->map()->associate($map);
                    $drop->player()->associate($player);
                }

                $drop->fill($data);

                $drop->verified = (int)($drop->lap > 0 && $drop->quantity / $drop->lap <= $map->max_drops);
                $drop->save();
            });

        \DB::commit();

        return response()->json(['success' => true]);
    }
}

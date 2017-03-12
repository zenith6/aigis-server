<?php

namespace Aigis\Http\Api\Drop;

use Aigis\Game\Drop;
use Aigis\Http\BaseController;
use Carbon\Carbon;

class DropController extends BaseController
{
    public function index(DropIndexRequest $request)
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Drop::query()
            ->with([
                'player',
                'map',
            ])
            ->orderBy('updated_at', 'desc');

        /** @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator $drops */
        $drops = $query->paginate(20);

        $histories = $drops->map(function (Drop $drop) {
            return [
                'player_uuid' => $drop->player->uuid,
                'map'         => $drop->map->name,
                'lap'         => $drop->lap,
                'drop'        => $drop->quantity,
                'rate'        => $drop->rate,
                'updated_at'  => $drop->updated_at->format(Carbon::ATOM),
            ];
        });

        return response()->json([
            'data'  => $histories,
            'total' => $drops->total(),
        ]);
    }
}

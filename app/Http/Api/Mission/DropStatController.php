<?php

namespace Aigis\Http\Api\Mission;

use Aigis\Game\Drop;
use Aigis\Game\Mission;
use Aigis\Http\BaseController;
use Illuminate\Database\Eloquent\Builder;

class DropStatController extends BaseController
{
    public function index(DropStatIndexRequest $request, Mission $mission)
    {
        $rate = $request->input('drop_rate');

        $stats = Drop::query()
            ->join('maps', 'maps.id', '=', 'drops.map_id')
            ->select([
                \DB::raw('map_id AS id'),
                \DB::raw('COUNT(*) AS samples'),
                \DB::raw('IFNULL(SUM(lap - IF(drops.contains_initial_bonus, LEAST(lap, 1), 0)), 0) AS lap_sum'),
                \DB::raw('IFNULL(SUM(quantity - IF(drops.contains_initial_bonus, LEAST(quantity, maps.max_drops), 0)), 0) AS drop_sum'),
                \DB::raw('IFNULL(SUM(quantity - IF(drops.contains_initial_bonus, LEAST(quantity, maps.max_drops), 0)) / SUM(lap - IF(drops.contains_initial_bonus, LEAST(lap, 1), 0)), 0) AS drop_average'),
                \DB::raw('IFNULL(MIN(rate), 0) AS rate'),
            ])
            ->where('maps.mission_id', $mission->id)
            ->where('verified', 1)
            ->when($rate, function (Builder $query) use ($rate) {
                return $query->where('rate', $rate);
            })
            ->groupBy('map_id')
            ->get()
            ->map(function (Drop $map) {
                $map->samples = 0 + $map->samples;
                $map->lap_sum = 0 + $map->lap_sum;
                $map->drop_sum = 0 + $map->drop_sum;
                $map->drop_average = 0 + $map->drop_average;
                $map->rate = 0 + $map->rate;

                return $map;
            });

        return response()->json([
            'maps' => $stats,
        ]);
    }
}

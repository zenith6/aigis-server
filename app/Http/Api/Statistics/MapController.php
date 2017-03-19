<?php

namespace Aigis\Http\Api\Statistics;

use Aigis\Game\Drop;
use Aigis\Game\Map;
use Aigis\Http\BaseController;
use Illuminate\Database\Query\JoinClause;

class MapController extends BaseController
{
    public function index(MapIndexRequest $request)
    {
        $drops = Drop::query()
            ->groupBy('map_id')
            ->select([
                'map_id',
                \DB::raw('COUNT(*) AS samples'),
                \DB::raw('SUM(lap) AS lap_sum'),
                \DB::raw('SUM(quantity) AS quantity_sum'),
                \DB::raw('SUM(quantity) / SUM(lap) AS quantity_average'),
                \DB::raw('MIN(rate) AS rate'),
            ])
            ->where('verified', 1);

        if ($rate = $request->input('filter.drop_rate')) {
            $drops->where('rate', $rate);
        }

        $maps = Map::query()
            ->leftJoin(\DB::raw('(' . $drops->toSql() . ') AS drops'), function (JoinClause $clause) {
                $clause->on('maps.id', 'drops.map_id');
            })
            ->addBinding($drops->getBindings(), 'join')
            ->select([
                'maps.id' => 'id',
                \DB::raw('IFNULL(drops.samples, 0) AS samples'),
                \DB::raw('IFNULL(drops.lap_sum, 0) AS lap_sum'),
                \DB::raw('IFNULL(drops.quantity_sum, 0) AS drop_sum'),
                \DB::raw('IFNULL(drops.quantity_average, 0) AS drop_average'),
            ])
            ->get()
            ->map(function (Map $map) {
                $map->samples = 0 + $map->samples;
                $map->lap_sum = 0 + $map->lap_sum;
                $map->drop_sum = 0 + $map->drop_sum;
                $map->drop_average = 0 + $map->drop_average;
                $map->quantity_sum = 0 + $map->quantity_sum;
                $map->quantity_average = 0 + $map->quantity_average;
                $map->rate = 0 + $map->rate;

                return $map;
            });

        return response()->json([
            'maps'      => $maps,
        ]);
    }
}

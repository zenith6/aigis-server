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
            ]);

        $maps = Map::query()
            ->leftJoin(\DB::raw('(' . $drops->toSql() . ') AS drops'), function (JoinClause $clause) {
                $clause->on('maps.id', 'drops.map_id');
            })
            ->select([
                'maps.id' => 'id',
                \DB::raw('IFNULL(drops.samples, 0) AS samples'),
                \DB::raw('IFNULL(drops.lap_sum, 0) AS lap_sum'),
                \DB::raw('IFNULL(drops.quantity_sum, 0) AS drop_sum'),
                \DB::raw('IFNULL(drops.quantity_average, 0) AS drop_average'),
            ])
            ->get()
            ->map(function (Map $map) {
                $map->drop_average = $map->drop_average + 0;

                return $map;
            });

        return response()->json([
            'maps' => $maps,
        ]);
    }
}

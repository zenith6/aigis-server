<?php

namespace Aigis\Game;

use Aigis\Account\Player;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property integer player_id
 * @property integer map_id
 * @property integer lap
 * @property integer quantity
 * @property float rate
 * @property bool verified
 * @property bool contains_initial_bonus
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property \Aigis\Game\Map map
 * @property \Aigis\Account\Player player
 */
class Drop extends Model
{
    protected $fillable = [
        'lap',
        'quantity',
        'rate',
        'contains_initial_bonus',
    ];

    protected $casts = [
        'lap'                    => 'int',
        'quantity'               => 'int',
        'rate'                   => 'float',
        'verified'               => 'bool',
        'contains_initial_bonus' => 'bool',
    ];

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}

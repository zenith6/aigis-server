<?php
namespace Aigis\Game;

use Aigis\Account\Player;
use Illuminate\Database\Eloquent\Model;

class Drop extends Model
{
    protected $fillable = [
        'lap',
        'quantity',
    ];

    protected $casts = [
        'lap'      => 'int',
        'quantity' => 'int',
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

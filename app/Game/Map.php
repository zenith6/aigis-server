<?php
namespace Aigis\Game;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    public function drops()
    {
        return $this->hasMany(Drop::class);
    }
}

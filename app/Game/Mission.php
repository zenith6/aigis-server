<?php

namespace Aigis\Game;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string name
 * @property integer weight
 * @property boolean allow_report
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 */
class Mission extends Model
{
    public function maps()
    {
        return $this->hasMany(Map::class);
    }

    public function drops()
    {
        return $this->hasManyThrough(Drop::class, Map::class);
    }
}

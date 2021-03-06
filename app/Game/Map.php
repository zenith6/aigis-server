<?php

namespace Aigis\Game;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string name
 * @property integer max_drops
 * @property integer weight
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 */
class Map extends Model
{
    public function drops()
    {
        return $this->hasMany(Drop::class);
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
}

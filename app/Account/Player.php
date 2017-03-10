<?php
namespace Aigis\Account;

use Aigis\Game\Drop;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property integer id
 * @property string uuid
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 */
class Player extends Authenticatable
{
    protected $hidden = [
        'remember_token',
        'uuid',
        'ip_address',
    ];

    public function drops()
    {
        return $this->hasMany(Drop::class);
    }

    public function getValidationRules()
    {
        return [
            'id'   => ['integer'],
            'uuid' => ['string', 'max:255'],
        ];
    }
}

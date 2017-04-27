<?php

namespace Aigis\Http\Api\Account;

use Aigis\Account\Player;
use Aigis\Http\BaseController;
use Ramsey\Uuid\Uuid;

class SignupController extends BaseController
{
    public function store(SignupStoreRequest $request)
    {
        $player = new Player();
        $player->uuid = Uuid::uuid4();
        $player->api_token = str_random(32);
        $player->ip_address = $request->getClientIp();
        $player->save();

        auth()->login($player);

        return response()->json(['player' => $player]);
    }
}

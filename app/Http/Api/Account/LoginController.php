<?php
namespace Aigis\Http\Api\Account;

use Aigis\Account\Player;
use Aigis\Http\BaseController;

class LoginController extends BaseController
{
    public function store(LoginStoreRequest $request)
    {
        /** @var \Aigis\Account\Player $player */
        $player = Player::query()->where('api_token', $request->input('name'))->first();

        if (!$player) {
            abort(401);
        }

        // $player->api_token = str_random(32);

        auth()->login($player);

        $player->save();

        return response()->json(['player' => $player]);
    }
}

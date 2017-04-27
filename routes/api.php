<?php

use Illuminate\Routing\Router;

/** @var \Illuminate\Routing\Router $router */

$router->group(['middleware' => ['api'], 'prefix' => 'api'], function (Router $router) {
    $router->group(['middleware' => ['guest']], function (Router $router) {
        $router->resource('account/login', \Aigis\Http\Api\Account\LoginController::class, ['only' => ['store']]);
        $router->resource('account/signup', \Aigis\Http\Api\Account\SignupController::class, ['only' => ['store']]);
    });

    $router->resource('missions.drops', \Aigis\Http\Api\Mission\DropController::class, ['only' => ['index', 'store', 'destroy']]);
    $router->resource('missions.drops_stats', \Aigis\Http\Api\Mission\DropStatController::class, ['only' => ['index']]);
});

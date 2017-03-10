<?php

use Illuminate\Routing\Router;

/** @var \Illuminate\Routing\Router $router */

$router->group(['middleware' => ['api'], 'prefix' => 'api'], function (Router $router) {
    $router->group(['middleware' => ['guest']], function (Router $router) {
        $router->resource('account/login', \Aigis\Http\Api\Account\LoginController::class, ['only' => ['store']]);
        $router->resource('account/signup', \Aigis\Http\Api\Account\SignupController::class, ['only' => ['store']]);
    });

    $router->group(['middleware' => ['auth:api']], function (Router $router) {
        $router->resource('report/drops', \Aigis\Http\Api\Reporting\DropController::class, ['only' => ['store']]);
        $router->delete('report/drops', \Aigis\Http\Api\Reporting\DropController::class . '@delete');
    });

    $router->resource('drops', \Aigis\Http\Api\Drop\DropController::class, ['only' => ['index']]);

    $router->resource('stat/drops', \Aigis\Http\Api\Statistics\MapController::class, ['only' => ['index']]);
});

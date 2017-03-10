<?php

use Illuminate\Routing\Router;

/** @var \Illuminate\Routing\Router $router */

$router->group(['middleware' => 'web'], function (Router $router) {
    $router->get('/', \Aigis\Http\Front\HomeController::class . '@index');
});

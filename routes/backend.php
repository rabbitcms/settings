<?php
declare(strict_types=1);
use Illuminate\Routing\Router;

/* @var Router $router */
$router->get('', ['as' => 'view', 'uses' => 'Settings@getIndex']);
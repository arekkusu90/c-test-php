<?php

use Psr\Container\ContainerInterface;

const VIEWS = 'views';

$container = $app->getContainer();
$container[VIEWS] = new Slim\Views\PhpRenderer(__DIR__ . '/Views');


// CONTROLLERS
// ---------------------------------------------------------------------------------------------------------------------
$container[\Clx\Controllers\UserController::class] = function (ContainerInterface $c) {
    $views = $c->get(VIEWS);
    return new \Clx\Controllers\UserController($views);
};
<?php


use Clx\Controllers\UserController;

$app->get('/', UserController::class . ':home');
$app->post('/users', UserController::class . ':showUserAction');


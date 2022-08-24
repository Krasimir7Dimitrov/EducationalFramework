<?php

use App\Controllers\CarsController;
use App\Controllers\AuthController;
use Pecee\SimpleRouter\SimpleRouter;
use App\Controllers\UsersController;
use App\Controllers\DefaultController;

SimpleRouter::match(['get','post'],'/', [DefaultController::class, 'index']);
SimpleRouter::match(['get','post'],'/cars/listing', [CarsController::class, 'listing']);
SimpleRouter::match(['get','post'],'/cars/create', [CarsController::class, 'create']);
SimpleRouter::match(['get','post'],'/auth/login', [AuthController::class, 'login']);
SimpleRouter::match(['get','post'],'/auth/logout', [AuthController::class, 'logout']);
SimpleRouter::match(['get','post'],'/auth/register', [AuthController::class, 'register']);
SimpleRouter::match(['get','post'],'/auth/forgotpassword', [AuthController::class, 'forgotPassword']);
SimpleRouter::match(['get','post'],'/auth/resetPassword', [AuthController::class, 'resetPassword']);
SimpleRouter::match(['get','post'],'/users/create', [UsersController::class, 'create']);
SimpleRouter::match(['get','post'], '/users/getAllUsers', [UsersController::class, 'getAllUsers']);

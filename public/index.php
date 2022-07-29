<?php
require_once __DIR__ . '/../vendor/autoload.php';

$application = \App\System\Application::getInstance();
$application2 = $application;
$application = null;
var_dump($application); die();
$application->run();

$email = 2;
$email2 = $email;

$email = null;

var_dump($email);
var_dump($email2);

<?php
require_once __DIR__ . '/../vendor/autoload.php';

$application = \App\System\Application::getInstance();
$bug = new App\System\Debugbar\Debugbar();
$application->run();



var_dump($_SESSION);
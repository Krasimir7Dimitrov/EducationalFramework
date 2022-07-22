<?php
require_once __DIR__ . '/../vendor/autoload.php';
$startTime = microtime();
\App\System\Registry::set('startTime', $startTime);

$application = \App\System\Application::getInstance();
$application->run();

$decorator = new \App\System\Decorator('html');
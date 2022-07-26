<?php

$startTime = microtime();

require_once __DIR__ . '/../vendor/autoload.php';

\App\System\Registry::set('startTime', $startTime);

$application = \App\System\Application::getInstance();
$application->run();

$debugData = $application->getDebugData();

$debugBar = new \App\System\Debugbar\Debugbar($debugData);
$decorator = new \App\System\Decorator($debugBar);

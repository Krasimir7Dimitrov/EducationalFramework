<?php

$startTime = microtime();

require_once __DIR__ . '/../vendor/autoload.php';

\App\System\Registry::set('startTime', $startTime);

$application = \App\System\Application::getInstance();
$application->run();

$debugData = $application->getDebugData();

$debugBar = new \App\Library\Debugbar\Debugbar($debugData);
$debugBar->render(\App\Library\Debugbar\Enums\DecorationTypes::HTML());


$decorator = new \App\Library\Debugbar\Decorators\Decorator($debugBar);


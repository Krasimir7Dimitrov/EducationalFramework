<?php

$startTime = microtime();

require_once __DIR__ . '/../vendor/autoload.php';

$application = \App\System\Application::getInstance();
$application->run();

$debugData = $application->getDebugData();

$debugBar = new \App\Library\Debugbar\Debugbar($debugData);
$debugBar->render(\App\Library\Debugbar\Enums\DecorationTypes::HTML());
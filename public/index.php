<?php

$startTime = microtime();

require_once __DIR__ . '/../vendor/autoload.php';

$application = \App\System\Application::getInstance();
$application->run();

$debugData = $application->getDebugData();

$debugBar1 = new \Debugbar\Debugbar($debugData);
$debugBar1->render(\Debugbar\Enums\DecorationTypes::HTML());

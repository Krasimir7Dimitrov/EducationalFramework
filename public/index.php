<?php
$startTime = microtime(true);
require_once __DIR__ . '/../vendor/autoload.php';
\App\System\Registry::set('startTime', $startTime);

$application = \App\System\Application::getInstance();
$application->run();
$debugData = $application->getDebugData();

$debugData = new \App\System\Debugbar\Debugbar($debugData);
$decorator = new \App\System\Debugbar\Decorator($debugData);
$decorator->returnCsv();

//var_dump($_SESSION);
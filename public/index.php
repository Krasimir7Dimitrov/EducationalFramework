<?php
require_once __DIR__ . '/../vendor/autoload.php';

echo "Heere we are in index.php <hr/>";

$application = \App\System\Application::getInstance();
$application->run();

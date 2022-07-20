<?php

use App\System\Database\DbAdapter;
use App\System\Registry;

require_once __DIR__ . '/../vendor/autoload.php';
session_start();

// here we will initialize our Registry
try {
    $config = require __DIR__.'/../app/config/config.php';
    Registry::set('config', $config);

    $dbAdapter = new DbAdapter();
    Registry::set('dbAdapter', $dbAdapter);

} catch (\Exception $e) {
    var_dump($e->getMessage());
}
Registry::get('dbAdapter');
$frontController = new \App\System\FrontController();
$frontController->run();


Registry::unset('config');
Registry::unset('dbAdapter');


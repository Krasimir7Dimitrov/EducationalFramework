<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../app/config/config.php';
\App\System\Registry::set('config', $config);

$hi = \App\System\Registry::get('config');
var_dump($hi['config']['db']['dbAdapter']);

\App\System\Registry::unset('config');


$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);

$defaultController = "\\App\\Controllers\\DefaultController";
$controllerInstance = new $defaultController();
//Get the controller class
if (!empty($uri_segments[1])) {
    try {
        $controllerName = "\\App\\Controllers\\".ucfirst(strtolower($uri_segments[1])).'Controller';
        $exists = class_exists($controllerName);
        if ($exists) {
            $controllerInstance = new $controllerName();
        }
    } catch (\Throwable $e) {
        //do some action if an error occurs
    }
}

//Get the action method
$defaultAction     = 'index';
$action = $defaultAction;
if (!empty($uri_segments[2])) {
    $actionName = strtolower($uri_segments[2]);
    $exists = method_exists($controllerInstance, $actionName);
    if ($exists) {
        $action = $actionName;
    }
}

$controllerInstance->$action();

die();
$host = "db";
$port = "3306";
$database = "db";
$user = "user";
$password = "password";
$results = null;

try {
    $connection = new PDO("mysql:host=$host;port=$port;charset=utf8mb4;dbname=$database", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db = $connection->query('SELECT * FROM cars');
    $results = $db->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Connection Failed' . $e->getMessage();
}

?>

<?php require_once __DIR__.'/partials/header.php'?>
<?php require_once __DIR__.'/partials/menu.php'?>
<table>
    <tr>
        <th>Make</th>
        <th>Model</th>
        <th>First registration</th>
        <th>Transmission</th>
    </tr>

    <?php foreach ($results as $key => $result) { ?>
        <tr>
            <td><?= $result['make'] ?> </td>
            <td><?= $result['model'] ?> </td>
            <td><?= $result['first_registration'] ?> </td>
            <?php if ($result['transmission'] == 1) { ?>
                <td> manual </td>
            <?php } ?>
            <?php if ($result['transmission'] == 2) { ?>
                <td> automation </td>
            <?php } ?>
        </tr>
    <?php } ?>


</table>

<?php require_once __DIR__.'/partials/footer.php'?>
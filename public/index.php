<?php
require_once __DIR__ . '/../vendor/autoload.php';

//Да се намери първия сегмент от URL и чрез него да се инициализира контролер
//Ако няма такъв сегмент по дефаулт да се инициализира някой от контролерите

// Да се намери и хване втория сегмент от URL ako има такъв и ако има валиден контролер, и да се рендира екшъна вътре;



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
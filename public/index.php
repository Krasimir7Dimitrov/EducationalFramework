<?php

include "templates/header.php";
include 'templates/footer.php';
require_once '../config/database.php';

global $connection;

$db = $connection->query('SELECT * FROM cars');
$results = $db->fetchAll(PDO::FETCH_ASSOC);

?>

<table class="centered">
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
            <td><?= $result['transmission'] ?></td>
        </tr>
    <?php } ?>


</table>

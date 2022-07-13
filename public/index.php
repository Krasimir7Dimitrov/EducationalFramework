<?php

require_once 'Listing.php';
$results = new Listing();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<a href="/edit.php">Edit</a>
<a href="/create.php">Create</a>
<a href="/contact.php">Contact Us</a>

<table>
    <tr>
        <th>Make</th>
        <th>Model</th>
        <th>First registration</th>
        <th>Transmission</th>
    </tr>

    <?php foreach ($results->getData() as $key => $result) { ?>
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

<style>
    tr{
        mar: 10px;
    }
</style>
</body>

</html>

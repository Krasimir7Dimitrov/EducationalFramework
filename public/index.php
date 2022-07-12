<?php
$host = "db";
$port = "3306";
$database = "db";
$user = "user";
$password = "password";
$result_set = null;

try {
    $connection = new PDO("mysql:host=$host;port=$port;charset=utf8mb4;dbname=$database", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//    $db = $connection->query('SELECT * FROM cars');
//    $results = $db->fetchAll(PDO::FETCH_ASSOC);

    if ($result_set = $connection->query('SELECT * FROM cars')) {
        while ($row = $result_set->fetch(3)) {
            echo $row;
        }
    }

} catch (PDOException $e) {
    echo 'Connection Failed' . $e->getMessage();
}

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

<ul>
    <?php foreach ($result_set as $result) { ?>
        <?php foreach ($result as $key => $value) { ?>
            <li><?php  ?> </li>
        <?php } ?>
    <?php
    }
    ?>
</ul>

</body>
</html>

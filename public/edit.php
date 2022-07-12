<?php
 echo 'This is the edit page';
 ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit</title>
</head>
<body>
<a href="/index.php">List</a>
<a href="/create.php">Create</a>
<a href="/contact.php">Contact Us</a>

<form action="post">
    <div>
        <label for="make">Make</label><br>
        <input type="text" name="make"><br>

        <label for="model">Model</label><br>
        <input type="text" name="model"><br>

        <label for="registration"> First Registration</label><br>
        <input type="text" name="registration"><br>

        <label for="transmission">Transmission</label><br>
        <input type="text" name="transmission"><br>

        <button type="submit">Edit record</button>
    </div>
</form>
</body>
</html>

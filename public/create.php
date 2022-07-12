<?php
echo 'This is the create page';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create</title>
</head>
<body>
<a href="/index.php">List</a>
<a href="/edit.php">Edit</a>
<a href="/contact.php">Contact Us</a>

<form action="post">
    <div>
        <label for="make">Make</label><br>
        <input type="text" name="make" placeholder="Enter make"><br>

        <label for="model">Model</label><br>
        <input type="text" name="model" placeholder="Enter model"><br>

        <label for="registration"> First Registration</label><br>
        <input type="text" name="registration" placeholder="Enter registration date"><br>

        <label for="transmission">Transmission</label><br>
        <input type="text" name="transmission" placeholder="Enter transmission type"><br>

        <button type="submit">Create record</button>
    </div>
</form>
</body>
</html>

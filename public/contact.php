<?php

echo 'This is the contact page';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Page</title>
</head>
<body>
    <a href="/index.php">List</a>
    <a href="/edit.php">Edit</a>
    <a href="/create.php">Create</a>

    <form action="post">
        <fieldset>
            <legend>Contact Form:</legend>

            <label for="firstName">First Name</label><br>
            <input type="text" name="firstName" placeholder="Enter First Name"><br>

            <label for="lastName">Last Name</label><br>
            <input type="text" name="lastName" placeholder="Enter Last Name"><br>

            <label for="email">Email</label><br>
            <input type="email" name="email" placeholder="Enter Your Email"><br>

            <label for="message">Message</label><br>
            <input type="text" name="message" placeholder="Enter Your Message"><br>
            <button type="submit">Send Message</button>
        </fieldset>
    </form>
</body>
</html>

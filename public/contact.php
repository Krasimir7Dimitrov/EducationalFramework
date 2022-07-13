<?php
include "templates/header.php";
include "templates/footer.php";

if (isset($_POST['submit'])) {
    echo $_POST['message'];
}
?>

    <form action="contact.php" method="POST">
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

            <input type="submit" name="submit" value="submit" class="btn">
        </fieldset>
    </form>


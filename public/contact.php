<?php require_once __DIR__.'/partials/header.php';?>
<?php require_once __DIR__.'/partials/menu.php';?>

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
<?php require_once __DIR__.'/partials/footer.php';?>

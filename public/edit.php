<?php require_once __DIR__.'/partials/header.php'?>
<?php require_once __DIR__.'/partials/menu.php'?>

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
<?php require_once __DIR__.'/partials/footer.php'?>
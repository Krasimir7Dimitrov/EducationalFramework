<?php require_once __DIR__.'/partials/header.php'?>
<?php require_once __DIR__.'/partials/menu.php'?>

<form action="../app/Controllers/CarsController.php" method="post">
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
<?php require_once __DIR__.'/partials/footer.php'?>

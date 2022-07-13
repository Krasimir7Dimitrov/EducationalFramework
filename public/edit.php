<?php

include "templates/header.php";
include "templates/footer.php";

?>

<form action="" method="">
    <fieldset>
        <legend>Edit form</legend>
        <label for="make">Make</label><br>
        <input type="text" name="make"><br>

        <label for="model">Model</label><br>
        <input type="text" name="model"><br>

        <label for="registration"> First Registration</label><br>
        <input type="text" name="registration"><br>

        <label>Transmission</label><br>
        <label>
            <input type="radio" name="transmission" class="with-gap">
            <span>Manual</span>
        </label>
        <label>
            <input type="radio" name="transmission" class="with-gap">
            <span>Automatic</span>
        </label> <br>

        <input type="submit" name="submit" value="submit" class="btn">
    </fieldset>

</form>

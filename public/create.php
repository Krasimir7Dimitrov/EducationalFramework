<?php

include "templates/header.php";
include "templates/footer.php";

if (isset($_POST['submit'])) {
    $info = 'This is the information that you have entered:  Make: ' . $_POST['make'] . ' Model : ' . $_POST['model'] . ' Registration Year : ' . $_POST['registration']
        . ' Transmission : ' . $_POST['transmission'];
    echo $info;
    var_dump($_POST['transmission']);
}
?>

<form action="create.php" method="POST">
    <fieldset>
        <legend>Create form</legend>
        <label for="make">Make</label><br>
        <input type="text" name="make" placeholder="Enter make"><br>

        <label for="model">Model</label><br>
        <input type="text" name="model" placeholder="Enter model"><br>

        <label for="registration"> First Registration</label><br>
        <input type="text" name="registration" placeholder="Enter registration date"><br>

        <label>Transmission</label><br>
        <label>
            <input type="radio" name="transmission" class="with-gap" value="manual">
            <span>Manual</span>
        </label>
        <label>
            <input type="radio" name="transmission" class="with-gap" value="automatic">
            <span>Automatic</span>
        </label> <br>


        <input type="submit" name="submit" value="submit" class="btn">
    </fieldset>
</form>


<?php
    $connection = mysqli_connect(Config::databaseHostname, Config::databaseUsername, Config::databasePassword, Config::databaseName);
    if(!$connection) {
        Config::displayErrorMessage("There was an error connecting to the database. Please check your configuration & server log.");
        die();
    }
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="/res/style.css?<?php echo rand(0, 99); ?>">

<div class="container">
    <div class="jumbotron">
    <h1 class="display-3">Setup</h1>
<?php
    $setup = true;
    include("config.php");

    if(!$setup) die("Setup is disabled. Please set setup to true in your setup.php.");
    $databaseHostname = null;
    $databaseUsername = null;
    $databasePassword = null;
    $databaseName = null;
    
    $configFile = file("config.php");
    foreach($configFile as $line) {
        if(strpos($line, "const databaseHostname =") !== false) {
            $databaseHostname = explode('"', $line)[1];
        }
        if(strpos($line, "const databaseUsername =") !== false) {
            $databaseUsername = explode('"', $line)[1];
        }
        if(strpos($line, "const databasePassword =") !== false) {
            $databasePassword = explode('"', $line)[1];
        }
        if(strpos($line, "const databaseName =") !== false) {
            $databaseName = explode('"', $line)[1];
        }
    }
    
    function fillUpDatabaseWithStuff($host, $user, $pass, $name) {
        $connection = mysqli_connect($host, $user, $pass, $name);
        $filename = 'geekstats.sql';
        echo "$host $user $pass $name";
        if(!$connection) die("There was an error connecting..?");
        $op_data = '';
        $lines = file($filename);
        foreach ($lines as $line)
        {
            if (substr($line, 0, 2) == '--' || $line == '')
            {
                continue;
            }
            $op_data .= $line;
            if (substr(trim($line), -1, 1) == ';')
            {
                mysqli_query($connection, $op_data);
                $op_data = '';
            }
        }
        if(unlink("geekstats.sql")) {
            $fileDeleted = true;
        } else {
            $fileDeleted = false;
        }
        
        die("Database has been successfully created! You can now go to <a class='btn btn-primary' href='/'>geekstats</a>.");
    }
    
    $connection = mysqli_connect($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
    if(!$connection) {
        die("There was an error attempting to connect to the database. Please make sure you've populated config.php correctly.");
    }
    
    $q = mysqli_query($connection, "select * from members");
    if($q) {
        die("It looks like a database already exists.. please reset the '$databaseName' database before running this setup.");
    }
    
    if(isset($_GET["setup"])) {
        fillUpDatabaseWithStuff($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
    }
    
?>

<p>Looks like everything is configured correctly. Once you're ready, hit <a class="btn btn-primary" href="?setup">this button</a> to setup the database.
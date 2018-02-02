<?php
    include("header.php");
    $siteConfig->verifyLoggedIn();
    
    $debugSwitch = true;
    
    echo "<div class=\"container\"><h1 class='display-3'>For testing purposes only!!!</h1><h3>?pwd=<password> at the end of the URL to get the result the database would have.<hr>";
    if($debugSwitch) {
        echo "Encrypted password result: " . $siteConfig->encryptPassword(strip_tags($_GET["pwd"]));
    } else {
        header('Location: /error/403');
    }
?>

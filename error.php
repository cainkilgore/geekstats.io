<?php
	include("header.php");
    $errorCode = $_GET["code"];
    
    $errorMessage = "There was no error message supplied for this $errorCode error.";
    if($errorCode == 404) $errorMessage = Config::fileNotFoundMessage;
    if($errorCode == 403) $errorMessage = Config::accessForbiddenMessage;
    if(strpos($errorCode, 5) !== false) $errorMessage = Config::internalServerMessage;
    if($errorCode == 418) $errorMessage = Config::teapotMessage;
?>
<br>
<div class="container" style="text-align:center">
	<h1 class="display-1">Oh no!</h1>
	<h3><?php echo $errorMessage; ?></h3>
    <hr>
	<img class="img-fluid" src="/images/cat.png" />
</div>
<?php
    include("config.php");
    $siteConfig = new Config();
    
    if(!isset($_GET["simulate"])) {
        if(!$siteConfig::underMaintenance) header('Location: /');
    }
    
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="/res/style.css?<?php echo rand(0, 99); ?>">
    
<body background="/images/maintenance.gif">
<style type="text/css">
body {
}
.flip {
    transform: scaleX(-1);
}
</style>
<hr>
<div class="container">
	<div class="jumbotron" style="background: rgba(255, 255, 255, 0.8)">
		<h1 class="display-1">Under Maintenance</h1>
		<h3><?php echo $siteConfig::maintenanceMessage; ?></h3>
	</div>
</div>

<marquee direction="right">
<img height="60px" src="/images/nyan.gif" />
</marquee>
<marquee direction="right" class="flip">
<img height="60px" src="/images/nyan.gif" />
</marquee>
<marquee direction="right">
<img height="60px" src="/images/nyan.gif" />
</marquee>
<marquee direction="right"class="flip">
<img height="60px" src="/images/nyan.gif" />
</marquee>
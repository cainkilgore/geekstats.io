<html>
	<head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="/res/style.css?<?php echo rand(0, 99); ?>">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js"></script>
	</head>
	<?php 
		include("config.php");
		include("servicePing.php");
        session_start();
		$servicePing = new ServicePing();
		
		$siteConfig = new Config();
		$siteConfig->loadConfig();
		echo "<title>" . $siteConfig::siteName . " | " . $siteConfig::siteMotto . "</title>\n";
		
		if($siteConfig::underMaintenance) {
			header('Location: /maintenance');
			exit;
		}
        if(isset($_GET["simulateMaintenance"])) {
            header('Location: /maintenance?simulate');
        }
	?>
	
	<body>
		<nav class="navbar navbar-expand-md navbar-light" style="background-color: #20bf6b;">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="#"><?php echo $siteConfig::siteName; ?></a>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<?php
						foreach($siteConfig->getNavbarPages() as $page) {
							$pageTitle = explode(",", $page)[0];
							$pageLink = explode(",", $page)[1];
							echo "<li class='nav-item'><a class='nav-link' href='$pageLink'>$pageTitle</a></li>\n";
						}
					?>
				</ul>
				<ul class="navbar-nav navbar-right">
                <?php
                    if($_SESSION["username"] != "") {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout"><?php echo $_SESSION[username]; ?></a>
                    </li>
                <?php
                    } else {
                ?>
					<li class="nav-item">
						<a class="nav-link" href="/login">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/register">Register</a>
					</li>
                <?php } ?>
				</ul>
			</div>
		</nav>
        <!-- End of Header -->
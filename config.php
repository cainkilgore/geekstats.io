<?php
    // By Cain Kilgore
    // January 2018 Project

	class Config {
		var $pageList = [];
        
        const fileNotFoundMessage = "The specified resource or directory could not be found.";
        const accessForbiddenMessage = "The file you tried to access is protected.";
        const internalServerMessage = "We've hit a internal server error. Rest assured, we're working on it.";
        const teapotMessage = "I'm a teapot.";
        
        const databaseHostname = "localhost";
        const databaseUsername = "geekstats";
        const databasePassword = "dGl0dGllcw==";
        const databaseName = "geekstats";
        
        const minUpdateInterval = 1;
        const maxUpdateInterval = 60;
        
        const showLargeHeader = true;
        
        const siteName = "geekstats.io";
        const siteMotto = "The friendly dashboard that monitors for you.";
        const underMaintenance = false;
        const maintenanceMessage = "We're working on some stuff.";
        
        const limitedAdminAccounts = true;
        
		function loadConfig() {
			array_push($this->pageList, "Home,/");
			array_push($this->pageList, "Admin,/services/list");
		}
		
		function getNavbarPages() {
			return $this->pageList;
		}
		
        function encryptPassword($password) {
            $resultPassword = base64_encode($password);
            $resultPassword = md5($resultPassword);
            $resultPassword = hash('sha512', $resultPassword);
            $resultPassword = base64_encode($password);
            return $resultPassword;
        }
        
        function logout() {
            session_unset();
            session_destroy();
            header('Location: /');
        }
        
        function verifyLoggedIn() {
            if($_SESSION["username"] == "") header('Location: /denied');
        }
        
        function backToHomepage() {
            header('Location: /');
        }
        
        function displayErrorMessage($message) {
            echo "<div class='alert alert-danger' role='alert'>$message</div>\n";
        }
        
        function displaySuccessMessage($message) {
            echo "<div class='alert alert-success' role='alert'>$message</div>\n";
        }
        
	}
?>

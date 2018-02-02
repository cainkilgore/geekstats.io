<?php
    include("header.php");
    include("sqlConnection.php");
    
    $username = mysqli_real_escape_string($connection, strip_tags($_POST["username"]));
    $password = Config::encryptPassword(mysqli_real_escape_string($connection, strip_tags($_POST["password"])));
    $email = mysqli_real_escape_string($connection, strip_tags($_POST["email"]));
    $confirmPassword = Config::encryptPassword(mysqli_real_escape_string($connection, strip_tags($_POST["confirmPassword"])));
    
    $success = false;
    echo "<div class=\"container\"><br>";

    $q = mysqli_query($connection, "select * from members");
    
    /*
        The purpose of this check is to prevent more than one account from being registered.
        You can disable this check by changing config.php.
        Do note that site functionality will be a little funky.
    */
    
    if(mysqli_num_rows($q) > 0 && $siteConfig::limitedAdminAccounts) {
        echo "<h1 class='display-3'>Error</h1>";
        $siteConfig->displayErrorMessage("An admin account has already been registered.");
        return;
    }
    if($username != "" && $password != "" && $email != "" && $confirmPassword != "") {
        $q = mysqli_query($connection, "select * from members WHERE LOWER(username)='" . strtolower($username) . "'");
        if(mysqli_num_rows($q) > 0) {
            $errorMessage = "A user with this username already exists.";
            $error = true;
        }
        
        if($password != $confirmPassword) {
            $errorMessage = "Your password and password confirmation are not the same.";
            $error = true;
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Your email address doesn't appear to be valid.";
            $error = true;
        }
        
        if($error) {
            $siteConfig->displayErrorMessage($errorMessage);
        } else {
            $q = mysqli_query($connection, "INSERT INTO members (username, email_address, password) VALUES('$username', '$email', '$password')");
            if($q) {
                $success = true;
            } else {
                $success = false;
            }
        }
        
        if($success) {
            $siteConfig->displaySuccessMessage("You have successfully registered. You can now proceed to <a href='/login'>login</a>.");
            return;
        } else {
            echo mysqli_error($connection);
        }
    }
?>

    <h1 class="display-2">Register</h1>

    <form method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Your username" required />
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="cain@cain.sh" required />
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" aria-describedBy="passwordHelp" placeholder="Password" required />
        </div>
        <div class="form-group">
            <label for="password">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" aria-describedBy="passwordHelp" placeholder="Password" required />
        </div>
        <button type="submit" class="btn btn-primary" id="register">Register</button>
    </form>
</div>

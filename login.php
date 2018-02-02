<?php
    include("header.php");
    include("sqlConnection.php");
    
    $username = mysqli_real_escape_string($connection, strip_tags($_POST["username"]));
    $password = $siteConfig->encryptPassword(mysqli_real_escape_string($connection, strip_tags($_POST["password"])));
    
    if($username != "" && $password != "") {
        $q = mysqli_query($connection, "select * from members WHERE LOWER(username)='" . strtolower($username) . "'");
        if(mysqli_num_rows($q) < 1) {
            $error = true;
        }
        
        if(mysqli_fetch_array($q)["password"] != $password) {
            $error = true;
        }
        
        if($error) {
            echo "<div class='alert alert-danger' role='alert'>You entered an invalid username or password.</div>";
        }
        else {
            $_SESSION["username"] = $username;
            header('Location: /services/list');
        }
    }
    
?>

<div class="container">
<h1 class="display-2">Login</h1>
<form method="post">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Your username" required />
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" aria-describedBy="passwordHelp" placeholder="Password" required />
    </div>
        <button type="submit" class="btn btn-primary" id="login">Login</button>
</form>

</div>

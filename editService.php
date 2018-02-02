<?php
    include("header.php");
    include("sqlConnection.php");
    $siteConfig->verifyLoggedIn();

    $serviceId = mysqli_real_escape_string($connection, strip_tags($_GET["id"]));
    if(empty($serviceId)) $siteConfig->backToHomepage();
    $q = mysqli_query($connection, "select * from stats WHERE id='$serviceId'");
    $errorLogged = false;
    if(mysqli_num_rows($q) < 1) {
        $errorMessage = "This service doesn't exist.";
        $errorLogged = true;
    }
    if($errorLogged) {
        die("<div class='alert alert-danger' role='alert'>$errorMessage</div>");
    }
    
    $memberId = mysqli_fetch_array(mysqli_query($connection, "SELECT * from members WHERE LOWER(username)='" . strtolower($_SESSION["username"]) . "'"))[0];
    
    if(mysqli_fetch_array($q)["member_id"] != $memberId) {
        $errorLogged = true;
        die("<div class='alert alert-danger' role='alert'>You don't own this service.</div>");
    }
    
    $q = mysqli_query($connection, "select * from stats WHERE id='$serviceId'");
    $q = mysqli_fetch_array($q);
    $serviceNickname = $q[2];
    $serviceHostname = $q[3];
    $servicePort = $q[4];
    $serviceShowConnection = $q[5];
    $servicePublic = $q[6];
    $serviceUpdateInterval = $q[7];
    
    $formNickname = mysqli_real_escape_string($connection, strip_tags($_POST["nickname"]));
    $formHostname = mysqli_real_escape_string($connection, strip_tags($_POST["hostname"]));
    $formPort = mysqli_real_escape_string($connection, strip_tags($_POST["port"]));
    $formShowCon = mysqli_real_escape_string($connection, strip_tags($_POST["showConString"]));
    $formPublic = mysqli_real_escape_string($connection, strip_tags($_POST["showPublic"]));
    $formUpdateInterval = mysqli_real_escape_string($connection, strip_tags($_POST["update"]));
    
    if($formNickname != "" && $formHostname != "" && $formPort != "" && $formShowCon != "" && $formPublic != "" && $formUpdateInterval != "") {
        if(strlen($formNickname) < 3) {
            $errorResult = true;
            $errorMessage = "Please pick a longer nickname.<br>\n";
        }
        $q = mysqli_query($connection, "UPDATE stats SET nickname='$formNickname', hostname='$formHostname', port='$formPort', show_connection='$formShowCon', public='$formPublic', update_interval='$formUpdateInterval' WHERE id='$serviceId'");
        if($q) {
            $error = 0;
            header('Location: /services/list?edited=' . $serviceId);
        } else {
            $error = mysqli_error($connection);
        }
        
        if(!is_null($error)) {
            $siteConfig->showErrorMessage("There was an error executing this SQL.<br>$error");
        }
    }
    
?>

<div class="container">
    <h1 class="display-3">Editing <?php echo $serviceNickname; ?>...</h1>
    <hr />

    <form method="post">
        <div class="form-group">
            <label for="nickname">Service Name</label>
            <input class="form-control" name="nickname" placeholder="..." value="<?php echo $serviceNickname; ?>" required />
        </div>
        <div class="form-group">
            <label for="hostname">Service Hostname</label>
            <input class="form-control" name="hostname" placeholder="..." value="<?php echo $serviceHostname; ?>" required />
        </div>
        <div class="form-group">
            <label for="port">Service Port</label>
            <input type="number" min="1" max="65535" class="form-control" name="port" placeholder="..." value="<?php echo $servicePort; ?>" required />
        </div>
        <div class="form-group">
            <label for="showConString">Show Connection String</label>
            <select class="form-control" id="showConString" name="showConString">
            <?php
                $sel = "";
                if($serviceShowConnection == "true") $sel = "selected=\"selected\"";
            ?>
                <option value="false" <?php echo $sel; ?>>false</option>
                <option value="true" <?php echo $sel; ?>>true</option>
            </select>
        </div>
        <div class="form-group">
            <label for="showPublic">Show on Homepage</label>
            <select class="form-control" id="showPublic" name="showPublic">
            <?php
                $sel = "";
                if($servicePublic == "true") $sel = "selected=\"selected\"";
            ?>
                <option value="false" <?php echo $sel; ?>>false</option>
                <option value="true" <?php echo $sel; ?>>true</option>
            </select>
        </div>
        <div class="form-group">
            <label for="update">Update Interval (minutes)</label>
            <input class="form-control" type="number" min="<?php echo $siteConfig::minUpdateInterval; ?>" max="<?php echo $siteConfig::maxUpdateInterval; ?>" name="update" placeholder="..." value="<?php echo $serviceUpdateInterval; ?>" required />
        </div>
        <button type="submit" class="btn btn-submit">Edit</button>
        <input type="hidden" name="id" value="<?php echo $serviceId; ?>" />
    </form>
</div>


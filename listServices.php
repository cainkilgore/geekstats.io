<?php
    include("header.php");
    include("sqlConnection.php");
    $siteConfig->verifyLoggedIn();
?>

<script>
function fadeMessage() {
    var alert = document.getElementById("alert");
    setTimeout(function() {
        alert.style.opacity = "0";
        setTimeout(function() {
            alert.style.display = "none";
        }, 600);
    }, 1000);
}
</script>

<div class="container">
    <h1 class="display-2">Hey <?php echo $_SESSION[username]; ?></h1>
    <hr>
    This is where you go to edit your services and add any you think you might need.
    
    <?php
        if($_GET["edited"] != "") {
            echo "<body onload=\"fadeMessage()\"><div class='alert alert-success alert-fade' role='alert' id='alert'>Your service was successfully changed.</div>\n<hr>";
        }
    ?>
    
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Date Added</th>
                    <th scope="col">Service Name</th>
                    <th scope="col">Service Address</th>
                    <th scope="col">Service Port</th>
                    <th scope="col">Show public?</th>
                    <th scope="col">Update Interval</th>
                    <th scope="col">Show connection?</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
        <?php
            $q = mysqli_query($connection, "select * from stats");
            while($row = mysqli_fetch_array($q)) {
                echo "<tr valign='middle'><td>$row[date_added]</td><td>$row[nickname]</td><td>$row[hostname]</td><td>$row[port]</td><td>$row[public]</td><td>$row[update_interval]</td><td>$row[show_connection]</td><td><a href='/service/$row[id]/edit'><img class='editPage' src='/images/edit.svg' width='16px' /></a>  <a href='#'><img class='editPage' src='/images/database.svg' width='16px' /></a> <a href='#'><img class='editPage'  src='/images/trash.svg' width='16px' /></a></td></tr>\n";
            }
        ?>
            </tbody>
        </table>
    </div>
</div>

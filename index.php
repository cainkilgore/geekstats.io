<?php
    include("header.php");
    include("sqlConnection.php");
?>

<?php
    if($siteConfig::showLargeHeader) {
?>
<div class="titleBar">
	<h1 class="display-2"><?php echo $siteConfig::siteName; ?></h1>
	<h3>The friendly dashboard that monitors for you.</h3>
</div>
<hr>
<?php } ?>
    
<script>
    function httpGetAsync(theUrl, box, element)
    {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() { 
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var result = xmlHttp.responseText;
                console.log(result);
                if(result.includes("Offline")) {
                        document.getElementById(box).style.backgroundColor = "#e74c3c";
                } else {
                        document.getElementById(box).style.backgroundColor = "#27ae60";
                }
                document.getElementById(element).innerHTML = xmlHttp.responseText;
            }
        }
        xmlHttp.open("GET", theUrl, true);
        xmlHttp.send(null);
    }

    <?php
        $hostnameList = [];
        $entry = [];
        $q = null;
        
        if($_SESSION["username"] == "") {
            $q = mysqli_query($connection, "select * from stats WHERE public='true'");
        }
        
        if($_SESSION["username"] != "") {
            $user = $_SESSION["username"];
            $q = mysqli_fetch_array(mysqli_query($connection, "select * from members WHERE username='$user'"))[id];
            $q = mysqli_query($connection, "select * from stats WHERE member_id='$q'");
        }
        
        while($row = mysqli_fetch_array($q)) {
            $entry = array($row[id], $row[nickname], $row[hostname], $row[port], $row[show_connection], $row["public"]);
            array_push($hostnameList, $entry);
        }
    ?>
    var refreshTimer;
    var timerAmount = 60;
    function runPingChecks() {
        timerAmount = 60;
        if(!refreshTimer) {
            refreshTimer = setInterval(function() { 
                    timerAmount -= 1;
                    document.getElementById("refresh").innerHTML = "(" + timerAmount + " seconds..)";
                    if(timerAmount == 0) {
                        clearInterval(refreshTimer);
                        refreshTimer = false;
                        runPingChecks();
                    }
            }, 1000);
        }
        
        <?php
            foreach($hostnameList as $hostname => $i) {
                echo "httpGetAsync('servicePing.php?hostname=$i[2]&port=$i[3]', 'box$hostname', 'ip$hostname');\n";
            }
        ?>
    }

    function gotoService(service) {
        window.location.href = "service/" + (service) + "/view";
    }

    window.onload = runPingChecks();
</script>

<div class="container" style="margin: 0px auto;">
    <div class="row">
        <?php
            foreach($hostnameList as $hostname => $i) {
                $titleBuilder = "";
                if($_SESSION["username"] != "") {
                    $titleBuilder = $i[1];
                    if($i[4] == "false") $titleBuilder .= " <img src='/images/locked.svg' width='18px' />";
                    if($i[4] == "true") $titleBuilder .= " <img src='/images/public.svg' width='18px' />";
                } else {
                    $titleBuilder = $i[1];
                }
                echo "<div class='col-md-4 pingList' id='box$hostname' onClick=\"gotoService($i[0])\"><h3>$titleBuilder<br>";
                if($i[3]) echo "<font size='1px'>($i[2]:$i[3])</font><br>";
                echo "<span id='ip$hostname'><img src='/images/loading.gif' width='32px' /></span></h3></td></div>\n";
            }
        ?>
    </div>
    <br>
    <button type="button" class="btn btn-primary btn-lg btn-block" onClick="runPingChecks()">Refresh <span id='refresh'>(60 seconds..)</span></button>
</div>

<hr>
<?php
	include("header.php");
    include("sqlConnection.php");
	$pageId = $_GET["id"];
	$rowLimit = 60;
	
	if($_GET["show"] != "") {
		$rowLimit = $_GET["show"];
	}
	
	$q = mysqli_query($connection, "select * from stats WHERE id='$pageId'");
	$serviceName = mysqli_fetch_array($q)[nickname];
	$showConnection = mysqli_fetch_array($q)[show_connection];
    $serviceConnection = mysqli_fetch_array($q)[hostname];
    
	$q = mysqli_query($connection, "select * from chart_stats WHERE service_id='$pageId' ORDER BY id DESC limit $rowLimit");
	$sBuild = "[";
	$tBuild = "[";
	
	$pingResponses = [];
	if(mysqli_num_rows($q) < 60) $rowLimit = mysqli_num_rows($q);
	while($row = mysqli_fetch_array($q)) {
		$sBuild .= "\"$row[timestamp]\", ";
		$tBuild .= "$row[ping_response], ";
		array_push($pingResponses, $row[ping_response]);
	}
	$sBuild .= "]";
	$tBuild .= "]";
	
	$a = 0;
	foreach($pingResponses as $i) {
		$a += $i;
	}
	
	$a = $a / $rowLimit; // Average
?>

<div class="container">

    <h1 class="display-1" style="text-align:center">
        <?php echo $serviceName; ?>
        <?php if($showConnection == "true") echo "hi" . $serviceConnection; ?></h1>
    <h3 style="text-align:center">Ping within the last hour (avg. <?php echo round($a); ?>ms)</h3>

    <?php
        if(is_nan($a)) { echo "<div class='jumbotron' style='text-align:center'><h1 class='display-3'>No chart data available yet.</h1></div>"; return; }
    ?>
    <canvas id="pingChart" width="100%" >
        <script>
        var ctx = document.getElementById("pingChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                //labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                <?php echo "labels: $sBuild,"; ?>
                datasets: [{
                    label: 'Ping',
                    //data: [12, 19, 3, 5, 2, 3],
                    <?php echo "data: $tBuild,"; ?>
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
        </script>
    </canvas>
</div>

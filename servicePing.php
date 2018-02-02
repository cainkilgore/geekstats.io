<?php
	class ServicePing {
		function pingIpAddr($ip) {
			return exec("ping -c 1 $ip");
		}
		function pingService($hostname, $port) {
			$starttime = microtime(true);
			$file = fsockopen($hostname, $port, $errno, $errstr, 2);
			$stoptime = microtime(true);
			$status = 0;
			
			if(!$file) $status = -1;
			else {
				fclose($file);
				$status = ($stoptime - $starttime) * 1000;
				$status = floor($status);
			}
			return $status;
		}
		
		function sqlPingEverything() {
            include("config.php");
			$conn = mysqli_connect(Config::databaseHostname, Config::databaseUsername, Config::databasePassword, Config::databaseName);
			$q = mysqli_query($conn, "select * from stats");
			while($row = mysqli_fetch_array($q)) {
				$serviceId = $row["id"];
				$serviceHost = $row["hostname"];
				$servicePort = $row["port"];
				$pingResult = ServicePing::pingService($serviceHost, $servicePort);
				print("$serviceHost:$servicePort => $pingResult\n");
				if(mysqli_query($conn, "insert into chart_stats (service_id, ping_response) values('$serviceId', '$pingResult')")) {
					print("Popped into SQL\n\n");
				}
			}
		}
	}
	
	if($_GET["hostname"] != "" && $_GET["port"] != "") {
        $hostname = strip_tags($_GET["hostname"]);
        $port = strip_tags($_GET["port"]);
        
		$ping = new ServicePing();
		$result = $ping->pingService($hostname, $port);
		if($result == -1) {
			$result = "Offline";
		} else {
			$result .= "ms";
		}
		echo "$result";
	}
?>


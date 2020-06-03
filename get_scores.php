<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1); 
$configs = include('../config.php');
$apiUrl = $configs->apiurl;
$pdoUrl = $configs->pdo;

try {   
	$db = new PDO($pdoUrl);  
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
} catch (Exception $e) {  
		echo "Error: Could not connect to database.  Please try again later.";
		exit;
  }

	$year = date('Y');
	$checkDate = date('m/d/Y');
	$checkDate=date('m/d/Y', strtotime($checkDate));
	$lowerBound = date('m/d/Y', strtotime("01/01/$year"));
	$upperBound = date('m/d/Y', strtotime("10/01/$year"));

	if (($checkDate >= $lowerBound) && ($checkDate < $upperBound)) {	
		$season = date('Y', strtotime('-1 years'));
		$season .= "-".date('Y');

	} else {
		$season = date('Y');
		$season .= "-".date('Y', strtotime('+1 years'));	
	}
	$dateURLFormatted = date("Ymd");
	if ($_GET['date'] != '') { 
		$now = $_GET['date'];
		$dateURLFormatted =  DateTime::createFromFormat("Ymd", $now);
	} else { 
		$now = date('Ymd'); 
		$dateURLFormatted =  DateTime::createFromFormat("Ymd", $now);
	}
	if ($dateURLFormatted > date_create($year."-04-09")) {
		$url = $apiUrl."/v2.1/pull/nhl/2020-playoff/date/".$now."/games.json";
	}
	if ($dateURLFormatted < date_create($year."-04-09")) {
		$url = $apiUrl."/v2.1/pull/nhl/".$season."-regular/date/".$now."/games.json";
	}
	$dateURLFormattedPrev =  DateTime::createFromFormat("Ymd", $now);
	$dateURLFormattedPrev = $dateURLFormattedPrev->modify('-1 day');

	$dateURLFormattedNext =  DateTime::createFromFormat("Ymd", $now);
	$dateURLFormattedNext = $dateURLFormattedNext->modify('+1 day');	

 	$json = file_get_contents($url);
	$json = json_decode($json);
?>
<div id="notLiveScores">
	<table class="scoresHeader"> 
		<tr>
			<td><button type='button' class='scoreButton' onclick='showHeaderFor("<?php echo $dateURLFormattedPrev->format('Ymd'); ?>")'>Prev</button></td>
			<td><h5> Scoreboard <br /> <?php echo $dateURLFormatted->format('M d, Y'); ?> </h5></td>
			<td><button type='button' class='scoreButton' onclick='showHeaderFor("<?php echo $dateURLFormattedNext->format('Ymd'); ?>")'>Next</button></td>
			<td> 
				<select class="dateDropDown" id="month" onchange='customDateUrl()'>
				<?php for ($i = 1; $i < 13; $i++) {
						if ($i < 10 ) {
							$i = "0".$i;
						}
						$m = $dateURLFormatted->format('m');
						if ( $m == $i ) {
							echo "<option class='option' selected>" . $i . " </option>" ;
						} else {
							echo "<option class='option'>" . $i . " </option>" ;
							}
						}
				?>
				</select>
			</td>
			<td> 
				<select class="dateDropDown" id="day" onchange='customDateUrl()'>
				<?php for ($i = 1; $i < 32; $i++) {
					if ($i < 10 ) {
						$i = "0".$i;
					}
					$d = $dateURLFormatted->format('d');
						if ( $d == $i ) {
							echo "<option class='option' selected>" . $i . " </option>" ;
						} else {
							echo "<option class='option'>" . $i . " </option>" ;
							}
						}
				?>
				</select>
			</td>
				<td> 
				<select class="dateDropDown" id="year" onchange='customDateUrl()'>
				<?php 
					for ($i = $year; $i > 2012; $i--) {
						$Y = $dateURLFormatted->format('Y');
						if ( $Y== $i ) {
							echo "<option class='option' selected>" . $i . " </option>" ;
						} else {
							echo "<option class='option'>" . $i . " </option>" ;
							}
					}
				?>
				</select>
			</td>
		</tr>
	</table>
</div>
<div id="liveScores">
	<table class='scores'>
		<tr class='scoreResult'>
		<?php
			for($i=0; $i<count($json->games); $i++)  {
				$startTime = $json->games[$i]->schedule->startTime;
				$time = date('h:i', strtotime($startTime));
				$currentPeriod = $json->games[$i]->score->currentPeriod;
				$currentPeriodSR = $json->games[$i]->score->currentPeriodSecondsRemaining;
				$currentInt = $json->games[$i]->score->currentIntermission;
				$playedStatus = $json->games[$i]->schedule->playedStatus;
				$periodCount = count($json->games[$i]->score->periods);
		
				if ($currentPeriodSR != 'null') {
					$periodLength = "00:20:00";
					$timeLeft = strtotime($periodLength) - (1200 - $currentPeriodSR);
					$timeLeft = strftime("%M:%S",$timeLeft);
				} 
				if ($currentPeriod == 1) {
					$currentPeriod = '-'.$currentPeriod.'st';
				}
				if ($currentPeriod == 2) {
					$currentPeriod = '-'.$currentPeriod.'nd';
				}
				if ($currentPeriod == 3) {
					$currentPeriod = '-'.$currentPeriod.'rd';
				}
				if ($playedStatus == "UNPLAYED") {
					$displayTime = $time.' PM';
					$timeLeft ='';
				}
				if ($playedStatus == "LIVE") {
					$displayTime = $currentPeriod;
				}
				if ($currentInt == 1) {
					$displayTime = "End 1st";
					$timeLeft ="";
				}
				if ($currentInt == 2) {
					$displayTime = "End 2nd";
					$timeLeft ="";
				} 
				if ((($playedStatus == "COMPLETED_PENDING_REVIEW") || ($playedStatus == "COMPLETED")) && ($periodCount == 3)) {
					$displayTime = "Final";
					$timeLeft ="";
				}
				if ((($playedStatus == "COMPLETED_PENDING_REVIEW") || ($playedStatus == "COMPLETED")) && ($periodCount == 4)) {
					$otNum = $periodCount - 3;
					$displayTime = "Final/OT";
					$timeLeft ="";
				}
				if ((($playedStatus == "COMPLETED_PENDING_REVIEW") || ($playedStatus == "COMPLETED")) && ($periodCount > 4)) {
					$otNum = $periodCount - 3;
					$displayTime = "Final/SO";
					$timeLeft ="";
				}
				?>
					<td colspan ='2'><?php echo $timeLeft.'&nbsp;'.$displayTime ?></td>
				<?php		
				}
		echo "</tr>";
		echo "<tr>";		
			for($i=0; $i<count($json->games); $i++)  {	
				$awayTeam = $json->games[$i]->schedule->awayTeam->abbreviation;
				$awayScore= $json->games[$i]->score->awayScoreTotal;
				$awayId = $json->games[$i]->schedule->awayTeam->id;

				$query = $db->prepare("SELECT logo FROM teams WHERE teamid = :awayId" );
				$query->bindParam(':awayId', $awayId);
				$query->execute();
				$result = $query->fetchAll();
				foreach ($result as $row) {
					$logo = $row['logo'];
				}
				?>
					<td><img class="logo" src='<?php echo $logo; ?>' alt = '' height='32px' ></td><td><?php echo $awayTeam.'&nbsp;&nbsp;'.$awayScore?></td>
				<?php	
				}
		echo "</tr>";
		echo "<tr>";		
		for($i=0; $i<count($json->games); $i++)  {		
			$homeTeam = $json->games[$i]->schedule->homeTeam->abbreviation;
			$homeScore= $json->games[$i]->score->homeScoreTotal;
			$homeId = $json->games[$i]->schedule->homeTeam->id;

			$query = $db->prepare("SELECT logo FROM teams WHERE teamid = :homeId" );
			$query->bindParam(':homeId', $homeId);
			$query->execute();
			$result = $query->fetchAll();
			foreach ($result as $row) {
				$logo = $row['logo'];
			}

			?>
				<td><img class="logo" src='<?php echo $logo; ?>' alt = '' height='32px' ></td><td><?php echo $homeTeam.'&nbsp;&nbsp;'.$homeScore?></td>
			<?php	
			}
	echo "</tr>";
	echo "</table>";
?>
</div>
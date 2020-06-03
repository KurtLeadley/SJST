<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1); 
$configs = include('../config.php');
$pdoUrl = $configs->pdo;
try {   
	$db = new PDO($pdoUrl);  
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
} catch (Exception $e) {  
		echo "Error: Could not connect to database.  Please try again later.";
		exit;
	}
	
function bindParam($query,$gid,$periods,$season) {
	$query->bindParam(':gid', $gid);
	$query->bindParam(':periods', $periods);
	$query->bindParam(':season', $season);  		
}	
	
$year = date('Y');
$checkDate = date('m/d/Y');
$checkDate=date('m/d/Y', strtotime($checkDate));
$lowerBound = date('m/d/Y', strtotime("01/01/$year"));
$upperBound = date('m/d/Y', strtotime("10/01/$year"));

if (($checkDate >= $lowerBound) && ($checkDate < $upperBound)) {	
	$season = date('Y', strtotime('-1 years'));
	$season .= date('Y');

} else {
	$season = date('Y');
	$season .= date('Y', strtotime('+1 years'));	
}
$dateURLFormatted = date("Ymd"); 
$iteratingSeason = substr($season , 0, -4);
$iteratingDate = $iteratingSeason.'-10-01';

while ($iteratingDate <= $dateURLFormatted) {
	$iteratingDate = new DateTime($iteratingDate);
	$iteratingDate->add(new DateInterval('P1D')); // Period 1 Day
	$iteratingDate = $iteratingDate->format('Ymd');
	echo $iteratingDate;
	echo "<br/>";
	$url = "$apiUrl2018-playoff/scoreboard.json?fordate=".$iteratingDate;
	
	//$url = "$apiUrl2017-2018-regular/scoreboard.json?fordate=".$iteratingDate;
 	$json = file_get_contents($url);
/* 	echo '<pre>';
	var_dump(json_decode($json));
	echo '</pre>'; */
	$json = json_decode($json);
	
	for($i=0; $i<count($json->scoreboard->gameScore); $i++)  {
		$periods = count($json->scoreboard->gameScore[$i]->periodSummary->period);
		$gid = $json->scoreboard->gameScore[$i]->game->ID;
		echo $periods." ". $gid;
		echo "<br/>";
		$query = $db->prepare("UPDATE results2
								   SET periods=:periods
								   WHERE gid=:gid AND season=:season");
														 
		bindParam($query, $gid, $periods, $season);
	  $query->execute(); 
	}
}
?>
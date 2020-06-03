<?php
error_reporting(E_ALL);
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

// The following code will determine the $season in this format: '2015-2016' '2016-2017' for database entry
$year = date('Y');
$checkDate = date('m/d/Y');
$checkDate=date('m/d/Y', strtotime($checkDate));
$lowerBound = date('m/d/Y', strtotime("01/01/$year"));
$upperBound = date('m/d/Y', strtotime("10/01/$year"));

if (($checkDate >= $lowerBound) && ($checkDate < $upperBound)) {	
	$season = date('Y', strtotime('-1 years'));
	$season .="-".date('Y');
	$playoffYear = date('Y');
} else {
	$season = date('Y');
	$season .= "-".date('Y', strtotime('+1 years'));
}
echo $season. '<br/>';
	$json = file_get_contents($apiUrl.'/'.$season.'-regular/roster_players.json');
	$json = json_decode($json);
	
	function bindParam($query, $pid, $lastName, $firstName, $jerseyNumber, $position, $height, $weight, $birthDate, $age, $birthCity, $birthCountry, $isRookie, $teamId, $city, $teamName, $abbreviation, $season) {
		
		$query->bindParam(':pid', $pid);
		$query->bindParam(':lastname', $lastName);
		$query->bindParam(':firstname', $firstName);
		$query->bindParam(':jerseynumber', $jerseyNumber);
		$query->bindParam(':position', $position);
		$query->bindParam(':height', $height);
		$query->bindParam(':weight', $weight);
		$query->bindParam(':birthdate', $birthDate);
		$query->bindParam(':age', $age);
		$query->bindParam(':birthcity', $birthCity);
		$query->bindParam(':birthcountry', $birthCountry);
		$query->bindParam(':isrookie', $isRookie);
		$query->bindParam(':teamid', $teamId);
		$query->bindParam(':city', $city);
		$query->bindParam(':teamname', $teamName);
		$query->bindParam(':abbreviation', $abbreviation);
		$query->bindParam(':season', $season);
	}
	
	for($i=0; $i<count($json->rosterplayers->playerentry); $i++) {
						
		//////////////////////////////////////////////////////////////////////////
		/////////////////////////// Player ///////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////
		
		$pid = $json->rosterplayers->playerentry[$i]->player->ID;
		$lastName = $json->rosterplayers->playerentry[$i]->player->LastName;
		$firstName = $json->rosterplayers->playerentry[$i]->player->FirstName;	
		$jerseyNumber = $json->rosterplayers->playerentry[$i]->player->JerseyNumber;
		$position = $json->rosterplayers->playerentry[$i]->player->Position;		
		$height = $json->rosterplayers->playerentry[$i]->player->Height;		
		$weight = $json->rosterplayers->playerentry[$i]->player->Weight;		
		$birthDate = $json->rosterplayers->playerentry[$i]->player->BirthDate;	
		$age = $json->rosterplayers->playerentry[$i]->player->Age;	
		$birthCity = $json->rosterplayers->playerentry[$i]->player->BirthCity;
		$birthCountry = $json->rosterplayers->playerentry[$i]->player->BirthCountry;	
		$isRookie = $json->rosterplayers->playerentry[$i]->player->IsRookie;
		
		////////////////////////////////////////////////////////////////////////////
		///////////////////////////////// Team /////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////
		
    $teamId = $json->rosterplayers->playerentry[$i]->team->ID;	
		$city = $json->rosterplayers->playerentry[$i]->team->City;		
		$teamName = $json->rosterplayers->playerentry[$i]->team->Name;		
		$abbreviation = $json->rosterplayers->playerentry[$i]->team->Abbreviation;

		
		$query = $db->prepare("INSERT OR IGNORE INTO rosters ('pid','lastname','firstname','jerseynumber','position','height','weight','birthdate','age','birthcity','birthcountry','isrookie','teamid','city','teamname','abbreviation','season')
													
													VALUES (:pid, :lastname, :firstname, :jerseynumber, :position, :height, :weight, :birthdate, :age, :birthcity, :birthcountry, :isrookie, :teamid, :city, :teamname, :abbreviation, :season)");
		
	    bindParam($query, $pid, $lastName, $firstName, $jerseyNumber, $position, $height, $weight, $birthDate, $age, $birthCity, $birthCountry, $isRookie, $teamId, $city, $teamName, $abbreviation, $season);
		$query->execute();
		
		$query = $db->prepare("Update rosters
													 SET pid=:pid, lastname=:lastname, firstname=:firstname, jerseynumber=:jerseynumber, position=:position, height=:height, weight=:weight, birthdate=:birthdate, age=:age, birthcity=:birthcity, birthcountry=:birthcountry, 
															 isrookie=:isrookie, teamid=:teamid, city=:city, teamname=:teamname, abbreviation=:abbreviation, season=:season
													 WHERE pid=:pid AND season=:season"); 
				
	  bindParam($query, $pid, $lastName, $firstName, $jerseyNumber, $position, $height, $weight, $birthDate, $age, $birthCity, $birthCountry, $isRookie, $teamId, $city, $teamName, $abbreviation, $season);
		$query->execute();
		
	}
?>	 
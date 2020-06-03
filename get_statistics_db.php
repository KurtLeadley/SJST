<?php
// this file is set to run with a Cron job daily.
/* 	error_reporting(E_ALL);
  ini_set('display_errors', 1);  */
  $configs = include('../config.php');
  $pdoUrl = $configs->pdo;
	try {   
		$db = new PDO($pdoUrl);  
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
	} catch (Exception $e) {  
		 echo "Error: Could not connect to database.  Please try again later.";
		 exit;
	}	
        // // The following code will determine the $schoolYear in this format: '2015-2016' '2016-2017' 
        // $today = date('m/d/Y');
        // $today = strtotime($today);
        // $year = date('Y', $today);
        // if (date('n', $today) < 8) {
        //     $schoolYear = ($year - 1).'-'.$year;
        // }
        // else {
        //     $schoolYear = ($year).'-'.($year + 1);
        // }
        // // create a lower and upper bound date for the school year to compare later
        // $parsedSchoolYear = explode('-',$schoolYear);
        // $startSchoolYear = $parsedSchoolYear[0];
        // $endSchoolYear = $parsedSchoolYear[1]; 
        // $schoolYearDayOne = date('9/1/'.$startSchoolYear);
        // $schoolYearDayOne = strtotime($schoolYearDayOne);
        // $schoolYearLastDay = date('8/1/'.$endSchoolYear);
        // $schoolYearLastDay = strtotime($schoolYearLastDay);
	
	$year = date('Y');
	$checkDate = date('m/d/Y');
	$checkDate = date('m/d/Y', strtotime($checkDate));
	$lowerBound = date('m/d/Y', strtotime("01/01/$year"));
	$upperBound = date('m/d/Y', strtotime("10/01/$year"));

	if (($checkDate >= $lowerBound) && ($checkDate < $upperBound)) {	
		$season = date('Y', strtotime('-1 years'));
		$season .="-".date('Y');
		$playoffYear = date('Y');
	} else {
		$season = date('Y');
		$season .= "-".date('Y', strtotime('+1 years'));
		$playoffYear =  date('Y', strtotime('+1 years'));
	} 
	echo $season;
	// get regular season json stats	
	$json = file_get_contents($apiUrl."/".$season."-regular/cumulative_player_stats.json");
	$json = json_decode($json);
	// bind parameter function
	function bindParam($query, $pid, $lastName, $firstName, $jerseyNumber, $position, $height, $weight, $birthDate, $age, $birthCity, $birthCountry, $isRookie, $teamId, $city, $teamName, $abbreviation,
										 $gamesPlayed, $goals, $assists, $points, $hatTricks, $plusMinus, $shots, $shotPercentage, $penalties, $pim, $powerplayGoals, $powerplayAssists, $powerplayPoints, $shorthandedGoals,
										 $shorthandedAssists, $shorthandedPoints, $gamewinningGoals, $hits, $faceoffs, $faceoffWins, $faceoffLosses, $faceoffPercent, $season) {
		
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
		$query->bindParam(':gamesplayed', $gamesPlayed);
		$query->bindParam(':goals', $goals); 
		$query->bindParam(':assists', $assists);
		$query->bindParam(':points', $points);
		$query->bindParam(':hattricks', $hatTricks);
		$query->bindParam(':plusminus', $plusMinus);
		$query->bindParam(':shots', $shots);
		$query->bindParam(':shotpercentage', $shotPercentage);
		$query->bindParam(':penalties', $penalties);
		$query->bindParam(':pim', $pim);
		$query->bindParam(':powerplaygoals', $powerplayGoals);
		$query->bindParam(':powerplayassists', $powerplayAssists);
		$query->bindParam(':powerplaypoints', $powerplayPoints);
		$query->bindParam(':shorthandedgoals', $shorthandedGoals);
		$query->bindParam(':shorthandedassists', $shorthandedAssists);
		$query->bindParam(':shorthandedpoints', $shorthandedPoints);
		$query->bindParam(':gamewinninggoals', $gamewinningGoals);
		$query->bindParam(':hits', $hits);
		$query->bindParam(':faceoffs', $faceoffs);
		$query->bindParam(':faceoffwins', $faceoffWins);
		$query->bindParam(':faceofflosses', $faceoffLosses);
		$query->bindParam(':faceoffpercent', $faceoffPercent);
		$query->bindParam(':season', $season);  		
	}
	
	// iterate through json contents
	for($i=0; $i<count($json->cumulativeplayerstats->playerstatsentry); $i++) {
						
		//////////////////////////////////////////////////////////////////////////
		/////////////////////////// Player ///////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////
		
		$pid = $json->cumulativeplayerstats->playerstatsentry[$i]->player->ID;
		$lastName = $json->cumulativeplayerstats->playerstatsentry[$i]->player->LastName;
		$firstName = $json->cumulativeplayerstats->playerstatsentry[$i]->player->FirstName;	
		$jerseyNumber = $json->cumulativeplayerstats->playerstatsentry[$i]->player->JerseyNumber;
		$position = $json->cumulativeplayerstats->playerstatsentry[$i]->player->Position;		
		$height = $json->cumulativeplayerstats->playerstatsentry[$i]->player->Height;		
		$weight = $json->cumulativeplayerstats->playerstatsentry[$i]->player->Weight;		
		$birthDate = $json->cumulativeplayerstats->playerstatsentry[$i]->player->BirthDate;	
		$age = $json->cumulativeplayerstats->playerstatsentry[$i]->player->Age;	
		$birthCity = $json->cumulativeplayerstats->playerstatsentry[$i]->player->BirthCity;
		$birthCountry = $json->cumulativeplayerstats->playerstatsentry[$i]->player->BirthCountry;	
		$isRookie = $json->cumulativeplayerstats->playerstatsentry[$i]->player->IsRookie;
		
		////////////////////////////////////////////////////////////////////////////
		///////////////////////////////// Team /////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////
		
    $teamId = $json->cumulativeplayerstats->playerstatsentry[$i]->team->ID;	
		$city = $json->cumulativeplayerstats->playerstatsentry[$i]->team->City;		
		$teamName = $json->cumulativeplayerstats->playerstatsentry[$i]->team->Name;		
		$abbreviation = $json->cumulativeplayerstats->playerstatsentry[$i]->team->Abbreviation;

		////////////////////////////////////////////////////////////////////////////
	  ////////////////////////////// Stats ///////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////
		
		$gamesPlayed = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->GamesPlayed->{'#text'};
	  $goals = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Goals->{'#text'};
		$assists = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Assists->{'#text'};
	  $points = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Points->{'#text'};
	  $hatTricks = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->HatTricks->{'#text'};
	  $plusMinus = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PlusMinus->{'#text'};
	  $shots = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Shots->{'#text'};
	  $shotPercentage = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->ShotPercentage->{'#text'};
	  $penalties = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Penalties->{'#text'};
	  $pim = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PenaltyMinutes->{'#text'};
	  $powerplayGoals = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PowerplayGoals->{'#text'};
	  $powerplayAssists = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PowerplayAssists->{'#text'};
	  $powerplayPoints = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PowerplayPoints->{'#text'};
	  $shorthandedGoals = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->ShorthandedGoals->{'#text'};
		$shorthandedAssists = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->ShorthandedAssists->{'#text'};
		$shorthandedPoints = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->ShorthandedPoints->{'#text'};
		$gamewinningGoals = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->GameWinningGoals->{'#text'};
		$hits = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Hits->{'#text'};
		$faceoffs = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Faceoffs->{'#text'};
		$faceoffWins = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->FaceoffWins->{'#text'};
		$faceoffLosses = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->FaceoffLosses->{'#text'};
		$faceoffPercent = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->FaceoffPercent->{'#text'};
		
		$query = $db->prepare("INSERT OR IGNORE INTO stats ('pid','lastname','firstname','jerseynumber','position','height','weight','birthdate','age','birthcity','birthcountry','isrookie','teamid','city','teamname','abbreviation','gamesplayed','goals', 
													'assists','points','hattricks','plusminus','shots','shotpercentage','penalties','pim','powerplaygoals','powerplayassists','powerplaypoints','shorthandedgoals','shorthandedassists','shorthandedpoints',
													'gamewinninggoals','hits','faceoffs','faceoffwins','faceofflosses','faceoffpercent','season')
													
													VALUES (:pid, :lastname, :firstname, :jerseynumber, :position, :height, :weight, :birthdate, :age, :birthcity, :birthcountry, :isrookie, :teamid, :city, :teamname, :abbreviation, :gamesplayed, :goals,
																	:assists, :points, :hattricks, :plusminus, :shots, :shotpercentage, :penalties, :pim, :powerplaygoals, :powerplayassists, :powerplaypoints, :shorthandedgoals, :shorthandedassists, :shorthandedpoints,
																	:gamewinninggoals, :hits, :faceoffs, :faceoffwins, :faceofflosses, :faceoffpercent, :season)");
		
	  bindParam($query, $pid, $lastName, $firstName, $jerseyNumber, $position, $height, $weight, $birthDate, $age, $birthCity, $birthCountry, $isRookie, $teamId, $city, $teamName, $abbreviation,
						  $gamesPlayed, $goals, $assists, $points, $hatTricks, $plusMinus, $shots, $shotPercentage, $penalties, $pim, $powerplayGoals, $powerplayAssists, $powerplayPoints, $shorthandedGoals,
						  $shorthandedAssists, $shorthandedPoints, $gamewinningGoals, $hits, $faceoffs, $faceoffWins, $faceoffLosses, $faceoffPercent, $season);
		$query->execute();
		
		$query = $db->prepare("Update stats
													 SET pid=:pid, lastname=:lastname, firstname=:firstname, jerseynumber=:jerseynumber, position=:position, height=:height, weight=:weight, birthdate=:birthdate, age=:age, birthcity=:birthcity, birthcountry=:birthcountry, 
															 isrookie=:isrookie, teamid=:teamid, city=:city, teamname=:teamname, abbreviation=:abbreviation, gamesplayed=:gamesplayed, goals=:goals, assists=:assists, points=:points,
															 hattricks=:hattricks, plusminus=:plusminus, shots=:shots, shotpercentage=:shotpercentage, penalties=:penalties, pim=:pim, powerplaygoals=:powerplaygoals, powerplayassists=:powerplayassists,
															 powerplaypoints=:powerplaypoints, shorthandedgoals=:shorthandedgoals, shorthandedassists=:shorthandedassists, shorthandedpoints=:shorthandedpoints, gamewinninggoals=:gamewinninggoals, hits=:hits,
															 faceoffs=:faceoffs, faceoffwins=:faceoffwins, faceofflosses=:faceofflosses, faceoffpercent=:faceoffpercent, season=:season
													 WHERE pid=:pid AND season=:season"); 
				
	  bindParam($query, $pid, $lastName, $firstName, $jerseyNumber, $position, $height, $weight, $birthDate, $age, $birthCity, $birthCountry, $isRookie, $teamId, $city, $teamName, $abbreviation,
						  $gamesPlayed, $goals, $assists, $points, $hatTricks, $plusMinus, $shots, $shotPercentage, $penalties, $pim, $powerplayGoals, $powerplayAssists, $powerplayPoints, $shorthandedGoals,
						  $shorthandedAssists, $shorthandedPoints, $gamewinningGoals, $hits, $faceoffs, $faceoffWins, $faceoffLosses, $faceoffPercent, $season);
		$query->execute();
		
	}	
	
	$json = file_get_contents($apiUrl."/".$playoffYear."-playoff/cumulative_player_stats.json");
	$json = json_decode($json);
	
	for($i=0; $i<count($json->cumulativeplayerstats->playerstatsentry); $i++) {
						
		//////////////////////////////////////////////////////////////////////////
		/////////////////////////// Player ///////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////
		
		$pid = $json->cumulativeplayerstats->playerstatsentry[$i]->player->ID;
		$lastName = $json->cumulativeplayerstats->playerstatsentry[$i]->player->LastName;
		$firstName = $json->cumulativeplayerstats->playerstatsentry[$i]->player->FirstName;	
		$jerseyNumber = $json->cumulativeplayerstats->playerstatsentry[$i]->player->JerseyNumber;
		$position = $json->cumulativeplayerstats->playerstatsentry[$i]->player->Position;		
		$height = $json->cumulativeplayerstats->playerstatsentry[$i]->player->Height;		
		$weight = $json->cumulativeplayerstats->playerstatsentry[$i]->player->Weight;		
		$birthDate = $json->cumulativeplayerstats->playerstatsentry[$i]->player->BirthDate;	
		$age = $json->cumulativeplayerstats->playerstatsentry[$i]->player->Age;	
		$birthCity = $json->cumulativeplayerstats->playerstatsentry[$i]->player->BirthCity;
		$birthCountry = $json->cumulativeplayerstats->playerstatsentry[$i]->player->BirthCountry;	
		$isRookie = $json->cumulativeplayerstats->playerstatsentry[$i]->player->IsRookie;
		
		////////////////////////////////////////////////////////////////////////////
		///////////////////////////////// Team /////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////
		
    $teamId = $json->cumulativeplayerstats->playerstatsentry[$i]->team->ID;	
		$city = $json->cumulativeplayerstats->playerstatsentry[$i]->team->City;		
		$teamName = $json->cumulativeplayerstats->playerstatsentry[$i]->team->Name;		
		$abbreviation = $json->cumulativeplayerstats->playerstatsentry[$i]->team->Abbreviation;

		////////////////////////////////////////////////////////////////////////////
	  ////////////////////////////// Stats ///////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////
		
		$gamesPlayed = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->GamesPlayed->{'#text'};
	  $goals = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Goals->{'#text'};
		$assists = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Assists->{'#text'};
	  $points = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Points->{'#text'};
	  $hatTricks = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->HatTricks->{'#text'};
	  $plusMinus = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PlusMinus->{'#text'};
	  $shots = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Shots->{'#text'};
	  $shotPercentage = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->ShotPercentage->{'#text'};
	  $penalties = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Penalties->{'#text'};
	  $pim = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PenaltyMinutes->{'#text'};
	  $powerplayGoals = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PowerplayGoals->{'#text'};
	  $powerplayAssists = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PowerplayAssists->{'#text'};
	  $powerplayPoints = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->PowerplayPoints->{'#text'};
	  $shorthandedGoals = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->ShorthandedGoals->{'#text'};
		$shorthandedAssists = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->ShorthandedAssists->{'#text'};
		$shorthandedPoints = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->ShorthandedPoints->{'#text'};
		$gamewinningGoals = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->GameWinningGoals->{'#text'};
		$hits = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Hits->{'#text'};
		$faceoffs = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->Faceoffs->{'#text'};
		$faceoffWins = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->FaceoffWins->{'#text'};
		$faceoffLosses = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->FaceoffLosses->{'#text'};
		$faceoffPercent = $json->cumulativeplayerstats->playerstatsentry[$i]->stats->stats->FaceoffPercent->{'#text'};
		
		$query = $db->prepare("INSERT OR IGNORE INTO playoff_stats ('pid','lastname','firstname','jerseynumber','position','height','weight','birthdate','age','birthcity','birthcountry','isrookie','teamid','city','teamname','abbreviation','gamesplayed','goals', 
													'assists','points','hattricks','plusminus','shots','shotpercentage','penalties','pim','powerplaygoals','powerplayassists','powerplaypoints','shorthandedgoals','shorthandedassists','shorthandedpoints',
													'gamewinninggoals','hits','faceoffs','faceoffwins','faceofflosses','faceoffpercent','season')
													
													VALUES (:pid, :lastname, :firstname, :jerseynumber, :position, :height, :weight, :birthdate, :age, :birthcity, :birthcountry, :isrookie, :teamid, :city, :teamname, :abbreviation, :gamesplayed, :goals,
																	:assists, :points, :hattricks, :plusminus, :shots, :shotpercentage, :penalties, :pim, :powerplaygoals, :powerplayassists, :powerplaypoints, :shorthandedgoals, :shorthandedassists, :shorthandedpoints,
																	:gamewinninggoals, :hits, :faceoffs, :faceoffwins, :faceofflosses, :faceoffpercent, :season)");
		
	  bindParam($query, $pid, $lastName, $firstName, $jerseyNumber, $position, $height, $weight, $birthDate, $age, $birthCity, $birthCountry, $isRookie, $teamId, $city, $teamName, $abbreviation,
						  $gamesPlayed, $goals, $assists, $points, $hatTricks, $plusMinus, $shots, $shotPercentage, $penalties, $pim, $powerplayGoals, $powerplayAssists, $powerplayPoints, $shorthandedGoals,
						  $shorthandedAssists, $shorthandedPoints, $gamewinningGoals, $hits, $faceoffs, $faceoffWins, $faceoffLosses, $faceoffPercent, $season);
		$query->execute();
		
		$query = $db->prepare("Update playoff_stats
													 SET pid=:pid, lastname=:lastname, firstname=:firstname, jerseynumber=:jerseynumber, position=:position, height=:height, weight=:weight, birthdate=:birthdate, age=:age, birthcity=:birthcity, birthcountry=:birthcountry, 
															 isrookie=:isrookie, teamid=:teamid, city=:city, teamname=:teamname, abbreviation=:abbreviation, gamesplayed=:gamesplayed, goals=:goals, assists=:assists, points=:points,
															 hattricks=:hattricks, plusminus=:plusminus, shots=:shots, shotpercentage=:shotpercentage, penalties=:penalties, pim=:pim, powerplaygoals=:powerplaygoals, powerplayassists=:powerplayassists,
															 powerplaypoints=:powerplaypoints, shorthandedgoals=:shorthandedgoals, shorthandedassists=:shorthandedassists, shorthandedpoints=:shorthandedpoints, gamewinninggoals=:gamewinninggoals, hits=:hits,
															 faceoffs=:faceoffs, faceoffwins=:faceoffwins, faceofflosses=:faceofflosses, faceoffpercent=:faceoffpercent, season=:season
													 WHERE pid=:pid AND season=:season"); 
				
	  bindParam($query, $pid, $lastName, $firstName, $jerseyNumber, $position, $height, $weight, $birthDate, $age, $birthCity, $birthCountry, $isRookie, $teamId, $city, $teamName, $abbreviation,
						  $gamesPlayed, $goals, $assists, $points, $hatTricks, $plusMinus, $shots, $shotPercentage, $penalties, $pim, $powerplayGoals, $powerplayAssists, $powerplayPoints, $shorthandedGoals,
						  $shorthandedAssists, $shorthandedPoints, $gamewinningGoals, $hits, $faceoffs, $faceoffWins, $faceoffLosses, $faceoffPercent, $season);
		$query->execute();
		
	}	
	
?>
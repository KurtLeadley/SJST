<?php
  $configs = include('../config.php');
  $apiUrl = $configs->apiurl;
  $pdoUrl = $configs->pdo;
	try {   
			$db = new PDO($pdoUrl);  
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
		}   catch (Exception $e) {  
				echo "Error: Could not connect to database.  Please try again later.";
				exit;
			}
	/////////////////////////////////////////////////////////
	////// Getting the season by date  //////////////////////
	////////////////////////////////////////////////////////	
		
	$seasonYear = date('Y');
	$checkDate = strtotime(date('m/d/Y'));  //working
	$lowerBound = strtotime("1/1/$seasonYear"); // working       
	$upperBound =  strtotime("10/01/$seasonYear"); // working

	if (($checkDate >= $lowerBound) && ($checkDate <= $upperBound)) {	
		$season = date('Y', strtotime('-1 years'));
		$season .= "-".date('Y');
		$seasonEndYear = $seasonYear;

	} else {
		$season = date('Y');
		$season .= "-".date('Y', strtotime('+1 years'));	
		$seasonEndYear = (int)$seasonYear + 1; 
		$seasonEndYear = (string)$seasonEndYear;
	}	
    $dbEntryBound = strtotime("07/01/$seasonEndYear");
    $playoffSeason = substr($season, -4);
		/////////////////////////////////////////////////////////
		///////////////// Get JSON Standings ////////////////////
		////////////////////////////////////////////////////////
		if ($checkDate < $dbEntryBound) {	
			$url = $apiUrl."/v2.1/pull/nhl/".$season."-regular/games.json";
			$json = file_get_contents($url);
			$json = json_decode($json);

			for($i=0; $i<count($json->games); $i++)  {
			  $gid = $json->games[$i]->schedule->id;
        $dateTime =  $json->games[$i]->schedule->startTime;
				$date = date('Y-m-d', strtotime($dateTime));
				$time = date('h:i', strtotime($dateTime));
				$homeId = $json->games[$i]->schedule->homeTeam->id;
				$homeAbbrev = $json->games[$i]->schedule->homeTeam->abbreviation;
				$awayId = $json->games[$i]->schedule->awayTeam->id;
				$awayAbbrev = $json->games[$i]->schedule->awayTeam->abbreviation;
				$homeGoals = $json->games[$i]->score->homeScoreTotal;
				$homeShots = $json->games[$i]->score->homeShotsTotal;
				$awayGoals = $json->games[$i]->score->awayScoreTotal;
				$awayShots = $json->games[$i]->score->awayShotsTotal;
				$currentPeriod = $json->games[$i]->score->currentPeriod;
				$periods = $json->games[$i]->score->periods;
				$periods = sizeOf($periods);
				$status = $json->games[$i]->schedule->playedStatus;
								
				$query = $db->prepare("INSERT OR IGNORE INTO schedule ('gid','season','date','time','home_id','home_abbrev','away_id','away_abbrev','home_goals','home_shots','away_goals','away_shots', 'periods', 'status', 'current_period')
												 VALUES (:gid, :season, :date, :time, :homeId, :homeAbbrev, :awayId, :awayAbbrev, :homeGoals, :homeShots, :awayGoals, :awayShots, :periods, :status, :currentPeriod)");

				bindParam($query, $gid, $season, $date, $time, $homeId, $homeAbbrev, $awayId, $awayAbbrev, $homeGoals, $homeShots, $awayGoals, $awayShots, $periods, $status, $currentPeriod);
				$query->execute();
				
				$query = $db->prepare("Update schedule
										 SET gid=:gid, season=:season, date=:date, time=:time, home_id=:homeId, home_abbrev=:homeAbbrev, away_id=:awayId, away_abbrev=:awayAbbrev, home_goals=:homeGoals, home_shots=:homeShots, away_goals=:awayGoals, 
										 away_shots=:awayShots, periods=:periods, status=:status, current_period=:currentPeriod
										 WHERE gid=:gid AND season=:season"); 
						
				bindParam($query, $gid, $season, $date, $time, $homeId, $homeAbbrev, $awayId, $awayAbbrev, $homeGoals, $homeShots, $awayGoals, $awayShots, $periods, $status, $currentPeriod);
				$query->execute();
					
			}
		}
		/////////////////////////////////////////////////////////
		///////////////// Get JSON playoff Standings ////////////
		////////////////////////////////////////////////////////
		if ($checkDate < $dbEntryBound) {	
			echo $playoffSeason;
			$url = $apiUrl."/v2.1/pull/nhl/".$playoffSeason."-playoff/games.json";
			$json = file_get_contents($url);
			$json = json_decode($json);

			for($i=0; $i<count($json->games); $i++)  {
				$gid = $json->games[$i]->schedule->id;
       	$dateTime =  $json->games[$i]->schedule->startTime;
				$date = date('Y-m-d', strtotime($dateTime));
				$time = date('h:i', strtotime($dateTime));
				$homeId = $json->games[$i]->schedule->homeTeam->id;
				$homeAbbrev = $json->games[$i]->schedule->homeTeam->abbreviation;
				$awayId = $json->games[$i]->schedule->awayTeam->id;
				$awayAbbrev = $json->games[$i]->schedule->awayTeam->abbreviation;
				$homeGoals = $json->games[$i]->score->homeScoreTotal;
				$homeShots = $json->games[$i]->score->homeShotsTotal;
				$awayGoals = $json->games[$i]->score->awayScoreTotal;
				$awayShots = $json->games[$i]->score->awayShotsTotal;
				$currentPeriod = $json->games[$i]->score->currentPeriod;
				$periods = $json->games[$i]->score->periods;
				$periods = sizeOf($periods);
				$status = $json->games[$i]->schedule->playedStatus;
								
				$query = $db->prepare("INSERT OR IGNORE INTO playoff_schedule ('gid','season','date','time','home_id','home_abbrev','away_id','away_abbrev','home_goals','home_shots','away_goals','away_shots', 'periods', 'status', 'current_period')
												 VALUES (:gid, :season, :date, :time, :homeId, :homeAbbrev, :awayId, :awayAbbrev, :homeGoals, :homeShots, :awayGoals, :awayShots, :periods, :status, :currentPeriod)");

				bindParamPlayoff($query, $gid, $playoffSeason, $date, $time, $homeId, $homeAbbrev, $awayId, $awayAbbrev, $homeGoals, $homeShots, $awayGoals, $awayShots, $periods, $status, $currentPeriod);
				$query->execute();
				
				$query = $db->prepare("Update playoff_schedule
										 SET gid=:gid, season=:season, date=:date, time=:time, home_id=:homeId, home_abbrev=:homeAbbrev, away_id=:awayId, away_abbrev=:awayAbbrev, home_goals=:homeGoals, home_shots=:homeShots, away_goals=:awayGoals, 
										 away_shots=:awayShots, periods=:periods, status=:status, current_period=:currentPeriod
										 WHERE gid=:gid AND season=:season"); 
						
				bindParamPlayoff($query, $gid, $playoffSeason, $date, $time, $homeId, $homeAbbrev, $awayId, $awayAbbrev, $homeGoals, $homeShots, $awayGoals, $awayShots, $periods, $status, $currentPeriod);
				$query->execute();
					
			}
		}
		/////////////////////////////////////////////////////////
		///////////////// Bind Parameters Function /////////////
		////////////////////////////////////////////////////////
		
		function  bindParam($query, $gid, $season, $date, $time, $homeId, $homeAbbrev, $awayId, $awayAbbrev, $homeGoals, $homeShots, $awayGoals, $awayShots, $periods, $status, $currentPeriod) {
			$query->bindParam(':gid', $gid);
			$query->bindParam(':season', $season);
			$query->bindParam(':date', $date);
			$query->bindParam(':time', $time);
			$query->bindParam(':homeId', $homeId);
			$query->bindParam(':homeAbbrev', $homeAbbrev);
			$query->bindParam(':awayId', $awayId);
			$query->bindParam(':awayAbbrev', $awayAbbrev);
			$query->bindParam(':homeGoals', $homeGoals);
			$query->bindParam(':homeShots', $homeShots);
			$query->bindParam(':awayGoals', $awayGoals);
			$query->bindParam(':awayShots', $awayShots);
			$query->bindParam(':periods', $periods);
			$query->bindParam(':status', $status);
			$query->bindParam(':currentPeriod', $currentPeriod);
		}
		function  bindParamPlayoff($query, $gid, $playoffSeason, $date, $time, $homeId, $homeAbbrev, $awayId, $awayAbbrev, $homeGoals, $homeShots, $awayGoals, $awayShots, $periods, $status, $currentPeriod) {
			$query->bindParam(':gid', $gid);
			$query->bindParam(':season', $playoffSeason);
			$query->bindParam(':date', $date);
			$query->bindParam(':time', $time);
			$query->bindParam(':homeId', $homeId);
			$query->bindParam(':homeAbbrev', $homeAbbrev);
			$query->bindParam(':awayId', $awayId);
			$query->bindParam(':awayAbbrev', $awayAbbrev);
			$query->bindParam(':homeGoals', $homeGoals);
			$query->bindParam(':homeShots', $homeShots);
			$query->bindParam(':awayGoals', $awayGoals);
			$query->bindParam(':awayShots', $awayShots);
			$query->bindParam(':periods', $periods);
			$query->bindParam(':status', $status);
			$query->bindParam(':currentPeriod', $currentPeriod);
		}
?>
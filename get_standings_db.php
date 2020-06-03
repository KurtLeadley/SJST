<?php include 'header.php'; ?>  
<?php include 'news.php' ; ?>
<div id="content">
<?php
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
		
		/////////////////////////////////////////////////////////
		///////////////// Get JSON Standings ////////////////////
		////////////////////////////////////////////////////////
		if ($checkDate < $dbEntryBound) {	
			$url = $apiUrl."/v2.0/pull/nhl/".$season."-regular/standings.json";
			$json = file_get_contents($url);
			$json = json_decode($json);

			for($i=0; $i<count($json->teams); $i++)  {
			  $id = $json->teams[$i]->team->id;
				$city = $json->teams[$i]->team->city;
				$name = $json->teams[$i]->team->name;
				$abbreviation = $json->teams[$i]->team->abbreviation;
				$gamesPlayed = $json->teams[$i]->stats->gamesPlayed;
				$wins = $json->teams[$i]->stats->standings->wins;
				$losses = $json->teams[$i]->stats->standings->losses;
				$overtimeWins = $json->teams[$i]->stats->standings->overtimeWins;
			  $overtimeLosses = $json->teams[$i]->stats->standings->overtimeLosses;
				$points = $json->teams[$i]->stats->standings->points;
				
				$faceoffWins = $json->teams[$i]->stats->faceoffs->faceoffWins;
				$faceoffLosses = $json->teams[$i]->stats->faceoffs->faceoffLosses;
				$faceoffPercent = $json->teams[$i]->stats->faceoffs->faceoffPercent;
				
				$powerplays = $json->teams[$i]->stats->powerplay->powerplays;
				$powerplayGoals = $json->teams[$i]->stats->powerplay->powerplayGoals;
				$ppPercent =  ($powerplayGoals / $powerplays) * 100;
				$ppPercent = round($ppPercent,2);
				$penaltyKills = $json->teams[$i]->stats->powerplay->penaltyKills;
				$penaltyKillGoalsAllowed = $json->teams[$i]->stats->powerplay->penaltyKillGoalsAllowed;
				$pkPercent =  100-(($penaltyKillGoalsAllowed / $penaltyKills) * 100); 
				$pkPercent = round($pkPercent,2);
				$shGF = $json->teams[$i]->stats->powerplay->shorthandedGoalsFor;
				$shGA = $json->teams[$i]->stats->powerplay->shorthandedGoalsAgainst;
				
			  $gf = $json->teams[$i]->stats->miscellaneous->goalsFor;
				$ga = $json->teams[$i]->stats->miscellaneous->goalsAgainst;
				$shots = $json->teams[$i]->stats->miscellaneous->shots;
				$penalties= $json->teams[$i]->stats->miscellaneous->penalties;
				$pim = $json->teams[$i]->stats->miscellaneous->penaltyMinutes;
				$hits = $json->teams[$i]->stats->miscellaneous->hits;
				
				$leagueRank = $json->teams[$i]->overallRank->rank;
				$conferenceRank = $json->teams[$i]->conferenceRank->rank;
				$divisionRank = $json->teams[$i]->divisionRank->rank;
				$conference = $json->teams[$i]->conferenceRank->conferenceName;
				$division = $json->teams[$i]->divisionRank->divisionName;
				$playoffRank = $json->teams[$i]->playoffRank->rank;
									
				$query = $db->prepare("INSERT OR IGNORE INTO standings ('id','season','gp','wins','losses','otw','otl','pts','league_rank','conf_rank','division_rank','playoff_rank', 'conference', 'division')
												 VALUES (:id, :season, :gamesPlayed, :wins, :losses, :overtimeWins, :overtimeLosses, :points, :leagueRank, :conferenceRank, :divisionRank, :playoffRank, :conference, :division)");

				bindParam($query, $id, $season, $gamesPlayed, $wins, $losses, $overtimeWins, $overtimeLosses, $points, $leagueRank, $conferenceRank, $divisionRank, $playoffRank, $conference, $division);
				$query->execute();
				
				$query = $db->prepare("Update standings
										 SET id=:id, season=:season, gp=:gamesPlayed, wins=:wins, losses=:losses, otw=:overtimeLosses, otw=:overtimeWins, pts=:points, league_rank=:leagueRank, conf_rank=:conferenceRank, division_rank=:divisionRank, 
										 playoff_rank=:playoffRank, conference=:conference, division=:division
										 WHERE id=:id AND season=:season"); 
						
				bindParam($query, $id, $season, $gamesPlayed, $wins, $losses, $overtimeWins, $overtimeLosses, $points, $leagueRank, $conferenceRank, $divisionRank, $playoffRank, $conference, $division);
				$query->execute();

				$query = $db->prepare("INSERT OR IGNORE INTO team_stats ('id','season','fow','fol','fo_percent','pps','ppg','pp_percent','pks','pkga','pk_percent','shgf', 'shga', 'gf', 'ga','shots','penalties','pim','hits')
												 VALUES (:id, :season, :fow, :fol, :fo_percent, :pps, :ppg, :pp_percent, :pks, :pkga, :pk_percent, :shgf, :shga, :gf, :ga, :shots,:penalties,:pim,:hits)");

				bindParam_ts($query, $id, $season, $faceoffWins, $faceoffLosses, $faceoffPercent, $powerplays, $powerplayGoals, $ppPercent, $penaltyKills, $penaltyKillGoalsAllowed, $pkPercent, 
													$shGF, $shGA, $gf, $ga, $shots, $penalties, $pim, $hits);
				$query->execute();
				
				$query = $db->prepare("Update team_stats
										 SET id=:id, season=:season, fow=:fow, fol=:fol, fo_percent=:fo_percent, pps=:pps, ppg=:ppg, pp_percent=:pp_percent, pks=:pks, pkga=:pkga, pk_percent=:pk_percent, 
										 shgf=:shgf, shga=:shga, gf=:gf, ga=:ga, shots=:shots, penalties=:penalties, pim=:pim, hits=:hits
										 WHERE id=:id AND season=:season"); 
						
				bindParam_ts($query, $id, $season, $faceoffWins, $faceoffLosses, $faceoffPercent, $powerplays, $powerplayGoals, $ppPercent, $penaltyKills, $penaltyKillGoalsAllowed, $pkPercent, 
													$shGF, $shGA, $gf, $ga, $shots, $penalties, $pim, $hits);
				$query->execute();	
				
					
			}
		}
		/////////////////////////////////////////////////////////
		///////////////// Bind Parameters Function /////////////
		////////////////////////////////////////////////////////
		
		function bindParam($query, $id, $season, $gamesPlayed, $wins, $losses, $overtimeWins, $overtimeLosses, $points, $leagueRank, $conferenceRank, $divisionRank, $playoffRank, $conference, $division) {
			$query->bindParam(':id', $id);
			$query->bindParam(':season', $season);
			$query->bindParam(':gamesPlayed', $gamesPlayed);
			$query->bindParam(':wins', $wins);
			$query->bindParam(':losses', $losses);
			$query->bindParam(':overtimeWins', $overtimeWins);
			$query->bindParam(':overtimeLosses', $overtimeLosses);
			$query->bindParam(':points', $points);
			$query->bindParam(':leagueRank', $leagueRank);
			$query->bindParam(':conferenceRank', $conferenceRank);
			$query->bindParam(':divisionRank', $divisionRank);
			$query->bindParam(':playoffRank', $playoffRank);
			$query->bindParam(':conference', $conference);
			$query->bindParam(':division', $division);
		}
		
				
		function bindParam_ts($query, $id, $season, $faceoffWins, $faceoffLosses, $faceoffPercent, $powerplays, $powerplayGoals, $ppPercent, $penaltyKills, $penaltyKillGoalsAllowed, $pkPercent, 
													$shGF, $shGA, $gf, $ga, $shots, $penalties, $pim, $hits) {
			$query->bindParam(':id', $id);
			$query->bindParam(':season', $season);
			$query->bindParam(':fow', $faceoffWins);
			$query->bindParam(':fol', $faceoffLosses);
			$query->bindParam(':fo_percent', $faceoffPercent);
			$query->bindParam(':pps', $powerplays);
			$query->bindParam(':ppg', $powerplayGoals);
			$query->bindParam(':pp_percent', $ppPercent);
			$query->bindParam(':pks', $penaltyKills);
			$query->bindParam(':pkga', $penaltyKillGoalsAllowed);
			$query->bindParam(':pk_percent', $pkPercent);
			$query->bindParam(':shgf', $shGF);
			$query->bindParam(':shga', $shGA);
			$query->bindParam(':gf', $gf);
			$query->bindParam(':ga', $ga);
			$query->bindParam(':shots', $shots);
			$query->bindParam(':penalties', $penalties);
			$query->bindParam(':pim', $pim);
			$query->bindParam(':hits', $hits);
		}
?>
</div>
  <?php include 'pictures.php' ; ?> 
	<?php include 'footer.php' ; ?>
</div> 
</html>
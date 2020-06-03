<?php include 'modules/header.php'; ?>  
	<div id="container">
	<?php include 'modules/news.php' ; ?>
	<?php
  $configs = include('../config.php');
  $pdoUrl = $configs->pdo;
	try {   
			$db = new PDO($pdoUrl);  
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
		} catch (Exception $e) {  
				echo "Error: Could not connect to database.  Please try again later.";
				exit;
			}
	// get season
	if (isset($_GET['resultsYear'])) {                 
		$season = $_GET['resultsYear'];
	} else {
			// if no season declared, find out what season it is in this format 2018-2019
			$year = date('Y');
			$checkDate = date('m/d/Y');
			$checkDate=date('m/d/Y', strtotime($checkDate));
			$lowerBound = date('m/d/Y', strtotime("01/01/$year"));
			$upperBound = date('m/d/Y', strtotime("10/01/$year"));
      // if today is greater than or equal to January 1st and less than October 1st, season = last year dash todays year
			if (($checkDate >= $lowerBound) && ($checkDate < $upperBound)) {	
				$season = date('Y', strtotime('-1 years'));
				$season .= "-".date('Y');
			// else season = this year dash next year
			} else {
				$season = date('Y');
				$season .= "-".date('Y', strtotime('+1 years'));  
			} 
		}
		$playoffSeason = substr($season, -4);
		if (!isset($_GET['resultsTeam'])) {
			$resultsTeam = 'SJS';
		} else {
			$resultsTeam = $_GET['resultsTeam'];
		}
		if (!isset($_GET['resultsYear'])) {
			$resultsYear = $season;
		}	else {
			$resultsYear = $_GET['resultsYear'];
		}
	?>
		<div id="content">
			<h2>Schedule and Results</h2>
			<form action= "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" id="search">
				<select name='resultsYear' id='resultsYear' class='dropDown' onchange='this.form.submit()'>
					<option <?php if ($_GET['resultsYear'] == '2019-2020') { ?>selected="true" <?php }; ?>value="2019-2020">2019-2020</option>
					<option <?php if ($_GET['resultsYear'] == '2018-2019') { ?>selected="true" <?php }; ?>value="2018-2019">2018-2019</option>
					<option <?php if ($_GET['resultsYear'] == '2017-2018') { ?>selected="true" <?php }; ?>value="2017-2018">2017-2018</option>
					<option <?php if ($_GET['resultsYear'] == '2016-2017') { ?>selected="true" <?php }; ?>value="2016-2017">2016-2017</option>
					<option <?php if ($_GET['resultsYear'] == '2015-2016') { ?>selected="true" <?php }; ?>value="2015-2016">2015-2016</option>
					<option <?php if ($_GET['resultsYear'] == '2014-2015') { ?>selected="true" <?php }; ?>value="2014-2015">2014-2015</option>
					<option <?php if ($_GET['resultsYear'] == '2013-2014') { ?>selected="true" <?php }; ?>value="2013-2014">2013-2014</option>
				</select>
				<select name='resultsTeam' id='resultsTeam' class='dropDown' onchange='this.form.submit()'>
					<option <?php if ($_GET['resultsTeam'] == 'ANA') { ?>selected="true" <?php }; ?>value="ANA">Anaheim</option>
					<option <?php if ($_GET['resultsTeam'] == 'ARI') { ?>selected="true" <?php }; ?>value="ARI">Arizona</option>
					<option <?php if ($_GET['resultsTeam'] == 'BOS') { ?>selected="true" <?php }; ?>value="BOS">Boston</option>
					<option <?php if ($_GET['resultsTeam'] == 'BUF') { ?>selected="true" <?php }; ?>value="BUF">Buffalo</option>
					<option <?php if ($_GET['resultsTeam'] == 'CAR') { ?>selected="true" <?php }; ?>value="CAR">Carolina</option>
					<option <?php if ($_GET['resultsTeam'] == 'CBJ') { ?>selected="true" <?php }; ?>value="CBJ">Columbus</option>
					<option <?php if ($_GET['resultsTeam'] == 'CGY') { ?>selected="true" <?php }; ?>value="CGY">Calgary</option>
					<option <?php if ($_GET['resultsTeam'] == 'CHI') { ?>selected="true" <?php }; ?>value="CHI">Chicago</option>
					<option <?php if ($_GET['resultsTeam'] == 'COL') { ?>selected="true" <?php }; ?>value="COL">Colorado</option>
					<option <?php if ($_GET['resultsTeam'] == 'DAL') { ?>selected="true" <?php }; ?>value="DAL">Dallas</option>
					<option <?php if ($_GET['resultsTeam'] == 'DET') { ?>selected="true" <?php }; ?>value="DET">Detroit</option>
					<option <?php if ($_GET['resultsTeam'] == 'EDM') { ?>selected="true" <?php }; ?>value="EDM">Edmonton</option>
					<option <?php if ($_GET['resultsTeam'] == 'FLO') { ?>selected="true" <?php }; ?>value="FLO">Florida</option>
					<option <?php if ($_GET['resultsTeam'] == 'LAK') { ?>selected="true" <?php }; ?>value="LAK">Los Angeles</option>
					<option <?php if ($_GET['resultsTeam'] == 'MIN') { ?>selected="true" <?php }; ?>value="MIN">Minnesota</option>
					<option <?php if ($_GET['resultsTeam'] == 'MTL') { ?>selected="true" <?php }; ?>value="MTL">Montreal</option>
					<option <?php if ($_GET['resultsTeam'] == 'NJD') { ?>selected="true" <?php }; ?>value="NJD">New Jersey</option>
					<option <?php if ($_GET['resultsTeam'] == 'NSH') { ?>selected="true" <?php }; ?>value="NSH">Nashville</option>
					<option <?php if ($_GET['resultsTeam'] == 'NYI') { ?>selected="true" <?php }; ?>value="NYI">NYI</option>
					<option <?php if ($_GET['resultsTeam'] == 'NYR') { ?>selected="true" <?php }; ?>value="NYR">NYR</option>
					<option <?php if ($_GET['resultsTeam'] == 'OTT') { ?>selected="true" <?php }; ?>value="OTT">Ottawa</option>
					<option <?php if ($_GET['resultsTeam'] == 'PHI') { ?>selected="true" <?php }; ?>value="PHI">Philadelphia</option>
					<option <?php if ($_GET['resultsTeam'] == 'PIT') { ?>selected="true" <?php }; ?>value="PIT">Pittsburgh</option>
					<option <?php if (($_GET['resultsTeam'] == 'SJS') || !isset($_GET['resultsTeam'])) { ?>selected="true" <?php }; ?>value="SJS">San Jose</option>
					<option <?php if ($_GET['resultsTeam'] == 'STL') { ?>selected="true" <?php }; ?>value="STL">St Louis</option>
					<option <?php if ($_GET['resultsTeam'] == 'TBL') { ?>selected="true" <?php }; ?>value="TBL">Tampa Bay</option>
					<option <?php if ($_GET['resultsTeam'] == 'TOR') { ?>selected="true" <?php }; ?>value="TOR">Toronto</option>
					<option <?php if ($_GET['resultsTeam'] == 'VAN') { ?>selected="true" <?php }; ?>value="VAN">Vancouver</option>
					<option <?php if ($_GET['resultsTeam'] == 'VGK') { ?>selected="true" <?php }; ?>value="VGK">Vegas</option>
					<option <?php if ($_GET['resultsTeam'] == 'WPJ') { ?>selected="true" <?php }; ?>value="WPJ">Winnipeg</option>
					<option <?php if ($_GET['resultsTeam'] == 'WSH') { ?>selected="true" <?php }; ?>value="WSH">Washington</option>
				</select>
		  </form>
		<?php 

			$query =$db->prepare("SELECT s.date,s.time,s.home_id,s.away_id,s.home_abbrev,s.away_abbrev,s.home_goals,s.away_goals,s.periods,s.status, t.teamid, t.logo 
														FROM schedule s
														JOIN teams t 
														WHERE s.home_abbrev=:home_abbrev AND s.season=:season	AND s.home_id = t.teamid
														OR s.away_abbrev=:away_abbrev AND s.season=:season AND s.away_id = t.teamid");
			$query->bindParam(':season', $season);
			$query->bindParam(':home_abbrev', $resultsTeam);
			$query->bindParam(':away_abbrev', $resultsTeam);
			$query->execute();
			$result = $query->fetchAll();
			echoTable($result,$resultsTeam, $db);
			
			function echoTable($result, $resultsTeam, $db) {
				echo "<table class='table sortable'>";	
				echo "<tr class='TableHeaders'>";
				echo "<td width='15%'>Date</td>";
				echo "<td class='hover'>Time</td>";
				echo "<td class='hover'>Opponent</td>";
				echo "<td class='hover'>Result</td>";
				echo "<td class='hover'>W-L-OTL</td>";
				echo "</tr>";	
				
				$wins = 0;
				$losses = 0;
				$otl = 0;
				
				foreach ($result as $row) {
					$date = $row['date'];
					$date = date_create($date);
					$date = date_format($date,"D, M d");
					$time = $row['time'];
					$awayAbbrev = $row['away_abbrev'];
					$homeAbbrev = $row['home_abbrev'];
					$homeScore = $row['home_goals'];
					$awayScore = $row['away_goals'];
					$status = $row['status'];
					$periods = $row['periods'];
					$logo = $row['logo'];

					if ($awayAbbrev != $resultsTeam) {
						$opponent = $awayAbbrev;
						$opponentScore = $awayScore;
						
					  $query =$db->prepare("SELECT logo FROM teams WHERE teamAcronym =:awayAbbrev");
						$query->bindParam(':awayAbbrev', $awayAbbrev);
						$query->execute();
						$result = $query->fetchAll();
						foreach ($result as $row) {
						  $opponentLogo = $row['logo'];
						}
						
						$teamScore = $homeScore;
						$homeOrAway = 'vs';
					} else {
						$opponent = $homeAbbrev;
						$opponentScore = $homeScore;
						
					  $query =$db->prepare("SELECT logo FROM teams WHERE teamAcronym =:homeAbbrev");
						$query->bindParam(':homeAbbrev', $homeAbbrev);
						$query->execute();
						$result = $query->fetchAll();
						foreach ($result as $row) {
						  $opponentLogo = $row['logo'];
						}
						
						$teamScore = $awayScore;
						$homeOrAway = "@";
					}
					if (($teamScore > $opponentScore) && ($status == 'COMPLETED')) {
						$teamResult = "W";
						$cssColor = 'green';
						$wins++;						
					}
				  if (($teamScore < $opponentScore) && ($status == 'COMPLETED')) {
						$teamResult = "L";
						$cssColor = 'red';
					}
					if (($teamScore < $opponentScore) && ($periods == 3)) {
						$losses++;
					}
					if (($teamScore < $opponentScore) && ($periods > 3)) {
						$otl++;
					}
				  if ($status == 'UNPLAYED') {
						$teamResult = "TBD";						
						$cssColor = '#307D7E';
					}
					echo "<tr>";
						echo "<td>";	 
						echo $date;  
						echo "</td><td>";	 
						echo $time.' PM';
						echo "</td><td>";	 
						echo $homeOrAway."<img class='logo' src='$opponentLogo' height='25px'>".$opponent;
						echo "</td><td>";
						echo "<span style='color:$cssColor'>".$teamResult."</span>&nbsp&nbsp".$awayScore.'-'.$homeScore;
						echo "</td><td>";
						echo $wins.'-'.$losses.'-'.$otl;
						echo "</td>";
				 echo "</tr>";
				}
				echo "</table>";
			}	
			///////////////////////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////  PLAYOFFS   //////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////////
			
			$query =$db->prepare("SELECT s.date,s.time,s.home_id,s.away_id,s.home_abbrev,s.away_abbrev,s.home_goals,s.away_goals,s.periods,s.status, t.teamid, t.logo 
														FROM playoff_schedule s
														JOIN teams t 
														WHERE s.home_abbrev=:home_abbrev AND s.season=:season	AND s.home_id = t.teamid
														OR s.away_abbrev=:away_abbrev AND s.season=:season AND s.away_id = t.teamid");
			$query->bindParam(':season', $playoffSeason);
			$query->bindParam(':home_abbrev', $resultsTeam);
			$query->bindParam(':away_abbrev', $resultsTeam);
			$query->execute();
			$result = $query->fetchAll();
			if ($result) {
				echo "<h3>Playoff Schedule</h3>";
				echoTable2($result,$resultsTeam, $db);				
			}
			function echoTable2($result, $resultsTeam, $db) {
				echo "<table class='table sortable'>";	
				echo "<tr class='TableHeaders'>";
				echo "<td width='15%'>Date</td>";
				echo "<td class='hover'>Time</td>";
				echo "<td class='hover'>Opponent</td>";
				echo "<td class='hover'>Result</td>";
				echo "<td class='hover'>W-L-OTL</td>";
				echo "</tr>";	
				
				$wins = 0;
				$losses = 0;
				$otl = 0;
				
				foreach ($result as $row) {
					$date = $row['date'];
					$date = date_create($date);
					$date = date_format($date,"D, M d");
					$time = $row['time'];
					$awayAbbrev = $row['away_abbrev'];
					$homeAbbrev = $row['home_abbrev'];
					$homeScore = $row['home_goals'];
					$awayScore = $row['away_goals'];
					$status = $row['status'];
					$periods = $row['periods'];
					$logo = $row['logo'];

					if ($awayAbbrev != $resultsTeam) {
						$opponent = $awayAbbrev;
						$opponentScore = $awayScore;
						
						$query =$db->prepare("SELECT logo FROM teams WHERE teamAcronym =:awayAbbrev");
						$query->bindParam(':awayAbbrev', $awayAbbrev);
						$query->execute();
						$result = $query->fetchAll();
						foreach ($result as $row) {
							$opponentLogo = $row['logo'];
						}
						
						$teamScore = $homeScore;
						$homeOrAway = 'vs';
					} else {
						$opponent = $homeAbbrev;
						$opponentScore = $homeScore;
						
						$query =$db->prepare("SELECT logo FROM teams WHERE teamAcronym =:homeAbbrev");
						$query->bindParam(':homeAbbrev', $homeAbbrev);
						$query->execute();
						$result = $query->fetchAll();
						foreach ($result as $row) {
							$opponentLogo = $row['logo'];
						}
						
						$teamScore = $awayScore;
						$homeOrAway = "@";
					}
					if (($teamScore > $opponentScore) && ($status == 'COMPLETED')) {
						$teamResult = "W";
						$cssColor = 'green';
						$wins++;						
					}
					if (($teamScore < $opponentScore) && ($status == 'COMPLETED')) {
						$teamResult = "L";
						$cssColor = 'red';
					}
					if (($teamScore < $opponentScore) && ($periods == 3)) {
						$losses++;
					}
					if (($teamScore < $opponentScore) && ($periods > 3)) {
						$otl++;
					}
					if ($status == 'UNPLAYED') {
						$teamResult = "TBD";						
						$cssColor = '#307D7E';
					}
					echo "<tr>";
						echo "<td>";	 
						echo $date;  
						echo "</td><td>";	 
						echo $time.' PM';
						echo "</td><td>";	 
						echo $homeOrAway."<img class='logo' src='$opponentLogo' height='25px'>".$opponent;
						echo "</td><td>";
						echo "<span style='color:$cssColor'>".$teamResult."</span>&nbsp&nbsp".$awayScore.'-'.$homeScore;
						echo "</td><td>";
						echo $wins.'-'.$losses.'-'.$otl;
						echo "</td>";
				 echo "</tr>";
				}
				echo "</table>";
			}
		?>
	</div>
		<?php include 'modules/pictures.php' ; ?>
	<?php include 'modules/footer.php' ; ?>
	</div>
	</div>  
</html>
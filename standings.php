<?php include 'modules/header.php'; ?>  
<?php include 'modules/news.php' ; ?>
<div id="content">
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
	if (isset($_GET['standingsYear'])) {                 
		$season = $_GET['standingsYear'];
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
		$standingsType = $_GET['standingsType'];
	  echo "<h2>Standings</h3>";
?>
	<form action= "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" id="search">
	  <select  name="standingsYear" id="standingsYear" class="dropDown" onchange='this.form.submit()'>
			<option <?php if (($_GET['standingsYear'] == '2019-2020') || !isset($_GET['standingsYear'])) { ?>selected="true" <?php }; ?>value="2019-2020">2019-2020</option>
			<option <?php if ($_GET['standingsYear'] == '2018-2019') { ?>selected="true" <?php }; ?>value="2018-2019">2018-2019</option>
			<option <?php if ($_GET['standingsYear'] == '2017-2018') { ?>selected="true" <?php }; ?>value="2017-2018">2017-2018</option>
			<option <?php if ($_GET['standingsYear'] == '2016-2017') { ?>selected="true" <?php }; ?>value="2016-2017">2016-2017</option>
			<option <?php if ($_GET['standingsYear'] == '2015-2016') { ?>selected="true" <?php }; ?>value="2015-2016">2015-2016</option>
			<option <?php if ($_GET['standingsYear'] == '2014-2015') { ?>selected="true" <?php }; ?>value="2014-2015">2014-2015</option>
			<option <?php if ($_GET['standingsYear'] == '2013-2014') { ?>selected="true" <?php }; ?>value="2013-2014">2013-2014</option>
			<option <?php if ($_GET['standingsYear'] == '2012-2013') { ?>selected="true" <?php }; ?>value="2012-2013">2012-2013</option>
			<option <?php if ($_GET['standingsYear'] == '2011-2012') { ?>selected="true" <?php }; ?>value="2011-2012">2011-2012</option>
			<option <?php if ($_GET['standingsYear'] == '2010-2011') { ?>selected="true" <?php }; ?>value="2010-2011">2010-2011</option>
			<option <?php if ($_GET['standingsYear'] == '2009-2010') { ?>selected="true" <?php }; ?>value="2009-2010">2009-2010</option>
			<option <?php if ($_GET['standingsYear'] == '2008-2009') { ?>selected="true" <?php }; ?>value="2008-2009">2008-2009</option>
			<option <?php if ($_GET['standingsYear'] == '2007-2008') { ?>selected="true" <?php }; ?>value="2007-2008">2007-2008</option>
		</select>
		<select  name="standingsType" id="standingsType" class="dropDown" onchange='this.form.submit()'>
			<option <?php if ($_GET['standingsType'] == 'conference') { ?>selected="true" <?php }; ?> value="conference">Conference</option>
			<option <?php if ($_GET['standingsType'] == 'league') { ?>selected="true" <?php }; ?> value="league">League</option>
			<option <?php if ($_GET['standingsType'] == 'division') { ?>selected="true" <?php }; ?>value="division">Division</option>
			<option <?php if (($_GET['standingsType'] == 'wildcard') || !isset($_GET['standingsType'])) { ?>selected="true" <?php }; ?>value="wildcard">Wildcard</option>
		</select>
	</form>		
<?php

	/////////////////////////////////////////////////////////
	////////////// Default to WildCard Standings ////////////
	////////////////////////////////////////////////////////
	
	if (($standingsType == 'wildcard') || (!isset($_GET['standingsType']))){              

		/////////////////////////////////////////////////////////
		////////////// Eastern Wildcard Standings ///////////////
		////////////////////////////////////////////////////////	
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga, s.division_rank 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id 
													WHERE division ='Atlantic' AND s.season=:season AND ts.season=:season
													ORDER BY s.division_rank ASC
													LIMIT 3");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();
		echo "<h3>Atlantic Division</h3>";
		echoTable($result);
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga, s.division_rank 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id 
													WHERE division ='Metropolitan' AND s.season=:season AND ts.season=:season
													ORDER BY s.division_rank ASC
													LIMIT 3");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();
		echo "<h3>Metropolitan Division</h3>";
		echoTable($result);
			
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga, s.division_rank 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id 
													WHERE conference ='Eastern' AND s.season=:season AND division_rank > 3 AND ts.season=:season
													ORDER BY s.pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();
		echo "<h3>Eastern Wildcard</h3>";
		echoTable($result);
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga, s.division_rank 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id 
													WHERE division ='Central' AND s.season=:season AND ts.season=:season
													ORDER BY s.division_rank ASC
													LIMIT 3");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();
		echo "<h3>Central Division</h3>";
		echoTable($result);
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga, s.division_rank 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id 
													WHERE division ='Pacific' AND s.season=:season AND ts.season=:season
													ORDER BY s.division_rank ASC
													LIMIT 3");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();
		echo "<h3>Pacific Division</h3>";
		echoTable($result);
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga, s.division_rank 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id 
													WHERE conference ='Western' AND s.season=:season AND division_rank > 3 AND ts.season=:season
													ORDER BY s.pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();
		echo "<h3>Western Wildcard</h3>";
		echoTable($result);		
	}		
	/////////////////////////////////////////////////////////
	////////////// Conference Standings /////////////////////
	////////////////////////////////////////////////////////

	if ( $standingsType == 'conference') {
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id
													WHERE s.conference='Eastern' and s.season=:season AND ts.season=:season
													ORDER BY pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();			
		echo "<h3>Eastern Conference</h3>";
		echoTable($result);
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id
													WHERE s.conference='Western' and s.season=:season AND ts.season=:season
													ORDER BY pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();			
		echo "<h3>Western Conference</h3>";
		echoTable($result);
	}
	/////////////////////////////////////////////////////////
	///////////////// Whole League  ////////////////////////
	////////////////////////////////////////////////////////	

	if ( $standingsType == 'league') {
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id
													WHERE s.season=:season AND ts.season=:season
													ORDER BY pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();			
		echo "<h3>League Standings</h3>";
		echoTable($result);

	}
				
	/////////////////////////////////////////////////////////
	///////////////// By Division  /////////////////////////
	////////////////////////////////////////////////////////	

	if ( $standingsType == 'division' ) {
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id
													WHERE s.division='Atlantic' and s.season=:season AND ts.season=:season
													ORDER BY pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();	
		echo "<h3>Atlantic</h3>";
		echoTable($result);
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id
													WHERE s.division='Metropolitan' and s.season=:season AND ts.season=:season
													ORDER BY pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();	
		echo "<h3>Metropolitan</h3>";
		echoTable($result);
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id
													WHERE s.division='Central' and s.season=:season AND ts.season=:season
													ORDER BY pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();	
		echo "<h3>Central</h3>";
		echoTable($result);
		
		$query =$db->prepare("SELECT s.id, s.gp, s.wins, s.losses, s.otw, s.otl, s.pts, t.teamid, t.team_name, t.teamAcronym, ts.id, ts.gf, ts.ga 
													FROM standings s 
													JOIN teams t ON s.id = t.teamid
													JOIN team_stats ts ON s.id = ts.id
													WHERE s.division='Pacific' and s.season=:season AND ts.season=:season
													ORDER BY pts DESC");
		$query->bindParam(':season', $season);
		$query->execute();
		$result = $query->fetchAll();	
		echo "<h3>Pacific</h3>";
		echoTable($result);
	}
	
	function echoTable($result) {
		echo "<table class='table sortable'>";	
		echo "<tr class='TableHeaders'>";
		echo "<td width='15%'>Team</td>";
		echo "<td class='hover'>GP</td>";
		echo "<td class='hover'>W</td>";
		echo "<td class='hover'>L</td>";
		echo "<td class='hover'>OTL</td>";
		echo "<td class='hover'>Pts</td>";
		echo "<td class='hover'>GF</td>";
		echo "<td class='hover'>GA</td>";
		echo "<td class='hover'>+/-</td>";
		echo "</tr>";	
		
		foreach ($result as $row) {
			echo "<tr>";
				echo "<td>";
				echo stripslashes($row['team_name']);
				echo "</td><td>";
				echo stripslashes($row['gp']);
				echo "</td><td>";						
				echo stripslashes($row['wins']);
				echo "</td><td>";	 
				echo stripslashes($row['losses']);  
				echo "</td><td>";	 
				echo stripslashes($row['otl']);	
				echo "</td><td>";	 
				echo stripslashes($row['pts']);
				echo "</td><td>";
				echo stripslashes($row['gf']);
				echo "</td><td>";
				echo stripslashes($row['ga']);
				echo "</td><td>";
				echo (($row['gf'])-($row['ga']));
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
</html>
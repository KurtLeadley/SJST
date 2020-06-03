<?php
  ini_set('error_reporting', E_ALL);
  $configs = include('../config.php');
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
	$upperBound = date('m/d/Y', strtotime("10/03/$year"));
		
	if (($checkDate >= $lowerBound) && ($checkDate < $upperBound)) {	
		$thisSeason = date('Y', strtotime('-1 years'));
		$thisSeason .= "-".date('Y');
		$thisPlayoffYear = date('Y');
	} else {
		$thisSeason = date('Y');
		$thisSeason .= "-".date('Y', strtotime('+1 years'));
		$thisPlayoffYear =  date('Y', strtotime('+1 years'));
	} 
	 
	if (isset($_GET['rosterYear'])) {
		$season = $_GET['rosterYear'];
			
	} else {
		$season = $thisSeason;  
	}
function echoTable($result) {
	echo "<table class='table sortable'>";	
		echo "<tr class='TableHeaders'>";
		echo "<td class='hover' width='5%'>#</td>";
		echo "<td class='hover' width='10%'>Pos</td>";
		echo "<td class='hover'>Name</td>";
		echo "<td class='hover' width='10%'>Ht</td>";
		echo "<td class='hover' width='10%'>Wt</td>";
		echo "<td class='hover' width='10%'>Age</td>";
		echo "<td class='hover'>Birth Place</td>";
		echo "</tr>";	
		
		foreach ($result as $row) {
			echo "<tr>";
				echo "<td>";
				echo stripslashes($row['jerseynumber']); 
				echo "</td><td>";
				echo stripslashes($row['position']);
				echo "</td><td>";	 
				echo stripslashes($row['firstname'])." ".stripslashes($row['lastname']);  
				echo "</td>";
				echo "<td>";
				echo stripslashes($row['height']); 
				echo "</td><td>";
				echo stripslashes($row['weight']);
				echo "</td><td>";	
				echo stripslashes($row['age']); 
				echo "</td><td>";		
				echo stripslashes($row['birthcity'])." ".stripslashes($row['birthcountry']);  
				echo "</td>";
		 echo "</tr>";
		}
	echo "</table>";   
}
?>
<?php include 'modules/header.php'; ?>  
	<div id="container">
		<?php include 'modules/news.php' ; ?>		
			<div id="content"> 
			<h2>Roster</h2>
				<form action= "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" id="search">
					<select name='rosterYear' id='rosterYear' class='dropDown' onchange='this.form.submit()'>
						<option <?php if (($_GET['rosterYear'] == $thisSeason) || !isset($_GET['rosterYear'])) { ?>selected="true" <?php }; ?>value="<?php echo $thisSeason?>"><?php echo $thisSeason?></option>
						<option <?php if ($_GET['rosterYear'] == '2018-2019') { ?>selected="true" <?php }; ?>value="2018-2019">2018-2019</option>
						<option <?php if ($_GET['rosterYear'] == '2017-2018') { ?>selected="true" <?php }; ?>value="2017-2018">2017-2018</option>
					</select>
					<select name='rosterTeam' id='rosterTeam' class='dropDown' onchange='this.form.submit()'>
						<option <?php if ($_GET['rosterTeam'] == '29') { ?>selected="true" <?php }; ?>value="29">Anaheim</option>
						<option <?php if ($_GET['rosterTeam'] == '30') { ?>selected="true" <?php }; ?>value="30">Arizona</option>
						<option <?php if ($_GET['rosterTeam'] == '11') { ?>selected="true" <?php }; ?>value="11">Boston</option>
						<option <?php if ($_GET['rosterTeam'] == '15') { ?>selected="true" <?php }; ?>value="15">Buffalo</option>
						<option <?php if ($_GET['rosterTeam'] == '3') { ?>selected="true" <?php }; ?>value="3">Carolina</option>
						<option <?php if ($_GET['rosterTeam'] == '19') { ?>selected="true" <?php }; ?>value="19">Columbus</option>
						<option <?php if ($_GET['rosterTeam'] == '23') { ?>selected="true" <?php }; ?>value="23">Calgary</option>
						<option <?php if ($_GET['rosterTeam'] == '20') { ?>selected="true" <?php }; ?>value="20">Chicago</option>
						<option <?php if ($_GET['rosterTeam'] == '22') { ?>selected="true" <?php }; ?>value="22">Colorado</option>
						<option <?php if ($_GET['rosterTeam'] == '27') { ?>selected="true" <?php }; ?>value="27">Dallas</option>
						<option <?php if ($_GET['rosterTeam'] == '16') { ?>selected="true" <?php }; ?>value="16">Detroit</option>
						<option <?php if ($_GET['rosterTeam'] == '24') { ?>selected="true" <?php }; ?>value="24">Edmonton</option>
						<option <?php if ($_GET['rosterTeam'] == '4') { ?>selected="true" <?php }; ?>value="4">Florida</option>
						<option <?php if ($_GET['rosterTeam'] == '28') { ?>selected="true" <?php }; ?>value="28">Los Angeles</option>
						<option <?php if ($_GET['rosterTeam'] == '25') { ?>selected="true" <?php }; ?>value="25">Minnesota</option>
						<option <?php if ($_GET['rosterTeam'] == '14') { ?>selected="true" <?php }; ?>value="14">Montreal</option>
						<option <?php if ($_GET['rosterTeam'] == '7') { ?>selected="true" <?php }; ?>value="7">New Jersey</option>
						<option <?php if ($_GET['rosterTeam'] == '18') { ?>selected="true" <?php }; ?>value="18">Nashville</option>
						<option <?php if ($_GET['rosterTeam'] == '9') { ?>selected="true" <?php }; ?>value="9">NYI</option>
						<option <?php if ($_GET['rosterTeam'] == '8') { ?>selected="true" <?php }; ?>value="8">NYR</option>
						<option <?php if ($_GET['rosterTeam'] == '13') { ?>selected="true" <?php }; ?>value="13">Ottawa</option>
						<option <?php if ($_GET['rosterTeam'] == '6') { ?>selected="true" <?php }; ?>value="6">Philadelphia</option>
						<option <?php if ($_GET['rosterTeam'] == '10') { ?>selected="true" <?php }; ?>value="10">Pittsburgh</option>
						<option <?php if (($_GET['rosterTeam'] == '26') || !isset($_GET['rosterTeam'])) { ?>selected="true" <?php }; ?>value="26">San Jose</option>
						<option <?php if ($_GET['rosterTeam'] == '17') { ?>selected="true" <?php }; ?>value="17">St Louis</option>
						<option <?php if ($_GET['rosterTeam'] == '1') { ?>selected="true" <?php }; ?>value="1">Tampa Bay</option>
						<option <?php if ($_GET['rosterTeam'] == '12') { ?>selected="true" <?php }; ?>value="12">Toronto</option>
						<option <?php if ($_GET['rosterTeam'] == '21') { ?>selected="true" <?php }; ?>value="21">Vancouver</option>
						<option <?php if ($_GET['rosterTeam'] == '142') { ?>selected="true" <?php }; ?>value="142">Vegas</option>
						<option <?php if ($_GET['rosterTeam'] == '47') { ?>selected="true" <?php }; ?>value="47">Winnipeg</option>
						<option <?php if ($_GET['rosterTeam'] == '5') { ?>selected="true" <?php }; ?>value="5">Washington</option>
					</select>
				</form>
					<?php						
						if ((isset($_GET['rosterYear'])) && (isset($_GET['rosterTeam']))) {                 
							$season = $_GET['rosterYear'];
							$teamid = $_GET['rosterTeam'];
						} else {
								// The following code will determine the $season in this format: '2015-2016' '2016-2017' for database entry
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
									$teamid = "26";
							}
						if ((isset($_GET['rosterYear'])) && (isset($_GET['rosterTeam']))) {						
							$season = $_GET['rosterYear'];
							$teamid = $_GET['rosterTeam']; 
							$query = $db->prepare("SELECT * FROM rosters WHERE season = :season AND teamid = :teamid ORDER BY 'id'");
							$query->bindParam(':season', $season);
							$query->bindParam(':teamid', $teamid);
							$query->execute();
							$result = $query->fetchAll();
							echoTable($result);
						} else {
								$query = $db->prepare("SELECT * FROM rosters WHERE season = :season And teamid = :teamid ORDER BY 'id'");
								$query->bindParam(':season', $season);
								$query->bindParam(':teamid', $teamid);
								$query->execute();
								$result = $query->fetchAll();
								echoTable($result);
							}
						$db = null;	
					?>
			</div>		
			<?php include 'modules/pictures.php' ; ?>
		<?php include 'modules/footer.php' ; ?>
	</div>
</div> 
</html>
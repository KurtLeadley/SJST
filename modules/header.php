<?php
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
?>
<!DOCTYPE html >
<html>
<head>
  	<meta charset="utf-8" >
	<title>SJS</title>
  	<link rel="stylesheet" type="text/css" href="sharkscss.css"> 
	<link rel="icon" href="images/favicon.ico">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="js/scripts.js"></script>
	<script src="js/sorttable.js"></script>
</head>
<div id='fixedHeader'>
	<table  class="upper-nav">
			<tr>
				<th width="15%">	
					<div class="dropdown">
						<button class="dropbtn" onclick="location.href='index.php';">HOME</button>
					</div>
				</th>
				<th width="15%">	
					<div class="dropdown">
						<button class="dropbtn" onclick="location.href='schedule.php';">SCHEDULE</button>
					</div>
				</th>
				<th width="15%">	
					<div class="dropdown">
						<button class="dropbtn" onclick="location.href='roster.php';">ROSTER</button>
					</div>
				</th>
				<th width="15%">	
					<div class="dropdown">
						<button class="dropbtn" onclick="location.href='statistics.php';" >STATISTICS</button>
						<div class="dropdown-content">
							<a href="statistics.php">Individual Stats</a>
							<a href="leaders.php">League Leaders</a>
						</div>
					</div>
				</th>
				<th width="15%">	
					<div class="dropdown">
						<button class="dropbtn" onclick="location.href='standings.php';">STANDINGS</button>
						<div class="dropdown-content">
							<a href="standings.php?standingsYear=<?php echo $season?>&standingsType=league">League</a>
							<a href="standings.php?standingsYear=<?php echo $season?>&standingsType=conference">Conference</a>
							<a href="standings.php?standingsYear=<?php echo $season?>&standingsType=division">Division</a>
							<a href="standings.php?standingsYear=<?php echo $season?>&standingsType=wildcard">Wildcard</a>
						</div>
					</div>
				</th>
				<!-- <th width="15%">	
					<div class="dropdown">
						<button class="dropbtn" onclick="location.href='cms';">FORUM</button>
					</div>
				</th> -->
				<th width="15%">	
					<div class="dropdown">
						<button class="dropbtn" onclick="location.href='results.php';">HISTORY</button>
					</div>
				</th>
			</tr>
	</table>
</div>
<div id='fixedHeader_mobile'>
	<table  class="upper-nav">
			<tr>
				<th width="15%">	
					<div class="dropdown">
						<button class="dropbtn">MENU</button>
						<div class="dropdown-content">
							<a href="index.php">HOME</a>
							<a href="schedule.php">SCHEDULE</a>
							<a href="roster.php">ROSTER</a>
							<a href="statistics.php">STATISTICS</a>
							<a href="leaders.php">LEADERS</a>
							<a href="standings.php">STANDINGS</a>
							<a href="results.php">HISTORY</a>
							<a href="archive.php">NEWS</a>
							<!-- <a href="cms">FORUM</a> -->
						</div>
					</div>
				</th>
			</tr>
	</table>
</div>
<div id='wrapper'>
	<?php include 'get_scores.php'; ?>  
	<h1>San Jose Shark Tank</h1>
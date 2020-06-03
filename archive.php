<?php include 'modules/header.php'; ?>  
	<div id="container">
		<?php include 'modules/pictureLeft.php' ; ?>
		<div id="content">				
		  <table class='tableArchive'>
				<tr>
					<td colspan="6">
						<h2> News Archive </h2> 
					</td>
					<td> Today: <?php echo date(" m/d/y"); ?> </td>
				</tr>
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
			
			$query = $db->prepare("SELECT title, desc, link, date(pubdate) AS pubdate FROM news ORDER BY pubdate DESC");				
			$query->execute();
			$result = $query->fetchAll();

			foreach ($result as $row) {
				$link = stripslashes($row['link']);
				echo "<tr>";
					echo "<th colspan='6'>";
					echo stripslashes($row['title']);
					echo "</th>";
					echo "<th class='white_header'>";
					echo stripslashes($row['pubdate']);
					echo "</th>";
				echo "</tr>";
				echo "<tr>";
					echo "<td colspan='6'>";
					echo stripslashes($row['desc']);
					echo "</td>";
					echo "<td>";
					echo "<a href ='$link' target='_blank'> Link </a>";
					echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
		?>

		</div>
		<?php include 'modules/pictures.php' ; ?>
	<?php include 'modules/footer.php' ; ?>
</div>
</div> 
</html>
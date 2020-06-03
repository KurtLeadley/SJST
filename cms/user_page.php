<?php 
require('includes/utilities.inc.php');
require('includes/header.inc.php');

$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$q = $pdo->prepare("SELECT id FROM comments WHERE userID = :sessionUserID");
$q->bindParam(':sessionUserID', $user->getId());
$q->execute();
$r = $q->fetchAll();
$sessionCommentCount = $q->rowCount();

if ($r) {
	echo 	"<h2>".$sessionUsername. "</h2>";
	$q = $pdo->prepare("SELECT * FROM users WHERE id = :sessionUserID");
	$q->bindParam(':sessionUserID', $user->getId());
	$q->execute();
	$r = $q->fetchAll();
	
	foreach ($r as $row) {
		$image = $row['image'];
		$sig = $row['sig'];
		if($image == "") {
			echo "<p>No Avatar in database</p>";
		} else {
				echo "<img width='100px' height='100px' src='avatars/".$image."'>";
				echo "<br/><br/>";
		}
	}
	?>
	<html>
		<form action="user_page.php" enctype="multipart/form-data" method="POST" name="avatarform">
			<label>Avatar Selection:</label>
			<input type="file" name="file" accept="image/*"><br/><br/><br/>
			<label>Add / edit Signature:</label>
			<input type="textfield" name="textfield" value="<?php echo htmlspecialchars($sig)?>">
			<input type="submit" name="submit">		
		</form>
	</html>
	
<?php
if (isset($_POST['submit'])) {
	$sig = $_POST['textfield']; 
	$q = $pdo->prepare("Update users 
											SET sig= :sig WHERE username= :username");
	$q->bindParam(':sig', $sig);
	$q->bindParam(':username', $sessionUsername);
	$q->execute();
	echo "<p>";
	if(move_uploaded_file($_FILES['file']['tmp_name'],"avatars/".$_FILES['file']['name'])) {
		echo "Avatar uploaded";
		$q = $pdo->prepare("Update users 
												SET image= :image WHERE username= :username");
		$q->bindParam(':image', $_FILES['file']['name']);
		$q->bindParam(':username', $sessionUsername);
		$q->execute();
		
	} 
	echo "</p>";
} 
?>
<?php
	if($sig == "") {
		echo "<p>No Signature in database</p>";
	} else {
			echo "<p>Signature: ".$sig."</p>";
	}	
}
require('includes/footer.inc.php');
	
?>
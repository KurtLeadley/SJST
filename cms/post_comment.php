<?php
// scripts.js calls this file on form submit to insert new comments into the database
require('includes/utilities.inc.php');
$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

if (isset($_POST['user_comment']) && isset($_POST['user_id'])) {
	$pageID = $_POST['page_id'];
	$userID = $_POST['user_id'];
	$title = $_POST['user_title'];
	$title = trim($title);
	$comment = $_POST['user_comment'];
	$comment = trim($comment);
	
	$q = $pdo->prepare("INSERT INTO comments (userID, title, comment, pageID, dateAdded) VALUES (:userID, :title, :comment, :pageID, NOW())");
			
	$q->bindParam(':userID', $userID);
	$q->bindParam(':title', $title);
	$q->bindParam(':comment', $comment);
	$q->bindParam(':pageID', $pageID);
	
	$result = $q->execute();	
}
?>
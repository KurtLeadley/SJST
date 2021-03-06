<?php
require('includes/utilities.inc.php');
$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$sessionUserID = $user->getId();

if (isset($_POST['comment_id'])) {
	$commentID = $_POST['comment_id'];
	$pageID = $_POST['page_id'];
	$userID = $_POST['user_id'];
	$title = $_POST['user_title'];
	$title = trim($title);
	$comment = $_POST['user_comment'];
	$comment = trim($comment);
	$q = $pdo->prepare("REPLACE INTO likes (user_id, comment_id) VALUES (:sessionuserid, :commentid)");
			
	$q->bindParam(':sessionuserid', $sessionUserID);
	$q->bindParam(':commentid', $commentID);
	
	$result = $q->execute();	
}
?>
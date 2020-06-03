<script>
  var divIds = [];
  $('.comments').each(function(){
		divIds.push($(this).attr('id'));
  });
  console.log(divIds);
</script>
<?php
If ($user) {
	$sessionUserID = $user->getId();
} else {
	echo "<p>Please log in or register to comment</p>";
}
	$pageID = $_GET['id'];
	$q = 'SELECT comments.id, comments.userID, comments.title, comments.comment, comments.pageID, users.username, users.sig, users.image, users.id AS usersid, DATE_FORMAT(comments.dateAdded, "%b %e %Y | %h:%i %p") AS dateAdded 
				FROM comments
				LEFT JOIN users ON (comments.userID = users.id)
				WHERE pageId=:pageId'; 
	$stmt = $pdo->prepare($q);
	$r = $stmt->execute(array(':pageId' => $page->getId()));
	$count = $stmt->rowCount();
?>
<div class="all_comments" id="allComments">
<h2><?php echo $count. " ";?>Comments:</h2>
<?php
if ($r){
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$comment = $row['comment'];
		$commentID = $row['id'];
		$title = $row['title'];
		$dateAdded = $row['dateAdded'];
		$userID = $row['usersid'];
		$userName = $row['username'];	
		$sig = $row['sig'];
		$image = $row['image'];
		
		$q2 = $pdo->prepare("SELECT * FROM likes WHERE comment_id = :commentid");
		$q2->bindParam(':commentid', $commentID);
		$q2->execute();
		$r2 = $q2->fetchAll();
		$count = $q2->rowCount();
		
		$q3 = $pdo->prepare("SELECT * FROM likes WHERE comment_id = :commentid AND user_id = :sessionuserid");
		$q3->bindParam(':commentid', $commentID);
		$q3->bindParam(':sessionuserid', $sessionUserID);
		$q3->execute();
		$r3 = $q3->fetchAll();
		$sessionCount = $q3->rowCount();
		
		$q4 = $pdo->prepare("SELECT image FROM users WHERE username = :username");
		$q4->bindParam(':username', $userName);
		$q4->execute();
		$r4 = $q4->fetchAll();
		
?>
<?php if ($count < 2) { ?> 
<div class="comment" id="<?php echo $commentID; ?>">
	<?php } else { ?>
<div class="comment_liked comment" id="<?php echo $commentID; ?>">
	<?php } ?>
	<div class="comment-title"><p><?php echo $title; ?></p></div>
	<div class="comment-avatar">
		<?php		
			foreach ($r4 as $row) {
				$image = $row['image'];
				if($image == "") {
					
				} else {
						echo "<img width='75px' height='75px' src='avatars/".$image."'>";
				}
			}
		?>			
	</div>
	<div class="comment-body"><p><?php echo $comment; ?></p></div>
	<div class="comment-user-sig"><p><?php echo $sig; ?></p></div>
	<div class="comment-user-name">
		<p>Posted by: <?php echo $userName; ?>&nbsp;&nbsp;<?php echo $dateAdded; ?>&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	<div class ="comment-rec-button">
		<?php	if ($sessionCount == 0) { ?>
						<form class="inlineform" id="inlineForm" action="" method="post" onsubmit="recComment(event, <?php echo $commentID; ?>)">
							<input type="hidden" id="commentid" value="<?php echo $commentID; ?>">
							<input type="hidden" id="userid" value="<?php echo $userID; ?>">
							<input type="hidden" id="title-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($title); ?>">
							<input type="hidden" id="comment-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($comment); ?>">
							<input type="hidden" id="userName-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($userName); ?>">
							<input type="hidden" id="time-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($dateAdded); ?>">
							<input type="hidden" id="count-<?php echo $commentID; ?>" value="<?php echo $count; ?>">
							<input type="hidden" id="image-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($image); ?>">
							<input type="hidden" id="sig-<?php echo $commentID; ?>" value="<?php echo $sig; ?>">
							<input class="rec-button" type="submit" value="rec" /><?php echo " (". $count.")"; ?>
						</form>
		<?php	} else if ($sessionCount > 0) { ?>
						<form class="inlineform" id="inlineForm" action="" method="post" onsubmit="unrecComment(event, <?php echo $commentID; ?>)">
							<input type="hidden" id="commentid" value="<?php echo $commentID; ?>">
							<input type="hidden" id="userid" value="<?php echo $userID; ?>">
							<input type="hidden" id="title-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($title); ?>">
							<input type="hidden" id="comment-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($comment); ?>">
							<input type="hidden" id="userName-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($userName); ?>">
							<input type="hidden" id="time-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($dateAdded); ?>">
							<input type="hidden" id="count-<?php echo $commentID; ?>" value="<?php echo $count; ?>">
							<input type="hidden" id="image-<?php echo $commentID; ?>" value="<?php echo htmlspecialchars($image); ?>">
							<input type="hidden" id="sig-<?php echo $commentID; ?>" value="<?php echo $sig; ?>">
							<input class="rec-button" type="submit" value="unrec" /><?php echo " (". $count.")"; ?> 
						</form>
		</p>
		<?php	} ?>  
	</div>
</div>
	<?php
	}
}	
?>
</div>
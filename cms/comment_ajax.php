<?php 
// This page is called by scripts.js to update the comment.php comment section every x amount of seconds
require('includes/utilities.inc.php');
$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  // Validate the page ID:
	if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
			throw new Exception('An invalid page ID was provided to this page.');
	}
	// Fetch the page from the database:
	$q = 'SELECT id, creatorId, title, content, alias, image, description, DATE_FORMAT(dateAdded, "%b %e %Y") AS dateAdded FROM pages WHERE id=:id'; 
	$stmt = $pdo->prepare($q);
	$r = $stmt->execute(array(':id' => $_GET['id']));

	// If the query ran okay, fetch the record into an object:
	if ($r) {
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Page');
		$page = $stmt->fetch();
		// Confirm that it exists:
		if ($page) {  
			// Set the browser title to the page title:
			$pageTitle = $page->getTitle();
			$pageID = $page->getId();
			// Create the page:
			include('comment.php');
		} 
	}
?>
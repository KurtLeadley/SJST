<?php # archive.php - Script 9.7

// Need the utilities file:
require('includes/utilities.inc.php');

// Include the header:
$pageTitle = 'Welcome to the Site!';
include('includes/header.inc.php');

// Fetch the three most recent pages:
try {   
	$q = 'SELECT id, title, content, image, alias, description, DATE_FORMAT(dateAdded, "%e %M %Y %r") AS dateAdded FROM pages ORDER BY id DESC'; 
	$r = $pdo->query($q);
	
	// Check that rows were returned:
	if ($r && $r->rowCount() > 0) {

			// Set the fetch mode:
			$r->setFetchMode(PDO::FETCH_CLASS, 'Page');

			// Records will be fetched in the view:
			include('views/archive.html');

	} else { // Problem!
			throw new Exception('No content is available to be viewed at this time.');
	}     
} catch (Exception $e) { // Catch generic Exceptions.
    include('views/error.html');
	}
// Include the footer:
include('includes/footer.inc.php');
?>
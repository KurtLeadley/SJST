<?php # add_page.php - Script 9.15
// This page both displays and handles the "add a page" form.

// Need the utilities file:
require('includes/utilities.inc.php');

//$id = $_GET['id'] ;
$q = 'SELECT title, alias, image, description, content FROM pages WHERE id = :id';
$stmt = $pdo->prepare($q);
$stmt = $pdo->prepare($q);
$r = $stmt->execute(array(':id' => $_GET['id']));

try {
    
    // Validate the page ID:
    if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        throw new Exception('An invalid page ID was provided to this page.');
    }
    
    // Fetch the page from the database:
    $q = 'SELECT id, creatorId, title, content, alias, image, description, DATE_FORMAT(dateAdded, "%e %M %Y") AS dateAdded FROM pages WHERE id=:id'; 
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
						$pageAlias = $page->getAlias();
						$pageImage = $page->getImage();
						$pageDescription = $page->getDesc();
						$pageContent = $page->getContent();
						$pageId = $page->getId();
						
						// Redirect if the user doesn't have permission:
						if (!$user->canCreatePage()) {
								header("Location:index.php");
								exit;
						}
								
						// Create a new form:
						set_include_path(get_include_path() . PATH_SEPARATOR . '/home/ubuntu/pear/share/pear');
						require('HTML/QuickForm2.php');
						$form = new HTML_QuickForm2('addPageForm');

						// Add the title field:
						$title = $form->addElement('text', 'title');
						$title->setLabel('Page Title');
						$title->addFilter('strip_tags');
						$title->addRule('required', 'Please enter a page title.');
						$title->setValue($pageTitle);

						// Add the alias field:
						$alias = $form->addElement('text', 'alias');
						$alias->setLabel('Author Alias');
						$alias->addFilter('strip_tags');
						$alias->addRule('required', 'Please enter an Alias.');
						$alias->setValue($pageAlias);
						
						// Add the image field:
						$image = $form->addElement('text', 'image');
						$image->setLabel('Front Page Image');
						$image->addFilter('trim');
						$image->addRule('required', 'Please enter the stories front page image.');
						$image->setValue($pageImage);

						// Add the decription field:
						$description = $form->addElement('textarea', 'description');
						$description->setLabel('Page Description');
						$description->addFilter('trim');
						$description->addRule('required', 'Please enter the front page description.');
						$description->setValue($pageDescription);

						// Add the content field:
						$content = $form->addElement('textarea', 'content');
						$content->setLabel('Page Content');
						$content->addFilter('trim');
						$content->addRule('required', 'Please enter the page content.');
						$content->setValue($pageContent);

						// Add the submit button:
						$submit = $form->addElement('submit', 'submit', array('value'=>'Edit This Page'));

						// Check for a form submission:
						if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form submission
								
								// Validate the form data:
								if ($form->validate()) {
										
										// Insert into the database:
										$q = 'UPDATE pages 
												  SET creatorId=:creatorId, title=:title, content=:content, dateUpdated=Now(), alies=:alias, description=:description, image=:image 
													WHERE id=:id';
										$stmt = $pdo->prepare($q);
										$r = $stmt->execute(array(':creatorId' => $user->getId(), ':title' => $title->getValue(),  ':id' => $id->getValue(), ':alias' => $alias->getValue(), 
											':description' => $description->getValue(), ':content' => $content->getValue(), ':image' => $image->getValue()));

										// Freeze the form upon success:
										if ($r) {
												$form->toggleFrozen(true);
												$form->removeChild($submit);
										}
														
								} // End of form validation IF.
								
						} // End of form submission IF.
            
        } else {
            throw new Exception('An invalid page ID was provided to this page.');
        }
    
    } else {
        throw new Exception('An invalid page ID was provided to this page.');       
    }

} catch (Exception $e) { // Catch generic Exceptions.

    $pageTitle = 'Error!';
    include('includes/header.inc.php');
    include('views/error.html');

}

// Show the page:
$pageTitle = 'Edit a Page';
include('includes/header.inc.php');
include('views/edit_page.html');
include('includes/footer.inc.php');
?>
<script src="js/tinymce_4.5.1/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: "textarea",
        theme: "modern",
				forced_root_block_attrs: { "style": "margin: 0;" },
        width: 600,
        height: 600,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
    }); 
</script> 
<?php # edit_page.php - Script 9.15
// This page both displays and handles the "edit a page" form.

// Need the utilities file:
require('includes/utilities.inc.php');

$q = 'SELECT title, alias, image, description, content FROM pages WHERE id = :id';
$stmt = $pdo->prepare($q);
$r = $stmt->execute(array(':id' => $_GET['id']));
       
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
		// Redirect if the user doesn't have permission:
		if (!$user->canCreatePage()) {
				header("Location:index.php");
				exit;
		}
				
		// Create a new form:
		set_include_path(get_include_path() . PATH_SEPARATOR . '/home/ubuntu/pear/share/pear');
		require('HTML/QuickForm2.php');

		$form = new HTML_QuickForm2('editPageForm', 'post', array(
				'action' => $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']
		));

		// Add the title field:
		$title = $form->addElement('text', 'title');
		if (!($form->validate())) {
			$title->setValue(strip_tags($page->getTitle()));
		}
		$title->setLabel('Page Title');
		$title->addFilter('strip_tags');
		$title->addRule('required', 'Please enter a page title');
		
		// Add the alias field:
		$alias = $form->addElement('text', 'alias');
		if (!($form->validate())) {
			$alias->setValue(strip_tags($page->getAlias()));
		}
		$alias->setLabel('Author Alias');
		$alias->addFilter('strip_tags');
		$alias->addRule('required', 'Please enter an Alias.');
    
		// Add the image field:
		$image = $form->addElement('text', 'image');
		if (!($form->validate())) {
			$image->setValue(strip_tags($page->getImage()));
		}
		$image->setLabel('Front Page Image');
		$image->addFilter('trim');
		$image->addRule('required', 'Please enter the stories front page image.');
		
		// Add the description field:
		$description = $form->addElement('text', 'description');
		if (!($form->validate())) {
			$description->setValue(strip_tags($page->getDesc()));
		}
		$description->setLabel('Page Description');
		$description->addFilter('strip_tags');
		$description->addRule('required', 'Please enter a page description');
		
		// Add the content field:
		$content = $form->addElement('textarea', 'content');
		if (!($form->validate())) {
		$content->setValue($page->getContent());
		}
		$content->setLabel('Page Content');
		$content->addFilter('trim');
		$content->addRule('required', 'Please enter the page content');
		
		// Add the submit button:
		$submit = $form->addElement('submit', 'submit', array('value' => 'Edit This Page'));
		$form->addRecursiveFilter('trim');

	 // Validate the form data:
		if ($form->validate()) {
			// Update the edited text:    
			$query = 'UPDATE pages
								SET creatorId=:creatorId, title=:title, content=:content, dateUpdated=NOW(), image=:image, description=:description, image=:image, alias=:alias
								WHERE id=:id';

			$stmt = $pdo->prepare($query);
		
			$result = $stmt->execute(array(':creatorId' => $user->getId(), ':title' => $title->getValue(), ':alias' => $alias->getValue(), 
																		 ':image' => $image->getValue(), ':description' => $description->getValue(), ':content' => $content->getValue(), ':id' => $page->getId()));
		
			// Freeze the form upon success:
			if ($result) {
				 $form->toggleFrozen(true);
				 $form->removeChild($submit);
			}				
		}
	}	
}
// Show the page:
$pageTitle = 'Edit a Page';
include('includes/header.inc.php');
include('views/edit_page.html');
include('includes/footer.inc.php');
?>
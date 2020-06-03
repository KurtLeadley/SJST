<script src="js/tinymce_4.5.1/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: "textarea",
        theme: "modern",
				forced_root_block_attrs: { "style": "margin: 0;" },
        width: 600,
        height: 400,
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
<?php # add_page.php - Script 9.15
// This page both displays and handles the "add a page" form.

// Need the utilities file:
require('includes/utilities.inc.php');

// Redirect if the user doesn't have permission:
if (!$user->canCreatePage()) {
    header("Location:index.php");
    exit;
}
// get latest page ID and add 1 to declare a new id for the self post (it auto increments in the db, but we need the id in the url)
$q = 'SELECT id FROM pages ORDER BY id DESC LIMIT 2'; 
$stmt = $pdo->prepare($q);
$r = $stmt->execute();

$array = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$id = $row['id'];
	array_push($array, $id);
}	

$nextID = $array[1] + 1;
    
// Create a new form:
set_include_path(get_include_path() . PATH_SEPARATOR . '/home/ubuntu/pear/share/pear');
require('HTML/QuickForm2.php');

$form = new HTML_QuickForm2('addPageForm', 'post', array(
		'action' => $_SERVER['PHP_SELF'] . '?id=' .$nextID
));

// Add the title field:
$title = $form->addElement('text', 'title');
$title->setLabel('Page Title');
$title->addFilter('strip_tags');
$title->addRule('required', 'Please enter a page title.');

// Add the alias field:
$alias = $form->addElement('text', 'alias');
$alias->setLabel('Author Alias');
$alias->addFilter('strip_tags');
$alias->addRule('required', 'Please enter an Alias.');

// Add the image field:
$image = $form->addElement('text', 'image');
$image->setLabel('Front Page Image');
$image->addFilter('trim');
$image->addRule('required', 'Please enter the stories front page image.');

// Add the decription field:
$description = $form->addElement('text', 'description');
$description->setLabel('Page Description');
$description->addFilter('trim');
$description->addRule('required', 'Please enter the front page description.');
$description->addRule('minlength', 'Description should be at least 15 characters long', 15,
                   HTML_QuickForm2_Rule::CLIENT_SERVER);
$description->addRule('maxlength', 'Description should be no more than 50 characters long', 50,
                   HTML_QuickForm2_Rule::CLIENT_SERVER);

// Add the content field:
$content = $form->addElement('textarea', 'content');
$content->setLabel('Page Content');
$content->addFilter('trim');
$content->addRule('required', 'Please enter the page content.');

// Add the submit button:
$submit = $form->addElement('submit', 'submit', array('value'=>'Add This Page'));
$form->addRecursiveFilter('trim');
// Check for a form submission:
    
// Validate the form data:
if ($form->validate()) {
		
		// Insert into the database:
		$q = 'INSERT INTO pages (creatorId, title, content, dateAdded, image, alias, description) VALUES (:creatorId, :title, :content, NOW(), :image, :alias, :description)';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':creatorId' => $user->getId(), ':title' => $title->getValue(), ':alias' => $alias->getValue(), ':description' => $description->getValue(), ':content' => $content->getValue(), 
												':image' => $image->getValue()));

		// Freeze the form upon success:
		if ($r) {
				$form->toggleFrozen(true);
				$form->removeChild($submit);
		}
						
} // End of form validation IF.
 
// Show the page:
$pageTitle = 'Add a Page';
include('includes/header.inc.php');
include('views/add_page.html');
include('includes/footer.inc.php');
?>
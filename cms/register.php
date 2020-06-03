<?php # register.php 
// This page both displays and handles the registration form.


// Need the utilities file:
require('includes/utilities.inc.php');
    
// Create a new form:
set_include_path(get_include_path() . PATH_SEPARATOR . '/home/ubuntu/pear/share/pear');
require('HTML/QuickForm2.php');
//$form = new HTML_QuickForm2('registerForm');
// Add the username field:
// $username = $form->addElement('text', 'username');
// $username->setLabel('Username');
// $username->addFilter('trim');
// $username->addFilter('strip_tags');
// $username->addRule('required', 'Please enter a username.');

// Add the email address field:
// $email = $form->addElement('text', 'email');
// $email->setLabel('Email Address');
// $email->addFilter('trim');
// $email->addRule('email', 'Please enter your email address.');
// $email->addRule('required', 'Please enter your email address.');

// Add the password field:
// $pass = $form->addElement('password', 'pass');
// $pass->setLabel('Password');
// $pass->addFilter('trim');
// $pass->addRule('required', 'Please enter your password.');

// Add the submit button:
// $submit = $form->addElement('submit', 'submit', array('value'=>'Register'));

// Check for a form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form submission
    
    // Validate the form data:
    if ($form->validate()) {

        // Check for the email address:
        $q = 'SELECT email FROM users WHERE email=:email';
        $stmt = $pdo->prepare($q);
        $r = $stmt->execute(array(':email' => $email->getValue()));
				
				// Check for the username:
        $q2 = 'SELECT username FROM users WHERE username=:username';
        $stmt2 = $pdo->prepare($q2);
        $r2 = $stmt->execute(array(':username' => $username->getValue()));
				
        if (($stmt->fetch(PDO::FETCH_NUM) > 0) || ($stmt2->fetch(PDO::FETCH_NUM) > 0)) {
            $email->setError('That email address or username has already been registered.');
        } else {
            // Insert into the database:
            $q = 'INSERT INTO users (userType, username, email, pass) VALUES ("public", :username, :email, :pass)';
            $stmt = $pdo->prepare($q);
            $r = $stmt->execute(array(':username' => $username->getValue(), ':email' => $email->getValue(), ':pass' => $pass->getValue()));

            // Freeze the form upon success:
            if ($r) {
                $form->toggleFrozen(true);
                $form->removeChild($submit);
            }

        }
                
    } // End of form validation IF.
    
} // End of form submission IF.

// Show the page:
$pageTitle = 'Registration Disabled';
include('includes/header.inc.php');
include('views/register.html');
include('includes/footer.inc.php');
?>
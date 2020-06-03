<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (isset($pageTitle)) ? $pageTitle : 'SJ Shark Tank'; ?></title>

    <script src="js/custom-jquery.js"></script>
    <script src="js/tinymce_4.5.1/tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="js/scripts.js"></script>

    <link rel="stylesheet" href="css/main.css">
	<link rel="icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<!-- # header.inc.php - Script 9.1 -->
<?php if ($user) { $sessionUsername = $user->getUserName(); $sessionUserID = $user->getId(); } else { $sessionUsername = "Please Register or Login";} ?>
<body>
    <header class='header'>
        <img src="images/sjst-logo.png" alt="Logo"/>
        <div id='nav-div'>
            <input class="menu-btn" type="checkbox" id="menu-btn" />
            <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label> 
            <ul class='menu'>
                <li><a href="index.php">Home</a></li>
                <li><a href="archive.php">Archives</a></li>
                <li><a href="../index.php">Stats</a></li>
                <li><?php if ($user) { echo '<a href="logout.php">Logout</a>'; } else { echo '<a href="login.php">Login</a>'; } ?></li>
                <li><?php if (!$user) { echo '<a href="register.php">Register</a>' ; } ?></li>
                <li><?php if ($user) { $sessionUserID = $user->getId(); ?><a href='user_page.php'> <span style="font-size: 15px !important; color:#307D7E;"><?php echo $sessionUsername ;
                        $q = $pdo->prepare("SELECT id FROM `comments` WHERE userID = :sessionUserID");
                        $q->bindParam(':sessionUserID', $sessionUserID);
                        $q->execute();
                        $r = $q->fetchAll();
                        $sessionCommentCount = $q->rowCount();
                        echo "&nbsp;(". $sessionCommentCount. " comments)</a>"; }?> </span>
                </li>
            </ul>
        </div>
    </header>
	<div class='content wrapper'>
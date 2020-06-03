<div id="side-a2">
	<img src="images2/fin.jpg" width="100%" height="100%"; alt="" /> 
<script> 
	 var height = $("#content").height();
</script> 
<?php

$imagesDir = 'images/';
$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
$imagesCount= 7;

 for($i = 0 ; $i < $imagesCount ; $i++) { 
    $randomIndex = array_rand($images); 

    $file_title = $randomImage = $images[$randomIndex];
	unset($images[$randomIndex]);
	?>
	<img src="<?php echo $randomImage; ?>"	width="100%" height="150%"; alt="randomImage" />		
<?php
}
?>
</div>

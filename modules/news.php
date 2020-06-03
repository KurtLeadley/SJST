<div id="side-a">
	<ul id="news">
		<li>
			<h2> News </h2>							
		</li>
		<li>
			<a href="archive.php" id="archive">News Archive</a><br /><br />
		</li>
<?php
	
	$query = $db->prepare("SELECT title, desc, link, date(pubdate) AS pubdate FROM news ORDER BY pubdate DESC");				
	$query->execute();
	$result = $query->fetchAll();
	
	$i = 0;
	foreach ($result as $row) {
		if ($i==10) break;
		$title = $row['title'];
		$desc = $row['desc'];
		$link = $row['link'];
		$pubDate = $row['pubdate'];	
?>		
		<li>
			<?php echo $pubDate; ?>
		</li>
		<li>
			<a href="<?php echo $link; ?>" target="_blank"><?php echo $title; ?></a>
			<!-- <a href="<?php //echo $link; ?>" target="_blank" title="<?php// echo $desc;?>"><?php// echo $title; ?></a> !-->
		</li><br />
<?php
			$i++;	
	}
?>
	<li>
		<h2> Links </h2><br />
	</li>
	<li><a href="http://sharks.nhl.com" target="official"> Official Sharks Website</a></li>
	<li><a href="http://bladesofteal.com" target="Blades of Teal"> Blades of Teal</a></li>
	<li><a href="http://blogs.mercurynews.com/sharks/" target=" Working The Corners">Working The Corners</a></li>
	<li><a href="http://www.letsgosharks.com/" target="Lets Go Sharks"> Lets Go Sharks</a></li>
	<li><a href="http://espn.go.com/nhl/team/_/name/sj/san-jose-sharks" target="ESPN"> ESPN Sharks Page</a></li>
	<li><a href="http://fearthefin.com" target="Fear The Fin">Fear The Fin</a></li>
</div>

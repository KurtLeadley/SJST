<?php

	error_reporting(E_ERROR);
  ini_set('display_errors', 1); 
  $configs = include('../config.php');
  $pdoUrl = $configs->pdo;
	try {   
		$db = new PDO($pdoUrl);  
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
	} catch (Exception $e) {  
			echo "Error: Could not connect to database.  Please try again later.";
			exit;
		}
	$xml_url = "https://thehockeywriters.com/category/san-jose-sharks/feed/";

	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $curl, CURLOPT_URL, $xml_url );
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0");
	
	$xml = curl_exec( $curl );
	curl_close( $curl );
	
	$document = new DOMDocument;
	$document->loadXML( $xml );	
	
	$items = $document->getElementsByTagName("item");		
	foreach ($items as $item) {	    
		$title = $item->getElementsByTagName('title')->item(0)->nodeValue;
		$desc = $item->getElementsByTagName('description')->item(0)->nodeValue;
		$link = $item->getElementsByTagName('link');
		$link = $link->item(0)->nodeValue;
		$pubDate = $item->getElementsByTagName('pubDate');
		$pubDate = $pubDate->item(0)->nodeValue;
		$pubDate = explode(" ",$pubDate);

		$month = $pubDate[2];
		switch ($month) {
				case "Jan":
						$month = "01";
						break;
				case "Feb":
						$month = "02";
						break;
				case "Mar":
						$month = "03";
						break;
				case "Apr":
						$month = "04";
						break;
				case "May":
						$month = "05";
						break;
				case "June":
						$month = "06";
						break;
				case "July":
						$month = "07";
						break;
				case "Aug":
						$month = "08";
						break;
				case "Sep":
						$month = "09";
						break;
				case "Oct":
						$month = "10";
						break;
				case "Nov":
						$month = "11";
						break;
				case "Dec":
						$month = "12";
						break;
				default:
					  $month = "xx";
		}
		$pubDate = $pubDate[3]."-".$month."-".$pubDate[1];

		$query = $db->prepare("INSERT OR IGNORE INTO news ('title','desc','link','pubdate')
								         VALUES (:title, :desc, :link, :pubdate)");
			
		bindParamNewsFeed($query, $title, $desc, $link, $pubDate);
  		$query->execute();
	}
	function bindParamNewsFeed($query, $title, $desc, $link, $pubDate) {
		$query->bindParam(':title', $title);
		$query->bindParam(':desc', $desc);
		$query->bindParam(':link', $link);
		$query->bindParam(':pubdate', $pubDate);	
	}
?>
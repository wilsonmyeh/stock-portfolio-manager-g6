<?php
	//enable global variables
	session_start();

	//Allow for errors to be displayed
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);	

	header("Access-Control-Allow-Origin: *");

	//http://bavotasan.com/2010/display-rss-feed-with-php/
	function pullNewsFromMarketPulse(){
		$rss = new DOMDocument();
		$rss->load("http://feeds.marketwatch.com/marketwatch/marketpulse");
		$feed = array();
		
		foreach ($rss->getElementsByTagName('item') as $node)
		{
			$item = array ( 
				'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
				'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
				'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
				'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
				);
			array_push($feed, $item);
		}

		$limit = 5;
		for($x=0;$x<$limit;$x++)
		{
			$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
			$link = $feed[$x]['link'];
			$description = $feed[$x]['desc'];
			$date = date('l F d, Y', strtotime($feed[$x]['date']));
			echo '<p><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br />';
			echo '<small><em>Posted on '.$date.'</em></small></p>';
			echo '<p>'.$description.'</p>';
		}
	}

	pullNewsFromMarketPulse();
?>
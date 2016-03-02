<?php
	//enable global variables
	session_start();

	include_once('../Portfolio.class.php');
	include_once('../Account.class.php');
	include_once('YahooFinance.php');

	//Allow for errors to be displayed
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require '../../vendor/autoload.php';
	use Parse\ParseUser;
	use Parse\ParseClient;
	use Parse\ParseException;
	use Parse\ParseQuery;
	ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');

	function watchStock($tickerName){

		readfile("http://localhost/Frontend/dashboard.html");

		//get the user's portfolio from Parse
		$queryWatchlist = new ParseQuery("Watchlist");

			//////******COMMENT THIS IN WHEN WE HAVE ACTUAL LOCAL OBJECTS****///
			// $queryStock->equalTo("username", username);

			$queryWatchlist->equalTo("username", "rebecca@usc.edu");
			try {
			 	$watchlist = $queryWatchlist->first();

				// The object was retrieved successfully.
			  	$stockNames = $watchlist->get("stockNames");

			  	if (($tickerKey = array_search($tickerName, $stockNames)) !== false) { //if they are already tracking the stock, don't add it
	       		$duplicateTrack = "Already tracking this stock.";
		  		echo "<script type='text/javascript'>alert('$duplicateTrack');</script>";
		  		return;
		  	}
		  	else{ //need to add it to the watchlist
		  		array_push($stockNames, $tickerName);
		  		$watchlist->setArray("stockNames", $stockNames);
		  		try{ //save this update to parse
	  			 	$watchlist->save();
	  			} 
	  			catch (ParseException $ex) {  
	  			 	echo 'Failed to update watchlist ' . $ex->getMessage();
	  			}
	  		}
	   	}

		catch (ParseException $ex) {
		  // The object was not retrieved successfully.
		 // error is a ParseException with an error code and message.
			echo 'error retrieving my portfolio';
		}
	}

	watchStock($stockToWatch);
?>
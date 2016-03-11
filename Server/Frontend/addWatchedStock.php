<?php
	//enable global variables

	include_once('../Portfolio.class.php');
	include_once('../Account.class.php');
	include_once('../Stock.class.php');
	include_once('../TrackedStock.class.php');
	include_once('../OwnedStock.class.php');
	include_once('YahooFinance.php');

	session_start();

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

	//this function adds the stock to the user's watch list
	function watchStock($tickerName){

		//get the user's portfolio from Parse
		$queryWatchlist = new ParseQuery("Watchlist");

			$queryWatchlist->equalTo("username", $_SESSION['account']->getUsername());
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
		  		sort($stockNames);

		  		//update the local watchlist stock list
		  		$trackedStock = new TrackedStock();
		  		$trackedStock->setTicker($tickerName);
		  		$_SESSION['account'] -> getPortfolio() -> addTrackedStock($tickerName, $trackedStock);

		   	    //updating the watchlist in Parse
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
			echo 'error retrieving my watchlist';
		}

		readfile("http://localhost/Frontend/dashboard.html"); //makes it so that any alerts or echos stay on the dashboard
	}

	watchStock($stockToWatch);
?>
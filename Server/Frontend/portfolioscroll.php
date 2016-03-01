<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('YahooFinance.php');
require '../../vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');


	//**************update global object*************
	function sellStock($stockTicker, $numShares, $purchasePrice){
		$queryStock = new ParseQuery("Portfolio");
		///////USE GLOBAL OBJECT USERNAME
		$queryStock->equalTo("username", "rebecca@usc.edu");
		try {
		  $portfolio = $queryStock->first();
		  // The object was retrieved successfully.
		  $stockNames = $portfolio->get("stockNames");
		  $stockPurchaseDates = $portfolio->get("purchaseDates");
		  $stockPurchasePrices = $portfolio->get("purchasePrices");
		  $stockNumberShares = $portfolio->get("numberShares");
		  $accountBalance = $portfolio->get("accountBalance");

		  $found = false;
		  foreach($stockNames as $code => $stock){
     		if( (string)$stock == (string)$stockTicker){
     			$found = true;
     			echo 'already own this stock';
     		}
     	  }

     	  if($found == false){ //if user does not already own shares of this stock, THEY CANNOT SELL THEM!
     	  	$failedSell = "You do not own this stock. Transaction failed.";
			echo "<script type='text/javascript'>alert('$failedSell');</script>";
			return;
     	  }

     	  if($found == true){
     	  	//if they own it, now check if they own at least # shares they want to sell
     	  	if($stockNumberShares[$stockTicker] < $numShares){
     	  		$failedShares = "You do not own enough shares. Transaction failed.";
				echo "<script type='text/javascript'>alert('$failedShares');</script>";
				return;
     	  	}

     	  	//check if they sell all of their shares, if so remove from their owned list 
     	  	///*****UPDATE LOCAL OBJECT*****************
     	  	if($stockNumberShares[$stockTicker] == $numShares){
     	  		if (($key = array_search($stockTicker, $stockNames)) !== false) {
    				unset($stockNames[$key]);
    				$portfolio->setArray("stockNames", $stockNames);
				}
     	  	}

     	  	//else, they still own at least 1 share of that stock 
     	  	$prevNumberOwned = $stockNumberShares[$stockTicker];
     	  	$stockNumberShares[$stockTicker]=$prevNumberOwned - $numShares; //add in number of shares
     	  	ksort($stockNumberShares);

     	  	//update values for Parse object
     	  	$portfolio->setAssociativeArray("numberShares", $stockNumberShares);
     	  }

     	  //update account balance
     	  $newBalance = $accountBalance + ($purchasePrice * $numShares);
     	  // echo $numShares; </br></br>
     	  $portfolio->set("accountBalance", $newBalance);

		  $successfulOrder = "Transaction successful!";
		  echo "<script type='text/javascript'>alert('$successfulOrder');</script>";

     	  try{ //save this update to parse
			  $portfolio->save();
			  echo 'updated stock names when buying stock ' . $portfolio->getObjectId();
			} catch (ParseException $ex) {  
			  echo 'Failed to update stock names when buying stock ' . $ex->getMessage();
			}
     	}

		catch (ParseException $ex) {
		  // The object was not retrieved successfully.
		  // error is a ParseException with an error code and message.
			echo 'error retrieving my portfolio';
		}
	}





	// function watchStock(stockTicker, username){ //this adds the stock to the user's watchlist
		
	// }


	// function removeFromWatchList(stockTicker){

	// }


	$name = "COST";
	$num = 90;
	$purchasePrice = 80000;
	buyStock($name, $num, $purchasePrice);

	//**************update global object*************
	function buyStock($stockTicker, $numShares, $purchasePrice){
		$queryStock = new ParseQuery("Portfolio");
		///////USE GLOBAL OBJECT USERNAME
		$queryStock->equalTo("username", "rebecca@usc.edu");
		try {
		  $portfolio = $queryStock->first();
		  // The object was retrieved successfully.
		  $stockNames = $portfolio->get("stockNames");
		  $stockPurchaseDates = $portfolio->get("purchaseDates");
		  $stockPurchasePrices = $portfolio->get("purchasePrices");
		  $stockNumberShares = $portfolio->get("numberShares");
		  $accountBalance = $portfolio->get("accountBalance");

		  if($accountBalance < ($numShares * $purchasePrice)){
		  	$failedOrder = "Insufficient funds. Transaction failed.";
			echo "<script type='text/javascript'>alert('$failedOrder');</script>";
			return;
		  }

		  //if user does not already own shares of this stock, add to stockNames
		  $found = false;
		  foreach($stockNames as $code => $stock){
     		if( (string)$stock == (string)$stockTicker){
     			$found = true;
     			echo 'already own this stock';
     		}
     	  }
     	  if($found == true){
     	  	//if they already own it, need to update the numbers of shares they own of this stock
     		$prevNumber = $stockNumberShares[$stockTicker];
     		$stockNumberShares[$stockTicker] = $prevNumber + $numShares;
     		ksort($stockNumberShares);

     		$portfolio->setAssociativeArray("numberShares",$stockNumberShares);

     	  }
     	  if($found == false){
     	  	array_push($stockNames, $stockTicker); //add it to stockNames
     	  	sort($stockNames);

     	  	$stockPurchaseDates[$stockTicker]=(string)date("Y/m/d"); //add in purchase date
     	  	ksort($stockPurchaseDates);

     	  	$stockPurchasePrices[$stockTicker]=$purchasePrice; //add in purchase price
     	  	ksort($stockPurchasePrices);

     	  	$stockNumberShares[$stockTicker]=$numShares; //add in number of shares
     	  	ksort($stockNumberShares);

     	  	echo 'newly owned stock!';
     	  	
     	  	//update values for Parse object
     	  	$portfolio->setArray("stockNames", $stockNames);
     	  	$portfolio->setAssociativeArray("purchaseDates", $stockPurchaseDates);
     	  	$portfolio->setAssociativeArray("purchasePrices", $stockPurchasePrices);
     	  	$portfolio->setAssociativeArray("numberShares", $stockNumberShares);
     	  }

     	  //update account balance
     	  $newBalance = $accountBalance - ($purchasePrice * $numShares);
     	  // echo $numShares; </br></br>
     	  $portfolio->set("accountBalance", $newBalance);

		  $successfulOrder = "Transaction successful!";
		  echo "<script type='text/javascript'>alert('$successfulOrder');</script>";

     	  try{ //save this update to parse
			  $portfolio->save();
			  echo 'updated stock names when buying stock ' . $portfolio->getObjectId();
			} catch (ParseException $ex) {  
			  echo 'Failed to update stock names when buying stock ' . $ex->getMessage();
			}
     	}

		catch (ParseException $ex) {
		  // The object was not retrieved successfully.
		  // error is a ParseException with an error code and message.
			echo 'error retrieving my portfolio';
		}
	}

	// pullPortfolioDataFromYahoo();

	//takes an array of stock names like: array("AAPL", "GOOG", "AMZN", "FB");
	function pullPortfolioDataFromYahoo($stockNames){
		$objYahooStock = new YahooStock;
		$objYahooStock->addFormat("snl1d1c1p2"); 

		//*****UPDATE LOCAL VARIABLES*****$stockNames = _SESSION["ownedStocks"];
		foreach($stockNames as $code => $stock){
     		$objYahooStock->addStock((string)$stock);
    	}

    	foreach( $objYahooStock->getQuotes() as $code => $stock){
	      ?>
	      Ticker: <?php echo $stock[0]; ?> <br />
	      Company Name: <?php echo $stock[1]; ?> <br />
	      Last Trade Price: <?php echo'$ '.$stock[2]; ?> <br />
	      Last Trade Date: <?php echo $stock[3]; ?> <br />
	      Dollar Change: <?php echo '$ '. $stock[4]; ?> <br />
	      Percent Change: <?php echo $stock[5]; ?> <br /><br />
	      <?php
	    }
		
	}
	
	$stockTickerNames = array("AAPL", "GOOG", "FB");
	sort($stockTickerNames);

	$stockTickerShares = array();
	$stockTickerShares["AAPL"] = 35;
	$stockTickerShares["GOOG"] = 12;
	$stockTickerShares["FB"] = 5;
	ksort($stockTickerShares);

	$stockTickerDates = array();
	$stockTickerDates["AAPL"] = "01/26/2001";
	$stockTickerDates["FB"] = "02/27/2016";
	$stockTickerDates["GOOG"] = "05/21/2015";
	ksort($stockTickerDates);

	$stockTickerPrices = array();
	$stockTickerPrices["AAPL"] = 65.12;
	$stockTickerPrices["GOOG"] = 55.12;
	$stockTickerPrices["FB"] = 15.12;
	ksort($stockTickerPrices);

	// createNewPortfolioInParse($stockTickerNames, $stockTickerShares, $stockTickerDates, $stockTickerPrices);

	function createNewPortfolioInParse($stockTickerNames, $stockTickerShares, $stockTickerDates, $stockTickerPrices){
		// $newPortfolio->set("username", $_SESSION["account"]);
		$newPortfolio = new ParseObject("Portfolio");
	
		$newPortfolio->set("username", "rebecca@usc.edu");
		$newPortfolio->set("accountBalance", 10000);
		$newPortfolio->setArray("stockNames", $stockTickerNames);
		$newPortfolio->setAssociativeArray("purchaseDates", $stockTickerDates);
		$newPortfolio->setAssociativeArray("purchasePrices", $stockTickerPrices);
		$newPortfolio->setAssociativeArray("numberShares", $stockTickerShares);

		try {
		  $newPortfolio->save();
		} catch (ParseException $ex) {  
		  // Execute any logic that should take place if the save fails.
		  // error is a ParseException object with an error code and message.
		  echo 'Failed to create new object, with error message: ' . $ex->getMessage();
		}
	}
	
?>

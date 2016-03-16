<?php

/*
Create a portfolio object with test arrays, pass in a previously owned stock and verify that the parse/local object now updates number of shares only

Create a portfolio object with test arrays, pass in a previously not owned stock and verify that the parse/local object now owns it

Test: buyStock() --Rebecca
Create a portfolio object with t

*/	
	require 'vendor/autoload.php';
	use Parse\ParseClient;
	use Parse\ParseException;
	use Parse\ParseQuery;
	ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');

	include_once('Server/Portfolio.class.php');
	include_once('Server/Account.class.php');
	include_once('Server/Stock.class.php');
	include_once('Server/TrackedStock.class.php');
	include_once('Server/OwnedStock.class.php');
	require 'Server/Frontend/YahooFinance.php';


	class BuyingStockTest extends PHPUnit_Framework_TestCase
	{
		public function testBuyingPreviouslyOwnedStockShouldOnlyUpdateLocalNumberOfShares()
		{	
			//Arrange
			//creating a variable for this test
			$portfolioObj = new Portfolio();
			$portfolioObj->setUsername("rebecca@usc.edu");
			
			$queryStock = new ParseQuery("Portfolio");
		    $queryStock->equalTo("username", "rebecca@usc.edu");

		    $queryTrack = new ParseQuery("Watchlist");
		    $queryTrack->equalTo("username", "rebecca@usc.edu");
		    try{
		    	//Query for stocks that the user owns in their protfolio
		        $portfolio = $queryStock->first();

		        $bankBalance = $portfolio->get("accountBalance");
		        $stockNamesArray = $portfolio->get("stockNames");
		        $stockPurchaseDatesArray = $portfolio->get("purchaseDates");
		        $stockPurchasePriceArray = $portfolio->get("purchasePrices");
		        $numberStockArray = $portfolio->get("numberShares");
		        $stockArray = array(); //owned stock array

		        //Create n array of the owned stock to be added to the portfolio
		        for($x = 0; $x < count($stockNamesArray);$x++){
		        	$ownedStock = new OwnedStock();
		        	$ownedStock->setTicker($stockNamesArray[$x]);
		        	$ownedStock->setInitialDate($stockPurchaseDatesArray[$stockNamesArray[$x]]);
		        	$ownedStock->setInitialPurchasePrice($stockPurchasePriceArray[$stockNamesArray[$x]]);
		        	$ownedStock->setNumberOwned($numberStockArray[$stockNamesArray[$x]]);

		     		array_push($stockArray,$ownedStock);
		        }


		        //Query for stock that the user is tracking
		        $watchlist = $queryTrack->first();

		        $stockListName = $watchlist->get("stockNames");
		        $trackedStockArray = array();
		        for($x = 0; $x < count($stockListName);$x++){
		       		 $trackedStock = new TrackedStock();
		       		 $trackedStock->setTicker($stockListName[$x]);
		       		 array_push($trackedStockArray,$trackedStock);

		        }

		        $portfolioObj->createOwnedStocks($stockNamesArray,$stockArray);
		        $portfolioObj->createWatchedStocks($stockListName,$trackedStockArray);
		        $portfolioObj->setBankBalance($bankBalance);
		        $portfolioObj->updatePortfolioValue();

		    }
		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }

		    //Act

		    $numShares = (int) 1; //test values for number of shares and purchase price to buy
		    $purchasePrice = (double) 25.0;

		    //get the ticker of the first stock they own
		    reset($portfolioObj->getOwnedStock());
			$stockTicker = key($portfolioObj->getOwnedStock());

			//get previous stock object associated with stockTicker before buying stock
			$tempStockArray = $portfolioObj->getOwnedStock();
			$prevStockObj = $tempStockArray[$stockTicker]; 
			$prevStockPurchasePrice = $prevStockObj->getInitialPurchasePrice();
			$prevStockNumberOwned = $prevStockObj->getNumberOwned();

			$portfolioObj->buyStock($stockTicker, $numShares, $purchasePrice); //buy 1 share of the first stock for 25 dollars

			$newStockObj = $tempStockArray[$stockTicker];
			$newStockPurchasePrice = $newStockObj->getInitialPurchasePrice();
			$newStockNumberOwned = $newStockObj->getNumberOwned();

			// Assert
			$this->assertEquals($prevStockPurchasePrice, $newStockPurchasePrice, "Old purchase price and new purchase price not equal when purchasing stock that was already owned");

			$this->assertEquals( ((int)$prevStockNumberOwned ) + (int)$numShares, $newStockNumberOwned, "number of shares owned did not increment properly when purchasing stock that was already owned");
		}
	}
?>

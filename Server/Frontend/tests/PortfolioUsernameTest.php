<?php

	require_once('../../vendor/autoload.php');
	use Parse\ParseClient;
	use Parse\ParseException;
	use Parse\ParseQuery;
	ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');

	include_once('../Portfolio.class.php');
	include_once('../Account.class.php');
	include_once('../Stock.class.php');
	include_once('../TrackedStock.class.php');
	include_once('../OwnedStock.class.php');
	include_once 'YahooFinance.php';


	class PortfolioUsernameTest extends PHPUnit_Framework_TestCase
	{
		//query the current portfolio's total value directly from Yahoo
		//and compare the results with the Yahoo portfolio value update function

		public function testThatPortfolioUsernameMemberVariableCanBeGetAndSet(){
			//Arrange
			//creating a variable for this test
			$portfolioObj = new Portfolio();
			
			
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

		        //Action
		        $portfolioObj->setUsername("rebecca@usc.edu");

		      //Assert
			$this->assertEquals("rebecca@usc.edu", $portfolioObj->getUsername(), "getters and setters for username of the portfolio are faulty");

		    }
		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }
		}

		
	}
?>

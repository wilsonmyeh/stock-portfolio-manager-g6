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


	class PortfolioUpdateTest extends PHPUnit_Framework_TestCase
	{
		//test that if someone uploads a csv with blank information
		//those blank arrays can update the database with the new portfolio information and save it
		public function testThatParsePortfolioCanBeUpdatedAndSavedWithNewPortfolio(){
			//Make a new global object for this test
			$portfolioObj = new Portfolio();
			$portfolioObj->setUsername("testuser@usc.edu");

			$queryStock = new ParseQuery("Portfolio");
	   	    $queryStock->equalTo("username", "testuser@usc.edu");

	   	    $queryTrack = new ParseQuery("Watchlist");
	   	    $queryTrack->equalTo("username", "testuser@usc.edu");
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


	   	        //Create account information
	   	        $accountObj = new Account();
				$accountObj->setPortfolio($portfolioObj);
				$accountObj->setUsername("testuser@usc.edu");
	   	       	$_SESSION['account'] =  $accountObj;

	   	       	
	   	       	$stockTickerNames = array("AMZN", "YHOO", "AMBA");

	   	       	$stockTickerShares = array();
	   	       	$stockTickerShares["AMZN"] = 12;
	   	       	$stockTickerShares["YHOO"] = 7;
	   	       	$stockTickerShares["AMBA"] = 17;

	   	       	$stockTickerDates = array();
	   	       	$stockTickerDates["AMZN"] = "2/17/2015";
	   	       	$stockTickerDates["YHOO"] = "1/18/2014";
	   	       	$stockTickerDates["AMBA"] = "3/16/2016";

	   	       	$stockTickerPrices = array();
	   	       	$stockTickerPrices["AMZN"] = 12.72;
	   	       	$stockTickerPrices["YHOO"] = 13.20;
	   	       	$stockTickerPrices["AMBA"] = 19.89;

	   	       	include_once('importcsv.php');

	   	       	createNewPortfolioInParse($stockTickerNames, $stockTickerShares, $stockTickerDates, $stockTickerPrices);

	   	       	//query portfoio again in parse and assert the new arrays have the correct thing		
				$queryStock2 = new ParseQuery("Portfolio");
			    $queryStock2->equalTo("username", "testuser@usc.edu");
			    try{
			    	//Query for stocks that the user owns in their protfolio
			        $portfolio2 = $queryStock2->first();

			        $stockNamesArray2 = $portfolio2->get("stockNames");
			        $stockPurchaseDatesArray2 = $portfolio2->get("purchaseDates");
			        $stockPurchasePriceArray2 = $portfolio2->get("purchasePrices");
			        $numberStockArray2 = $portfolio2->get("numberShares");

			        $this->assertArrayHasKey("AMZN", $stockNamesArray2, "after importing new portfolio, AMZN stock does not exist in Parse names array");
			        $this->assertArrayHasKey("YHOO", $stockNamesArray2, "after importing new portfolio, YHOO stock does not exist in Parse names array");
			        $this->assertArrayHasKey("AMBA", $stockNamesArray2, "after importing new portfolio, AMBA stock does not exist in Parse names array");
			        $this->assertEquals(3, count($stockNamesArray2), "after importing new portfolio, size of names array is not correct in Parse");

			        $this->assertArrayHasKey("AMZN", $stockPurchaseDatesArray2, "after importing new portfolio, AMZN stock does not exist in Parse dates array");
			        $this->assertArrayHasKey("YHOO", $stockPurchaseDatesArray2, "after importing new portfolio, YHOO stock does not exist in Parse dates array");
			        $this->assertArrayHasKey("AMBA", $stockPurchaseDatesArray2, "after importing new portfolio, AMBA stock does not exist in Parse dates array");
			        $this->assertEquals(3, count($stockPurchaseDatesArray2), "after importing new portfolio, size of dates array is not correct in Parse");

			        $this->assertArrayHasKey("AMZN", $stockPurchasePriceArray2, "after importing new portfolio, AMZN stock does not exist in Parse prices array");
			        $this->assertArrayHasKey("YHOO", $stockPurchasePriceArray2, "after importing new portfolio, YHOO stock does not exist in Parse prices array");
			        $this->assertArrayHasKey("AMBA", $stockPurchasePriceArray2, "after importing new portfolio, AMBA stock does not exist in Parse prices array");
			        $this->assertEquals(3, count($stockPurchasePriceArray2), "after importing new portfolio, size of prices array is not correct in Parse");

			        $this->assertArrayHasKey("AMZN", $numberStockArray2, "after importing new portfolio, AMZN stock does not exist in Parse shares array");
			        $this->assertArrayHasKey("YHOO", $numberStockArray2, "after importing new portfolio, YHOO stock does not exist in Parse shares array");
			        $this->assertArrayHasKey("AMBA", $numberStockArray2, "after importing new portfolio, AMBA stock does not exist in Parse shares array");
			        $this->assertEquals(3, count($numberStockArray2), "after importing new portfolio, size of shares array is not correct in Parse");

			   	 }
			   	 catch (ParseException $ex) {
		    		echo "could not test previously owned stock--error retrieving Parse portfolio";
		    	}
		    }
		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }
		}

		//test that if someone uploads a csv with blank information
		//those blank arrays can update the new portfolio information and save it
		public function testThatLocalPortfolioCanBeUpdatedAndSavedWithNewPortfolio(){
			//Make a new global object for this test
			$portfolioObj = new Portfolio();
			$portfolioObj->setUsername("testuser@usc.edu");

			$queryStock = new ParseQuery("Portfolio");
	   	    $queryStock->equalTo("username", "testuser@usc.edu");

	   	    $queryTrack = new ParseQuery("Watchlist");
	   	    $queryTrack->equalTo("username", "testuser@usc.edu");
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


	   	        //Create account information
	   	        $accountObj = new Account();
				$accountObj->setPortfolio($portfolioObj);
				$accountObj->setUsername("testuser@usc.edu");
	   	       	$_SESSION['account'] =  $accountObj;

	   	       	$stockTickerNames = array("AMZN", "YHOO", "AMBA");

	   	       	$stockTickerShares = array();
	   	       	$stockTickerShares["AMZN"] = 12;
	   	       	$stockTickerShares["YHOO"] = 7;
	   	       	$stockTickerShares["AMBA"] = 17;

	   	       	$stockTickerDates = array();
	   	       	$stockTickerDates["AMZN"] = "2/17/2015";
	   	       	$stockTickerDates["YHOO"] = "1/18/2014";
	   	       	$stockTickerDates["AMBA"] = "3/16/2016";

	   	       	$stockTickerPrices = array();
	   	       	$stockTickerPrices["AMZN"] = 12.72;
	   	       	$stockTickerPrices["YHOO"] = 13.20;
	   	       	$stockTickerPrices["AMBA"] = 19.89;

	   	       	include_once('importcsv.php');

	   	       	populatePortfolio($stockTickerNames, $stockTickerShares, $stockTickerDates, $stockTickerPrices);

	   	       	$ownedStocks = $portfolioObj->getOwnedStock();

				$this->assertArrayHasKey("AMZN", $ownedStocks, "after importing new portfolio, AMZN stock does not exist in local owned stock array");
		        $this->assertArrayHasKey("YHOO", $ownedStocks, "after importing new portfolio, YHOO stock does not exist in local owned stock array");
		        $this->assertArrayHasKey("AMBA", $ownedStocks, "after importing new portfolio, AMBA stock does not exist in local owned stock array");
		        $this->assertEquals(3, count($ownedStocks), "after importing new portfolio, size of names array is not correct in Parse");

		   	 }
		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }
		}

		
	}
?>

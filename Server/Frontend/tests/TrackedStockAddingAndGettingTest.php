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


	class TrackedStockAddingAndGettingTest extends PHPUnit_Framework_TestCase
	{
		//test that getter for tracked stocks work
		public function testThatGetTrackedStockReturnsCorrectlySavedTrackedStocksInAPortfolio(){
			$portfolioObj = new Portfolio();
			$portfolioObj->setUsername("rebecca@usc.edu");
			$portfolioObj->setBankBalance(10000);

			//make new tester ownedStock objects
			$ownedStock1 = new OwnedStock();
        	$ownedStock1->setTicker("MSFT");
        	$ownedStock1->setInitialDate((string)date("Y/m/d")); 
        	$initialPrice1 = (double)72.8;
        	$ownedStock1->setInitialPurchasePrice($initialPrice1);
        	$numberOwned1 = (int) 7;
        	$ownedStock1->setNumberOwned($numberOwned1);

        	//make new tester ownedStock objects
			$ownedStock2 = new OwnedStock();
        	$ownedStock2->setTicker("GOOG");
        	$ownedStock2->setInitialDate((string)date("Y/m/d")); 
        	$initialPrice2 = (double)54.2;
        	$ownedStock2->setInitialPurchasePrice($initialPrice2);
        	$numberOwned2 = (int) 3;
        	$ownedStock2->setNumberOwned($numberOwned2);

        	//make new tester ownedStock objects
			$ownedStock3 = new OwnedStock();
        	$ownedStock3->setTicker("AAPL");
        	$ownedStock3->setInitialDate((string)date("Y/m/d")); 
        	$initialPrice3 = (double)32.8;
        	$ownedStock3->setInitialPurchasePrice($initialPrice3);
        	$numberOwned3 = (int) 12;
        	$ownedStock3->setNumberOwned($numberOwned3);

			$ownedStockNames = array("MSFT", "GOOG", "AAPL");
			$ownedStocks = array($ownedStock1, $ownedStock2, $ownedStock3);

			$portfolioObj->createOwnedStocks($ownedStockNames, $ownedStocks); //set portfolio owned stock list


			//create watched list
			$trackedStock1 = new TrackedStock();
		    $trackedStock1->setTicker("CMG");

		    $trackedStock2 = new TrackedStock();
		    $trackedStock2->setTicker("AMZN");

		    $trackedStock3 = new TrackedStock();
		    $trackedStock3->setTicker("COST");

		    $trackedStockNames = array("CMG", "AMZN", "COST");
		    $trackedStocks = array($trackedStock1, $trackedStock2, $trackedStock3);

		    $portfolioObj->createWatchedStocks($trackedStockNames, $trackedStocks); //set portfolio tracked stock list

		    $portfolioObj->updatePortfolioValue(); 

		    //now test that the ownedStocks are saved and can be returned properly
		    $trackedStockObjects = $portfolioObj->getTrackedStock();

		    $this->assertArrayHasKey("CMG", $trackedStockObjects, "tracked stock array mistakenly does not have correct stock objects after setting the portfolio");
		    $this->assertArrayHasKey("AMZN", $trackedStockObjects, "tracked stock array mistakenly does not have correct stock objects after setting the portfolio");
		    $this->assertArrayHasKey("COST", $trackedStockObjects, "tracked stock array mistakenly does not have correct stock objects after setting the portfolio");
		    $this->assertEquals(count($trackedStocks), count($trackedStockObjects), "tracked stock objects list and test stock objects list are not the same size after the getter");
        }

		  
		//test that individual stock object can be added to tracked list
		public function testIndividualTrackedStockCanBeAddedToTrackedStockListInPortfolioObject(){
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


		     //make a new trackedStock object to add to the portfolio object
		    $trackedStock = new TrackedStock();
        	$trackedStock->setTicker("AMZN");
        	
			//Assert
			$this->assertArrayNotHasKey("AMZN", $portfolioObj->getTrackedStock(), "tracked stock array mistakenly already has new stock object before inserting it");

			$portfolioObj->addTrackedStock("AMZN", $trackedStock);

			$this->assertArrayHasKey("AMZN", $portfolioObj->getTrackedStock(), "tracked stock array mistakenly does not have new stock object after inserting it");
		    }

		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }
		}

		
	}
?>

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

	class OwnedStockAddingAndGettingTest extends PHPUnit_Framework_TestCase
	{

		//test that owned stock getter works
		public function testThatGetOwnedStockReturnsCorrectlySavedOwnedStocksInAPortfolio(){
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
		    $ownedStockObjects = $portfolioObj->getOwnedStock();

		    $this->assertArrayHasKey("MSFT", $ownedStockObjects, "owned stock array mistakenly does not have correct stock objects after setting the portfolio");
		    $this->assertArrayHasKey("GOOG", $ownedStockObjects, "owned stock array mistakenly does not have correct stock objects after setting the portfolio");
		    $this->assertArrayHasKey("AAPL", $ownedStockObjects, "owned stock array mistakenly does not have correct stock objects after setting the portfolio");
		    $this->assertEquals(count($ownedStocks), count($ownedStockObjects), "owned stock objects list and test stock objects list are not the same size after the getter");
        }

		  
		//test that individual stock object can be saved in the owned list
		public function testIndividualOwnedStockCanBeAddedToOwnedStockListInPortfolioObject(){
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

		     //make a new ownedStock object to add to the portfolio object
		    $ownedStock = new OwnedStock();
        	$ownedStock->setTicker("AMZN");
        	$ownedStock->setInitialDate((string)date("Y/m/d")); 
        	$initialPrice = (double)72.8;
        	$ownedStock->setInitialPurchasePrice($initialPrice);
        	$numberOwned = (int) 7;
        	$ownedStock->setNumberOwned($numberOwned);

			//Assert
			$this->assertArrayNotHasKey("AMZN", $portfolioObj->getOwnedStock(), "owned stock array mistakenly already has new stock object before inserting it");

			$portfolioObj->addOwnedStock("AMZN", $ownedStock);

			$this->assertArrayHasKey("AMZN", $portfolioObj->getOwnedStock(), "owned stock array mistakenly does not have new stock object after inserting it");

		 

		  
		}

	}
?>

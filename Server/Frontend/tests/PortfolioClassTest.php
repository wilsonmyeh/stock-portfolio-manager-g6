<?php
  	require_once('../Portfolio.class.php');

	class PortfolioClassTest extends PHPUnit_Framework_TestCase
	{
		public function testCreateOwnedStocks(){
			$portfolio = new Portfolio();
			$tickerArray = array("GOOG","AAPL","COST");
			$tickerName1 = "GOOG";
			$tickerName2 = "AAPL";
			$tickerName3 = "COST";
			$numOwned1 = 10;
			$numOwned2 = 50;
			$numOwned3 = 100;
			$compName1 = "Google";
			$compName2 = "Apple";
			$compName3 = "Costco";

			$ownedStock1 = new OwnedStock();
			$ownedStock2 = new OwnedStock();
			$ownedStock3 = new OwnedStock();

			$ownedStock1->setNumberOwned(10);
			$ownedStock2->setNumberOwned(50);
			$ownedStock3->setNumberOwned(100);

			$ownedStock1->setTicker("GOOG");
			$ownedStock2->setTicker("AAPL");
			$ownedStock3->setTicker("COST");

			$ownedStock1->setCompanyName("Google");
			$ownedStock2->setCompanyName("Apple");
			$ownedStock3->setCompanyName("Costco");

			$ownedStockArray = array($ownedStock1,$ownedStock2,$ownedStock3);


			$portfolio->createOwnedStocks(array("GOOG","AAPL","COST"),$ownedStockArray);

			$ownedStocks = $portfolio->getOwnedStock();
			$googStock = $ownedStocks["GOOG"];
			$aaplStock  = $ownedStocks["AAPL"];
			$costStock = $ownedStocks["COST"];

			$this->assertEquals($tickerName1,$googStock->getTicker());
			$this->assertEquals($tickerName2,$aaplStock->getTicker());
			$this->assertEquals($tickerName3,$costStock->getTicker());

			$this->assertEquals($numOwned1,$googStock->getNumberOwned());
			$this->assertEquals($numOwned2,$aaplStock->getNumberOwned());
			$this->assertEquals($numOwned3,$costStock->getNumberOwned());

			$this->assertEquals($compName1,$googStock->getCompanyName());
			$this->assertEquals($compName2,$aaplStock->getCompanyName());
			$this->assertEquals($compName3,$costStock->getCompanyName());




		}

		public function testCreateTrackedStocks(){
			$portfolio = new Portfolio();
			$tickerArray = array("GOOG","AAPL","COST");
			$tickerName1 = "GOOG";
			$tickerName2 = "AAPL";
			$tickerName3 = "COST";
		
			$compName1 = "Google";
			$compName2 = "Apple";
			$compName3 = "Costco";

			$trackedStock1 = new TrackedStock();
			$trackedStock2 = new TrackedStock();
			$trackedStock3 = new TrackedStock();

			$trackedStock1->setTicker("GOOG");
			$trackedStock2->setTicker("AAPL");
			$trackedStock3->setTicker("COST");

			$trackedStock1->setCompanyName("Google");
			$trackedStock2->setCompanyName("Apple");
			$trackedStock3->setCompanyName("Costco");

			$trackedStockArray = array($trackedStock1,$trackedStock2,$trackedStock3);


			$portfolio->createWatchedStocks(array("GOOG","AAPL","COST"),$trackedStockArray);

			$trackedStocks = $portfolio->getTrackedStock();
			$googStock = $trackedStocks["GOOG"];
			$aaplStock  = $trackedStocks["AAPL"];
			$costStock = $trackedStocks["COST"];

			$this->assertEquals($tickerName1,$googStock->getTicker());
			$this->assertEquals($tickerName2,$aaplStock->getTicker());
			$this->assertEquals($tickerName3,$costStock->getTicker());

			$this->assertEquals($compName1,$googStock->getCompanyName());
			$this->assertEquals($compName2,$aaplStock->getCompanyName());
			$this->assertEquals($compName3,$costStock->getCompanyName());
		}
	}

?>
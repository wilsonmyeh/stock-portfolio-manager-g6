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


	class BuyingStockTest extends PHPUnit_Framework_TestCase
	{
		//buying a stock that doesn't exist, nothing should change about the portfolio
		public function testBuyingStockThatDoesNotExistShouldResultInUnchangedPortfolio(){
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

		        //now try to buy 
		        $numShares = (int) 12; //any number of shares
		    	$purchasePrice = (double) 12; //any price point
		    	$stockTicker = "ZBT"; //a stock that doesn't exist

				$prevAccountBalance = $portfolioObj->getBankBalance();

				$portfolioObj->buyStock($stockTicker, $numShares, $purchasePrice); //buy 1 share of the first stock for 25 dollars

				//get new stock objects information
				$newStockArray = $portfolioObj->getOwnedStock();
				$newAccountBalance = $portfolioObj->getBankBalance();

				//Assert
				$this->assertEquals($prevAccountBalance, $newAccountBalance, "pre and post purchase account balances are not the same when buying new stock that doesn't exist");

		    }
		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }
		}

		//buying a stock with insufficient funds, nothing should change about the portfolio
		public function testBuyingStockWithInsufficientFundsShouldResultInUnchangedPortfolio(){
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

		        //now try to buy 
		        $numShares = (int) 330; //test values for number of shares and purchase price to buy
		    	$purchasePrice = (double) 30000.60; //ridiculously large value
		    	$stockTicker = "GOOG"; //can be either a stock they own or do not own

				$prevAccountBalance = $portfolioObj->getBankBalance();

				$portfolioObj->buyStock($stockTicker, $numShares, $purchasePrice); //buy 1 share of the first stock for 25 dollars

				//get new stock objects information
				$newStockArray = $portfolioObj->getOwnedStock();
				$newAccountBalance = $portfolioObj->getBankBalance();

				//Assert
				$this->assertEquals($prevAccountBalance, $newAccountBalance, "pre and post purchase account balances are incorrect when buying new stock without sifficient funds");

		    }
		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }

		}

		//buying a stock that was not previously owned in the portfolio with sufficient funds
		//should update account balance, add a new stock object with the current date, the current price in Yahoo, and the specified number of shares
		public function testBuyingNotPreviouslyOwnedStockWithSufficientFundsShouldCreateNewLocalOwnedStockObjectAndPushUpdatesToLocalObjectAndParse(){
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

		    

			    //Act
			    $numShares = (int) 2; //test values for number of shares and purchase price to buy
			    $purchasePrice = (double) 25.0;

			    $stockTicker="PBR"; //stock that I do not own currently

				//get previous stocks owned before buying stock
				$prevStockArray = $portfolioObj->getOwnedStock();
				
				$portfolioObj->buyStock($stockTicker, $numShares, $purchasePrice); //buy shares of new stock 

				$newStockArray = $portfolioObj->getOwnedStock();

				$newStockObj = $newStockArray[$stockTicker];
				$newStockPurchasePrice = $newStockObj->getInitialPurchasePrice();
				$newStockNumberOwned = $newStockObj->getNumberOwned();

				// Assert
				$this->assertArrayNotHasKey($stockTicker, $prevStockArray, "pre-purchase, previous stock array incorrectly contains the new stock when buying new stock that was not previously owned");
				$this->assertArrayHasKey($stockTicker, $newStockArray, "post-purchase, new stock array incorrectly does not contain the new stock when buying new stock that was not previously owned");
				$this->assertEquals($newStockNumberOwned, $numShares, "number of shares owned did not match when buying new stock that was not previously owned");
				$this->assertEquals($newStockPurchasePrice, $purchasePrice, "purchase price did not match when buying new stock that was not previously owned");


				//go back and delete the PBR object from Parse so that this test can continue to be run multiple times
				$query = new ParseQuery("Portfolio");
			    $query->equalTo("username", "rebecca@usc.edu");

			    try{
			    	//Query for stocks that the user owns in their protfolio
			        $portfolio2 = $query->first();

			        $stockNames = $portfolio2->get("stockNames");
			        $stockPurchaseDates = $portfolio2->get("purchaseDates");
			        $stockPurchasePrices = $portfolio2->get("purchasePrices");
			        $numberStock = $portfolio2->get("numberShares");

			        if (($key = array_search($stockTicker, $stockNames)) !== false) {
	    				unset($stockNames[$key]);
	    				$portfolio2->setArray("stockNames", $stockNames);
					}	

			        //remove it from purhcaseDates
	                unset($stockPurchaseDates[$stockTicker]);
	                $portfolio2->setAssociativeArray("purchaseDates", $stockPurchaseDates);  

	                //remove it from purhcasePrices
	                unset($stockPurchasePrices[$stockTicker]);
	                $portfolio2->setAssociativeArray("purchasePrices", $stockPurchasePrices);  

	                //remove it from numberShares
	                unset($numberStock[$stockTicker]);
	                $portfolio2->setAssociativeArray("numberShares", $numberStock);  

	                $this->assertArrayNotHasKey($stockTicker, $stockNames, "failed to delete PBR from parse names list when trying to buy stock not previously owned");
	                $this->assertArrayNotHasKey($stockTicker, $stockPurchaseDates, "failed to delete PBR from parse dates list when trying to buy stock not previously owned");
	                $this->assertArrayNotHasKey($stockTicker, $stockPurchasePrices, "failed to delete PBR from parse price list when trying to buy stock not previously owned");
	                $this->assertArrayNotHasKey($stockTicker, $numberStock, "failed to delete PBR from parse shares list when trying to buy stock not previously owned");

	                try{ //save this update to parse
	              		$portfolio2->save();
	            	} 
	            	catch (ParseException $ex) {  
	              	echo 'Failed to update stock names when buying stock ' . $ex->getMessage();
	            	}

		    	}

		    	catch (ParseException $ex) {
		    		echo "could not test previously owned stock--error retrieving Parse portfolio";
		   		}
		
			}

			catch (ParseException $ex) {
			    	echo "could not test previously owned stock--error retrieving Parse portfolio";
			}
		
		}		

		public function testBuyingPreviouslyOwnedStockWithSufficientFundsShouldOnlyUpdateNumberOfSharesAndAccountBalance()
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
		    $array = $portfolioObj->getOwnedStock();
		    reset($array);
			$stockTicker = key($array);

			//get previous stock object associated with stockTicker before buying stock
			$tempStockArray = $portfolioObj->getOwnedStock();
			$prevStockObj = $tempStockArray[$stockTicker]; 
			$prevStockPurchasePrice = $prevStockObj->getInitialPurchasePrice();
			$prevStockNumberOwned = $prevStockObj->getNumberOwned();

			$prevAccountBalance = $portfolioObj->getBankBalance();

			$portfolioObj->buyStock($stockTicker, $numShares, $purchasePrice); //buy 1 share of the first stock for 25 dollars

			//get new stock objects information
			$newStockObj = $tempStockArray[$stockTicker];
			$newStockPurchasePrice = $newStockObj->getInitialPurchasePrice();
			$newStockNumberOwned = $newStockObj->getNumberOwned();

			$newAccountBalance = $portfolioObj->getBankBalance();

			// Assert
			$this->assertEquals($prevStockPurchasePrice, $newStockPurchasePrice, "Old purchase price and new purchase price not equal when purchasing stock that was already owned");

			$this->assertEquals( ((int)$prevStockNumberOwned ) + (int)$numShares, $newStockNumberOwned, "number of shares owned did not increment properly when purchasing stock that was already owned");

			$this->assertEquals((double)$prevAccountBalance - ( (int)$numShares * (double)$purchasePrice ), $newAccountBalance, "remaining account balances incorrect when purchasing stock that was arleady owned");
		}
	}
?>

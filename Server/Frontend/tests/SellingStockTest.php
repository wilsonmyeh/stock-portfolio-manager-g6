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


	class SellingStockTest extends PHPUnit_Framework_TestCase
	{
		//selling a stock that doesn't exist, nothing should change about the portfolio
		public function testSellingStockThatDoesNotExistShouldResultInUnchangedPortfolio(){
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

		        //now try to sell
		        $numShares = (int) 12; //any number of shares
		    	$stockTicker = "ZBT"; //a stock that doesn't exist

		    	$prevArray = $portfolioObj->getOwnedStock();

				$prevAccountBalance = $portfolioObj->getBankBalance();

				$portfolioObj->sellStock($stockTicker, $numShares); //sell shares of a non existant stock

				//get new stock objects information
				$newStockArray = $portfolioObj->getOwnedStock();
				$newAccountBalance = $portfolioObj->getBankBalance();

				//Assert
				$this->assertEquals($prevArray, $newStockArray, "pre and post owned arrays are not the same when selling new stock that doesn't exist");
				//Assert
				$this->assertEquals($prevAccountBalance, $newAccountBalance, "pre and post sale account balances are not the same when selling new stock that doesn't exist");

		    }
		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }
		}

		//selling a stock that is owned, but trying to sell more shares than owned
		public function testSellingOwnedStockWithInsufficientSharesShouldResultInUnchangedPortfolio(){
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

		        //now try to sell
		        $numShares = (int) 800; //large value for number of shares trying to sell

		    	 //get the ticker of the first stock they own
			    $array = $portfolioObj->getOwnedStock();
			    reset($array);
				$stockTicker = key($array);

				$prevArray = $portfolioObj->getOwnedStock();
				$prevAccountBalance = $portfolioObj->getBankBalance();

				$portfolioObj->sellStock($stockTicker, $numShares); //try to sell too many shares of the first stock they own

				//get new stock objects information
				$newStockArray = $portfolioObj->getOwnedStock();
				$newAccountBalance = $portfolioObj->getBankBalance();

				//Assert
				$this->assertEquals($prevAccountBalance, $newAccountBalance, "pre and post sale account balances don't match when selling more shares than owned");
				//Assert
				$this->assertEquals($prevArray, $newStockArray, "pre and post sale arrays of owned stocks don't match when selling more shares than owned");

		    }
		    catch (ParseException $ex) {
		    	echo "could not test previously owned stock--error retrieving Parse portfolio";
		    }

		}

		//selling a stock that was not previously owned in the portfolio with should result in unchanged portfolio
		public function testSellingNotOwnedStockShouldResultInUnchangedPortfolio(){
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
			    //now try to sell
		        $numShares = (int) 12; //any number of shares trying to sell

			    $stockTicker="PBR"; //stock that I do not own currently

				//get previous stocks owned before buying stock
				$prevStockArray = $portfolioObj->getOwnedStock();
				$prevAccountBalance = $portfolioObj->getBankBalance(); //previous balance
				
				$portfolioObj->sellStock($stockTicker, $numShares); //sell shares of stock not owned

				$newStockArray = $portfolioObj->getOwnedStock();
				$newAccountBalance = $portfolioObj->getBankBalance();

				//Assert
				$this->assertEquals($prevAccountBalance, $newAccountBalance, "pre and post sale account balances don't match when selling stock I do not own");
				//Assert
				$this->assertEquals($prevStockArray, $newStockArray, "pre and post sale arrays of owned stocks don't match when selling more stock I do not own");
			}

			catch (ParseException $ex) {
			    	echo "could not test previously owned stock--error retrieving Parse portfolio";
			}
		
		}		

		public function testSellingSomeSharesOfOwnedStockShouldOnlyUpdateNumberOfSharesAndAccountBalance()
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
 				//now try to sell
		    	 //get the ticker of the first stock they own
			    $array = $portfolioObj->getOwnedStock();
			    reset($array);
				$stockTicker = key($array);
				$numShares = 1; //sell just some of the shares owned

				$prevArray = $portfolioObj->getOwnedStock();
				$prevAccountBalance = $portfolioObj->getBankBalance();
				$prevSharesOwned = $prevArray[$stockTicker]->getNumberOwned();

				$portfolioObj->sellStock($stockTicker, $numShares); //try to sell too many shares of the first stock they own

				//get new stock objects information
				$newStockArray = $portfolioObj->getOwnedStock();
				$newAccountBalance = $portfolioObj->getBankBalance();
				$newSharesOwned = $newStockArray[$stockTicker]->getNumberOwned();

				//Assert
				$this->assertGreaterThan($prevAccountBalance, $newAccountBalance, "post sale account balances is not greater than previous sale account balance when selling owned shares of stock");
				//Assert
				$this->assertEquals(count($prevArray), count($newStockArray), "size of pre and post sale arrays of owned stocks don't match when selling only some shares owned");

				//Assert
				$this->assertEquals($prevSharesOwned - (int)$numShares, (int) $newSharesOwned, "size of post sale arrays of owned stocks don't match after selling some shares of owned stock");

		}



		public function testSellingAllSharesOfOneOwnedStockShouldRemoveStockObjectFromOwnedListAndUpdateAccountBalance()
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
 				//now try to sell
		    	 //get the ticker of the first stock they own
			    $array = $portfolioObj->getOwnedStock();
			    reset($array);
				$stockTicker = key($array);
				$numShares = $array[$stockTicker]->getNumberOwned(); //sell all shares owned of this stock

				$prevArray = $portfolioObj->getOwnedStock();
				$prevAccountBalance = $portfolioObj->getBankBalance();

				$portfolioObj->sellStock($stockTicker, $numShares); //try to sell too many shares of the first stock they own

				//get new stock objects information
				$newStockArray = $portfolioObj->getOwnedStock();
				$newAccountBalance = $portfolioObj->getBankBalance();

				//Assert
				$this->assertGreaterThan($prevAccountBalance, $newAccountBalance, "post sale account balances is not greater than previous sale account balance when selling owned shares of stock");
				//Assert
				$this->assertEquals(count($prevArray) - 1, count($newStockArray), "size of post sale array is not one less than pre sale array when selling all shares owned of a stock");

				//Assert
				$this->assertArrayNotHasKey($stockTicker, $newStockArray, "post sale array still contains stock after selling all shares owned of that stock");

				//now re-buy the same shares of the same stock so this test can run over and over again
				$purchasePrice = (double) 5.67; //any purchase price will do
				$portfolioObj->buyStock($stockTicker, $numShares, $purchasePrice);

		}
	}
?>

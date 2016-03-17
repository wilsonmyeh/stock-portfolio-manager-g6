<?php 
	session_start();

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

	class RemovingTrackedStocksTest extends PHPUnit_Framework_TestCase
	{
		//try to remove a stock on the watchlist
		//should result in watchlist no longer having that stock
		public function testRemovingTrackedStockShouldResultInChangedWatchlist(){
			//Make a new global object for this test
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


		   	        //Create account information
		   	        $accountObj = new Account();
					$accountObj->setPortfolio($portfolioObj);
					$accountObj->setUsername("rebecca@usc.edu");
		   	       	$_SESSION['account'] =  $accountObj;

		   	       //get the ticker of the first stock they track
		    		$array = $portfolioObj->getTrackedStock();
		    		reset($array);
					$stockTicker = key($array);

		   	       	$prevCount = count($portfolioObj->getTrackedStock());

		   	       	$this->assertArrayHasKey($stockTicker, $portfolioObj->getTrackedStock(), "tracked stock array mistakenly does not havestock ticker before removing it from the watchlist");

		   	       	include_once('removeWatchedStock.php');

					removeWatchedStock($stockTicker);

		   	       	$postCount = count($portfolioObj->getTrackedStock());

		   	       	$this->assertArrayNotHasKey($stockTicker, $portfolioObj->get, "tracked stock array mistakenly has stock ticker after removing it from the watchlist");

		   	       	$this->assertEquals($prevCount - 1, $postCount, "post size of tracked stock array is not one less than previous size after removing stock from the watchlist");

		   	       	//re-watch this stock to help make sure the test account won't run out of things on the watchlist
		   	       	$query2 = new ParseQuery("Watchlist");
			   		$query2->equalTo("username", "rebecca@usc.edu");

			    	try{
			    		//Query for stocks that the user owns in their protfolio
			        	$watchlist2 = $query2->first();

			        	$stockNames2 = $watchlist2->get("stockNames");
			        
			        	array_push($stockNames2, $stockTicker);
			        	
	    				$watchlist2->setArray("stockNames", $stockNames2);
						

	                	try{ //save this update to parse
	              			$watchlist2->save();
	            		} 
	            		catch (ParseException $ex) {  
	              			echo 'Failed to update watchlist when tracking stock ' . $ex->getMessage();
	            		}
	            	}
	            	catch(ParseException $ex){

	            	}

		   	    }
		   	    catch (ParseException $ex) {
		   	    	echo "error retrieving portfolio in testAddingAlreadyTrackedStockShouldResultInUnchangedWatchlist()";
		   	    }

			} 

	}
?>

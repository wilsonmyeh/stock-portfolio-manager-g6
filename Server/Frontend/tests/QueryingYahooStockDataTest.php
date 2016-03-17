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


	class QueryingYahooStockDataTest extends PHPUnit_Framework_TestCase
	{
		//try to query fake stocks from yahoo
		//should return no information
		public function testThatQueryingNonexistentStocksReturnNoInformation(){
		      //create a new yahoo stock object
		      $objYahooStock = new YahooStock;
		      $objYahooStock->addFormat("snl1d1"); 

		      $stockName = "ZBT";
		  
		      $objYahooStock->addStock($stockName);

		      $stockNameResult;

		      foreach( $objYahooStock->getQuotes() as $code => $stock){
		        $stockNameResult = $stock[1];
		      }

			//Assert
			$this->assertEquals($stockNameResult, "N/A", "fake stock returns valid information from Yahoo Stock");

		}

		//try to query a single stock point from yahoo
		//should only return data on one stock
		public function testThatQueryingSingleStockReturnsOnlySingleResult(){
		      //create a new yahoo stock object
		      $objYahooStock = new YahooStock;
		      $objYahooStock->addFormat("snl1d1"); 

		      $stockName = "AAPL";
		  
		      $objYahooStock->addStock($stockName);

		      $stockNameResult;

		      foreach( $objYahooStock->getQuotes() as $code => $stock){
		        $stockNameResult = $stock[1];
		      }

			//Assert
			$this->assertEquals(1, count($objYahooStock->getQuotes()), "querying a single stock value does not return only 1 result from yahoo");

		}

		//try to query multiple values from yahoo
		//should return data on all values
		public function testThatQueryingMultipleStocksReturnsSameNumberOfResults(){
		      //create a new yahoo stock object
		      $objYahooStock = new YahooStock;
		      $objYahooStock->addFormat("snl1d1"); 

		      $stocks = array("AAPL", "MSFT", "GOOG", "AMZN");

		      foreach($stocks as $code=>$stock){
		        $objYahooStock->addStock((string)$stock);
		      }

		      $stockResultNames = array();

		      foreach( $objYahooStock->getQuotes() as $code => $stock){
		        array_push($stockResultNames, (string)$stock[0]);
		      }

			//Assert
			$this->assertEquals(count($stocks), count($stockResultNames), "size of array returned from Yahoo Finance do not match size of array queried");
			
		}
		  
	}
?>

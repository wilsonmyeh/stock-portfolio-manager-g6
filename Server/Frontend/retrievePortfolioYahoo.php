<?php
	//enable global variables
	session_start();

	include_once('../Portfolio.class.php');
	include_once('../Account.class.php');
	include_once('YahooFinance.php');

	//Allow for errors to be displayed
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);	

	function pullPortfolioDataFromYahoo(){
		$objYahooStock = new YahooStock;
		$objYahooStock->addFormat("snl1d1c1p2"); 

		$stockList = array("CMG", "COST", "NFLX", "EBAY", "GLD"); //hard coded test case for now
		// $stocks = _SESSION["account"] -> portfolio -> ownedStocks;
		
		foreach($stockList as $code=>$stock){
			$objYahooStock->addStock((string)$stock);
		}

		// COMMENT THIS IN WHEN WE HAVE AN ACTUAL LOCATION OBJECT
		// foreach($stocks as $code => $stock){
		// $objYahooStock->addStock((string)$stock->getTicker());
		// }

		//THIS IS THE LINE TO INCLUDE LATER INSIDE OF THE FORLOOP TO PRINT OUT QUANTITY
		/* Quantity: <?php echo $stockList[(string)$stock[0]]->getNumberOwned(); ?> <br />;*/
		foreach( $objYahooStock->getQuotes() as $code => $stock){
			?>
			Ticker: <?php echo $stock[0]; ?> <br />
			Company Name: <?php echo $stock[1]; ?> <br />
			Last Trade Price: <?php echo'$ '.$stock[2]; ?> <br />
			Last Trade Date: <?php echo $stock[3]; ?> <br />
			Dollar Change: <?php echo '$ '. $stock[4]; ?> <br />
			Percent Change: <?php echo $stock[5]; ?> <br /><br />
			<?php
		}
	}

	pullPortfolioDataFromYahoo();
?>
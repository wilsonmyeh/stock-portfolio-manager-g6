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

	function pullWatchlistDataFromYahoo(){
		$objYahooStock = new YahooStock;
		$objYahooStock->addFormat("snl1d1c1p2"); 

		$stockNames = array("AAPL", "GOOG", "AMZN", "FB");//hard coded test case for now
		//*****UPDATE LOCAL VARIABLES*****$stockNames = _SESSION["ownedStocks"];
		foreach($stockNames as $code => $stock){
			$objYahooStock->addStock((string)$stock);
		}

		foreach( $objYahooStock->getQuotes() as $code => $stock){
			?>
			<?php echo $stock[0]; ?> <?php echo $stock[1]; ?> <br />
			Last Trade Price: <?php echo'$ '.$stock[2]; ?> <br />
			Last Trade Date: <?php echo $stock[3]; ?> <br />
			Dollar Change: <?php echo '$ '. $stock[4]; ?> <br />
			Percent Change: <?php echo $stock[5]; ?> <br /><br />
			<?php
		}	
	}

	pullWatchlistDataFromYahoo();
?>
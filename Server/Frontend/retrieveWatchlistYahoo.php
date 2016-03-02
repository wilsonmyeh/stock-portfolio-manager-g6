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
	
 	header("Access-Control-Allow-Origin: *");

	function pullWatchlistDataFromYahoo(){
		$objYahooStock = new YahooStock;
		$objYahooStock->addFormat("snl1d1c1p2"); 

		$stockNames = array("AAPL", "CMG", "COST", "FB", "GOOG");//hard coded test case for now
		//*****UPDATE LOCAL VARIABLES*****$stockNames = _SESSION["ownedStocks"];
		foreach($stockNames as $code => $stock){
			$objYahooStock->addStock((string)$stock);
		}

		foreach( $objYahooStock->getQuotes() as $code => $stock){

			?>
			<!doctype html>

			<html>

			<link rel="stylesheet" type="text/css" href="style.css">
			
			<div class="stockmodule" id=<?php echo $stock[0]; ?> >


				<b><?php echo $stock[1]; ?> (<?php echo $stock[0]; ?>)</b> <br />
				<b>Last Trade Price:</b> <?php echo'$ '.$stock[2]; ?> <br />
				<b>Last Trade Date:</b> <?php echo $stock[3]; ?> <br />
				<b>Dollar Change:</b> <?php echo '$ '. $stock[4]; ?> <br />
				<b>Percent Change:</b> <?php echo $stock[5]; ?> <br />
				<input type="checkbox">Graph
				
				<form action="http://localhost/Frontend/removeWatchedStock.php" method="post" enctype="multipart/form-data">
					<input hidden type="text" name="ticker" value = <?php echo $stock[0]; ?>>
					<input type="submit" value="X">
				</form>

			</div>

			</html>

			<?php

		}	
	}

	pullWatchlistDataFromYahoo();
?>
<?php
	// //enable global variables
	// session_start();

	include_once('../Portfolio.class.php');
	include_once('../Account.class.php');
	include_once('../Stock.class.php');
	include_once('../TrackedStock.class.php');
	include_once('../OwnedStock.class.php');
	include_once('YahooFinance.php');

	//enable global variables
	session_start();

	//Allow for errors to be displayed
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
 	header("Access-Control-Allow-Origin: *");

 	pullWatchlistDataFromYahoo();
	function pullWatchlistDataFromYahoo(){
		$objYahooStock = new YahooStock;
		$objYahooStock->addFormat("snl1d1c1p2"); 

		$stockNames = array_keys($_SESSION['account'] -> getPortfolio() -> getTrackedStock());
		
		foreach($stockNames as $code => $stock){
			$objYahooStock->addStock((string)$stock);
		}
// 
		foreach( $objYahooStock->getQuotes() as $code => $stock){

			?>
			<!doctype html>

			<html>

			<link rel="stylesheet" type="text/css" href="style.css">
			
			<div class="stockmodule" id=<?php echo $stock[0]; ?> >


				<b><?php echo $stock[1]; ?> (<?php echo $stock[0]; ?>)</b> <br />
				<b>Price:</b> <span class="money"><?php echo'$ '.$stock[2]; ?></span> <br />
				<b>Dollar Change:</b>  <span class="money"><?php echo '$ '. $stock[4]; ?></span> <br />
				<b>Percent Change:</b> <span class="money"><?php echo $stock[5]; ?></span> <br />
				<input type="checkbox" onchange="addStock(<?php echo '\''.$stock[0].'\''; ?>);">Graph
				
				<form action="http://localhost/Frontend/removeWatchedStock.php" method="POST" enctype="multipart/form-data" style="display: inline;">
					<input hidden type="text" name="ticker"  value = <?php echo $stock[0]; ?>>
					<input type="submit" value="X">
				</form>

			</div>

			</html>

			<?php

		}	
	}

	// pullWatchlistDataFromYahoo();
?>
<html>
<body>
	<?php

		include_once('../Portfolio.class.php');
		include_once('../Account.class.php');
		include_once('../Stock.class.php');
		include_once('../TrackedStock.class.php');
		include_once('../OwnedStock.class.php');
		include_once('YahooFinance.php');

		//Enable global variables
	 	session_start();

	 	//Allow for errors to be displayed
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	
		//Initialize parse and the classes it needs
		require '../../vendor/autoload.php';
		use Parse\ParseUser;
		use Parse\ParseClient;
		use Parse\ParseException;
		ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');

		

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			if($_POST['action'] == 'Buy'){
				$globalAccount = $_SESSION['account'];
				$tickerName = $_POST["ticker"];
				$stockQuantity = $_POST["quantity"];
				
				$objYahooStock = new YahooStock;
				$objYahooStock->addFormat("snl1d1t1c1p2"); 
				$objYahooStock->addStock($tickerName);
				$stockPrice;
				foreach( $objYahooStock->getQuotes() as $code => $stock)
				{
			  		$stockPrice = floatval($stock[2]);
				}

				$globalAccount->getPortfolio()->buyStock($tickerName,$stockQuantity,$stockPrice);
			}
			else if($_POST['action'] == 'Sell'){

				$globalAccount = $_SESSION['account'];
				$tickerName = $_POST["ticker"];
				$stockQuantity = $_POST["quantity"];

				$globalAccount->getPortfolio()->sellStock($tickerName,$stockQuantity);

			}
		}


	?>
</body>
</html>
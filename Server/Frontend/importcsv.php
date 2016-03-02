<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include_once('../Portfolio.class.php');
	include_once('../Account.class.php');
	include_once('../Stock.class.php');
	include_once('../TrackedStock.class.php');
	include_once('../OwnedStock.class.php');

	 //Enable global variables
	 session_start();

	require '../../vendor/autoload.php';
	use Parse\ParseClient;
	use Parse\ParseObject;
	use Parse\ParseQuery;
	ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');

	function uploadCSVToServer(){
		readfile("http://localhost/Frontend/dashboard.html");
		//modified code from http://www.w3schools.com/php/php_file_upload.asp
		$target_dir = "";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if file already exists
		if (file_exists($target_file)) {
			//echo "Sorry, file already exists.";
			$message =  "Sorry, file already exists.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			//echo "Sorry, your file is too large.";
			$message =  "Sorry, your file is too large.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			$uploadOk = 0;
		}
		// Allow only CSV files
		if($imageFileType != "csv") {
			//echo "Sorry, only CSV files are allowed.";
			$message =  "Sorry, only CSV files are allowed.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			//echo "Sorry, your file was not uploaded.";
			
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				$message =  "Portfolio imported sucessfully!";
				echo "<script type='text/javascript'>alert('$message');</script>";
				parseCSVToArrays(basename($_FILES["fileToUpload"]["name"]));
			} else {
				//echo "Sorry, there was an error uploading your file.";
			}
		}
		
	}

	function parseCSVToArrays($CSVFilename){
		$file = fopen("portfolio.csv","r");
		$header = fgetcsv($file);
		while($row = fgetcsv($file)){
			$stockTickerNames[] = $row[0];
		}
		fclose($file);

		$stockTickerShares = $stockTickerNames;
		$stockTickerDates = $stockTickerNames;
		$stockTickerPrices = $stockTickerNames;
		$stockTickerShares = array_flip($stockTickerShares);
		$stockTickerDates = array_flip($stockTickerDates);
		$stockTickerPrices = array_flip($stockTickerPrices);

		$file = fopen("portfolio.csv","r");
		$header = fgetcsv($file);
		foreach(array_keys($stockTickerShares) as $key) {
			$row = fgetcsv($file);
			$stockTickerShares[$key] = intval($row[3]);
		}
		fclose($file);

		$file = fopen("portfolio.csv","r");
		$header = fgetcsv($file);
		foreach(array_keys($stockTickerDates) as $key) {
			$row = fgetcsv($file);
			$stockTickerDates[$key] = $row[1];
		}
		fclose($file);

		$file = fopen("portfolio.csv","r");
		$header = fgetcsv($file);
		foreach(array_keys($stockTickerPrices) as $key) {
			$row = fgetcsv($file);
			$stockTickerPrices[$key] = floatval($row[2]);
		}
		fclose($file);


		sort($stockTickerNames);
		ksort($stockTickerShares);
		ksort($stockTickerDates);
		ksort($stockTickerPrices);

		foreach($stockTickerNames as $x){
			echo "Key=" . $x;
			echo "<br>";
		}
		foreach($stockTickerShares as $x => $x_value){
			echo "Key=" . $x . ", Value=" . $x_value;
			echo "<br>";
		}
		foreach($stockTickerDates as $x => $x_value){
			echo "Key=" . $x . ", Value=" . $x_value;
			echo "<br>";
		}
		foreach($stockTickerPrices as $x => $x_value){
			echo "Key=" . $x . ", Value=" . $x_value;
			echo "<br>";
		}

		createNewPortfolioInParse($stockTickerNames, $stockTickerShares, $stockTickerDates, $stockTickerPrices);
		populatePortfolio($stockTickerNames, $stockTickerShares, $stockTickerDates, $stockTickerPrices);
	}

	function createNewPortfolioInParse($stockTickerNames, $stockTickerShares, $stockTickerDates, $stockTickerPrices){
		$queryPortfolio = new ParseQuery("Portfolio");
		$queryPortfolio->equalTo("username", $_SESSION['account']->getUsername());
		try {
			 	$newPortfolio = $queryPortfolio->first();
				$newPortfolio->setArray("stockNames", $stockTickerNames);
				$newPortfolio->setAssociativeArray("purchaseDates", $stockTickerDates);
				$newPortfolio->setAssociativeArray("purchasePrices", $stockTickerPrices);
				$newPortfolio->setAssociativeArray("numberShares", $stockTickerShares);

				try {
				  $newPortfolio->save();
				} catch (ParseException $ex) {  
				  // Execute any logic that should take place if the save fails.
				  // error is a ParseException object with an error code and message.
				  echo 'Failed to create new object, with error message: ' . $ex->getMessage();
				}
		}
		catch (ParseException $ex) {  
				  // Execute any logic that should take place if the save fails.
				  // error is a ParseException object with an error code and message.
				  echo 'Failed to retrieve portfolio, with error message: ' . $ex->getMessage();
		}

	}

	function populatePortfolio($stockTickerNames, $stockTickerShares, $stockTickerDates, $stockTickerPrices){
		//Load global variable
		$globalAccount = $_SESSION['account'];

		 $stockArray = array(); //owned stock array

		  //Create n array of the owned stock to be added to the portfolio
		  for($x = 0; $x < count($stockTickerNames);$x++){
  	        	$ownedStock = new OwnedStock();
				$ownedStock->setTicker($stockTickerNames[$x]);
		      	$ownedStock->setInitialDate($stockTickerDates[$stockTickerNames[$x]]);
		  		$ownedStock->setInitialPurchasePrice($stockTickerPrices[$stockTickerNames[$x]]);
		   	    $ownedStock->setNumberOwned($stockTickerShares[$stockTickerNames[$x]]);

		   	    array_push($stockArray,$ownedStock);
		   }
		   	$globalAccount->getPortfolio()->createOwnedStocks($stockTickerNames,$stockArray);

		 $_SESSION['account'] =  $globalAccount;


	}

	uploadCSVToServer();
?>
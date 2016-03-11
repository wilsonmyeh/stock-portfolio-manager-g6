
	 <?php 
	 	//Enable global variables
	 	session_start();

		//Allow for errors to be displayed
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		include_once('../Portfolio.class.php');
		include_once('../Account.class.php');
		include_once('../Stock.class.php');
		include_once('../TrackedStock.class.php');
		include_once('../OwnedStock.class.php');

		// //Enable global variables
	 // 	session_start();
	 	
	
		//Initialize parse and the classes it needs
		require '../../vendor/autoload.php';
		use Parse\ParseUser;
		use Parse\ParseClient;
		use Parse\ParseException;
		use Parse\ParseQuery;
		ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');
		
	 	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
	 	//Get input from text fields in login.html
	 	$usernameText = formatUserInput($_POST["user"]);
	 	$passwordText = formatUserInput($_POST["password"]);
	 	
		try {
				//Check for successful log in 
			 	$user = ParseUser::logIn($usernameText, $passwordText);
				$message = "Successful log in!";

				//Loading class variables for the corresponding user in the database
				$portfolioObj = new Portfolio();
				$portfolioObj->setUsername($usernameText);
				

				$queryStock = new ParseQuery("Portfolio");
		   	    $queryStock->equalTo("username", $usernameText);

		   	    $queryTrack = new ParseQuery("Watchlist");
		   	    $queryTrack->equalTo("username", $usernameText);
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
					$accountObj->setUsername($usernameText);
		   	       	$_SESSION['account'] =  $accountObj;

		   	    }
		   	    catch (ParseException $ex) {

		   	    //They have an account that does not have a portfolio yet
		   	     $portfolioObj->setBankBalance(10000);
		   	     //Create account information
		   	        $accountObj = new Account();
					$accountObj->setPortfolio($portfolioObj);
					$accountObj->setUsername($usernameText);
		   	       	$_SESSION['account'] =  $accountObj;

		   	    }

				echo "<script type='text/javascript'>alert('$message');</script>";
				readfile("http://localhost/Frontend/dashboard.html");
			} 
			catch (ParseException $ex) 
			{
				$message = $ex->getMessage();
				echo "<script type='text/javascript'>alert('$message');</script>";
				readfile("http://localhost/Frontend/login.html");
			}
	 }

	 	//Gets rid of special characters and slashes from input
	 
	 	function formatUserInput($input){
	 		$input = trim($input);
	 		$input = stripslashes($input);
	 		return $input;
	 	}
	 	
	 ?> 

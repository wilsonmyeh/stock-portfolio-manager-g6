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


	?>
	<!doctype html>

	<html>

	<div rel="stylesheet" type="text/css" href="style.css">

		<b>Bank Balance:</b>  <span class="money"><?php echo '$ '. $_SESSION['account']->getPortfolio()->getBankBalance(); ?></span> <br />
		<b>Portfolio Value:</b> <span class="money"><?php echo '$ '. $_SESSION['account']->getPortfolio()->getTotalValue(); ?></span> <br />

	</div>

	</html>

	<?php

?>
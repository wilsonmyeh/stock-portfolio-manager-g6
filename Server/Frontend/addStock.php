<?php
include_once "../Stock.class.php";
include_once "../Graph.class.php";
include_once "../TrackedStock.class.php";
include_once "../OwnedStock.class.php";
include_once "../Account.class.php";
include_once "../Portfolio.class.php";
session_start();

if(isset($_POST["ticker"])) {
	$stockArray = $_SESSION["account"]->getPortfolio()->getTrackedStock();
	$stock = $stockArray[$_POST["ticker"]];
	$stock->setInitialDate('2016/01/01');
	$_SESSION["graph"]->pullHistoricalData($stock);
	echo $_POST["ticker"];
}
else {
	echo "error";
}
?>
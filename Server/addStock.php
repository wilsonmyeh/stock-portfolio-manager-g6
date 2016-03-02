<?php
include_once "Stock.class.php";
include_once "Graph.class.php";
session_start();

if(isset($_POST["ticker"])) {
	$_SESSION[$_POST["ticker"]] = new Stock($_POST["ticker"], date_create('2016-01-15'));
	$_SESSION["graph"]->pullHistoricalData($_SESSION[$_POST["ticker"]]);
	echo $_POST["ticker"];
}
else {
	echo "error";
}
?>
<?php
include_once "../Stock.class.php";
include_once "../Graph.class.php";
include_once "../TrackedStock.class.php";
include_once "../OwnedStock.class.php";
include_once "../Account.class.php";
include_once "../Portfolio.class.php";
session_start();

$_SESSION["graph"] = new Graph();
/*$_SESSION["apple"] = new TrackedStock;
$_SESSION["apple"]->setTicker("AAPL");
$_SESSION["apple"]->setInitialDate(date_create('2016-02-01'));
$_SESSION["goog"] = new TrackedStock;
$_SESSION["goog"]->setTicker("GOOG");
$_SESSION["goog"]->setInitialDate(date_create('2016-02-01'));
$_SESSION["microsoft"] = new TrackedStock;
$_SESSION["microsoft"]->setTicker("MSFT");
$_SESSION["microsoft"]->setInitialDate(date_create('2016-02-01'));
$_SESSION["facebook"] = new TrackedStock;
$_SESSION["facebook"]->setTicker("FB");
$_SESSION["facebook"]->setInitialDate(date_create('2016-02-01'));

$_SESSION["graph"]->pullHistoricalData($_SESSION["apple"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["goog"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["microsoft"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["facebook"]);*/
foreach($_SESSION["account"]->getPortfolio()->getOwnedStock() as $stock)
{
	print_r($stock);
	$_SESSION["graph"]->pullHistoricalData($stock);
}
?>

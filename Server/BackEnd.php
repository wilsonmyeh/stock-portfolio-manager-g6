<?php
session_start();
include_once "Stock.class.php";
include_once "Graph.class.php";
include_once "TrackedStock.class.php";
include_once "OwnedStock.class.php";

$_SESSION["graph"] = new Graph();
$_SESSION["apple"] = new TrackedStock;
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
$_SESSION["graph"]->pullHistoricalData($_SESSION["facebook"]);
print_r($_SESSION["graph"]);
?>

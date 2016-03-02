<?php
session_start();
include_once "Stock.class.php";
include_once "Graph.class.php";

$_SESSION["graph"] = new Graph();
$_SESSION["apple"] = new Stock("AAPL", date_create('2016-02-01'));
$_SESSION["nflx"] = new Stock("NFLX", date_create('2016-02-01'));
$_SESSION["microsoft"] = new Stock("MSFT", date_create('2016-01-20'));
$_SESSION["facebook"] = new Stock("FB", date_create('2016-01-20'));

$_SESSION["graph"]->pullHistoricalData($_SESSION["apple"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["nflx"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["microsoft"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["facebook"]);
?>
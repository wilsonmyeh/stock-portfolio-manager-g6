<?php
include_once "../Stock.class.php";
include_once "../Graph.class.php";
include_once "../TrackedStock.class.php";
session_start();
$array = array();
foreach($_SESSION["graph"]->stocksToGraph as $stock)
{
	$array[] = $stock->getTicker();
}
header('Content-Type: application/json');
echo json_encode($array);
?>

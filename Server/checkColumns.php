<?php
include_once "Stock.class.php";
include_once "Graph.class.php";
session_start();
$array = array();
foreach($_SESSION["graph"]->stocksToGraph as $stock)
{
	$array[] = $stock->ticker;
}
header('Content-Type: application/json');
echo json_encode($array);
?>
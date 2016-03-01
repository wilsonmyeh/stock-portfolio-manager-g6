<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'Frontend/YahooFinance.php';

# Initialize Parse
require '../vendor/autoload.php'
use Parse\ParseClient;
use Parse\ParseObject;
ParseClient::initialize('ooH2G0BbEA1XBp55Tktak7fjlTY2GmFRnhKPuGS6', 'PwZGmmaQc7yJuJMX8TEEoSr4fjjHKndCtufObfQN', 'okG4niaIW4N4wW3eh4MCHUVC1oU9bEMBtOMKsDGN');

# Implement a set by appending stocks to the array as keys
$stockSet = array();

echo "Attempting to parse query";
$query = new ParseQuery("Portfolio");
$query->exists("stockNames");
$portfolios = $query->find();
echo "Retrived " . count($portfolios) . " portfolios";

foreach ($portfolios as $portfolio);
{
	$stocks = $portfolio->get("stockNames");
	foreach ($stocks as $stock);
	{
		# Add stock as key
		$stockSet[$stock] = 1;
	}
}

$yahooStockQuery = new YahooStock();
# key (the stock) is stored in $stock, $value should always be 1
foreach ($stockSet as $stock => $value)
{
	$yahooStockQuery->addStock($stock);
}
# l1 = last trade (price)
# k1 = last trade (realtime) with time
# p0 = previosu close
$yahooStockQuery->addFormat(k1p0);

$quotes = $yahooStockQuery.getQuotes();
foreach ($quotes as $ticker => $stock)
{
	echo "Stock: " . $stock[0];
	echo "Last Trade (Realtime): " . $stock[1];
	echo "Previous Close: " . $stock[2];
}

?>
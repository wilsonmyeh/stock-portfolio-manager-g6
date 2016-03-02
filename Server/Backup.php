<?php

# Start a cron job to run this M-F 4:01pm EST (stock market closing time)
# crontab -e
# Add the following line:
# 1 13 * * 1-5 lynx -dump localhost/Backup.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'Frontend/YahooFinance.php';

# Initialize Parse
require '../vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');

header("Access-Control-Allow-Origin: *");

$backup_log = fopen("logs/backup_log.txt","a");
$curTime = getdate();
# Write timestamp
fwrite($backup_log, date("m/d/y H:i:s\n"));
# Implement a set by appending stocks to the array as keys
$stockSet = array();

$query = new ParseQuery("Portfolio");
$query->exists("stockNames");
$portfolios = $query->find();
fwrite($backup_log,"Retrieved " . count($portfolios) . " portfolios\n");

foreach ($portfolios as $portfolio)
{
	$stocks = ($portfolio->get("stockNames"));
	foreach ($stocks as $stock)
	{
		# Add stock as key
		$stockSet[$stock] = 1;
	}
}

$yahooStockQuery = new YahooStock;
# key (the stock) is stored in $stock, $value should always be 1
fwrite($backup_log, "Querying " . count($stockSet) . " stocks\n");
foreach ($stockSet as $stock => $value)
{
	$yahooStockQuery->addStock($stock);
	fwrite($backup_log, $stock . "\n");
}
# s = symbol
# l1 = last trade (price)
$yahooStockQuery->addFormat('sl1');

$quotes = $yahooStockQuery->getQuotes();

$query = new ParseQuery("Backup");
$query->exists("objectId");
$objects = $query->find();

fwrite($backup_log, "Retrieved " . count($objects) . " stock objects\n");
foreach ($objects as $object)
{
	$object->destroy();
}
fwrite($backup_log, "Successfully destroyed stock objects\n");

foreach ($quotes as $ticker => $stock)
{
	fwrite($backup_log, "Code: " . $stock[0] . "\n");
	fwrite($backup_log, "Last Trade (Price Only): " . $stock[1] . "\n");
	$stockObj = ParseObject::create("Backup");
	$stockObj->set("tickerSymbol", $stock[0]);
	$stockObj->set("closingPrice", floatval($stock[1]));
	$stockObj->save();
}
fwrite($backup_log, "\n");
fclose($backup_log);
?>
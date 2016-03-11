<?php
include_once "../Stock.class.php";
include_once "../Graph.class.php";
include_once "../TrackedStock.class.php";
session_start();
$array = array();
$dateMap = array();
// check each stock that will be graphed
foreach($_SESSION["graph"]->stocksToGraph as $stock)
{
	// check each dataEntry for each stock (date and price)
	foreach($stock->historicalData as $dataEntry)
	{
		// if this particular date has not been entered yet
		if(is_null($dateMap[$dataEntry[date]]))
		{
			// create the date
			$newDate = date_create($dataEntry[date]);
			// create the array to hold the row
			$newRow = array();
			// add the new date to the row
			$newRow[] = date_format($newDate, "Y/m/d");
			// loop through the stocks to get the price for that date
			foreach($_SESSION["graph"]->stocksToGraph as $stockForPrice)
			{
				// check if this stock has price for that day
				if(!is_null($stockForPrice->historicalData[$dataEntry[date]]))
				{
					// if there is a price, add that data
					$newRow[] = $stockForPrice->historicalData[$dataEntry[date]][price];
				}
				else
				{
					// if not add null
					$newRow[] = null;
				}
			}
			// add the date to the datemap
			$dateMap[$dataEntry[date]] = true;
			// add the row to the array
			$array[] = $newRow;
		}
	}
}
// return the array
header('Content-Type: application/json');
echo json_encode($array);
?>

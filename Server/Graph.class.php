<?php
include_once("Stock.class.php");
include_once("TrackedStock.class.php");
class Graph
{
	public $stocksToGraph = array();
	
	public function pullHistoricalData(&$stock)
	{
		// the end date will be today
		$endDate = date_create();
		$baseURL = "http://query.yahooapis.com/v1/public/yql";
		// format the query with the ticker, start date, and end date
		$query = "select * from yahoo.finance.historicaldata where symbol = '" . urlencode($stock->getTicker())
		. "' and startDate = '" . urlencode(date_format($stock->getInitialDate(), 'Y-m-d'))
		. "' and endDate = '" .urlencode(date_format($endDate, 'Y-m-d')) . "'";
		
		$queryURL = $baseURL . "?q=" . urlencode($query);
		$queryURL .= "&format=json&env=store://datatables.org/alltableswithkeys";
		// send the request
		$session = curl_init($queryURL);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$jsonObj = curl_exec($session);
		$phpObj = json_decode($jsonObj);
		if(!is_null($phpObj->query->results))
		{
			foreach($phpObj->query->results->quote as $quote)
			{
				$stock->addHistoricalData($quote->Close, $quote->Date);
			}
			$this->stocksToGraph[] = $stock;
		}
	}
}

?>

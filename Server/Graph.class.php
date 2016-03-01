<?php

class Graph
{
	public $stocksToGraph = array();
	
	public function pullHistoricalData(&$stock)
	{
		$endDate = date_create();
		$baseURL = "http://query.yahooapis.com/v1/public/yql";
		
		$query = "select * from yahoo.finance.historicaldata where symbol = '" . urlencode($stock->ticker)
		. "' and startDate = '" . urlencode(date_format($stock->initialDate, 'Y-m-d'))
		. "' and endDate = '" .urlencode(date_format($endDate, 'Y-m-d')) . "'";
		
		$queryURL = $baseURL . "?q=" . urlencode($query);
		$queryURL .= "&format=json&env=store://datatables.org/alltableswithkeys";
		
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
<?php

class Stock
{
	public $ticker;
	public $currentValue;
	public $companyName;
	public $initialDate;
	public $historicalData = array();
	
	public function __construct($ticker, $initialDate)
	{
		$this->ticker = $ticker;
		$this->initialDate = $initialDate;
	}
	
	public function addHistoricalData($price, $date)
	{
		$this->historicalData[] = array("price"=>$price, "date"=>$date);
	}
	
	public function outputData()
	{
		var_dump($this->historicalData);
	}
}

?>
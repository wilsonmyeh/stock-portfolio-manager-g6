<?php

abstract class Stock
{
	public $ticker;
	public $currentValue;
	public $companyName;
	public $initialDate;
	
	
	
	public function addHistoricalData($price, $date)
	{
		$this->historicalData[] = array("price"=>$price, "date"=>$date);
	}
	
	public function outputData()
	{
		var_dump($this->historicalData);
	}

	public function getTicker(){
		return (string)$ticker;
	}

	public function setTicker($ticker){
		$this->ticker = $ticker;
	}

	public function getCurrentValue(){
		return $currentValue;
	}

	public function setCurrentValue($currentValue){
		$this->currentValue = $currentValue;
	}

	public function getCompanyName(){
		return $companyName;
	}

	public function setCompanyName($companyName){
		$this->companyName = $companyName;
	}

	public function getInitialDate(){
		return $initialDate;
	}

	public function setInitialDate($initialDate){
		$this->initialDate = $initialDate;
	}

	


}

?>
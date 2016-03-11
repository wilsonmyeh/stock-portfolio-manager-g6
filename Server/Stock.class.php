<?php

abstract class Stock
{
	private $ticker;
	private $currentValue;
	private $companyName;
	private $initialDate;
	
	public $historicalData = array();
	
	public function addHistoricalData($price, $date)
	{
		$this->historicalData[$date] = array("price"=>$price, "date"=>$date);
	}
	
	public function outputData()
	{
		var_dump($this->historicalData);
	}

	public function getTicker(){
		return $this->ticker;
	}

	public function setTicker($ticker){
		$this->ticker = $ticker;
	}

	public function getCurrentValue(){
		return $this->currentValue;
	}

	public function setCurrentValue($currentValue){
		$this->currentValue = $currentValue;
	}

	public function getCompanyName(){
		return $this->companyName;
	}

	public function setCompanyName($companyName){
		$this->companyName = $companyName;
	}

	public function getInitialDate(){
		return $this->initialDate;
	}

	public function setInitialDate($initialDate){
		$this->initialDate = $initialDate;
	}

	


}

?>

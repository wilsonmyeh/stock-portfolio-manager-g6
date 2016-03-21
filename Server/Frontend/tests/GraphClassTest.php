<?php
	require_once('../Graph.class.php');
	require_once('../Stock.class.php');
	require_once('../TrackedStock.class.php');
	require_once('../OwnedStock.class.php');

	class GraphClassTest extends PHPUnit_Framework_TestCase {
		public function testCreateAGraphObjectAndUseItToPullAStocksHistoricalData() {
			// setup
			$graph = new Graph();
			$stock = new TrackedStock();
			$stock->setTicker("GOOG");
			$stock->SetInitialDate("2016/01/01");

			// Actions
			$graph->pullHistoricalData($stock);

			// Check results
			$array = $graph->stocksToGraph;
			$this->assertContains($stock, $array);
			$this->assertNotEmpty($stock->historicalData);
		}

		public function testCreateAGraphObjectAndUseItToPullStocksHistoricalData() {
			//setup
			$graph = new Graph();
			$stock1 = new TrackedStock();
			$stock2 = new TrackedStock();
			$stock3 = new TrackedStock();
			$stock1->setTicker("GOOG");
			$stock1->SetInitialDate("2016/01/01");
			$stock2->setTicker("FB");
			$stock2->SetInitialDate("2016/01/01");
			$stock3->setTicker("MSFT");
			$stock3->SetInitialDate("2016/01/01");

			// Actions
			$graph->pullHistoricalData($stock1);
			$graph->pullHistoricalData($stock2);
			$graph->pullHistoricalData($stock3);

			// Check results
			$array = $graph->stocksToGraph;
			$this->assertContains($stock1, $array);
			$this->assertContains($stock2, $array);
			$this->assertContains($stock3, $array);
			$this->assertNotEmpty($stock1->historicalData);
			$this->assertNotEmpty($stock2->historicalData);
			$this->assertNotEmpty($stock3->historicalData);
		}
	}

?>
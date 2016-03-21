<?php
	require_once( '../TrackedStock.class.php');

	class TrackedStockTest extends PHPUnit_Framework_TestCase
	{

		public function testTrackedStockTickerMemberVariableCanBeSetAndGet()
		{
			// Arrange
			$ts = new TrackedStock();
			$ts->setTicker("GOOG");

			// Act
			$ticker = $ts->getTicker();

			// Assert
			$this->assertEquals("GOOG", $ticker);
		}

		public function testTrackedStockCurrentValueMemberVariableCanBeSetAndGet()
		{
			// Setup
			$ts = new TrackedStock();
			$ts->setCurrentValue(100);

			// Actions
			$cv = $ts->getCurrentValue();

			//Assert
			$this->assertEquals(100, $cv);
		}

		public function testTrackedStockCompanyNameMemberVariableCanBeSetAndGet()
		{
			// Setup
			$ts = new TrackedStock();
			$ts->setCompanyName("Alphabet Inc.");

			// Actions
			$cn = $ts->getCompanyName();

			// Assert
			$this->assertEquals("Alphabet Inc.", $cn);
		}

		public function testTrackedStockInitialDateMemberVariableCanBeSetAndGet()
		{
			// Setup
			$ts = new TrackedStock();
			$ts->setInitialDate("2016/01/01");

			// Actions
			$id = $ts->getInitialDate();

			// Assert
			$this->assertEquals("2016/01/01", $id);
		}
	}
?>

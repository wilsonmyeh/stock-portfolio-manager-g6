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
	}
?>

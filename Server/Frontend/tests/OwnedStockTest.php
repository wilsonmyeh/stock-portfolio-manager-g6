<?php
	require_once('../OwnedStock.class.php');

	class OwnedStockTest extends PHPUnit_Framework_TestCase {

		public function testOwnedStockNumberOwnedMemberVariableCanBeSetAndGet()
		{
			// Arrange
			$ts = new OwnedStock();
			$ts->setNumberOwned(10);

			// Act
			$no = $ts->getNumberOwned();

			// Assert
			$this->assertEquals(10, $no);
		}

		public function testOwnedStockInitialPurchasePriceMemberVariableCanBeSetAndGet()
		{
			// Setup
			$os = new OwnedStock();
			$os->setInitialPurchasePrice(100);

			// Actions
			$ipp = $os->getInitialPurchasePrice();

			// Check results
			$this->assertEquals(100, $ipp);
		}
	}
?>
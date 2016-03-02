<html>
<body>	
	<?php
		class OwnedStock extends Stock{
			private $initialPurchasePrice;
			private $numberOwned;

			public function getInitialPurchasePrice(){
				return $initialPurchasePrice;
			}

			public function setInitialPurchasePrice($initialPurchasePrice){
				$this->initialPurchasePrice = $initialPurchasePrice;
			}

			public function getNumberOwned(){
				return $numberOwned;
			}

			public function setNumberOwned($numberOwned){
				$this->numberOwned = $numberOwned;
			}
		}
	?>
</body>
</html>
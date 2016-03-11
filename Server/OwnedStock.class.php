<body>	
	<?php
		class OwnedStock extends Stock{
			private $initialPurchasePrice;
			private $numberOwned;

			public function getInitialPurchasePrice(){
				return $this->initialPurchasePrice;
			}

			public function setInitialPurchasePrice($initialPurchasePrice){
				$this->initialPurchasePrice = $initialPurchasePrice;
			}

			public function getNumberOwned(){
				return $this->numberOwned;
			}

			public function setNumberOwned($numberOwned){
				$this->numberOwned = $numberOwned;
			}
		}
	?>
</body>
<html>
<body>	
	<?php
		class Account{
			private $username;
			private $portfolio;

			public function logout(){

			}

			public function setPortfolio($portfolio){
				$this->portfolio = $portfolio;
			}

			public function getPortfolio(){
				return $this->portfolio;
			}

			public function setUsername($username){
				$this->username = $username;
			}


		}
	?>
</body>
</html>
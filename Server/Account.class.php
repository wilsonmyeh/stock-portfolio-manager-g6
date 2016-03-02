<html>
<body>	
	<?php
		class Account{
			public $username;
			public $portfolio;

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

			public function getUsername(){
				return $this->username;
			}


		}
	?>
</body>
</html>
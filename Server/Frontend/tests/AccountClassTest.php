<?php
 	require_once('../Account.class.php');
 	require_once('../Portfolio.class.php');


 	class AccountClassTest extends PHPUnit_Framework_TestCase{

 		public function testCreatingAccountClassUserName(){

 			$account = new Account();
 			$testString = "testUser";
 			$account->setUserName("testUser");
 			$this->assertEquals($testString,$account->getUserName());

 		}

 		public function testCreatingAccountPortfolio(){
 			$account = new Account();
 			$portfolio = new Portfolio();

 			$totalValue = 500000;
 			$balance = 10000;
 			$testString = "testUser";

 			$portfolio->setTotalValue(500000);
 			$portfolio->setBankBalance(10000);
 			$portfolio->setUserName("testUser");

 			$account->setPortfolio($portfolio);

 			$this->assertEquals($totalValue,$account->getPortfolio()->getTotalValue());
 			$this->assertEquals($balance, $account->getPortfolio()->getBankBalance());
 			$this->assertEquals($testString, $account->getPortfolio()->getUserName());

 		}

 	}

?>
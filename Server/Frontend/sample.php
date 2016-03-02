<?php
include_once('YahooFinance.php');
 
$objYahooStock = new YahooStock;
 
/**
  Add format/parameters to be fetched
  
  s = Symbol
  n = Name
  l1 = Last Trade (Price Only)
  d1 = Last Trade Date
  t1 = Last Trade Time
  c1 = Change
  p2 = Percent Change

 */
$objYahooStock->addFormat("snl1d1t1c1p2"); 
 
/**
  Add company stock code to be fetched
  
  msft = Microsoft
  amzn = Amazon
  yhoo = Yahoo
  goog = Google
  aapl = Apple  
 */
$objYahooStock->addStock("aapl");
$objYahooStock->addStock("amzn");
$objYahooStock->addStock("yhoo");
$objYahooStock->addStock("goog"); 
$objYahooStock->addStock("vgz"); 
$objYahooStock->addStock("xxxx");
 
/**
 * Printing out the data
 
foreach( $objYahooStock->getQuotes() as $code => $stock)
{
  ?>
  Code: <?php echo $stock[0]; ?> <br />
  Name: <?php echo $stock[1]; ?> <br />
  Last Trade Price: <?php echo $stock[2]; ?> <br />
  Last Trade Date: <?php echo $stock[3]; ?> <br />
  Last Trade Time: <?php echo $stock[4]; ?> <br />
  Change and Percent Change: <?php echo $stock[5]; ?> <br />
  Volume: <?php echo $stock[6]; ?> <br /><br />
  <?php
}

*/

foreach( $objYahooStock->getQuotes() as $code => $stock)
{
  ?>
  Symbol: <?php echo $stock[0]; ?> <br />
  Name: <?php echo $stock[1]; ?> <br />
  Last Trade Price: <?php echo $stock[2]; ?> <br />
  Last Trade Date: <?php echo $stock[3]; ?> <br />
  Last Trade Time: <?php echo $stock[4]; ?> <br />
  Change: <?php echo $stock[5]. " USD"; ?> <br />
  Percent Change: <?php echo $stock[6]; ?> <br /><br />
  <?php
}
?>



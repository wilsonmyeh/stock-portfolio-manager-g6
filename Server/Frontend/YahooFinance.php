<?php

/*******CITATION COMMENT **********
per Prof. Halfond's permission here: https://piazza.com/class/ijjbo11o97o4k0?cid=202 in the followup discussions, we are using this 
open source github project: https://github.com/wontonst/yahoo-finance-api-csv

as well as: 

http://blog.chapagain.com.np/php-how-to-get-stock-quote-data-from-yahoo-finance-complete-code-and-tutorial/
*/
/**
 * Class to fetch stock data from Yahoo! Finance
 *
 */
 
class YahooStock{
    
  /**
   * Array of stock code
   */
    private $stocks = array();
  
  /**
   * Parameters string to be fetched   
   */
  private $format;

    /**
     * Populate stock array with stock code
   *
     * @param string $stock Stock code of company    
     * @return void
     */
    public function addStock($stock)
    {
        $this->stocks[] = $stock;
    }
  
  /**
     * Populate parameters/format to be fetched
   *
     * @param string $param Parameters/Format to be fetched
     * @return void
     */
    public function addFormat($format)
    {
        $this->format = $format;
    }
 
    /**
     * Get Stock Data
   *
     * @return array
     */
    public function getQuotes()
    {        
        $result = array();    
    $format = $this->format;
        
        foreach ($this->stocks as $stock)
        {            
      /**
       * fetch data from Yahoo!
       * s = stock code
       * f = format
       * e = filetype
       */
            $s = file_get_contents("http://finance.yahoo.com/d/quotes.csv?s=$stock&f=$format&e=.csv");
            
      /** 
       * convert the comma separated data into array
       */
            $data = str_getcsv($s, ",", '"');
      
      /** 
       * populate result array with stock code as key
       */
            $result[$stock] = $data;
        }
        return $result;
    }
} 

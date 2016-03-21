Feature:Successfull selling stock
	When the user logs in and inputs a valid stock to sell and succeeds should have message
Scenario: Sell Stock
	Given user navigates to http://localhost/Frontend/login.html
	And fills Username as "halfond@usc.edu"
	And fills Password as "HelloWorld1!"
	And logs in
	And fills Ticker as "GOOG"
	And fills Quantity as "5"
	And fills Name as "Google"
	And sells stock

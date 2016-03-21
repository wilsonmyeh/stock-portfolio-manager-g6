Feature: Stock Graph
		A graph displaying stocks should be featured on the main dashboard.

Scenario: Opening Dashboard
		Given user navigates to http://localhost/Frontend/login.html
		And fills Username as "halfond@usc.edu"
		And fills Password as "HelloWorld1!"
		And logs in
		When user waits for 10 seconds
		Then graph should be loaded on the dashboard

Scenario: Changing Time Tabs
		Given user navigates to http://localhost/Frontend/login.html
		And fills Username as "halfond@usc.edu"
		And fills Password as "HelloWorld1!"
		And logs in
		And waits for 10 seconds
		When user clicks the graph tab for 3 months
		Then graph axis should change to span 3 months

Scenario: Switch To Watchlist
		Given user navigates to http://localhost/Frontend/login.html
		And fills Username as "halfond@usc.edu"
		And fills Password as "HelloWorld1!"
		And logs in
		And waits for 10 seconds
		When user clicks the tab for the "Watchlist Tab"
		Then graph should change to "Watchlist Tab" graph

Scenario: Switch To Portfolio
		Given user navigates to http://localhost/Frontend/login.html
		And fills Username as "halfond@usc.edu"
		And fills Password as "HelloWorld1!"
		And logs in
		And waits for 10 seconds
		When user clicks the tab for the "Portfolio Tab"
		Then graph should change to "Portfolio Tab" graph

Scenario: Zoom
		Given user navigates to http://localhost/Frontend/login.html
		And fills Username as "halfond@usc.edu"
		And fills Password as "HelloWorld1!"
		And logs in
		And waits for 10 seconds
		When user scrolls up while hovering over graph
		Then graph should zoom

Scenario: Clicked On Stock In Graph
		Given user navigates to http://localhost/Frontend/login.html
		And fills Username as "halfond@usc.edu"
		And fills Password as "HelloWorld1!"
		And logs in
		And waits for 10 seconds
		When user clicks on a stock in graph
		Then detailed information widget should populate

Scenario: Add Stock To Graph Using Text Field And Button
		Given user navigates to http://localhost/Frontend/login.html
		And fills Username as "halfond@usc.edu"
		And fills Password as "HelloWorld1!"
		And logs in
		And waits for 10 seconds
		And fills Add Stock Ticker as "FB"
		When user clicks Graph Stock
		Then graph should add stock
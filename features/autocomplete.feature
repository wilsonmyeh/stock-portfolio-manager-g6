Feature: Searchbar Autocomplete Feature
	After typing some letters into the searchbar, there should be a dropdown of possible matching stocks.

Scenario: Autocomplete
	Given user navigates to http://localhost/Frontend/login.html
	And fills Username as "ttrojan@usc.edu"
	And fills Password as "HelloWorld1!"
	And logs in
	When user types "aa" into the searchbar
	Then user should see a dropdown of search results
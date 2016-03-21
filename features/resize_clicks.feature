Feature: Resize Click Feature
	User should be able to click on every form and button independent of window size.

Scenario: Resize Click
	Given user navigates to http://localhost/Frontend/login.html
	And resizes the window to 600x1000
	Then user should be able to click Username and Password forms
	Given user fills Username as "ttrojan@usc.edu"
	And fills Password as "HelloWorld1!"
	Then user should be able to click Login
	Given user logs in
	Then user should be able to click on any form or button on the dashboard
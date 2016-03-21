Feature: Automatic Timeout Feature
	After 5 minutes of inactivity, the user should automatically be logged out.

Scenario: Automatic Timeout
	Given user navigates to http://localhost/Frontend/login.html
	And fills Username as "ttrojan@usc.edu"
	And fills Password as "HelloWorld1!"
	And logs in
	When user is inactive for 5 minutes
	Then user should be automatically logged out

Scenario: Automatic Timeout
	Given user navigates to http://localhost/Frontend/login.html
	And fills Username as "jgui@usc.edu"
	And fills Password as "HelloWorld1!"
	And logs in
	When user is inactive for 5 minutes
	Then user should be automatically logged out

Scenario: Automatic Timeout
	Given user navigates to http://localhost/Frontend/login.html
	And fills Username as "halfond@usc.edu"
	And fills Password as "HelloWorld1!"
	And logs in
	When user is inactive for 5 minutes
	Then user should be automatically logged out
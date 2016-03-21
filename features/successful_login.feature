Feature:Successful login feature
	Inputting a valid username and password should open the main dashboard.

Scenario:Succesful Login
	Given user navigates to http://localhost/Frontend/login.html
	And fills Username as "halfond@usc.edu"
	And fills Password as "HelloWorld1!"
	And logs in


Feature:Successful logout feature
	Inputting a valid username and password should open the main dashboard which has a logout button.

Scenario:Succesful Login
	Given user navigates to http://localhost/Frontend/login.html
	And fills Username as "halfond@usc.edu"
	And fills Password as "HelloWorld1!"
	And logs in
	And logs out


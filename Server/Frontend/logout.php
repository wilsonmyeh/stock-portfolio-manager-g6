<?php
	# Display Errors
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require '../../vendor/autoload.php';
	use Parse\ParseUser;
	use Parse\ParseClient;
	use Parse\ParseException;

	ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');

	# try to log user out, ending the parse and php sessions
	try
	{
	 	ParseUser::logOut();
	 	session_unset();     // unset $_SESSION variable for the run-time 
    	session_destroy();   // destroy session data in storage
		$message = "Successful log out!";
		echo "<script type='text/javascript'>alert('$message');</script>";
		readfile("http://localhost/Frontend/login.html");
	} 
	catch (ParseException $ex) 
	{
		$message = $ex->getMessage();
		echo "<script type='text/javascript'>alert('$message');</script>";
		readfile("http://localhost/Frontend/dashboard.html");
	}
 	
 ?> 
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../vendor/autoload.php';
use Parse\ParseUser;
use Parse\ParseClient;
use Parse\ParseException;

ParseClient::initialize('YtTIOIVkgKimi9f3KgvmhAm9be09KaFPD0lK1r21', 'Bxf6gl3FUT0goWvvx3DIger9bcOjwY1LflXr6MIO', 'r86cSKPWagMCavzJXVF4OFnte5yPpNY74GhY9UxS');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$usernameTxt = formatUserInput($_POST["email"]);
try {
	  ParseUser::requestPasswordReset($usernameTxt);
	  $message = "Password reset successful. Please check your email.";
		echo "<script type='text/javascript'>alert('$message');</script>";
		readfile("http://localhost/Frontend/login.html");
	}
 catch (ParseException $ex) {
		$message = $ex->getMessage();
		echo "<script type='text/javascript'>alert('$message');</script>";
		readfile("http://localhost/Frontend/recovery.html");
	}
	
	
	}
	function formatUserInput($input){
		$input = trim($input);
		$input = stripslashes($input);
		return $input;
	}
?>
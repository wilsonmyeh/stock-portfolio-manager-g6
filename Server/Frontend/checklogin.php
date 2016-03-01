
<html>
<body>

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
		
	 	
	 	$usernameText = formatUserInput($_POST["user"]);
	 	$passwordText = formatUserInput($_POST["password"]);
	 	
		try {

			 	$user = ParseUser::logIn($usernameText, $passwordText);
				$message = "Successful log in!";
				echo "<script type='text/javascript'>alert('$message');</script>";
				readfile("http://localhost/Frontend/dashboard.html");
			} 
			catch (ParseException $ex) 
			{
				$message = $ex->getMessage();
				echo "<script type='text/javascript'>alert('$message');</script>";
				readfile("http://localhost/Frontend/login.html");
			}
	 }

	 	//Gets rid of special characters and slashes from input
	 
	 	function formatUserInput($input){
	 		$input = trim($input);
	 		$input = stripslashes($input);
	 		return $input;
	 	}
	 	
	 ?> 
</body>
</html>
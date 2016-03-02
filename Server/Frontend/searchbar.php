<?php
	if($_POST["a"]) {
		echo $_POST["searchbar"];
	}
	else{
		global $stockToWatch;
		$stockToWatch = $_POST["searchbar"];
		include('addWatchedStock.php');
	}
?>
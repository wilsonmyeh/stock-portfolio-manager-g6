<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$today = date_format(new DateTime(), 'md1259Y');
	shell_exec("timedatectl set-ntp 0"); // Switch clock to manual setting
	$msg = shell_exec("sudo date " . $today); // It says "date MMDDhhmiYYYY
?>
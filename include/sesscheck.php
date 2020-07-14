	
<?php
	session_start();
	date_default_timezone_set('UTC');
	if(!isset($_SESSION['userid'])){				
		header("location: ../php/logout.php");
	}
?>
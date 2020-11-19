<?php 
//
	// sql data
	$sql_host = 'localhost';
	$sql_user = 'yt-reminder';
	$sql_pass = 'yt-reminder';
	$sql_db = 'yt-reminder';
			
	// connection
	@$dbmysqli = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);
	@$dbmysqli->set_charset("utf8");
	
	if (mysqli_connect_errno()) {
	printf("SQL connection error: %s\n", mysqli_connect_error());
	exit();
	}
	
	// mail settings
	$from = 'yt-reminder@domain.com';
	$from_name = 'Youtube Upload Reminder';
	
	// date format
	$format_1 = 'd.m, H:i';
	$format_2 = 'm/d, H:i';  
	
	# For format month/day change to $date_format = $format_1;
	# For format day.month change to $date_format = $format_2;
	
	$date_format = $format_1;

?>
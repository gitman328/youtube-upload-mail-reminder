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
	$to = 'yourmail@web.com'; 
	$from = 'yt-reminder@web.com';
	$from_name = 'Youtube Upload Reminder';

?>
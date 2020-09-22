<?php 
//
	include("config.php");
	
	$loop = 0;
	$delete_time = time() - 604800;
	
	$sql_0 = "SELECT * FROM `#channel_list` ";
	if ($result_0 = mysqli_query($dbmysqli,$sql_0))
	{
	while ($obj_0 = mysqli_fetch_object($result_0)){
	{
	
	$sql_1 = "SELECT * FROM `".$obj_0->channel_id."` WHERE `notified` LIKE '0' ";
	if ($result_1 = mysqli_query($dbmysqli,$sql_1))
	{
	while ($obj_1 = mysqli_fetch_object($result_1)){
	{
	
	if(!($obj_1->timestamp < $delete_time))
	{

	$obj_1->video_title = str_replace("'", "`", $obj_1->video_title);
	$obj_1->video_title = str_replace("\"", "`", $obj_1->video_title);
	//$obj_1->channel_name = mb_convert_encoding($obj_1->channel_name, "HTML-ENTITIES", "UTF-8");
	
	$channel_name = $obj_0->channel_name;
	
	$loop = $loop + 1;
	
	$game_list = $game_list.'
	<div align="center">
		<table border="0">
		  <tr>
			<td class="channel-name" align="center">
			<h2><a href="https://www.youtube.com/channel/'.$obj_0->channel_id.'" target="_blank">
			<strong>'.$channel_name.' ('.date("d.m, H:i", $obj_1->timestamp).')</strong></a></h2>
			<h3 class="video-title"><strong>'.$obj_1->video_title.'</strong></h3></td>
		  </tr>
		  <tr>
			<td>
			<div align="center"> 
			<a href="https://youtube.com/watch?v='.$obj_1->video_id.'" target="_blank"> 
			<img class="thumbnail-img" src="'.$obj_1->video_thumbnail.'" alt="'.$obj_1->video_title.'" title="'.$obj_1->video_title.'"></a> 
			</div>
			</td>
		  </tr>
		  <tr>
			<td class="spacer">&nbsp;</td>
		  </tr>
		</table>
		<hr style="border: 1px dotted #FFF; width:75%;">
	  </div>';
	  
	}
	
	mysqli_query($dbmysqli, "UPDATE `".$obj_0->channel_id."` SET `notified` = '1', `notified_ts` = '".time()."' WHERE `id` = '".$obj_1->id."' ");
	
	}
	}
	}
	
	mysqli_query($dbmysqli, "DELETE FROM `".$obj_0->channel_id."` WHERE `notified` = '1' AND `timestamp` < ".$delete_time." ");
	mysqli_query($dbmysqli, "OPTIMIZE TABLE `".$obj_0->channel_id."` ");

	}
	}
	} // channel list
	
	if($loop == 1){ $subject = $channel_name.' has uploaded a video'; } else { $subject = 'A favorite channel has uploaded a video';  }
	
	$htmlContent = ' 
	<html> 
	<head> 
	<title>Youtube Upload Reminder</title>
	<style>
	body {
		background-color: #000;
	}
	.wrapper {
		color:#000; 
		background-color: #000; 
		border: 2px dotted #FFF;
		padding: 20px;
	}
	.header {
		font-size: 35px;
		color: #FFF;
		text-align: center;
		font-weight: bold;
		padding-bottom: 10px;
	}
	.channel-name {
		color: #FF4646; 
		padding-top: 10px; 
		padding-bottom: 10px;
	}
	.channel-name a:link {
		color: #FF4646;
		text-decoration: none;
	}
	.channel-name a:hover {
		color: #FF4646;
		text-decoration: underline;
	}
	.video-title {
		color: #FFF; 
		padding-top: 10px; 
		padding-bottom: 10px;
	}
	.content-tab {
		color: #E0E0E0; 
		padding-top: 10px; 
		padding-bottom: 30px;
	}
	.thumbnail-img {
		width: 360px;
		height: 270px;
		border: 5px solid #FFF;
	}
	.spacer {
		padding-top: 10px; 
		padding-bottom: 10px;
	}
	.spacer a:link {
		color: #FFF;
	}
	.spacer_30 {
		clear: both;
		width: 100%;
		height: 30px;
	}
	.footer {
		color: #FFF; 
		background-color: #000;
	}
	</style>
	</head>
	<body>
	<div class="wrapper">
	<div class="header"></div>
	'.$game_list.'
	<div class="spacer_30"></div>
	<center class="spacer"><a href="https://www.github.com/gitman328/youtube-upload-mail-reminder" target="_blank">Youtube Mail Reminder</a></center>
	<div class="spacer"></div>
	</div>
	<div class="footer" align="center"> </div>
	</body> 
	</html>'; 
 
	// content-type html header
	$headers = "MIME-Version: 1.0"."\r\n"; 
	$headers .= "Content-type:text/html;charset=UTF-8"."\r\n"; 
	$headers .= 'From: '.$from_name.'<'.$from.'>'."\r\n"; 
	
	if($game_list != '')
	{
	if(mail($to, $subject, $htmlContent, $headers)){ echo 'Email has sent successfully.'; } else { echo 'Email sending failed.'; }
	}

?>

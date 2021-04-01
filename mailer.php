<?php 
//
	include("config.php");
	
	$delete_time = time() - 1209600;
	
	$loop = 0;

	$sql = "SELECT * FROM `#accounts` WHERE `active` LIKE 'yes' ";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){
	{
	
	$id = $obj->id;
	$mailadress = $obj->mailadress;
	
	$sql_0 = "SELECT * FROM `#channel_list` WHERE `account` LIKE '".$mailadress."' AND `active` LIKE 'yes' ";
	if ($result_0 = mysqli_query($dbmysqli,$sql_0))
	{
	while ($obj_0 = mysqli_fetch_object($result_0)){
	{
	
	$sql_1 = "SELECT * FROM `".$obj_0->channel_id."#".$id."` WHERE `notified` LIKE '0' ";
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
	$thumbnail_img = $obj_1->video_thumbnail;
	
	$loop = $loop + 1;
	
	// video length
	if(function_exists('curl_version'))
	{
	$curl = curl_init();
	curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_FOLLOWLOCATION => 1,
	CURLOPT_URL => 'https://www.youtube.com/watch?v='.$obj_1->video_id,
    CURLOPT_USERAGENT => 'Mozilla 5./0 (compatible) Opera or Gecko',
	));
	$response = curl_exec($curl);
	
	preg_match_all("#u0026len=(.*?)u#si", $response, $video_length);
	$video_length[0][0] = str_replace("u0026len=", "", $video_length[0][0]);
	$video_length[0][0] = str_replace("\\\u", "", $video_length[0][0]);
	
	$video_length = round($video_length[0][0]/60, 0);
	$video_length_info = ' | ~ '.$video_length.' min.';
	if($video_length == '0'){ $video_length_info = ' | Livestream'; }
	} 
	else { // echo 'curl not available'; 
	$video_length_info = '';
	}
	
	if(!isset($content) or $content == ""){ $content = ""; }
	
	$content = $content.'
	<div class="frame">
	<div align="center">
		<table border="0">
		  <tr>
			<td class="channel-name" align="center">
			<h2><a href="https://www.youtube.com/channel/'.$obj_0->channel_id.'" target="_blank">
			<strong>'.$channel_name.' ('.date($date_format, $obj_1->timestamp).')</strong></a></h2>
			<h3 class="video-title"><strong>'.$obj_1->video_title.'</strong>'.$video_length_info.'</h3></td>
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
	  </div>
	  </div>';
	  
	}
	
	mysqli_query($dbmysqli, "UPDATE `".$obj_0->channel_id."#".$id."` SET `notified` = '1', `notified_ts` = '".time()."' WHERE `id` = '".$obj_1->id."' ");
	
	}
	}
	}
	
	mysqli_query($dbmysqli, "DELETE FROM `".$obj_0->channel_id."#".$id."` WHERE `timestamp` < ".$delete_time." ");
	mysqli_query($dbmysqli, "OPTIMIZE TABLE `".$obj_0->channel_id."#".$id."`");
	mysqli_query($dbmysqli, "OPTIMIZE TABLE `#accounts`");
	mysqli_query($dbmysqli, "OPTIMIZE TABLE `#channel_list`");

	}
	}
	} // channel list
	
	if($loop == 1){ $subject = $channel_name.' has uploaded a video'; } else { $subject = 'A favorite channel has uploaded a video';  }
	
	if(!isset($content) or $content == ""){ $content = ""; }
	
	$htmlContent = '
	<!DOCTYPE html> 
	<html> 
	<head> 
	<title>Youtube Upload Reminder</title>
	<style>
	body {
		background-color: #000;
		font-family: Verdana, Arial, Helvetica, sans-serif;
	}
	.wrapper {
		color:#000; 
		background-color: #000;
		padding: 10px;
		box-shadow: inset 0px 0px 5px 0px #fff;
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
	.channel-name a:visited {
		color: #FF4646;
	}
	.video-title {
		color: #FFF; 
		padding-top: 10px; 
		padding-bottom: 10px;
	}
	.frame {
		border: 1px solid #ccc;
		padding-top: 10px;
		margin-bottom: 40px;
	}
	.content-tab {
		color: #E0E0E0; 
		padding-top: 10px; 
		padding-bottom: 30px;
	}
	.thumbnail-img {
		width: 360px;
		height: 270px;
		border: 3px solid #fff;
	}
	.spacer {
		padding-top: 10px; 
		padding-bottom: 10px;
	}
	.spacer a:link {
		font-size: 10px;
		color: #333333;
		text-decoration: none;
	}
	</style>
	</head>
	<body>
	<div class="wrapper">
	<div class="header"></div>
	'.$content.'
	<center class="spacer"><a href="https://www.github.com/gitman328/youtube-upload-mail-reminder" target="_blank">Youtube Upload Mail Reminder</a></center>
	</div>
	</body> 
	</html>'; 
 
	// content-type html header
	$headers = "MIME-Version: 1.0"."\r\n"; 
	$headers .= "Content-type:text/html;charset=UTF-8"."\r\n"; 
	$headers .= 'From: '.$from_name.'<'.$from.'>'."\r\n"; 
	
	if($content != '')
	{
	if(mail($mailadress, $subject, $htmlContent, $headers)){ echo 'Email to '.$mailadress.' sent.<br>'; } else { echo 'Email sending to '.$mailadress.' failed.<br>'; }
	}
	
	$loop = 0;
	$content = '';
	
	}
	}
	} // accounts

?>

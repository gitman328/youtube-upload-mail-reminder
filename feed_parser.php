<?php 
//
	include("config.php");
	
	$sql_0 = "SELECT * FROM `#accounts` ";
	
	if ($result_0 = mysqli_query($dbmysqli,$sql_0))
	{
	while ($obj_0 = mysqli_fetch_object($result_0)){
	{
	
	$sql_1 = "SELECT * FROM `#channel_list` WHERE `account` LIKE '".$obj_0->mailadress."' AND `active` LIKE 'yes' ";
	
	if ($result_1 = mysqli_query($dbmysqli,$sql_1))
	{
	while ($obj_1 = mysqli_fetch_object($result_1)){
	{
	
	// create table if not exist
	mysqli_query($dbmysqli, "
	CREATE TABLE IF NOT EXISTS `".$obj_1->channel_id."#".$obj_0->id."` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`channel_name` varchar(255) NOT NULL,
	`video_title` varchar(255) NOT NULL,
	`video_id` varchar(255) NOT NULL,
	`video_thumbnail` varchar(255) NOT NULL,
	`timestamp` int(12) NOT NULL,
	`date` varchar(255) NOT NULL,
	`notified` int(1) NOT NULL, 
	`notified_ts` int(12) NOT NULL, 
	`account` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	");
	
	$xmlfile = 'https://www.youtube.com/feeds/videos.xml?channel_id='.$obj_1->channel_id;
	
	$feed = simplexml_load_file(rawurlencode($xmlfile));
		
if($feed)
	{
	for ($i = 0; $i <= 9; $i++){
	
	$channel_name = $feed->author->name;
	$channel_name = str_replace("'", "`", $channel_name);
	$channel_name = str_replace("\"", "`", $channel_name);

	$video_title = $feed->entry[$i]->title;
	$video_title = str_replace("'", "`", $video_title);
	$video_title = str_replace("\"", "`", $video_title);
	
	$video_id = $feed->entry[$i]->id;
	$video_id = str_replace("yt:video:", "", $video_id);

	$video_thumbnail = 'https://i2.ytimg.com/vi/'.$video_id.'/hqdefault.jpg';

	$timestamp = strtotime($feed->entry[$i]->published);
	
	// debug
//	echo $channel_name.'<br>';
//	echo $video_title.'<br>';
//	echo '<a href="https://www.youtube.com/watch?v='.$video_id.'" target="_blank">Watch</a>'.'<br>';
//	echo $video_thumbnail.'<br>';
//	echo date("d.m.Y, H:i", $timestamp).'<br>';
//	echo '<hr>';
	
	// check if video already exist
	$sql_2 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `".$obj_1->channel_id."#".$obj_0->id."` WHERE `video_id` LIKE '".$video_id."' ");
	$result_2 = mysqli_fetch_row($sql_2);
	$summary = $result_2[0];
	
	if($summary == 0 and $video_id != '')
	{
	mysqli_query($dbmysqli, "INSERT INTO `".$obj_1->channel_id."#".$obj_0->id."` 
	(
	channel_name, 
	video_title, 
	video_id, 
	video_thumbnail, 
	timestamp, 
	date, 
	account   
	) VALUES (
	'".$channel_name."', 
	'".$video_title."', 
	'".$video_id."', 
	'".$video_thumbnail."', 
	'".$timestamp."', 
	'".date("d.m.Y, H:i:s", $timestamp)."', 
	'".$obj_0->mailadress."'
	) 
	");
	} // 
		
	} // for i
	
	} // feed
	
	sleep(5);

	}
	}
	}
	
	}
	}
	}
	
	include("mailer.php");

?>

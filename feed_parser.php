<?php 
//
	include("config.php");
	
	$sql = "SELECT * FROM `#channel_list` ";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){
	{
	
	// create table if not exist
	mysqli_query($dbmysqli, "
	CREATE TABLE IF NOT EXISTS `".$obj->channel_id."` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`channel_name` varchar(255) NOT NULL,
	`video_title` varchar(255) NOT NULL,
	`video_id` varchar(255) NOT NULL,
	`video_thumbnail` varchar(255) NOT NULL,
	`timestamp` int(12) NOT NULL,
	`date` varchar(255) NOT NULL,
	`notified` int(1) NOT NULL, 
	`notified_ts` int(12) NOT NULL, 
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	");
	
	$xmlfile = 'https://www.youtube.com/feeds/videos.xml?channel_id='.$obj->channel_id;
	
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
	
	// check if video alreday exist
	$sql_1 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `".$obj->channel_id."` WHERE `video_id` LIKE '".$video_id."' ");
	$result_1 = mysqli_fetch_row($sql_1);
	$summary = $result_1[0];
	
	if($summary == 0 and $video_id != '')
	{
	mysqli_query($dbmysqli, "INSERT INTO `".$obj->channel_id."` 
	(
	channel_name, 
	video_title, 
	video_id, 
	video_thumbnail, 
	timestamp, 
	date  
	) VALUES (
	'".$channel_name."', 
	'".$video_title."', 
	'".$video_id."', 
	'".$video_thumbnail."', 
	'".$timestamp."', 
	'".date("d.m.Y, H:i:s", $timestamp)."' 
	) 
	");
	} // 
		
	} // for i
	
	} // feed
	
	sleep(5);

	}
	}
	}
	
	include("mailer.php");

?>

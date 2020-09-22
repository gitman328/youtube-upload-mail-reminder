<?php 
//

	include("config.php");
	
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ""){ $_REQUEST['action'] = ""; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == ""){ $_REQUEST['channel_id'] = ""; }
	if(!isset($_REQUEST['channel_name']) or $_REQUEST['channel_name'] == ""){ $_REQUEST['channel_name'] = ""; }
	
	$action = $_REQUEST['action'];
	$channel_id = $_REQUEST['channel_id'];
	$channel_name = $_REQUEST['channel_name'];
	
if($action == 'save_channel_id')
	{
	
	sleep(1);
	
	$sql_0 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `#channel_list` WHERE `channel_id` LIKE '".$channel_id."' ");
	$result_0 = mysqli_fetch_row($sql_0);
	$summary = $result_0[0];
	
if($summary == 0)
	{
	mysqli_query($dbmysqli, "INSERT INTO `#channel_list` (channel_id, channel_name) VALUES ('".$channel_id."', '".$channel_name."') ");
	
	echo 'Channel saved in list.'; exit;
	
	} else { echo 'Channel already in list'; exit; }
	
	} // save channel
	
	
if($action == 'search_channel')
	{
	sleep(1);
	
	$sql_1 = "SELECT * FROM `#channel_list` WHERE `channel_name` LIKE '%".$channel_name."%' OR `channel_id` LIKE '%".$channel_name."%' ";
	
	if ($result_1 = mysqli_query($dbmysqli,$sql_1))
	{
	while ($obj = mysqli_fetch_object($result_1)){
	{
	
	if(!isset($result_list) or $result_list == ""){ $result_list = ""; }
	
	$result_list = $result_list.'
	Channel Name: '.$obj->channel_name.'<br>
	Channel ID: '.$obj->channel_id.'<br>
	';
	}
	}
	}
	
	if(!isset($result_list) or $result_list == ""){ $result_list = "Channel not found"; }
	
	echo $result_list;
	
	exit;
	
	} // search channel
	
	
if($action == 'delete_channel')
	{
	sleep(1);
	
	$sql_2 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `#channel_list` WHERE `channel_id` LIKE '".$channel_id."' ");
	$result_2 = mysqli_fetch_row($sql_2);
	$summary = $result_2[0];
	
if($summary != 0)
	{
	mysqli_query($dbmysqli, "DELETE FROM `#channel_list` WHERE `channel_id` LIKE '".$channel_id."' ");
	
	mysqli_query($dbmysqli, "DROP TABLE `".$channel_id."` ");
	
	echo 'Channel deleted.';
	
	} else { echo 'Channel not found'; }
	
	exit;
	
	} // delete channel
	
	
	// show channels
if($action == 'channel_list')
	{
	$sql_3 = "SELECT * FROM `#channel_list` ORDER BY `channel_name` ASC";
	
	if ($result_3 = mysqli_query($dbmysqli,$sql_3))
	{
	while ($obj = mysqli_fetch_object($result_3)){
	{
	
	if(!isset($channel_list) or $channel_list == ""){ $channel_list = ""; }
	
	$channel_list = $channel_list.'
	'.$obj->channel_name.' | '.$obj->channel_id.'<br> 
	';
	}
	}
	}
	
	if(!isset($channel_list) or $channel_list == ""){ $channel_list = ""; } 
	
	echo $channel_list;
	
	exit;
}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Youtube Mail Reminder</title>
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<style>
.spacer_10 {
	clear: both;
	width: 100%;
	height: 10px;
}
</style>
</head>

<body>
Save channel<br>
<input type="text" id="channel_name" size="40" placeholder="Channel Name">
<input type="text" id="channel_id" size="40" placeholder="Channel ID">
<input type="submit" name="button" id="button" value="Save" onclick="save_channel()">
<div class="spacer_10"></div>
<div id="status_register">&nbsp;</div>
<div class="spacer_10"></div>
<hr>

Search channel<br>
<input type="text" id="search_channel" size="40" placeholder="Channel Name">
<input type="submit" name="button" id="button" value="Search" onclick="search_channel()">
<div class="spacer_10"></div>
<div id="status_search">&nbsp;</div>
<div class="spacer_10"></div>
<hr>

Delete channel<br>
<input type="text" id="delete_channel" size="40" placeholder="Channel ID">
<input type="submit" name="button" id="button" value="Delete" onclick="delete_channel()">
<div class="spacer_10"></div>
<div id="status_delete">&nbsp;</div>
<div class="spacer_10"></div>
<hr>
<div id="channel_list"></div>

<script>
$(document).ready(function(){

$.post("index.php",
	{
	action: 'channel_list'
	},
	function(data){
	$("#channel_list").html(data);
	});
});

function channel_list(){

$.post("index.php",
	{
	action: 'channel_list'
	},
	function(data){
	$("#channel_list").html(data);
	});
}

function save_channel(){
	
	var channel_name = $("#channel_name").val();
	var channel_id = $("#channel_id").val();
	
	if(channel_id == ''){ return; }
	if(channel_name == ''){ return; }
	
	$("#status_register").html("<img src=\"loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("index.php",
	{
	action: 'save_channel_id',
	channel_id: channel_id,
	channel_name: channel_name
	},
	function(data){
	$("#status_register").html(data);
	channel_list();
	});
}

function search_channel(){
	
	var channel_name = $("#search_channel").val();
	if(channel_name == ''){ return; }
	
	$("#status_search").html("<img src=\"loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("index.php",
	{
	action: 'search_channel',
	channel_name: channel_name
	},
	function(data){
	$("#status_search").html(data);
	});
	
}

function delete_channel(){
	
	var channel_id = $("#delete_channel").val();
	if(channel_id == ''){ return; }
	
	$("#status_delete").html("<img src=\"loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("index.php",
	{
	action: 'delete_channel',
	channel_id: channel_id
	},
	function(data){
	$("#status_delete").html(data);
	channel_list();
	});
}
</script>
</body>
</html>

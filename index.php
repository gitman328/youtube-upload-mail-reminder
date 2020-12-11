<?php 

	include("config.php");
	
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ''){ $_REQUEST['action'] = ''; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == ''){ $_REQUEST['channel_id'] = ''; }
	if(!isset($_REQUEST['channel_name']) or $_REQUEST['channel_name'] == ''){ $_REQUEST['channel_name'] = ''; }
	if(!isset($_REQUEST['mailadress']) or $_REQUEST['mailadress'] == ''){ $_REQUEST['mailadress'] = ''; }
	if(!isset($_REQUEST['account']) or $_REQUEST['account'] == ''){ $_REQUEST['account'] = ''; }
	if(!isset($_REQUEST['active']) or $_REQUEST['active'] == ''){ $_REQUEST['active'] = ''; }
	if(!isset($_REQUEST['content']) or $_REQUEST['content'] == ''){ $_REQUEST['content'] = ''; }
	if(!isset($_REQUEST['import_option']) or $_REQUEST['import_option'] == ''){ $_REQUEST['import_option'] = ''; }
	if(!isset($_REQUEST['channel_url']) or $_REQUEST['channel_url'] == ''){ $_REQUEST['channel_url'] = ''; }
	
	$action = $_REQUEST['action'];
	$channel_id = $_REQUEST['channel_id'];
	$channel_name = $_REQUEST['channel_name'];
	$mailadress = $_REQUEST['mailadress'];
	$account = $_REQUEST['account'];
	$active = $_REQUEST['active'];
	$content = $_REQUEST['content'];
	$import_option = $_REQUEST['import_option'];
	$channel_url = $_REQUEST['channel_url'];

if($action == 'show_accounts')
	{
	$sql = "SELECT * FROM `#accounts` ";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){
	{
	
	if(!isset($account_list) or $account_list == ''){ $account_list = ''; }
	
	$account_list = $account_list.'
	<a style="cursor:pointer;" class="list-group-item" onclick="show_subscription_list(\''.$obj->mailadress.'\')">'.$obj->mailadress.'</a>
	';
	}
	}
	}
	
	if(!isset($account_list) or $account_list == ''){ $account_list = ''; }
	
	echo $account_list.'<a style="cursor:pointer;" class="list-group-item" onclick="add_account()">Add Account</a>';
	
	exit;
}
	
	
if($action == 'show_subscription_list')
	{
	$group = '';
	$group_title = '';
	
	$sql = mysqli_query($dbmysqli, "SELECT active FROM `#accounts` WHERE `mailadress` LIKE '".$account."' ");
	$result = mysqli_fetch_assoc($sql);
	$active = $result['active'];
	
	if($active == 'yes'){ $account_status = 'checked'; } else { $account_status = ''; }
	
	$sql_0 = "SELECT * FROM `#channel_list` WHERE `account` LIKE '".$account."' ORDER BY `channel_name` ASC";
	
	if ($result_0 = mysqli_query($dbmysqli,$sql_0))
	{
	while ($obj = mysqli_fetch_object($result_0)){
	{
	if($obj->active == 'yes'){ $checked = 'checked'; } else { $checked = ''; }
	
	if($group == strtoupper($obj->channel_name[0][0])){ $group_title = ''; } else { $group_title = strtoupper($obj->channel_name[0][0]); }
	
	$group = strtoupper($obj->channel_name[0][0]);
	
	if(!isset($subscription_content) or $subscription_content == ''){ $subscription_content = ''; }
	
	if(function_exists('curl_version'))
	{
	$search_channel_link = '<a style="cursor:pointer;" onclick="search_channel_id(\'modal\');">Search Channel ID</a> |';
	
	} else { 
	
	$search_channel_link = ''; 
	}
	
	$subscription_content = $subscription_content.'
	<tr id="item_'.$obj->channel_id.'">
	<td><strong>'.$group_title.'</strong></td>
	<td><input id="channel_name_'.$obj->channel_id.'" type="text" style="width:100%" value="'.$obj->channel_name.'"></td>
	<td>'.$obj->channel_id.'</td>
	<td><input id="checkbox_'.$obj->channel_id.'" type="checkbox" onclick="subscription_list(\'set\',\''.$obj->channel_id.'\',\''.$account.'\')" '.$checked.'>
	</td>
	<td>
	<button class="btn btn-success btn-xs" title="Save" onclick="subscription_list(\'update_channel\',\''.$obj->channel_id.'\',\''.$account.'\')" type="button">
	<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
	</button>
	<button class="btn btn-danger btn-xs" title="Delete" onclick="subscription_list(\'delete_channel\',\''.$obj->channel_id.'\',\''.$account.'\')" type="button">
	<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
	</button>
	<button class="btn btn-default btn-xs" title="Show last videos" onclick="subscription_list(\'show_last_videos\',\''.$obj->channel_id.'\',\''.$account.'\')" type="button">
	<span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>
	</button>
	</td>
	</tr>';
	
	}
	}
	}
	
	$subscription_list_header = '
	<div class="spacer_5"></div>
	<div class="row">
	<div class="col-sm-9 col-lg-9 col-md-9">
	Add a channel | <a style="cursor:pointer;" onclick="bulk_import(\'modal\',\''.$account.'\')">Bulk import</a> | '.$search_channel_link.'
	<a href="https://github.com/gitman328/youtube-upload-mail-reminder#how-to-get-the-channel-id" target="_blank" title="How to get the Channel ID?">?</a>
	<div class="spacer_5"></div>
	<div class="row">
	<div class="col-sm-7 col-lg-7 col-md-7"><input type="text" id="channel_name" placeholder="Channel Name" class="form-control"></div>
	<div class="spacer_5"></div>
	<div class="col-sm-7 col-lg-7 col-md-7"><input type="text" id="channel_id" placeholder="Channel ID" class="form-control"></div>
	<div class="spacer_5"></div>
	<div class="col-sm-7 col-lg-7 col-md-7"><input type="submit" class="btn btn-default" id="button" value="Save" onclick="save_channel(\''.$account.'\')">
	<span style="padding-left:3px;" id="status_register">&nbsp;</span>
	</div>
	</div>
	</div>
	<!-- col-->
	<div class="col-sm-3 col-lg-3 col-md-3">
	<label style="font-weight:normal"><input id="account" onclick="activate_account(\''.$account.'\')" type="checkbox" '.$account_status.'> Activate Account</label>
	<div class="spacer_5"></div>
	<strong class="label label-primary">'.$account.'</strong>
	<div class="spacer_5"></div>
	<strong style="cursor:pointer;" class="label label-danger" onclick="delete_account(\''.$account.'\')">Delete Account</strong>
	</div>
	<!-- col-->
	</div>
	<!-- row-->
	<div class="row">
	<div class="col-sm-12 col-lg-12 col-md-12">
	<hr>
	  <div class="ibox">
		<div class="ibox-title">
		  <h5>Subscribed channels</h5>
		  <div class="spacer_5"></div>
		<div class="ibox-content">
		  <table class="table table-striped table-bordered table-responsive">
			<thead>
			  <tr>
				<th>#</th>
				<th>Channel Name</th>
				<th>Channel ID</th>
				<th>active</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
			';
			
	$subscription_list_footer = '
			</tbody>
		  </table>  
		</div>
	  </div>
	</div>';
	
	if(!isset($subscription_content) or $subscription_content == ''){ $subscription_content = ''; }
	
	echo '<div id="channel_list">'.$subscription_list_header.$subscription_content.$subscription_list_footer.'</div>';
	
	exit;
}
	
	
if($action == 'add_account')
	{
	echo '
	<div class="row">
	<div class="col-sm-6 col-lg-6 col-md-6">
	<div class="spacer_5"></div>
	Enter the mail address to which notifications should be sent
	<div class="spacer_5"></div>
	<input type="text" class="form-control" id="mailadress">
	<div class="spacer_5"></div>
	<input type="submit" class="btn btn-default" name="button" id="button" value="Save" onclick="save_account()">
	<span style="padding-left:3px;" id="save_account_status">&nbsp;</span>
	<div class="spacer_5"></div>
	</div>
	</div>
	';
	
	exit;
}

	
if($action == 'save_account')
	{
	sleep(1);
	
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `#accounts` WHERE `mailadress` LIKE '".$mailadress."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary != 0){ echo 'Account already exist!'; exit; }
	
	mysqli_query($dbmysqli, "INSERT INTO `#accounts` (mailadress, active) VALUES ('".$mailadress."', 'yes')");
	
	echo 'success';
	
	exit;
}


if($action == 'delete_account')
	{
	$sql = mysqli_query($dbmysqli, "SELECT id FROM `#accounts` WHERE `mailadress` LIKE '".$account."' ");
	$result = mysqli_fetch_assoc($sql);
	$id = $result['id'];
	
	$sql_0 = "SELECT * FROM `#channel_list` WHERE `account` LIKE '".$account."' ";
	
	if ($result_0 = mysqli_query($dbmysqli,$sql_0))
	{
	while ($obj_0 = mysqli_fetch_object($result_0)){
	{
	mysqli_query($dbmysqli, "DROP TABLE `".$obj_0->channel_id."#".$id."` ");
	}
	}
	}

	mysqli_query($dbmysqli, "DELETE FROM `#accounts` WHERE `mailadress` LIKE '".$account."' ");
	mysqli_query($dbmysqli, "DELETE FROM `#channel_list` WHERE `account` LIKE '".$account."' ");
	
	echo 'account_deleted';
	
	exit;
}


if($action == 'activate_account')
	{
	mysqli_query($dbmysqli, "UPDATE `#accounts` SET `active` = '".$active."' WHERE `mailadress` LIKE '".$account."' ");
	exit;
}

	
if($action == 'save_channel_id')
	{
	sleep(1);
	
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `#channel_list` WHERE `channel_id` LIKE '".$channel_id."' AND `account` LIKE '".$account."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary == 0)
	{
	mysqli_query($dbmysqli, "INSERT INTO `#channel_list` (channel_id, channel_name, account, active) VALUES ('".$channel_id."', '".$channel_name."', '".$account."', 'yes') ");
	
	echo 'success';
	
	exit;
	
	} else { echo 'Channel is already in list!'; exit; }
}

	
if($action == 'delete_channel')
	{
	$sql = mysqli_query($dbmysqli, "SELECT id FROM `#accounts` WHERE `mailadress` LIKE '".$account."' ");
	$result = mysqli_fetch_assoc($sql);
	$id = $result['id'];
	
	mysqli_query($dbmysqli, "DELETE FROM `#channel_list` WHERE `channel_id` LIKE '".$channel_id."' AND `account` LIKE '".$account."' ");
	mysqli_query($dbmysqli, "DROP TABLE `".$channel_id."#".$id."` ");
	
	echo 'channel_deleted';

	exit;
}

	
if($action == 'set')
	{
	mysqli_query($dbmysqli, "UPDATE `#channel_list` SET `active` = '".$active."' WHERE `channel_id` LIKE '".$channel_id."' AND `account` LIKE '".$account."' ");
	exit;
}


if($action == 'update_channel')
	{
	sleep(1);
	mysqli_query($dbmysqli, "UPDATE `#channel_list` SET `channel_name` = '".$channel_name."' WHERE `channel_id` LIKE '".$channel_id."' AND `account` LIKE '".$account."' ");
	exit;
}


if($action == 'channel_list')
	{
	$sql = "SELECT * FROM `#channel_list` ORDER BY `channel_name` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){
	{
	
	if(!isset($channel_list) or $channel_list == ''){ $channel_list = ''; }
	
	$channel_list = $channel_list.'
	'.$obj->channel_name.' | '.$obj->channel_id.'<br> 
	';
	}
	}
	}
	
	if(!isset($channel_list) or $channel_list == ''){ $channel_list = ''; } 
	
	echo $channel_list;
	
	exit;
}


if($action == 'bulk_import')
	{
	sleep(1);
	
	$loop = 0;
	
	if($import_option == 'xml')
	{
	$entries = preg_match_all("#<outline(.*?)/>#si", $content, $null);
	
	if($entries != 0)
	{
	for ($i = 1; $i <= $entries; $i++)
	{
	// channel name
	preg_match_all("#text=\"(.*?)\"#si", $content, $channel_name);
	$channel_name = str_replace("text=\"", "", $channel_name[0][$i]);
	$channel_name = str_replace("\"", "", $channel_name);
	
	// channel id
	preg_match_all("#channel_id=(.*?)\"#si", $content, $channel_id);
	$channel_id = str_replace("channel_id=", "", $channel_id[0][$i-1]);
	$channel_id = str_replace("\"", "", $channel_id);
	
	// check if channel already exist
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `#channel_list` WHERE `channel_id` LIKE '".$match_channel_id[0][$i]."' AND `account` LIKE '".$account."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary == 0)
	{
	$loop = $loop + 1;
	
	mysqli_query($dbmysqli, "INSERT INTO `#channel_list` 
	(
	channel_id, 
	channel_name, 
	account, 
	active
	) VALUES (
	'".$match_channel_id[0][$i]."', 
	'".$channel_name."', 
	'".$account."', 
	'yes'
	) 
	");
	}
	} // for i
	
	} else {
	
	echo '<strong>An error occured. Check the inserted content!</strong>';
	exit;
	}
	
	} // xml


	if($import_option == 'html')
	{
	preg_match_all("#ytInitialData(.*?)ytInitialPlayerResponse#si", $content, $source);
	$entries = preg_match_all("#channelRenderer(.*?)channelId#si", $source[0][0], $null);

	if($entries != 0)
	{
	for ($i = 0; $i < $entries; $i++)
	{
	// channel name
	preg_match_all("#title\":{\"simpleText\"(.*?)},#si", $source[0][0], $match_channel_name);
	$match_channel_name[0][$i] = str_replace("title\":{\"simpleText\":\"", "", $match_channel_name[0][$i]);
	$match_channel_name[0][$i] = str_replace("\"},", "", $match_channel_name[0][$i]);
	
	// channel id
	preg_match_all("#:{\"channelId\":(.*?)\",\"#si", $source[0][0], $match_channel_id);
	$match_channel_id[0][$i] = str_replace(":{\"channelId\":\"", "", $match_channel_id[0][$i]);
	$match_channel_id[0][$i] = str_replace("\",\"", "", $match_channel_id[0][$i]);
	
	// check if channel already exist
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `#channel_list` WHERE `channel_id` LIKE '".$match_channel_id[0][$i]."' AND `account` LIKE '".$account."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary == 0)
	{
	$loop = $loop + 1;
	mysqli_query($dbmysqli, "INSERT INTO `#channel_list` 
	(
	channel_id, 
	channel_name, 
	account, 
	active
	) VALUES (
	'".$match_channel_id[0][$i]."', 
	'".$match_channel_name[0][$i]."', 
	'".$account."', 
	'yes'
	) 
	");
	}		
	} // for i
	
	echo $loop.' Channels added!';
	exit;
	
	} else { 
	
	echo '<strong>An error occured. Check the inserted content!</strong>'; 
	exit;
	}
	} // html
	
}


if($action == 'show_last_videos')
	{
	$loop = 0;
	
	$sql_0 = mysqli_query($dbmysqli, "SELECT `id` FROM `#accounts` WHERE `mailadress` LIKE '".$account."' ");
	$result_0 = mysqli_fetch_assoc($sql_0);
	$account_id = $result_0['id'];
	
	$sql_1 = "SELECT * FROM `".$channel_id."#".$account_id."` ORDER BY `timestamp` DESC LIMIT 0,25";
	
	if ($result_1 = mysqli_query($dbmysqli,$sql_1))
	{
	while ($obj = mysqli_fetch_object($result_1)){
	{
	
	if(!isset($last_videos_content) or $last_videos_content == ''){ $last_videos_content = ''; }
	
	if($date_format == $format_1){ $publish_date = 'd.m.Y, H:i'; }
	if($date_format == $format_2){ $publish_date = 'm/d/Y, g:i A'; }
	
	$loop = $loop + 1;
	
	$last_videos_content = $last_videos_content.'
	<tr>
		<td>'.$loop.'</td>
		<td>
		<a href="https://youtube.com/watch?v='.$obj->video_id.'" target="_blank"> 
		<img src="'.$obj->video_thumbnail.'" width="150" height="112" alt="'.$obj->video_title.'" title="'.$obj->video_title.'"></a>
		</td>
		<td><h4>'.$obj->video_title.'</h4>
		<div class="spacer_5"></div>
		'.date($publish_date, $obj->timestamp).'
		</td>
	</tr>';
	
	}
	}
	}

	$last_videos_header = '
	<div class="row">
	<table class="table table-hover">
	<tbody>';
	
	$last_videos_footer = '
	</tbody>
	</table>
	';
	
	if(!isset($last_videos_content) or $last_videos_content == ''){ $last_videos_content = '<div style="padding-left:10px;">No videos in database.</div>'; }
	
	echo $last_videos_header.$last_videos_content.$last_videos_footer;
	
	exit;
}


if($action == 'search_channel_id')
	{
	sleep(1);
	$curl = curl_init();
	curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => $channel_url,
    CURLOPT_USERAGENT => 'Mozilla 5./0 (compatible) Opera or Gecko',
	));
	$response = curl_exec($curl);
	
	preg_match_all("#value\":\"(.*?)\"#si", $response, $channel_id);
	$channel_id[0][0] = str_replace("value\":\"", "", $channel_id[0][0]);
	$channel_id[0][0] = str_replace("\"", "", $channel_id[0][0]);
	
	if($channel_id[0][0] == ''){ echo 'Channel not found!'; } else { echo 'Channel ID: <strong>'.$channel_id[0][0].'</strong>'; }
	
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Youtube Upload Mail Reminder</title>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="css/style.css" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="top">
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>-->
      <a class="navbar-brand" href="./">Youtube Upload Mail Reminder</a> </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">Link</a>
                    </li>
                </ul>
            </div>-->
    <!-- /.navbar-collapse -->
  </div>
  <!-- /.container -->
</nav>
<!-- Page Content -->
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <p class="lead">Account</p>
      <div id="account_list" class="list-group"> </div>
    </div>
    <div class="col-md-9">
      <div class="row carousel-holder"></div>
      <div class="row">
        <div class="col-sm-12 col-lg-12 col-md-12" id="content">&nbsp;</div>
      </div>
    </div>
  </div>
</div>
<!-- /.container -->
<div class="container">
  <hr>
  <!-- Footer -->
  <footer>
    <div class="row">
      <div class="col-lg-12">
        <p align="center"><a href="https://github.com/gitman328/youtube-upload-mail-reminder" target="_blank">Youtube Upload Mail Reminder</a></p>
      </div>
    </div>
  </footer>
  <!--modal1 bulk import-->
  <div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="Modal1Label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <span>Input format: 
        <select id="import_option" onChange="show_info()">
        <option value="html" selected>HTML</option>
        <option value="xml">XML</option>
        </select>
        </span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group"> To import your whole subscription list from Youtube, make these steps.
            <ul id="import-info">
              <div class="spacer_10"></div>
                <li>Login into your Youtube Account, and open your <a href="https://www.youtube.com/feed/channels" target="_blank">subscription list</a></li>
                <li>Press key Ctrl + U to view the source code from Youtube page</li>
                <li>Press key Ctrl + A to mark the whole source code</li>
                <li>Press key Ctrl + C to copy the whole source code</li>
                <li>Paste the content in text area down below, and click the button to import</li>
            </ul>
            <div class="spacer_5"></div>
            <textarea id="xml_content" cols="3" rows="5" style="width:100%;"></textarea>
            <div class="spacer_10"></div>
            <div align="center" id="import_status">&nbsp;</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-block" onclick="bulk_import('import')">Import</button>
        </div>
        <input id="account_name" type="text" hidden>
      </div>
    </div>
  </div>
</div>
<!--modal1-->
<!--modal2 last videos-->
  <div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="Modal2Label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <span id="Modal2Header"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group"> 
            <div id="last_videos_list"></div>
          </div>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
<!--modal2-->
<!--modal3 search channel id-->
  <div class="modal fade" id="Modal3" tabindex="-1" role="dialog" aria-labelledby="Modal3Label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
          <p>Paste in the complete URL from the Youtube user channel</p>
            <input id="channel_url" type="text" class="form-control" style="width:80%;" placeholder="e.g. https://www.youtube.com/user/NewerDocumentaries">
                <div class="spacer_5"></div>
                <input type="button" class="btn btn-success" onclick="search_channel_id();" value="Search">
              <div class="spacer_20"></div>
            <div id="search_result">&nbsp;</div>
          </div>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
<!--modal3-->
<!-- /.container -->
<!-- jQuery -->
<script src="js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<!-- Custom Scropt -->
<script src="js/script.js"></script>
<script>
$(function() {
	$(window).scroll(function(){
	if ($(this).scrollTop() > 500) {
	$('.scroll-top').fadeIn();
	} else {
	$('.scroll-top').fadeOut();
	}
});
$('.scroll-top').click(function(){
	$('html, body').animate({scrollTop : 0},500);
	return false;
	});
});

$(document).ready(function(){
	$.post("index.php",
	{
	action: 'show_accounts'
	},
	function(data){
	$("#account_list").html(data);
	});
	
	// read cookie
function getCookieValue(a) {
   var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
   return b ? b.pop() : '';
   }
   var s_account = getCookieValue("s_account");
   if(s_account != ''){ show_subscription_list(s_account); }
});
</script>
<a class="scroll-top"> <i class="glyphicon glyphicon-arrow-up"></i> </a>
<span style="display:none;"><img src="loading.gif"></span>

</body>
</html>

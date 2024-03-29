// JavaScript Document
function add_account(){
	
	$.post("index.php",
	{
	action: 'add_account'
	},
	function(data){
	$("#content").html(data);
	});
}


function save_account(){
	
	var mailadress = $("#mailadress").val();
	
	if(mailadress == ''){ return; }
	
	$("#save_account_status").html("<img src=\"loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("index.php",
	{
	action: 'save_account',
	mailadress: mailadress
	},
	function(data){
	
	if(data != 'success'){ $("#save_account_status").html(data); }
	
	if(data == 'success')
	{
	$.post("index.php",
	{
	action: 'show_accounts'
	},
	function(data){
	$("#account_list").html(data);
	$("#save_account_status").html("");
	});
	}	
	});
}


function delete_account(account){

	if(confirm(unescape('Are you sure?'))){
	
	document.cookie="s_account="+account+";"+"max-age="+(3600*24*-1)+"";
	
	$.post("index.php",
	{
	action: 'delete_account',
	account: account
	},
	function(data){
	if(data == 'account_deleted')
	{
	s1 = 'loca';
	s2 = 'tion.r';
	s3 = 'eplace("';
	s4 = './");';
	if (document.all || document.getElementById || document.layers)
	eval(s1+s2+s3+s4);
	}
	});
	
	} else {
    return;
	}
}


function activate_account(account){
	
	if($("#account").is(':checked')){ var active = 'yes'; } else { var active = 'no'; }
	
	$.post("index.php",
	{
	action: 'activate_account',
	account: account,
	active: active
	});
}


function channel_list(account){
	
	$.post("index.php",
	{
	action: 'show_subscription_list',
	account: account
	},
	function(data){
	$("#content").html(data);
	});
}


function save_channel(account){
	
	var channel_name = $("#channel_name").val();
	var channel_id = $("#channel_id").val();
	
	if(channel_id == ''){ return; }
	if(channel_name == ''){ return; }
	
	$("#status_register").html("<img src=\"loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("index.php",
	{
	action: 'save_channel_id',
	channel_id: channel_id,
	channel_name: channel_name,
	account: account
	},
	function(data){
	if(data == 'success'){ setTimeout(function() { channel_list(account); }, 1000); 
	$("#status_register").html('<i class="glyphicon glyphicon-ok fa-1x" style="color:#5CB85C"></i>');} else { $("#status_register").html(data); }
	});
}


function show_subscription_list(account){

	document.cookie="s_account="+account+";"+"max-age="+(3600*24*365)+"";
	
	$.post("index.php",
	{
	action: 'show_subscription_list',
	account: account
	},
	function(data){
	$("#content").html(data);
	});
}


function subscription_list(action,channel_id,account){
	
	if($("#checkbox_"+channel_id).is(':checked')){ var active = 'yes'; } else { var active = 'no'; }
	
	var channel_name = $("#channel_name_"+channel_id).val();
	
	$.post("index.php",
	{
	action: action,
	channel_id: channel_id,
	channel_name: channel_name,
	account: account,
	active: active
	},
	function(data){
	if(action == 'delete_channel'){ $("#item_"+channel_id).fadeOut(); }
	
	if(action == 'show_last_videos'){ 
	$("#Modal2Header").html("<a href=\"https://youtube.com/channel/"+channel_id+"\" target=\"_blank\">"+channel_name+"</a>"); 
	$('#Modal2').modal('show'); 
	$("#last_videos_list").html(data); 
	return; 
	}
	
	$("#edit_status").html("");
	});
}


function bulk_import(action,account){
	
	if(action == 'modal'){ $('#Modal1').modal('show'); $("#account_name").val(account); }
	
	if(action == 'import')
	{
	var account = $("#account_name").val();
	var content = $("#xml_content").val();
	var import_option = $("#import_option").val();
	
	if(content == ''){ return; }
	
	$("#import_status").html("<img src=\"loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("index.php",
	{
	action: 'bulk_import',
	account: account,
	content: content,
	import_option: import_option
	},
	function(data){
	$("#import_status").html(data);
	
	if(data.match(/channels/i))
	{
	setTimeout(function() { $('#Modal1').modal('hide'); $("#xml_content").val(""); $("#import_status").html(""); show_subscription_list(account); }, 1500); 
	}
	});
	}
}


function show_info(option){

	var option = $("#import_option").val();
	
	if(option == 'html')
	{
	$("#import-info").html("<div class=\"spacer_10\"></div>\
	<li>Login into your Youtube Account, and open your <a href=\"https://www.youtube.com/feed/channels\" target=\"_blank\">subscription list</a></li>\
	<li>Press Ctrl+U to open the source code from page</li>\
	<li>Press Ctrl+A to mark the source code</li>\
	<li>Press Ctrl+C to copy the source code</li>\
	<li>Paste the source code in text area down below, and click the button to import</li>");
	}
	
	if(option == 'xml')
	{
	$("#import-info").html("<div class=\"spacer_10\"></div>\
	<li>Login into your Youtube Account</li>\
	<li><a href=\"https://www.youtube.com/subscription_manager?action_takeout=1\" target=\"_blank\">Download the XML file from your subscription list</a></li>\
	<li>Open the XML file with a Text Editor, and copy the content</li>\
	<li>Paste the complete content from the XML file in text area down below</li>\
	<li>Click the button to import</li>");
	}
	
}


function search_channel_id(action)
	{
	if(action == 'modal'){ $('#Modal3').modal('show'); return; }
	var channel_url = $("#channel_url").val();
	if(channel_url == ''){ return; }
	
	$("#search_result").html("<img src=\"loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("index.php",
	{
	action: 'search_channel_id', 
	channel_url: channel_url
	},
	function(data){
	$("#search_result").html(data);
	});
}

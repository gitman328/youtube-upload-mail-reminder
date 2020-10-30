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
	if(data == 'success'){ channel_list(account); } else { $("#status_register").html(data); }
	});
}


function show_subscription_list(account){
	
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
	$("#edit_status").html("");
	});
}

function bulk_import(action,account){
	
	if(action == 'modal'){ $('#Modal1').modal('show'); $("#account_name").val(account); }
	
	if(action == 'import')
	{
	var account = $("#account_name").val();
	var content = $("#xml_content").val();
	if(content == ''){ return; }
	
	$("#import_status").html("<img src=\"loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("index.php",
	{
	action: 'bulk_import',
	account: account,
	content: content
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

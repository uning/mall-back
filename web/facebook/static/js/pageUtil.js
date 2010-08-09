var inviteCallBack;
function inviteFriends(param, callBack) {
	var max;
	var exclude;
	var type;
	var wind;
	try {
		type = param.type;
		max = param.max;
		exclude = param.exclude;

	} catch (e) {
	}
	
	inviteCallBack = callBack;
	if (max == null)
		max = "";
	if (exclude == null)
		exclude = "";

	var div =  $("#tabs");
	if(div==null||div=='undefined')
	div = $("#tabs",window.parent.document);
	 var inviteH = $("#invite");
	if(inviteH==null||inviteH=='undefined')
		inviteH = $("#invite",window.parent.document);
	try {
		navigateTo(inviteH.attr('href'));
		for(var i =1;i<7;i++){
			$("#li"+i).removeClass('selected');
		}
		inviteH.parent().addClass('selected');
		return false;
	} catch (e) {
	}

}

function visitFriends(fbid, gid, callBack) {
	$("#likeFrame",window.parent.document).attr('src', "pop/like.php?fbid=" + fbid);
}

function closeInvite(data) {
	var div = $("#inviteDiv");
	var inviteFrm = $("#inviteFrame");
	if (div.css("display") != 'none') {
		inviteFrm.attr('src', 'pop/invite/blank.php');
		div.css('display', 'none');
	

	}
	if (!isBlank(inviteCallBack) && (typeof (inviteCallBack) == 'function'))
		inviteCallBack(data);
}

function isBlank(str) {
	if (str == null || str == '')
		return true;
	return false;
}

var cached_publish_stream = false;

function init_popFrame() {
	$('#likeFrameC')
			.html(
					'<iframe id="likeFrame" src="pop/like.php?fbid=' + query_json.fb_sig_user + '" frameborder="0" border="0" width="754" height="54"' + ' style="overflow:hidden;margin:0;padding:0"></iframe>');
	$('#innerInviteFrameC').html();
	

}

var param;
var feedCall;
function popUpFeed(data,callBack){
	feedCall = callBack;
	FB.ui(prepareParams(data), feedPublishCallback);
}

function prepareParams(data){
	console.log('data:',data);
	param = data;
	var feedId = PLStat.uuid();
	param['fid'] = feedId;
	param['type'] = 3;
	//data.actionLinkName="www.koogoo.com"
	var publish = {
			  method: 'stream.publish',
			  attachment: {
			    name: data['name'],
			    caption: data['caption'],
			    description: (
			      ''
			    ),
			    href: 'pop/feeedBack.php?key='+feedId,
			    media: [
			      {
			        type: 'image',
			        href: 'http://127.0.0.1/work/mall/backend/web/facebook/pop/feedBack.php?key='+feedId,
			        src: data['picture']
			      }
			    ]
			  },
			 
			  user_prompt_message: 'tell us you story'
			};
	if(data['actionLinkName']!=null&&data['actionLinkName']!=''){
		publish['action_links'] = [
					 			    { text: data['actionLinkName'], href:'http://127.0.0.1/work/mall/backend/web/facebook/pop/fedBack.php?key='+feedId}
						 			  ]
	}
	return publish;
}

function feedPublishCallback(response){
	var pub = 1;
	if(response==null||response=='') pub = 0;
	
	$.ajax({
		type: 'POST',
		url: 'pop/storeFeed.php',
		data: 'type=' + param['type'] + '&task=' + param['task']+ '&gift=' + param['gift']+'&pid'+param['pid']+'&fid'+param['fid'],
		dataType:'text',
		success: feedCall
	});
	
}






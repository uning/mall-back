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
		div.children('a').removeClass('active');
		div.children('li').removeClass('active');
		inviteH.addClass('active');
		return false;
	} catch (e) {
	}

}

function visitFriends(pid,id,callBack){}
function payFor(pid, callBack) {
	
	var div =  $("#tabs");
	if(div==null||div=='undefined')
		div = $("#tabs",window.parent.document);
	var payFor = $("#pay");
	if(payFor==null||payFor=='undefined')
		payFor = $("#pay",window.parent.document);
	try {
		navigateTo(payFor.attr('href'));
		div.children('a').removeClass('active');
		div.children('li').removeClass('active');
		payFor.addClass('active');
		return false;
	} catch (e) {
	}
	
}
function chooseGift(callBack)
{
	var div =  $("#tabs");
	if(div==null||div=='undefined')
		div = $("#tabs",window.parent.document);
	var freeGift = $("#freeGift");
	if(freeGift==null||freeGift=='undefined')
		freeGift = $("#freeGift",window.parent.document);
	try {
		navigateTo(freeGift.attr('href'));
		div.children('a').removeClass('active');
		div.children('li').removeClass('active');
		freeGift.addClass('active');
		return false;
	} catch (e) {
	}
	
}

function sendGift(giftid,callBack)
{
	
	var div =  $("#tabs");
	if(div==null||div=='undefined')
	div = $("#tabs",window.parent.document);
	var inviteH = $("#invite");
	if(inviteH==null||inviteH=='undefined')
		inviteH = $("#invite",window.parent.document);
	try {
		var g = isBlank(giftid)?'':'?gift='+giftid;
		navigateTo(inviteH.attr('href')+g);
		div.children('a').removeClass('active');
		div.children('li').removeClass('active');
		inviteH.addClass('active');
		return false;
	} catch (e) {
	}
}
function isBlank(str) {
	if (str == null || str == '')
		return true;
	return false;
}

var cached_publish_stream = false;


var param;
var feedCall;
var ddd = {
		'picture':'http://rrmall.playcrab.com/work/mall/backend/web/renren/static/images/feed/gift.jpg',
		'name' : '礼物feed',
		'caption':'这里是内容',
		'ext':{'feedtype':1}
		
};
function popUpFeed(data,callBack){
	
	feedCall = callBack;
	if(data)
	XN.Connect.showFeedDialog(prepareParams(data));
	else
	XN.Connect.showFeedDialog(prepareParams(ddd));
	
}
function stat(op)
{
	/*if(_gaq){
		  _gaq._trackEvent('Feed', op);
	  }*/
}
function prepareParams(data){
	console.log('data:',data);
	param = data;
	var feedId = PLStat.uuid();
	param['fid'] = feedId;
	 var publish = {
	  			template_bundle_id: data['ext']['feedtype'],
	  			template_data: {images:[
	                            {src:data['picture'], 
	                            href:'http://apps.renren.com/livemall/feed_back.php?ft='+data['ext']['feedtype']+'&action={action}&xnuid={xnuid}&fid='+feedId}
	                              ]
	                              ,feedtype:data['name']
	                              ,content:data['caption']  
	                              ,xnuid:PL.conf('pid')
	                              ,action:'feed_back.php?&fid='+feedId
	                              },
	  			body_general: '',
	  			callback: feedPublishCallback,
	  			user_message_prompt: "有啥想法没？^o^",
	  			user_message: "讲两句吧.."
	  		};
	
	return publish;
}

function feedPublishCallback(response){
	var pub = 1;
	if(response==null||response=='') pub = 0;
	if(pub==0){
		stat('Try '+param['ext']['feedtype']);
	}else if(pub==1){
		stat('Ok '+param['ext']['feedtype']);
		var k='';
		if(param['ext']['feedtype']==2){
			k = '&task=' + param['task'];
		}if(param['ext']['feedtype']==3){
			k = '&gift=' + param['gift'];
		}
		$.ajax({
			type: 'POST',
			url: '../pop/storeFeed.php',
			data: 'type=' + param['ext']['feedtype']+'&fid'+param['fid'] + k+'&pid'+PL.conf('pid'),
			dataType:'text',
			success: function (response){alert(response);}
		});
	}
	
	
}


/**
 * 响应tab切换
 * **/
var appFrame, flashFrame, loadingFrame, htmlFrame, iframe, oldIframe, tabs, flashTab, preloadedUrls = {}, needToRestore;
var cleanup = function() {
	if(oldIframe)
	oldIframe.remove();
	/*if (needToRestore) {
		preloadUrl(needToRestore);
		needToRestore = null;
	}*/
};

var hideFlash = function() {
	if(document.getElementById('flash_run_id')){
		try{
		document.getElementById('flash_run_id').shutDownMusic();
		}catch(e){}
	}
	window.location.hash = '#notOnFlash';
	flashFrame.addClass('offscreen');
	
};

var hideHtml = function() {htmlFrame.addClass('offscreen'); if(oldIframe)
	oldIframe.remove();};
var hideLoading = function() {loadingFrame.hide();};

var showHtml = function() {
	hideLoading();
	hideFlash();
	htmlFrame.removeClass('offscreen');
	appFrame.removeClass('flashVisible');
};

var showLoading = function() {
	hideHtml();
	hideFlash();
	loadingFrame.show();
	//loadingFrame.removeClass('offscreen');
	
	appFrame.removeClass('flashVisible');
};

var showFlash = function() {
	if(document.getElementById('flash_run_id')){
		try{
			document.getElementById('flash_run_id').turnOnMusic();
		}catch(e){}
	}
	hideHtml();
	hideLoading();
	flashFrame.removeClass('offscreen');
	flashFrame.show();
	appFrame.addClass('flashVisible');
	oldIframe = iframe;
	iframe = false;
	tabs.children('a').removeClass('active');
	flashTab.children('a').addClass('active');
	setTimeout(cleanup, 500);
};

var interval, innerDoc, lastHeight;

var innerIFramePoller = function() {

	try {
		if (iframe == null || (iframe[0].contentWindow && iframe[0].contentWindow.location.hash == "#switchToFlash")) {
		  iframe && iframe.unbind('load', iframeLoaded);
			showFlash();
			clearInterval(interval);
			interval = null;
			if (iframe)
			iframe[0].contentWindow.location.hash = '#switched';
		} else {
			if (!innerDoc || lastHeight == null || lastHeight == 0) {
				try{
				innerDoc = $(iframe[0].contentWindow.document.body);
				}catch(e){}
			}
			
			var height = innerDoc.outerHeight();
			if (height != lastHeight|| height ==0) {
				iframe.height(height+200);
				lastHeight = height;
			}
		}
	} catch(e) {
	}

};

var iframeLoaded = function() {
	iframe && iframe.unbind('load', iframeLoaded);
	showHtml();
};

var navigateTo = function(url) {
	//return;
	if (url.match(/^#switchToFlash/)) {
		switchToFlash();
		return;
	}
	setupElements();
	showLoading();
	if (!iframe) {
		document.getElementById("htmlFrame").innerHTML = "";
		iframe = createIframe().appendTo(htmlFrame);
	}
	iframe.load(iframeLoaded);
	if(url.indexOf('?')>-1)
              nurl = url+'&pid='+query_json.xn_sig_user;
	else{
              nurl = url+'?&pid='+query_json.xn_sig_user;
	}
	console.log('navigateTo',nurl);
	iframe.attr('src', nurl);     
	innerDoc = null; lastHeight = 0;
	interval = setInterval(innerIFramePoller, 500);
};

var switchToFlash = function() {
	interval && clearInterval(interval);
	interval = null;
	showFlash();
};

var preloadFuncs = [];
var preloadUrl = function(url) {
	var preloader = function(){
		var preloadedFrame = createIframe();
		preloadedFrame.addClass('preloaded').attr('src', prepUrl(url)).appendTo(htmlFrame).load(function() {
			preloadedUrls[url] = preloadedFrame;
		});
	};

	if(preloadFuncs.push){
		preloadFuncs.push(preloader);
	}	
};

/*var readyToPreload = function() {
	setupElements();
	var funcs = preloadFuncs;
	preloadFuncs = [];
	setTimeout(function() {
		for (var i = 0; i < funcs.length; ++i) {
			funcs[i]();
		}
	}, 5000);
};
*/
var createIframe = function() {
	return $("<iframe/>", {scrolling: 'no', border: '0', frameborder: '0', height: '0'});
};

var setupElements = function() {
	appFrame = $('#appFrame');
	flashFrame = $('#flashFrame');
	loadingFrame = $('#loadingFrame');
	htmlFrame = $('#htmlFrame');
	flashTab = $('#flashTab');
};


$(document).ready(
		function(){
	/** Opens an overlaying iframe */
	(function() {
		
		var goTo  = function(el){
		 	if (!el.is('a')) {
				el = el.parents('a');
			}
			navigateTo(el.attr('href'));
			tabs.children('a').removeClass('active');
			el.addClass('active'); 
			
		};
		
		var tabClick = function(e) {
            if (e && e.target) {
				var el = $(e.target);
				goTo(el);
				return false;
			}
		};

		
		$(function() {
		   tabs = $('#tabs li');
			console.log(tabs.children('a'));
			tabs.children('a').not('.fullpage').click(tabClick);
			setupElements();
		});
		
		$(function() { 
	      if(a=='invite' || a=='freeGift'  || a=='faq'){
		    tabs = $('#tabs li');
			var link = $("#"+a);
			goTo(link);
		  }
		});

	})();

});

function sendNotifcation(ids,name,callBack,say)
{
	XN.Main.apiClient.notifications_send(45182749, '李彦宾'+"在<a href=\"http://apps.renren.com/livemall/\">购物天堂</a>送给了你一件神秘礼物，并对你说:快来玩啊，真好玩啊，放松一下吧，呵呵"+say, function (result, ex) {
		  if (ex) {
			window.alert("出错了，不好意思 " + ex.userData.error_msg);
	  	  }
		  else {
			window.alert(result.result);
		  }
    });

}

function openCinema(data,callBack)
{
	navigateTo('../pop/open_shop.php?oid='+data['oid']);
	$('#flash').removeClass('active');
	//callBack();
}
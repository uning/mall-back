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
		inviteH.addClass('active');
		return false;
	} catch (e) {
	}

}

function visitFriends(pid,id,callBack){}
function payFor(pid, callBack) {
	alert('暂未开放');
	return;
	var div =  $("#tabs");
	if(div==null||div=='undefined')
		div = $("#tabs",window.parent.document);
	var payFor = $("#pay");
	if(payFor==null||payFor=='undefined')
		payFor = $("#pay",window.parent.document);
	try {
		navigateTo(payFor.attr('href'));
		div.children('a').removeClass('active');
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
var d = {
		picture:'http://hdn.xnimg.cn/photos/hdn321/20100519/2235/tiny_Wx1k_67280c019118.jpg',
		name : '是我的马甲',
		caption:'曾经黄小虎就站在我面前'
}
function popUpFeed(data,callBack){
	
	feedCall = callBack;
	XN.Connect.showFeedDialog(prepareParams(d));
}

function prepareParams(data){
	console.log('data:',data);
	param = data;
	
	var feedId = PLStat.uuid();
	 var publish = {
	  			template_bundle_id: 1,
	  			template_data: {images:[
	                            {src:data['picture'], 
	                            href:'pop/feed_back.php?fid='+feedId}
	                              ]
	                              ,feedtype:data['name']
	                              ,content:data['caption']  
	                              ,xnuid:PL.conf('pid')
	                              ,action:'pop/feed_back.php?fid='+feedId
	                              },
	  			body_general: '',
	  			callback: feedPublishCallback,
	  			user_message_prompt: "有啥想法没？^o^",
	  			user_message: "here user_message"
	  		};
	
	return publish;
}

function feedPublishCallback(response){
	

	var pub = 1;
	if(response==null||response=='') pub = 0;
	$.ajax({
		type: 'POST',
		url: '../pop/storeFeed.php',
		data: 'type=' + param['type'] + '&task=' + param['task']+ '&gift=' + param['gift']+'&pid'+PL.conf('pid')+'&fid'+param['fid'],
		dataType:'text',
		success: feedCall
	});
	
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
	flashTab.addClass('active');
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
				iframe.height(height+10);
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
		
		
		var tabClick = function(e) {
			if (e && e.target) {
				var el = $(e.target);
				if (!el.is('a')) {
					el = el.parents('a');
				}
				navigateTo(el.attr('href'));
				tabs.children('a').removeClass('active');
				el.addClass('active');
				return false;
			}
		};

		
		$(function() {
			tabs = $('#tabs li');
			console.log(tabs.children('a'));
			tabs.children('a').not('.fullpage').click(tabClick);
			setupElements();
		});

	})();

});


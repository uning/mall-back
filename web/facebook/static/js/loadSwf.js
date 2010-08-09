
var queryParams = document.location.search || document.location.hash;
if (queryParams)
{
	if (/\?/.test(queryParams))
	{
		queryParams = queryParams.split("?")[1]; // strip question mark
	}
}



function nlc_stream_publish(params)
{
	parent.nlc_stream_publish(params);
}

function check_stream_permission()
{
	parent.check_stream_permission();
}

function get_stream_permission()
{
	parent.get_stream_permission();
}


// callback from check_stream_permission/get_stream_permission
function get_stream_permission_callback(value)
{
	getFlashMovie("flash_run_id").setStreamPermission(value); 
}

// Called to popup the window that prompts the user with ways to get more facebook credits
function get_more_fb_credits(params)
{
	parent.get_more_fb_credits(params);
}

// Called by app to retrieve amount of fb credits user currently has
function get_fb_credits(params)
{
	parent.get_fb_credits(params);
}

// Call this with amount of fb credits user currently has (value expected to be an int)
// This can be called at any time (not just as a response to get_fb_credits)
function get_fb_credits_callback(value)
{
	getFlashMovie("flash_run_id").fbcSetCredits(value); 
}

function fbcPurchaseItemCallback(item)
{
	console.log('js:fbcPurchaseItemCallback:' + item.itemId);
	parent.buy_with_fb_credits(item.itemId, item.quantity);		
	 console.log('done')
}

function callExternalInterface(data)
{
	/* Call a function registered as callPlayBall in the SWF named myMovie. */
	getFlashMovie("flash_run_id").fbcPurchaseComplete(data); 
}

/* This utility function resolves the string movieName to a Flash object reference based on browser type. */
/* typically you pass through '' */
function getFlashMovie(swfDivName)
{
	var isIE = navigator.appName.indexOf("Microsoft") != -1;
	return (isIE) ? window[swfDivName] : document.getElementById(swfDivName);
}

function invite_friends_pop(obj)
{
	parent.invite_friends_open(obj);
}

function invite_friends_gift_pop(obj)
{
	parent.invite_gift_open(obj);
}

function invite_friends_callback(data)
{
	getFlashMovie("flash_run_id").userDidInviteFriends(data);
}

function switch_like(clubOwnerFbUserId)
{
	parent.switch_like(clubOwnerFbUserId);
}


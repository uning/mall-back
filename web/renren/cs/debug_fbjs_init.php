
<script type='text/text' id='log_htmls'> 
<div class="clearfix">
<button onclick="login()" class="login-button">connect</button>
<button onclick="disconnect()">Disconnect</button>
<span id="auth-status-container">status:
<span id="auth-status">unknown</span>
</span>
<input id='test_method'></input>
<button onclick='dotest()'>dotest</button>
<button onclick='dodump()'>dodump</button>
<div id="log-container">
<button onclick="Log.clear()">Clearlog</button>
<div id="log"></div>
</div>
</div>
</script>

<script>

PL.js(['jquery-1','jsDump-1.0.0.js','delegator.js','log.js'],function(){
                $('body').append($('#log_htmls').html());
				Log.init(document.getElementById("log"),'debug');
				Log.debug("log inited");
});
var old = after_fbinit || false;
var after_fbinit = function(){
	if(old){
		old();
	}
	FB.getLoginStatus(Log.info.bind('getLoginStatus'));
	console.info('in debug after_fbinit');
	Log.info('in after_fbinit');
}

console.info(before_fbinit);
var old = before_fbinit || false;
var before_fbinit = function(){
        if(old){
           old();
        }
	Log.info('in debug before_fbinit');
	console.info('in debug before_fbinit');
        FB.Canvas.setAutoResize(); 
	FB.Event.subscribe('fb.log', Log.info.bind('fb.log'));
	FB.Event.subscribe('auth.statusChange', function(response){
		var el = document.getElementById('auth-status');
		el.className = response.status;
		el.innerHTML = response.status;
	});
}

//tool functions
function login()
{
   var cb = function(response) {
    Log.info('FB.login callback', response);
    if (response.session) {
      Log.info('User logged in');
      if (response.perms){
        Log.info('User granted permissions');
      }
    } else {
      Log.info('User is logged out');
    }
    FB.Canvas.setAutoResize(false); 
  };
  FB.login(cb, { perms: 'publish_stream,email,read_stream' });
}


function login()
{
   var cb = function(response) {
    Log.info('FB.login callback', response);
    if (response.session) {
      Log.info('User logged in');
      if (response.perms){
        Log.info('User granted permissions');
      }
    } else {
      Log.info('User is logged out');
    }
  };
  FB.login(cb, { perms: 'publish_stream,email,read_stream' });
}

function disconnect()
{
 FB.api({ method: 'Auth.revokeAuthorization' }, Log.debug.bind('Auth.revokeAuthorization'));
}




function dotest()
{
  var m = document.getElementById('test_method').value;
  Log.debug("call " + m);
  Log.debug.bind("eval:" + m)(eval(m));
}


function dodump()
{
  var varname = document.getElementById('test_method').value;
  var m ='Log.debug.bind("dump: '+varname+'")(varname)';
  eval(m);
}

</script>



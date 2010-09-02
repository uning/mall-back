//loader and common function
//to add reinit guard
var PL = {
	_config :{},
	_query_str:false,
	_query_json:false,
	_useparent:false,
	_log_init:false,
	_platform_init:false,
	
	conf:function(name,value){

	   PL._config =   PL._config || {};
	   PL._config[name] = value || PL._config[name] || false
	   //console.log(PL._config,PL._config[name],value);
	   return PL._config[name]
	  
	},
	
	// f config file,cb: after init callback
	init : function(f, config){
		cb  =  config.cb||function(){};
	    PL._useparent = config.useparent || false;
		if (PL._useparent) {
			p = parent.PL || false;
			if (p._config) {
			    var o=PL._config
				for (i in o)
				   p._config[i]=o[i];
			
				PL._config = p._config;
				
				(config.fbd||PL._config.debug||config.log)&&PL.init_log(config.logcb);
				(config.fb||config.fbd)&&PL.init_platform(config.fbd||PL._config.debug||false,config.before_fbinit,config.after_fbinit);// need
				cb();
				return;
			}
		}
		d = new Date();
		v = d.getTime();
		f += '?' + v;
		// console.log('init from:' + f +' me='+ window.location.href)
		PL.js( [ f ], function(r) {

			 var o=PL._config
				for (i in o)
				   $config[i]=o[i];
			
			PL._config = $config;
			console.log('in init',PL._config);
			(config.fbd||PL._config.debug||config.log)&&PL.init_log(config.logcb);
			(config.fb||config.fbd)&&PL.init_platform(config.fbd||PL._config.debug||false,config.before_fbinit,config.after_fbinit);// need
			cb(r);
		})
	},
	
	init_log:function(cb){
		if(this._log_init)
			return;
			this._log_init = true;
		  cb = cb||function(){}
		   PL.js(['jquery-1','jsDump-1.0.0.js','delegator.js','log.js'],
				function(){
			   cb();
			$('body').append(
			"<input id='test_method'></input>"+
			"<button onclick='dotest()'>dotest</button>"+
			"<button onclick='dodump()'>dodump</button>"+
			'<div id="log-container"><button onclick="Log.clear()">Clearlog</button><div id="log">log here</div></div>');
			Log.init(document.getElementById("log"),'debug');
			Log.debug("log inited");
			window.dotest=function()
			{
				  var m = document.getElementById('test_method').value;
				  Log.debug("call " + m);
				  Log.debug.bind("eval:" + m)(eval(m));
			}


			window.dodump=function()
			{
				  var varname = document.getElementById('test_method').value;
				  var m ='Log.debug.bind("dump: '+varname+'")('+varname+ ')';
				  eval(m);
			}
		});
	},
	F : function(file) {
		
		if (PL._config == false || file.indexOf('/') >-1
				|| file.indexOf('https://') == 0)
			return file;
		var df = false;
		for ( var i = 0; PL._config.jsfiles&& i < PL._config.jsfiles.length; i++) {
			if (PL._config.jsfiles[i][0].indexOf(file) > -1) {
				df = PL._config.jspre[PL._config.jsfiles[i][1]]
						+ PL._config.jsfiles[i][0];
				break;
			}
		}
		return df||file;
	},

	js : function(c, d) {
		d = d||function(){}
		f = function() {
				if (!(this.readyState && this.readyState !== "complete" && this.readyState !== "loaded")) {
					// console.log("readyState " +this.src + " " +
					// this.readyState + ' e=' + e);
					this.onload = this.onreadystatechange = null;
					--e || d(c)
					
				}
		 }
		
		for ( var b = c.length, e = b,
		
		g = document.getElementsByTagName("head")[0], i = function(h) {
			nh = PL.F(h);
			// console.info('down find ' + h + " " + nh);
			if (!nh) {
				console.warn('down not find ' + h);
				--e || d(c);
				return;
			}
			idd = 'sc_' + nh;
			var a = document.getElementById(idd);
			if (a) {
				console.warn('exists a ' + nh + ' for ' + h);
				--e || d(c);
				return;
			}
			a = document.createElement("script");
			a.async = true;
			a.src = nh;
			a.id = idd;
			a.onload = a.onreadystatechange = f;
			g.appendChild(a)
		}; b;)
			i(c[--b])
	},
	getCookie:function(c_name)
	{
	  if (document.cookie.length>0)
	  {
	    var c_start=document.cookie.indexOf(c_name + "=");
	    if (c_start!=-1)
	    {
	      c_start = c_start + c_name.length+1;
	      var c_end=document.cookie.indexOf(";",c_start);
	      if (c_end==-1) c_end=document.cookie.length;
	      return unescape(document.cookie.substring(c_start,c_end).replace(/\+/g, ' '));
	    }
	  }
	  return "";
	},
	getUrlVars : function() {
		var vars = {}, hash;
		qstr = PL.query_str();
		var hashes = qstr.split('&');
		for ( var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			if(hash.length>1)
			 vars[hash[0]] = hash[1];
		}
		
		return vars;
	},
	
	getUrlVar : function(name, defv) {
		return PL.getUrlVars()[name] || defv;
	},
	
	query_str:function()
	{
	    ret = PL._query_str||false;
	    if(ret)
	    	return ret;
	    qs=window.location.href.indexOf('?');
	    ret = '';
	    if(qs>-1)
	      ret = PL._query_str = window.location.href.slice(qs + 1);
	    return ret;
		
	},
	query_json:function()
	{
	    ret = PL._query_json||false;
	    if(ret)
	    	return ret;
	    ret = PL._query_json = PL.getUrlVars();
	    return ret;
		
	},
	uuid:function() {
		  // Private array of chars to use
		  var CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
		  return function (len, radix) {
		    var chars = CHARS, uuid = [], rnd = Math.random;
		    radix = radix || chars.length;

		    if (len) {
		      // Compact form
		      for (var i = 0; i < len; i++) uuid[i] = chars[0 | rnd()*radix];
		    } else {
		      // rfc4122, version 4 form
		      var r;

		      // rfc4122 requires these characters
		      uuid[8] = uuid[13] = uuid[18] = uuid[23] = '';
		      uuid[14] = '4';

		      // Fill in random data.  At i==19 set the high bits of clock sequence as
		      // per rfc4122, sec. 4.1.5
		      for (var i = 0; i < 36; i++) {
		        if (!uuid[i]) {
		          r = 0 | rnd()*16;
		          uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r & 0xf];
		        }
		      }
		    }

		    var ret = uuid.join('');
		    return ret.substring(0, 32);
		  }();
		},

	obj2str : function(o) {
		var r = [];
		if (typeof o == "string" || o == null) {
			return o;
		}
		if (typeof o == "object") {
			if (!o.sort) {
				r[0] = "{"
				for ( var i in o) {
					r[r.length] = i;
					r[r.length] = ":";
					r[r.length] = PL.obj2str(o[i]);
					r[r.length] = ",";
				}
				r[r.length - 1] = "}"
			} else {
				r[0] = "["
				for ( var i = 0; i < o.length; i++) {
					r[r.length] = PL.obj2str(o[i]);
					r[r.length] = ",";
				}
				r[r.length - 1] = "]"
			}
			return r.join("");
		}
		return o.toString();
	},
	
	init_platform : function(debug,before_init,after_init) {
		console.log("platform init... ");
		before_fbinit  = before_init  || function(){};
		after_fbinit = after_init || function(){};
		if(debug){
			console.info(before_fbinit);
			var oldb = before_fbinit || false;
			var before_fbinit = function(){
				  console.info('in debug before_fbinit');
			        if(oldb){
			           oldb();
			        }
				  
			}
			var olda = after_fbinit || false;
			var after_fbinit = function(){
				Log.info('in debug after_fbinit');
				if(olda){
					olda();
				}
				
				XN.Connect.get_status().waitUntilReady(function (login_state) {
					 /*
						 * XN.Main.apiClient.friends_get(Log.info.bind('XN.Main.apiClient.friends_get'));
						 * XN.Main.apiClient.friends_getAppUsers(Log.info.bind('XN.Main.apiClient.friends_getAppUsers'));
						 * XN.Main.apiClient.friends_getAppFriends(Log.info.bind('XN.Main.apiClient.friends_getAppFriends'));
						 * 
						 * XN.Main.apiClient.users_getInfo(Log.info.bind('XN.Main.apiClient.users_getInfo'));
						 * 
						 * XN.Main.apiClient.friends_getAppFriends(Log.info.bind('XN.Main.apiClient.friends_getAppFriends'));
						 * XN.Main.apiClient.friends_getAppUsers(Log.info.bind('XN.Main.apiClient.friends_getAppUsers'));
						 * 
						 * XN.Main.apiClient.users_getLoggedInUser(Log.info.bind('XN.Main.apiClient.users_getLoggedInUser'));
						 * XN.Main.apiClient.users_isAppAdded(Log.info.bind('XN.Main.apiClient.users_isAppAdded'));
						 * XN.Main.apiClient.connect_getUnconnectedFriendsCount(Log.info.bind('XN.Main.apiClient.connect_getUnconnectedFriendsCount'));
						 * 
						 */
					 var el = document.getElementById('auth-status');
					el.className = login_state;
					el.innerHTML = login_state;
				});
				 XN.Main.get_sessionState().waitUntilReady(function (){
					 var get_user = function(r)
					 {
						  PL.conf('pid', r.uid);
						  Log.info.bind('XN.Main.apiClient.users_getLoggedInUser')(r);
						 XN.Main.apiClient.users_getInfo([ PL.conf('pid')],["uid",
						                                                    "name",
						                                                    "sex",
						                                                    "star",
						                                                    "zidou",
						                                                    "vip",
						                                                    "birthday",
						                                                    "email_hash",
						                                                    "tinyurl"						                                             		                                          
						                                                    ],Log.info.bind('XN.Main.apiClient.users_getInfo'));
						 // XN.Main.apiClient.users_isAppAdded([
							// PL.conf('pid')],Log.info.bind('XN.Main.apiClient.users_isAppAdded'));
					 }
					     XN.Main.apiClient.users_getLoggedInUser(get_user);
					 	 XN.Main.apiClient.friends_get(Log.info.bind('XN.Main.apiClient.friends_get'));
						 XN.Main.apiClient.friends_getAppUsers(Log.info.bind('XN.Main.apiClient.friends_getAppUsers'));
					
					
				 } );
				
			}

		
		}
		
		var initcb = function() {
			console.log("platform init callback ");
			before_fbinit();
			XN_RequireFeatures(["Connect","EXNML", "CanvasUtil"], function(){
				  XN.Main.init(PL._config.api_key,PL._config.reciever_url);
				  
				  after_fbinit();
				  console.log("platform  init ok ");
				});
			
			
		};
		
		
		 PL.js([ document.location.protocol + '//static.connect.renren.com/js/v1.0/FeatureLoader.jsp'],
				 initcb);
				 
		

		 if(debug){
			 var c=document.createElement('div');
			 c.innerHTML='<div class="clearfix">'+
			'<button onclick="login()" class="login-button">connect</button>'+
			'<button onclick="disconnect()">Disconnect</button>'+
			'<span id="auth-status-container">status:'+
			'<span id="auth-status">unknown</span>'+
			'</span>'+
			'</div>';
			 document.getElementsByTagName("body")[0].appendChild(c)
			// tool functions
			 window.login =function()
			 {
			    var cb = function(response) {
			     Log.info('login callback', response);
			     if (response.session) {
			       Log.info('User logged in');
			       if (response.perms){
			         Log.info('User granted permissions');
			       }
			     } else {
			       Log.info('User is logged out');
			     }
			  
			   };
			   XN.Connect.requireSession(cb);
			 }
			 window.disconnect=function()
			 {
				 XN.Connect.get_status().waitUntilReady(function (login_state) {
					   XN.Connect.logout(function () {
						   alert(PL._config.canvas_url);
	                       window.location = PL._config.canvas_url;
	                   });

					});
			 }
		 }
	}
}

query_json = PL.query_json();
query_str  = PL.query_str();
// for firebug
// logf=function (r){alert(r)};
logf = function(r) {
};
if (typeof (console) == 'undefined') {
	var console = {
		log : logf,
		error : logf,
		info : logf,
		dir : logf
	};
}

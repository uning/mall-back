//loader and common function
//to add reinit guard
var PL = {
	_config : false,
	_query_str:false,
	_query_json:false,
	_useparent:false,
	_log_init:false,
	_fb_init:false,
	
	conf:function(name,value){
	  return PL._config[name] = value||PL._config[name]||0;
	},
	
	// f config file,cb: after init callback
	init : function(f, config){
		cb  =  config.cb||function(){};
		// config.fb&&PL.init_fb(config.fbd||false,config.before_fbinit,config.after_fbinit);//
	
	    this._useparent = config.useparent || true;
		if (this._useparent) {
			p = parent.PL || false;
			if (p._config) {
				// console.dir(parent);
				console.log('init from parent : me='+  window.location.href)
				this._config = p._config;
				(config.fbd||PL._config.debug||config.log)&&PL.init_log(config.logcb);
				(config.fb||config.fbd)&&PL.init_fb(config.fbd||PL._config.debug||false,config.before_fbinit,config.after_fbinit);// need
				return;
			}
		}
		d = new Date();
		v = d.getTime();
		f += '?' + v;
		// console.log('init from:' + f +' me='+ window.location.href)
		PL.js( [ f ], function(r) {
			// console.log('from '+ f +' config download : me='+
			// window.location.href)
			// console.dir( $config);
			PL._config = $config;
			(config.fbd||PL._config.debug||config.log)&&PL.init_log(config.logcb);
			(config.fb||config.fbd)&&PL.init_fb(config.fbd||PL._config.debug||false,config.before_fbinit,config.after_fbinit);// need
			cb(r);
		})

	},
	init_fb : function(debug,after_fbinit,before_fbinit) {
		console.log("fb init... ");
		window.fbAsyncInit = function() {
			before_fbinit();
			FB.init( {
				appId : PL._config.app_id,
				apiKey : PL._config.api_key,
				status : true, // check login status
				cookie : true, // enable cookies to allow the server to access
				xfbml : true// parse XFBML
					});
			after_fbinit();
			console.log("fb init ok ");
		};
		before_fbinit = before_fbinit || function(){};
		after_fbinit  = after_fbinit  || function(){};
		if(debug){
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
				    console.info('in debug before_fbinit');
			        FB.Canvas.setAutoResize(); 
				    FB.Event.subscribe('fb.log', Log.info.bind('fb.log'));
				    FB.Event.subscribe('auth.statusChange', function(response){
					var el = document.getElementById('auth-status');
					el.className = response.status;
					el.innerHTML = response.status;
				});
			}
		}
		
		
		 var e = document.createElement('script');
		 e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		 e.async = true;
		// document.getElementsByTagName("head")[0].appendChild(e);
		// return;
		 var fbr=document.createElement('div');
		 fbr.id = 'fb-root';
		 fbr.appendChild(e);
		
		 document.getElementsByTagName('body')[0].appendChild(fbr);
		 if(debug){
			 var c=document.createElement('div');
			 c.innerHTML='<div class="clearfix">'+
			'<button onclick="login()" class="login-button">connect</button>'+
			'<button onclick="disconnect()">Disconnect</button>'+
			'<span id="auth-status-container">status:'+
			'<span id="auth-status">unknown</span>'+
			'</span>'+
			'</div>';
			 fbr.appendChild(c);
			// tool functions
			 window.login =function()
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
			   FB.login(cb, { perms: 'email,read_stream' });// publish_stream,
			 }
			 window.disconnect=function()
			 {
			  FB.api({ method: 'Auth.revokeAuthorization' }, Log.debug.bind('Auth.revokeAuthorization'));
			 }
		 }
		
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
		for ( var i = 0; i < PL._config.jsfiles.length; i++) {
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
					//console.log("readyState " +this.src + " " + this.readyState + ' e=' + e);
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
	}
}

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

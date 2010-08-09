// JavaScript Document
//本文件是同AS库中相互配合使用的facebook接口，使用时请注意一下几点：
//一：将SWFObject的id设为flash_run_id,这样能正常工作。
//二：在flash_run_id所在的html页面中<body>标签后一定要包含标准的对facebook的jsSDK的引用
//这里尝试过使用flash_run_id = document.getElementById("flash_run_id"),
//然后在每个方法中用flash_run_id来代替所有的语句，结果无效，应该是js用的不对的缘故，再改进

var playcrab_fb = {
	
	//call in page
	set_page_ok:function(){
	         Log.info('playcrab_fb.set_page_ok '+ playcrab_fb.flash_ok)
			 playcrab_fb.page_ok=true;
			 if(playcrab_fb.flash_ok==1){
			    var flashapp = document.getElementById("flash_run_id");
			    flashapp.onfb_ok();
			    Log.info(' flashapp.onfb_ok() called '+ playcrab_fb.flash_ok)
			    setTimeout("playcrab_fb.set_page_ok()",1000);
			 }else if(playcrab_fb.flash_ok==2){
				return;
			 }else{
				 setTimeout("playcrab_fb.set_page_ok()",1000);
			 }
			
	},	
	
	//call in flash 
	set_flash_ok:function(r){
	     console.log('set_flash_ok',r)
	    //r: 1 callback ready, 2 onfb_ok ready
	      playcrab_fb.flash_ok = r;
	},
	
	
	
	
	api: function(id,param1,param2,param3){
	    console.info("flash call api() ",param1,param2,param3);
	    var flashapp = document.getElementById("flash_run_id");
	    
		if(param3 == null){
			if(param2 == null){
				FB.api(param1,function(response){
					Log.info("response flash call api() "+param1+","+response);
					flashapp.onfb_api(id,response);
					Log.info("ok response flash call api() "+param1);
				});
				Log.info("flash call api() sended "+id);
			}else{
				FB.api(param1,param2,function(response){
					Log.info("response flash call api() "+id);
					flashapp.onfb_api(id,response);
				});
			}
		}else{
			FB.api(param1,param2,param3,function(response){
				Log.info("response flash call api() "+id);
				flashapp.onfb_api(id,response);
			});
		}
		
	},
	
	getLoginStatus: function(id){
		FB.getLoginStatus(function(response){
				document.getElementById("flash_run_id").onfb_getLoginStatus(id,response);
			});
	},
	
	getSession: function(id){
		FB.getSession(function(response){
				document.getElementById("flash_run_id").onfb_getSession(id,response);
			});
	},
	
	login: function(id,params){
		FB.login(function(response){
				document.getElementById("flash_run_id").onfb_login(id,response);
			},
			params);
	},
	
	ui: function(id,params){
		FB.ui(params,function(response){
			document.getElementById("flash_run_id").onfb_ui(id,response);
		});
	},
	
	dataQuery: function(param1,param2){
		var query = FB.Data.query(param1,param2);
		query.wait(function(rows){
			document.getElementById("flash_run_id").onfb_dataQuery(rows);
		});
	},

	inviteFriends: function(id,param1){
		alert(1);
		inviteFriends(param1,function(response){
			document.getElementById("flash_run_id").onfb_playcrab(id,response);
		});
	},
	
	
	sendGift: function(id,giftid){
		sendGift(giftid,function(response){
			document.getElementById("flash_run_id").onfb_playcrab(id,response);
		});
	},
	
	payForGame: function(id,bdid,pid){
		sendGift(giftid,function(response){
			document.getElementById("flash_run_id").onfb_playcrab(id,response);
		});
	},
	
	visitFriends: function(id,frienddbid,friendpid){
		alert(1);
		visitFriends(friendid,friendpid,function(response){
			document.getElementById("flash_run_id").onfb_playcrab(id,response);
		});
	},
	chooseGift:function(id,data){
		chooseGift(function(res){});
	},
	popUpFeed: function (id,data){
		popUpFeed(data,function(response){
			document.getElementById("flash_run_id").onfb_playcrab(id,response);
		});
	}
};



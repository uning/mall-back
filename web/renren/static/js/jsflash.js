// JavaScript Document
//本文件是同AS库中相互配合使用的facebook接口，使用时请注意一下几点：
//一：将SWFObject的id设为flash_run_id,这样能正常工作。
//二：在flash_run_id所在的html页面中<body>标签后一定要包含标准的对facebook的jsSDK的引用
//这里尝试过使用flash_run_id = document.getElementById("flash_run_id"),
//然后在每个方法中用flash_run_id来代替所有的语句，结果无效，应该是js用的不对的缘故，再改进

var PF = {



	// call in page
	set_page_ok:function(){
	         PF.flashapp=document.getElementById("flash_run_id"),
	         console.info('PF.set_page_ok '+ PF.flash_ok)
			 PF.page_ok=true;
			 if(PF.flash_ok==1){
				PF.flashapp.onpage_ok();
				console.info('PF.flashapp.onpage_ok() called '+ PF.flash_ok)
			    setTimeout("PF.set_page_ok()",1000);
			 }else if(PF.flash_ok==2){
				return;
			 }else{
				 setTimeout("PF.set_page_ok()",1000);
			 }
			
	},	
	
	// call in flash
	set_flash_ok:function(r){
	     console.log('set_flash_ok',r)
	    // r: 1 callback ready, 2 onfb_ok ready
	      PF.flash_ok = r;
	},
	
	
	
	// 获取平台id
	get_pid: function(id){
	   pid = PL.conf('pid')||query_json.xn_sig_user;
	  
	   if(!pid){
		   var get_user = function(r){
			   pid = r.uid;
			   PL.conf('pid',pid);
			   console.log(pid)
			   PF.flashapp.onrr_playcrab(id,pid);
			   
		   }
		   XN.Main.get_sessionState().waitUntilReady(
				   function(){
					   XN.Main.apiClient.users_getLoggedInUser(get_user);
				   });
		   
	   }else{
		   PF.flashapp.onrr_playcrab(id,pid);
	   }
	  
		   
	},
	
	// 获取好友
	get_friends: function(id){
		  XN.Main.get_sessionState().waitUntilReady(
					   function(){
						   XN.Main.apiClient.friends_get(
								   function cb(rr,ex){
									   XN.Main.apiClient.users_getInfo(rr,["uid","name","sex","tinyurl"],
											function cb(r,ex){
											PF.flashapp.onrr_playcrab(id,r);
											});

								   });
	     });
	},
	
	// 获取安装游戏的好友
	get_appfriends: function(id){
		 console.log('get_appfriends')
		  XN.Main.get_sessionState().waitUntilReady(
					   function(){
						   XN.Main.apiClient.friends_getAppFriends([],
								   function cb(r,ex){
									   console.log(r,ex)

								    PF.flashapp.onrr_playcrab(id,r);   

								   });
	     });
	},
	
	// 获取用户信息
	get_userInfo: function(id,uid){
		 console.log("get_userInfo:"+uid)
		  XN.Main.get_sessionState().waitUntilReady(
					   function(){
						   XN.Main.apiClient.users_getInfo([uid],["uid",
				                                                    "name",
				                                                    "sex",
				                                                    "star",
				                                                    "zidou",
				                                                    "vip",
				                                                    "birthday",
				                                                    "email_hash",
				                                                    "tinyurl"						                                             		                                          
				                                                    ],
								   function cb(r,ex){
							         console.log(r,ex)


								    PF.flashapp.onrr_playcrab(id,r);   
								   });
	     });
	},
	
	
	inviteFriends: function(id,param1){
		console.log('inviteFriends');
		inviteFriends(param1,function(response){
			PF.flashapp.onfb_playcrab(id,response);
		});
	},
	
	
	sendGift: function(id,giftid){
		console.log('sendGift');
		sendGift(giftid,function(response){
			PF.flashapp.onfb_playcrab(id,response);
		});
	},
	
	payForGame: function(id,bdid,pid){
		console.log('payForGame');
		payFor(pid,function(response){
			PF.flashapp.onfb_playcrab(id,response);
		});
	},
	
	visitFriends: function(id,frienddbid,friendpid){
		console.log('visitFriends');
		visitFriends(friendid,friendpid,function(response){
			PF.flashapp.onfb_playcrab(id,response);
		});
	},
	
	chooseGift:function(id,data){
		console.log('chooseGift');
		chooseGift(function(res){});
	},
	
	popUpFeed: function (id,data){
		console.log('popUpFeed');
		popUpFeed(data,function(response){
			PF.flashapp.onfb_playcrab(id,response);
		});
	},
	switchToCinema: function(id,data){
		openCinema(data,function(response){
			PF.flashapp.onfb_playcrab(id,response);
		});
	}
};



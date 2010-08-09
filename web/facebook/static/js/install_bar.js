//bar js

var IBar = {
	stat:function (op,phase)
	{
	  if(gPageTracker){
		  gPageTracker._trackEvent('IBar', op, phase);
	  }
	},
	save_bar : function(op) {
		console.log('save_bar', op)
		$.post("ajax/op_bar.php?op=" + op, query_json, Log.info
				.bind('save_' + op), 'json');
	},
	bookmark : function(step) {
		IBar.stat('bookmark','try');
		FB.ui( {
			method : 'bookmark.add'
		}, function(res) {
			console.dir(res);
			if (res.bookmarked == 1) {
				IBar.increase_completion(step);
				IBar.save_bar('bookmark')
				IBar.stat('bookmark','ok');
			}
		});
	},
	
	//
	fan : function(step) {
		IBar.stat('fan','try');
		$("#fan_dialog").show();
		$("#fan_close_link").click(
				function() {
					FB.api( {
						method : 'pages.isFan',
						page_id : PL.conf('app_id')
					}, function(resp) {
						if (resp) {
							IBar.increase_completion(step);
						} else {
							show_header = true;
							console.info(query_json.fb_sig_user
									+ " don't like the Application.");
						}
						if (show_header) {
							console.info('show header');
							$("#installBarContainer").slideDown("fast");
						} else {
							console.info('show header false');
						}

					});
					$("#fan_dialog").hide();
					IBar.increase_completion(step);
					IBar.save_bar('fan')
					IBar.stat('fan','ok');
				});
	},
	
	publish_stream : function(step){
		IBar.get_perm(step, 'publish_stream')
	},
	email : function(step) {
		IBar.get_perm(step, 'email')
	},
	get_perm : function(step, perm) {
		IBar.stat(perm,'try');
		var cb = function(response) {
			if (response.session) {
				if (response.perms.indexOf(perm) > -1) {
					IBar.increase_completion(step);
					IBar.save_bar(perm)
					IBar.stat(perm,'ok');
				}
			}
		};
		FB.login(cb, {
			perms : perm
		});
	},

	increase_completion : function(step) {
		var button = $("#step_button_" + step);
		var img = button.find('a');

		if (img != null) {
			button.empty();
			button.append(img.html());
		}

		if (button.hasClass("button")) {
			var bar = $("#bar_completeness");
			var steps = bar.attr("steps");
			if (steps < 5) {
				steps++;
				bar.attr("steps", steps);
				bar.attr("className", "bar_" + steps);
			}
		}
		button.attr("className", "progress" + step + "_done");
	},

	
	

	//
	idpre : 'step_button_',
	steps : [ {}, {
		name : 'publish_stream',
		step : '2',
		click : function() {
			IBar.publish_stream(2, 'publish_stream')
			return false;
		}
	}, {
		name : 'bookmark',
		step : '3',
		click : function() {
			IBar.bookmark(3)
			return false;
		}
	}, {
		name : 'fan',
		step : '4',
		click : function() {
		IBar.fan(4)
			return false;
		}
	}, {
		name : 'email',
		step : '5',
		click : function() {
			IBar.email(2, 'email')
			return false;
		}
	} ],

	bind : function() {
		// *
		$.each(IBar.steps, function(i, s) {
			eid = '#' + IBar.idpre + s.step + " a";
			$(eid).bind('click', s.click);

		});

	},
	show : function() {
		//return;
		var show_header = false;
		var total_calls = 0;

		// require user to login
		var qstr = "SELECT email, bookmarked, publish_stream, offline_access FROM permissions WHERE uid = "
				+ PL.conf('uid');
		var query = FB.Data.query(qstr);

		query.wait(function(result) {
			if (result != null) {

				if (result[0].publish_stream == 1) {
					IBar.increase_completion(2);

				} else {
					show_header = true;
				}
				if (result[0].bookmarked == 1) {
					IBar.increase_completion(3);
				} else {
					show_header = true;
				}

				if (result[0].email == 1) {
					IBar.increase_completion(5);
				} else {
					show_header = true;
				}

				if (result[0].publish_stream == 0)
					show_header = true;
				if (show_header) {
					console.info('show header');
					$("#installBarContainer").slideDown("fast");
				} else {
					console.info('show header false');
				}
			}
		});
		FB.api( {
			method : 'pages.isFan',
			page_id : PL.conf('app_id')
		}, function(resp) {
			if (resp) {
				IBar.increase_completion(4);
			} else {
				show_header = true;
				console.info(query_json.fb_sig_user
						+ " don't like the Application.");
			}
			if (show_header) {
				console.info('show header');
				$("#installBarContainer").slideDown("fast");
			} else {
				console.info('show header false');
			}

		});
	}
};

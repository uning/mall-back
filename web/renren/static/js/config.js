//load all js,every time downloaded
var $config = {
	//debug:1,
	jspre : [ '',
			'/work/mall/backend/web/renren/static/js/' ],
	api_key:"a32cb73bea154d2c9d40703b66dc9142",
	app_id: "110107",
	secret:'023a6201a9b04955b1af79b1e9037c16',
    canvas_url: "http://apps.renren.com/livemall/",
	reciever_url:'/work/mall/backend/web/renren/xd.html',
	jsfiles : [ 
	        [ 'fb_jsflash.js', 1 ],
	        [ 'delegator.js', 1 ],
			[ 'jquery.boxy.js', 1 ],
			[ 'jsDump-1.0.0.js', 1 ], 
			[ 'log.js', 1 ],
			[ 'install_bar.js', 1 ],
			[ 'jquery-1.4.2.min.js', 1 ],
			[ 'pageUtil.js',1],
			[ 'loadSwf.js',1]
			],
	stat_urlpre:'http://stat.playcrab.com/i.htm',
	stat_version:0.1,
	stat_key:'k',
	error_urlpre:'http://stat.playcrab.com/e.htm'
}

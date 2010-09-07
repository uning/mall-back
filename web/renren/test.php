<script>
function test(ok){
    window.alert('here in feed callback!!, return ' + ok);
}
var publish = {
		template_bundle_id: 1,
		template_data: {images:[
                    {src:"http://fmn042.xnimg.cn/fmn042/20090806/0905/p_large_oQSJ_2633m016062.jpg", 
                     href:"http://dev.renren.com/developers/portal.do"}
                      ]
                      ,feedtype:'ssseddsddd'
                      ,content:'sssssssssss'  
                      ,xnuid:'234567'
                      ,action: 'http://dev.renren.com/developers/portal.do'
                      },
		body_general: '',
		callback: test,
		user_message_prompt: "有啥想法没？^o^",
		user_message: "讲两句吧.."
	};

</script>
<a href="#" onclick="XNML.showFeedDialog(publish)">显示新鲜事</a>
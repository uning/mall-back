<script>
function test(ok){
    window.alert('here in feed callback!!, return ' + ok);
}

var feedSettings
 = {"template_id":"1",
"template_data":{"images":
  [{"src":"http://fmn042.xnimg.cn/fmn042/20090806/0905/p_large_oQSJ_2633m016062.jpg",
  "href":"http://dev.renren.com/developers/portal.do"}],
  "title":"ttt",
  "content":"支持校内，情系人人！",
  "action":"这里是的值",
  "rruid":"这里是的值"
},
"body_general":"这里用来显示 body_general",
"callback": test,
"user_message_prompt": "这里用来显示user_message_prompt"
};
</script>
<a href="#" onclick="XNML.showFeedDialog(feedSettings)">显示新鲜事</a>
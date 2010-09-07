
<form onSubmit="proxy('9999','http://apps.renren.com/livemall/?f=bookmark&origin=103'); return false;">	
	<input type="submit" value="哈哈哈哈" name="publish" id="publish" class="inputsubmit"/>
</form>

<script type="text/javascript">
		function proxy(appId, callback){
		var url = '/xnml/promptFeed.do';
		var pars = 'app_id=' + appId + '&callback=' + callback;
		var myAjax = new Ajax.Request(url, {
			method: 'post',
			parameters: pars,
			onComplete: function (r) {
				var json = eval('(' + r.responseText + ')');
				if(json.errorMsg.length != 0){
					XN.DO.showError(json.errorMsg, "发生错误");
				}
				else{
					showDialog(json, appId);
				}
			}
		});
	}
</script>


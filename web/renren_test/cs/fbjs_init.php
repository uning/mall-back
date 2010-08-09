<script type="text/javascript" src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
<script>
var api_key = "<?php echo FB::$api_key;?>";
var app_id = "<?php echo FB::$app_id;?>";
var canvas_url = "<?php echo FB::$canvas_url;?>";
var before_fbinit = before_fbinit || function(){};
var after_fbinit  = after_fbinit  || function(){};
<script type="text/javascript">
XN_RequireFeatures(["Connect"], function()
{
  XN.Main.init("<?php echo FB::$api_key;?>","<?php echo FB::$reciever_url;?>");
});
</script>




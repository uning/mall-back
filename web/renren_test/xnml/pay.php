<?php
    $myself=basename(__FILE__);
    include "./header.php";
    $platform_id = "renren".$renren->user;
    $session_key = $renren->session_key;
    $user_id     = AutoIncIdGenerator::genid($platform_id);
    $db          = ServerConfig::getdb_by_userid($user_id);
	$sql_up = "select * from user_profiles where id=$user_id";
	$up = $db->fetchRow($sql_up);
	$sql_ua = "select * from user_accounts where id=$user_id";
	$ua = $db->fetchRow($sql_ua);
?>
	<script type="text/javascript">
	function IsInteger(sText)
	{
		var validChars = "0123456789";
		var ch;
		for (i = 0; i < sText.length; i++)
		{
			ch = sText.charAt(i);
			if (validChars.indexOf(ch) == -1)
			{
				return false;
			}
		}
		return true;
	}

	function freeAmount(){
		var quantity = document.getElementById('quantity');
		var q = quantity.getValue();
		return q;
	}

	function buyPoints()
	{
		var order_number = document.getElementById('order_number');
		var token = document.getElementById('token');
		var redirect_url = document.getElementById('redirect_url');
		var form = document.getElementById('purchase_form');

		var choose_type=0;
		var quantity = 0;
		for(var i=1;i<13;i++){
			var ch = document.getElementById("poker_radio_"+i);
			if(typeof(ch)!="undefined"){
				if(ch.getChecked()){
					choose_type = i;
					quantity = ch.getValue();
					console.log("type = "+i);
					console.log("quantity = "+ch.getValue());
					break;

				}
			}
		}
		if(choose_type==0){
			new Dialog(Dialog.DIALOG_ALERT, {message: '请选择充值类型', title: '输入核实', type: 'error'});
			return false;
		}


		console.log('quantity'+quantity)
			var params = {
				type: Ajax.JSON,
					amount: quantity,		
					order_type:choose_type
			};

		var ajax = new Ajax(Ajax.JSON);

		ajax.ondone = function(data) {
			console.log('ondone');
			console.log(data.token);
			if(data.token){
				new Dialog(Dialog.DIALOG_ALERT, {message: '验证成功，点确定进入支付页面。', title: '提交订单', callBack:function(){
					order_number.setValue(data.order_number);
					token.setValue(data.token);
					redirect_url.setValue('<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name."/pay_history.php"; ?>'+'?order_id='+ data.order_number);
					console.log(form);
					form.submit();
				}
				}
			);
			}
			else
			{
				var message_box = new Dialog(Dialog.DIALOG_ALERT,{message:data.message,title: '提示'});
			}

			return false;
		}

		ajax.onerror = function(errobj) {
			console.log('error');
			console.log(errobj);
			new Dialog(Dialog.DIALOG_ALERT, {message: errobj.error_message, title: '验证失败', type: 'error'});
			return false;
		}
		ajax.post('<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name."/pay_new_order.php";?>',params, true);
	}
  </script>

<div id='is_install'></div>		
<div class="poker_game">
	<div class="poker_cz_banner">
    	<div class="renren_user_photo"><img src="<?php echo $up['hIcon']; ?>" alt="<?php echo $up['name']; ?>" class="boxes" ></div>
    	<p class="poker_ad_text">邀请一位朋友赠送1000游戏币啦，赶快加入吧！</p>
        <p class="poker_game_gold"><?php echo $ua['money']; ?></p>
        <p class="poker_gold"><?php echo $ua['gem']; ?></p>
   	</div> 
    
<div class="poker_main">
       <div class="poker_main_t">
	 <img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_jinbicz.jpg" width="134" height="32" />
     <div class="back_game"><a href="<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name;?>/index.php"><img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_backgame.jpg" width="109" height="28" /></a></div>
     </div>
	<div class="poker_pay"> 
      	<div class="poker_pay_top"><a <?php if($myself=='pay.php') echo "class='select'";?> href="<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name;?>/pay.php">游戏充值</a> <a <?php if($myself=='pay_history.php') echo "class='select'";?> href="<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name;?>/pay_history.php">充值记录</a></div>
        <div class="poker_pay_type">
       	  <h1>选择您要充值的类型：</h1>
          <div class="poker_pay_yxb">
          	<img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_yxb_cz.jpg" width="160" height="43" />
  <ul>
            <li><label><input id="poker_radio_1" type="radio" value="10" name="unquie"/><span>10人人豆充值10万游戏币</span></label></li>
            <li><label><input id="poker_radio_2" type="radio" value="20" name="unquie"/><span>20人人豆充值20万游戏币(赠送0.5万)</span></label></li>
            <li><label><input id="poker_radio_3" type="radio" value="50" name="unquie"/><span>50人人豆充值50万游戏币(赠送1.5万)</span></label></li>
            <li><label><input id="poker_radio_4" type="radio" value="100" name="unquie"/><span>100人人豆充值100万游戏币(赠送3.5万)</span></label></li>
            <li><label><input id="poker_radio_5" type="radio" value="200" name="unquie"/><span>200人人豆充值200万游戏币(赠送8万)</span></label></li>
            <li><label><input id="poker_radio_6" type="radio" value="500" name="unquie"/><span>500人人豆充值500万游戏币(赠送15万)</span></label></li>
            </ul>
            </div>
            
          <div class="poker_pay_gold">
          <img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_jb_cz.jpg" width="160" height="43" />
<ul>
            <li><label><input id="poker_radio_7" type="radio" value="10" name="unquie"/><span>10人人豆充值1000金币</span></label></li>
            <li><label><input id="poker_radio_8" type="radio" value="20" name="unquie"/><span>20人人豆充值2000金币(赠送50)</span></label></li>
            <li><label><input id="poker_radio_9" type="radio" value="50" name="unquie"/><span>50人人豆充值5000金币(赠送150)</span></label></li>
            <li><label><input id="poker_radio_10" type="radio" value="100" name="unquie"/><span>100人人豆充值10000金币(赠送350)</span></label></li>
			<li><label><input id="poker_radio_11" type="radio" value="200" name="unquie"/><span>200人人豆充值20000金币(赠送800)</span></label></li>
            <li><label><input id="poker_radio_12" type="radio" value="500" name="unquie"/><span>500人人豆充值50000金币(赠送1500)</span></label></li>
            </ul>
            </div>
        </div>  
        
        <div class="poker_pay_btn">
        <button onclick="buyPoints()" type="submit"><img src="<?php echo RenrenConfig::$resource_url; ?>img/poker_pay_ok.jpg" width="108" height="45" border="0" /></button>
        </div>  
	</div>
      <div class="poker_main_b">  </div>
    </div>
                     <form action="http://app.renren.com/pay/web4test/submitOrder" method="post" name="purchase_form" id="purchase_form">
					 <input type="hidden" value="<?php echo $renren->app_id; ?>" name="app_id" id="app_id"/>
					<input type="hidden" value="" name="order_number" id="order_number"/>
					<input type="hidden" value="" name="token" id="token"/>
					<input type="hidden" value="" name="redirect_url" id="redirect_url"/>

					</form>
</div>
 <xn:else>
<script>
var auth = false;
function authOK()
{
	auth = true;
	document.setLocation("<?php echo RenrenConfig::$canvas_host.RenrenConfig::$canvas_name;?>?"+Math.random() ) ;
}
function authKO()
{
	auth = false;
	document.setLocation("http://app.renren.com/app/apps/list?origin=119") ;
}
var is_install=document.getElementById('is_install');
if(!Session.isApplicationAdded() || is_install == null ){
	Session.requireLogin(authOK,authKO);
}
</script>
 </xn:else>
 </xn:if-is-app-user>

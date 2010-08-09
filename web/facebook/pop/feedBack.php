<html>
<head>
</head>
<body>
<?php

require_once '../config.php';

echo 'jklsajksadjkdkkdjd';
print_r($_REQUEST);
$key = $_GET['key'];
$tt = TT::TTWeb();
$value = $tt->get($key);
$type = $value['type'];

$type = 3;
?>
<?php if($type==3&&$value['count']>0):?>
<div class="padding_content center">
			<div class="main_giftConfirm_cont">
									<h3>You just accepted this Pine Tree</h3>
			<div class="gift_box_holder">
				<div class="gift_box_cont">
					<div class="giftConfirm_img"><img src="http://assets.frontierville.zynga.com/production/R.0.7.005.18240/assets/assets/trees/babypine_icon.png"></div>
					<div class="giftConfirm_name"><span>Pine Tree</span></div>
				</div>
				<div class="gift_from"><h3>From</h3></div>
				<div class="from_box_cont">
										<div class="giftFrom_img"><img src="http://assets.frontierville.zynga.com/production/R.0.7.005.18240/assets/images/FV_Main_FriendBar_No_Profile_Img.png"></div>
					<div class="giftFrom_name"><span>雨农</span></div>
				</div>
			</div>
		</div>
		<div class="morePending_cont">
			<div class="text">Do you have more pending gifts to accept?</div>
			<div class="morePending_bttn">
				<form id="app201278444497_form_4c5623b1f226f120a39b7" method="post" action="http://fb-0.frontier.zynga.com/giftaccept.php?zySnid=1&amp;zySnuid=100000891768718&amp;zy_user=100000891768718&amp;zy_ts=&amp;zy_session=&amp;zySig=65a6120fa7471366979e56b2184e0db2"><input type="hidden" value="zh_CN" name="fb_sig_locale"><input type="hidden" value="1" name="fb_sig_in_new_facebook"><input type="hidden" value="1280713649.9901" name="fb_sig_time"><input type="hidden" value="1" name="fb_sig_added"><input type="hidden" value="1269526478" name="fb_sig_profile_update_time"><input type="hidden" value="1280718000" name="fb_sig_expires"><input type="hidden" value="100000891768718" name="fb_sig_user"><input type="hidden" value="2.4ZtlJtk_UeW4TAl7DZKPRw__.3600.1280718000-100000891768718" name="fb_sig_session_key"><input type="hidden" value="us" name="fb_sig_country"><input type="hidden" value="14eac7bb6f4b3019a69b01a392b72699" name="fb_sig_api_key"><input type="hidden" value="201278444497" name="fb_sig_app_id"><input type="hidden" value="ad2ee7985c35546997648c5d5401ba38" name="fb_sig">
					<span>
						<input type="hidden" value="yes" name="reqType">
						<input type="submit" value="Yes" class="inputsubmit">
					</span>
				</form>
				<form id="app201278444497_form_4c5623b1f2b335e4880a6" method="post" action="http://fb-0.frontier.zynga.com/giftaccept.php?zySnid=1&amp;zySnuid=100000891768718&amp;zy_user=100000891768718&amp;zy_ts=&amp;zy_session=&amp;zySig=65a6120fa7471366979e56b2184e0db2"><input type="hidden" value="zh_CN" name="fb_sig_locale"><input type="hidden" value="1" name="fb_sig_in_new_facebook"><input type="hidden" value="1280713649.9925" name="fb_sig_time"><input type="hidden" value="1" name="fb_sig_added"><input type="hidden" value="1269526478" name="fb_sig_profile_update_time"><input type="hidden" value="1280718000" name="fb_sig_expires"><input type="hidden" value="100000891768718" name="fb_sig_user"><input type="hidden" value="2.4ZtlJtk_UeW4TAl7DZKPRw__.3600.1280718000-100000891768718" name="fb_sig_session_key"><input type="hidden" value="us" name="fb_sig_country"><input type="hidden" value="14eac7bb6f4b3019a69b01a392b72699" name="fb_sig_api_key"><input type="hidden" value="201278444497" name="fb_sig_app_id"><input type="hidden" value="7cfe68978b9ef99f276a9147fc00cd97" name="fb_sig">
					<input type="hidden" value="no" name="reqType">
					<input type="submit" value="No" class="inputsubmit">
				</form>
			</div>
			<div class="text"><span>Please remember to accept each gift right away.</span></div>
		</div>
	</div>
<?php elseif($type==2&&$value['count']>0):
		{
			
		}
 	  elseif($type==1&&$value['count']>0):
 	   {
 	  		
 	   }
 	  endif;?>
</body>
</html>

print_r($value);
?>


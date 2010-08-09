<?php
require_once '../config.php';
if($_POST['content']){
	include '../mail.php';	
	$mail->Subject = 'renren:uid='.$_POST['uid'];
	$body.="<pre>\n";
	$body .="\n{$_POST['content']}\n";
	foreach($_POST as $k=>$v){
		if($k!='content')
			$body .="$k:$v\n";
	}
	$body.="</pre>\n";
	$mail->Body=$body;
	echo 'OK';
	$mail->send();
	return;
}
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>意见反馈</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<script src="<?php echo RenrenConfig::$resource_urlp;?>js/loader.js"></script>
  
</head>
<body>
<p class="message">您留下的每个字都将被用来改善我们的游戏&成为我们的动力:</p>

<div id="feedback">

<form method="post" id='fform'>
    <p>联系方式：
	<input type="text" class="text" = name="contact" id="idcontact" inlinetip="留下您的联系方式，我们需要互动" style="width:390px;*width:395px;" >  </p>
<p></p>
    
    <p>反馈内容：<br/>
      <textarea name="content" id="idcontent" inlinetip="建议orBUG" cols="45" rows="5"></textarea>
    </p>
    <p><button><strong>提交</strong></button></p>
</form>

</div>


    <p><button><strong></strong></button></p>
</body>
</html>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jquery/form.js"></script>
<script type="text/javascript" >
var userfs=["uid",
	"name",
	"sex",
	"star",
	"zidou",
	"vip",
	"birthday",
	"email_hash",
	"tinyurl"
	];
var fform = $('#fform')
var config = {
	useparent:true,
		fb:1,//init fb?
		after_fbinit: function(){
			console.log('feedback after_fbinit');
			var getpid = function(r){
				pid = r.uid;
				PL.conf('pid',pid);
				console.log(pid);
				XN.Main.apiClient.users_getInfo([ PL.conf('pid')],userfs
					,function(r){
						console.log(r);
						$.each(r[0],function(n,v){
							console.log(n,v);
							fform.append('<input type="hidden" name="'+n+'" value="'+v+'" />');
						 });
					}
				);
			}
			XN.Main.get_sessionState().waitUntilReady(
				function(){
					XN.Main.apiClient.users_getLoggedInUser(getpid);
				});

		}
}

PL.init('../static/js/config.js',config);

function form_triger(p_jqsel)
{
	  if(arguments.length   ==   0)  
                  p_jasel   =   "";  
        else  
                  p_jasel   =   arguments[0];   
	try{
	 if(p_jasel == "" ){
	 inputsWithTip = $('input[inlinetip],textarea[inlinetip]')
		}
	else{
		inputsWithTip = $(p_jasel).children('input[inlinetip],textarea[inlinetip]')
    }			
		inputsWithTip.each(function(i,jqinput){
				var input = $(jqinput);
				if( input.attr('value') == ''){
				  input.addClass('with-inlinetip');
				  input.attr('value', input.attr('inlinetip'));
				}
				
				
				input.bind('focus',function(){
					if( input.attr('value') == input.attr('inlinetip')){
					input.removeClass('with-inlinetip');
					input.attr('value', '');
					}
					});
				input.bind('blur',function(){
					if( input.attr('value') == '' ){
					input.addClass('with-inlinetip');
					input.attr('value', input.attr('inlinetip'));
					}
					});
              
				input.parents('form').bind('reset',function(){
					window.setTimeout(function(){
						input.trigger('blur');
						},1);
					});
		});
		
		//*
	$('form').bind('submit', function(){
			$(this).find('input[inlinetip]').each(function(i, input){
				var input = $(input);
				if(input.val() == input.attr('inlinetip')){
				input.val('');
				}
				});
			});
		//	*/
		
	}catch(e)
	{
		myalert("dfdf:"+e);
	}
}
$(document).ready(function() {
	form_triger();
	$('#fform').bind('submit', function() {
		$('#feedback').text('已经收到你的反馈，再次感谢:)你可以点击上方游戏按钮，继续游戏');
		$(this).ajaxSubmit({
			target: '#output',
				success: function(responseText, code){			
					$('#feedback').text('已经收到你的反馈，再次感谢:)你可以点击上方游戏按钮，继续游戏');
					alert(responseText);
				}	
		});
		return false; // <-- important!
	});
});

</script>

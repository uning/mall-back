<?php
require_once '../../config.php';
?>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fb="http://www.facebook.com/2008/fbml">
<meta name="layout" content="facebook_xfbml"></meta>
<script type="text/javascript">
	function show(){
		var div = document.getElementById("invite");
		
		if(div.style.display=='block')
		div.style.display='none';
		else{
			div.style.display='block';
		
			document.getElementById("in").src="invite/invite.php"
		}
		
		}
</script>
<body>
	<?php
	include FB_CURR . '/cs/fbjs_init.php';
	?>
	<table cellspacing="0" cellpadding="0" width="754"  border="1" >
	<tr valign="middle">

		<td width="10%">
		
		<fb:profile-pic linked="true" size="square"
			uid="100000891768718"/></td>
		<td width="20%"><fb:name
			firstnameonly="true" ifcantsee="Facebook User" linked="false"
			possessive="true" uid="100000891768718" />'s Mall
		</td>
		<td width="70%" align="left">
		<fb:like
		href="http://supermall.playcrab.com/inif/pop/liked.php?uid=<?php
		echo $_REQUEST ["uid"];
		?>" show_faces="false"></fb:like>
	</td>
	</tr>
	</table>
	<div style="display:none;height: 70%;vertical-align: middle;z-index: 2" id="invite">
		<iframe src="" scrolling="no" width="700" align="middle" id="in">
		</iframe>
		</div>
	<div style="display:block;">
	<input type="button" onclick="show();" value="jfjfj">
	</div>
</body>
</html>

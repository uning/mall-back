//bar js

var IBar = {
	stat:function (op,phase)
	{
	 // if(_gaq){
	//	  _gaq._trackEvent('IBar', op, phase);
	 // }
	},
	save_bar : function(op) {
		console.log('save_bar', op)
		$.post("../ajax/op_bar.php?op=" + op+"&xn_sig_user="+pid+"&xn_sig_session_key="+session_key,  function(data){console.debug("xxxx",data)}, 'json');
	},
	update_bar: function() {
	    var w;
	    switch (installStep) {
	        case 1: 
	            w = 241;
	            break;
	        case 2:
	            w = 519;
	            break;
	        default:
	            w = 755;
	            break;
	    } 
	    document.getElementById("progressBar").style.width = w + "px";
	    document.getElementById("progressBar").className = "stepcount_" + installStep;
	
	    if (installStep >= 3) {
	    	document.getElementById("installBar").style.display = "none";
	    }
	    else {
	        document.getElementById("installBar").style.display = "block";
	    }
    }, 
	init_bar : function() {
        if (installStep == 2) {
            document.getElementById("iBarStepFan").className = "iBarStep done";
			IBar.update_bar();
        }
    },
	
	permCallBack: function(permission) {
		if (permission) {
	        installStep++;
	        document.getElementById("iBarStepEmail").className = "iBarStep done";
	    }
        IBar.update_bar();        
		IBar.save_bar('email');
	},
	//
	becomeFan : function() {
 		IBar.stat('fan','try'); 
		window.open("http://page.renren.com/pa/bf?pid=699110107", "_blank"); 
	    installStep++;
	    document.getElementById("iBarStepFan").className = "iBarStep done"; 
	    IBar.update_bar();
		IBar.save_bar('fan')
	    IBar.stat('fan','ok');     
	}
};

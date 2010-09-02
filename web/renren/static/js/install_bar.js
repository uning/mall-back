//bar js

var IBar = {
	stat:function (op,phase)
	{
	  if(_gaq){
		  _gaq._trackEvent('IBar', op, phase);
	  }
	},
	save_bar : function(op) {
		console.log('save_bar', op)
		$.post("ajax/op_bar.php?op=" + op,  function(data){console.debug("xxxx",data}, 'json');
	},
	update_bar: function() {
	    var w;
	    switch (stepCnt) {
	        case 1: 
	            w = 229;
	            break;
	        case 2:
	            w = 509;
	            break;
	        default:
	            w = 747;
	            break;
	    } 
	    document.getElementById("progressBar").style.width = w + "px";
	    document.getElementById("progressBar").className = "stepcount_" + stepCnt;
	
	    if (stepCnt >= 3) {
	    	document.getElementById("installBar").style.display = "none";
	    }
	    else {
	        document.getElementById("installBar").style.display = "block";
	    }
    },
	init_bar : function() {
        if (stepCnt == 2) {
            document.getElementById("pBarStepFan").className = "pBarStep done";
        }
    },
	
	permCallBack: function(permission) {
		if (permission) {
	        stepCnt++;
	        document.getElementById("pBarStepEmail").className = "pBarStep done";
	    }
        IBar.update_bar();        
		IBar.save_bar('email');
	},
	//
	becomeFan : function() {
 		IBar.stat('fan','try'); 
		window.open("http://page.renren.com/pa/bf?pid=699110107", "_blank"); 
	    stepCnt++;
	    document.getElementById("pBarStepFan").className = "pBarStep done"; 
	    IBar.update_bar();
		IBar.save_bar('fan')
	    IBar.stat('fan','ok');     
	}
};

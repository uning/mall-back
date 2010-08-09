


function urlencode( str ) {
    var hash_map = {}, unicodeStr='', hexEscStr='';
    var ret = (str+'').toString();

    var replacer = function(search, replace, str) {
        var tmp_arr = [];
        tmp_arr = str.split(search);
        return tmp_arr.join(replace);
    };

    // The hash_map is identical to the one in urldecode.
    hash_map["'"]   = '%27';
    hash_map['(']   = '%28';
    hash_map[')']   = '%29';
    hash_map['*']   = '%2A';
    hash_map['~']   = '%7E';
    hash_map['!']   = '%21';
    hash_map['%20'] = '+';
    hash_map['\u00DC'] = '%DC';
    hash_map['\u00FC'] = '%FC';
    hash_map['\u00C4'] = '%D4';
    hash_map['\u00E4'] = '%E4';
    hash_map['\u00D6'] = '%D6';
    hash_map['\u00F6'] = '%F6';
    hash_map['\u00DF'] = '%DF';
    hash_map['\u20AC'] = '%80';
    hash_map['\u0081'] = '%81';
    hash_map['\u201A'] = '%82';
    hash_map['\u0192'] = '%83';
    hash_map['\u201E'] = '%84';
    hash_map['\u2026'] = '%85';
    hash_map['\u2020'] = '%86';
    hash_map['\u2021'] = '%87';
    hash_map['\u02C6'] = '%88';
    hash_map['\u2030'] = '%89';
    hash_map['\u0160'] = '%8A';
    hash_map['\u2039'] = '%8B';
    hash_map['\u0152'] = '%8C';
    hash_map['\u008D'] = '%8D';
    hash_map['\u017D'] = '%8E';
    hash_map['\u008F'] = '%8F';
    hash_map['\u0090'] = '%90';
    hash_map['\u2018'] = '%91';
    hash_map['\u2019'] = '%92';
    hash_map['\u201C'] = '%93';
    hash_map['\u201D'] = '%94';
    hash_map['\u2022'] = '%95';
    hash_map['\u2013'] = '%96';
    hash_map['\u2014'] = '%97';
    hash_map['\u02DC'] = '%98';
    hash_map['\u2122'] = '%99';
    hash_map['\u0161'] = '%9A';
    hash_map['\u203A'] = '%9B';
    hash_map['\u0153'] = '%9C';
    hash_map['\u009D'] = '%9D';
    hash_map['\u017E'] = '%9E';
    hash_map['\u0178'] = '%9F';

    // Begin with encodeURIComponent, which most resembles PHP's encoding functions
    ret = encodeURIComponent(ret);

    for (unicodeStr in hash_map) {
        hexEscStr = hash_map[unicodeStr];
        ret = replacer(unicodeStr, hexEscStr, ret); // Custom replace. No regexing
    }

    // Uppercase for full PHP compatibility
    return ret.replace(/(\%([a-z0-9]{2}))/g, function(full, m1, m2) {
        return "%"+m2.toUpperCase();
    });
}

function http_build_query( formdata, numeric_prefix, arg_separator ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Legaev Andrey
    // +   improved by: Michael White (http://getsprink.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: stag019
    // -    depends on: urlencode
    // *     example 1: http_build_query({foo: 'bar', php: 'hypertext processor', baz: 'boom', cow: 'milk'}, '', '&amp;');
    // *     returns 1: 'foo=bar&amp;php=hypertext+processor&amp;baz=boom&amp;cow=milk'
    // *     example 2: http_build_query({'php': 'hypertext processor', 0: 'foo', 1: 'bar', 2: 'baz', 3: 'boom', 'cow': 'milk'}, 'myvar_');
    // *     returns 2: 'php=hypertext+processor&myvar_0=foo&myvar_1=bar&myvar_2=baz&myvar_3=boom&cow=milk'

    var value, key, tmp = [];

    var _http_build_query_helper = function (key, val, arg_separator) {
        var k, tmp = [];
        if (val === true) {
            val = "1";
        } else if (val === false) {
            val = "0";
        }
        if (typeof(val) == "array" || typeof(val) == "object") {
            for (k in val) {
                if(val[k] !== null) {
                    tmp.push(_http_build_query_helper(key + "[" + k + "]", val[k], arg_separator));
                }
            }
            return tmp.join(arg_separator);
        } else if(typeof(val) != "function") {
	  if(val != undefined)
	    return urlencode(key) + "=" + urlencode(val);
	  else
	    return undefined;
        }
    };

    if (!arg_separator) {
        arg_separator = "&";
    }
    for (key in formdata) {
        value = formdata[key];
        if (numeric_prefix && !isNaN(key)) {
            key = String(numeric_prefix) + key;
        }
	var key_val_str = _http_build_query_helper(key, value, arg_separator);
	if(key_val_str != undefined)
	  tmp.push(key_val_str);
    }

    return tmp.join(arg_separator);
}


PLStat = {
	url_pre: PL.conf('stat_urlpre'),
	version: PL.conf('stat_version'),
	key: PL.conf('stat_key'),
	uuid:function() {
		  // Private array of chars to use
		  var CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
		  return function (len, radix) {
		    var chars = CHARS, uuid = [], rnd = Math.random;
		    radix = radix || chars.length;

		    if (len) {
		      // Compact form
		      for (var i = 0; i < len; i++) uuid[i] = chars[0 | rnd()*radix];
		    } else {
		      // rfc4122, version 4 form
		      var r;

		      // rfc4122 requires these characters
		      uuid[8] = uuid[13] = uuid[18] = uuid[23] = '';
		      uuid[14] = '4';

		      // Fill in random data.  At i==19 set the high bits of clock sequence as
		      // per rfc4122, sec. 4.1.5
		      for (var i = 0; i < 36; i++) {
		        if (!uuid[i]) {
		          r = 0 | rnd()*16;
		          uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r & 0xf];
		        }
		      }
		    }

		    var ret = uuid.join('');
		    return ret.substring(0, 32);
		  }();
		},
  _gen_link : function(channel, data){
    var qs = http_build_query(data);
    var url_path = this.url_pre + "?@="+ PLStat.version + "_" + PLStat.key + "_" + channel + "&" + qs;
    return url_path;
  },
  
  send:function(url){
	  if(PLStat.url_pre==false)
		  return;
	  console.log('send',url)
	  img = document.getElementById('stat_playcrab')
	  if(!img){
		  img = document.createElement('img');
		  img.id = 'stat_playcrab';
	  }
	  img.src = url;
  },
  
  
  //尝试发送
  stat_try : function(_GET)
  {
    var data = { 
	    	 id  : _GET.id,
	    	 channel:'mall', //应用标示，flash程序可以从配置读取
	    	 type:_GET.type,//'link',//
			 u   :PL.conf('pid'), //发送者fbid
			 s  : 'a',
			 w   : 'test', //什么地方触发？
			 c   : 'testva' //内容标示
		}
    _GET.d1 && (data.d1 = _GET.d1);
    _GET.d2 && (data.d1 = _GET.d2);
    _GET.d3 && (data.d2 = _GET.d3);
    PLStat.send(PLStat._gen_link('link', data));
  },
  

  //成功发送
  stat_sended : function(_GET){
	  var data = { 
		    	 id  : _GET['id'],//stream 唯一标识
				 u   : PL.conf('pid'), //发送者fbid
				 t  :  'b',
				 w  : _GET['w'], //什么地方触发？
				 c  : _GET['c'], //内容标示
				 d  : _GET['d'],//其他内容
				 r  :  document.refer
			  };
	  _GET.d1 && (data.d1 = _GET.d1);
	  _GET.d2 && (data.d1 = _GET.d2);
	  _GET.d3 && (data.d2 = _GET.d3);
	  PLStat.send(PLStat._gen_link('link', data));
  } 
};
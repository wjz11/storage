// ================================================================= 
// 标题：lbuil.js
// 代码描述：通用jquery插件
// 创建日期：2015-11-10 11:10:50
// 作者：吕洋波
// 修改记录：吕洋波 2015-11-15
// =================================================================
	/*
	通过js加载js和css文件
	$.include(['js/bootstrap.min.js','css/bootstrap.min.css']);
	$(function() {
	LookBiCms_CreateGalleryPnl();
	});
	*******************************************************************************************
	*/
	$.extend({ 
	includePath: '', 
	include: function(file) { 
	var files = typeof file == "string" ? [file]:file; 
	for (var i = 0; i < files.length; i++) { 
	var name = files[i].replace(/^\s|\s$/g, ""); 
	var att = name.split('.'); 
	var ext = att[att.length - 1].toLowerCase(); 
	var isCSS = ext == "css"; 
	var tag = isCSS ? "link" : "script"; 
	var attr = isCSS ? " type='text/css' rel='stylesheet' " : " language='javascript' type='text/javascript' "; 
	var link = (isCSS ? "href" : "src") + "='" + $.includePath + name + "'"; 
	if ($(tag + "[" + link + "]").length == 0) document.write("<" + tag + attr + link + "></" + tag + ">"); 
	}
	}
	});
	
	
	$.extend({
	/*
	loadFile v1.0
	$.loadFile('js/test1.js',function(){},function(data){});
	$.loadFile(['js/test1.js','js/test2.js'],function(){},function(data){});
	*/
	//file：文件路径 callback：回调函数 error：失败回调
	loadFile: function(file, callback, error) {
			callback = callback ||
			function() {};
			error = error ||
			function() {};
			var htmlDoc = document.getElementsByTagName('head')[0],
				okCounts = 0,
				fileCounts = 0,
				i,loadFilePath=null;
			var files = typeof file == "string" ? [file] : file;
			fileCounts = files.length;
			for (i = 0; i < fileCounts; i++) {
				var includeFile = null,
					att = null,
					ext, hash;
				loadFilePath=files[i];
				hash = $.getHashCode(loadFilePath);
				if (document.getElementById("loadHash_" + hash)) {
					//console.log(loadFilePath + ' ready fired');
					okCounts += 1;
					if (okCounts == fileCounts) {
						callback();
					}
					continue;
				}
				att = loadFilePath.split('.');
				ext = att[att.length - 1].toLowerCase();
				switch (ext) {
				case 'css':
					includeFile = document.createElement('link');
					includeFile.setAttribute('rel', 'stylesheet');
					includeFile.setAttribute('type', 'text/css');
					includeFile.setAttribute('href', loadFilePath);
					break;
				case 'js':
					includeFile = document.createElement('script');
					includeFile.setAttribute('type', 'text/javascript');
					includeFile.setAttribute('src', loadFilePath);
					break;
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'gif':
					includeFile = document.createElement("img");
					includeFile.setAttribute('src', loadFilePath);
					break;
				default:
					error('载入的格式不支持:' + loadFilePath);
					//console.log('载入的格式不支持:' + loadFilePath);
					return false;
				}
				if (typeof includeFile != "undefined") {
					includeFile.setAttribute('id', "loadHash_" + hash);
					htmlDoc.appendChild(includeFile);
					includeFile.onreadystatechange = function() {
						if (includeFile.readyState == 'loaded' || includeFile.readyState == 'complete') {
							//console.log(includeFile.id + ' onreadystatechange fired');
							okCounts += 1;
							if (okCounts == fileCounts) {
								callback();
							}
						}
					}
					includeFile.onload = function() {
						//console.log(includeFile.id + ' onload fired');
						okCounts += 1;
						if (okCounts == fileCounts) {
							callback();
						}
					}
					includeFile.onerror = function() {
						$("#loadhash_" + hash).remove();
						//console.log(loadFilePath + 'load error');
						error(loadFilePath + ' load error');
						return false;
					}
				} else {
					error('文件创建失败');
					return false;
				}
			}
			return true;
		},
		getHashCode: function(str, caseSensitive) {
			if (!caseSensitive) {
				str = str.toLowerCase();
			}
			var hash = 1315423911,
				i, ch;
			for (i = str.length - 1; i >= 0; i--) {
				ch = str.charCodeAt(i);
				hash ^= ((hash << 5) + ch + (hash >> 2));
			}
			return (hash & 0x7FFFFFFF);
		}
	});

	
	/*
	基于Cookie plugin
	$.cookie('the_cookie'); //读取Cookie值
	$.cookie(’the_cookie’, ‘the_value’); //设置cookie的值
	$.cookie(’the_cookie’, ‘the_value’, {expires: 7, path: ‘/’, domain: ‘jquery.com’, secure: true});
	*******************************************************************************************
	*/
	jQuery.cookie = function(name, value, options) {
		if (typeof value != 'undefined') {
			options = options || {};
			if (value === null) {
				value = '';
				options.expires = -1;
			}
			var expires = '';
			if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
				var date;
				if (typeof options.expires == 'number') {
					date = new Date();
					date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
				} else {
					date = options.expires;
				}
				expires = '; expires=' + date.toUTCString();
			}
			var path = options.path ? '; path=' + (options.path) : '';
			var domain = options.domain ? '; domain=' + (options.domain) : '';
			var secure = options.secure ? '; secure' : '';
			document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
		} else {
			var cookieValue = null;
			if (document.cookie && document.cookie != '') {
				var cookies = document.cookie.split(';');
				for (var i = 0; i < cookies.length; i++) {
					var cookie = jQuery.trim(cookies[i]);
					if (cookie.substring(0, name.length + 1) == (name + '=')) {
						cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
						break;
					}
				}
			}
			return cookieValue;
		}
	};	

	/*
	定时器
	$("#close-button").oneTime(1000,function(){});
	$("#close-button").stopTime();
	1. everyTime(时间间隔, [计时器名称], 函式名称, [次数限制], [等待函式程序完成])
	2. oneTime(时间间隔, [计时器名称], 呼叫的函式)
	3. stopTime ([计时器名称], [函式名称])
	*******************************************************************************************
	*/
	jQuery.fn.extend({
	everyTime: function(interval, label, fn, times, belay) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, times, belay);
		});
	},
	oneTime: function(interval, label, fn) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, 1);
		});
	},
	stopTime: function(label, fn) {
		return this.each(function() {
			jQuery.timer.remove(this, label, fn);
		});
	}
	});
	jQuery.extend({
		timer: {
			guid: 1,
			global: {},
			regex: /^([0-9]+)\s*(.*s)?$/,
			powers: {
				'ms': 1,
				'cs': 10,
				'ds': 100,
				's': 1000,
				'das': 10000,
				'hs': 100000,
				'ks': 1000000
			},
			timeParse: function(value) {
				if (value == undefined || value == null)
					return null;
				var result = this.regex.exec(jQuery.trim(value.toString()));
				if (result[2]) {
					var num = parseInt(result[1], 10);
					var mult = this.powers[result[2]] || 1;
					return num * mult;
				} else {
					return value;
				}
			},
			add: function(element, interval, label, fn, times, belay) {
				var counter = 0;
				if (jQuery.isFunction(label)) {
					if (!times) 
						times = fn;
					fn = label;
					label = interval;
				}
				interval = jQuery.timer.timeParse(interval);
				if (typeof interval != 'number' || isNaN(interval) || interval <= 0)
					return;
				if (times && times.constructor != Number) {
					belay = !!times;
					times = 0;
				}
				times = times || 0;
				belay = belay || false;
				if (!element.$timers) 
					element.$timers = {};
				if (!element.$timers[label])
					element.$timers[label] = {};
				fn.$timerID = fn.$timerID || this.guid++;
				var handler = function() {
					if (belay && this.inProgress) 
						return;
					this.inProgress = true;
					if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
						jQuery.timer.remove(element, label, fn);
					this.inProgress = false;
				};
				handler.$timerID = fn.$timerID;
				if (!element.$timers[label][fn.$timerID]) 
					element.$timers[label][fn.$timerID] = window.setInterval(handler,interval);
				if ( !this.global[label] )
					this.global[label] = [];
				this.global[label].push( element );
			},
			remove: function(element, label, fn) {
				var timers = element.$timers, ret;
				if ( timers ) {
					if (!label) {
						for ( label in timers )
							this.remove(element, label, fn);
					} else if ( timers[label] ) {
						if ( fn ) {
							if ( fn.$timerID ) {
								window.clearInterval(timers[label][fn.$timerID]);
								delete timers[label][fn.$timerID];
							}
						} else {
							for ( var fn in timers[label] ) {
								window.clearInterval(timers[label][fn]);
								delete timers[label][fn];
							}
						}
						for ( ret in timers[label] ) break;
						if ( !ret ) {
							ret = null;
							delete timers[label];
						}
					}
					for ( ret in timers ) break;
					if ( !ret ) 
						element.$timers = null;
				}
			}
		}
	});
	if (/msie/.test(navigator.userAgent.toLowerCase()))
		jQuery(window).one("unload", function() {
			var global = jQuery.timer.global;
			for ( var label in global ) {
				var els = global[label], i = els.length;
				while ( --i )
					jQuery.timer.remove(els[i], label);
			}
	});
	

	/*
	图片预加载等比缩放垂直水平居中插件
	$(".main img").LoadImage(true,true,800,600,"images/loading.gif");
 	*******************************************************************************************
	*/
	jQuery.fn.LoadImage=function(scaling,iscenter,width,height,loadpic,errpic){
    return this.each(function(){
        var t=$(this),src=$(this).attr("src"),img=new Image();
		var loadImageId=Math.random();
		t.attr('loadimage-id',loadImageId);
        img.src=src;
        //自动缩放图片
        var autoScaling=function(){
            if(scaling){
                if(img.width>0 && img.height>0){ 
                    if(img.width/img.height>=width/height){ 
                        if(img.width>width){ 
                            t.width(width); 
                            t.height((img.height*width)/img.width); 
                        }else{ 
                            t.width(img.width); 
                            t.height(img.height); 
                        } 
                    } 
                    else{ 
                        if(img.height>height){ 
                            t.height(height); 
                            t.width((img.width*height)/img.height); 
                        }else{ 
                            t.width(img.width); 
                            t.height(img.height); 
                        } 
                    } 
                } 
            }   
        };
		var autoCenter=function(){
			 if(iscenter){
			//让图片居中显示
             var marginLeft=Math.abs((width-t.innerWidth())/2); 
			 var marginTop=Math.abs((height-t.innerHeight())/2); 
			 t.css({'margin-top':marginTop,'margin-left':marginLeft});
			 }
		};
        //处理ff下会自动读取缓存图片
        //if(img.complete){
            //alert("getToCache!");
        //    autoScaling();
        //    return;
        //}
        t.attr("src","");
        t.hide();
		if($('div[loadimage-id="'+t.attr('loadimage-id')+'"]').length<1){
		var loading=$('<div loadimage-id="'+t.attr('loadimage-id')+'" style="width:'+width+'px;height:'+height+'px;background:url('+loadpic+') center no-repeat"></div>');
        t.after(loading);
		}
        $(img).load(function(){
			t.hide();
			autoScaling();
			autoCenter();
            loading.remove();
            t.attr("src",this.src);
            t.fadeIn();
			$(this).remove();
        }).error(function(){
		  if(loading.length>0){loading.css({'background':'#f0f0f0 url('+errpic+') center no-repeat'});}
		  $(this).remove();
		  //loading.remove();		
		});
         
    });
	};
	
	/*
	光标处插入文本
	$('#'+oid).insertContent(str);
 	*******************************************************************************************
	*/
	(function($) {
    $.fn.insertContent = function(myValue, t) {
		var $t = $(this)[0];
		if (document.selection) { //ie
			this.focus();
			var sel = document.selection.createRange();
			sel.text = myValue;
			this.focus();
			sel.moveStart('character', -l);
			var wee = sel.text.length;
			if (arguments.length == 2) {
				var l = $t.value.length;
				sel.moveEnd("character", wee + t);
				t <= 0 ? sel.moveStart("character", wee - 2 * t - myValue.length) : sel.moveStart("character", wee - t - myValue.length);
 
				sel.select();
			}
		} else if ($t.selectionStart || $t.selectionStart == '0') {
			var startPos = $t.selectionStart;
			var endPos = $t.selectionEnd;
			var scrollTop = $t.scrollTop;
			$t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
			this.focus();
			$t.selectionStart = startPos + myValue.length;
			$t.selectionEnd = startPos + myValue.length;
			$t.scrollTop = scrollTop;
			if (arguments.length == 2) {
				$t.setSelectionRange(startPos - t, $t.selectionEnd + t);
				this.focus();
			}
		}
		else {
			this.value += myValue;
			this.focus();
		}       
    };
	})(jQuery);

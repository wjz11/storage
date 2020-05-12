// ================================================================= 
// 标题：function.js
// 代码描述：通用js函数
// 创建日期：2015-11-10 11:10:50
// 作者：吕洋波
// 修改记录：吕洋波 2015-11-15
// =================================================================

var str_split='<{|*|}>',str_subsplit='<{*|*}>',encodestr_split='%3C%7B%7C*%7C%7D%3E',encodestr_subsplit='%3C%7B*%7C*%7D%3E';
var file_split='<{|}>',file_subsplit='<{*}>',encodefile_split='%3C%7B%7C%7D%3E',encodefile_subsplit='%3C%7B*%7D%3E';
function AddPerAnimateEffect(o,ani){
o.addClass(ani+' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){o.removeClass(ani+' animated');});
}
function AddPerHoverEffect(o,ani,o_hvr) {
    o.hover(
        function () {
            o_hvr.addClass('animated ' + ani);
        },
        function () {
			o_hvr.stopTime().oneTime(1000,function(){o_hvr.removeClass('animated ' + ani);});
        });
}
//双引号转换
String.prototype.ToQuote=function(re){return re ? this.replace(/"/g, "&quot;") : this.replace(/&quot;/g, '"') ;}
String.prototype.JsonQuotes=function(re){return re ? this.replace(/"/g, "\\\"") : this.replace(/\\"/g, '"') ;}
String.prototype.Stripcslashes=function(re){return this.replace(/\\"/g, '&quot;') ;}
function Br2Nr(re){return re.replace(/<br>/g, "\n");}
function Nr2Br(re) {
    var i;
    var result = "";
    var c;
    for (i = 0; i < re.length; i++) {
        c = re.substr(i, 1);
        if (c == "\n")
            result = result + "<br>";
        else if (c != "\r")
            result = result + c;
    }
    return result;
}
String.prototype.ReplaceAll=function(str1,str2){var regS = new RegExp(str1,"gi");return this.replace(regS,str2);}


function SysDebugTip(s){layer.msg(s);}
//重复字符串
function RepeatStr(str,num){return new Array(num+1).join(str);}
//字符串连接
function AddStr(str,add,sign){if(str===""||str===undefined){return add;}else{return str+sign+add;}}
//字符串清除
function DelStr(str,del,sign){str=sign+str+sign;del=sign+del+sign;str=str.replace(del,sign);var signLen=sign.length;if(str.substr(0,signLen)==sign){str=str.substring(1,str.length);}if(str.substr(str.length-1,signLen)==sign){str=str.substring(0,str.length-1);}return str;}
//小数及百分比换算
function Division(nm1,nm2,digits){out=(nm2==0)?0:(nm1/nm2);return fmoney(out,digits);}
function Proportion(nm1,nm2,digits,sign){return division(nm1*100,nm2,digits)+sign;}
//货币格式化
function Fmoney(s,n){sign='';if(s<0){s=-s;sign='-';}isInteger=(n==0)?true:false;n = n > 0 && n <= 20 ? n : 2;s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";var l = s.split(".")[0].split("").reverse(),r = s.split(".")[1];t = "";for(i = 0; i < l.length; i ++ ){t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");}if(isInteger){return sign+t.split("").reverse().join("");}else{return sign+t.split("").reverse().join("") + "." + r;}}
//货币还原
function Rmoney(s){if(s==''||s==null) return '0'; else return parseFloat(s.replace(/[^\d\.-]/g, ""));}
//页码格式化
function FpageNumber(nm1,nm2){return nm2<1?'<font style="font-family:Times New Roman;font-size:14px;font-weight:bold">'+nm1+'</font>':'<font style="font-family:Times New Roman;font-size:16px;font-weight:bold">'+nm1+'</font><font style="font-family:Times New Roman;font-size:10px;font-style:italic"> / </font><font style="font-family:Times New Roman;font-size:12px;font-weight:bold">'+nm2+'</font>';}
function countObjLength(obj) {
    var count = 0;
    for (var property in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, property)) {
            count++;
        }
    }
    return count;
}
//图片批量预加载
function PreLoadImgs(arr,funOnLoad,funLoading,funOnError){ 
	  var numLoaded=0, 
	  numError=0, 
	  isObject=Object.prototype.toString.call(arr)==="[object Object]" ? true : false; 
	  var arr=isObject ? arr.get() : arr; 
	  for(a in arr){ 
		var src=isObject ? $(arr[a]).attr("data-src") : arr[a]; 
		PreLoadPerImg(src,arr[a]); 
} 
	   
function PreLoadPerImg(src,obj){ 
		var img=new Image(); 
		img.onload=function(){ 
		  numLoaded++; 
		  //alert(this.src);
		  funLoading && funLoading(numLoaded,arr.length,src,obj); 
		  funOnLoad && numLoaded==arr.length && funOnLoad(numError); 
		}; 
		img.onerror=function(){ 
		  numLoaded++; 
		  numError++; 
		  funOnError && funOnError(numLoaded,arr.length,src,obj); 
		} 
		img.src=src; 
	  } 
} 
	
//Json替换
//json='{"planpayment":"0","realpayment":"0","plancommission":"0","realcommission":"0","planfreight":"0","realfreight":"0","plancut":"0","realcut":"0"}';
//arrkey=['planpayment','realpayment'];arrval=['1','2'];
//alert(replaceJson(json,arrkey,arrval));
function JsonToObj(json){
json=(json=='')?'{}':json;
obj=$.parseJSON(json);
return obj;
}
function UpdateJson(json,arrkey,arrval){
	$.each(arrkey,function(key,val){
	//alert(key+'  '+arrval[key]);
	arrK=[val];arrV=[arrval[key]];
	if(IsExistKeyJson(json,val)){
	json=ReplaceJson(json,arrK,arrV);
	}else{
	json=AddJson(json,arrK,arrV);	
	}
	})
return json;
}
function ReplaceJson(json,arrkey,arrval){
out='',i=0;
$.each($.parseJSON(json),function(key,val){
r=arrkey.indexOf(key);
if(r==-1){
add=('"'+key+'":"'+val.JsonQuotes(true)+'"');	
}else{
add=('"'+key+'":"'+arrval[r].JsonQuotes(true)+'"');	
}
out=AddStr(out,add,',');
i++;
})
return '{'+out+'}';
}
//新增键值内容
function AddJson(json,arrkey,arrval){
var out='';
json=(json=='')?'{}':json;
$.each($.parseJSON(json),function(key,val){
out=AddStr(out,('"'+key+'":"'+val.JsonQuotes(true)+'"'),',');
})
var i=0;
$.each(arrkey,function(key,val){
out=AddStr(out,('"'+val+'":"'+arrval[i].JsonQuotes(true)+'"'),',');
i++;
})
return '{'+out+'}';
}
//检测键值是否存在
function IsExistKeyJson(json,validKey){
var out=false;
try{
	$.each($.parseJSON(json),function(key,val){
	if(key==validKey){out=true;return false;}
	})
}catch(e){                                
return out;  
}
return out;
}

//验证授权json
//validOauthJson('{"personnel.profile":"1","clickfarming.finacial":"1"}','personnel.profile')
function ValidOauthJson(json,validKey){
var out=false;
try{
	$.each($.parseJSON(json),function(key,val){
	if(key==validKey){out=true;return false;}
	})
}catch(e){                                
return out;  
}
return out; 
}
//验证更新指定键值的授权json，如果传入值为1新增，否则移除
//json='{"planpayment":"0","realpayment":"0","plancommission":"0","realcommission":"0","planfreight":"0","realfreight":"0","plancut":"0","realcut":"0"}';
//arrkey=['planpayment','realpayment'];arrval=['1','2'];
//alert(updateOauthJson(json,arrkey,arrval));
function UpdateOauthJson(json,arrkey,arrval){
out='',i=0;
if(json==''||json=='{}'){
	$.each(arrkey,function(key,val){
		if(arrval[i]=='1'){
		add=('"'+val+'":"'+arrval[i]+'"');
		out=AddStr(out,add,',');	
		}
	})
}else{
	$.each(arrkey,function(key,val){
		arrK=[val];arrV=[arrval[i]];
			if(IsExistKeyJson(json,val)){
			json=ReplaceJson(json,arrK,arrV);
			}else{
			json=AddJson(json,arrK,arrV);	
			}
	i++;	
	})
	out='';
	$.each($.parseJSON(json),function(key,val){
	if(val=='1'){
	add=('"'+key+'":"1"');out=AddStr(out,add,',');
	}
	})
}
return out==''?'':'{'+out+'}';
}
//对于授权操作对象的常规处理
function ManageOauthControl(arr){
arr['object'].each(function(){
switch(arr['type']){
case'unblind':
if($(this).attr('href')!=undefined){$(this).attr('href','javascript:void(0)');}
$(this).unbind();break
case'remove':$(this).remove();break
case'warning':
if($(this).attr('href')!=undefined){$(this).attr('href','javascript:void(0)');}
$(this).html(arr['warning']);break
}
});
}
ip = getCookieByName('ipConfig');
//获取当前网址
var currentLocation = encodeURIComponent(window.location.href);
console.log(currentLocation);

  //来源URL
var refurl = document.referrer == "" ? "" : encodeURIComponent(document.referrer);
//高宽
var width = window.screen.width; 
var height = window.screen.height 
// var domain = 'http://122.5.51.134:8034/collection/info.gif';
var domain = 'http://192.168.22.27/collection/info.gif';
//cookie可用性
cookieEnabled = navigator.cookieEnabled ? 1 : 0;
//应该是获取第几个COOKIE
function getCookieByOffset(offset) {
    var endstr = document.cookie.indexOf(";", offset);
    if (endstr == -1) endstr = document.cookie.length;
    return unescape(document.cookie.substring(offset, endstr));
}
//应该是获取COOKIE
function getCookieByName(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg) return getCookieByOffset(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}
//设置COOKIE
function setCookie(name, value, exptime, domain) {
console.log('setCookie');
console.log(domain);
    var domain = domain ? domain : "everyoo.com";
    var exp = new Date();
    exp.setTime(exp.getTime() + exptime);
    // document.cookie = name + "=" + value + ";path=/;expires=" + exp.toGMTString() + ";domain=" + domain + ";";
    document.cookie = name + "=" + value + ";path=/;expires=" + exp.toGMTString() + ";";
}

//检查用户是否登录
function checkLog()
{

}
//获取浏览器信息的存放对象
var browser = {
	versions: function () {
		var u = navigator.userAgent, app = navigator.appVersion;
		return {//移动终端浏览器版本信息 
			trident: u.indexOf('Trident') > -1, //IE内核
			presto: u.indexOf('Presto') > -1, //opera内核
			webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
			gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
			mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/), //是否为移动终端
			ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
			android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
			iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
			iPad: u.indexOf('iPad') > -1, //是否iPad
			webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
		};
	} (),
	language: (navigator.browserLanguage || navigator.language).toLowerCase()
}

//获取OS
var os = {
	name: function() { 
	var app = navigator.appVersion;
	var sUserAgent = navigator.userAgent;
	var isWin = (navigator.platform == "Win32") || (navigator.platform == "Windows");
	var isMac = (navigator.platform == "Mac68K") || (navigator.platform == "MacPPC") || (navigator.platform == "Macintosh") || (navigator.platform == "MacIntel");
	var isLinux = (String(navigator.platform).indexOf("Linux") > -1);
	var isUnix = (navigator.platform == "X11") && !isWin && !isMac;
	var isiOS = !!sUserAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); 
	var wp = (String(navigator.platform).indexOf("Windows Phone") > -1);
	<!-- alert(navigator.platform); -->
	if (isMac) return "2";
	if (isUnix) return "7";
	if (isLinux) return "3";
	if (isWin)   return '1';
	if (isAndroid) return '5';
	if (isIOS) return '6';
	if (wp) return '4';
	return "other";
	}()
};

//定义一个Object
//不懂在哪里调用了
//url 是发送地址
//data可以是array 也可以是string 未定
//与数据对接方交流之后在进行重构
var SendData = function (url) {
    //设置服务器地址
    var pingServerUrl = url;
    //得到时间
    var n =parseInt((new Date().getTime())/1000);
	//定义参数
	var param = 'init_time=' + n + '&';
    //得到类似时间戳
    var c = escape(n * 1000 + Math.round(Math.random() * 1000));
	//os名称
	var os = this.os.name;	
//定义浏览器参数代码	
var bro = 5;
if (this.browser.versions.trident) {
	bro = 1; 
} 
if (this.browser.versions.presto) {
	bro = 2;
}
if (this.browser.versions.gecko) {
	bro = 3;
}
if (this.browser.versions.webKit) {
	bro = 4;
}
//param += 'lang=' +this.browser.language + '&' + 'platform=' + (this.browser.versions.mobile ? '1' : '0') +
 //'&' + 'os=' + this.os.name + '&' + 'brower='  + bro + '&' + 'screen=' + width + '*' + height + '&' + //'referer=' + refurl  + '&' + 'cookie=' + '1'  + '&';
param += 'lang=' +this.browser.language + '&screen=' + width + '*' + height + '&' + 'cookie=' + cookieEnabled  + '&' + 'url=' + currentLocation + '&' + 'platform=1' + '&rand=' + Math.random() + '&ip=' + ip + '&';
    //方法中this的作用域是什么
    //作用域是整个WINDOW下
    this.getUid = function () {
        var uid = "";
        if (getCookieByName("SMYUV") != null) {
            uid = getCookieByName("SMYUV");
        } else {
            uid = c;
            //设置过期时间为三十天
			console.log(this.domain);
            setCookie("SMYUV", uid, 2592000000, this.domain);
        }
        return uid;
    }

    //调用之前的闭包
    var u = this.getUid();
	param += 'rnd=' + u + '&';
    //有一个闭包。发送PV
    this.getPv = function () {
		//pv事件代码是1
		param += 'event=1';
        //可能在其他的JS代码中
        var pvImg = document.createElement('img');
        //利用IMG标记发送一个请求
        //C 标记每一台计算机       
        //U 标记每一台计算机， 有可能是第一次执行的标记的KEY
        //R 标记来源站点
        //p1 当前地址
        pvImg.src = pingServerUrl + "?" + param + '&param=';
    }
	this.getPush = function($event, $param, $url) {
		param += 'event=' + $event + '&' + 'param=' + $param;
		var pushImg = document.createElement('img');
		pushImg.src = pingServerUrl + '?' + param;
	
	}
    //一个闭包
    this.getDlBtnClick = function (type, ele) {
        var n = new Date().getTime();
        var c = escape(n * 1000 + Math.round(Math.random() * 1000));
        var id = ele.attr("id");
        var ctImg = document.createElement('img');
        //发送到ct.GIF的信息
        ctImg.src = pingServerUrl + "ct.GIF?t=" + c + "&u=" + u + "&pl=" + this.pl + "&id=" + id + "&type=" + type;
    }
    //有一个闭包。闭包小王子
    this.getClick = function (evt) {
        //传入的EVT为空的话， 使用window.event获取
        //window.event这只在IE下是这样的。
        //window.event代表着事件对象的状态
        evt = evt ? evt : window.event;
        var srcElem = (evt.target) ? evt.target : evt.srcElement;
        //try太多， where you try
        try {
            while (srcElem.tagName.toUpperCase() == "A" || srcElem.tagName.toUpperCase() == "INPUT" || srcElem.tagName.toUpperCase() == "IMG") {
                is_link = false;
                if (srcElem.tagName.toUpperCase() == "A" || srcElem.tagName.toUpperCase() == "INPUT") {
                    is_link = true;
                }
                var linkname = srcElem.innerHTML ? srcElem.innerHTML : srcElem.value;
                linkname = escape(linkname);
                var linkurl = srcElem.href ? srcElem.href : srcElem.value;
                linkurl = encodeURIComponent(linkurl);
                clickFrom = window.location.href;
                if (linkname == "" || address == "") {
                    break;
                }
                var n = new Date().getTime();
                var c = escape(n * 1000 + Math.round(Math.random() * 1000));
                var parent = srcElem.parentNode;
                while (!parent.id) {
                    parent = parent.parentNode;
                }
                mod = parent.id;
                var clickImg = document.createElement('img');
                if (is_link) {
                    clickImg.src = pingServerUrl + "ct.GIF?t=" + c + "&u=" + u + "&r=" + this.refurl + "&pl=" + this.pl + "&on=" + linkname + "&ol=" + linkurl + "&mod=" + mod + "&clickfrom=" + clickFrom;
                }
                srcElem = srcElem.parentNode;
            }
            console.log('165');
        } catch (e) {
            console.log('168');
        }
        return true;
    }
}
//页面加载的时候发出一个PV请求
this.SendData('http://192.168.22.27/collection/info.gif');
getPv();

/**
* 定义手动添加统计的函数
* $event 为事件类型
* $param 为事件参数
* $url 为基于事件的URL
*/
function push($event, $param, $url) {
	this.SendData('http://192.168.22.27/collection/info.gif');
	getPush($event, $param, $url);
}
//在confim下面点确定即离开页面的时候会alert 387, 不离开的话会出现392
var _t;
window.onbeforeunload = function()
{
    setTimeout(function(){_t = setTimeout(onunloadcancel, 0)}, 0);
	push(10, '', encodeURIComponent(window.location.href))
}
window.onunloadcancel = function()
{
    clearTimeout(_t);
}





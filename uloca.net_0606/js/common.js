// common.js	2019/02/23
// <script src="/js/common.js?version=20190203"></script>


function clog(msg) {
	console.log(msg);
}

String.prototype.string = function(len){var s = '', i = 0; while (i++ < len) { s += this; } return s;};
String.prototype.zf = function(len){return "0".string(len - this.length) + this;};
Number.prototype.zf = function(len){return this.toString().zf(len);};

function number_format(amount) {
 
	if(amount==0) return 0;
 
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (amount + '');
 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
    return n;
}

function dateAddDel(sDate, nNum, type) {
    var yy = eval(sDate.substr(0, 4)); //, 10);
    var mm = eval(sDate.substr(5, 2)); //parseInt(sDate.substr(5, 2), 10);
    var dd = eval(sDate.substr(8)); //parseInt(sDate.substr(8), 10);
    var d = new Date();
    if (type == "d") {
        d = new Date(yy, mm - 1, dd + nNum);
    }
    else if (type == "w") {
		nNum = 7 * nNum;
        d = new Date(yy, mm - 1 , dd + nNum);
    }
	else if (type == "m") {
        d = new Date(yy, mm - 1 + nNum, dd);
    }
    else if (type == "y") {
        d = new Date(yy + nNum, mm - 1, dd);
    }
 
    yy = d.getFullYear();
    mm = d.getMonth() + 1; mm = (mm < 10) ? '0' + mm : mm;
    dd = d.getDate(); dd = (dd < 10) ? '0' + dd : dd;
 
    return '' + yy + '-' +  mm  + '-' + dd;
}

/* =============================================================================================================
	쿠키 관련 함수
=============================================================================================================== */
//쿠키읽어 오기
function GetCookie(cookieName)
{
	var retVal = ""
	if(cookieName == null) return "";

	thisCookie = document.cookie.split("; ");
	
	for (i = 0; i < thisCookie.length; i++)
	{
		if (cookieName == thisCookie[i].split("=")[0])
		{
			retVal = thisCookie[i].split("=")[1];
		}
	}
	
	return unescape(retVal);
}

//쿠키에 설정 
function SetCookie(cookieName,cookieVal,cookieExp)
{ 
	
	if(cookieVal == null) return;
	if(cookieName == null) return;

	expireDate = new Date;
		
	if(cookieExp == null) // default 하루
	{
		//expireDate.setMonth(expireDate.getMonth()+6); // 6개월간 쿠키 저장
		expireDate.setDate(expireDate.getDate()+1); // 현재 시간에서 날자 구하고 유효기간 더해서 쿠키 유효일 설정
	} else {
		expireDate.setDate(expireDate.getDate()+cookieExp);
	}
	
	//document.cookie = cookieName + "=" + cookieVal + "; expires=" + expireDate.toGMTString() ;
	document.cookie = cookieName + "=" + escape(cookieVal)+"; path=/; expires="+expireDate.toGMTString()+"; "; // 쿠키 문자열 설정
	
}

function getToday(sep) {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
		dd = '0'+dd;
	} 

	if(mm<10) {
		mm = '0'+mm;
	} 

	today = yyyy + sep + mm + sep + dd  ;
	return today;
}
function getTodayTime(sep) {
	var sep2 = ':';
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	var hh = today.getHours();
	var mi = today.getMinutes();
	var ss = today.getSeconds();
	
	if(dd<10) {
		dd = '0'+dd;
	} 

	if(mm<10) {
		mm = '0'+mm;
	} 
	if(hh<10) {
		hh = '0'+hh;
	} 

	if(mi<10) {
		mi = '0'+mi;
	}
	if(ss<10) {
		ss = '0'+ss;
	}

	today = yyyy + sep + mm + sep + dd + ' ' + hh + sep2 + mi + sep2 + ss;
	return today;
}
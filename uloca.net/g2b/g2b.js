//상세검색::uloca23 
//var ServerAddr = '<?=$_SESSION["ServerAddr"]?>';
var agent = navigator.userAgent.toLowerCase();
var brswer = '';
//clog("agent="+agent);
//agent.indexOf("chrome")
if (agent.indexOf("msie") >= 0 || agent.indexOf("chrome") < 0) {
//alert("인터넷익스플로러 브라우저입니다. agent="+agent);
brswer = 'ie';
}
if (agent.indexOf("edge") >= 0) brswer = 'edge';
// edge agent=mozilla/5.0 (windows nt 10.0; win64; x64) applewebkit/537.36 (khtml, like gecko) chrome/64.0.3282.140 safari/537.36 edge/17.17134
// ie agent=mozilla/5.0 (windows nt 10.0; wow64; trident/7.0; .net4.0c; .net4.0e; infopath.3; rv:11.0) like gecko
// chrome agent=mozilla/5.0 (windows nt 10.0; win64; x64) applewebkit/537.36 (khtml, like gecko) chrome/69.0.3497.100 safari/537.36
Date.prototype.format = function(f) { 
    if (!this.valueOf()) return " ";
 
    var weekName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
    var d = this;
     
    return f.replace(/(yyyy|yy|MM|dd|E|hh|mm|ss|a\/p)/gi, function($1) {
        switch ($1) {
            case "yyyy": return d.getFullYear();
            case "yy": return (d.getFullYear() % 1000).zf(2);
            case "MM": return (d.getMonth() + 1).zf(2);
            case "dd": return d.getDate().zf(2);
            case "E": return weekName[d.getDay()];
            case "HH": return d.getHours().zf(2);
            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2);
            case "mm": return d.getMinutes().zf(2);
            case "ss": return d.getSeconds().zf(2);
            case "a/p": return d.getHours() < 12 ? "오전" : "오후";
            default: return $1;
        }
    });
};
 
String.prototype.string = function(len){var s = '', i = 0; while (i++ < len) { s += this; } return s;};
String.prototype.zf = function(len){return "0".string(len - this.length) + this;};
Number.prototype.zf = function(len){return this.toString().zf(len);};

Number.prototype.format = function(){
    if(this==0) return 0;
 
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');
 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
    return n;
};
// 문자열 타입에서 쓸 수 있도록 format() 함수 추가
String.prototype.format = function(){
    var num = parseFloat(this);
    if( isNaN(num) ) return num;
 
    return num.format();
};
function clog(msg) {
	console.log(msg);
}
function number_format(amount) {

	if(amount==0) return 0;
 
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (amount + '');
 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
    return n;
}

function isNumber(s) {
  s += ''; // 문자열로 변환
  s = s.replace(/^\s*|\s*$/g, ''); // 좌우 공백 제거
  if (s == '' || isNaN(s)) return false;
  return true;
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
function timeAddDel(sDate, nNum, type) {

}
 
function move() {
   document.getElementById('loading').style.display = 'inline'; //"none";= inline
}
function move_stop() {
   document.getElementById('loading').style.display = 'none'; //"none";= inline
}

//무료검색 횟수설정 searchCount
var searchType=1; // 1:입찰정보 2:기업정보
var searchDt = '';
var searchCount = 0;
var searchCountMax = 5;  //무료검색횟수 	-by jsj 0314 
var searchLoginPlus = 5; //로그인후 무료검색횟수 -by jsj 0314
var detailsw = false;
var loginSW = '<?=$loginSW?>'; 	// 1:관리자 or 유료사용자, 0: 권한없음,  plugins/g2b.php 83line -by jsj 0312

function searchajax000() {	  	// 상세검색 (통합검색은 g2b_2019.js) 
	detailsw = true;
	//var form = document.historyForm;
	var form = document.myForm;
	if (form.id.value == '' && (SearchCounts > searchCountMax))	{
		alert (String(SearchCounts)+'::무료 검색 횟수가 초과 했습니다. 로그인 하십시요. '); //-by jsj 0312
		location.href='/ulocawp/?page_id=325';	//로그인메뉴로 이동 
		exit;
	}
	else if (loginSW == 0 && (SearchCounts > (searchCountMax + searchLoginPlus))) {
		alert(String(SearchCounts) + 'ln136[구매결제]회비 납부 기간이 지났습니다. 서비스운영을 위한 구독료 or Donation이 필요합니다.^^');
		
		// location.href='/ulocawp/?page_id=1352'; //구매결제로 이동 -by jsj 0312 
		// exit;
	}
	
	if (searchType==1 && form.kwd.value.trim() == '' && form.dminsttNm.value.trim() == '')
		{
			alert('입찰정보 검색에 공고명이나 수요기관 하나는 입력하세요.');
			form.kwd.focus();
			return;
		}
		/*
		if (form.kwd.value == '' && form.dminsttNm.value == '')
		{
			alert('검색 키워드를 입력 하세요.');
			form.kwd.focus();
			return;
			//form.kwd.value = ' ';
		} */
		if (searchType==2 && form.compname.value.trim() == '')
		{
			alert('기업정보 검색에 입찰기업명이 없습니다.');
			form.compname.focus();
			return;
		}
		 
		document.getElementById('tables').innerHTML = '';
		document.getElementById('totalrec').innerHTML = '';
		durationIndex = 0;
		var today = getToday('-'); //new Date().toJSON().slice(0,10);
		//alert(utc);
		form.endDate.value = today;
		eDate1 = form.endDate.value;
		durationIndex = 0;
		ddur = 0 - durationatonce[durationIndex];
		//clog('durationIndex='+durationIndex+' durationatonce='+durationatonce[durationIndex]+' ddur='+ddur+' eDate1='+eDate1);
		sDate1 = dateAddDel(eDate1, ddur, 'd');
		endSw = false;
		if (sDate1 <= form.startDate.value)
		{
			sDate1 = form.startDate.value;
			endSw = true;
		}
		lastStartDate = form.lastStartDate.value;
		idx = 0;
		ajaxCnt = 0;
		curStart = 0;
		//clog('ajax 1 sDate1='+sDate1+' eDate1='+eDate1+' endSw ='+ endSw+' durationIndex='+durationIndex+' ddur='+ddur+' ajaxCnt='+ajaxCnt);
	
		parm = 'kwd='+encodeURIComponent(form.kwd.value.trim()); //+'&startDate='+encodeURIComponent(sDate1)+'&endDate='+eDate1;
		parm +='&dminsttNm='+encodeURIComponent(form.dminsttNm.value.trim());
		parm +='&compname='+encodeURIComponent(form.compname.value.trim());
		//parm +='&searchDt1='+searchDt1;
		//parm +='&searchDt2='+searchDt2;
		//parm +='&searchDt3='+searchDt3;
		//parm +='&searchDt4='+searchDt4;
		//parm +='&searchDt5='+searchDt5;
		//parm +='&searchDt6='+searchDt6;
		parm += '&curStart='+curStart+'&cntonce='+cntonce;
		if (form.kind21.checked) parm +='&bidthing=1';
		if (form.kind22.checked) parm +='&bidcnstwk=1';
		if (form.kind23.checked) parm +='&bidservc=1'; 
		if (searchType==2) parm +='&compinfo=1';
		else parm +='&bidinfo=1';
		
		var sel = document.getElementById("kind1");
		var val = sel.options[sel.selectedIndex].value;
		parm += '&bidhrc='+val; 
		parm +='&id='+form.id.value;
		//if (form.chkHrc.checked) parm +='&chkHrc=hrc';
		//var sel = document.getElementById("syear");
		//var val = sel.options[sel.selectedIndex].value;
		//parm +='&sYear='+val;
		//발주계획 추가 한줄 
		if (searchType==1 && val == "pln") server = "/g2b/g2bOrder.php"; // ?kwd="+kwd+"&dminsttNm="+dminsttNm;
		else server="/datas/publicData.php";
		clog(server+'?'+parm);
				
		move();
		//------------------------------
		getAjaxPost(server,recv,parm);
		//------------------------------

		/*   $.ajax({
        type: "get",/*method type* /
		scriptCharset: "utf-8" ,
        contentType: "application/json; charset=utf-8",
        url: server+parm, //"/datas/publicData.php"+parm,
        //data: parm , /*parameter pass data is parameter name param is value * /
        //dataType: "json",
        success: function(data) {
               // data = new Buffer(data, 'UTF-8');
			   //clog(data);
			   //document.getElementById('tables').innerHTML = data;
			   //move_stop();
			   makeTable(data);
			   
            }
        ,
        error: function(result) {
			move_stop();
            alert("Error "+objToString(result));
        }
    });

*/		
}

function searchajax2() {
	var form = document.myForm;
	eDate1 = dateAddDel(sDate1, -1, 'd');
	if (durationIndex<durationatonce.length-1) durationIndex = durationIndex +1;
	ddur = 0 - durationatonce[durationIndex];
	sDate1 = dateAddDel(eDate1, ddur, 'd');
	if (sDate1 <= form.startDate.value)
	{
		sDate1 = form.startDate.value;
		endSw = true;
		
	}
	//clog('ajax sDate1='+sDate1+' eDate1='+eDate1+' endSw ='+ endSw+' durationIndex='+durationIndex+' ddur='+ddur+' ajaxCnt='+ajaxCnt);
	if (form.compname.value.trim() != '')
	{
		searchType=2; // 1:입찰정보 2:기업정보
		searchDt = dateAddDel(searchDt, -6, 'm');
		//searchCount -= 1; // 3년 -by jsj 190417 ???
	} 
	
	parm = 'kwd='+encodeURIComponent(form.kwd.value.trim())+'&startDate='+sDate1+'&endDate='+eDate1;
		parm +='&dminsttNm='+encodeURIComponent(form.dminsttNm.value.trim());
		parm +='&compname='+encodeURIComponent(form.compname.value)+'&searchDt='+searchDt;
		if (form.kind21.checked) parm +='&bidthing=1';
		if (form.kind22.checked) parm +='&bidcnstwk=1';
		if (form.kind23.checked) parm +='&bidservc=1';
		var sel = document.getElementById("kind1");
		var val = sel.options[sel.selectedIndex].value;
		parm += '&bidhrc='+val;
		//if (form.chkHrc.checked) parm +='&chkHrc=hrc';
		server="/datas/publicData.php";
		parm +='&id='+form.id.value;
		//clog(server+'///'+parm);
		getAjaxPost(server,recv,parm);	
/*
		$.ajax({
        type: "get",/*method type* /
		scriptCharset: "utf-8" ,
        contentType: "application/json; charset=utf-8",
        url: "/datas/publicData.php"+parm,
        
        success: function(data) {
               
			   makeTable(data);
			   //if (endSw == true)  
				   move_stop();
            }
        ,
        error: function(result) {
			move_stop();
            alert("Error "+objToString(result));
        }
    });
*/
}

function searchajax3() {
	
	//var form = document.historyForm;
	var form = document.myForm;
				
		durationIndex = 0;
		//var utc = getToday('-'); //new Date().toJSON().slice(0,10);
		//alert(utc);
		//form.endDate.value = today;
		eDate1 = dateAddDel(sDate1, -1, 'd'); //eDate1 = form.endDate.value;
		durationIndex = 0;
		ddur = 0 - durationatonce[durationIndex];
		//clog('durationIndex='+durationIndex+' durationatonce='+durationatonce[durationIndex]+' ddur='+ddur+' eDate1='+eDate1);
		sDate1 = dateAddDel(eDate1, ddur, 'd');
		endSw = false;
		form.startDate.value = dateAddDel(eDate1, -1, 'y');
		
		//idx = 0;
		ajaxCnt = 0;
		//clog('ajax 3 form.startDate.value='+form.startDate.value+' eDate1='+eDate1+' endSw ='+ endSw+' durationIndex='+durationIndex+' ddur='+ddur+' ajaxCnt='+ajaxCnt);
		document.getElementById('continueSearch').style.visibility = 'hidden';

		parm = 'kwd='+encodeURIComponent(form.kwd.value.trim())+'&startDate='+encodeURIComponent(sDate1)+'&endDate='+eDate1;
		parm +='&dminsttNm='+encodeURIComponent(form.dminsttNm.value.trim());
		if (form.kind21.checked) parm +='&bidthing=1';
		if (form.kind22.checked) parm +='&bidcnstwk=1';
		if (form.kind23.checked) parm +='&bidservc=1';
		var sel = document.getElementById("kind1");
		var val = sel.options[sel.selectedIndex].value;
		parm += '&bidhrc='+val;
		parm +='&id='+form.id.value;
		//if (form.chkHrc.checked) parm +='&chkHrc=hrc';
		server="/datas/publicData.php";
		//clog(server+parm);
		//clog(server+'///'+parm);
		
		//---------------------------------
		getAjaxPost(server,recv,parm);	
		//---------------------------------
/*

    $.ajax({
        type: "get",/*method type* /
		scriptCharset: "utf-8" ,
        contentType: "application/json; charset=utf-8",
        url: "/datas/publicData.php"+parm,
        //data: parm , /*parameter pass data is parameter name param is value * /
        //dataType: "json",
        success: function(data) {
               // data = new Buffer(data, 'UTF-8');
			   //clog(data);
			   //document.getElementById('tables').innerHTML = data;
			   makeTable(data);
			   move_stop();
            }
        ,
        error: function(result) {
			move_stop();
            alert("Error "+objToString(result));
        }
    });
*/
		
}
/* --------------------------------------------------------------------------------
	스크롤바의 현재 위치 %
--------------------------------------------------------------------------------- */
var sHeight = '';
function viewScroll() {
	pos = getCurrentScrollPercentage();
	clog('위치='+pos+'%'+ ' sDate1='+sDate1+ ' sHeight='+sHeight+' doing='+doing);
	//return;
	if (sDate1 == '') return;

	if (endSw == true && pos < 90) return;
	if (window.innerHeight == sHeight) return;
	
	if ( sDate1 < lastStartDate  && doing == true) return;
	sHeight = window.innerHeight;
	var form = document.myForm;
	eDate1 = dateAddDel(sDate1, -1, 'd');
	sDate1 = dateAddDel(eDate1, -30, 'd');
	form.startDate.value = sDate1;
	form.endDate.value = eDate1;
	if (doingDate == sDate1)
	{
	};
	doing = true;
	searchajax2();
	doing = false;

}

//위치=98.72047244094489% sDate1=2016-02-07 endSw=true doing=false
//g2b.js:263 위치=98.75328083989501% sDate1=2016-01-08 endSw=true doing=false
//23?page_id=337:384 sDate1=2015-12-09 eDate1=2016-01-07 endSw =true durationIndex=9 ddur=-60 doing=false

function getCurrentScrollPercentage(){
	return (window.scrollY + window.innerHeight) / document.body.clientHeight * 100
}

function objToString (obj) {
    var str = '';
    for (var p in obj) {
        if (obj.hasOwnProperty(p)) {
            str += p + '::' + obj[p] + '\n';
        }
    }
    return str;
}
function getPublicData() {
	try {
		move_stop();
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
					document.getElementById('tables').innerHTML = xhr.responseText;
					
				} else {
					alert ("Error" + xhr.status);
				}
			}
		} catch (e) {}
}

//키워드, 입찰/낙찰옵션, 받을메일주소, 메일받기(체크박스)옵션: 
function mailMe() {
	//var bid = '<table>' + document.getElementById('bidData').innerHTML + '</table>';
	//clog('length='+bid.length);
	selectCheckedRow();

	frm = document.myForm;
	if (frm.email.value == '')
		{
			alert('email 주소를 입력 하세요.');
			return;
		} 
	parm = '?email='+frm.email.value+'&kwd='+frm.kwd.value +'&startDate='+frm.startDate.value.replace(/\-/g, "");
	//parm += '&endDate='+frm.endDate.value.replace("-", "").replace("-", "")	;
	parm += '&endDate='+frm.endDate.value.replace(/\-/g, "");
	parm += '&dminsttNm=';
	parm +='&id='+form.id.value;	
	url = 'sendmail.php'+parm; //?email='+document.myForm.email.value; //+'&bid='+bid;
	//clog('parm='+parm);
	//var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;   
  
  
	if (url == '') // || regex.test(url) === false)
	{
		alert('메일 주소가 없거나, 형식이 틀립니다.');
		return;
	}
	popupWindow = window.open(url); //,'_blank','height=700,width=900,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')

}

function mailMe2() {
	//var bid = '<table>' + document.getElementById('bidData').innerHTML + '</table>';
	//clog('length='+bid.length);
	
	frm = document.myForm;
	frm2 = document.mailForm;
	if (frm.email.value == '')
		{
			alert('email 주소를 입력 하세요.');
			return;
		} 
	frm2.email2.value = frm.email.value;

	trs01 = '';
	trs02 = '';
	trs03 = '';
	trsh = '<html><head><title>입.낙찰 알림</title><meta http-equiv="Content-Type" content="text/html; charset=utf8" /></head><body><p><a href="http://uloca.net"><input  type="button" value="유로카 입찰정보" style="width:300px; background:url(http://uloca.net/img/grid_tit_bg.gif) repeat-x; height:28px; color:#557b94; cursor:pointer; font-size:14px; font-weight: bold;font-family:Dotum; text-align:center; border-right:solid 1px #99bbe8; border-bottom:solid 1px #99bbe8;"></a></p>일시: <font color=blue size=3>'+frm.startDate.value+'</font> - <font color=blue size=3>'+frm.endDate.value+'</font> 키워드: <font color=blue size=3>'+frm.kwd.value+'</font> 수요기관: <font color=blue size=3>'+frm.dminsttNm.value+'</font><br><br>';
	
	trs01 = selectCheckedRowSelf2('specData','chk2'); //'bidDatabidthing','chkbidthing');	// 물품
	//clog('trs01='+trs01);
	trsh11 = '';
	if (trs01 != false) {
		trsh11 = '&nbsp;&nbsp;&nbsp;&nbsp<div style="font-size:18px;"><strong>[입찰 정보 (물품)]</strong>';
		trsh11 += '<table class="type10" id="bidData" style="font-size:12px;"><tr><th width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고번호</th><th scope="cols" width="150px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고명</th><th scope="cols" width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">추정가격</th><th scope="cols" width="120px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고일시</th><th scope="cols" width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">수요기관</th><th scope="cols" width="120px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">마감일시</th></tr>';
		trsh11 += trs01 + '</table>';
	//frm2.message.value = trsh1 + trs+'</table>';
	}
	trs02 = selectCheckedRowSelf2('specData','chk2'); //'bidDatabidcnstwk','chkbidcnstwk');	// 공사
	trsh12 = '';
	if (trs02 != false) {
		trsh12 = '&nbsp;&nbsp;&nbsp;&nbsp<div style="font-size:18px;"><strong>[입찰 정보 (공사)]</strong>';
		trsh12 += '<table class="type10" id="bidData" style="font-size:12px;"><tr><th width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고번호</th><th scope="cols" width="150px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고명</th><th scope="cols" width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">추정가격</th><th scope="cols" width="120px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고일시</th><th scope="cols" width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">수요기관</th><th scope="cols" width="120px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">마감일시</th></tr>';
		trsh12 += trs02 + '</table>';
	//frm2.message.value = trsh1 + trs+'</table>';
	}
	trs03 = selectCheckedRowSelf2('specData','chk2'); //'bidDatabidservc','chkbidservc');	// 용역
	trsh13 = '';
	if (trs03 != false) {
		trsh13 = '&nbsp;&nbsp;&nbsp;&nbsp<div style="font-size:18px;"><strong>- 입찰 정보 (용역)-</strong>';
		trsh13 += '<table class="type10" id="bidData" style="font-size:12px;"><tr><th width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고번호</th><th scope="cols" width="150px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고명</th><th scope="cols" width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">추정가격</th><th scope="cols" width="120px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">공고일시</th><th scope="cols" width="100px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">수요기관</th><th scope="cols" width="120px;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">마감일시</th></tr>';
		trsh13 += trs03 + '</table>';
	//frm2.message.value = trsh1 + trs+'</table>';
	}
	// 사전 규격 헤드
	trs2 = '';
	trs2 = selectCheckedRowSelf2('specData','chk2'); // 사전규격
	trsh2 = '';
	if (trs2 != false) {
		trsh2 += '<br><br><br>';
		trsh2 += '&nbsp;&nbsp;&nbsp;&nbsp<div style="font-size:18px;"><strong>[사전규격 정보]</strong>';
		trsh2 += '<table class="type10" id="specData" style="font-size:12px;">'
		trsh2 += '<tr>';
			
		//trsh2 += '	<th scope="cols" width="5%;" onclick="javascript:CheckAll()"><input type="checkbox"></th>';
		trsh2 += '	<th scope="cols" width="10%;"style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">등록번호</th>';
		trsh2 += '    <th scope="cols" width="25%;"style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">품명</th>';
		trsh2 += '    <th scope="cols" width="15%;"style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">예산금액</th>';
		trsh2 += '    <th scope="cols" width="12%;"style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">등록일시</th>';
		trsh2 += '    <th scope="cols" width="20%;"style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">수요기관</th>';
		trsh2 += '    <th scope="cols" width="13%;"style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;">마감일시</th>';
		trsh2 += '</tr>';
		trsh2 += trs2 + '</table>';
		//frm2.message.value = trsh2 + trs2+'</table>';
	}
	if (trs2 == false && trs01 == false && trs02 == false && trs03 == false) {
		alert('선택된 항목이 없습니다.');
		//window.close(); //
		return false;
	}
	frm2.message.value = trsh + trsh11 + trsh12 + trsh13 + trsh2 + '</body></html>';
	//clog(frm2.message.value);
	var gsWin = window.open('about:blank','new_blank','width=900,height=700,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
	frm2.submit();
	//popupWindow = window.open(
    //    url,'_blank','height=700,width=900,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')

}

function domCheck() {
	//x= document.getElementById("bidData").rows[1].cells;
	var table = document.getElementById('bidData');

	var rowLength = table.rows.length;
	chkst = document.getElementsByName('chk');
	for(var i=0; i<rowLength; i+=1){
		var row = table.rows[i];
		//clog('i='+i+' '+chkst[i].checked );
		
		var cellLength = row.cells.length;
		for(var y=0; y<cellLength; y+=1){
			var cell = row.cells[y];
			//clog('i='+i+' '+' y='+y+' ' +row.cells[y].innerHTML);
			
		} 
	}
}

function chkTable() {
	//x= document.getElementById("bidData").rows[1].cells;
	var table = document.getElementById('bidData');

var rowLength = table.rows.length;

for(var i=0; i<rowLength; i+=1){
  var row = table.rows[i];
  //clog('i='+i+' '+row.cells[0].checked );
  //your code goes here, looping over every row.
  //cells are accessed as easy

  var cellLength = row.cells.length;
  for(var y=0; y<cellLength; y+=1){
    var cell = row.cells[y];
	//clog('i='+i+' '+' y='+y+' ' +row.cells[y].innerHTML);
    //do something with every cell here
  } 
}
	//clog(x[0].innerHTML);
}
/* ------------------------------------------------------------------------------------------
json 찾기
------------------------------------------------------------------------------------------- */
function fndjson(jsn,col,val) {
//foreach ($item as $key => $row) {
//    $bidClseDt[$key]  = $row['bidClseDt'];
//}

}
/* ------------------------------------------------------------------------------------------
상세정보보기
------------------------------------------------------------------------------------------- */
function viewDtl(url,bidNtceNo,bidNtceOrd) {

if (url.trim() == '') // || regex.test(url) === false)
	{
		//alert('상세 주소가 없습니다.');
		//return;
		if (bidNtceNo.length==6) url = "https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo="+bidNtceNo; //689870
		else url = "http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno="+bidNtceNo+"&bidseq="+bidNtceOrd+"&releaseYn=Y&taskClCd=1";
		
	}
 //http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180509427&bidseq=00&releaseYn=Y&taskClCd=1
	clog ('url='+url);
	popupWindow = window.open(url); //,'_blank','height=920,width=840,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

}
/* ------------------------------------------------------------------------------------------
사전규격 상세정보보기
------------------------------------------------------------------------------------------- */
function viewDtls(bfSpecRgstNo) {

if (bfSpecRgstNo == '') // || regex.test(url) === false)
	{
		alert('등록번호가 없습니다.');
		return;
	}
	url = 'https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo=' + bfSpecRgstNo;
	//url = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=' + bfSpecRgstNo+'&bidseq=00&releaseYn=Y&taskClCd=5';
	popupWindow = window.open(url); //,'_blank1','height=920,width=840,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

}

function viewDtls1(bfSpecRgstNo,bidseq) {

if (bfSpecRgstNo == '') // || regex.test(url) === false)
	{
		alert('등록번호가 없습니다.');
		return;
	}
	//url = 'https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo=' + bfSpecRgstNo;
	url = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=' + bfSpecRgstNo+'&bidseq='+bidseq+'&releaseYn=Y&taskClCd=5';
	popupWindow = window.open(url); //,'_blank1','height=920,width=840,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

}
/* ------------------------------------------------------------------------------------------
입찰 결과 상세정보보기
------------------------------------------------------------------------------------------- */
function viewRslt(bidNtceNo,bidNtceOrd, dt, pss) {
	//clog(bidNtceNo+'/'+bidNtceOrd+'/'+dt+'/'+pss);
	if (dt == '')
	{
		alert('마감일이 정해지지 않았습니다.');
		return;
	}
    d = new Date();
	endDate = d.format("yyyyMMdd"); //d.format("yyyy-MM-dd");
	//startDate = dateAddDel(endDate, -1, 'd').replace(/-/g,'');
	dt=dt.replace(/-/g,'');
	dt = dt.substr(0,8);
	//alert(dt+'/'+startDate);
	if (dt>endDate) //startDate)
	{
		alert('아직 낙찰 되지 않았습니다.');
		return;
	}
	frm = document.compInfoForm;
	// id = frm.id.value;
	url = '/g2b/bidResult.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	// url += '&id='+id;
	//clog('function viewRslt '+url);
	popupWindow = window.open(url); //,'_blank','height=920,width=880,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
	popupWindow.focus();

	return; 
	/*
	frm = document.popForm;
	frm.bidNtceNo.value = bidNtceNo;
	frm.bidNtceOrd.value = bidNtceOrd;
	frm.pss.value = pss;
	frm.from.value = 'getBid';
	//parm = 'bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	url = '/g2b/bidResult.php';//?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	//alert(url);
	popupWindow = window.open(
        '','_blank','height=920,width=880,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
	frm.action =url; 
	frm.method="post";
	frm.target="_blank";
	frm.submit();
	popupWindow.focus(); */
}

function viewRslt2(bidNtceNo,bidNtceOrd,dt,pss) {
	if (dt == '')
	{
		alert('마감일이 정해지지 않았습니다.');
		return;
	}
    d = new Date();
	endDate = d.format("yyyy-MM-dd");
	startDate = dateAddDel(endDate, -1, 'd').replace(/-/g,'');
	dt=dt.replace(/-/g,'');
	//alert(dt+'/'+startDate);
	if (dt>=startDate)
	{
		alert('아직 낙찰 되지 않았습니다.');
		return;
	}
	//alert(bidNtceNo+'/'+bidNtceOrd+'/'+dt+'/'+pss);
/*	용역입찰	용역구분명 srvceDivNm
	물품입찰	물품규격명 prdctSpecNm, 물품수량 prdctQty
	공사입찰	부대공종명1	subsiCnsttyNm1 */
if (pss == '용역') 
	{
		url='http://www.g2b.go.kr:8101/ep/result/serviceBidResultDtl.do?bidno='+bidNtceNo+'&bidseq='+bidNtceOrd+'&whereAreYouFrom=piser ';
	} else if (pss == '공사') // || regex.test(url) === false)
	{
		url = 'http://www.g2b.go.kr:8101/ep/result/facilBidResultDtl.do?bidno='+bidNtceNo+'&bidseq='+bidNtceOrd;
	} else {
	url = 'http://www.g2b.go.kr:8101/ep/result/prodBidResultCateList.do?whereAreYouFrom=piser&check=211.42.85.19915331727476461&bidno='+bidNtceNo+'&bidseq='+bidNtceOrd;
	}
	popupWindow = window.open(url); //,'_blank1','height=920,width=840,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

}
function viewRslt3(bidNtceNo,bidNtceOrd,dt,pss) {
	if (dt == '')
	{
		alert('마감일이 정해지지 않았습니다.');
		return;
	}
    d = new Date();
	endDate = d.format("yyyy-MM-dd");
	startDate = dateAddDel(endDate, -1, 'd').replace(/-/g,'');
	dt=dt.replace(/-/g,'');
	//alert(dt+'/'+startDate);
	if (dt>=startDate)
	{
		alert('아직 낙찰 되지 않았습니다.');
		return;
	}
	//alert(bidNtceNo+'/'+bidNtceOrd+'/'+dt+'/'+pss);
/*	용역입찰	용역구분명 srvceDivNm
	물품입찰	물품규격명 prdctSpecNm, 물품수량 prdctQty
	공사입찰	부대공종명1	subsiCnsttyNm1 */
if (pss == '용역') 
	{
		url='http://www.g2b.go.kr:8101/ep/result/serviceBidResultDtl.do?bidno='+bidNtceNo+'&bidseq='+bidNtceOrd+'&whereAreYouFrom=piser ';
	} else if (pss == '공사') // || regex.test(url) === false)
	{
		url = 'http://www.g2b.go.kr:8101/ep/result/facilBidResultDtl.do?bidno='+bidNtceNo+'&bidseq='+bidNtceOrd;
	} else {
	url = 'http://www.g2b.go.kr:8101/ep/result/prodBidResultCateList.do?whereAreYouFrom=piser&check=211.42.85.19915331727476461&bidno='+bidNtceNo+'&bidseq='+bidNtceOrd;
	}

	return url;
	
}
function viewComp(bidNtceNo,bidNtceOrd,dt) {
	if (dt == '')
	{
		alert('마감일이 정해지지 않았습니다.');
		return;
	}
	var d = new Date();
	today = d.format("yyyy-MM-dd");
	//alert('dt='+dt+' today='+today);
	if (dt>=today)
	{
		alert('아직 낙찰 되지 않았습니다.');
		return;
	}
	
	url = '/g2b/bidWinner.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&id='+resudi; ;
	//alert(url);
	popupWindow = window.open(url); //,'_blank','height=920,width=880,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

}

/* ------------------------------------------------------------------------------------------
// 입찰정보 - 수요기관 재검색
------------------------------------------------------------------------------------------- */
var detailsw = false;
function viewscs(dminsttNm, kwd) {
	// kwd 디폴트 '' 으로 셋팅 -by jsj 20200423
	kwd = typeof kwd == 'undefined' ? kwd : '';

	if (dminsttNm == '') // || regex.test(url) === false)
		{
			alert('수요기관이 없습니다.');
			return;
		}
	frm = document.getElementById('myForm');
	// ----------------------------------------------------------
	// 수요기관 재검색 시 수요기관은 "?" 뒤에 표시 -by jsj 20200423
	// ----------------------------------------------------------
	kwd = frm.kwd.value;
	// ? 부터 재검색 시 지움
	if (kwd.indexOf('?') !== -1) {
		kwd = kwd.substr(0, kwd.indexOf('?'));
	}
	frm.dminsttNm.value = dminsttNm;

	idx=0;
	duridx = -1;
	curStart = 0;
	if (detailsw == true){
		searchajax000(); 							// 상세검색
	} else {
		frm.kwd.value = kwd + '? ' + dminsttNm;		// 통합검색은 ? 뒤에 수요기관
		searchajax0_1(); 							// 통합검색 
	}  
}

function viewscs0(dminsttNm) {
if (dminsttNm == '') // || regex.test(url) === false)
	{
		alert('수요기관이 없습니다.');
		return;
	}
	kwd = encodeURIComponent(document.getElementById('kwd').value.replace(/ /g,'+'));
	//kwd = '';
	dminsttNm = encodeURIComponent(dminsttNm);
	var d = new Date();
	endDate = d.format("yyyy-MM-dd");
	//startDate = dateAddDel(endDate, -6, 'm').replace(/-/g,''); 
	startDate = dateAddDel(endDate, -1, 'y').replace(/-/g,''); // 1년 전
	endDate = endDate.replace(/-/g,'');
	//alert(endDate+' / '+startDate);

	inqryBgnDt = startDate; //document.getElementById('startDate').value.replace(/-/g,'');
	inqryEndDt = endDate; //document.getElementById('endDate').value.replace(/-/g,'');
	//kwd = kwd.replace(/ /g,'+');
	//alert('viewscs kwd='+kwd);
	url = '/g2b/getBid.php?kwd='+kwd+'&dminsttNm='+dminsttNm+'&inqryBgnDt='+inqryBgnDt+'&inqryEndDt='+inqryEndDt;

	//clog('viewscs url='+url);
	popupWindow = window.open(url); //,'_blank','height=920,width=940,left="10",top="10",resizable="yes",scrollbars="both",toolbar="yes",menubar="no",location="no",directories="no",status="yes"');
}
function viewscs1(dminsttNm) {
if (dminsttNm == '') // || regex.test(url) === false)
	{
		alert('수요기관이 없습니다.');
		return;
	}
	//<a onclick="viewscs(&quot;부산광역시교육청 부산국제고등학교&quot;)">부산광역시교육청 부산국제고등학교</a> 수요기관
	kwd = encodeURIComponent(document.getElementById('kwd').value.replace(/ /g,'+'));
	dminsttNm1 = encodeURIComponent(dminsttNm);
	var d = new Date();
	endDate = d.format("yyyy-MM-dd");
	//startDate = dateAddDel(endDate, -6, 'm').replace(/-/g,''); 
	startDate = dateAddDel(endDate, -1, 'y').replace(/-/g,''); // 1년 전
	endDate = endDate.replace(/-/g,'');
	//alert(endDate+' / '+startDate);

	inqryBgnDt = startDate; //document.getElementById('startDate').value.replace(/-/g,'');
	inqryEndDt = endDate; //document.getElementById('endDate').value.replace(/-/g,'');
	//kwd = kwd.replace(/ /g,'+');
	//alert('viewscs kwd='+kwd);
	url = '<a href="/g2b/getBid.php?kwd='+kwd+'&dminsttNm='+dminsttNm1+'&inqryBgnDt='+inqryBgnDt+'&inqryEndDt='+inqryEndDt+'">'+dminsttNm + '</a>';
	return url;
	
}
function viewscsOpener(dminsttNm) {
if (dminsttNm == '') // || regex.test(url) === false)
	{
		alert('수요기관이 없습니다.');
		return;
	}
	kwd = encodeURIComponent(opener.document.getElementById('kwd').value.replace(/ /g,'+'));
	dminsttNm = encodeURIComponent(dminsttNm);
	var d = new Date();
	endDate = d.format("yyyy-MM-dd");
	//startDate = dateAddDel(endDate, -6, 'm').replace(/-/g,''); 
	startDate = dateAddDel(endDate, -1, 'y').replace(/-/g,''); // 5년 전
	endDate = endDate.replace(/-/g,'');
	//alert(endDate+'/'+startDate);

	inqryBgnDt = startDate; //document.getElementById('startDate').value.replace(/-/g,'');
	inqryEndDt = endDate; //document.getElementById('endDate').value.replace(/-/g,'');
	//kwd = kwd.replace(/ /g,'+');
	//alert('viewscs kwd='+kwd);
	url = '/g2b/getBid.php?kwd='+kwd+'&dminsttNm='+dminsttNm+'&inqryBgnDt='+inqryBgnDt+'&inqryEndDt='+inqryEndDt;

	//clog('viewscs url='+url);
	popupWindow = window.open(
        url,'_blank','height=920,width=940,left="10",top="10",resizable="yes",scrollbars="both",toolbar="yes",menubar="no",location="no",directories="no",status="yes"');
}
function loginCheck() {
	if (resudi == '')
	{
		alert('로그인이 필요한 기능입니다.');
		return false;
	}
	return true;
}
/* ------------------------------------------------------------------------------------------
응찰정보보기
------------------------------------------------------------------------------------------- */
function bidInfo(compno) {

	url = '/g2b/datas/getInfobyComp.php?compno='+compno+'&id='+resudi;
	popupWindow = window.open(url); //,'_blank7','height=920,width=950,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

}

/* ------------------------------------------------------------------------------------------
업체정보보기
------------------------------------------------------------------------------------------- */
function compInfo(compno) {
	clog ("compInfo compno="+compno);
	url = '/g2b/datas/companyInfo.php?compno='+compno;
	//url = '/ulocawp?page_id=1557&compno='+compno;
	frm = document.compInfoForm;
	frm.compno.value = compno;

	//parm = 'bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	//url = '/g2b/datas/bidResult.php'; //?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	//alert(window.location.hostname);
	//popupWindow = window.open(
    //    '','compInfoForm','height=920,width=880,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

	//frm.action = url; //winddow.location.hostname + url;
	//frm.method="post";
	//frm.target="compInfoForm";
	//frm.submit();

	popupWindow = window.open(url);

}

// KED 상세 검색 --------------------------------
function detailCompany(compno) {
	clog ("detailCompany compno="+compno);
	//url = '/g2b/datas/companyInfo.php?compno='+compno;
    url = '/ulocawp?page_id=2074&compno='+compno;
    //url = '/ulocawp?page_id=2074&compno='+compno;  //<==uloca22

	//frm = document.compInfoForm;
	//frm.compno.value = compno;


	//parm = 'bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	//url = '/g2b/datas/bidResult.php'; //?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	//alert(window.location.hostname);
	//popupWindow = window.open(
    //    '','compInfoForm','height=920,width=880,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

	//frm.action = url; //winddow.location.hostname + url;
	//frm.method="post";
	//frm.target="compInfoForm";
	//frm.submit();

	popupWindow = window.open(url);

}
function compInfobyComp(compno) {
	frm = document.compInfoForm;
	
	url = '/g2b/datas/getInfobyComp.php?compno='+compno+'&id='+frm.id.value;
	//url = '?page_id=1557&compno='+compno+'&id='+frm.id.value; //url = 'http://uloca23.cafe24.com/ulocawp/?page_id=337';
	
	frm.compno.value = compno;
	//frm.opengDt.value = opengDt;
	
	//parm = 'bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	//url = '/g2b/datas/bidResult.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd +'&pss='+pss+'&from=getBid';
	//alert(url);
	popupWindow = window.open(url); // ,'_blank','height=920,width=880,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
	/* frm.action =url; 
	frm.method="post";
	frm.target="compInfoForm";
	frm.submit(); */

}
/* ------------------------------------------------------------------------------------------
특정업체 응찰기록 업체 찾기
------------------------------------------------------------------------------------------- */
function searchcomp() {
	frm = document.myForm;
	compname=frm.compname.value;
	compno=frm.compno.value;
	//alert(compname.length);
	if (compname == '' && compno == '')
	{
		alert('업체명이나 사업자등록번호를 입력하세요.');
		return;
	}
	if (compname != '' && compname.length<2)
	{
		alert('2글자 이상을 입력하세요.');
		return;
	}
	if (compname == '주식' || compname == '회사' || compname == '주식회사')
	{
		alert('너무 많은 데이타가 나옵니다. 다른 업체명을 입력하세요.');
		return;
	}
	if (compno != '' && compno.length != 10)
	{
		alert('사업자등록번호는 10자리 입력하세요.');
		return;
	}
	//alert(frm.duration.value);
	//return;
	url = '/g2b/datas/bidCompanySearch.php?compname='+encodeURIComponent(compname)+'&compno='+encodeURIComponent(compno)+'&duration='+encodeURIComponent(frm.duration.value)+'&id='+resudi;;
	move();
	getAjax(url,searchcomp2);
}
function searchcomp2(data) {
	document.getElementById('tablist').innerHTML = data;
	move_stop();
	setSort();
}
var openBidInfo='';
var openBidSeq='';
var openBidSeq_tmp='';

//------------------------------------------------------------------------------------------
// 일일자료수집 시작 --> dailyData.php (화면에서 호출)
//------------------------------------------------------------------------------------------- */
function searchDaily() {
	frm = document.myForm;
	startDate=frm.startDate1.value;
	endDate=frm.endDate1.value;
	openBidInfo = frm.openBidInfo.value;
	openBidSeq = frm.openBidSeq.value;
	openBidSeq_tmp = frm.openBidSeq_tmp.value;
	if (openBidInfo == '')
	{
		alert('입찰정보 테이블 명이 없습니다.');
		return;
	}
	if (openBidSeq == '')
	{
		alert('개찰정보 테이블 명이 없습니다.');
		return;
	}
	if (openBidSeq_tmp == '')
	{
		alert('임시 개찰정보 테이블 명이 없습니다.');
		return;
	}
	startDate = startDate.replace(/-/gi,'');
	startDate = startDate.replace(/:/gi,'');
	startDate = startDate.replace(/ /gi,'');
	if (startDate.length == 12) {
		startDate = startDate.substr(0,4) + '-' + startDate.substr(4,2) + '-' + startDate.substr(6,2) + ' ' + startDate.substr(8,2)+ ':' +startDate.substr(10,2);
		frm.startDate1.value = startDate;
	}
	else {alert(startDate + ' 틀린 시간 포맷입니다'); return;}
	endDate = endDate.replace(/-/gi,'');
	endDate = endDate.replace(/:/gi,'');
	endDate = endDate.replace(/ /gi,'');
	if (endDate.length == 12) {
		endDate = endDate.substr(0,4) + '-' + endDate.substr(4,2) + '-' + endDate.substr(6,2) + ' ' + endDate.substr(8,2)+ ':' +endDate.substr(10,2);
		frm.endDate1.value = endDate;
	}
	else {alert(endDate + ' 틀린 시간 포맷입니다'); return;}
	if (startDate>=endDate)
	{
		alert('시작시간이 종료시간보다 작습니다.');
		return;
	}
	 /* if (endDate.length == 9) endDate += ' 23:59';
	if (startDate.length != 16)
	{
		alert('시작일시를 바르게 입력하세요. yyyy-mm-dd hh:mm');
		return;
	}
	if (endDate.length != 16)
	{
		alert('종료일시를 바르게 입력하세요. yyyy-mm-dd hh:mm');
		return;
	} */
	noRow = 0;
	noSeq = 0;
	doingsw = true;
	//--------------------------------
	// 입찰정보 (from~to) 수집  
	//--------------------------------
	url = '/g2b/datas/dailyDataSearch.php?startDate='+startDate+'&endDate='+endDate + '&openBidInfo='+openBidInfo + '&openBidSeq='+openBidSeq_tmp;
	move();
	//document.getElementById('btn').style.display = 'none';
	console.log(url);
	//alert(url);
	getAjax(url,searchDaily2);
}

// daily Data 받아서 화면 (table) 표시 
function searchDaily2(data) {
	if (doingsw == false) return;

	//clog("searchDaily2 data = "+noRow);
	document.getElementById('tablist').innerHTML = data;
	setTotalRecords('specData','totalRecords');
	//clog("searchDaily2-1 data = "+noRow);
	insertSeq();
}

var insIdx=0;
var insTable = 'specData';
var noRow = 0;
var noSeq = 0;
function insertSeq() {
	insTable = document.getElementById('specData');
	if (insTable == null) return;
	
	//테이블 Row 갯수 확인  
	noRow = insTable.rows.length;
	//clog("data = "+noRow);
	if (noRow<2)
	{
		clog("data가 없습니다.");
		move_stop();
		moved_tmp('');
		return;
	}
	insIdx=1;
	insertSeq2();
	//clog('lth='+lth);
}

//-----------------------------------------
// noRow 갯수 만큼  dailyDataInsSeq.php call
//-----------------------------------------
function insertSeq2() {
	if (insIdx < noRow) // noRow
	{
		bidNtceNo = insTable.rows[insIdx].cells[1].innerHTML.split('-')[0];
			bididx = insTable.rows[insIdx].cells[7].innerHTML;
			pss = insTable.rows[insIdx].cells[6].innerHTML;
			//clog('noRow='+noRow+'/'+bidNtceNo+'/'+bididx+'/'+insIdx);
			url = '/g2b/datas/dailyDataInsSeq.php?bidNtceNo='+bidNtceNo+'&openBidInfo='+openBidInfo + '&openBidSeq='+openBidSeq_tmp+'&bididx='+bididx+'&pss='+pss;

			// clog ("ln1106::url=" + url);
			getAjax(url,searchDaily3);
	}
}

//---------------------------------------------
// dailyDataInsSeq.php에서 받은  Data를 Grid에 표시 
//---------------------------------------------
function searchDaily3(data) {
	// 응찰건수 컬럼
	//clog('searchDaily3 /'+data+'/'+insIdx);
	insTable.rows[insIdx].cells[8].innerHTML = data;
	insIdx ++;
	
	//noSeq += eval(data); // msg 전달하면 에러나서 삭제 -by jsj 
	document.getElementById('proccnt').value=insIdx;
	document.getElementById('seqcnt').value=data;
	
	// noRow 만큼 insertSeq2() call
	if (insIdx < noRow ) {
		if (!doingsw) {
			document.getElementById('seqcnt').value= String(doingsw);
			move_stop();
			searchDaily_tmp(1);	// dalyData.php ::call dailyHandle tmp -> seq
			return;
		}
		insertSeq2();	
	} else { 
		move_stop();
		noRow --;
		//clog('입찰정보= ' + noRow + '건 개찰정보= ' + noSeq + ' 건이 수집되었습니다.'); 
		clog('입찰정보= ' + noRow + ' 건이 수집되었습니다.'); 
		
		//임시테이블 데이타 복제 및 삭제 
		searchDaily_tmp(1);	// dalyData.php ::call dailyHandle tmp -> seq
		return; 
	}
}
function setTotalRecords(tblid,totid) {
	//alert('setTotalRecords/'+tblid+'/'+totid);
	var tbl = document.getElementById(tblid); //'specData');
	if (tbl == null) return;
	var lth = tbl.rows.length - 1;
	//alert('Total Records = '+lth+'/'+document.getElementById(totid).innerHTML);
	document.getElementById(totid).innerHTML = 'Total Records = '+lth;
	document.getElementById('totalcnt').value=lth;
}

/* -------------------------------------------------------------------------
	new Version of get ajax : 20180728 HMJ
-------------------------------------------------------------------------- */
function getAjax(server,client) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		client(this.responseText);
    } else if (this.status == 504) {
		move_stop();
		alert('Time-out Error...');
		return;
    }
  };
  xhttp.open("GET", server, true);
  xhttp.send();
}

function getAjaxPost(server,client,parm) {
	var xhr = new XMLHttpRequest();
				
	xhr.onreadystatechange=function() {
		if (this.readyState == 4 && this.status == 200) {
			client(this.responseText);
		} else if (this.status == 504) {
			alert('Time-out Error...');
			return;
		}
	  };
	xhr.open("POST", server, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8"); 
	xhr.send(parm);
}

/* -------------------------------------------------------------------------------------------
  ajaxJSON	서버에 데이타 요청 & 처리		function ajaxJSON(parm,callback)
-------------------------------------------------------------------------------------------- */
function ajaxJSON(parm,callback) {
	
$.ajax({
	type: "POST",
	url:'bcDispatcher', //'readjson.jsp',               //데이터를 요청할 페이지
	dataType: 'json',                   //데이터 유형
	data:parm, //in_js, //'js_cond='+jscond+'&sqlfile=EMPQuery'+'&invoketest=bncJSONTest.test',  //요청할 페이지에 전송할 파라메터
	error:function(xhr,status,e){       //에러 발생시 처리함수
		var msg = 'Error xhr.status='+xhr.status+' e='+e + ' xhr.responseText='+xhr.responseText;
		//alert('Error xhr.status='+xhr.status+' e='+e + ' xhr.responseText='+xhr.responseText);
		if(xhr.responseText != ""){alert("ERROR ="+ xhr.responseText); errFlag = false;}

		$("#debug_result").val(msg);
		$("#divMsg").hide();
		callback(xhr.status,e);

	},
	success: function(jdata){           //성공시 처리 함수, 인수는 위에서 data를 사용한 경우
		////alert(jdata);        		//data라는 변수명을 하면 에러를 뱉습니다.
		if (parent.DebugSW) {
			timerEnd();
			errFlag = true;
			$("#divMsg").hide();
			json_result_s =new Array();
			$("#debug_result").val(JSON.stringify(jdata));
		}
		callback("0",jdata);
		// jdata 는 JSON object 이다.........................................

		}
});

}

  function closeMe() {
  //alert("메일을 보냈습니다.");
  //history.go(-1);
  window.close();
	}
/* ------------------------------------------------------------------------------------------

------------------------------------------------------------------------------------------- */

function tableToExcel(table, name, filename) {
        let uri = 'data:application/vnd.ms-excel;base64,', 
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><title></title><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>', 
        base64 = function(s) { 
			//return window.btoa(decodeURIComponent(encodeURIComponent(s))) 
			return window.btoa(unescape(encodeURIComponent(s)))
				},         format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; })}
        
        //if (!table) table = document.getElementById(table);
		//clog(document.getElementById(table).innerHTML);
        var ctx = {worksheet: name || 'Worksheet', table: document.getElementById(table).innerHTML}

        var link = document.createElement('a');
        link.download = filename;
        link.href = uri + base64(format(template, ctx));
        link.click();
		alert("filename= '" + filename + "'로 다운로드 폴더에 저장되었습니다.");
}

function SortByDesc(x,y) {
      return ((x.bidClseDt == y.bidClseDt) ? 0 : ((x.bidClseDt < y.bidClseDt) ? 1 : -1 ));
}

// 숫자 타입에서 쓸 수 있도록 format() 함수 추가
Number.prototype.format = function(){
    if(this==0) return 0;
 
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');
 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
    return n;
};


// 문자열 타입에서 쓸 수 있도록 format() 함수 추가
String.prototype.format = function(){
    var num = parseFloat(this);
    if( isNaN(num) ) return "0";
 
    return num.format();
};


var check1 = false;
var check2 = false;
function CheckAll(chkid){
	//chkid2 = chkid.trim;
	//alert('/'+chkid2+'/');
	var chk = document.getElementsByName(chkid);
	//alert('chk0='+chk[0].checked+'/'+chk.length);
	if(chk[0].checked == false){
		//check = true;
		for(var i=0; i<chk.length;i++){                                                                    
			chk[i].checked = true;     //모두 체크
		}
	}else{
		//check = false;
		for(var i=0; i<chk.length;i++){                                                                    
			chk[i].checked = false;     //모두 해제
		}

	}
	
}
/* ---------------------------------------------------------
- 체크된 값만 가져오기 
(결과값은 'value1', 'value2', 'value3', 'value4' 의 형태로 query에서 where  구문의 in 조건에 바로 들어갈수 있도록 출력된다.)
[ex] delete from SampleTable where code in ('value1', 'value2', 'value3', 'value4' );
--------------------------------------------------------- */
function selectCheckedRowSelf(tblnm,chk) {
var trs = "";
var tbl = document.getElementById(tblnm); //.rows[0]
//clog(tbl.innerHTML);
var chk = document.getElementsByName(chk); // 체크박스객체를 담는다 []
var len = chk.length;   //체크박스의 전체 개수
var checkRow = '';      //체크된 체크박스의 value를 담기위한 변수
var checkCnt = 0;       //체크된 체크박스의 개수
var checkLast = '';     //체크된 체크박스 중 마지막 체크박스의 인덱스를 담기위한 변수
var rowid = '';         //체크된 체크박스의 모든 value 값을 담는다
var cnt = 0; 

for(var i=0; i<len; i++){

	if(chk[i].checked == true){
		checkCnt++;        //체크된 체크박스의 개수
		checkLast = i;     //체크된 체크박스의 인덱스
	}
} 
if (checkCnt == 0)
{
	//alert('선택된 항목이 없습니다.');
	//window.close(); //
	return false;
}


for(var i=0; i<len; i++){

	if(chk[i].checked == true){  //체크가 되어있는 값 구분
		checkRow = chk[i].value;

		if(checkCnt == 1){                  //체크된 체크박스의 개수가 한 개 일때,
			rowid += "'"+checkRow+"'";      //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
		}else{                              //체크된 체크박스의 개수가 여러 개 일때,
			if(i == checkLast){             //체크된 체크박스 중 마지막 체크박스일 때,
			rowid += "'"+checkRow+"'";  	//'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
			}else{
			rowid += "'"+checkRow+"',";	 	//'value',의 형태 (뒤에 ,(콤마)가 붙게)         			
			}
		}
	cnt++;
	checkRow = '';    //checkRow초기화.
	//clog('i='+i);
	trss = remove1stTD(tbl.rows[i+1].outerHTML);
	//trss = remove2ndA2(trss);
	trss = removeEven(trss);
	trss = trss.replace(/viewscs/g,'viewscsOpener');
	//trss = trss.replace(/onclick=\'viewDtl("/g,'href=
	trs += trss;
	//clog(i+"줄 checked."+  " trs="+ trs);
	}
	//alert(rowid);    //'value1', 'value2', 'value3' 의 형태로 출력된다.
  }
  return trs;
}
/* --------------------------------------------------------------------------------------------------------------------------------------------------
기능 : object 내용보기
-------------------------------------------------------------------------------------------------------------------------------------------------- */
function viewObject(obj) {
	try
	{
		var txtValue;
		for(var x in obj) { txtValue += [x, obj[x]]+"\n"; }
		clog("viewObject " +txtValue);
	} catch (e) {}
	
	try
	{
		txtValue = "";
		for(var x in obj.target) { txtValue += [x, obj[x]]+"\n"; }
		clog("viewObject target " +txtValue);
	} catch (e) {}

	try
	{
		txtValue = "";
		for(var x in obj.currentTarget) { txtValue += [x, obj[x]]+"\n"; }
		clog("viewObject currentTarget " +txtValue);
	} catch (e) {}

	try
	{
		txtValue = "";
		for(var x in obj.delegateTarget) { txtValue += [x, obj[x]]+"\n"; }
		clog("viewObject delegateTarget " +txtValue);
	} catch (e) {}
	
	
	
	
}
/* --------------------------------------------------------------------------------------------------------------------------------------------------
기능 : selectCheckedRowSelf2 table 에서 체크된 row 가져오기
-------------------------------------------------------------------------------------------------------------------------------------------------- */
function selectCheckedRowSelf2(tblnm,chk) {
	var trs = "";
	var tbl = document.getElementById(tblnm); //.rows[0]
	//clog(tbl.innerHTML);
	var chk = document.getElementsByName(chk); // 체크박스객체를 담는다 []
	var len = chk.length;    //체크박스의 전체 개수
	var checkRow = '';      //체크된 체크박스의 value를 담기위한 변수
	var checkCnt = 0;        //체크된 체크박스의 개수
	var checkLast = '';      //체크된 체크박스 중 마지막 체크박스의 인덱스를 담기위한 변수
	var rowid = '';             //체크된 체크박스의 모든 value 값을 담는다
	var cnt = 0; 

	try
	{
		clog(tbl.rows.length);
		//viewObject(tbl.rows[0]);
	}
	catch (ex) { return false; }

	rowlen = tbl.rows.length;
	//if (rowlen>5) rowlen=5;
	//clog('cells.length='+tbl.rows[0].cells.length);

	for(var i=0; i<len; i++){
		if(chk[i].checked == true){
			checkCnt++;        //체크된 체크박스의 개수
			checkLast = i;     //체크된 체크박스의 인덱스
		}
	} 
	if (checkCnt == 0)
	{
		//alert('선택된 항목이 없습니다.');
		//window.close(); //
		return false;
	}
	trss = '';
	for(var i=1; i<rowlen; i++){
		if (chk[i-1].checked == true)
		{
			trss += '<tr>';
			//<a onclick="viewDtl(&quot;http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180911471&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=5&quot;)">20180911471-00</a>
			//<a onclick='viewDtl("http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180911471&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=5")'>20180911471-00</a>
			td = tbl.rows[i].cells[2].innerHTML; // 공고번호
			
			td1 = td.indexOf('onclick');
			td2 = td.indexOf('>');
			td3 = td.indexOf('http:');
			td4 = td.indexOf('ClCd=5');
			//E011809159-00	http://www.g2b.go.kr:8101/ep/tbid/serviceBidDtl.do?tbidno=20180905349&bidseq=00
			//20180901241 - 00	http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180901241&bidseq=00&releaseYn=Y&taskClCd=5

			if (td4<1 || td4 >120)
			{
				td4 = td.indexOf('bidseq=');
				td5 = td.substr(td3,td4-td3+9);
			} else td5 = td.substr(td3,td4-td3+6);

			td1 = td.indexOf('</a>');
			no1 = td.substr(td2+1,td1-td2);
			no2 = no1.split('-'); // 공고번호
			td6 = '<td style="text-align:center;text-decoration:none;"><a href="'+td5+'">'+td.substr(td2+1)+'</td>';
			//clog('공고번호='+td5+'/'+td.substr(td2+1));
			
			trss += td6;
			trss += '<td>'+tbl.rows[i].cells[2].innerHTML+'</td>'; // 공고명
			trss += '<td style="text-align:right">'+tbl.rows[i].cells[3].innerHTML+'</td>'; // 추정가격
			trss += '<td style="text-align:center">'+tbl.rows[i].cells[4].innerHTML+'</td>'; // 공고일시

			//<a onclick="viewscs(&quot;부산광역시교육청 부산국제고등학교&quot;)">부산광역시교육청 부산국제고등학교</a> 수요기관
			td = tbl.rows[i].cells[6].innerHTML; // 수요기관
			td1 = td.indexOf('>');
			td2 = td.indexOf('</');
			dminsttNm = td.substr(td1+1,td2-td1-1);
			td = viewscs1(dminsttNm);
			//clog('dminsttNm='+dminsttNm);
			trss += '<td style="text-align:left;text-decoration:none;">'+td+'</td>'; // 수요기관

			//<a onclick="viewRslt(&quot;20180911471&quot;,&quot;00&quot;,&quot;2019-09-18 10:00:00&quot;,&quot;용역&quot;)">2019-09-18</a> 마감일
			td = tbl.rows[i].cells[7].innerHTML; // 마감일
			td1 = td.indexOf('>');
			td2 = td.indexOf('</');
			dt = td.substr(td1+1,td2-td1+1);
			pss = '용역';
			if (td.indexOf('물품'>0)) pss = '물품';
			else if (td.indexOf('공사'>0)) pss = '공사';

			td = viewRslt3(no2[0],'00',dt,pss); // viewRslt3(bidNtceNo,bidNtceOrd,dt,pss)
			trss += '<td style="text-align:center;text-decoration:none;"><a href="'+td+'"</a>'+dt+'</td>'; // 마감일
			trss += '</tr>';

			//clog('i='+i+' '+trss);
			
		}
		
	}
	return trss;

	/*




	for(var i=0; i<len; i++){

		if(chk[i].checked == true){  //체크가 되어있는 값 구분
			checkRow = chk[i].value;

			if(checkCnt == 1){                            //체크된 체크박스의 개수가 한 개 일때,
				rowid += "'"+checkRow+"'";        //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
			}else{                                            //체크된 체크박스의 개수가 여러 개 일때,
				if(i == checkLast){                     //체크된 체크박스 중 마지막 체크박스일 때,
				rowid += "'"+checkRow+"'";  //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
				}else{
				rowid += "'"+checkRow+"',";	 //'value',의 형태 (뒤에 ,(콤마)가 붙게)         			
				}
			}
		cnt++;
		checkRow = '';    //checkRow초기화.
		//clog('i='+i);
		trss = remove1stTD(tbl.rows[i+1].outerHTML);
		//trss = remove2ndA2(trss);
		trss = removeEven(trss);
		//trss = trss.replace(/viewscs/g,'viewscsOpener');
		//clog('trss='+trss);
		bidno=getbidno(trss);
		bid = bidno.split('-',99);
		//clog('bid='+bid.length);
		if (bid.length>1)
		{
			
			bidseq = bidno[bidno.length-1];
			bidn = '';
			for (var k=0;k<bid.length-1 ;k++ )
			{
				clog('k='+k+'/'+bid[k]);
				bidn += bid[k]+'-';
			}
			bidno = bidn.substr(0,bidn.length-1);
		}
		else bidseq=getbidseq(trss);
		pss=getitem(trss);
		dminsttNm=getdminsttNm(trss);
		clog('bidno='+bidno+' bidseq='+bidseq+' pss='+pss+' dminsttNm='+dminsttNm);

		trss = relaceUrl1(trss,bidno,bidseq); // 공고번호 링크
		trss = relaceUrl2(trss,dminsttNm); // 수요기관 링크
		//clog('bidno='+bidno+' biidseq='+bidseq);
		trss = relaceUrl3(trss,bidno,bidseq,pss); // 마감일시 링크
		if (brswer == 'ie' || brswer == 'edge') trs += '<tr><td style="text-align: center;"><a '+trss;
		else trs += trss;
		clog(i+"줄 checked."+  " trs="+ trss);
		}
		//alert(rowid);    //'value1', 'value2', 'value3' 의 형태로 출력된다.
	}
	return trs;
  */
}
function selectCheckedRowSelf2_0(tblnm,chk) {
var trs = "";
var tbl = document.getElementById(tblnm); //.rows[0]
//clog(tbl.innerHTML);
var chk = document.getElementsByName(chk); // 체크박스객체를 담는다 []
var len = chk.length;    //체크박스의 전체 개수
var checkRow = '';      //체크된 체크박스의 value를 담기위한 변수
var checkCnt = 0;        //체크된 체크박스의 개수
var checkLast = '';      //체크된 체크박스 중 마지막 체크박스의 인덱스를 담기위한 변수
var rowid = '';             //체크된 체크박스의 모든 value 값을 담는다
var cnt = 0; 

for(var i=0; i<len; i++){

	if(chk[i].checked == true){
		checkCnt++;        //체크된 체크박스의 개수
		checkLast = i;     //체크된 체크박스의 인덱스
	}
} 
if (checkCnt == 0)
{
	//alert('선택된 항목이 없습니다.');
	//window.close(); //
	return false;
}


for(var i=0; i<len; i++){

	if(chk[i].checked == true){  //체크가 되어있는 값 구분
		checkRow = chk[i].value;

		if(checkCnt == 1){                            //체크된 체크박스의 개수가 한 개 일때,
			rowid += "'"+checkRow+"'";        //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
		}else{                                            //체크된 체크박스의 개수가 여러 개 일때,
			if(i == checkLast){                     //체크된 체크박스 중 마지막 체크박스일 때,
			rowid += "'"+checkRow+"'";  //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
			}else{
			rowid += "'"+checkRow+"',";	 //'value',의 형태 (뒤에 ,(콤마)가 붙게)         			
			}
		}
	cnt++;
	checkRow = '';    //checkRow초기화.
	//clog('i='+i);
	trss = remove1stTD(tbl.rows[i+1].outerHTML);
	//trss = remove2ndA2(trss);
	trss = removeEven(trss);
	//trss = trss.replace(/viewscs/g,'viewscsOpener');
	//clog('trss='+trss);
	bidno=getbidno(trss);
	bid = bidno.split('-',99);
	//clog('bid='+bid.length);
	if (bid.length>1)
	{
		
		bidseq = bidno[bidno.length-1];
		bidn = '';
		for (var k=0;k<bid.length-1 ;k++ )
		{
			clog('k='+k+'/'+bid[k]);
			bidn += bid[k]+'-';
		}
		bidno = bidn.substr(0,bidn.length-1);
	}
	else bidseq=getbidseq(trss);
	pss=getitem(trss);
	dminsttNm=getdminsttNm(trss);
	//clog('bidno='+bidno+' bidseq='+bidseq+' pss='+pss+' dminsttNm='+dminsttNm);

	trss = relaceUrl1(trss,bidno,bidseq); // 공고번호 링크
	trss = relaceUrl2(trss,dminsttNm); // 수요기관 링크
	//clog('bidno='+bidno+' biidseq='+bidseq);
	trss = relaceUrl3(trss,bidno,bidseq,pss); // 마감일시 링크
	if (brswer == 'ie' || brswer == 'edge') trs += '<tr><td style="text-align: center;"><a '+trss;
	else trs += trss;
	clog(i+"줄 checked."+  " trs="+ trss);
	}
	//alert(rowid);    //'value1', 'value2', 'value3' 의 형태로 출력된다.
  }
  return trs;
}
function getbidno(str) {
	i=str.indexOf("bidno=");
	if (i>0)
	{
		k= str.indexOf("&",i);
		bidno=str.substr(i+6,k-i-6);
		return bidno;
	} else {
		i=str.indexOf(")\">");
		k=str.indexOf("</a>",i);
		bidno = str.substr(i+3,k-i-3);
		return bidno;
	}
	
}
function getbidseq(str) {
	i=str.indexOf("bidseq=");
	if (i<0) return '';

	k= str.indexOf("&",i);
	bidseq=str.substr(i+7,k-i-7);
	return bidseq;
}
// brswer = 'ie'
function getitem(str) {
	var pss = "";
	i=str.indexOf("title=");
	k= str.indexOf("\"",i+8);
	//alert('i='+i+' k='+k+' str='+str.substr(i,80));
	pss=str.substr(i+7,2); //k-i-7);
	if (pss.indexOf("native code")>0) pss = "";
	//clog('str='+str+' pss='+pss);
	
	return pss;
}
function getdminsttNm(str) {
	i=str.indexOf("viewscs(");
	k = 0;
	if (brswer == 'ie') k = str.indexOf("\"",i+12);
	else k= str.indexOf("&quot;",i+12);
	var dminsttNm = "";
	dminsttNm=str.substr(i+9,k-i-9); // dminsttNm=str.substr(i+14,k-i-14);
	if (dminsttNm.indexOf("native code")>0) dminsttNm = "";
	//clog('brswer='+brswer+' dminsttNm='+dminsttNm+' i='+i+' k='+k+' str='+str);
	
	return dminsttNm;
	
}
// 공고번호 링크
function relaceUrl1(str,bidno,bidseq) {
	//
	//trss=<tr><td style="text-align: center;"><a onclick="viewDtl(&quot;&quot;)">UMM0393-1</a></td><td title="용역">부산 북항지역 우암선 민·군 공동 활용방안 연구용역</td><td align="right"></td><td style="text-align: center;">2018-05-14 00:00:00</td><td><a onclick="viewscs(&quot;국군재정관리단&quot;)">국군재정관리단</a></td><td style="text-align: center;"><a onclick="viewRslt(&quot;UMM0393&quot;,&quot;1&quot;,&quot;2018-05-25 14:00:00&quot;,&quot;용역&quot;)">2018-05-25 14:00:00</a></td></tr>
	while (str.indexOf('onclick=\"viewDtl')>=0)
	{
		i=str.indexOf('onclick=\"viewDtl');
		j=str.indexOf('bidno=',i);
		k = str.indexOf('&',j);
		bidno1 = str.substr(j+6,k-j-6);
		clog('i='+i+' j='+j+' k='+k+' bidno='+bidno+' bidno1='+bidno1);
		
		j=str.indexOf('bidseq=',i);
		bidseq1 = str.substr(j+7,2);
		//str = str.substr(0,i)+'href="http://www.g2b.go.kr:8101/ep/tbid/facilBidDtl.do?tbidno='+bidno+"&bidseq="+bidseq+"\"";
		if (j<0)
		{
			j=str.indexOf(')',i);
			str =str.substr(0,i)+'href="http://www.g2b.go.kr:8101/ep/tbid/facilBidDtl.do?tbidno='+bidno+"&bidseq="+bidseq+"\""+str.substr(j+2);
		} else {
			k = str.indexOf('>',j);
			//bidseq = str.substr(j+7,k-j-7);
			//clog('i='+i+' j='+j+' k='+k+' bidseq='+bidseq);
			str =str.substr(0,i)+'href="http://www.g2b.go.kr:8101/ep/tbid/facilBidDtl.do?tbidno='+bidno1+"&bidseq="+bidseq1+"\""+str.substr(k); //+37);
		}
	}
	//clog('relaceUrl1='+str);
	return str;
}
//수요기관 링크
function relaceUrl2(str,dminsttNm) {
	
	//kwd = encodeURIComponent(document.getElementById('kwd').value.replace(/ /g,'+'));
	//dminsttNm = encodeURIComponent(dminsttNm);
	kwd = document.getElementById('kwd').value.replace(/ /g,'+');
	//dminsttNm = dminsttNm;
	var d = new Date();
	endDate = d.format("yyyy-MM-dd");
	//startDate = dateAddDel(endDate, -6, 'm').replace(/-/g,''); 
	startDate = dateAddDel(endDate, -1, 'y').replace(/-/g,''); // 5년 전
	endDate = endDate.replace(/-/g,'');
	//alert(endDate+'/'+startDate);

	inqryBgnDt = startDate; //document.getElementById('startDate').value.replace(/-/g,'');
	inqryEndDt = endDate; //document.getElementById('endDate').value.replace(/-/g,'');
	//kwd = kwd.replace(/ /g,'+');
	//alert('viewscs kwd='+kwd);
	if (dminsttNm == '') url = 'http://uloca.net/g2b/getBid.php?kwd='+kwd+'&dminsttNm=&inqryBgnDt='+inqryBgnDt+'&inqryEndDt='+inqryEndDt;
	else url = 'http://uloca.net/g2b/getBid.php?kwd='+kwd+'&dminsttNm='+dminsttNm+'&inqryBgnDt='+inqryBgnDt+'&inqryEndDt='+inqryEndDt;
	//clog(url);
	//<a onclick="viewscsOpener(&quot;한국수자원공사 김천부항지사&quot;)">
	//clog('viewscs url='+url);
	i=str.indexOf("onclick=\"viewscs");
	k= str.indexOf("&quot;",i+20);
	if (brswer == 'ie') str=str.substr(0,i)+'href=\"'+url+'\">'+str.substr(k+9+28);
	else str=str.substr(0,i)+'href=\"'+url+'\">'+str.substr(k+9);
	clog('relaceUrl2='+str);
	return str;

}
// 마감일시 링크
function relaceUrl3(str,bidno,bidseq,pss) {
	//<a onclick="viewRslt(&quot;20180538945&quot;,&quot;00&quot;,&quot;2018-06-14 12:00:00&quot;,&quot;물품&quot;)">2018-06-14 12:00:00</a>
	//<a href='http://www.g2b.go.kr:8101/ep/tbid/facilBidDtl.do?tbidno=20180607807&bidseq=00'>
	//clog('relaceUrl3='+str);
	while (str.indexOf('onclick=\"viewRslt')>=0)
	{
		i=str.indexOf('onclick=\"viewRslt');
		
		k = str.indexOf('>',i);
		
		str = str.substr(0,i)+'href="http://uloca.net/g2b/bidResult.php?bidNtceNo='+bidno+"&bidNtceOrd="+bidseq+"&pss="+pss+"\">"+str.substr(k+1);

	}
	//clog('relaceUrl3='+str);
	return str;
}
function selectOpenerCheckedRow() {
var trs = "";
var tbl = window.opener.document.getElementById("bidData"); //.rows[0]
//clog(tbl.innerHTML);
var chk = window.opener.document.getElementsByName("chk"); // 체크박스객체를 담는다 []
var len = chk.length;    //체크박스의 전체 개수
var checkRow = '';      //체크된 체크박스의 value를 담기위한 변수
var checkCnt = 0;        //체크된 체크박스의 개수
var checkLast = '';      //체크된 체크박스 중 마지막 체크박스의 인덱스를 담기위한 변수
var rowid = '';             //체크된 체크박스의 모든 value 값을 담는다
var cnt = 0; 

for(var i=0; i<len; i++){

	if(chk[i].checked == true){
		checkCnt++;        //체크된 체크박스의 개수
		checkLast = i;     //체크된 체크박스의 인덱스
	}
} 
if (checkCnt == 0)
{
	alert('선택된 항목이 없습니다.');
	window.close(); //return;
}


for(var i=0; i<len; i++){

	if(chk[i].checked == true){  //체크가 되어있는 값 구분
		checkRow = chk[i].value;

		if(checkCnt == 1){                            //체크된 체크박스의 개수가 한 개 일때,
			rowid += "'"+checkRow+"'";        //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
		}else{                                            //체크된 체크박스의 개수가 여러 개 일때,
			if(i == checkLast){                     //체크된 체크박스 중 마지막 체크박스일 때,
			rowid += "'"+checkRow+"'";  //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
			}else{
			rowid += "'"+checkRow+"',";	 //'value',의 형태 (뒤에 ,(콤마)가 붙게)         			
			}
		}
	cnt++;
	checkRow = '';    //checkRow초기화.
	trss = remove1stTD(tbl.rows[i+1].outerHTML);
	//trss = remove2ndA(trss);
	trss = removeEven(trss);
	trs += trss;
	//clog(i+"줄 checked."+  " trs="+ trs);
	}
	//alert(rowid);    //'value1', 'value2', 'value3' 의 형태로 출력된다.
  }
  return trs;
}


function remove1stTD(trstr) {
	// <tr><td scope="row"><input id="chk" name="chk" type="checkbox"></td><td><a onclick="viewDtl(&quot;http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180703853&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1&quot;)">20180703853-00</a></td><td>부산 1호선 전동차 48량 제작구입</td><td align="right">52,174,545,455</td><td>2018-07-04 18:06:40</td><td><a onclick="viewscs(&quot;부산교통공사&quot;)">부산교통공사</a></td><td>2018-08-16 14:00:00</td></tr>

	s = trstr.indexOf("<td");
	e = trstr.indexOf("</td");
	str = trstr.substring(0,s)+trstr.substring(e+5);
	return str;
}
function remove2ndA(trstr) {
	// <tr><td scope="row"><input id="chk" name="chk" type="checkbox"></td><td><a onclick="viewDtl(&quot;http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180703853&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1&quot;)">20180703853-00</a></td><td>부산 1호선 전동차 48량 제작구입</td><td align="right">52,174,545,455</td><td>2018-07-04 18:06:40</td><td><a onclick="viewscs(&quot;부산교통공사&quot;)">부산교통공사</a></td><td>2018-08-16 14:00:00</td></tr>
	
	s = trstr.indexOf("viewscs");
	e = trstr.indexOf(")\">",s);
	//clog('s='+s+' e='+e);
	str = trstr.substring(0,s-12)+trstr.substring(e+3); //-s+12);
	return str;
}
function remove2ndA2(trstr) {
	// <tr><td scope="row"><input id="chk" name="chk" type="checkbox"></td><td><a onclick="viewDtl(&quot;http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180703853&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1&quot;)">20180703853-00</a></td><td>부산 1호선 전동차 48량 제작구입</td><td align="right">52,174,545,455</td><td>2018-07-04 18:06:40</td><td><a onclick="viewscs(&quot;부산교통공사&quot;)">부산교통공사</a></td><td>2018-08-16 14:00:00</td></tr>
	
	s = trstr.indexOf("viewscs");
	e = trstr.indexOf(")\'>",s); // trstr.indexOf(")\">",s); )'>
	if (e < 1) e = trstr.indexOf(")\">",s);
	
	//clog('s='+s+' e='+e);
	str = trstr.substring(0,s-12)+trstr.substring(e+3); //-s+12);
	return str;
}
function replaceAll(str, searchStr, replaceStr) {
  return str.split(searchStr).join(replaceStr);
}
function removeEven(trstr) {
	return replaceAll(trstr,'class="even"', '');
}
function selectCheckedRow() {

var chk = document.getElementsByName("chk"); // 체크박스객체를 담는다 []
var len = chk.length;    //체크박스의 전체 개수
var checkRow = '';      //체크된 체크박스의 value를 담기위한 변수
var checkCnt = 0;        //체크된 체크박스의 개수
var checkLast = '';      //체크된 체크박스 중 마지막 체크박스의 인덱스를 담기위한 변수
var rowid = '';             //체크된 체크박스의 모든 value 값을 담는다
var cnt = 0; 

for(var i=0; i<len; i++){

	if(chk[i].checked == true){
		checkCnt++;        //체크된 체크박스의 개수
		checkLast = i;     //체크된 체크박스의 인덱스
	}
} 

for(var i=0; i<len; i++){

	if(chk[i].checked == true){  //체크가 되어있는 값 구분
		checkRow = chk[i].value;

		if(checkCnt == 1){                            //체크된 체크박스의 개수가 한 개 일때,
			rowid += "'"+checkRow+"'";        //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
		}else{                                            //체크된 체크박스의 개수가 여러 개 일때,
			if(i == checkLast){                     //체크된 체크박스 중 마지막 체크박스일 때,
			rowid += "'"+checkRow+"'";  //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
			}else{
			rowid += "'"+checkRow+"',";	 //'value',의 형태 (뒤에 ,(콤마)가 붙게)         			
			}
		}
	cnt++;
	checkRow = '';    //checkRow초기화.

	//clog(i+"줄 checked.");
	}
	//alert(rowid);    //'value1', 'value2', 'value3' 의 형태로 출력된다.
  }
}
//----------------------------
//bitly -by jsj 190325
//----------------------------
function shortURL(link) {
	format = 'txt';
	login = 'enable21';
	apiKey = 'R_cb94c2cd92bba988984791acf7704b6e';
	
	bitlyUrl = 'https://api-ssl.bitly.com/v3/shorten?login='+login+'&apiKey='+apiKey+'&longUrl='+encodeURIComponent(link)+'&format='+format;
	//-------------------------------------
	getAjax_bitly(bitlyUrl, callBack_Url);
	//-------------------------------------
}

function getAjax_bitly(bitlyUrl, callBack) {
 	var xhttp = new XMLHttpRequest();
 	xhttp.onreadystatechange = function() {
    	if (this.readyState == 4 && this.status == 200) {
    		callBack(this.responseText);
    	} else if (this.status == 504) {
			alert('Bitly Time-out Error...');
		}
	};
	xhttp.open("GET", bitlyUrl, true);
	xhttp.send();
}

function callBack_Url(data) {
	//prompt('Ctrl+C를 눌러 아래의 URL을 복사하세요:', data);
	prompt('Ctrl+C를 눌러 URL을 전달하세요!', data);
}

//----------------------------
// 한국기업데이터(Ked) 기업 검색 -by jsj 190325
//----------------------------
function compKedSearch(compNo){
	user_id = "ulocaonl" + "&process=S"; // ID 
	bzno = "&bzno=" + compNo + "&cono="; // 파라미터 사업자번호 
	jm_no = "&pid_agr_yn=Y&jm_no=E017";  // 전문번호 E017
	//6098164815 제일중앙

	//kedURL = "https://testkedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?user_id="; 
	kedURL = "https://kedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?user_id="; 

	kedURL = kedURL + user_id + bzno + jm_no;
	
	//-------------------------------------
	getAjax_bitly(kedURL, callBack_kedURL);
	//-------------------------------------

}

function callBack_kedURL(data) {
	return data;
}
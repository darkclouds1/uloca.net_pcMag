/* ---------------------------------------------------------------------
 *  Source Path		: /include/JavaScript/GeneralFunc.js
 *  시스템명		: 
 * 업무대중소분류	:
 * 프로그램설명		: java script utility
 * 파일명			: GeneralFunc.js
 * Called By		: All
 * Calling			:
 *  작성자			: 황명제
 *  작성날짜		: 2008. 05. 24
 *
 * ----------------------------------------------------------------------
 * 변경사항
 * 변경일자	변경자		내용
 * -------		-----			------------------------------
 *  */

 String.prototype.trim = function() {
		var a = this;
		var search = 0;
		
		while ( a.charAt(search) == ' ') search += 1;
		a = a.substring(search, (a.length));
		search = a.length - 1;
		
		while (a.charAt(search) ==' ') search -= 1;
		return a.substring(0, search + 1);
	}
 /* =============================================================================================================
	Layer 관련 함수
=============================================================================================================== */

var openingLayer = 0;
var t_height, t_width;


function openLayer(layerName) {
	oLS = document.getElementById(layerName).style;
	if (oLS.display=="inline") {
		oLS.display="none";
		return;
	}
	if(!openingLayer) {
		closeLayer(layerName);
		t_height = parseInt(oLS.height);
		t_width = parseInt(oLS.width);
		oLS.height = 10;
		oLS.width = 10;
		openingLayer = 1;
		oLS.display = "inline";
		movingLayer(layerName);
	}
	
}
function movingLayer(layerName) {
	var steps = 2.5;
	oLS = document.getElementById(layerName).style;
	tmpx = parseInt(oLS.height);
	tmpy = parseInt(oLS.width);
	if(t_height - tmpx > 1) {
		oLS.height = tmpx + (t_height - tmpx) / steps + 1;
		oLS.width = tmpy + (t_width - tmpy) / steps + 1;
		window.setTimeout("movingLayer('" + layerName + "')", 20);
	}
	else {
		oLS.height = t_height;
		oLS.width = t_width;
		openingLayer = 0;
	}
}

function closeLayer(layerName) {
	var layerNames = new Array(layerName );  // new Array("newsf" ); 
	for(i in layerNames) {
		document.getElementById(layerNames[i]).style.display = "none";
	}
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
	//alert(thisCookie);
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

// this deletes the cookie when called
function DelCookie(name) {
	//Delete_Cookie(name,"/","");
	document.cookie = name + "= ; path=/;expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
function Delete_Cookie( name, path, domain ) {
	if ( Get_Cookie( name ) ) document.cookie = name + "=" +
	( ( path ) ? ";path=" + path : "") +
	( ( domain ) ? ";domain=" + domain : "" ) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}


/*
function getCookie( name ) {
    var start = document.cookie.indexOf( name + "=" );
    var len = start + name.length + 1;
    if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
        return null;
    }
    if ( start == -1 ) return null;
    var end = document.cookie.indexOf( ';', len );
    if ( end == -1 ) end = document.cookie.length;
    return unescape( document.cookie.substring( len, end ) );
}

function setCookie( name, value, expires, path, domain, secure ) {
    var today = new Date();
    today.setTime( today.getTime() );
    if ( expires ) {
        expires = expires * 1000 * 60 * 60 * 24;
    }
    var expires_date = new Date( today.getTime() + (expires) );
    document.cookie = name+'='+escape( value ) +
        ( ( expires ) ? ';expires='+expires_date.toGMTString() : '' ) + //expires.toGMTString()
        ( ( path ) ? ';path=' + path : '' ) +
        ( ( domain ) ? ';domain=' + domain : '' ) +
        ( ( secure ) ? ';secure' : '' );
}
*/

/* =============================================================================================================
	클립보드복사 관련 함수
=============================================================================================================== */

// 클립보드복사
function copyClip(str) { 
	if (window.clipboardData) {
		window.clipboardData.setData("Text", str); 
	} else if (window.netscape) {
		netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect'); 
		
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard); 
		if (!clip) return; 
		
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable); 
		if (!trans) return; 
		
		trans.addDataFlavor('text/unicode'); 
		
		var str = new Object(); 
		var len = new Object(); 
		
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString); 
		
		var copytext = str;  
		str.data = copytext; 
		
		trans.setTransferData("text/unicode",str,copytext.length*2); 
		var clipid = Components.interfaces.nsIClipboard; 
		if (!clipid) return false; 
		
		clip.setData(trans,null,clipid.kGlobalClipboard); 
	} 
	return false; 
 }
/*---------------------------------------------------------------------------------
 *	문자열의 길이를 구하는 함수(한글:2 바이트)
 *	s	: 문자열
 ----------------------------------------------------------------------------------*/
function getByteLength(s){  
	var len = 0;  
	if ( s == null ) return 0;  
	for(var i=0;i<s.length;i++){  
		var c = escape(s.charAt(i));  
		if ( c.length == 1 ) len ++;  
		else if ( c.indexOf("%u") != -1 ) len += 2;  
		else if ( c.indexOf("%") != -1 ) len += c.length/3;  
	}  
	return len;  
}
/* =============================================================================================================
	팝업 관련 함수 및 function Start
=============================================================================================================== */
function determinePopUpAtCenter(height, width)
		{
			var properties= "";
			var leftprop, topprop, screenX, screenY, cursorX, cursorY, padAmt;
			if(navigator.appName == "Microsoft Internet Explorer")
			{
				screenY= document.body.offsetHeight;
				screenX= window.screen.availWidth;
			}
			else
			{
				screenY= window.outerHeight
				screenX= window.outerWidth
			}
			leftvar= (screenX - width) / 2;
			rightvar= (screenY - height) / 2;
			
			if(navigator.appName == "Microsoft Internet Explorer")
			{
				leftprop= leftvar;
				topprop= rightvar;
			}
			else
			{
				leftprop= (leftvar - pageXOffset);
				topprop= (rightvar - pageYOffset);
			}

			if(topprop<100) {
				topprop = topprop + 100;
			}

			properties= properties + ", left = " + leftprop;
			properties= properties + ", top = " + topprop;
			
			return properties;
		}
function determinePopUpAtLeft(width)
		{
			var properties= "";
			var leftprop, topprop, screenX, screenY, cursorX, cursorY, padAmt;
			if(navigator.appName == "Microsoft Internet Explorer")
			{
				screenX= window.screen.availWidth;
			}
			else
			{
				screenX= window.outerWidth
			}
			leftvar= (screenX - width) / 2;
						
			if(navigator.appName == "Microsoft Internet Explorer")
			{
				leftprop= leftvar;
			}
			else
			{
				leftprop= (leftvar - pageXOffset);
			}

			return leftprop;
		}
function determinePopUpAtTop(height)
		{
			var properties= "";
			var leftprop, topprop, screenX, screenY, cursorX, cursorY, padAmt;
			if(navigator.appName == "Microsoft Internet Explorer")
			{
				//screenY= document.body.offsetHeight;
				screenY= window.screen.availHeight;
			}
			else
			{ 
				screenY= window.outerHeight
			}
			rightvar= (screenY - height) / 2;
			
			if(navigator.appName == "Microsoft Internet Explorer")
			{
				topprop= rightvar;
			}
			else
			{
				topprop= (rightvar - pageYOffset);
			}
			if(topprop<100) {
				topprop = topprop + 100;
			}
			return topprop;
		}

function popup(openURL , width, height , scroll, resize, checkNew) 
		{

			if(width== '') {
				width='1020';
			}
			if(height == '') {
				height='640';
			}
			if(scroll == '') {
				scroll='no';
			}
			if(resize == '') {
				resize='no';
			}

			var popName = '';
			if(checkNew != null && checkNew == false) {
				popName = openURL.substring(openURL.lastIndexOf("/")+1,openURL.lastIndexOf("."));
			}

			var properties_common = 'status=no,toolbar=no,location=no,menubar=no,titlebar=no,';
			var properties = 'scrollbars=' + scroll +  ',resizable=' + resize + ',width=' + width + ', height=' + height + determinePopUpAtCenter(height, width);
		
			
			if(openURL != null && openURL != ""){
				
				openURL = trim(openURL);
			}
			else {
				alert("해당 URL 정보가 올바르지 않습니다. 관리자에게 문의하시기 바랍니다.");
				return;
			}
			
			var win = window.open(openURL, popName , properties_common + properties);
			win.focus();
		}

function pdPopupWin(url,pageNo,width,height,initOption,bModal,top,left)
		{
			
			if(pageNo != null && pageNo != "") {
				pageNo = pageNo.replace(/(\[|\])/gi,"");
			}
		  
			if(url == null){
				return;
			}


			var strOptions;
			var nScreenTop;
			var nScreenLeft;
			var url;
			var retValue = "";

			var linkYn = false;


			strOptions = "";

			if ((url == "") && (pageNo == "")) {
				alert("해당 서비스정보가 올바르지 않습니다. 관리자에게 문의하시기 바랍니다.");
				return;
			}



			if(url != null){
				url = trim(url);
			}
			else {
				return;
			}

			if(width == null)
			{
				width = 1020;
			}
			if(height == null)
			{
				height = 640;
			}
			if(pageNo == null)
			{
				strName = "";
			}else{
				strName = pageNo;
			}

			if(top ==null){
				nScreenTop = 768/2 - height/2;
			}else{

				nScreenTop=top;

			}

			if(left==null){
				nScreenLeft  = 1024/2 - width/2;
			}else{
				nScreenLeft=left
			}

			if(height>690)
			{
				//초화면
				nScreenTop=0;
			}

			popupArguments = initOption;

			if(!bModal)
			{
				strOptions = "width="+width+", height="+height+", top=" + nScreenTop + ", left=" + nScreenLeft;
				if(linkYn) {
					strOptions = strOptions + ",status=yes,resizable=yes,menubar=yes, scrollbars=yes, title=yes";
				}else {
					strOptions = strOptions + ",status=no,resizable=no,menubar=no, scrollbars=no, title=no";
				}

				var bObjFind = false;
				var popupWin = null;

				try
				{
					popupWin = window.open(url, strName, strOptions);
					
				

				}
				catch(e)
				{
				}

				if(!linkYn) {

					retValue = popupWin;

				}
			}
			else
			{
				strOptions = "dialogWidth:"+width+"px; dialogHeight:"+height+"px; dialogTop:" + nScreenTop + "px; dialogLeft:" + nScreenLeft + "px;";
				strOptions = strOptions + "status:no;resizable:no;menubar:no; scroll:no; title:no";
				//alert(strOptions);

				if(initOption == null)
				{
					initOption = window;
				}

				retValue = window.showModalDialog(url, initOption, strOptions);
			}

			return retValue;
		}


		
/*---------------------------------------------------------------------------------
 *	팝업창에 전달된 파라미터 가져오기
 ----------------------------------------------------------------------------------*/
function pdPopupArgument()
{
	var arg = null;
	arg = window.dialogArguments;

	if(arg == undefined)
	{
		arg = opener.popupArguments;
	}

	try
	{
		if(arg.Agent != null || arg.Agent != undefined)
		{
//			return null;
		}

		if(arg.id == undefined)
		{
			opener = arg;
			arg = opener.popupArguments;
		}
	}
	catch(e)
	{
	}

	//debugPopupArgument(arg);

	return arg;
}
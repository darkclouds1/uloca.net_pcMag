////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//전역에서 사용될 함수 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* ---------------------------------------------------------------------
 *  Source Path		: /include/javaScript/GlobalFuncHMJ.js
 *  시스템명		: 
 * 업무대중소분류	:
 * 프로그램설명		: java script utility
 * 파일명			: GlobalFuncHMJ.js
 * Called By		: All
 * Calling			:
 *  작성자			: 황명제
 *  작성날짜		: 2008. 05. 16
 *
 * ----------------------------------------------------------------------
 * 변경사항
 * 변경일자	변경자		내용
 * -------		-----			------------------------------
 * 
 
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
//쿠키읽어 오기
function GetCookieMulti(cookieKey)
{
	var retVal = ""
	if(cookieKey == null)
	{
		cookieName = "Oceans";
	}
	else
	{
		cookieName = cookieKey;
	}
	thisCookie = document.cookie.split("; ");
	
	for (i = 0; i < thisCookie.length; i++)
	{
		if (cookieName == thisCookie[i].split("=")[0])
		{
			retVal = thisCookie[i].split("=")[1];
		}
	}
	
	return retVal;
}

//쿠키에 설정 
function SetCookieMulti(cookieVal,cookieKey,cookieExp)
{ 
	if(cookieKey == null)
	{
		cookieName = "Oceans";
	}
	else
	{
		cookieName = cookieKey;
	}
	expireDate = new Date;
		
	if(cookieExp == null) // default 하루
	{
		//expireDate.setMonth(expireDate.getMonth()+6); // 6개월간 쿠키 저장
		expireDate.setDate(exp.getDate()+1); // 현재 시간에서 날자 구하고 유효기간 더해서 쿠키 유효일 설정
	} else {
		expireDate.setDate(exp.getDate()+cookieExp);
	}
	
	document.cookie = cookieName + "=" + cookieVal + "; expires=" + expireDate.toGMTString() ;
	//document.cookie=name+"="+escape(value)+"; path=/; expires="+expires.toGMTString()+"; "; // 쿠키 문자열 설정
} 
 /**
  * 쿠키 삭제
  * @param cookieName 삭제할 쿠키명
  */
 function deleteCookie( cookieName )
 {
  var expireDate = new Date();
 
  //어제 날짜를 쿠키 소멸 날짜로 설정한다.
  expireDate.setDate( expireDate.getDate() - 1 );
  document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString() + "; path=/";
 }
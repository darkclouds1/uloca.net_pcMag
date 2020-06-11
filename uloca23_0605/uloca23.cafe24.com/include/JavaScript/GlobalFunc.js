////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//�������� ���� �Լ� 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* ---------------------------------------------------------------------
 *  Source Path		: /include/javaScript/GlobalFuncHMJ.js
 *  �ý��۸�		: 
 * �������߼Һз�	:
 * ���α׷�����		: java script utility
 * ���ϸ�			: GlobalFuncHMJ.js
 * Called By		: All
 * Calling			:
 *  �ۼ���			: Ȳ����
 *  �ۼ���¥		: 2008. 05. 16
 *
 * ----------------------------------------------------------------------
 * �������
 * ��������	������		����
 * -------		-----			------------------------------
 * 
 
 /* =============================================================================================================
	��Ű ���� �Լ�
=============================================================================================================== */
//��Ű�о� ����
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

//��Ű�� ���� 
function SetCookie(cookieName,cookieVal,cookieExp)
{ 
	
	if(cookieVal == null) return;
	if(cookieName == null) return;

	expireDate = new Date;
		
	if(cookieExp == null) // default �Ϸ�
	{
		//expireDate.setMonth(expireDate.getMonth()+6); // 6������ ��Ű ����
		expireDate.setDate(expireDate.getDate()+1); // ���� �ð����� ���� ���ϰ� ��ȿ�Ⱓ ���ؼ� ��Ű ��ȿ�� ����
	} else {
		expireDate.setDate(expireDate.getDate()+cookieExp);
	}
	
	//document.cookie = cookieName + "=" + cookieVal + "; expires=" + expireDate.toGMTString() ;
	document.cookie = cookieName + "=" + escape(cookieVal)+"; path=/; expires="+expireDate.toGMTString()+"; "; // ��Ű ���ڿ� ����
	
} 
//��Ű�о� ����
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

//��Ű�� ���� 
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
		
	if(cookieExp == null) // default �Ϸ�
	{
		//expireDate.setMonth(expireDate.getMonth()+6); // 6������ ��Ű ����
		expireDate.setDate(exp.getDate()+1); // ���� �ð����� ���� ���ϰ� ��ȿ�Ⱓ ���ؼ� ��Ű ��ȿ�� ����
	} else {
		expireDate.setDate(exp.getDate()+cookieExp);
	}
	
	document.cookie = cookieName + "=" + cookieVal + "; expires=" + expireDate.toGMTString() ;
	//document.cookie=name+"="+escape(value)+"; path=/; expires="+expires.toGMTString()+"; "; // ��Ű ���ڿ� ����
} 
 /**
  * ��Ű ����
  * @param cookieName ������ ��Ű��
  */
 function deleteCookie( cookieName )
 {
  var expireDate = new Date();
 
  //���� ��¥�� ��Ű �Ҹ� ��¥�� �����Ѵ�.
  expireDate.setDate( expireDate.getDate() - 1 );
  document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString() + "; path=/";
 }
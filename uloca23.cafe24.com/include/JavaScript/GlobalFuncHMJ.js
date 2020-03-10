////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//�������� ���� �Լ� 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* ---------------------------------------------------------------------
 *  Source Path		: /include/javaScript/GlobalFunc.js
 *  �ý��۸�		: 
 * �������߼Һз�	:
 * ���α׷�����		: java script utility
 * ���ϸ�			: GlobalFuncHMJ.js
 * Called By		: All
 * Calling			:
 *  �ۼ���			: Ȳ����
 *  �ۼ���¥		: 2008. 01. 20
 *
 * ----------------------------------------------------------------------
 * �������
 * ��������	������		����
 * -------		-----			------------------------------
 * 
function getGlobalPage(obj)						�������� ������ ����
function getParentWindow(obj) {					�������� ������ ����
function determinePopUpAtCenter(height, width)  �����츦 �߾ӿ�
function determinePopUpAtLeft(width)			 =  ���� ���
function determinePopUpAtTop(height)			 =  ���� ���
function sendClick(ScreenID,EventName){
function popup(openURL , width, height , scroll, resize, checkNew) 
function pdPopupWin(url,pageNo,width,height,initOption,bModal,top,left)
function pdPopupArgument()
function debugPopupArgument(obj) {
function calcAge(sno) { 
function convertNation(strCode)
function convertFundUnit(strCode)
function getDate(){
function getYearMonth(){
function getGapYear(gap)
function getGapMonth(gap) {
function add_date(i) // �ż��尡 �� �Լ� ����
function getGapDate(gap){
function getTime(){
function getTime2() {
function trim(targetStr)
function ltrim(targetStr)
function rtrim(targetStr)
function IsValidEmail(email) {
function isNum(v){
function f_validate(obj,iType,operator)
function pdDelZero(par1)
function pdChkTextareaSize(aro_name,ari_max)
function format_phone(str) {
function getFormattedPhone(str) {
function getFormattedPhone2(str) {
function getFormat(str, delim, type, maskYN) {
function f_remove_format(obj)
function getYear()
function moneyFormat(pnum){
function getMoneyFormat(pnum) {
function checkNumber(strNo1, strNo2)
function check_busino(vencod) 
function isFgnNo(fgnno)	
function isJuminNo(strValue1, strValue2){
function addComma(strSrc, nPos, bSymbol)
function setComma(strSrc)
function getStartDate(strDate, strMonth) {
function getEndDate(strDate, strMonth) {
function getMakeDate(strDate, pDay, bFlag) // ��, ��, ��, ����� ���� (�⵵�� �ݵ�� 4�ڸ��� �Է�)
function copyClip(str) { 
function getNumber(str) {
function fncGetCookieInfo(cookieKey)
function fncSetCookieInfo(cookieVal,cookieKey)
function loadJikeob()
function pdFormatObj(str, pType)
function pdSimbolDeleteString(retVal)
function pdJuminFormatObj(str)
function pdSaFormatObj(str)
function pdJuminSaFormatObj(str)
function pdTelFormatObj(str)
function pdDateFormatObj(str)
function pdTimeFormatObj(str)
function getByteLength(s){  
function removeChar(sTemp, sBChar, sAChar)
function displayTelNo(sTelNo)
function fncDebug() {
function pdDbgWin()
function pdInfWin()
function dbgInitPage()
function dbgStartPage()
function preExecute()
function postExecute()
function dbgkeydown()
function dbgScript()
function CheckAgent()
function getCodeArraytoString(str, gubun1, gubun2) {
function textCounter(theField,maxChars)
function cutStr(theField, i, maxChars)
function getByteLength(s){  
function getloc(str,len){
function getSplitData(str, sStart, sSize) {
function pdTelFormatString(pVal)
function convertTel(strValue)
function changeTel1( strValue ) {
function changeTel2( strValue ) {

 -----------------------------------------------------------------------*/

// ---------------------------------------------------------------------------------- �������� ���� function Start	

/* ---------------------------------------------------------------------------------- * 
    �̸� : getGlobalPage �Լ� 
	���� : �� �Լ��� ȣ���Ͽ� Global.js  �� ���� ������ ������ �� �ִ�. 
	�������� ������ ����
----------------------------------------------------------------------------------  */
function getGlobalPage(obj) 
{
		if(obj != null && typeof obj != 'undefined' && obj  != '' && obj.ProgID != null && typeof obj.ProgID != 'undefined' && obj.ProgID == 'inbound_main') {
			return obj;
		}

		var win = getParentWindow(obj);
		if(win == null || typeof win == 'undefined' || win  == '') {
			win = obj;
		}

		if(win != null && typeof win != 'undefined' && win  != '' && win.ProgID != null && typeof win.ProgID != 'undefined' && win.ProgID == 'inbound_main') {
			return win;
		}
		
		
		else {
			while(getParentWindow(win) != null)
			{
				win = getParentWindow(win);
				if(win != null && typeof win != 'undefined' && win  != '' && win.ProgID != null && typeof win.ProgID != 'undefined' && win.ProgID == 'inbound_main') {
					return win;
				}
			}
		}
		return win;
}
// �������� ������ ����
function getParentWindow(obj) {
	if(obj.opener == null || typeof obj.opener == 'undefined' || obj.opener  == '' ) {
		if(obj.parent == obj ) {
			return null;
		}
		else {
			return obj.parent;
		}
	}
	else {
		return obj.opener;
	}
}

// ---------------------------------------------------------------------------------- �������� ���� function End
// ---------------------------------------------------------------------------------- �˾� ���� �Լ� �� function Start	
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
		    prtLog("AGT.WAPP [�˾�] : ��ġ = ["+openURL+"], ũ�� = ["+width+","+height+"]");
			if (pdGlobal.gIsLogTime) chkTimeLog("[�α׽��� --> ȭ���˾����� : " + openURL + "]", 0);

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

				if(openURL.indexOf(".xml") > 0 && openURL.indexOf("?") < 0) {
					openURL = '/websquare/websquare.html?w2xPath=' + openURL;
				}
				else if ( openURL.substring(0,4) != 'http' && openURL.indexOf(".xml") <0 )
				{
					properties_common = 'status=no,toolbar=yes,location=no,menubar=yes,titlebar=yes,';
					properties = 'scrollbars=yes,resizable=yes,width=' + width + ', height=' + height + determinePopUpAtCenter(height, width);

					if(pdGlobal.linkHash == null) {
						//��ũ�����Ͱ� ������ �ٽ� �������������� �ٽ� ��ȸ �Ѵ�.
						pdGlobal.getLinkopenURLData();
					}
					
					if( pdGlobal.linkHash.get(openURL) != null) {
						
						openURL = pdGlobal.linkHash.get(openURL);

						if(pdGlobal.NCSLinkPage != null && typeof(pdGlobal.NCSLinkPage) != "undefined") {
							if(openURL.indexOf("ScreenID=") >0) {
								var str1 = openURL.split("?");
								var str2 = str1[1].split("&");
								var screenID = str2[0].split("=");
								var eventName = str2[1].split("=");
								
								pdGlobal.NCSLinkPage.sendClick( screenID[1] );
								return;
							}
						}						

					}
					else {
						alert("����� ��ũ �����Ϳ� '" + openURL + "' URL�ּҰ� �����ϴ�.");
						return;
					}
				}
			}
			else {
				alert("�ش� URL ������ �ùٸ��� �ʽ��ϴ�. �����ڿ��� �����Ͻñ� �ٶ��ϴ�.");
				return;
			}
			
			var win = window.open(openURL, popName , properties_common + properties);
			if(win != null)
				{
					try
					{
						var strName = win.name;
						pdGlobal.OPEN_OBJECT[pdGlobal.gOpenIndex++] = win;

						prtLog("AGT.WAPP [�˾�] : �˾��ε��� = ["+pdGlobal.OPEN_OBJECT.length+"], �˾�â���� = ["+ pdGlobal.OPEN_OBJECT[pdGlobal.gOpenIndex-1].name+"]");
					}
					catch(e){}
				}
			win.focus();
		}

		function pdPopupWin(url,pageNo,width,height,initOption,bModal,top,left)
		{
			 prtLog("AGT.WAPP [�˾�] : ��ġ = ["+url+"], ������� = ["+ pageNo+"], ũ�� = ["+width+","+height+"]");
			if (pdGlobal.gIsLogTime) chkTimeLog("[�α׽��� --> ȭ���˾����� : " + url + "]", 0);

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
				alert("�ش� ���������� �ùٸ��� �ʽ��ϴ�. �����ڿ��� �����Ͻñ� �ٶ��ϴ�.");
				return;
			}



			if(url != null){
				
				url = trim(url);

				if(url.indexOf(".xml") > 0 && url.indexOf("?") < 0) {
					url = '/websquare/websquare.html?w2xPath=' + url;
				}
				else if ( url.substring(0,4) != 'http' && url.indexOf(".xml") <0 )
				{
					linkYn = true;

					if(pdGlobal.linkHash == null) {
						//��ũ�����Ͱ� ������ �ٽ� �������������� �ٽ� ��ȸ �Ѵ�.
						pdGlobal.getLinkUrlData();
					}
					
					if( pdGlobal.linkHash.get(url) != null) {
						url = pdGlobal.linkHash.get(url);
						
						if(pdGlobal.NCSLinkPage != null && typeof(pdGlobal.NCSLinkPage) != "undefined") {
							if(url.indexOf("ScreenID=") >0) {
								var str1 = url.split("?");
								var str2 = str1[1].split("&");
								var screenID = str2[0].split("=");
								var eventName = str2[1].split("=");
								
								pdGlobal.NCSLinkPage.sendClick( screenID[1] );
								return;
							}
						}
						
					}
					else {
						alert("����� ��ũ �����Ϳ� '" + url + "' URL�ּҰ� �����ϴ�.");
						return;
					}
				}
				


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
				//��ȭ��
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
					
					if(!linkYn) {
						pdGlobal.OPEN_OBJECT[pdGlobal.gOpenIndex++] = popupWin;
						popupWin.dialogArguments = initOption;
						popupWin.focus();
					}
					

				}
				catch(e)
				{
				}

				if(!linkYn) {

					retValue = popupWin;

					if(retValue != null)
					{
						try
						{
							var strName = retValue.name;
							pdGlobal.OPEN_OBJECT[pdGlobal.gOpenIndex++] = retValue;
							
							prtLog("AGT.WAPP [�˾�] : �˾��ε��� = ["+pdGlobal.OPEN_OBJECT.length+"], �˾�â���� = ["+ pdGlobal.OPEN_OBJECT[pdGlobal.gOpenIndex-1].name+"]");
						}
						catch(e){
							alert(e.description);
						}
					}
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
 *	�˾�â�� ���޵� �Ķ���� ��������
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

	debugPopupArgument(arg);

	return arg;
}

// ���ڰ����� �α����
function debugPopupArgument(obj) {
	if (obj == null) { return; }
	if (obj.style == null) { return; }
	
	var sValue = new Array();
	var sText = "";
	
	if (opener != null) { sText = opener.name; }
		
	sValue = obj.style.cssText.split(";");
	
	for (var i=0;i<sValue.length;i++) {
		if (sValue[i].split(":").length > 0 && sValue[i] != "") {
			try{
				WebSquare.logger.printLog("AGT.WAPP [�ڵ�] : ���ڰ�����(style), ȣ����ȭ�� = ["+sText+"], "+ sValue[i].split(":")[0].replace(/ /gi,"") + " = ["+sValue[i].split(":")[1].replace(/ /gi,"")+"]");
			}catch(e){}
		}
	}	
}
	
// ---------------------------------------------------------------------------------- �˾� ���� �Լ� �� function End



// ----------------------------------------------------------------------------------
// �ֹι�ȣ�� ���̰��
// ----------------------------------------------------------------------------------
function calcAge(sno) { 
		var sno1=sno.substr(6,1);
		var sno2=sno.substr(0,2);
		var yr=0;
		var WatchS=new Date();
		sn = Number(sno2);
		if (sno1==3 || sno1==4 || sno1==7 || sno1==8) {
			yr=WatchS.getYear()-2000-sn;
		} else {
			yr=WatchS.getYear()-1900-sn;
		}
		//alert("111 yr="+yr);
		return yr;
	}

// ----------------------------------------------------------------------------- ������



// ȯ�ڵ� - ������
function convertNation(strCode)
{
	var retValue = '';
	switch(strCode)
	{
		case "USD":
			retValue = "�̱�";
			break;
		case "GBP":
			retValue = "����";
			break;
		case "DEM":
			retValue = "����";
			break;
		case "CAD":
			retValue = "ī����";
			break;
		case "FRF":
			retValue = "������";
			break;
		case "ITL":
			retValue = "���¸�";
			break;
		case "CHF":
			retValue = "������";
			break;
		case "HKD":
			retValue = "ȫ��";
			break;
		case "SEK":
			retValue = "������";
			break;
		case "AUD":
			retValue = "ȣ��";
			break;
		case "DKK":
			retValue = "����ũ";
			break;
		case "BEF":
			retValue = "���⿡";
			break;
		case "ATS":
			retValue = "����Ʈ����";
			break;
		case "NOK":
			retValue = "�븣����";
			break;
		case "NLG":
			retValue = "�״�����";
			break;
		case "SAR":
			retValue = "����";
			break;
		case "NOK":
			retValue = "�븣����";
			break;
		case "KWD":
			retValue = "�����Ʈ";
			break;
		case "BHD":
			retValue = "�ٷ���";
			break;
		case "AED":
			retValue = "UAE";
			break;
		case "SGD":
			retValue = "�̰���";
			break;
		case "MYR":
			retValue = "����������";
			break;
		case "JPY":
			retValue = "�Ϻ�";
			break;
		case "NZD":
			retValue = "��������";
			break;
		case "ESP":
			retValue = "������";
			break;
		case "FIM":
			retValue = "�ɶ���";
			break;
		case "CNY":
			retValue = "�߱�";
			break;
		case "THB":
			retValue = "�±�";
			break;
		case "EUR":
			retValue = "E U";
			break;
		case "IDR":
			retValue = "�ε��׽þ�";
			break;
		case "TWD":
			retValue = "�븸";
			break;
		case "PHP":
			retValue = "�ʸ���";
			break;
		case "MXN":
			retValue = "�߽���";
			break;
		case "XAU":
			retValue = "��帮��";
		default:
			retValue=strCode;
			break;
	}
	return retValue;
}

// �ڵ� -> �����ѱ�ȭ (����)
function convertFundUnit(strCode)
{
	switch(strCode)
	{
		case "USD":
			retValue = "�޷�";
			break;
		case "GBP":
			retValue = "�Ŀ��";
			break;
		case "DEM":
			retValue = "����ũ";
			break;
		case "CAD":
			retValue = "�޷�";
			break;
		case "FRF":
			retValue = "����";
			break;
		case "ITL":
			retValue = "100 ����";
			break;
		case "CHF":
			retValue = "����";
			break;
		case "HKD":
			retValue = "�޷�";
			break;
		case "SEK":
			retValue = "ũ�γ�";
			break;
		case "AUD":
			retValue = "�޷�";
			break;
		case "DKK":
			retValue = "ũ�γ�";
			break;
		case "BEF":
			retValue = "����";
			break;
		case "ATS":
			retValue = "�Ǹ�";
			break;
		case "NOK":
			retValue = "ũ�γ�";
			break;
		case "NLG":
			retValue = "���";
			break;
		case "SAR":
			retValue = "����";
			break;
		case "NOK":
			retValue = "ũ�γ�";
			break;
		case "KWD":
			retValue = "�𳪸�";
			break;
		case "BHD":
			retValue = "�𳪸�";
			break;
		case "AED":
			retValue = "������";
			break;
		case "SGD":
			retValue = "�޷�";
			break;
		case "MYR":
			retValue = "��ŰƮ";
			break;
		case "JPY":
			retValue = "100��";
			break;
		case "NZD":
			retValue = "�޷�";
			break;
		case "ESP":
			retValue = "100�似Ÿ";
			break;
		case "FIM":
			retValue = "����Ÿ";
			break;
		case "CNY":
			retValue = "����";
			break;
		case "THB":
			retValue = "��Ʈ";
			break;
		case "EUR":
			retValue = "����ȭ";
			break;
		case "IDR":
			retValue = "100���Ǿ�";
			break;
		case "TWD":
			retValue = "�޷�";
			break;
		case "PHP":
			retValue = "���";
			break;
		case "MXN":
			retValue = "���";
			break;
		default:
			retValue=strCode;
			break;
	}
	return retValue;
}


///////////////////////////////////////////////////////////////////////////////
// �Լ��� : getDate()
// ��  �� :2005.01.28  ���ó�¥ �������� �Լ� �߰�
///////////////////////////////////////////////////////////////////////////////
function getDate(){
	var nowDate = new Date();
	var todayDate="";
	var inYear="";
	var inMonth="";
	var inDate="";
	inYear = nowDate.getYear();
	inMonth = nowDate.getMonth()+1+"";
	if(inMonth.length<2)
	{
		inMonth="0"+inMonth;
	}
	inDate = nowDate.getDate()+"";

	if(inDate.length<2)	{
		inDate="0"+inDate;
	}
	todayDate =inYear+""+inMonth+""+inDate;
	return todayDate;
}


///////////////////////////////////////////////////////////////////////////////
// �Լ��� : getYearMonth()
// ��  �� :2005.01.28  ���� ��� �������� �Լ�
///////////////////////////////////////////////////////////////////////////////
function getYearMonth(){
	var nowDate = new Date();
	var todayDate="";
	var inYear="";
	var inMonth="";
	inYear = nowDate.getYear();
	inMonth = nowDate.getMonth()+1+"";
	if(inMonth.length<2)
	{
		inMonth="0"+inMonth;
	}

	todayDate =inYear+""+inMonth;
	return todayDate;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// �Լ��� : getGapYear(gap)
// ��  �� :2005.01.28  ���ó�¥ ���� gap(������ - , ���Ĵ� +�� �Է�) �⵵ ��ŭ ���̳��� ���ڸ� �������� �Լ�
////////////////////////////////////////////////////////////////////////////////////////////////////
function getGapYear(gap)
{
	var objDate = new Date();			//Date ��ü�� ����ϴ�.
	var cYear = Number(objDate.getYear()) + Number(gap*1);

	var inMonth = objDate.getMonth()+1+"";
	if(inMonth.length<2)
	{
		inMonth="0"+inMonth;
	}
	
	var inDate = objDate.getDate()  +"";
	
	return cYear+""+inMonth+""+inDate;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// �Լ��� : getGapMonth(gap)
// ��  �� :2005.01.28  ���ó�¥ ���� gap(������ - , ���Ĵ� +�� �Է�) ����ŭ ���̳��� ���ڸ� �������� �Լ�
////////////////////////////////////////////////////////////////////////////////////////////////////
function getGapMonth(gap) {
	var nowDate = new Date();
	
	var inYear = nowDate.getYear();
	var inMonth = nowDate.getMonth()+1+"";
	
	if(inMonth.length<2)
	{
		inMonth="0"+inMonth;
	}
	
	var inDate = nowDate.getDate()  +"";
	var strDate = inYear + "" + inMonth + "" + inDate;
	
	var gapDate;
	if(gap > 0) {
		gapDate = getEndDate(strDate,gap);
	}
	else {
		gapDate = getStartDate(strDate,(-1*gap));
	}
	return gapDate;
}





function add_date(i) // �ż��尡 �� �Լ� ����
{
 var currentDate; // ���� ��
 currentDate = this.getDate() + i*1;  // ���� ��¥�� ����(��)�� ��¥�� ���
 this.setDate(currentDate);  // ���� ��¥�� �ٽ� ����
}
Date.prototype.addDate = add_date; // Date ��ü�� �޼��� ����


////////////////////////////////////////////////////////////////////////////////////////////////////
// �Լ��� : getGapDate(gap)
// ��  �� :2005.01.28  ���ó�¥ ���� gap(������ - , ���Ĵ� +�� �Է�) ��ŭ ���̳��� ���ڸ� �������� �Լ�
////////////////////////////////////////////////////////////////////////////////////////////////////
function getGapDate(gap){
	var nowDate = new Date();

	nowDate.addDate(gap);

	var todayDate="";
	var inYear="";
	var inMonth="";
	var inDate="";
	inYear = nowDate.getYear();
	inMonth = nowDate.getMonth()+1+"";
	if(inMonth.length<2)
	{
		inMonth="0"+inMonth;
	}
	inDate = nowDate.getDate()  +"";

	if(inDate.length<2)	{
		inDate="0"+inDate;
	}
	todayDate =inYear+""+inMonth+""+inDate;
	return todayDate;
}


///////////////////////////////////////////////////////////////////////////////
// �Լ��� : getTime()
// ��  �� :2005.01.28  ����ð� �������� �Լ� �߰�
///////////////////////////////////////////////////////////////////////////////
function getTime(){
	var currentTime = new Date();
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	var seconds = currentTime.getSeconds();
	var runTime="";
	if (hours <= 9){
	  hours = "0" + hours;
	}
	if (minutes <= 9){
	  minutes = "0" + minutes;
	}
	if (seconds <= 9){
	  seconds = "0" + seconds;
	}
	runTime = hours+""+minutes+""+seconds;
	return runTime;
}

//HH:MM:SS
function getTime2() {
	var ptime = getTime();
	var ctime = "";
	ctime = ptime.substring(0,2) + ":" + ptime.substring(2,4) + ":" + ptime.substring(4,6);
	return ctime;
}


///////////////////////////////////////////////////////////////////////////////
// �Լ��� : trim(string) 2005.02.01
// ��  �� : ���ڿ����� ������ �����ϰ� ��ȯ�Ѵ�.
///////////////////////////////////////////////////////////////////////////////
function trim(targetStr)
{
	try
	{
		//return rtrim(ltrim(targetStr));
		if(typeof(targetStr) == "undefined") {
			return "";
		} else {
			return rtrim(ltrim(targetStr));
		}
	}
	catch(e)
	{
		return targetStr;
	}
 }

///////////////////////////////////////////////////////////////////////////////
// �Լ��� : ltrim(string) 2005.02.01
// ��  �� : ���ڿ����� ���� ���鸸�� �����ϰ� ��ȯ�Ѵ�.
///////////////////////////////////////////////////////////////////////////////
 function ltrim(targetStr)
{
	try
	{
		while(targetStr.substring(0,1)==" ") {
			targetStr = targetStr.substring(1);
		}
		return targetStr;
	}
	catch(e)
	{
		return targetStr;
	}

 }

///////////////////////////////////////////////////////////////////////////////
// �Լ��� : rtrim(string) 2005.02.01
// ��  �� : ���ڿ����� ���� ������ �����ϰ� ��ȯ�Ѵ�.
///////////////////////////////////////////////////////////////////////////////
function rtrim(targetStr)
{
	try
	{
		 len = targetStr.length;
		while(targetStr.substring(len-1,len)==" ") {
			targetStr = targetStr.substring(0,len-1);
			len = targetStr.length;
		}
		return targetStr;
	}
	catch(e)
	{
		return targetStr;
	}
 }

///////////////////////////////////////////////////////////////////////////////
// �Լ��� : IsValidEmail(string) 2005.02.01
// ��  �� : ���� ��ȿ�� üũ
///////////////////////////////////////////////////////////////////////////////
function IsValidEmail(email) {
	num = 0;
	num_1 = 0;
	for (i=0;i<email.length;i++){
		if (email.charAt(i) == '@') num++;
		if (email.charAt(i) == '.') num_1++;
	}
	if (num != 1 || num_1 == 0) {
		alert("��ȿ���� ���� �����ּ� �Դϴ�.");
		return false;
	}
	return true;
}

//////////////////////////////////////////////////////////////////////////////
// �Լ��� :  isNum (v)
// ��  �� : �Է��� �������� �˻��Ѵ�.
///////////////////////////////////////////////////////////////////////////////
function isNum(v){
	return (v.toString() && !/\D/.test(v));
}




///////////////////////////////////////////////////////////////////////////////
// �Լ��� : f_validate()
// ��  �� : �� element�� validate �˻� �̺�Ʈ
// Event  : <script language=JavaScript for=gcGrid1 event=CanColumnPosChange(row,colid)>
// Object : elements
///////////////////////////////////////////////////////////////////////////////
function f_validate(obj,iType,operator)
{
	switch(iType){

		case "date":

			var sDate=obj.replace(/(\,|\.|\-|\/)/g,"");
			var sFormat="YYYYMMDD";
			var aDaysInMonth=new Array(31,28,31,30,31,30,31,31,30,31,30,31);
			var err_date="��¥�Է��� �߸��Ǿ����ϴ�.[YYYY/MM/DD]";

			if ( sDate.length!=0 && sDate.length != 8 ) {
				alert(err_date);
				return false;
			}

			if ( !isNum(sDate.substr(0,4)) ||!isNum(sDate.substr(4,2)) ||!isNum(sDate.substr(6,2))){
				alert(err_date);
				return false;
			}


			iYear=eval(sDate.substr(0,4));
			iMonth=eval(sDate.substr(4,2));
			iDay=eval(sDate.substr(6,2));

			 // Check for leap year
			var iDaysInMonth=(iMonth!=2)?aDaysInMonth[iMonth-1]:((iYear%4==0 && iYear%100!=0 || iYear % 400==0)?29:28);

			if((iDay!=null && iMonth!=null && iYear!=null  && iMonth<13 && iMonth>0 && iDay>0 && iDay<=iDaysInMonth) == false ) {
				alert(err_date);
				return false;
			}

			return true;
			break;
		case "int":
		case "jumin":
			var eliminateNum = "1234567890";
			for (var i = 0; i < trim(obj).length ;i++)
			{

				//If there is something which is not number_______________
				if (eliminateNum.indexOf(trim(obj).charAt(i)) == -1)
				{
					objValue="";
					alert("���ڸ� ����մϴ�");
					return false;
					break;
				}
				if(iType=="jumin"){
					if ( obj.length !=0  && obj.length != 13  ){
						if(operator !=null && operator !="like"){
							alert("�ֹι�ȣ �ڸ����� ���� �ʽ��ϴ�.");
							return false
						}

					}
				}
			}

			return true;
			break;
		default:return true;

	}

 }

////////////////////////////////////////////////////////////////////////////////
// �Լ��� : pdDelZero(par1)
// ��  �� : par1���� �Ӹ��� '0'�� ���� ��� '0'�� ������ ������ �����Ѵ�.
// �Ķ����  : �ؽ�Ʈ, �⺻��
///////////////////////////////////////////////////////////////////////////////
function pdDelZero(par1)
{
	var retVal = "";
	var count =0 ;
	par1 = par1.toString();
	try
	{
		for(i =0 ; i< par1.length; i++)
			{
				 var ch = par1.substring(i, i+1);
				if (ch == "0" )
				{
					count += 1;
				}

			}
			return retVal = par1.substring(count, par1.length);

	}
	catch(e)
	{
	}
}


/**
--------------------------------------------------
 �뵵       : TextArea ���ڼ�üũ�ϱ�
 �Ķ����   : aro_name - ������Ʈ �̸�, ari_max - �ִ� Byte
 ����       : ������ �°� ������ Control���� String�� ������
 �ۼ���     : 2005-02-18
 ��         :
 -------------------------------------------------
 */
function pdChkTextareaSize(aro_name,ari_max)
{

	var ls_str = aro_name.getValue(); // �̺�Ʈ�� �Ͼ ��Ʈ���� value ��
	var li_str_len = ls_str.length; // ��ü����

	// �����ʱ�ȭ
	var li_max = ari_max; // ������ ���ڼ� ũ��
	var i = 0; // for���� ���
	var li_byte = 0; // �ѱ��ϰ��� 2 �׹ܿ��� 1�� ����
	var li_len = 0; // substring�ϱ� ���ؼ� ���
	var ls_one_char = ""; // �ѱ��ھ� �˻��Ѵ�
	var ls_str2 = ""; // ���ڼ��� �ʰ��ϸ� �����Ҽ� ������������ �����ش�.

	for(i=0; i< li_str_len; i++)
	{
		ls_one_char = ls_str.charAt(i);	// �ѱ�������

		if (escape(ls_one_char).length > 4)	// �ѱ��̸� 2�� ���Ѵ�.
		{
			li_byte += 2;
		}
		else			// �׹��� ���� 1�� ���Ѵ�.
		{
			li_byte++;
		}

		// ��ü ũ�Ⱑ li_max�� ����������
		if(li_byte <= li_max)
		{
			li_len = i + 1;
		}
	}

	// ��ü���̸� �ʰ��ϸ�
	if(li_byte > li_max)
	{
		alert( li_max + " ���ڸ� �ʰ� �Է��Ҽ� �����ϴ�. \n �ʰ��� ������ �ڵ����� ���� �˴ϴ�. ");
		ls_str2 = ls_str.substr(0, li_len);
		aro_name.setValue(ls_str2);
	}
	aro_name.focus();
}


///////////////////////////////////////////////////////////////////////////////
// �Լ��� :format_phone()
// ��  �� : ��ȭ��ȣ ������ �ڵ������Ѵ�.
// Event :
// Object : Input
///////////////////////////////////////////////////////////////////////////////
function format_phone(str) {
	rgnNo = new Array;
	rgnNo[0] = "02";
	rgnNo[1] = "031";
	rgnNo[2] = "032";
	rgnNo[3] = "033";
	rgnNo[4] = "041";
	rgnNo[5] = "042";
	rgnNo[6] = "043";
	rgnNo[7] = "051";
	rgnNo[8] = "052";
	rgnNo[9] = "053";
	rgnNo[10] = "054";
	rgnNo[11] = "055";
	rgnNo[12] = "061";
	rgnNo[13] = "062";
	rgnNo[14] = "063";
	rgnNo[15] = "064";
	rgnNo[16] = "010";
	rgnNo[17] = "011";
	rgnNo[18] = "016";
	rgnNo[19] = "017";
	rgnNo[20] = "018";
	rgnNo[21] = "019";

	var eliminateStr = /(\,|\.|\-|\/|\:|\s)/g;
	str = str.replace(eliminateStr,"");
	for (var i = 0; i < rgnNo.length; i++) {
		if (str.indexOf(rgnNo[i]) == 0) {
			len_rgn = rgnNo[i].length;
			formattedNo = getFormattedPhone(str.substring(len_rgn));
			return rgnNo[i] + "-" + formattedNo;
		}else if(str.length==11){
			formattedNo = getFormattedPhone(str.substring(3));
			return str.substring(0,3) + "-" + formattedNo;

		}else if(str.length==12){
			formattedNo = getFormattedPhone(str.substring(4));
			return str.substring(0,4) + "-" + formattedNo;
		}
	}

	if (str.length > 8)
		return str;

	return getFormattedPhone(str);
}
///////////////////////////////////////////////////////////////////////////////
// �Լ��� :getFormattedPhone()
// ��  �� : ��ȭ��ȣ ������ �ڵ������Ѵ�.
// Event :
// Object : Input
///////////////////////////////////////////////////////////////////////////////
function getFormattedPhone(str) {
	if (str.length<=4) {
		return str;
	}
	else {
		len_no1 = str.length - 4;
		no1 = str.substring(0, len_no1);
		no2 = str.substring(len_no1);
		return no1 + "-" + no2;
	}
}

function getFormattedPhone2(str) {
	if (str.length<=4) {
		return str;
	}
	else {
		var eliminateStr = /(\,|\.|\-|\/|\:|\s)/g;
		str = str.replace(eliminateStr,"");
	var	len_no1 = str.length - 4;
		no1 = str.substring(0,4);
		no2 = str.substring(4,8);
		no3 = str.substring(8);
	
		if (no1.substring(0,3)=="000") no1 = no1.substring(2);
		if (no1.substring(0,2)=="00") no1 = no1.substring(1);
		if (no2.substring(0,1)=="0") no2 = no2.substring(1);
		
		return no1 + "-" + no2+ "-" + no3;
	}
}

//ex) getFormat("111222333333","-","3-3-6");
	function getFormat(str, delim, type, maskYN) {
		
		if(maskYN == null || maskYN != "N"){
			str = maskAccNO(str);
		}
		
		if(str=="") return "";
		if(str == null || delim == null || type == null)		return '';
		var aType = type.split("-");
		var retStr = "";
		var firstLen = 0;
		var lastLen = 0;

		for(i3=0; i3<aType.length; i3++) {
			if(i3 == 0) {
				firstLen	= 0;
				lastLen		= parseInt(aType[0]);
			} else {
				firstLen	= lastLen;
				lastLen		= firstLen + parseInt(aType[i3]);
			}
			if(i3 == aType.length-1)
				retStr = retStr + str.substring(firstLen, lastLen);
			else
				retStr = retStr + str.substring(firstLen, lastLen) + delim;
		}
		return retStr;
		
	}


///////////////////////////////////////////////////////////////////////////////
// �Լ��� :f_remove_format()
// ��  �� : ��� �Է� ������ ���ش�.
// Event : OnFocus�� ȣ��Ǵ� �Լ��̴�.
// Object : Input
///////////////////////////////////////////////////////////////////////////////
function f_remove_format(obj)
{
	obj = obj.replace(/(\,|\.|\-|\/|\:)/g,"");
	return obj;
}


function getYear()
{
	var objDate;
	objDate = new Date();			//Date ��ü�� ����ϴ�.
	return objDate.getYear().toString();	//������ �����ɴϴ�.
}

// �ݾ� �ڸ��� ǥ��
function moneyFormat(pnum){

	var sign="";
	var temp="";
	var pos=3;
	var num_len = 0;
	var num;


	var sTemp = "";
	var iLen = 0;

	num = Number(pnum);

	num=new String(num);
	if(trim(num) == "")
	{
		//alert("�����Դϴ�.");
		return pnum;
	}
	num=num.replace(/,/gi,"");

	if(isNaN(num)) {
		//alert("���ڸ� �Է��� �� �ֽ��ϴ�.");
		return pnum;
	}
	if(num==0) {
		return num;
	}

	if(num<0){
		num=num*(-1);
		sign="-";
	}
	else{
		num=num*1;
	}

	num = new String(num)

	num_len=num.length;
	while (num_len>0){
		num_len=num_len-pos;
		if(num_len<0) {
			pos=num_len+pos;
			num_len=0;
		}
		temp=","+num.substr(num_len,pos)+temp;
	}
	return sign+temp.substr(1);


}

// �ݾ� �ڸ��� ǥ��
function getMoneyFormat(pnum) {
	return moneyFormat(pnum)
}



/*****************************************************************************
 * ����   :�ֹι�ȣ/�ܱ���/����ڵ�Ϲ�ȣ ��ȿ�� äũ
 * String strNo1 : �ֹε�Ϲ�ȣ �Ǵ� �ܱ��� ��ȣ �� 6�ڸ� �Ǵ� �� 13�ڸ�
                   ����� ��Ϲ�ȣ 10�ڸ�
 * String strNo2 : �ֹε�Ϲ�ȣ �Ǵ� �ܱ��� ��ȣ ���ڸ� 7�ڸ�
 * RETURN boolean: ��ȿ���� �´ٸ� true, Ʋ�ȴٸ� false
 * ��) var bResult = checkIDNumber('7704111122334')
 *     var bResult = checkIDNumber('770411,1122816')
 *****************************************************************************/
function checkNumber(strNo1, strNo2)
{
	var retValue = false;
	var strTempNo;
	var strSex;
	
	if(strNo1.length == 13)
	{
		strTempNo = strNo1;

		strSex = strNo1.substring(6,7) ;
		//alert(strSex);
		if(strSex  == '1' || strSex == '2' || strSex =='3' || strSex == '4')
		{ 
			retValue = isJuminNo(strTempNo);
		}
		else // �ܱ��� 
		{
			retValue = isFgnNo(strTempNo);
		}
	}
	else
	{
		if(strNo1.length != 10)
		{
			strSex = strNo1.substring(1,2) ;
			//alert(strSex);

			if(strSex  == '1' || strSex == '2' || strSex =='3' || strSex == '4')
			{
				retValue = isJuminNo(strNo1, strNo2);
			}
			else
			{
				retValue = isFgnNo(strNo1);
			}
		}
		else // ����ڵ�Ϲ�ȣ ��ȿ��
		{
			retValue = check_busino(strNo1);
		}
	}

	return retValue;
}

// ����ڵ�Ϲ�ȣ üũ 
function check_busino(vencod) 
{ 
	var sum = 0; 
	var getlist = new Array(10); 
	var chkvalue =new Array("1","3","7","1","3","7","1","3","5"); 

	try
	{
		for(var i=0; i<10; i++) 
		{ 
			getlist[i] = vencod.substring(i, i+1); 
		} 
		
		for(var i=0; i<9; i++) 
		{ 
			sum += getlist[i]*chkvalue[i]; 
		} 

		sum = sum + parseInt((getlist[8]*5)/10); 
		//sum = sum + Number((getlist[8]*5)/10); 
		sidliy = sum % 10; 
		sidchk = 0; 
		
		if(sidliy != 0)
		{ 
			sidchk = 10 - sidliy; 
		} 
		else 
		{
			sidchk = 0; 
		} 
		
		if(sidchk != getlist[9]) 
		{ 
			return false; 
		} 
		return true; 
	}
	catch(e)
	{
		return false;
	}
} 

// ��ܱ���	��ȣ üũ 
function isFgnNo(fgnno)	
{ 
	var	sum=0; 
	var	odd=0; 
	buf	= new Array(13); 
	try
	{
		for(i=0; i<13; i++)	
		{ 
			buf[i]=Number(fgnno.charAt(i));	
		} 

		odd	= buf[7]*10	+ buf[8]; 
		
		if(odd%2 !=	0) 
		{ 
			return	false; 
		} 
		
		if(	(buf[11]!=6) &&	(buf[11]!=7) &&	(buf[11]!=8) &&	(buf[11]!=9) ) 
		{ 
				return false; 
		} 
		multipliers	= [2,3,4,5,6,7,8,9,2,3,4,5]; 
		for(i=0, sum=0;	i<12; i++)
		{ 
			sum +=	(buf[i]	*= multipliers[i]);	
		} 
		sum	= 11 - (sum%11); 
		
		if(sum >= 10) 
		{	
			sum	-= 10; 
		} 
		sum	+= 2; 
		if(sum >= 10)
		{
			sum	-= 10; 
		} 
		if(sum != buf[12]) 
		{ 
			return	false 
		}	
		return true; 
	}
	catch(e)
	{
		return false;
	}
} 

//�ֹε�Ϲ�ȣ üũ
function isJuminNo(strValue1, strValue2){
	var str_f_num;
	var str_l_num;
	try
	{
		if(strValue1.length == 13)
		{
			str_f_num = strValue1.substring(0,6);
			str_l_num = strValue1.substring(6);
		}
		else
		{
			str_f_num = strValue1;
			str_l_num = strValue2;
		}

		var i3=0
		for (var i=0;i<str_f_num.length;i++){
		 var ch1 = str_f_num.substring(i,i+1);
			if (ch1<'0' || ch1>'9') i3=i3+1;
		}
		if ((str_f_num == '') || ( i3 != 0 )) return false;
		var i4=0;
		for (var i=0;i<str_l_num.length;i++){
			var ch1 = str_l_num.substring(i,i+1);
			if (ch1<'0' || ch1>'9') i4=i4+1; 
		}
		if ((str_l_num == '') || ( i4 != 0 )) return false;
		/* 2000 ���� �»��� �ֹε�Ϲ�ȣ */
//		if(str_f_num.substring(0,1) < 4) return false;
//		if(str_l_num.substring(0,1) > 2) return false;
		if((str_f_num.length > 7) || (str_l_num.length > 8)) return false;
		if ((str_f_num == '72') || ( str_l_num == '18'))  return false;
				
		var f1=str_f_num.substring(0,1)
		var f2=str_f_num.substring(1,2)
		var f3=str_f_num.substring(2,3)
		var f4=str_f_num.substring(3,4)
		var f5=str_f_num.substring(4,5)
		var f6=str_f_num.substring(5,6)
		var hap=f1*2+f2*3+f3*4+f4*5+f5*6+f6*7
		var l1=str_l_num.substring(0,1)
		var l2=str_l_num.substring(1,2)
		var l3=str_l_num.substring(2,3)
		var l4=str_l_num.substring(3,4)
		var l5=str_l_num.substring(4,5)
		var l6=str_l_num.substring(5,6)
		var l7=str_l_num.substring(6,7)
		hap=hap+l1*8+l2*9+l3*2+l4*3+l5*4+l6*5
		hap=hap%11
		hap=11-hap
		hap=hap%10
		if (hap != l7) return false;
		return true; 
	}
	catch(e)
	{
		return false;
	}
}


function addComma(strSrc, nPos, bSymbol)
{
	var	strSymbol =	'';
	var	retValue  =	'';
	var	strTempSymbol =	'';
	var	strTempDotValue	= '';

	var	nLen	  =	0;

	try
	{
		if(bSymbol == null || typeof bSymbol == "undefined")
		{
			bSymbol	= false;
		}
		if(nPos == null || typeof nPos == "undefined")
		{
			nPos = 0;
		}
		if(strSrc == "")
		{
			strSrc = "0";
		}
		strSrc = strSrc;

		var	nIdx = strSrc.indexOf('.');

		if(nIdx != -1)
		{	
			// �Ҽ����� ������ 
			var strRemain = '';
			if(nPos == -1)
			{
				strTempDotValue	= strSrc.substring(nIdx);
				strSrc = strSrc.substring(0, nIdx);
			}
			else
			{
				if(nPos != 0)
				{
					var nRemain = strSrc.length - nIdx;	// �Է°� �Ҽ��� �ڸ���
					var nCount = nPos+1 - nRemain;		// ä�� �ڸ���		
					for(var i=0; i < nCount; i++)
					{
						strRemain += '0';
					}
					strTempDotValue	= strSrc.substr(nIdx, nPos+1) + strRemain;
				}	// nPos�� 0�϶� �Ǽ� ���� ����ȭ
				strSrc = strSrc.substring(0, nIdx);
			}
		}
		else
		{	
			// �Ҽ��� ������ 
			if(nPos != 0)
			{
				strTempDotValue = '.';
				for(var i=0; i < nPos; i++)
				{
					strTempDotValue += '0'
				}
			}
		}
	
		strTempSymbol =	strSrc.substr(0,1);
		if(strTempSymbol ==	'+')
		{
			strSymbol =	'+';
			strSrc = strSrc.substring(1);
		}
		if(strTempSymbol ==	'-')
		{
			strSymbol =	'-';
			strSrc = strSrc.substring(1);
		}

		nLen = strSrc.length;

		for(var	i=1; i <= nLen;	i++) 
		{
			retValue = strSrc.charAt(nLen -	i) + retValue;

			if((i %	3 == 0)	&& ((nLen -	i) != 0))
			{
				retValue = "," + retValue;
			}
		}

		if(bSymbol)
		{
			if(strSymbol ==	'')
			{
				strSymbol =	'+';
			}
		}
		else
		{
			if(strSymbol ==	'+')
			{
				strSymbol =	'';
			}
		}
		return strSymbol + retValue	+ strTempDotValue;
	}
	catch(e)
	{
	}
}



function setComma(strSrc)
{
	var	strSymbol =	'';
	var	retValue  =	'';
	var	strTempSymbol =	'';
	var	strTempDotValue	= '';

	var	nLen	  =	0;

	strSrc = pdStringReplace(strSrc, ',','');


	try
	{
		if(strSrc == "")
		{
			strSrc = "0";
		}
		strSrc = strSrc;

		nLen = strSrc.length;

		for(var	i=1; i <= nLen;	i++) 
		{
			retValue = strSrc.charAt(nLen -	i) + retValue;

			if((i %	3 == 0)	&& ((nLen -	i) != 0))
			{
				retValue = "," + retValue;
			}
		}

		return retValue
	}
	catch(e)
	{
	}
}


// �ش�⵵���� ���� �� ����¥ (��� - PJW)
function getStartDate(strDate, strMonth) {
	var orgValue = strDate;
	var val = 0 - strMonth;
	
	var retVal	= "";
	var strYear	= Number(orgValue.substr(0,4));
	var strMonth	= new Number(orgValue.substr(4,2));
	var strDay	= new Number(orgValue.substr(6,2));
	var monthTmp	= Number(strMonth) + Number(val);
	var yearTmp	= strYear;

	if(monthTmp < 1)
	{
		monthTmp	= monthTmp + 12;
		yearTmp		= yearTmp - 1;
	}
	
	if(monthTmp < 10)
	{
		monthTmp = "0" + "" + monthTmp;
	}

	if(strDay < 10)
	{
		strDay = "0"  + ""+ strDay;
	}
	
	retVal = yearTmp + "" + monthTmp + strDay;

	return retVal;
	
}



// �ش�⵵���� ���� ���� ��¥ (��� - PJW)
function getEndDate(strDate, strMonth) {
	var returnValue = "";
	var curYear = 0;
	var curMonth = 0;
	var curDay = 0;
	
	var minMonth = 0;
	var minYear = 0;
	
	strDate = strDate.replace(/-/gi, "");
	
	curYear = Number(strDate.substr(0,4));
	curMonth = Number(strDate.substr(4,2));
	curDay = Number(strDate.substr(6,2));	
	
	minMonth = Number(strMonth) % 12;
	minYear = parseInt(Number(strMonth) / 12);
	
	if ((curMonth + minMonth) > 12) {
		curYear = curYear + (minYear + 1);
		curMonth = (curMonth + minMonth) - 12;
	} else {
		curYear = curYear + minYear;
		curMonth += minMonth;
	}

	returnValue = curYear + "" + pdPackValue(curMonth, 2, true) + "" + pdPackValue(curDay, 2, true);
	
	return returnValue;
}

// ���Ͽ��� �Ǵ� �Ͽ� ������ ��� ���� ǥ��(bFlag = true)
function getMakeDate(strDate, pDay, bFlag) // ��, ��, ��, ����� ���� (�⵵�� �ݵ�� 4�ڸ��� �Է�)
{
	var regDate = "0101,0128,0129,0130,0301,0505,0531,0606,0717,0815,1003,1005,1006,1007,1225";	// ������
	var regDates = new Array();
	resDates = regDate.split(",");	
	
	if (bFlag == null) { bFlag = false; }

	strDate = strDate.replace(/-/gi, "");
	var curYear = Number(strDate.substr(0,4));
	var curMonth = Number(strDate.substr(4,2))-1;
	var curDay = Number(strDate.substr(6,2));
	
	curDay = curDay + pDay*1; // ��¥ ���
		
	var oDate = new Date(curYear, curMonth, curDay); // ���� ��¥ ��ü ���� (��ü���� �ڵ� ���)
	var strFlag = "";
	
	// alert(oDate.toLocaleString());
	
	if (bFlag) {
		strFlag = oDate.toLocaleString();
		if (strFlag.indexOf("�����") >-1) {
			curDay = oDate.getDate() - 1; // ��¥ ���
			oDate = new Date(oDate.getYear(), oDate.getMonth(), curDay); // ���� ��¥ ��ü ���� (��ü���� �ڵ� ���)
		} else if (strFlag.indexOf("�Ͽ���") >-1) {
			curDay = oDate.getDate() - 2; // ��¥ ���
			oDate = new Date(oDate.getYear(), oDate.getMonth(), curDay); // ���� ��¥ ��ü ���� (��ü���� �ڵ� ���)
		}
	}	
	return oDate.getYear()+""+pdPackValue(oDate.getMonth()+1,2,true)+""+pdPackValue(oDate.getDate(),2,true);
}


// Ŭ�����庹��
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


// ���ڸ� ���ڷ� �������ش�.
// ���ڵ� ���̿� ������ �ְų� �ϴ� ��쵵 �ֱ⶧����(�� :000000000121           020)
// ������ ���ְ� ���ڷ� �����ؼ� �����Ѵ�.
function getNumber(str) {
	str = trim(str.replace(/ /g,''));

	return Number(str);
}



/* =============================================================================================================
	��Ű ���� �Լ�
=============================================================================================================== */

//��Ű�о� ����
function fncGetCookieInfo(cookieKey)
{
	var retVal = ""
	if(cookieKey == null)
	{
		cookieName = "shbLoginInfo";
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

//��Ű�� ���� (6������)
function fncSetCookieInfo(cookieVal,cookieKey)
{ 
	if(cookieKey == null)
	{
		cookieName = "shbLoginInfo";
	}
	else
	{
		cookieName = cookieKey;
	}
	expireDate = new Date;
	expireDate.setMonth(expireDate.getMonth()+6); // 6������ ��Ű ����
	document.cookie = cookieName + "=" + cookieVal + "; expires=" + expireDate.toGMTString() ;
} 


// �����ڵ� 
function loadJikeob()
{
	try
	{
		var adoConn = new ActiveXObject("ADODB.Connection");
		var adoRS  = new ActiveXObject("ADODB.Recordset");
		/* Select ���� �ʵ带 ��������� ������ level �ʵ���� ��������� ���� �߻� */
		adoConn.Open("Provider=Microsoft.Jet.OLEDB.4.0;Data Source='C:\\shinhan\\mis\\cr\\srms_pro.MDB'");
		adoRS.Open("SELECT * FROM tmtb_jikeob", adoConn, 1, 1);
		pdGlobal.gJikeob = new Array(); // �����ڵ� array

		for(var i=0; !adoRS.EOF; adoRS.MoveNext)
		{

			var sub = new Array(); //adoRS.Fields(0),adoRS.Fields(1),adoRS.Fields(2),adoRS.Fields(3));
			sub[0]=new String( adoRS.Fields(0)); // jikeob_code
			sub[1]=new String( adoRS.Fields(1)); // jikeob_name
			sub[2]=new String( adoRS.Fields(2)); // jikeob_yuhyung
			sub[3]=new String( adoRS.Fields(3)); // jikeob_level
			//alert(sub);
			pdGlobal.gJikeob[i]=sub;
			//alert(pdGlobal.gJikeob[i].toString());
			//if (i>2) break;

			i++;
		}
		
	}
	catch(e)
	{
		if(adoRS == null)
		{
			WebSquare.logger.printLog("GlobalFunc.js loadAccess() :: adoRs == null");
		}
		if(adoConn == null)
		{
			//prtLog("pd223501main.jsp loadAccess() :: adoConn == null");
			WebSquare.logger.printLog("GlobalFunc.js loadAccess() :: adoConn == null");
		}
		alert("[�����ڵ�] : "+e.message+"\n �����͸� ����� ���� ���߽��ϴ�.\n\n �ٽ� �α����� �ֽʽÿ�.");
	}
	finally
	{
		adoRS.Close();
		adoConn.Close();
	}

}



/********************************************************************************************
 * ����          : Ÿ�Կ� ���� ���˺����Ͽ� �� ����
 * pType - jumin:�ֹι�ȣ, sa:����ڹ�ȣ,juminSa:�ֹι�ȣ or ����ڹ�ȣ, cif:����ȣ , acc:���¹�ȣ, tel:��ȭ��ȣ, date:��¥, time:�ð�
 ********************************************************************************************/
function pdFormatObj(str, pType)
{
	var retVal = "";

	if(str == null || pType == "" || pType.trim().length == 0) {
		return str;
	}
	
	
	if(pType == null || pType == "" || pType.trim().length == 0)
	{
		pType = "jumin";
	}

	switch(pType)
	{
		case "jumin": // �ֹι�ȣ
			retVal = pdJuminFormatObj(str);
			break;
		case "sa": // ����ڹ�ȣ
			retVal = pdSaFormatObj(str);
			break;
		case "juminSa": // �ֹι�ȣ or ����ڹ�ȣ
			retVal = pdJuminSaFormatObj(str);
			break;
		case "cif": // ����ȣ
			retVal = pdCifFormatObj(str);
			break;
		case "acc": // ���¹�ȣ
			retVal = pdAccFormatObj(str);
			break;
		case "tel": // ��ȭ��ȣ
			retVal = pdTelFormatObj(str);
			break;
		case "date": // ��¥
			retVal = pdDateFormatObj(str);
			break;
		case "time": // �ð�
			retVal = pdTimeFormatObj(str);
			break;
	}

	return retVal;
}

/*****************************************************************************
 * ����          : retVal �� Ư������ ���� �� ���ڿ� return
 *****************************************************************************/
function pdSimbolDeleteString(retVal)
{
	if(retVal != null && retVal != "" && retVal.trim().length > 0)
	{
		retVal = retVal.replace(/(\[|\]|\{|\)|\{|\}|\-|\_|\.|\ |\/|\,)/g,"");
	}
	
	return retVal;
}

// pType - jumin:�ֹι�ȣ
function pdJuminFormatObj(str)
{
	var textVal = pdSimbolDeleteString(str);
	if(textVal != null && textVal != "" && textVal.trim().length == 13)
	{
		return textVal.substring(0,6) + "-" + textVal.substring(6);
	}
	else {
		return str;		
	}
}

// pType - sa:����ڹ�ȣ
function pdSaFormatObj(str)
{
	var textVal = pdSimbolDeleteString(str);
	
	if(textVal != null && textVal != "" && textVal.length == 10)
	{
		return textVal.substring(0,3) + "-" + textVal.substring(3,5) + "-" + textVal.substring(5);
	}
	else {
		return str;		
	}
}


// pType - juminSa:�ֹι�ȣ or ����ڹ�ȣ
function pdJuminSaFormatObj(str)
{
	var textVal = pdSimbolDeleteString(str);

	if(textVal != null && textVal != "" && textVal.trim().length > 0)
	{
		if(textVal.length == 10)
		{
			return textVal.substring(0,3) + "-" + textVal.substring(3,5) + "-" + textVal.substring(5);
		}
		else if(textVal.length == 13)
		{
			return textVal.substring(0,6) + "-" + textVal.substring(7);
		}
		else {
			return str;
		}
	}
	return str;
}


// pType - tel:��ȭ��ȣ
function pdTelFormatObj(str)
{
	var textVal = pdSimbolDeleteString(str);

	if(textVal != null && textVal != "" && textVal.length > 0)
	{
		return format_phone(textVal);
	}
	else {
		return str;
	}
}

// pType - date:��¥
function pdDateFormatObj(str)
{
	var textVal = pdSimbolDeleteString(str);
	
	if(textVal != null && textVal != "" && textVal.length == 8)
	{
		return textVal.substring(0,4) + "/" + textVal.substring(4,6) + "/" + textVal.substring(6);
	}
	else {
		return str;
	}
}

// pType - time:�ð�
function pdTimeFormatObj(str)
{
	var textVal = pdSimbolDeleteString(str);
	
	if(textVal != null && textVal != "" && textVal.length == 6)
	{
		return textVal.substring(0,2) + ":" + textVal.substring(2,4) + ":" + textVal.substring(4);
	}
	else {
		return str;
	}	
}


/*---------------------------------------------------------------------------------
 *	���ڿ��� ���̸� ���ϴ� �Լ�(�ѱ�:2 ����Ʈ)
 *	s	: ���ڿ�
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


/*---------------------------------------------------------------------------------
 *	sTemp���ڿ����� sBChar���ڸ� sAChar���ڷ� ��ȯ�ϴ� �Լ�
 *	
 ----------------------------------------------------------------------------------*/
function removeChar(sTemp, sBChar, sAChar)
{
	while (sTemp.indexOf(sBChar) > -1)
	{
		sTemp = sTemp.replace(sBChar, sAChar);
	}
	
	return sTemp;
}




// ��ȭ��ȣ ���˺���
function displayTelNo(sTelNo)
{
	var sTemp = "";
	var iLen = 0;
	
	if(sTelNo != undefined || sTelNo != null )
	{
		if(sTelNo.length > 0)
		{
			sTemp = sTelNo.replace(/(\-|\ |\/|\,|\)|\]|\[|\()/g,"");
		}
	}
	else
	{
		return sTemp;
	}
	
	if(!isNum(sTemp))
	{
		return sTemp;
	}
	iLen = sTemp.length;
	
	switch (iLen)
	{
		case 0:
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		case 12:
			sTemp = "";
			break;
		case 7:
			sTemp = "02-" + sTemp.substr(0, 3) + "-" + sTemp.substr(3, 4);
			break;
		case 8:
			sTemp = "02-" + sTemp.substr(0, 4) + "-" + sTemp.substr(4, 4);
			break;
		case 9:
			if (sTemp.substr(0, 2) == "02")
			{
				sTemp = sTemp.substr(0, 2) + "-" + sTemp.substr(2, 3) + "-" + sTemp.substr(5, 4);
			}
			else
			{
				sTemp = "";
			}
			break;
		case 10:
			if (sTemp.substr(0, 2) == "02")
			{
				sTemp = sTemp.substr(0, 2) + "-" + sTemp.substr(2, 4) + "-" + sTemp.substr(6, 4);
			}
			else
			{
				sTemp = sTemp.substr(0, 3) + "-" + sTemp.substr(3, 3) + "-" + sTemp.substr(6, 4);
			}
			break;
		case 11:
			if (sTemp.substr(0,1) == "0")
			{
				sTemp = sTemp.substr(0, 3) + "-" + sTemp.substr(3, 4) + "-" + sTemp.substr(7, 4);
			}
			else
			{
				sTemp = "02-" + sTemp.substr(0, 3) + "-" + sTemp.substr(3, 4) + "-" + sTemp.substr(7, 4);	//���� ������ȣ
			}
			break;
		case 13:
			if(sTemp.substr(0, 2) == "02")
			{
				sTemp = sTemp.substr(0, 2) + "-" + sTemp.substr(2, 3) + "-" + sTemp.substr(5, 4) + "-" + sTemp.substr(9, 4);	//���� ������ȣ
			}
			else
			{
				sTemp = "";	
			}
			break;
		case 14:
			if(sTemp.substr(0, 1) == "0")
			{
				sTemp = sTemp.substr(0, 3) + "-" + sTemp.substr(3, 3) + "-" + sTemp.substr(6, 4) + "-" + sTemp.substr(10, 4);	//���� ������ȣ
			}
			else
			{
				sTemp = "";
			}
			break;
		case 15:
			if(sTemp.substr(0, 1) == "0")
			{
				sTemp = sTemp.substr(0, 3) + "-" + sTemp.substr(3, 4) + "-" + sTemp.substr(7, 4) + "-" + sTemp.substr(11, 4);	//���� ������ȣ
			}
			else
			{
				sTemp = "";
			}
			break;
		default:
			sTemp = "";
			break;
	}
	
	return sTemp;
}


/*---------------------------------------------------------------------------------
 *	����� â ó�� START
 document.onkeydown = fncDebug;
 ----------------------------------------------------------------------------------*/
function fncDebug() {
	var frBody = null;
	if(window.event.ctrlKey) {
		if(window.event.keyCode == 68) {	// [Ctrl + shift +D] �̸� ScriptDebugâ �˾�
			if(window.event.shiftKey) {
				pdDbgWin();
			}
		}
		else
		if(window.event.keyCode == 73) {	// [Ctrl + shift +I] �̸� ScriptInformationâ �˾�
			if(window.event.shiftKey) {
				pdInfWin();
			}
		}
	}
}

function pdDbgWin()
{
	var strDbgWin_ = ""
		+	"<html>\n"
		+	"<head>\n"
		+	"    <title>[debug] ��ũ��Ʈ �����</title>\n"
		+	"    <meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>\n"
		+	"    <meta http-equiv='Cache-Control' content='no-cache' />\n"
		+	"    <meta http-equiv='Expires' content='0' />\n"
		+	"    <meta http-equiv='Pragma' content='no-cache' />\n"
		+	"    <style>\n"
		+	"        body { font-size: 12px; font-family: ����ü; } \n"
		+	"        td { font-size: 12px; font-family: ����ü; } \n"
		+	"        textarea { font-size: 12px; font-family: ����ü; border: 1px solid #999999 } \n"
		+	"        input { font-size: 12px; font-family: ����ü; border: 1px solid #999999 } \n"
		+	"    </style>\n"
		+	"\n"
		+	"    <link href='/comm/css/global.css' rel='stylesheet' type='text/css'>\n"
		+	"    <script language='javascript'>\n"
		+	"    <!--\n"
		+	"        var ACSTEAM = 'G00101';\n"
		+	"        var CRMTEAM = 'G00102';\n"
		+	"        var INBOUNDTEAM = 'G00103';\n"
		+	"        var Agent;\n"
		+	"        var AgentEtc;\n"
		+	"        var pdAgent;\n"
		+	"        var pdAgentEtc;\n"
		+	"        var LoginProgram;\n"
		+	"        var Ftp;\n"
		+	"        var pdGlobal;\n"
		+	"\n"
		+	"	     String.prototype.trim = function() {\n"
		+	"		     var a = this;\n"
		+	"		     var search = 0;\n"
		+	"\n"
		+	"		     while ( a.charAt(search) == ' ') search += 1;\n"
		+	"		     a = a.substring(search, (a.length));\n"
		+	"		     search = a.length - 1;\n"
		+	"\n"
		+	"		     while (a.charAt(search) ==' ') search -= 1;\n"
		+	"		     return a.substring(0, search + 1);\n"
		+	"	     }\n"
		+	"    	 \n"
		+	"        function dbgInitPage() {\n"
		+	"            pdGlobal = opener.pdGlobal;\n"
		+	"            try {\n"
		+	"                // pdGlobal = opener.pdGlobal;\n"
		+	"                Agent = pdGlobal.Agent;\n"
		+	"                AgentEtc = pdGlobal.AgentEtc;\n"
		+	"                ArrAgent = pdGlobal.ArrAgent;\n"
		+	"                pdAgent = pdGlobal.pdAgent;\n"
		+	"                pdAgentEtc = pdGlobal.pdAgentEtc;\n"
		+	"                ArrPdAgent = pdGlobal.ArrPdAgent;\n"
		+	"                LoginProgram = pdGlobal.LoginProgram;\n"
		+	"                Ftp = pdGlobal.Ftp;\n"
		+	"            } catch(e) {\n"
		+	"                try {\n"
		+	"                    // pdGlobal = opener.pdGlobal;\n"
		+	"                    Agent = pdGlobal.Agent;\n"
		+	"                    AgentEtc = pdGlobal.AgentEtc;\n"
		+	"                    ArrAgent = pdGlobal.ArrAgent;\n"
		+	"                    pdAgent = pdGlobal.pdAgent;\n"
		+	"                    pdAgentEtc = pdGlobal.pdAgentEtc;\n"
		+	"                    ArrPdAgent = pdGlobal.ArrPdAgent;\n"
		+	"                    LoginProgram = pdGlobal.LoginProgram;\n"
		+	"                    Ftp     = pdGlobal.Ftp;\n"
		+	"                } catch(e) {\n"
		+	"                    // pdGlobal = parent.pdGlobal;\n"
		+	"                    Agent = pdGlobal.Agent;\n"
		+	"                    AgentEtc = pdGlobal.AgentEtc;\n"
		+	"                    ArrAgent = pdGlobal.ArrAgent;\n"
		+	"                    pdAgent = pdGlobal.pdAgent;\n"
		+	"                    pdAgentEtc = pdGlobal.pdAgentEtc;\n"
		+	"                    ArrPdAgent = pdGlobal.ArrPdAgent;\n"
		+	"                    LoginProgram = pdGlobal.LoginProgram;\n"
		+	"                    Ftp = pdGlobal.Ftp;\n"
		+	"                }\n"
		+	"            }\n"
		+	"            setTimeout('dbgStartPage()',100);\n"
		+	"        }\n"
		+	"\n"
		+	"        function dbgStartPage() {\n"
		+	"           document.getElementById('dbgtxtaIn').focus();\n"
		+	"        }\n"
		+	"\n"
		+	"        function preExecute() {}\n"
		+	"\n"
		+	"        function postExecute() {}\n"
		+	"\n"
		+	"        function dbgkeydown() {}\n"
		+	"\n"
		+	"        function dbgScript() {\n"
		+	"            var dbgStr_dbg = dbgtxtaIn.value.trim();\n"
		+	"            dbgStr_dbg = dbgStr_dbg.replace(/(\\n)/g,'\\t').trim();\n"
		+	"            try {\n"
		+	"                if(dbgStr_dbg.substr(0,1) == '?') {\n"
		+	"                    dbgtxtaOut.value = eval(dbgStr_dbg.substr(1).trim());\n"
		+	"                } else {\n"
		+	"                    dbgtxtaOut.value = eval('pdGlobal=opener.pdGlobal;'+dbgStr_dbg);\n"
		+	"                }\n"
		+	"            } catch(e) {\n"
		+	"                alert('��ũ��Ʈ �������:' +e);\n"
		+	"            }\n"
		+	"        }\n"
		+	"\n"
		+	"        function dbglnkClick(dbgIndex) {\n"
		+	"            switch(dbgIndex) {\n"
		+	"                case 0: \n"
		+	"                    dbgtxtaIn.value='';\n"
		+	"                    dbgtxtaOut.value='';\n"
		+	"                    break;\n"
		+	"                case 1: \n"
		+	"                    dbgScript();\n"
		+	"                    break;\n"
		+	"                case 2: \n"
		+	"                    window.close();\n"
		+	"                    break;\n"
		+	"                default:\n"
		+	"                    alert(index + '������Ʈ �̺�Ʈ');\n"
		+	"                   break;\n"
		+	"            }\n"
		+	"        }\n"
		+	"\n"
		+	"        document.onkeydown = dbgkeydown; \n"
		+	"    //-->\n"
		+	"    </script>\n"
		+	"\n"
		+	"</head>\n"
		+	"<body style='text-align: center' onload='dbgInitPage();'>\n"
		+	"    <table width='100%' height='100%' border='1' cellpadding='2' cellspacing='0'>\n"
		+	"        <tr>\n"
		+	"            <td>\n"
		+	"                 <!--������ Ÿ��Ʋ �г� START-->\n"
		+	"                <table width='98%' border='0' cellspacing='0' cellpadding='2'>\n"
		+	"                    <tr>\n"
		+	"                        <td width='80' align=center nowrap>\n"
		+	"                            [debug]\n"
		+	"                        </td>\n"
		+	"                        <td width='100%'>\n"
		+	"                            Script Debugger\n"
		+	"                        </td>\n"
		+	"                    </tr>\n"
		+	"                </table>\n"
		+	"                <!--������ Ÿ��Ʋ �г� END-->\n"
		+	"            </td>\n"
		+	"        </tr>\n"
		+	"        <tr>\n"
		+	"            <td>\n"
		+	"                <!--�˻� �г� START-->\n"
		+	"                <table width='98%' border='0' cellpadding='2' cellspacing='0' style='padding-top: 5px'>\n"
		+	"                    <tr>\n"
		+	"                        <td align='center' width='80' height='100' nowrap>\n"
		+	"                            Script\n"
		+	"                        </td>\n"
		+	"                        <td width='100%' height='100'>\n"
		+	"                            <textarea id='dbgtxtaIn' name='dbgtxtaIn' style='width: 100%; height: 100%'></textarea>\n"
		+	"                        </td>\n"
		+	"                        <td width='60' align=center nowrap>\n"
		+	"                            <a href='#' onclick='dbglnkClick(0);' id='dbglnk'><input type='button' style='width: 100px; height: 30px' value='ȭ������'></a><br><br>\n"
		+	"                            <a href='#' onclick='dbglnkClick(1);' id='A1'><input type='button' style='width: 100px; height: 30px' value='��      ��'></a>\n"
		+	"                        </td>\n"
		+	"                    </tr>\n"
		+	"                </table>\n"
		+	"                <!--�˻� �г� END-->\n"
		+	"            </td>\n"
		+	"        </tr>\n"
		+	"        <tr>\n"
		+	"            <td>\n"
		+	"                <!--�׸��� �г� START-->\n"
		+	"                <table width='98%' height='100' border='0' align='middle' cellpadding='0' cellspacing='0'>\n"
		+	"                    <tr>\n"
		+	"                        <td align='center' width='80' nowrap>\n"
		+	"                            ������\n"
		+	"                        </td>\n"
		+	"                        <td width='100%' height='100'>\n"
		+	"                            <textarea id='dbgtxtaOut' name='dbgtxtaOut' style='width: 100%; height: 100%'></textarea>\n"
		+	"                        </td>\n"
		+	"                    </tr>\n"
		+	"                </table>\n"
		+	"                <!--�׸��� �г� START-->\n"
		+	"            </td>\n"
		+	"        </tr>\n"
		+	"        <tr>\n"
		+	"            <td>\n"
		+	"                <!--��ư �г� START-->\n"
		+	"                <table width='98%' border='0' align='middle' cellpadding='0' cellspacing='1' style='padding-top: 5px'>\n"
		+	"                    <tr>\n"
		+	"                        <td align='center'>\n"
		+	"                            <a href='#' onclick='dbglnkClick(2);' id='A2'><input type='button' style='width: 100px; height: 30px' value='��     ��'></a>\n"
		+	"                        </td>\n"
		+	"                    </tr>\n"
		+	"                </table>\n"
		+	"                <!--��ư �г� END-->\n"
		+	"            </td>\n"
		+	"        </tr>\n"
		+	"    </table>\n"
		+	"</body>\n"
		+	"<script language='javascript'>\n"
        +   " dbgtxtaIn.focus();\n"
        +   "</script>\n"
		+	"</html>\n"
		+	"";
		
	var strOptions = "";
	var dbgWin = null;
	var nScreenTop = window.screen.height/2 - 200;
	var nScreenLeft  = window.screen.width/2 - 400;
	var strOptions = "width=800, height=400, top=" + nScreenTop + ", left=" + nScreenLeft;

	strOptions = strOptions + ",resizable=yes, status=no,menubar=no, scrollbars=no, title=no";
	dbgWin = window.open("about:blank", "", strOptions);	
	dbgWin.document.write(strDbgWin_);
}


function pdInfWin()
{
	var strInfWin_ = ""
		+	"<html>\n"
		+	"<head>\n"
		+	"    <title>[debug] ��ũ��Ʈ ����</title>\n"
		+	"    <meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>\n"
		+	"    <meta http-equiv='Cache-Control' content='no-cache' />\n"
		+	"    <meta http-equiv='Expires' content='0' />\n"
		+	"    <meta http-equiv='Pragma' content='no-cache' />\n"
		+	"    <style>\n"
		+	"        body { font-size: 12px; font-family: ����ü; } \n"
		+	"        td { font-size: 12px; font-family: ����ü; } \n"
		+	"        textarea { font-size: 12px; font-family: ����ü; border: 1px solid #999999 } \n"
		+	"        input { font-size: 12px; font-family: ����ü; border: 1px solid #999999 } \n"
		+	"    </style>\n"
		+	"\n"
		+	"    <script language='javascript'>\n"
		+	"\n"
		+	"        var pdGlobal;\n"
		+	"\n"
		+	"	     String.prototype.trim = function() {\n"
		+	"		     var a = this;\n"
		+	"		     var search = 0;\n"
		+	"\n"
		+	"		     while ( a.charAt(search) == ' ') search += 1;\n"
		+	"		     a = a.substring(search, (a.length));\n"
		+	"		     search = a.length - 1;\n"
		+	"\n"
		+	"		     while (a.charAt(search) ==' ') search -= 1;\n"
		+	"		     return a.substring(0, search + 1);\n"
		+	"	     }\n"
		+	"    	 \n"
		+	"        function dbgInitPage() {\n"
		+	"            pdGlobal = opener.pdGlobal;\n"
		+	"        }\n"
		+	"\n"
		+	"        function setInfo(obj) {\n"
		+	"            var msgStr = obj[obj.selectedIndex].value;\n"
		+	"            try {\n"
		+	"                dbgtxtaOut.value = eval('opener.' + msgStr);\n"
		+	"            } catch(e) {\n"
		+	"                alert('��ũ��Ʈ �������:' +e);\n"
		+	"            }\n"
		+	"        }\n"
		+	"\n"
		+	"    </script>\n"
		+	"\n"
		+	"</head>\n"
		+	"<body style='text-align: center' onload='dbgInitPage();'>\n"
		+	"    <table width='100%' height='100%' border='1' cellpadding='2' cellspacing='0'>\n"
		+	"        <tr>\n"
		+	"            <td>\n"
		+	"                <table width='100%' border='0' cellpadding='2' cellspacing='0' style='padding-top: 5px'>\n"
		+	"                    <tr>\n"
		+	"                        <td width='100%' height='40'>\n"
		+	"                            <b>Script Information</b> \n"
		+   "                            <select id='dbgcmbSelect' onchange='setInfo(this)'>\n"
		+	"                               <option value='msgProgTitle' selected>���α׷���</option>\n"
		+	"                               <option value='msgProgDesc'>���α׷���</option>\n"
		+	"                               <option value='msgProgCoder'>�����</option>\n"
		+	"                               <option value='msgProgComment'>Comment</option>\n"
		+	"                               <option value='msgProgHistory'>Update History</option>\n"
		+   "                            </select>\n"
		+	"                        </td>\n"
		+	"                    </tr>\n"
		+	"                </table>\n"
		+	"            </td>\n"
		+	"        </tr>\n"
		+	"        <tr>\n"
		+	"            <td>\n"
		+	"                <!--�׸��� �г� START-->\n"
		+	"                <table width='98%' height='180' border='0' align='middle' cellpadding='0' cellspacing='0'>\n"
		+	"                    <tr>\n"
		+	"                        <td width='100%' height='180'>\n"
		+	"                            <textarea id='dbgtxtaOut' name='dbgtxtaOut' style='width: 100%; height: 100%'></textarea>\n"
		+	"                        </td>\n"
		+	"                    </tr>\n"
		+	"                </table>\n"
		+	"                <!--�׸��� �г� START-->\n"
		+	"            </td>\n"
		+	"        </tr>\n"
		+	"        <tr>\n"
		+	"            <td>\n"
		+	"                <!--��ư �г� START-->\n"
		+	"                <table width='98%' border='0' align='middle' cellpadding='0' cellspacing='1' style='padding-top: 5px'>\n"
		+	"                    <tr>\n"
		+	"                        <td align='center'>\n"
		+	"                            <a href='#' onclick='self.close()' id='A2'><input type='button' style='width: 100px; height: 30px' value='��     ��'></a>\n"
		+	"                        </td>\n"
		+	"                    </tr>\n"
		+	"                </table>\n"
		+	"                <!--��ư �г� END-->\n"
		+	"            </td>\n"
		+	"        </tr>\n"
		+	"    </table>\n"
		+	"</body>\n"
        +	"<script language='javascript'>\n"
        +   " setInfo(dbgcmbSelect);\n"
        +   "</script>\n"
		+	"</html>\n"
		+	"";
		
	var strOptions = "";
	var infWin = null;
	var nScreenTop = window.screen.height/2 - 200;
	var nScreenLeft  = window.screen.width/2 - 400;
	var strOptions = "width=600, height=300, top=" + nScreenTop + ", left=" + nScreenLeft;

	strOptions += ",resizable=yes, status=no,menubar=no, scrollbars=no, title=no";
	infWin = window.open("about:blank", "InfWin", strOptions);
	infWin.document.write(strInfWin_);
}


function dbgInitPage()
{
	try
	{
		pdGlobal = top.frGlobal;
	}
	catch(e)
	{
		try
		{
			pdGlobal = opener.frGlobal;
		}
		catch(e)
		{
			pdGlobal = parent.frGlobal;
		}
	}
	setTimeout("dbgStartPage()",100);
}

function dbgStartPage()
{
	dbgtxtaIn.focus();
}

function preExecute()
{
}

function postExecute()
{
}

function dbgkeydown()
{
}

function dbgScript()
{
	var dbgStr_dbg = dbgtxtaIn.value.trim();
	dbgStr_dbg.replace(/(\n)/g,'\t').trim();

	try
	{
		if(dbgStr_dbg.substr(0,1) == "?")
		{
			dbgtxtaOut.value = eval(dbgStr_dbg.substr(1).trim());
		}
		else
		{
			dbgtxtaOut.value = eval(dbgStr_dbg);
		}
	}
	catch(e)
	{
		alert("��ũ��Ʈ �������:" +e);
	}
}

function CheckAgent()
{
	try
	{
		if(opener == undefined)
		{
			opener = window.dialogArguments;
		}
	}
	catch(e)
	{
	}
	
	try
	{
		pdGlobal = top.frGlobal;
	}
	catch(e)
	{
		try
		{
			pdGlobal = opener.pdGlobal;
		}
		catch(e)
		{
			pdGlobal = parent.frGlobal;
		}
	}
}

/*---------------------------------------------------------------------------------
 *	����� â ó�� END
 ----------------------------------------------------------------------------------*/


/* String ���� �Ǿ� �ִ� �ڵ� ������ String�� Array�� ��ȯ�ؼ� ��ȯ
*   ��)  str = "10020,����������\n10021,����������\n10022,����������";
*			gubun1 �� "\n" 
*     		gubun2 �� ","
*          �� �ϸ� Array�� ��ȯ 
*/
function getCodeArraytoString(str, gubun1, gubun2) {
	var arr1 = str.split(gubun1);
	var arr2 = new Array();
	
	var retArray = new Array();
	for(var i=0; i<arr1.length;i++) {
		retArray[i] = arr1[i].split(gubun2);
	}
	
	return retArray;
}

//-- ���ü, ����byte
function textCounter(theField,maxChars)
{

        var strCharCounter = 0;
	var intLength = 0;
	
	if(theField.getValue() == null || theField.getValue() == "" || theField.getValue().trim().length == 0)
	{
		return;
	}

	try
	{
		intLength = theField.getValue().length;
		for (var i = 0; i < intLength; i++) {
			var charCode = theField.getValue().charCodeAt(i);

			//�ѱ��� ���
			if (charCode > 128)        {
				strCharCounter += 2;
			} else {
				strCharCounter++;
			}

			if(strCharCounter > maxChars) {
				eval("alert('�ѱ�" + maxChars/2 + ", ����" + maxChars+ "�� �����Դϴ�. �ʰ��� ���ڴ� �߸��ϴ�.')");

				if(!cutStr(theField, i,  maxChars)) {
					alert("���ڿ� ĿƮ �Լ��� �۵����� �ʽ��ϴ�.");
				}
				break;
			}
		}
	}
	catch(e)
	{
		intLength = theField.getValue().length;
		for (var i = 0; i < intLength; i++) {
			var charCode = theField.getValue().charCodeAt(i);

			//�ѱ��� ���
			if (charCode > 128)        {
				strCharCounter += 2;
			} else {
				strCharCounter++;
			}

			if(strCharCounter > maxChars) {
				eval("alert('�ѱ�" + maxChars/2 + ", ����" + maxChars+ "�� �����Դϴ�. �ʰ��� ���ڴ� �߸��ϴ�.')");

				if(!cutStr(theField, i,  maxChars)) {
					alert("���ڿ� ĿƮ �Լ��� �۵����� �ʽ��ϴ�.");
				}
				break;
			}
		}
	}
}



/** 
* string String::cut(int len)
* ���ڸ� �տ������� ���ϴ� ����Ʈ��ŭ �߶� �����մϴ�.
* �ѱ��� ��� 2����Ʈ�� ����ϸ�, ���� �߰����� �߸��� �ʽ��ϴ�.
*/
String.prototype.cutstr = function(len) {
	var str = this;
	var l = 0;
	for (var i=0; i<str.length; i++) {
		l += (str.charCodeAt(i) > 128) ? 2 : 1;
		if (l > len) return str.substring(0,i);
	}
	// return str;
	return pdPackValue(str,len,false);
}



//���� byte�� ���ڿ� Cut
function cutStr(theField, i, maxChars)
{
        var intLength;
	var strChar;

	try
	{
		intLength = theField.getValue().length;        //-- ���� ������ ���̸� ���Ѵ�.
		strChar = theField.getValue().substring(0,i);                //������ ���ڸ� �߶󳽴�.
		theField.setValue("");
		theField.setValue(strChar);
	}
	catch(e)
	{
		intLength = theField.getValue().length;        //-- ���� ������ ���̸� ���Ѵ�.
		strChar = theField.getValue().substring(0,i);                //������ ���ڸ� �߶󳽴�.
		theField.setValue("");
		theField.setValue(strChar);
	}

        return true;
}
//-->

/*---------------------------------------------------------------------------------
 *	���ڿ��� ���̸� ���ϴ� �Լ�(�ѱ�:2 ����Ʈ)
 *	s	: ���ڿ�
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

//
function getloc(str,len){
	var l = 0;
	for (var i=0; i<str.length; i++){
		l += (str.charCodeAt(i) > 128) ? 2 : 1;
		if (l > len) return i;
	}
	return len;
}

// ����, ����, ũ��
function getSplitData(str, sStart, sSize) {

	var i = getloc(str, sStart);
	var j = getloc(str, sStart+sSize);
	return str.substring(i,j).cutstr(sSize);
}


/*****************************************************************************
 * ����		: ��ȭ��ȣ ���� ����
 * Object pVal	: �Է°�
 *****************************************************************************/
function pdTelFormatString(pVal)
{
	var retVal = "";
	
	if(pVal != null && pVal != "" && pVal.trim().length > 0)
	{
		pVal = pVal.replace(/(\-|\ |\/|\,)/g,"");
		
		if(pVal.length > 3)
		{

			if(pVal.substring(0,2) == "02")
			{
				if(pVal.substring(2,3)=="0")
				{
					pVal = pVal.substring(0,2) + pVal.substr(3);
				}

				if(pVal.trim().length == 9)
				{
					retVal = pVal.substring(0,2) + "-" + pVal.substring(2,5) + "-" + pVal.substring(5,9);
				}
				else if(pVal.trim().length == 10)
				{
					if(pVal.substring(0,2) == "02")
					{
						retVal = pVal.substring(0,2) + "-" + pVal.substring(2,6) + "-" + pVal.substring(6,10);
					}
					else
					{
						retVal = pVal.substring(0,3) + "-" + pVal.substring(3,6) + "-" + pVal.substring(6,10);
					}
				}
				else
				{
					retVal = pVal;
				}
			}
			else
			{
				if(pVal.trim().length == 10)
				{
					retVal = pVal.substring(0,3) + "-" + pVal.substring(3,6) + "-" + pVal.substring(6,10);
				}
				else if(pVal.trim().length == 11)
				{
					if(pVal.substring(3,4) == "0")
					{
						retVal = pVal.substring(0,3) + "-" + pVal.substring(4,7) + "-" + pVal.substring(7,11);
					}
					else
					{
						retVal = pVal.substring(0,3) + "-" + pVal.substring(3,7) + "-" + pVal.substring(7,11);
					}
				}
				else
				{
					retVal = pVal;
				}
			}
		}
		else
		{
			retVal = pVal;
		}
	}
	
	return retVal;
}


function convertTel(strValue)
{
	
	var retValue;
	var strTel1;
	var strTel2;
	var strTel3;
	strValue = strValue.replace(/(\-|\ |\/|\,)/g,"");

	if(strValue.length != 12)
	{
		if(strValue.length == 7)
		{
			return "02-" + strValue.substring(0,3) + "-" + strValue.substring(3);
		}
		else
		{
			return pdTelFormatString(strValue);
		}
	}
	else
	{

		strTel1 = Number(strValue.substring(0,4)).toString();
		strTel2 = Number(strValue.substring(4,strValue.length -4)).toString();
		strTel3 = strValue.substring(strValue.length -4);
		
		return '0' + strTel1 + '-' + strTel2 + '-' + strTel3;
	}	
}


function changeTel1( strValue ) {
	var retValue = "";
	strValue = strValue.replace(/(\-|\ |\/|\,)/g,"");
	
	switch(strValue.length) {
		case 9:
		case 8:
			retValue = strValue.substr(0,1)+"-"+ strValue.substr(1,4)+"-"+strValue.substring(5);
			break;
		case 7:
			retValue = strValue.substr(0,1)+"-"+ strValue.substr(1,3)+"-"+strValue.substring(4);
			break;
		case 6:
			retValue = strValue.substr(0,1)+"-"+ strValue.substr(1,2)+"-"+strValue.substring(3);
			break;
		case 5:
			retValue = strValue.substr(0,1)+"-"+ strValue.substring(1);
			break;
		default:
			retValue = strValue;
			break;
	}
		return retValue;			
}


function changeTel2( strValue ) {
	var retValue = "";
	strValue = strValue.replace(/(\-|\ |\/|\,)/g,"");
	
	switch(strValue.length) {
		case 12:
			retValue = strValue.substr(0,4)+"-"+ strValue.substr(4,4)+"-"+strValue.substring(8);
			break;
		case 11:
			retValue = strValue.substr(0,3)+"-"+ strValue.substr(3,4)+"-"+strValue.substring(7);
			break;
		case 10:
			if(strValue.substring(0,2) == "02") {
				retValue = strValue.substr(0,2)+"-"+ strValue.substr(2,4)+"-"+strValue.substring(6);						
			}
			else {
				retValue = strValue.substr(0,3)+"-"+ strValue.substr(3,3)+"-"+strValue.substring(6);
			}					
			break;
		case 9:
			retValue = strValue.substr(0,2)+"-"+ strValue.substr(2,3)+"-"+strValue.substring(5);
			break;
		case 8:
			retValue = strValue.substr(0,4)+"-"+ strValue.substring(4);
			break;
		case 7:
			retValue = strValue.substr(0,3)+"-"+ strValue.substring(3);
			break;
		default:
			retValue = strValue;
			break;
	}
		return retValue;			
}
//if (StopWatch) alert("������ Service Code = 400032 "+StopWatchMsg);
var StopWatch = false;
var StopWatchS = 0;
var StopWatchE = 0;
var StopWatchSstr = '';
var StopWatchEstr = '';
var StopWatchMsg = '';
var StopWatchDur = 0;

function StopWatchStart() {
	if (!StopWatch) return;
	StopWatchS=new Date();
	StopWatchSstr=StopWatchS.getHours()+":"+StopWatchS.getMinutes()+":"+StopWatchS.getSeconds()+":"+StopWatchS.getMilliseconds();
	StopWatchS=StopWatchS.getTime();
}
function StopWatchStop() {
	if (!StopWatch) return;
	StopWatchE=new Date();
	StopWatchEstr=StopWatchE.getHours()+":"+StopWatchE.getMinutes()+":"+StopWatchE.getSeconds()+":"+StopWatchE.getMilliseconds();
	StopWatchE=StopWatchE.getTime();
	StopWatchDur=StopWatchE-StopWatchS;
	StopWatchMsg =	"start="+StopWatchSstr+" end="+StopWatchEstr+" Duration="+StopWatchDur+"ms";
}

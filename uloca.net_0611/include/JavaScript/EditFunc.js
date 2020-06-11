/* ---------------------------------------------------------------------
 *  Source Path		: /include/JavaScript/EditFunc.js
 *  시스템명		: 
 * 업무대중소분류	:
 * 프로그램설명		: java script utility
 * 파일명			: EditFunc.js
 * Called By		: All
 * Calling			:
 *  작성자			: 황명제
 *  작성날짜		: 2008. 05. 24
 *
 * ----------------------------------------------------------------------
 * 변경사항
 * 변경일자	변경자		내용
 * -------		-----			------------------------------
 * 
 
 /* =============================================================================================================
	Edit, Format 관련 함수
=============================================================================================================== */
///////////////////////////////////////////////////////////////////////////////
// 함수명 :format_phone()
// 내  용 : 전화번호 포멧을 자동변경한다.
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
// 함수명 :getFormattedPhone()
// 내  용 : 전화번호 포멧을 자동변경한다.
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
// 함수명 :f_remove_format()
// 내  용 : 모든 입력 포멧을 없앤다.
// Event : OnFocus시 호출되는 함수이다.
// Object : Input
///////////////////////////////////////////////////////////////////////////////
function f_remove_format(obj)
{
	obj = obj.replace(/(\,|\.|\-|\/|\:)/g,"");
	return obj;
}
// 금액 자리수 표시
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
		//alert("공백입니다.");
		return pnum;
	}
	num=num.replace(/,/gi,"");

	if(isNaN(num)) {
		//alert("숫자만 입력할 수 있습니다.");
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
/* =============================================================================================================
	주민등록번호,사업자번호,외국인번호체크  함수
=============================================================================================================== */


//주민등록번호 체크
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
		/* 2000 이후 태생인 주민등록번호 */
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
// 사업자등록번호 체크 
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

// 재외국인	번호 체크 
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

// check field all check or uncheck------------------------------------------------
function checkall(chks) {
	//chks=document.all("chk");
	tf=!chks[0].checked;
	for (i = 0; i < chks.length; i++)
	chks[i].checked = tf ;

}

/** 
* string String::cut(int len)
* 글자를 앞에서부터 원하는 바이트만큼 잘라 리턴합니다.
* 한글의 경우 2바이트로 계산하며, 글자 중간에서 잘리지 않습니다.
*/
String.prototype.cutstr = function(len) {
	var str = this;
	var l = 0;
	for (var i=0; i<str.length; i++) {
		l += (str.charCodeAt(i) > 128) ? 2 : 1;
		if (l > len) return str.substring(0,i);
	}
	return str;
	//return pdPackValue(str,len,false);
}

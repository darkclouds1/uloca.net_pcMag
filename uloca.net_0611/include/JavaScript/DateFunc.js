/* ---------------------------------------------------------------------
 *  Source Path		: /include/JavaScript/DateFunc.js
 *  �ý��۸�		: 
 * �������߼Һз�	:
 * ���α׷�����		: java script utility
 * ���ϸ�		: DateFunc.js
 * Called By		: All
 * Calling			:
 *  �ۼ���			: Ȳ����
 *  �ۼ���¥		: 2008. 05. 24
 *
 * ----------------------------------------------------------------------
 * �������
 * ��������	������		����
 * -------		-----			------------------------------
 * 
 
/* =============================================================================================================
	StopWatch ���� �Լ�
=============================================================================================================== */

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
/* =============================================================================================================
	�Ϲ�  �Լ�
=============================================================================================================== */

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
/* =============================================================================================================
	��¥,�ð� ���� �Լ�
=============================================================================================================== */

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

function getYear()
{
	var objDate;
	objDate = new Date();			//Date ��ü�� ����ϴ�.
	return objDate.getYear().toString();	//������ �����ɴϴ�.
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

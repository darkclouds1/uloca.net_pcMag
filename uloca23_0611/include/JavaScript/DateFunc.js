/* ---------------------------------------------------------------------
 *  Source Path		: /include/JavaScript/DateFunc.js
 *  시스템명		: 
 * 업무대중소분류	:
 * 프로그램설명		: java script utility
 * 파일명		: DateFunc.js
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
	StopWatch 관련 함수
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
	일반  함수
=============================================================================================================== */

// ----------------------------------------------------------------------------------
// 주민번호로 나이계산
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
	날짜,시간 관련 함수
=============================================================================================================== */

///////////////////////////////////////////////////////////////////////////////
// 함수명 : getDate()
// 내  용 :2005.01.28  오늘날짜 가져오는 함수 추가
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
	objDate = new Date();			//Date 개체를 만듭니다.
	return objDate.getYear().toString();	//연도를 가져옵니다.
}

///////////////////////////////////////////////////////////////////////////////
// 함수명 : getYearMonth()
// 내  용 :2005.01.28  오늘 년월 가져오기 함수
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
// 함수명 : getGapYear(gap)
// 내  용 :2005.01.28  오늘날짜 에서 gap(이전은 - , 이후는 +값 입력) 년도 만큼 차이나는 날자를 가져오는 함수
////////////////////////////////////////////////////////////////////////////////////////////////////
function getGapYear(gap)
{
	var objDate = new Date();			//Date 개체를 만듭니다.
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
// 함수명 : getGapMonth(gap)
// 내  용 :2005.01.28  오늘날짜 에서 gap(이전은 - , 이후는 +값 입력) 월만큼 차이나는 날자를 가져오는 함수
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





function add_date(i) // 매서드가 될 함수 구현
{
 var currentDate; // 계산된 날
 currentDate = this.getDate() + i*1;  // 현재 날짜에 더해(빼)줄 날짜를 계산
 this.setDate(currentDate);  // 계산된 날짜로 다시 세팅
}
Date.prototype.addDate = add_date; // Date 객체에 메서드 정의


////////////////////////////////////////////////////////////////////////////////////////////////////
// 함수명 : getGapDate(gap)
// 내  용 :2005.01.28  오늘날짜 에서 gap(이전은 - , 이후는 +값 입력) 만큼 차이나는 날자를 가져오는 함수
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
// 함수명 : getTime()
// 내  용 :2005.01.28  현재시간 가져오는 함수 추가
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

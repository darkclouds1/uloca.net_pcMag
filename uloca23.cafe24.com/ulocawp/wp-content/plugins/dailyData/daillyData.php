<?php
/*
 Plugin Name: dailyDataGathering : 매일 data.co.kr data 수집
 Plugin URI: http://uloca.net/ulocawp/wp-cotent/plugins/dailyData
 Description: 매일 data.co.kr data 수집
 Version: 1.0
 Author: Monolith
 Author URI: http://uloca.net/ulocawp/wp-cotent/plugins/dailyData
 
 입찰공고 수집
 사전규격 수집
 제한목록 수집
 개찰정보 수집
 낙찰 상세정보 수집
 */
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
function dailyDataGatheringShortCode() {
	date_default_timezone_set('Asia/Seoul');
	require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
	require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
	
	$g2bClass = new g2bClass;
	$dbConn = new dbConn;
	
	$conn = $dbConn->conn();
	$mobile = $g2bClass->MobileCheck();
	// --------------------------------- log
	$rmrk = '일일자료수집';
	$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
	// --------------------------------- log
	
	//openBidSeq_tmp :: 임시테이블 데이터갯수 확인 
	$thisYear = date('Y');
	if (isset($openBidInfo)== false || $openBidInfo == '') $openBidInfo = 'openBidInfo'; //_2018';
	if (isset($openBidSeq)== false || $openBidSeq == '') $openBidSeq = 'openBidSeq_'.$thisYear; //_2018';
	$openBidSeq_tmp = 'openBidSeq_tmp';
	//if ($lastdt == '') $lastdt= '2018-07-01 00:00:00';
	$sql = 'select count(*) as cnt from '.$openBidSeq_tmp  ;
	$result0 = $conn->query($sql);
	if ($row = $result0->fetch_assoc()) {
		$tmp_cnt = $row['cnt'];
	}

	//------------------------------------
	//($startDate) 데이터 수집 마지막일 확인
	//------------------------------------
	if (isset($startDate)== false || $startDate == '') {
		//$sql = 'select max(bidNtceDt) as startDate from '.$openBidInfo  ; // opengDt
		$sql = "select workdt as startDate from workdate where workname='".$openBidInfo."' ";
		//$sql = "select bidNtceDt from openBidInfo order by bidNtceDt desc limit 0,1";
		$result0 = $conn->query($sql);
		if ($row = $result0->fetch_assoc()) {
			$startDate = substr($row['startDate'],0,16);
			if ($startDate == 'NULL' || $startDate == '') $startDate = date("Y-m-d H:i");
		} else {
			$startDate = date("Y-m-d H:i");
		}
	}
	//--------------------------------------------------------------------------
	//$endDate 설정 :: 과거데이터 수집 시 30분 증가 (과거수집 체크박스) -by jsj 20190601
	//--------------------------------------------------------------------------
	$endDate = date('Y-m-d H:i') ; 	// ,$timestamp); //'20180720';
	$oldDataCheck = 0;				//재수집 데이터 시간 간격을 줄이기 위한 플래그
	
	//현재시간 - 마지막업뎃시간이 > 1800초 보다 크면 endDate는 마지막 업뎃 + 30분으로 함
	//과거데이터 수집 시 30분으로 줄일 :: 시간이 길면 타임오버
	$gapTime = strtotime($endDate) - strtotime($startDate);
	if ( $gapTime >= 10800 ) {  //3시간차이보다 크면 3시간으로 수집
		$endDate= date("Y-m-d H:i", strtotime("+3 hours",strtotime($startDate))); //3시간 더하기
		$oldDataCheck = 1;
	}
	
	?>
<!DOCTYPE html>
<html>
<head>
<title>ULOCA 매일 data.co.kr data 수집</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">
<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script src="/js/common.js?version=20190203"></script>
<script src="/g2b/g2b.js"></script>

</head>
<body>
<div id='procdiv' style='visibility:inline;position: fixed; top: 234px; right: 20px;'>
<input type="text" name="totalcnt" id="totalcnt" value="" style='text-align:center;' size=5 />
<input type="text" name="proccnt" id="proccnt" value=""  style='text-align:center;' size=5 />
<input type="text" name="seqcnt" id="seqcnt" value=""  style='text-align:center;' size=5 />
</div>
<div id="contents">
<div class="detail_search" >
<form action="/g2b/datas/dailyDataSearch.php" name="myForm" id="myForm" method="post" >

	<table align=center cellpadding="0" cellspacing="0" width="700px">
		<colgroup>
			<col style="width:15%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>
			<tr>
				<th>Table Name</th>
				<td>
					입찰정보<input class="input_style2" type="text" name="openBidInfo" id="openBidInfo" value="<?=$openBidInfo?>" maxlength="50" style="width:20%;text-align:center;"/>
					&nbsp;&nbsp;개찰정보 임시 (현재<?=$tmp_cnt?>건)
					<input class="input_style2" type="text" name="openBidSeq_tmp" id="openBidSeq_tmp" value="<?=$$openBidSeq_tmp?>" maxlength="50" style="width:20%;text-align:center;"/>
					&nbsp;&nbsp;->개찰정보
					<input class="input_style2" type="text" name="openBidSeq" id="openBidSeq" value="<?=$openBidSeq?>" maxlength="50" style="width:20%;text-align:center;"/>
					</td>
			</tr>
			<tr>
				<th>마지막 일시</th>
				<td>
					<input class="input_style1" type="text" name="startDate1" id="startDate1" value="<?=$startDate?>" maxlength="30" style="width:150px;text-align:center;"/>
					~ <input class="input_style2" type="text" name="endDate1" id="endDate1" value="<?=$endDate?>" maxlength="30" style="width:150px;text-align:center;">
					&nbsp;&nbsp;
					<input type="radio" name="lastdt" value="10" onclick="dtvalue(this)">익일10시
					<input type="radio" name="lastdt" value="11" onclick="dtvalue(this)">11시
					<input type="radio" name="lastdt" value="12" onclick="dtvalue(this)">12시
					<input type="radio" name="lastdt" value="14" onclick="dtvalue(this)">14시
					<input type="radio" name="lastdt" value="15" onclick="dtvalue(this)">15시
					<input type="radio" name="lastdt" value="16" onclick="dtvalue(this)">16시
					<input type="radio" name="lastdt" value="24" onclick="dtvalue(this)">24시
<? if ($mobile != "Mobile") { ?>				
					 <br>일시 포맷은 yyyy-mm-dd hh:mm 혹은 yyyymmdd hhmm 입니다.<!-- p style='color:red;font-size:11px;'>업무시간외에 실행 시키는 것이 좋습니다. 한번에 4시간이내로 하십시요.</p -->
					  하루치 입찰공고 건수가 약 1,500-2,000건이고 그 응찰 건수는 약 10만건이 넘습니다.<br>
					  입찰공고 수집후 개찰정보가 수집됩니다. (목록이 보인후 응찰건수가 한 라인별로 계산됩니다.)<br>
					  
					  <!-- 일과후 시간을 09:00 - 10:00,10:00 - 11:00, 11:00 - 14:00, 14:00 - 16:00, 16:00 - 23:59 5회/일 실행 하는게 좋을듯..<br -->
					  <p style='color:red;font-size:11px;'>입,개찰정보 시작하면 10분마다 자동 반복 실행합니다.</p>
					  
					  <input type="checkbox" name="oldDataCheck" value="과거데이터수집" id="oldDataCheck" <?php if ($oldDataCheck) { echo 'checked';} ?>/>
					  (체크되어 있으면 5초 마다 반복 실행합니다.)
					  <p style='color:blue;font-size:11px;'>개찰정보 수집에 너무 많은 시간이 걸리므로 tmp 를 거쳐 2단계로 수집합니다.</p>
<? } ?>
					</td>
			</tr>

		</tbody>
	</table>
	</form>
		<div class="btn_area" id='btn'>
			<a onclick="xxxsearchDaily();" class="bigbtn">수집</a>
			<a onclick="stopDaily();" class="bigbtn">중 지</a>
			남은시간 <input type="text" name="timer" id="timer" value="" style='text-align:center;font-size: 18px;color:blue;' size=5 />
			<!-- a onclick="searchDaily_tmp(1);" class="bigbtn">임시->개찰정보</a -->
			<!-- a onclick="searchDaily_tmp(2);" class="bigbtn">임시개찰정보 비우기</a -->
		</div>
</div></div>
<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
  <img src='http://uloca.net/g2b/loading3.gif' width='100px' height='100px'>
</div>
<br>
<div id = tablist></div>
<script>
function xxxsearchDaily() {
	clog('xxxsearchDaily()');
	if (doingsw == true)
	{
		alert("카운트 다운 중입니다. '중지' 버튼을 누르고 다시 클릭하세요.");
		return;
	}
	searchDaily();
}
function dtvalue(obj) {
//alert(obj.value);
	sdt = document.getElementById("startDate1");
	edt = document.getElementById("endDate1");
	if (obj.value == 10) edt.value = dateAddDel(sdt.value.substr(0,10), 1, 'd')+' 10:00';
	else if (obj.value == 11) edt.value = edt.value.substr(0,10)+' 11:00';
	else if (obj.value == 12) edt.value = edt.value.substr(0,10)+' 12:00';
	else if (obj.value == 14) edt.value = edt.value.substr(0,10)+' 14:00';
	else if (obj.value == 15) edt.value = edt.value.substr(0,10)+' 15:00';
	else if (obj.value == 16) edt.value = edt.value.substr(0,10)+' 16:00';
	else if (obj.value == 24) edt.value = edt.value.substr(0,10)+' 23:59';

}
function dataGether() {
	form = document.myForm;
	form.submit();
}

//------------------------------------
//1 : 임시->개찰정보
//2 : 임시개찰정보 비우기
//------------------------------------
var daily_func='';
function searchDaily_tmp(func) {
	frm = document.myForm;
	daily_func = func;
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
	// 임시테이블 처리 
	url = '/g2b/datas/dailyDataHandle.php?func='+func+'&openBidInfo='+openBidInfo + '&openBidSeq='+openBidSeq + '&openBidSeq_tmp='+openBidSeq_tmp;
	move();
	
	getAjax(url,moved_tmp);
}

function moved_tmp(data) {
	move_stop();
	clog('moved_tmp '+data);
	doagain();
	time = 600;  //10분마다 재수집 20200226
	timerclock();
}


function chageDateSelect(){
	return;

    var lastdt = document.getElementById("lastdt");

    var selDate = lastdt.options[lastdt.selectedIndex].value;
	//alert('selDate='+selDate+' selectedIndex='+lastdt.selectedIndex);
	form = document.myForm;
	if (selDate == '1')
	{
		tmp = dateAddDel(form.startDate.value.substr(0,10), 0, 'd');
		//tmp = dateAddDel(tmp, -1, 'd');
		form.endDate.value = tmp+' 23:59:59';
	} else if (selDate == 'w')
	{
		tmp = dateAddDel(form.startDate.value.substr(0,10), 1, 'w');
		//tmp = dateAddDel(tmp, -1, 'd');
		form.endDate.value = tmp+' 23:59:59';
	} else if (selDate == 'a')
	{
		tmp = new Date();
		//tmp = dateAddDel(tmp, -1, 'd');
		form.endDate.value = tmp.format('yyyy-MM-dd')+' 23:59:59';
	}
}

var url = '/ulocawp/?page_id=490';
var mywin;
function stopDaily() {
	clearTimeout(stoptimer);
	clearTimeout(stimer);
	doingsw = false;
	move_stop();
	alert('타이머가 중지 되었습니다.');
	document.getElementById('timer').value = '0:00';
	time=600;
}

var stoptimer;
var doingsw = true;
var time = 600;	//카운트 다운 
var stimer;		// 타이머
var gapTime = 600000; //재시작 시간 10분 
function doagain() {
	
	today = new Date();
	var hh = today.getHours();
	clog('doagain hh='+hh);

	var oldDataCheck = document.getElementsByName("oldDataCheck");
	
	//20시 이후이고 oldDataCheck가 false인 경우 재시작 하지 않음.
	// ==> 재시작은 시간에 관계없이 진행으로 변경  -by dc 20200226 
	//if (eval(hh)>=20 && !oldDataCheck[0].checked) return;
	//과거데이터 수집이면 5초 gap
	if(oldDataCheck[0].checked) {
		gapTime = 5000; //5초    
	}  
	url = '/ulocawp/?page_id=490'; 
	stoptimer = setTimeout(function () {
            location.href=url;
			//doagain();
        }, gapTime); // 3시간 gap으로 되어 있어 oldDataCheck 되어 있으면 5초 만에 다시 시작 
}

function timerclock() {
	stimer = setTimeout(function () {
		t = minsec(time);
            document.getElementById('timer').value = t;
			time--;
			timerclock();
        }, 1000); // 1초
}

function minsec(t) {
	min = Math.floor(t / 60);
	sec = t - (min * 60);
	if (sec<10) sec = '0'+sec;

	return min+':'+sec;
}

// 폼 열리면서 바로 시작 -by jsj 20200308
searchDaily(); 
time = 600; // 10분 카운트다운 

</script>
</body>
</html>
<?
}
add_shortcode('dailyDataGathering','dailyDataGatheringShortCode');

?>
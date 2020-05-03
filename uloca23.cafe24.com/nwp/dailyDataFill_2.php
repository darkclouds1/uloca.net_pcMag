<?

//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//ini_set('max_execution_time', 600);
echo "max_execution_time=" . ini_get('max_execution_time') . "<br>";

@extract($_GET);
@extract($_POST);
ob_start();
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
//http://uloca.net/nwp/dailyDataFill.php?startDate=20180930&endDate=20180930&pss=%EB%AC%BC%ED%92%88
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

$startDate = '';
$endDate = '';
$contn = 0;

// sumit 안하기 때문에 _POST 없음
// echo "_POST Value= startDate=" .$_POST['startDate']. ", enddate=" .$_POST['endDate']. ", contn=" .$_POST['contn']. "<br>";

if ($_POST['startDate'] != '') {
	$startDate = date("Y-m-d", strtotime($_POST['startDate']));
	$endDate   = date("Y-m-d", strtotime($_POST['endDate']));
} else {
	//최초 화면 열릴땐 startDate가 없으므로 DB의 lastdb를 가져옴
	$sql = " SELECT workdt, lastdt FROM workdate WHERE workname = 'dailyDataFill'";
	$result = $conn->query($sql);
	if ($row = $result->fetch_assoc()) {
		$workdt = $row['workdt'];
		$startDate = date("Y-m-d", strtotime($row['lastdt']));
		$endDate = date("Y-m-d", strtotime($row['lastdt']));
	}
	// echo "DB workTable 에서 가져옴 startDate=" . $startDate . ", endDate=" . $endDate . ", workdt=" . $workdt . "<br>";
}

$dur = substr($startDate, 0, 4);
$openBidInfo = "openBidInfo";
$openBidSeq  = "openBidSeq_" . $dur;

// 계속설정
if (isset($_POST['contn'])) {
	$contn = 1;    //on
} else {
	$contn = 0;
}
echo " startDate=" . $startDate . ", endDate=" . $endDate . ", 계속(contn)=" . $contn . "<br>";
// var_dump($_POST);

?>

<!DOCTYPE html>
<html>

<head>
	<title>낙찰결과 Fill </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="format-detection" content="telephone=no">
	<!--//-by jsj 전화걸기로 링크되는 것 막음 -->

	<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?version=20190102" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="/js/common.js"></script>
	<script src="/g2b/g2b.js"></script>
	<script src="/g2b/g2b_2019.js?version=20190203"></script>

	<script>
		stopOn = true;  
		bidSeqOn = false; // 개찰결과 업데이트할지 여부

		function doit() {
			move();
			searchDaily_Fill(); // 자료수집시작
		}

		function move() {
			document.getElementById('loading').style.display = 'inline'; //"none";= inline
		}

		function move_stop() {
			document.getElementById("loading").style.display = "none"; //"none";= inline
		}


		function stopit() {
			var contn = document.getElementById("contn");
			if (contn.checked){
				contn.checked = false;		// 중지시킴
				stopOn = true;				// 중지
				// alert("stopOn=" + stopOn)
			} else {
				contn.checked = true;		// 연속진행
				stopOn = false;				// 계속
				// alert("stopOn=" + stopOn)
			}
		}
		function openBidSeq_Fill() {
			var bidSeq = document.getElementById("bidSeq");
			if (bidSeq.checked){
				// alert("개찰결과는 입력하지 않음)
				bidSeqOn = false;
				bidSeq.checked = false;
			} else {
				bidSeqOn = true;
				bidSeq.checked = true;
				alert("ln110::openBidSeq_xxxx 에 업데이트::개찰결과를 입력합니다. bidSeqOn=" + bidSeqOn)
			}
		}

		function donextday() {  // form onload
			frm = document.myForm;
			// 계속여부 체크
			if (frm.contn.checked) {		
				dts = dateAddDel(frm.startDate.value, 1, 'd');
				today = new Date(); 
				today = today.getTime();
				dts1 = new Date(dts);
				dts1 = 	dts1.getTime();	
				// alert ("dts=" + dts1 + ", today_YMD=" + today);
				// 오늘날짜보다 크면 StopIt!
				if (dts1 > today) {
					bidSeqOn = false;
					frm.bidSeq.checked = false;
					alert ("오늘까지 완료하였습니다!");
					move_stop();
					return;
				}	
				frm.startDate.value = dts;
				frm.endDate.value = dts;
				frm.openBidSeq.value = "openBidSeq_" + dts.substr(0,4);				
				move_stop();
				setTimeout(function() {
					move();
					searchDaily_Fill();	// 자료수집시작
				}, 5000);				// 5초간 delay
			}
			move_stop();
			return;
		}

		//------------------------------------------------------------------------------------------
		// 자료수집 시작 -by jsj 20200414
		//------------------------------------------------------------------------------------------- */
		function searchDaily_Fill() {
			var frm = document.myForm;
			var startDate = frm.startDate.value;
			var endDate = frm.endDate.value;
			var openBidSeq = frm.openBidSeq.value;

			if (openBidSeq == '') {
				alert('ln140::개찰정보 테이블 명이 없습니다.');
				move_stop();
				return;
			}
			//------------------------------------------------------
			// 입찰정보 (from~to) 수집  , bidSeqOn=true 개찰이력 입력
			//------------------------------------------------------
			url = '/nwp/insertBidInfoFill.php?startDate=' + startDate + '&endDate=' + endDate + '&openBidInfo=openBidInfo&openBidSeq=' + openBidSeq + '&bidSeqOn=' +bidSeqOn;
			//document.getElementById('btn').style.display = 'none';
			getAjax(url, searchDaily2_Fill);
			console.log(url);
		}

		// daily Data 받아서 화면 (table) 표시 
		function searchDaily2_Fill(data) {
			var frm = document.myForm;
			var openBidSeq = frm.openBidSeq.value;
			
			// 개찰결과가 없는 공고목록 표시
			document.getElementById("specData").innerHTML = data;
			//$("#specData").append(data);
			setTotalRecords('specData', 'totalRecords');
			move_stop();

			// 개찰결과 업데이트 할건지 선택
			if (bidSeqOn) {	
				//alert ("bidSeqOn==ture, 개찰이력 입력")
				move();
				insertBidSeq_Fill();
			} else {
				donextday(); // 계속
			}
		}

		// 임시테이블 삭제 console.log 에 표시
		function moved_tmp(data) {
			console.log('ln192::moved_tmp='+data);
		}

		// total record 표시
		function setTotalRecords(tblid, totid) {
			var tbl = document.getElementById(tblid); //'specData'
			if (tbl == null) return;
			var tableRowCount = tbl.rows.length - 1;
			//alert('Total Records = '+lth+'/'+document.getElementById(totid).innerHTML);
			document.getElementById(totid).innerHTML = 'Total Records = ' + tableRowCount;
			document.getElementById('totalcnt').value = tableRowCount;
		}

		//  개찰결과 표시 
		var insIdx = 0;	
		var insTable = 'specData';
		var rowCount = 0;
		var noSeq = 0;
		function insertBidSeq_Fill() {

			// --------------------------
			// 개찰목록 입력할 목록 표시
			// --------------------------
			insTable = document.getElementById('specData');
			if (insTable == null) return;

			//테이블 Row 갯수 확인  
			rowCount = insTable.rows.length; 
			clog("data = "+ rowCount -1);
			if (rowCount < 2) {
				clog("data가 없습니다.");
				// 다음날 계속
				donextday();				
			}
			insIdx = 1; // 목록의 레코드 count 1부터 시작
			insertSeq2_Fill();
		}

		// 개찰결과 업데이트 
		function insertSeq2_Fill (){
			var frm = document.myForm;
			var openBidSeq = frm.openBidSeq.value;
			// openBidSeq_xxxx INSERT 시작점
			if (insIdx < rowCount) // rowCount
			{
				bidNtceNo  = insTable.rows[insIdx].cells[1].innerHTML.split('-')[0];
				bidNtceOrd = insTable.rows[insIdx].cells[1].innerHTML.split('-')[1];
				bidIdx     = insTable.rows[insIdx].cells[7].innerHTML;
				pss        = insTable.rows[insIdx].cells[6].innerHTML;
				
				// bidNtceOrd 도 보내야 함.. 기존엔 안보내는 걸로 되어 있음, 일일데이터 수집에도 확인 필요
				//url = '/nwp/insertBidSeqFill.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&bidIdx='+bidIdx;
				url = '/g2b/datas/dailyDataInsSeq_Fill.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&openBidInfo=openBidInfo'+'&openBidSeq='+openBidSeq+'&bidIdx='+bidIdx+'&pss='+pss;
				//url= '/g2b/datas/dailyDataInsSeq.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&openBidInfo=openBidInfo'+'&openBidSeq='+openBidSeq+'&bidIdx='+bidIdx+'&pss='+pss;
				// clog ("ln247::url="+url);
				getAjax(url, searchDaily3_Fill);

			}
		}

		//---------------------------------------------
		// dailyDataInsSeq.php에서 받은  Data를 Grid에 표시 
		//---------------------------------------------
		function searchDaily3_Fill(data) {
			var frm = document.myForm;
			var openBidSeq = frm.openBidSeq.value;

			// 응찰건수 컬럼
			if (data == '') data = 0;
			insTable.rows[insIdx].cells[8].innerHTML = data;
			
			//noSeq += eval(data); // msg 전달하면 에러나서 삭제 -by jsj 
			document.getElementById('proccnt').value = insIdx;
			document.getElementById('seqcnt').value = data;

			// table 목록 수 만큼 반복
			insIdx++;

			if (insIdx < rowCount) {
				insertSeq2_Fill(); 	// row 마지막까지 반복
			} else {
				rowCount--;
				clog('입찰정보= ' + rowCount + ' 건이 수집되었습니다.');
				//openBidSeq_Fill  임시테이블 옮기기
				url = '/g2b/datas/dailyDataHandle.php?&openBidInfo=openBidInfo&openBidSeq='+openBidSeq+'&openBidSeq_tmp=openBidSeq_Fill';
				getAjax(url,moved_tmp);
			}
		}

		function moved_tmp(data) {
			clog('moved_tmp '+data);
			move_stop();
			setTimeout( function() { 
				donextday(); 
			}, 10000);				// 20초간 delay
		}

		// sumit 시작
		//searchDailyFill();
	</script>

<body onload="javascript:donextday();">
	<div id='procdiv' style='visibility:inline;position: fixed; top: 234px; right: 20px;'>
		<input type="text" name="totalcnt" id="totalcnt" value="" style='text-align:center;' size=5 />
		<input type="text" name="proccnt" id="proccnt" value="" style='text-align:center;' size=5 />
		<input type="text" name="seqcnt" id="seqcnt" value="" style='text-align:center;' size=20 />
	</div>
	<form action="dailyDataFill_2.php" name="myForm" id="myform" method="post">
		<div id="contents">
			<div class="detail_search">
				<table cellpadding="0" cellspacing="0" width="700px" frame=void style="margin: auto; text-align: center;">
					<tbody>
						<tr>
							<td>Table</td>
							<td>
								<input class="input_style2" type="text" name="openBidInfo" id="openBidInfo" value="<?= $openBidInfo ?>" maxlength="30" style="width:20%;text-align:center;" />
								&nbsp;&nbsp; 개찰정보
								<input class="input_style2" type="text" name="openBidSeq" id="openBidSeq" value="<?= $openBidSeq ?>" maxlength="30" style="width:20%;text-align:center;" />
							</td>
						</tr>

						<tr>
							<td>기간 </td>
							<td>
								<input type="date" name="startDate" id="startDate" value="<?= $endDate ?>" onchange='document.getElementById("endDate").value = this.value' />
								~
								<input type="date" name="endDate" id="endDate" value="<?= $endDate ?>" />
								1주일 이내-하루
								<input type="checkbox" name="contn" id="contn" <? if ($contn) { ?> checked=checked <? } ?> disabled="disabled">계속
								<input type="checkbox" name="bidSeq" id="bidSeq" <? if ($bidSeqOn) { ?> checked=checked <? } ?> disabled="disabled">개찰결과 입력
							</td>
						</tr>
					</tbody>
				</table>
				<div class="btn_area">
					<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
					<a onclick="doit();" class="search">실행</a>
					<a onclick="stopit();" class="search">계속</a>
					<a onclick="openBidSeq_Fill();" class="search">개찰결과Fill</a>
				</div>
			</div>
		</div>

	</form>
	<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 50%;margin-top: -10px; margin-left: -50px; '>
		<img src='http://uloca23.cafe24.com/g2b/loading3.gif' width='100px' height='100px'>
	</div>
	<div style='font-size:14px ;font-weight:bold'>- 입찰공고 / 낙찰현황 / 낙찰목록 API  <br>
		1) 입찰정보(등록일 기준): 입찰공고 UPDATE, 없으면 REPLACE, 개찰결과가 없거나 신규입력을 화면에 보여줌, 개찰일시 > 오늘날짜(-1 day) 제외  <br/>
		2) 낙찰목록(개찰일 기준): 낙찰사업자번호가 없으면 ★ 진행구분(progrsDivCdNm) UPDATE, 공고가 없으면 openBidSeq_status '01'에 진행구분 REPLACE <br/>
		3) 낙찰현황(개찰일 기준); 낙찰1순위 정보 UPDATE, 공고없으면 openBidSeq_status '01' 에 ★ 낙찰현황정보 및 진행구분(progrsDivCdNm)='개찰완료' REPLACE <br/>
	</div>

	<div id='totalRecords'>total records=<?= $countItem ?> </div>
	<!-- 공고목록 테이블 헤드 -->
	<table class='type10' id='specData'>

	<?
	// ------------------------------------------------------
	// 결과표시 -by jsj 20200328
	// ------------------------------------------------------
	$sql = " REPLACE INTO " .$openBidSeq;
	$sql .= " SELECT * FROM openBidSeq_Fill ";
	if ($conn->query($sql)) {
		$sql = " TRUNCATE openBidSeq_Fill ";
		if ($conn->query($sql) == false) {
			echo "ln354::Error sql=" . $sql;
		}
	} else {
		echo "ln359::Error sql=" .$sql;
	}

	// statusCd=01 배치) 낙찰정보 업데이트 status '01'의 낙찰정보를 입찰정보에 UPDATE
	$sql = " UPDATE openBidInfo, openBidInfo_status ";
	$sql .= "   SET openBidInfo.prtcptCnum = openBidInfo_status.prtcptCnum, ";
	$sql .= "       openBidInfo.bidwinnrNm = openBidInfo_status.bidwinnrNm, ";
	$sql .= "       openBidInfo.bidwinnrBizno = openBidInfo_status.bidwinnrBizno, ";
	$sql .= "       openBidInfo.sucsfbidAmt = openBidInfo_status.sucsfbidAmt, ";
	$sql .= "       openBidInfo.sucsfbidRate = openBidInfo_status.sucsfbidRate, ";
	$sql .= "       openBidInfo.rlOpengDt = openBidInfo_status.rlOpengDt, ";
	$sql .= "       openBidInfo.bidwinnrCeoNm = openBidInfo_status.bidwinnrCeoNm, ";
	$sql .= "       openBidInfo.bidwinnrTelNo = openBidInfo_status.bidwinnrTelNo, ";
	$sql .= "       openBidInfo.progrsDivCdNm = openBidInfo_status.progrsDivCdNm, "; // 진행구분 "개찰완료"
	$sql .= "       openBidInfo_status.status_rs = 'Y' "; 							 // 처리 완료로 변경
	$sql .= " WHERE openBidInfo.bidNtceNo = openBidInfo_status.bidNtceNo ";			 // 낙찰현황 완료는 공고번호만 일치하면 전체 업데이트
	$sql .= "   AND openBidInfo_status.status_rs = 'N' "; 							 // 미처리
	$sql .= "   AND openBidInfo_status.statusCd = '01' "; 							 // 낙찰현황정보 있음
	if ($conn->query($sql) <> true) {
		echo "Error (openBidInfo_Status):" . $sql;
	}

	// 배치2) 입찰정보에 낙찰정보가 업데이트되어 있거나, 취소 공고인 경우
	// 낙찰정보있으면 완료로 변경
	$sql = " UPDATE openBidInfo_status ";
	$sql .= "   SET status_rs = 'Y' "; 		//처리완료
	$sql .= " WHERE status_rs = 'N' ";
	$sql .= "   AND bidNtceNo IN ( ";
	$sql .= "       SELECT bidNtceNo FROM openBidInfo where 1 ";
	$sql .= "          AND bidwinnrBizno <> '' ";    		   				   // 낙찰결과있음
	$sql .= "           OR ntceKindNm IN ('취소', '재입찰', '연기') ";			//  일반, < 변경, 취소, 재입찰, 연기>,  긴급, 갱신, 긴급갱신
	$sql .= "           OR bidMethdNm NOT IN ('전자입찰' )";    			    // 입찰방식이 '전자입찰' 이 아니면 낙찰결과가 없음
	$sql .= "           OR progrsDivCdNm IN ('유찰', '개찰완료', '재입찰') ";   //  유찰, 개찰완료, 재입찰
	$sql .= "           OR rgstTyNm = '연계기관 공고건' )";    					// 연계기관 공고건
	if ($conn->query($sql) <> true) echo "Error (openBidInfo_Status):" . $sql;

	// 업데이트 완료된 건은 삭제 함
	// $sql = " DELETE FROM openBidInfo_status WHERE status_rs = 'Y' ";
	// if ($conn->query($sql) <> true) echo "SQL error= " . $sql . "<br>";
	// <status상태> 결과에 openBidInfo_status 상태를 보여줌
	echo "<br>------ openBidInfo_Status 상태정보 -------------<br>";
	$sql = " SELECT statusCd, status_rs, COUNT(statusCd) as Cnt from openBidInfo_status GROUP BY statusCd, status_rs ";
	echo "ln629 sql=' " . $sql . " '<br>";

	$dbRst = $conn->query($sql);
	while ($arr = $dbRst->fetch_assoc()) {
		switch ($arr['statusCd'] . $arr['status_rs']) {
			case "01N":
				echo " (01)낙찰목록비교 공고번호없고, 진행구분-확보= " . $arr['Cnt'] . "건,";
				break;
			case "01Y":
				echo " (완료)낙찰목록비교 공고번호없고, 완료= " . $arr['Cnt'] . "건, <br>";
				break;
			case "02N":
				echo " (02)입찰정보비교 낙찰정보없음=" . $arr['Cnt'] . "건, ";
				break;
			case "02Y":
				echo " (완료)입찰정보비교 낙찰정보없음, 완료=" . $arr['Cnt'] . "건, <br>";
				break;
			case "03N":
				echo " (03)입찰정보비교 공고신규입력=" . $arr['Cnt'] . "건, ";
				break;
			case "03Y":
				echo " (완료)입찰정보비교 공고신규입력, 완료=" . $arr['Cnt'] . "건, <br>";
				break;
		}
	}

	?>
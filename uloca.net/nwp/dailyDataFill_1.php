<?

use PhpMyAdmin\Console;

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

echo " startDate=" . $_POST['startDate'] . ", enddate=" . $_POST['endDate'] . ", contn=" . $_POST['contn'] . ", stop=" . $_POST['stop'] . "<br>";
$dur = '2020';
if ($_POST['startDate'] != '') {
	$dur = substr($startDate, 0, 4);
}

$openBidInfo = 'openBidInfo'; //.$dur;
$openBidSeq = 'openBidSeq' . '_' . $dur;

if (isset($_POST['contn'])) {
	$contn = 1;    //on
} else {
	$contn = 0;
}

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

$sql = "select workdt, lastdt from workdate where workname = 'dailyDataFill'";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$workdt = $row['workdt'];
	$lastdt = $row['lastdt'];
}

echo "workdt=" . $workdt . ", lastdt=" . $lastdt;

?>

<!DOCTYPE html>
<html>

<head>
	<title>ULOCA</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/ulocawp/wp-content/themes/one-edge/style.css" />
	<link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css" />
	<link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css" />
	<link rel="stylesheet" type="text/css" href="http://uloca23.cafe24.com/g2b/css/g2b.css" />
	<link rel="stylesheet" href="http://uloca23.cafe24.com/jquery/jquery-ui.css">
	<script src="http://uloca23.cafe24.com/jquery/jquery.min.js"></script>
	<script src="http://uloca23.cafe24.com/jquery/jquery-ui.min.js"></script>
	<script src="http://uloca23.cafe24.com/g2b/g2b.js"></script>
	<script src="/dhtml/codebase/dhtmlx.js"></script>
	<script>
		function doit() {
			frm = document.myForm;
			frm.submit();
		}

		function stopit() {
			var contn = document.getElementById("contn");
			contn.checked = false;
			alert("stop!");
			return;
		}

		function donextday() {
			frm = document.myForm;
			if (frm.startDate.value > '<?= $endDate ?>') {
				return;
			}
			//오늘날짜 이후는 무의미
			if (frm.startDate.value >= '<?= $workdt ?>') {
				return
			}

			if (frm.contn.checked) {
				dts = dateAddDel(frm.startDate.value, 1, 'd');
				frm.startDate.value = dts;
				frm.endDate.value = dts;
				frm.submit();
			} else {
				frm.contn.checked = false;
				return;
			}
		}
	</script>

<body onload="javascript:donextday();">
	<form action="dailyDataFill_1.php" name="myForm" id="myform" method="post">
		<div id="contents">
			<div class="detail_search">
				<table align=center cellpadding="0" cellspacing="0" width="700px">
					<colgroup>
						<col style="width:20%;" />
						<col style="width:auto;" />
					</colgroup>
					<tbody>
						<tr>
							<th>기간</th>
							<td>
								<div class="calendar">
									<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?= $endDate ?>" style="width:76px;" readonly='readonly' onchange='document.getElementById("endDate").value = this.value' />
									~
									<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?= $endDate ?>" style="width:76px;" readonly='readonly' />

									1주일 이내-하루
									<input type="checkbox" name="contn" id="contn" <? if ($contn) { ?> checked=checked <? } ?>>계속

									<div id="datepicker"></div>
								</div>
			</div>
			</td>
			</tr>
			</table>
			<div class="btn_area">
				<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
				<a onclick="doit();" class="search">실행</a>
				<a onclick="stopit();" class="search">STOP</a>

			</div>
		</div>
		</div>
	</form>

	<center>
		<div style='font-size:14px; color:blue;font-weight:bold'>- 입찰정보 / 낙찰업데이트 1등정보 (입찰/낙찰 연관없음) <br>
			입찰정보(등록일시기준): DB에 없는것만 INSERT, 낙찰정보(개찰일시기준): DB에 공고번호로 업데이트 </div>
	</center>

	<table class="type10" id="specData" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
		<thead>
			<tr>
				<th scope="cols" width="5%;">순위</th>
				<th scope="cols" width="12%;">공고번호</th>
				<th scope="cols" width="20%;">공고명</th>
				<th scope="cols" width="15%;">공고기관</th>
				<th scope="cols" width="12%;">수요기관</th>
				<th scope="cols" width="10%;">개찰일시</th>
				<th scope="cols" width="6%;">구분</th>
				<th scope="cols" width="6%;">공고Index</th>
				<th scope="cols" width="6%;">응찰건수</th>
				<th scope="cols" width="10%;">낙찰업체</th>

			</tr>
		</thead>
		<tbody>

			<?php
			// function insertOpenBidInfo '20180912933'
			// openBidInfo = table명, arr=가져온입찰정보, pss=입찰종류, dbResult=DB의 입찰정보 -by jsj 200320		
			function insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $arr, $pss, $dbResult)
			{
				global $insertStatusRecord, $newInfoRecord, $updateRecord2;

				if ($row = $dbResult->fetch_assoc()) {
					// 공고번호있으나 낙찰결과 없으면 '02' Status에 추가
					if ($row["bidwinnrBizno"] == "") {
						$sql = " REPLACE INTO openBidInfo_status (bidNtceNo, bidNtceOrd, statusCD, bidNtceDt, opengDt) ";
						$sql .= "VALUES ('" . $arr['bidNtceNo'] . "', '" . $arr['bidNtceOrd'] . "', '02', '" . $arr['bidNtceDt'] . "','" . $arr['opengDt'] . "')";
						if ($conn->query($sql) <> true) {
							echo "REPLACE Error(openBidInfo_status):" . $sql . "<br>";
						}
						$insertStatusRecord++;
						return true;
					}
				} else { // 공고신규입력하고, '03' Status에 추가 -by jsj 200320
					$sql = 'INSERT INTO ' . $openBidInfo . ' (bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
					$sql .= 'reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
					$sql .= 'presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo)';
					$sql .= "VALUES ('" . $arr['bidNtceNo'] . "', '" . $arr['bidNtceOrd'] . "', ";
					$sql .= "'" . addslashes($arr['bidNtceNm']) . "', '" . addslashes($arr['ntceInsttNm']) . "', ";
					$sql .= "'" . addslashes($arr['dminsttNm']) . "', '" . $arr['opengDt'] . "', '" . $pss . "', ";
					$sql .= "'" . $arr['reNtceYn'] . "', '" . $arr['rgstTyNm'] . "', ";
					$sql .= "'" . $arr['ntceKindNm'] . "', '" . $arr['bidNtceDt'] . "', ";
					$sql .= "'" . $arr['ntceInsttCd'] . "', '" . $arr['dminsttCd'] . "', ";
					$sql .= "'" . $arr['bidBeginDt'] . "', '" . $arr['bidClseDt'] . "', ";
					$sql .= "'" . $arr['presmptPrce'] . "', '" . $arr['bidNtceDtlUrl'] . "', ";
					$sql .= "'" . $arr['bidNtceUrl'] . "', '" . $arr['sucsfbidLwltRate'] . "', ";
					$sql .= "'" . $arr['bfSpecRgstNo'] . "');";

					if ($conn->query($sql)) {
						$sql = " REPLACE INTO openBidInfo_status (bidNtceNo, bidNtceOrd, statusCD, bidNtceDt, opengDt) ";
						$sql .= "VALUES ('" . $arr['bidNtceNo'] . "', '" . $arr['bidNtceOrd'] . "', '03', '" . $arr['bidNtceDt'] . "','" . $arr['opengDt'] . "')";
						if ($conn->query($sql) <> true) {
							echo "REPLACE Error(openBidInfo_status):" . $sql . "<br>";
						}
					} else {
						echo "INSERT Error(openBidInfo):" . $sql . "<br>";
						return false;
					}
					$newInfoRecord++;
					return true;
				}
			}

			// 낙찰정보 업데이트, 공고번호 RETURN  -by jsj 200321
			function updateOpenBidInfo($conn, $openBidInfo, $arr, $row)
			{
				// 낙찰결과로 입찰공고 업뎃 - 이미 있어도 무조건 업데이트
				$sql = "UPDATE openBidInfo SET "; // bidNtceOrd ='". $arr['bidNtceOrd'] . "', ";
				$sql .= " prtcptCnum = '" . $arr['prtcptCnum'] . "', bidwinnrNm = '" . $arr['bidwinnrNm'] . "', ";
				$sql .= " bidwinnrBizno = '" . $arr['bidwinnrBizno'] . "', sucsfbidAmt = '" . $arr['sucsfbidAmt'] . "', ";
				$sql .= " sucsfbidRate = '" . $arr['sucsfbidRate'] . "', rlOpengDt = '" . $arr['rlOpengDt'] . "', ";
				$sql .= " bidwinnrCeoNm = '" . $arr['bidwinnrCeoNm'] . "', bidwinnrTelNo = '" . $arr['bidwinnrTelNo'] . "' ";
				$sql .= " WHERE bidNtceNo=' " . $row['bidNtceNo'] . "' ";
				$sql .= "   AND bidntceOrd=' " . $row['bidNtceOrd'] . "' ";
				if ($conn->query($sql)) {
					global $updateRecord2;
					$updateRecord2++;
					return TRUE;
				} else {
					echo "Update Error:" . $sql;
					return FALSE;
				}
			}

			// -by jsj 사용하지않음 (해당일자의 낙찰정보와 입찰정보가 매치 않됨)
			function findjson($arr, $col, $val, $item)
			{
				$i = 0;
				//echo 'findjson col='.$col.' val='.$val;
				foreach ($item as $arr2) {	// $arr['bidNtceNo']
					if ($arr2['bidNtceNo'] == $val) {
						echo 'findjson i=' . $i . '<br>';
						return $i; // if ($arr2[$col] == $val) return $i;
					}
					$i++;
				}
				return -1;
			}
 
			// ----화면표시----------------------------------- 
			function insertTableBidInfo($i, $arr, $pss, $prtcptCnum, $bidwinnrNm)
			{
				$tr = '<tr>';
				$tr .= '<td style="text-align: center;">' . $i . '</td>';
				$tr .= '<td style="text-align: center;">' . $arr['bidNtceNo'] . '-' . $arr['bidNtceOrd'] . '</td>';
				$tr .= '<td>' . $arr['bidNtceNm'] . '</td>';
				$tr .= '<td>' . $arr['ntceInsttNm'] . '</td>';
				$tr .= '<td>' . $arr['dminsttNm'] . '</td>';
				$tr .= '<td style="text-align: center;">' . $arr['opengDt'] . '</td>';
				$tr .= '<td style="text-align: center;">' . $pss . '</td>';
				$tr .= '<td style="text-align: center;"> </td>';
				$tr .= '<td style="text-align: right;">' . $prtcptCnum . '</td>';
				$tr .= '<td style="text-align: left;">' . $bidwinnrNm . '</td>';
				$tr .= '</tr>';
				echo $tr;
			}

			function workdate($conn, $workname, $workdt, $lastdt)
			{
				$sql = " UPDATE workdate SET workdt='" . $workdt . "', lastdt='" . $lastdt . "'";
				$sql .= " Where workname='" . $workname . "' ";
				if ($conn->query($sql) <> true) {
					echo "SQL Error: " . $sql;
				}
			}

			// DB 작업 시작
			if ($startDate == '' || $endDate == '') {
				exit;
			}

			if (strlen($startDate) == 10) $startDate = substr($startDate, 0, 4) . substr($startDate, 5, 2) . substr($startDate, 8, 2);
			if (strlen($endDate) == 10) $endDate = substr($endDate, 0, 4) . substr($endDate, 5, 2) . substr($endDate, 8, 2);
			$startDate .= '0000'; //=$g2bClass->changeDateFormat($startDate);
			$endDate  .= '2359'; //=$g2bClass->changeDateFormat($endDate);

			$kwd = '';
			$dminsttNm = '';
			$numOfRows = 999; //8000;
			$pageNo = 1;

			// 입찰정보 
			$inqryDiv = 1; // 1.등록일시, 2.입찰공고번호, 3.변경일시
			$bidrdo1 = 'bidthing'; //$bidrdo2 = '물품';
			$response11 = $g2bClass->getSvrData($bidrdo1, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
			$json11 = json_decode($response11, true);
			$item11 = $json11['response']['body']['items'];
			$bidrdo1 = 'bidcnstwk'; //$bidrdo2 = '공사';
			$response12 = $g2bClass->getSvrData($bidrdo1, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
			$json12 = json_decode($response12, true);
			$item12 = $json12['response']['body']['items'];
			$bidrdo1 = 'bidservc'; //$bidrdo2 = '용역';
			$response13 = $g2bClass->getSvrData($bidrdo1, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
			$json13 = json_decode($response13, true);
			$item13 = $json13['response']['body']['items'];
			// 입찰정보 Sum
			$item10 = array_merge($item11, $item12, $item13);
			$item = count($item10);

			//낙찰정보 -by jsj 200320
			$inqryDiv = 2;	//1.등록일시(공고개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
			$pss = '물품';
			$response2 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json2 = json_decode($response2, true);
			$item2 = $json2['response']['body']['items'];
			$pss = '공사';
			$response3 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json3 = json_decode($response3, true);
			$item3 = $json3['response']['body']['items'];
			$pss = '용역';
			$response4 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json4 = json_decode($response4, true);
			$item4 = $json4['response']['body']['items'];
			// 낙찰정보 sum
			$item22 = array_merge($item2, $item3, $item4);

			//var_dump ($item22); exit();
			// http://uloca.net/g2b/datas/dailyDataFill.php?startDate=2018-10-01-00:00&endDate=2018-10-01-23:59&pss=물품

			echo '<br>입찰정보=' . count($item10) . ' 낙찰정보=' . count($item22) . '<br>';

			$i = 1; // 그리드 목록 번호
			$newInfoRecord = 0;	    //입찰정보 추가 Count
			$insertStatusRecord = 0;  //낙찰정보 State에 추가 count
			$updateRecord2 = 0; //낙찰정보 업데이트 count
			// ----------------------------------------------
			// 입찰정보 INSERT or UPDATE(낙찰정보아님) -by jsj 200321
			// item11~13 입찰정보
			// 없는 입찰정보 INSERT, -by jsj 200320
			// 낙찰정보 업데이터 시 DB에 공고번호 없으면 다시 API조회해서 INSERT 필요함.
			// ----------------------------------------------
			foreach ($item11 as $arr) {
				$pss = '물품';
				// DB 공고번호 조회
				$sql =  "SELECT * FROM " . $openBidInfo;
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "'";
				$sql .= "   AND bidNtceOrd= '" . $arr['bidNtceOrd'] . "';";
				$dbResult = $conn->query($sql);
				// 입찰정보 INSERT or UPDATE(낙찰정보아님)
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $arr, $pss, $dbResult); //DB에 있던 공고번호 return
				
				/* // 화면그리드표시: 조회날짜 입찰목록 보여주고 기존 DB 참가업체수 및 낙찰업체명도 표시
				$prtcptCnum = '';
				$bidwinnrNm = '';
				if ($row = $dbResult->fetch_assoc()) {
					$prtcptCnum = $row['prtcptCnum'];
					$bidwinnrNm = $row['bidwinnrNm'];
				}
				insertTableBidInfo($i, $arr, $pss, $prtcptCnum, $bidwinnrNm);
				$i++;
				*/ 
			}

			foreach ($item12 as $arr) {
				$pss = '공사';
				// DB 공고번호 조회
				$sql =  "SELECT * FROM " . $openBidInfo;
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "'";
				$sql .= "   AND bidNtceOrd= '" . $arr['bidNtceOrd'] . "';";
				$dbResult = $conn->query($sql);
				// 입찰정보 INSERT or UPDATE(낙찰정보아님)
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $arr, $pss, $dbResult); //DB에 있던 공고번호 return
			}

			foreach ($item13 as $arr) {
				$pss = '용역';
				// DB 공고번호 조회
				$sql =  "SELECT * FROM " . $openBidInfo;
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "'";
				$sql .= "   AND bidNtceOrd= '" . $arr['bidNtceOrd'] . "';";
				$dbResult = $conn->query($sql);
				// 입찰정보 INSERT or UPDATE(낙찰정보아님)
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $arr, $pss, $dbResult); //DB에 있던 공고번호 return
			}

			// ==============================================
			// 낙찰결과 업데이트 -by jsj 200321 
			// item2~4 입찰정보 merge=item22
			// ==============================================
			$nonNtceNoCnt = 0;
			foreach ($item22 as $arr) {
				$pss = '낙찰';
				// DB 공고번호 조회
				$sql = " SELECT * FROM openBidInfo ";
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "' ";
				$sql .= "   AND bidNtceOrd = '" . $arr['bidNtceOrd'] . "' ";
				$dbResult = $conn->query($sql);
				if (($row = $dbResult->fetch_assoc()) == false) {
					// 업데이트할 공고가 없으면 continue
					// '01' Stauts 에 낙찰결과 INSERT
					$sql =  " REPLACE INTO openBidInfo_status (";
					$sql .= "              bidNtceNo,     bidNtceOrd, statusCD, ";
					$sql .= "              bidNtceDt,     opengDt, ";
					$sql .= "              prtcptCnum,    bidwinnrNm, ";
					$sql .= "              bidwinnrBizno, sucsfbidAmt, ";
					$sql .= "              sucsfbidRate,  rlOpengDt, ";
					$sql .= "              bidwinnrCeoNm, bidwinnrTelNo )";
					$sql .= " VALUES ('" . $arr['bidNtceNo'] . "',    '" . $arr['bidNtceOrd'] . "', '01', ";
					$sql .= "         '" . $arr['bidNtceDt'] . "',    '"  . $arr['opengDt'] . "', ";
					$sql .= "         '" . $arr['prtcptCnum'] . "',    '" . $arr['bidwinnrNm'] . "', ";
					$sql .= "         '" . $arr['bidwinnrBizno'] . "', '" . $arr['sucsfbidAmt'] . "', ";
					$sql .= "         '" . $arr['sucsfbidRate'] . "',  '" . $arr['rlOpengDt'] . "', ";
					$sql .= "         '" . $arr['bidwinnrCeoNm'] . "', '" . $arr['bidwinnrTelNo'] . "')";
					if ($conn->query($sql) <> TRUE) {
						echo "REPLACE Error:" . $sql . "<br>";
					}
					$nonNtceNoCnt++; //공고없음
					continue;
				}

				//공고번호 있으면 업데이트하고, Status_rs 변경
				$rtn = updateOpenBidInfo($conn, $openBidInfo, $arr, $row);
				// 화면그리드표시
				$tr = '<tr>';
				$tr .= '<td style="text-align: center;">' . $i . '</td>';
				$tr .= '<td style="text-align: center;">' . $arr['bidNtceNo'] . '-' . $arr['bidNtceOrd'] . '</td>';
				$tr .= '<td>' . $arr['bidNtceNm'] . '</td>';
				$tr .= '<td>' . $row['ntceInsttNm'] . '</td>';  //공고기관
				$tr .= '<td>' . $arr['dminsttNm'] . '</td>';
				$tr .= '<td style="text-align: center;">' . $row['rlOpengDt'] . '</td>'; //개찰일시
				$tr .= '<td style="text-align: center;">' . $pss . '</td>';
				$tr .= '<td style="text-align: center;">' . $row['idx'] . '</td>'; //공고Index
				$tr .= '<td style="text-align: right;">' . $arr['prtcptCnum'] . '</td>';
				$tr .= '<td style="text-align: left;">' . $arr['bidwinnrNm'] . '</td>';
				$tr .= '</tr>';
				echo $tr;
				$i++;
			}

			echo "---------- 결과 --------------------------------<br>";
			echo "** 가져온 낙찰정보를 UPDATE= " . $updateRecord2 . " 건" . "**<br> ";
			echo " 01)낙찰정보비교 공고번호없음= " . $nonNtceNoCnt . " 건" . ", <br> ";		  //status에 레코드 추가
			echo " 02)입찰정보비교 낙찰정보없음= " . $insertStatusRecord . " 건" . ", <br> "; // status에 레코드 추가
			echo " 03)입찰정보비교 공고신규입력= " . $newInfoRecord . " 건<br>";			  // 입찰공고에 레코드 추가

			// 배치) 낙찰정보 업데이트 status '01'의 낙찰정보를 입찰정보에 UPDATE
			$sql = " UPDATE openBidInfo, openBidInfo_status ";
			$sql .= "   SET openBidInfo.prtcptCnum = openBidInfo_status.prtcptCnum, ";
			$sql .= "       openBidInfo.bidwinnrNm = openBidInfo_status.bidwinnrNm, ";
			$sql .= "       openBidInfo.bidwinnrBizno = openBidInfo_status.bidwinnrBizno, ";
			$sql .= "       openBidInfo.sucsfbidAmt = openBidInfo_status.sucsfbidAmt, ";
			$sql .= "      	openBidInfo.sucsfbidRate = openBidInfo_status.sucsfbidRate, ";
			$sql .= "       openBidInfo.rlOpengDt = openBidInfo_status.rlOpengDt, ";
			$sql .= "      	openBidInfo.bidwinnrCeoNm = openBidInfo_status.bidwinnrCeoNm, ";
			$sql .= "      	openBidInfo.bidwinnrTelNo = openBidInfo_status.bidwinnrTelNo, ";
			$sql .= "       openBidInfo_status.status_rs = 'Y' "; //처리로
			$sql .= " WHERE openBidInfo.bidNtceNo = openBidInfo_status.bidNtceNo ";
			// $sql .= "   AND openBidInfo.bidNtceOrd = openBidInfo_status.bidNtceOrd "; //차수에 상관없이 업데이트
			$sql .= "   AND openBidInfo_status.status_rs = 'N' "; //미처리
			$sql .= "   AND openBidInfo_status.statusCd = '01' "; //낙찰정보 있음
			if ($conn->query($sql) <> true) {
				echo "Error (openBidInfo_Status):" . $sql;
			}

			// 배치2) 입찰정보에 낙찰정보가 업데이트되어 있다면, '02','03' status의 낙찰정보 완료로 변경
			$sql = " UPDATE openBidInfo, openBidInfo_status ";
			$sql .= "   SET openBidInfo_status.status_rs = 'Y' "; //처리 완료
			$sql .= " WHERE openBidInfo.bidNtceNo = openBidInfo_status.bidNtceNo ";
			//$sql .= "   AND openBidInfo.bidNtceOrd = openBidInfo_status.bidNtceOrd "; // 차수에 상관없이 업데이트
			$sql .= "   AND openBidInfo.bidwinnrBizno <> '' ";    //입찰결과 사업자번호가 있음
			$sql .= "   AND openBidInfo_status.status_rs = 'N' "; //미처리
			$sql .= "   AND openBidInfo_status.statusCd IN ('02','03') "; //낙찰정보없음 코드
			if ($conn->query($sql) <> true) {
				echo "Error (openBidInfo_Status):" . $sql;
			}

			//<status상태> 결과에 openBidInfo_status 상태를 보여줌
			$sql = " SELECT statusCd, status_rs, COUNT(statusCd) as Cnt from openBidInfo_status GROUP BY statusCd, status_rs ";
			if ($dbRst = $conn->query($sql)) {
				//$row = $dbRst->fetch_assoc();
				//echo "num_row: " . $dbRst->num_rows . "<br>";
				echo "------ openBidInfo_Status 상태정보 -------------<br>";
				while ($arr = $dbRst->fetch_assoc()) {
					//echo $arr['statusCd'] . "= " . $arr['Cnt'] . "<br>";
					switch ($arr['statusCd'] . $arr['status_rs']) {
						case "01N":
							echo " (01)낙찰정보비교 공고번호없음= " . $arr['Cnt'] .  "건, ";
							break;
						case "01Y":
							echo " (01)(batch)공고번호입력완료=" . $arr['Cnt'] .  "건, <br>";
							break;
						case "02N":
							echo " (02)입찰정보비교 낙찰정보없음=" . $arr['Cnt'] .  "건, ";
							break;
						case "02Y":
							echo " (02)(batch)낙찰정보입력완료=" . $arr['Cnt'] .  "건, <br>";
							break;
						case "03N":
							echo " (03)입찰정보비교 공고신규입력=" . $arr['Cnt'] .  "건, ";
							break;
						case "03Y":
							echo " (03)(batch)낙찰정보입력완료=" . $arr['Cnt'] .  "건, <br>";
							break;
					}
				}
				/* 머가 문젠지 확인해 보기
				$i = 0;
				foreach ($row as $arr) {
					$Cnt[$i] = $arr['Cnt'];
					//echo $i . "," . $arr['statusCd'] . ", " . $arr['Cnt'] . "<br>" ;
					echo $arr['statusCd'] . "= " . $arr['Cnt'] . "<br>" ;
					++$i;
				} */
			} else {
				echo "SQL error(openBidInfo_status):" . $sql . "<br>";
			}

			// workdate, lasdt DB저장
			$workname = 'dailyDataFill';
			$workdt = date('Y-m-d', strtotime(date("Ymd"))); //Today
			$lastdt = date('Y-m-d', strtotime($endDate));
			workdate($conn, $workname, $workdt, $lastdt);

			?>
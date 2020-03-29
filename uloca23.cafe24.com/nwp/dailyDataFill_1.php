<?

use PhpMyAdmin\Console;

@extract($_GET);
@extract($_POST);
ob_start();
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
//http://uloca.net/nwp/dailyDataFill.php?startDate=20180930&endDate=20180930&pss=%EB%AC%BC%ED%92%88
require($_SERVER['DOCUMENT_ROOT'] .'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] .'/classphp/dbConn.php');
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

echo " startDate=" .$_POST['startDate'] .", enddate=" .$_POST['endDate'] .", contn=" .$_POST['contn'] .", stop=" .$_POST['stop'] ."<br>";
$dur = '2020';
if ($_POST['startDate'] != '') {
	$dur = substr($startDate, 0, 4);
}

$openBidInfo = 'openBidInfo'; //.$dur;
$openBidSeq = 'openBidSeq' .'_' .$dur;

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

echo "workdt=" .$workdt .", lastdt=" .$lastdt;

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
		var stopOn = false;
		function doit() {
			frm = document.myForm;
			frm.submit();
		}

		function stopit() {
			var contn = document.getElementById("contn");
			contn.checked = false;
			if (stopOn) {
				stopOn = false;
				contn.checked = true;
				alert("계속! = ")
			} else{
				stopOn = true;
				contn.checked = false;
				alert("stop! = ");
			}
			return;
		}

		function donextday() {
			setTimeout(function() {
			  if (stopOn){
				frm.contn.checked = false;
			  }
			}, 5000);

			frm = document.myForm;
			if (frm.startDate.value > '<?= $endDate ?>') {
				return;
			}
			//오늘날짜 이후는 무의미
			if (frm.startDate.value >= '<?= $workdt ?>') {
				return
			}
			// stopOn 
			if (stopOn) {
				frm.contn.checked = false;
				return;
			}
			if (frm.contn.checked) {
				dts = dateAddDel(frm.startDate.value, 1, 'd');
				frm.startDate.value = dts;
				frm.endDate.value = dts;
				frm.submit();

			} else {				
				frm.contn.checked = false;
			}
			return;
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
			// 1) 공고번호로 확인:: DB에 공고번호없으면 신규입력 및 낙찰정보 업뎃
			// 2) 공고번호 있으나 낙찰정보 없으면 낙찰정보 업뎃
			// openBidInfo = table명, arr=가져온입찰정보, pss=입찰종류, dbResult=DB의 입찰정보 -by jsj 200320		
			function insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $arr, $pss, $openBidSeq = 'openBidSeq_2020')
			{
				global $insertStatusRecord, $newInfoRecord, $updateRecord1, $updateRecord2, $updateRecord3, $updateProgrsDivCdNm;

				// DB 공고번호 조회
				$sql =  "SELECT * FROM " .$openBidInfo;
				$sql .= " WHERE bidNtceNo = '" .$arr['bidNtceNo'] ."'";
				$sql .= "   AND bidNtceOrd= '" .$arr['bidNtceOrd'] ."';";
				$dbResult = $conn->query($sql);

				if ($row = $dbResult->fetch_assoc()) {

					// 공고번호있으나 낙찰결과 없으면 '02' Status에 추가하고, seq 입력
					// 취소공고와 연계기관 공고건은 낙찰결과 없음, 
					// ntceKindNm: 일반, 변경, 취소, 재입찰, 연기, 긴급, 갱신, 긴급갱신
					if (($row["bidwinnrBizno"] == "") && ($row["ntceKindNm"] <> '취소' ) && ($row["ntceKindNm"] <> '연기' ) && ($row['rgsTyNm'] <> '연계기관 공고건')) {
						$sql = " REPLACE INTO openBidInfo_status (bidNtceNo, bidNtceOrd, statusCD, bidNtceDt, opengDt) ";
						$sql .= "VALUES ('" .$arr['bidNtceNo'] ."', '" .$arr['bidNtceOrd'] ."', '02', '" .$arr['bidNtceDt'] ."','" .$arr['opengDt'] ."')";
						if ($conn->query($sql) <> true) {
							echo "REPLACE Error(openBidInfo_status):" .$sql ."<br>";
						}
						$insertStatusRecord++; // 낙철정보 없음 '02' Status에 추가
						// seq 낙찰결과 업데이트 (openBidInfo 및 openBidSeq 테이블 2개)
						$rtn = openBidSeq_Update($g2bClass, $conn, $arr['bidNtceNo'], $arr['bidNtceOrd'], $openBidSeq);
						return true;
					}
				} else {

					// 공고없어서 신규입력 및 '03' Status에 추가 -by jsj 200320
					$sql = 'INSERT INTO ' .$openBidInfo .' (bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
					$sql .= '                               reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
					$sql .= '                               presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo)';
					$sql .= "VALUES ('"  .$arr['bidNtceNo'] ."', '"   .$arr['bidNtceOrd'] ."', ";
					$sql .= "'" .addslashes($arr['bidNtceNm']) ."', '" .addslashes($arr['ntceInsttNm']) ."', ";
					$sql .= "'" .addslashes($arr['dminsttNm']) ."', '".$arr['opengDt'] ."', '" .$pss ."', ";
					$sql .= "'"          .$arr['reNtceYn'] .  "', '"  .$arr['rgstTyNm'] ."', ";
					$sql .= "'"          .$arr['ntceKindNm'] ."', '"  .$arr['bidNtceDt'] ."', ";
					$sql .= "'"          .$arr['ntceInsttCd']."', '"  .$arr['dminsttCd'] ."', ";
					$sql .= "'"          .$arr['bidBeginDt']."', '"   .$arr['bidClseDt'] ."', ";
					$sql .= "'"          .$arr['presmptPrce']. "', '" .$arr['bidNtceDtlUrl'] ."', ";
					$sql .= "'"          .$arr['bidNtceUrl'] ."', '"  .$arr['sucsfbidLwltRate'] ."', ";
					$sql .= "'"          .$arr['bfSpecRgstNo'] ."');";
					if ($conn->query($sql)) {
						$sql = " REPLACE INTO openBidInfo_status (bidNtceNo, bidNtceOrd, statusCD, bidNtceDt, opengDt) ";
						$sql .= "VALUES ('" .$arr['bidNtceNo'] ."', '" .$arr['bidNtceOrd'] ."', '03', '" .$arr['bidNtceDt'] ."','" .$arr['opengDt'] ."')";
						if ($conn->query($sql) <> true) {
							echo "REPLACE Error(openBidInfo_status):" .$sql ."<br>";
						}
					} else {
						echo "INSERT Error(openBidInfo):" .$sql ."<br>";
						return false;
					}
					$newInfoRecord++; // 공고신규입력

					// seq 낙찰결과 업데이트 (openBidInfo 및 openBidSeq 테이블 2개)
					$rtn  = openBidSeq_Update($g2bClass, $conn, $arr['bidNtceNo'], $arr['bidNtceOrd'], $openBidSeq);
					
					return true;
				}
				// 공고번호 및 낙찰정보가 있으면 Insert 없음
				return false;
			}

			// 낙찰SEQ 업뎃::공고번호, 공고차수로 낙찰 API call -by jsj 20200328
			// 입찰정보에 1순위 및 낙찰_seq 두테이블에 모두 업뎃
			// $openBidSeq = 테이블네임_년도 포함
			function openBidSeq_Update($g2bClass, $conn, $bidNtceNo, $bidNtceOrd, $openBidSeq_xxxx)
			{
				// 낙찰현황 조회
				$response1 = $g2bClass->getRsltData($bidNtceNo, $bidNtceOrd);
				$json1 = json_decode($response1, true);
				$item1 = $json1['response']['body']['items'];

				$i = 1;
				$k = 1;
				if (count($item1) == 0) return false;
				foreach ($item1 as $arr) { //foreach element in $arr
					//---------------------------
					$k = (int) $arr["opengRank"]; //순위 - Null이 오는 경우 순서대로 순위를 매김
					if ($k == 0) $k = $i; 			// opengRank null 오는 경우, 순서대로 순위를 매김
					$rmrk = addslashes($arr['rmrk']);

					// rmrk 에 순위와 비고 같이 들어감 -by jsj 20200318
					if ($arr['rmrk'] == '') {  //openRank 비어있고, rmark 도 비어 있는 경우 순위만 대입
						$Rank_rmark = (string) $k;
					} else {
						$Rank_rmark = $arr['rmrk'];
					}

					// 999까지만 입력 (속도문제 고려)
					if ($k > 1000) return;

					// 1순위
					if ($k == 1) { //$i == 0) {
						//-------------------------------------------------
						//$openBidInfo 에 1순위 정보 업데이트 -by jsj 190601
						//-------------------------------------------------
						if ( updateOpenBidInfo($conn, $arr, $bidNtceNo, $bidNtceOrd )) {
							// SEQ 1순위 업뎃 갯수
							global $updateRecord2;
							$updateRecord2++;
						}

						// 1순위 화면 표시 
						$msg = "1순위 낙찰정보 업데이트= " .$bidNtceNo ."-" .$bidNtceOrd .", " .addslashes($arr['prcbdrNm']) .", " .$arr['prcbdrBizno'] .", 입찰업체수=" .count($item1) ."<br>";
					}

					// 입찰공고의 idx를 낙찰정보 bidindx에 업뎃 용
					$sql = " SELECT idx FROM openBidInfo WHERE bidNtceNo ='" .$bidNtceNo ."' AND bidNtceOrd ='" .$bidNtceOrd ."'";
					$result0 = $conn->query($sql);
					if ($row = $result0->fetch_assoc()) {
						$bididx = $row['idx'];
					} else {
						echo "Error sql=" .$sql ."<br>";
					}
					//---------------------------------------------------
					//$openBidSeq_xxxx 에 입찰이력 입력 -by jsj 190601
					//---------------------------------------------------
					$sql  = ' REPLACE INTO ' .$openBidSeq_xxxx .' ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx)';
					$sql .= "  VALUES ( '" .$bidNtceNo ."', '" .$bidNtceOrd ."', '" .$arr['rbidNo'] ."', '" .$arr['prcbdrBizno'] ."','" .$arr['bidprcAmt'] ."','" .$arr['bidprcrt'] ."',";
					$sql .= " '" .$arr['drwtNo1'] ."', '" .$arr['bidprcDt'] ."', '" .$Rank_rmark ."','" .$bididx ."')";
					if ($conn->query($sql) <> true) {
						echo "Error sql=" .$sql ."<br>";
						return false;
					}
					// SEQ 1순위 외 업뎃 갯수
					global $updateRecord3;
					$updateRecord3++;
					$i += 1;
				} //for each
			}

			// 낙찰정보 업데이트  -by jsj 200321
			function updateOpenBidInfo($conn, $arr, $bidNtceNo, $bidNtceOrd)
			{
				// 낙찰결과로 입찰공고 업뎃 - 이미 있으면 SKIP
				$sql = "UPDATE openBidInfo SET "; // bidNtceOrd ='".$arr['bidNtceOrd']."', ";
				$sql .= " prtcptCnum = '"    .$arr['prtcptCnum'] .   "', ";
				$sql .= " bidwinnrNm = '"    .$arr['bidwinnrNm'] .   "', ";
				$sql .= " bidwinnrBizno = '" .$arr['bidwinnrBizno'] ."', ";
				$sql .= " sucsfbidAmt = '"   .$arr['sucsfbidAmt'] .  "', ";
				$sql .= " sucsfbidRate = '"  .$arr['sucsfbidRate'] . "', ";
				$sql .= " rlOpengDt = '"     .$arr['rlOpengDt'] .    "', ";
				$sql .= " bidwinnrCeoNm = '" .$arr['bidwinnrCeoNm'] ."', ";
				$sql .= " bidwinnrTelNo = '" .$arr['bidwinnrTelNo'] ."', ";
				$sql .= " progrsDivCdNm = '개찰완료'";
				$sql .= " WHERE bidNtceNo= '"  .$bidNtceNo .  "' ";
				$sql .= "   AND bidntceOrd= '" .$bidNtceOrd . "' ";
				if ($conn->query($sql)) {
					global $updateRecord1;
					$updateRecord1++;
					return TRUE;
				} else {
					echo "Update Error:" .$sql;
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
						echo 'findjson i=' .$i .'<br>';
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
				$tr .= '<td style="text-align: center;">' .$i .'</td>';
				$tr .= '<td style="text-align: center;">' .$arr['bidNtceNo'] .'-' .$arr['bidNtceOrd'] .'</td>';
				$tr .= '<td>' .$arr['bidNtceNm'] .'</td>';
				$tr .= '<td>' .$arr['ntceInsttNm'] .'</td>';
				$tr .= '<td>' .$arr['dminsttNm'] .'</td>';
				$tr .= '<td style="text-align: center;">' .$arr['opengDt'] .'</td>';
				$tr .= '<td style="text-align: center;">' .$pss .'</td>';
				$tr .= '<td style="text-align: center;"> </td>';
				$tr .= '<td style="text-align: right;">' .$prtcptCnum .'</td>';
				$tr .= '<td style="text-align: left;">' .$bidwinnrNm .'</td>';
				$tr .= '</tr>';
				echo $tr;
			}

			// DB에 작업일자 저장
			function workdate($conn, $workname, $workdt, $lastdt)
			{
				$sql = " UPDATE workdate SET workdt='" .$workdt ."', lastdt='" .$lastdt ."'";
				$sql .= " Where workname='" .$workname ."' ";
				if ($conn->query($sql) <> true) {
					echo "SQL Error: " .$sql;
				}
			}

			// DB 작업 시작
			if ($startDate == '' || $endDate == '') {
				exit;
			}

			if (strlen($startDate) == 10) $startDate = substr($startDate, 0, 4) .substr($startDate, 5, 2) .substr($startDate, 8, 2);
			if (strlen($endDate) == 10) $endDate = substr($endDate, 0, 4) .substr($endDate, 5, 2) .substr($endDate, 8, 2);
			$startDate .= '0000'; //=$g2bClass->changeDateFormat($startDate);
			$endDate .= '2359'; //=$g2bClass->changeDateFormat($endDate);

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

			// 낙찰정보 현황조회 -by jsj 200329
			$inqryDiv = 3;	//1.등록일시(공고개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
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
			
			// 낙찰정보 목록조회 -by jsj 200329 ? 
			// 낙찰정보 목록에 있는 낙찰진행(progrsDivCdNm (유찰, 개찰완료, 재입찰)) 
			$inqryDiv = 3;	//1.등록일시(공고개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
			$pss = '물품목록';
			$response5 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json5 = json_decode($response2, true);
			$item5 = $json5['response']['body']['items'];
			$pss = '공사목록';
			$response6 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json6 = json_decode($response3, true);
			$item6 = $json6['response']['body']['items'];
			$pss = '용역목록';
			$response7 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json7 = json_decode($response4, true);
			$item7 = $json7['response']['body']['items'];
			// 낙찰정보 sum
			$item23 = array_merge($item5, $item6, $item7);
			//var_dump ($item22); exit();
			// http://uloca.net/g2b/datas/dailyDataFill.php?startDate=2018-10-01-00:00&endDate=2018-10-01-23:59&pss=물품

			echo '<br>입찰정보=' .count($item10) .' 낙찰현황=' .count($item22) . ' 낙찰목록=' .count($item23) .'<br>';

			$i = 1; // 그리드 목록 번호
			$newInfoRecord = 0;	     //입찰정보 추가 Count
			$insertStatusRecord = 0; //낙찰정보 State에 추가 count
			$updateRecord1 = 0; 	//낙찰정보 업데이트 count
			$updateRecord2 = 0; 	//낙찰정보 업데이트 count (seq 1순위)
			$updateRecord3 = 0; 	//낙찰정보 업데이트 count (seq 1순위 외)
			$updateProgrsDivCdNm = 0; // 낙찰목록의 진행구분을 입찰공고에 업뎃 
			// ----------------------------------------------
			// 입찰정보 INSERT or UPDATE(낙찰정보아님) -by jsj 200321
			// item11~13 입찰정보
			// 없는 입찰정보 INSERT, -by jsj 200320
			// 낙찰정보 업데이터 시 DB에 공고번호 없으면 다시 API조회해서 INSERT 필요함.
			// ----------------------------------------------

			// opneBidInfo 입찰/낙찰정보 모두 있으면 Insert없음 count
			$nonInsertCnt = 0;

			foreach ($item11 as $arr) {
				$pss = '물품';
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $arr, $pss, $openBidSeq); //DB에 있던 공고번호 return		
				if (!$tf) $nonInsertCnt++;
			}
			foreach ($item12 as $arr) {
				$pss = '공사';
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $arr, $pss, $openBidSeq); //DB에 있던 공고번호 return
				if (!$tf) $nonInsertCnt++;
			}
			foreach ($item13 as $arr) {
				$pss = '용역';
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $arr, $pss, $openBidSeq); //DB에 있던 공고번호 return
				if (!$tf) $nonInsertCnt++;
			}

			//------------------------------------------------------------
			// 낙찰목록의 진행구분 업뎃 -by jsj 20200329
			// 낙찰목록에만 있음 (progrsDivCdNm: 유찰, 개찰완료, 재입찰)
			//------------------------------------------------------------
			foreach ($item23 as $arr) {
				// DB 공고번호 조회
				$sql = " SELECT * FROM openBidInfo ";
				$sql .= " WHERE bidNtceNo = '" .$arr['bidNtceNo'] ."' ";
				$sql .= "   AND bidNtceOrd = '" .$arr['bidNtceOrd'] ."' ";

				$dbResult = $conn->query($sql);
				if (($row = $dbResult->fetch_assoc()) == false) {
					// 업데이트할 공고가 없으면 continue
					continue;
				}
				//-------------------------------------------------------------
				// 공고번호 있고 진행구분 정보가 없으면  progrsDivCdNm 업데이트
				//-------------------------------------------------------------
				if ($row['progrsDivCdNm'] <> '') {
					// 낙찰결과로 입찰공고 업뎃 - 이미 있으면 SKIP
					$sql = " UPDATE openBidInfo SET "; // bidNtceOrd ='".$arr['bidNtceOrd']."', ";
					$sql .= "       progrsDivCdNm = '" .$arr['progrsDivCdNm']. "' "; // 유찰, 개찰완료, 재입찰 (낙찰정보)
					$sql .= " WHERE bidNtceNo=  '"     .$arr['bidNtceNo'].     "' ";
					$sql .= "   AND bidntceOrd= '"     .$arr['bidNtceOrd'].    "' ";
					if ($conn->query($sql)) {
						global $updateProgrsDivCdNm;
						$updateProgrsDivCdNm++;
						return TRUE;
					} else {
						echo "Update Error:" .$sql;
						return FALSE;
					}
				}
			}

			//-------------------------------------------
			// 낙찰결과 업데이트 -by jsj 200321 
			// item2~4 입찰정보 merge=item22
			//-------------------------------------------
			$nonNtceNoCnt = 0;
			foreach ($item22 as $arr) {
				$pss = '낙찰';
				// DB 공고번호 조회
				$sql = " SELECT * FROM openBidInfo ";
				$sql .= " WHERE bidNtceNo = '" .$arr['bidNtceNo'] ."' ";
				$sql .= "   AND bidNtceOrd = '" .$arr['bidNtceOrd'] ."' ";

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
					$sql .= " VALUES ('" .$arr['bidNtceNo'] ."',    '" .$arr['bidNtceOrd'] ."', '01', ";
					$sql .= "         '" .$arr['bidNtceDt'] ."',    '" .$arr['opengDt'] ."', ";
					$sql .= "         '" .$arr['prtcptCnum'] ."',    '" .$arr['bidwinnrNm'] ."', ";
					$sql .= "         '" .$arr['bidwinnrBizno'] ."', '" .$arr['sucsfbidAmt'] ."', ";
					$sql .= "         '" .$arr['sucsfbidRate'] ."',  '" .$arr['rlOpengDt'] ."', ";
					$sql .= "         '" .$arr['bidwinnrCeoNm'] ."', '" .$arr['bidwinnrTelNo'] ."')";
					if ($conn->query($sql) <> TRUE) {
						$msg = "REPLACE Error:" .$sql ."<br>";
					}
					$nonNtceNoCnt++; //공고없음
					continue;
				}

				//--------------------------------------------------------- 
				// 공고번호 있고 낙찰결과 정보가 없으면  openBidInto 업데이트
				//--------------------------------------------------------- 
				if ($row['bidwinnrBizno'] <> '') {
					$rtn = updateOpenBidInfo($conn, $arr, $$arr['bidNtceNo'], $arr['bidNtceOrd']);
				}

				//---------------------------------------
				// 낙찰결과로 업데이트한 것만 화면그리드표시
				//---------------------------------------
				$tr = '<tr>';
				$tr .= '<td style="text-align: center;">' .$i .'</td>';
				$tr .= '<td style="text-align: center;">' .$arr['bidNtceNo'] .'-' .$arr['bidNtceOrd'] .'</td>';
				$tr .= '<td>' .$arr['bidNtceNm'] .'</td>';
				$tr .= '<td>' .$row['ntceInsttNm'] .'</td>';  //공고기관
				$tr .= '<td>' .$arr['dminsttNm'] .'</td>';
				$tr .= '<td style="text-align: center;">' .$row['rlOpengDt'] .'</td>'; //개찰일시
				$tr .= '<td style="text-align: center;">' .$pss .'</td>';
				$tr .= '<td style="text-align: center;">' .$row['idx'] .'</td>'; //공고Index
				$tr .= '<td style="text-align: right;">' .$arr['prtcptCnum'] .'</td>';
				$tr .= '<td style="text-align: left;">' .$arr['bidwinnrNm'] .'</td>';
				$tr .= '</tr>';
				echo $tr;
				$i++;
			}

			// ------------------------------------------------------
			// 결과표시 -by jsj 20200328
			// ------------------------------------------------------
			echo "---------- 결과 --------------------------------<br>";
			echo "** 입찰정보에 낙찰정보 추가 UPDATE= " .$updateRecord1 . " 건" ."**<br> ";
			echo "** 낙찰목록의 진행구분 업뎃 UPDATE= " .$updateProgrsDivCdNm. " 건" ."**<br> "; // 진행구분: 유찰,개찰완료,재입찰 업뎃건수
			echo "** SEQ 1순위 낙찰정보 추가 UPDATE= " .$updateRecord2 . " 건" ."**<br> ";
			echo "** SEQ 순위외 낙찰정보 추가 UPDATE= " .$updateRecord3 . " 건" ."**<br> ";
			echo "** 공고번호/낙찰정보 있어서 SKIP= " .$nonInsertCnt ." 건" ."**<br> ";
			// echo "------------------------------------------------------------------------";
			echo " 01)낙찰정보비교 공고번호없음= " .$nonNtceNoCnt ." 건" .", <br> ";		  //status에 레코드 추가
			echo " 02)입찰정보비교 낙찰정보없음= " .$insertStatusRecord ." 건" .", <br> ";    // status에 레코드 추가
			echo " 03)입찰정보비교 공고신규입력= " .$newInfoRecord ." 건<br>";			      // 입찰공고에 레코드 추가

			// 배치) 낙찰정보 업데이트 status '01'의 낙찰정보를 입찰정보에 UPDATE
			$sql = " UPDATE openBidInfo, openBidInfo_status ";
			$sql .= "   SET openBidInfo.prtcptCnum = openBidInfo_status.prtcptCnum, ";
			$sql .= "       openBidInfo.bidwinnrNm = openBidInfo_status.bidwinnrNm, ";
			$sql .= "       openBidInfo.bidwinnrBizno = openBidInfo_status.bidwinnrBizno, ";
			$sql .= "       openBidInfo.sucsfbidAmt = openBidInfo_status.sucsfbidAmt, ";
			$sql .= "       openBidInfo.sucsfbidRate = openBidInfo_status.sucsfbidRate, ";
			$sql .= "       openBidInfo.rlOpengDt = openBidInfo_status.rlOpengDt, ";
			$sql .= "       openBidInfo.bidwinnrCeoNm = openBidInfo_status.bidwinnrCeoNm, ";
			$sql .= "       openBidInfo.bidwinnrTelNo = openBidInfo_status.bidwinnrTelNo, ";
			$sql .= "       openBidInfo.progrsDivCdNm = openBidInfo_status.progrsDivCdNm, "; // 진행구분
			$sql .= "       openBidInfo_status.status_rs = 'Y' "; //처리 완료로 변경
			$sql .= " WHERE openBidInfo.bidNtceNo = openBidInfo_status.bidNtceNo ";
			$sql .= "   AND openBidInfo_status.status_rs = 'N' "; //미처리
			$sql .= "   AND openBidInfo_status.statusCd = '01' "; //낙찰정보 있음
			if ($conn->query($sql) <> true) {
				echo "Error (openBidInfo_Status):" .$sql;
			}

			// 배치2) 입찰정보에 낙찰정보가 업데이트되어 있거나, 취소 공고인 경우
			// 낙찰정보있으면 완료로 변경
			$sql = " UPDATE openBidInfo_status ";
			$sql .= "   SET status_rs = 'Y' "; 				//처리완료
			$sql .= " WHERE status_rs = 'N' ";
			$sql .= "   and bidNtceNo IN ( ";
			$sql .= "       SELECT bidNtceNo FROM openBidInfo where 1 ";
			$sql .= "          AND bidwinnrBizno <> '' ";    		   // 낙찰결과있음
			$sql .= "           OR ntceKindNm IN ('변경', '취소', '재입찰', '연기') ";      //  일반, < 변경, 취소, 재입찰, 연기>,  긴급, 갱신, 긴급갱신
			$sql .= "           OR progrsDivCdNm IN ('유찰', '개찰완료', '재입찰') ";   //  유찰, 개찰완료, 재입찰
			$sql .= "           OR rgstTyNm = '연계기관 공고건' )";    // 연계기관 공고건

			if ($conn->query($sql) <> true) {
				echo "Error (openBidInfo_Status):" .$sql;
			}

			//<status상태> 결과에 openBidInfo_status 상태를 보여줌
			$sql = " SELECT statusCd, status_rs, COUNT(statusCd) as Cnt from openBidInfo_status GROUP BY statusCd, status_rs ";
			if ($dbRst = $conn->query($sql)) {
				//$row = $dbRst->fetch_assoc();
				//echo "num_row: ".$dbRst->num_rows."<br>";
				echo "------ openBidInfo_Status 상태정보 -------------<br>";
				while ($arr = $dbRst->fetch_assoc()) {
					//echo $arr['statusCd']."= ".$arr['Cnt']."<br>";
					switch ($arr['statusCd'] .$arr['status_rs']) {
						case "01N":
							echo " (01)낙찰정보비교 공고번호없음= " .$arr['Cnt'] ."건, ";
							break;
						case "01Y":
							echo " (01-1)공고번호입력완료=" .$arr['Cnt'] ."건, <br>";
							break;
						case "02N":
							echo " (02)입찰정보비교 낙찰정보없음=" .$arr['Cnt'] ."건, ";
							break;
						case "02Y":
							echo " (02-1)낙찰정보입력완료=" .$arr['Cnt'] ."건, <br>";
							break;
						case "03N":
							echo " (03)입찰정보비교 공고신규입력=" .$arr['Cnt'] ."건, ";
							break;
						case "03Y":
							echo " (03-1)낙찰정보입력완료=" .$arr['Cnt'] ."건, <br>";
							break;
					}
				}
				/* 머가 문젠지 확인해 보기
				$i = 0;
				foreach ($row as $arr) {
					$Cnt[$i] = $arr['Cnt'];
					//echo $i.",".$arr['statusCd'].", ".$arr['Cnt']."<br>" ;
					echo $arr['statusCd']."= ".$arr['Cnt']."<br>" ;
					++$i;
				} */
			} else {
				echo "SQL error(openBidInfo_status):" .$sql ."<br>";
			}

			// workdate, lasdt DB저장
			$workname = 'dailyDataFill';
			$workdt = date('Y-m-d', strtotime(date("Ymd"))); //Today
			$lastdt = date('Y-m-d', strtotime($endDate));
			workdate($conn, $workname, $workdt, $lastdt);

			?>
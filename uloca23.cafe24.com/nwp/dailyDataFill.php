<?
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
//echo $_POST['startDate'].'/'.$startDate;
$dur = '2020';
if ($_POST['startDate'] != '') {
	$dur = $g2bClass->countDuration($startDate);
}
$openBidInfo = 'openBidInfo'; //.$dur;
//$openBidSeq = 'openBidSeq' . '_' . $dur;

echo $openBidSeq . ' / StartDate=' . $startDate;

if (isset($_POST['contn'])) {
	$contn1 = 1;	//on
} else {
	$contn1 = 0; 
}

$sql = "select lastdt from workdate where workname = 'dailyDataFill'";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$lastdt = $row['lastdt'];
}
echo '/ lastdt=' . $lastdt . ' / 계속 체크박스=' . $contn ;

?>

<!DOCTYPE html>
<html>

<head>
	<title>ULOCA</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="http://uloca.net/g2b/css/g2b.css" />
	<link rel="stylesheet" href="http://uloca.net/jquery/jquery-ui.css">
	<script src="http://uloca.net/jquery/jquery.min.js"></script>
	<script src="http://uloca.net/jquery/jquery-ui.min.js"></script>
	<script src="http://uloca.net/g2b/g2b.js"></script>
	<script>
		function doit() {
			frm = document.myForm;
			frm.submit();
		}

		function donextday() {
			frm = document.myForm;
			if (frm.startDate.value >= '<?= $lastdt ?>') return; //lastdt 없으면 return
			//alert(frm.contn.checked);
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
	<form action="dailyDataFill.php" name="myForm" id="myform" method="post">
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

									<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?= $startDate ?>" style="width:76px;" readonly='readonly' onchange='document.getElementById("endDate").value=this.value' />

									~
									<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?= $endDate ?>" style="width:76px;" readonly='readonly' /> 1주일 이내-하루
									<input type="checkbox" name="contn" id="contn" <? if ($contn == 'on') { ?>checked=checked <? } ?>>계속
									<div id="datepicker"></div>
								</div>
							</td>
						</tr>
				</table>
				<div class="btn_area">
					<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
					<a onclick="doit();" class="search">실행</a>
				</div>
			</div>
		</div>
	</form>

	<center>
		<div style='font-size:14px; color:blue;font-weight:bold'>- 입찰정보 / 낙찰업데이트 1등정보 (입찰/낙찰 연관없음)  <br> 
		입찰정보(등록일시기준): DB에 없는것만 INSERT, 낙찰정보(개찰일시기준): DB에 공고번호로 업데이트 </div>
	</center>
	<div id='totalRecords'>total records(입찰정보)=<?= $countItem ?></div>

	<table class="type10" id="specData">
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

			<?

			$updateRecord = 0; //입찰정보에 1순위 낙찰정보 업데이트 count
			$newRecord = 0;	   //입찰정보 없는거 신규로 Insert Count
			// function insertOpenBidInfo '20180912933'
			// Update: $item2 낙찰정보도 같이 넘어옴 -by jsj 200320
			// openBidInfo = table명, arr=가져온입찰정보, pss=입찰종류, item2=가져온낙찰정보, dbResult=DB의 입찰정보
		
			function insertOpenBidInfo($conn, $openBidInfo, $arr, $pss, $item2, $dbResult)
			{
				global $updateRecord, $newRecord;
				if ($arr['bidNtceNo'] <> '20200301760' ) return;

				if ($dbResult->num_rows <> 0) { //DB에 있으면 입찰정보 업데이트
					// 기존입찰정보에, 가져온 입찰정보로 업데이트(낙찰정보는 업데이트 안함)) -by jsj 0320
					if ($row = $dbResult->fetch_assoc()) {
						if ($row["bidwinnrNm"] == ""){
							$sql = "UPDATE " . $openBidInfo . " SET "; // bidNtceOrd ='". $arr['bidNtceOrd'] . "', ";
							$sql .= "reNtceYn = '" . $arr['reNtceYn'] . "', rgstTyNm = '" . $arr['rgstTyNm'] . "', ";
							$sql .= "ntceKindNm = '" . $arr['ntceKindNm'] . "', bidNtceDt = '" . $arr['bidNtceDt'] . "', ";
							$sql .= "ntceInsttCd = '" . $arr['ntceInsttCd'] . "', dminsttCd = '" . $arr['dminsttCd'] . "', ";
							$sql .= "bidBeginDt = '" . $arr['bidBeginDt'] . "', bidClseDt = '" . $arr['bidClseDt'] . "', ";
							$sql .= "presmptPrce = '" . $arr['presmptPrce'] . "', bidNtceDtlUrl = '" . $arr['bidNtceDtlUrl'] . "', ";
							$sql .= "bidNtceUrl = '" . $arr['bidNtceUrl'] . "', sucsfbidLwltRate = '" . $arr['sucsfbidLwltRate'] . "', ";
							$sql .= "bfSpecRgstNo = '" . $arr['bfSpecRgstNo'] . "' ";
							$sql .= " WHERE bidNtceNo = '" . $row['bidNtceNo'] . "'";
							$sql .= "   AND bidNtceOrd = '" . $row['bidNtceOrd'] ."';"; 
							if ($conn->query($sql) === true) {
								global $updateRecord;
								$updateRecord++;
								return $arr['bidNtceNo'];
							} else {
								echo "Update Error:" . $sql;
								return false;
							}
						}
					}
				} else {
					//입찰정보가 없으면 INSERT 후 낙찰정보도 업데이트해야 함 -by jsj 200320
					$sql = 'INSERT INTO ' . $openBidInfo . ' (bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
					$sql .= 'reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
					$sql .= 'presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo)';
					//$sql .= 'prtcptCnum,bidwinnrNm,bidwinnrBizno,sucsfbidAmt,sucsfbidRate,rlOpengDt,bidwinnrCeoNm) ';
					$sql .= "VALUES ('" . $arr['bidNtceNo'] . "', '" . $arr['bidNtceOrd'] . "', ";
					$sql .= "'" . addslashes($arr['bidNtceNm']) . "', '" . addslashes($arr['ntceInsttNm']) . "', ";
					$sql .= "'" . addslashes($arr['dminsttNm']) . "', '" . $arr['opengDt'] . "', '" . $pss . "', ";
					$sql .= "'" . $arr['reNtceYn'] . "', '" . $arr['rgstTyNm'] . "', ";
					$sql .= "'" . $arr['ntceKindNm'] . "', '" . $arr['bidNtceDt'] . "', ";
					$sql .= "'" . $arr['ntceInsttCd'] . "', '" . $arr['dminsttCd'] . "', ";
					$sql .= "'" . $arr['bidBeginDt'] . "', '" . $arr['bidClseDt'] . "', ";
					$sql .= "'" . $arr['presmptPrce'] . "', '" . $arr['bidNtceDtlUrl'] . "', ";
					$sql .= "'" . $arr['bidNtceUrl'] . "', '" . $arr['sucsfbidLwltRate'] . "', ";
					$sql .= "'" . $arr['bfSpecRgstNo'] . "';";

					if ($conn->query($sql) === TRUE) {
						echo "[INSERT(" . $newRecord . ")]." . $arr['bidNtceNo'] . "<br>";
						// 입찰정보 없음을 openBidInfo_status에 Insert, statusCD='03'낙찰기업없음
						$sql = " INSERT INTO openBidInfo_staus Values ('";
						$sql .= "'" . $arr['bidNtceNo'] . "', " . $arr['bidNtceOrd'] . "', '03')";
						$row = $conn->query($sql);

					} else {
						echo "Update Error:" . $sql;
						return false;
					}
					$newRecord++;
					return $arr['bidNtceNo'];
				}
				//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
			}

			// 낙찰정보 업데이트, 공고번호 RETURN  -by jsj 200321
			function updateOpenBidInfo($conn, $openBidInfo, $arr, $dbResult){

				if ($row = $dbResult->fetch_assoc()) {				
					$sql = "UPDATE " . $openBidInfo . " SET "; // bidNtceOrd ='". $arr['bidNtceOrd'] . "', ";
					// 낙찰결과 
					$sql .= " prtcptCnum = '" . $arr['prtcptCnum'] . "', bidwinnrNm = '" . $arr['bidwinnrNm'] . "', ";
					$sql .= " bidwinnrBizno = '" . $arr['bidwinnrBizno'] . "', sucsfbidAmt = '" . $arr['sucsfbidAmt'] . "', ";
					$sql .= " sucsfbidRate = '" . $arr['sucsfbidRate'] . "', rlOpengDt = '" . $arr['rlOpengDt'] . "', ";
					$sql .= " bidwinnrCeoNm = '" . $arr['bidwinnrCeoNm'] . "', bidwinnrTelNo = '" .$arr['bidwinnrTelNo'] . "' ";
					$sql .= " WHERE bidNtceNo=' " . $row['bidNtceNo'] . "' ";
					$sql .= "   AND bidntceOrd=' " .$row['bidNtceOrd'] . "' ";

					if ($conn->query($sql)) {
						global $updateRecord;
						$updateRecord++;
						return $arr['bidNtceNo'];
					} else {
						echo "Update Error:" . $sql;
						return false;
					}
				}
			}


			function workdate($conn, $workname, $workdt) {
				$sql = "update workdate set workdt='" . $workdt . "' where workname='" . $workname . "' ";
				$conn->query($sql);
			}

			// --------------------------------------- function 
			function findjson($arr, $col, $val, $item) {
				$i = 0;
				//echo 'findjson col='.$col.' val='.$val;
				foreach ($item as $arr2) {	// $arr['bidNtceNo']
					if ($arr2['bidNtceNo'] == $val) {						
						echo 'findjson i='.$i.'<br>';
						return $i; // if ($arr2[$col] == $val) return $i;
					}
					$i++;					
				}
				return -1;
			}

			// --------------------------------------- function insertTableBidInfo
			function insertTableBidInfo($i, $arr, $pss, $prtcptCnum, $bidwinnrNm)
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


			if ($startDate == '' || $endDate == '') {
				exit;
			}

			/*
if ($pss == '' ) {
	//echo '물품,공사,용역 구분이 없습니다.'; exit;
} */
			if (strlen($startDate) == 10) $startDate = substr($startDate, 0, 4) . substr($startDate, 5, 2) . substr($startDate, 8, 2);
			if (strlen($endDate) == 10) $endDate = substr($endDate, 0, 4) . substr($endDate, 5, 2) . substr($endDate, 8, 2);
			$startDate .= '0000'; //=$g2bClass->changeDateFormat($startDate);
			$endDate  .= '2359'; //=$g2bClass->changeDateFormat($endDate);
			echo 'startDate=' . $startDate . ' endDate=' . $endDate;
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
			// 입찰정보
			$item10 = array_merge($item11, $item12, $item13);
			$countItem = count($item10);

			//낙찰정보 -by jsj 200320
			$inqryDiv = 1;	//1.등록일시(공고 개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
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
			// 낙찰정보
			$item22 = array_merge($item2, $item3, $item4);

			//var_dump ($item22); exit();
			// http://uloca.net/g2b/datas/dailyDataFill.php?startDate=2018-10-01-00:00&endDate=2018-10-01-23:59&pss=물품

			echo '<br>입찰정보=' . count($item10) . ' 낙찰정보=' . count($item22) . '<br>';

			$i = 1; // 그리드 목록 번호
			// ----------------------------------------------
			// 입찰정보 INSERT or UPDATE(낙찰정보아님) -by jsj 200321
			// item11~13 입찰정보
			// 없는 입찰정보 INSERT, -by jsj 200320
			// 낙찰정보 업데이터 시 DB에 공고번호 없으면 다시 API조회해서 INSERT 필요함.
			// ----------------------------------------------
			foreach ($item10 as $arr) {
				$pss = '통합';
				
				// DB 공고번호 조회
				$sql =  "SELECT * FROM " . $openBidInfo;
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "'";
				$sql .= "   AND bidNtceOrd= '" . $arr['bidNtceOrd'] ."';";
				$dbResult = $conn->query($sql);

				// 입찰정보 INSERT or UPDATE(낙찰정보아님)
				$tf = insertOpenBidInfo($conn, $openBidInfo, $arr, $pss, $item22, $dbResult);
				
				// 화면그리드표시: 조회날짜 입찰목록 보여주고 기존 DB 참가업체수 및 낙찰업체명도 표시
				$prtcptCnum = '';
				$bidwinnrNm = '';
				if ($row = $dbResult->fetch_assoc()) {
					$prtcptCnum = $row['prtcptCnum'];
					$bidwinnrNm = $row['bidwinnrNm'];
				}
				insertTableBidInfo($i, $arr, $pss, $prtcptCnum, $bidwinnrNm);
				$i++;
			}

			foreach ($item12 as $arr) {
				$pss = '공사';

				// DB 공고번호 조회
				$sql =  "SELECT * FROM " . $openBidInfo;
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "'";
				$sql .= "   AND bidNtceOrd= '" . $arr['bidNtceOrd'] ."';";
				$dbResult = $conn->query($sql);
				// 입찰정보 INSERT or UPDATE(낙찰정보아님)
				$tf = insertOpenBidInfo($conn, $openBidInfo, $arr, $pss, $item22, $dbResult);
				
				// 화면그리드표시: 조회날짜 입찰목록 보여주고 기존 DB 참가업체수 및 낙찰업체명도 표시
				$prtcptCnum = '';
				$bidwinnrNm = '';
				if ($row = $dbResult->fetch_assoc()) {
					$prtcptCnum = $row['prtcptCnum'];
					$bidwinnrNm = $row['bidwinnrNm'];
				}
				insertTableBidInfo($i, $arr, $pss, $prtcptCnum, $bidwinnrNm);
				$i++;			
			}

			foreach ($item13 as $arr) {
				$pss = '용역';

				// DB 공고번호 조회
				$sql =  "SELECT * FROM " . $openBidInfo;
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "'";
				$sql .= "   AND bidNtceOrd= '" . $arr['bidNtceOrd'] ."';";
				$dbResult = $conn->query($sql);
				// 입찰정보 INSERT or UPDATE(낙찰정보아님)
				$tf = insertOpenBidInfo($conn, $openBidInfo, $arr, $pss, $item22, $dbResult);
				
				// 화면그리드표시: 조회날짜 입찰목록 보여주고 기존 DB 참가업체수 및 낙찰업체명도 표시
				$prtcptCnum = '';
				$bidwinnrNm = '';
				if ($row = $dbResult->fetch_assoc()) {
					$prtcptCnum = $row['prtcptCnum'];
					$bidwinnrNm = $row['bidwinnrNm'];
				}
				insertTableBidInfo($i, $arr, $pss, $prtcptCnum, $bidwinnrNm);
				$i++;			
			}

			// ==============================================
			// 낙찰결과 업데이트 -by jsj 200321 
			// item2~4 입찰정보 merge=item22
			// ==============================================
			foreach ($item22 as $arr) {
				$nonNtceNoCnt = 0;	// 입찰정보없는 낙찰정보 (입찰정보 추가 개발(배치) 필요)
				$pss = '낙찰통합'; 		// 물품,공사,용역 구분없이 한꺼번에 처리 화면에는 "통합"으로 표시

				// DB 공고번호 조회
				$sql = " SELECT * from '" . $openBidInfo . "' ";
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "' ";
				$sql .= "   AND bidNtceOrd = '" . $arr['bidNtceOrd'] . "' ";
				$dbResult = $conn->query($sql);

				//업데이트할 공고가 없으면 contiue, (공고없는 낙찰결과 숫자를 화면에 표시)
				if ($dbResult->num_rows == 0) {
					//입찰공고가 없으면 openBidInfo_stauts에 INSERT
					$sql = " INSERT INTO openBidInfo_staus Values ('";
					$sql .= "'" . $arr['bidNtceNo'] . "', " . $arr['bidNtceOrd'] . "', '01'";
					$row = $conn->query($sql);

					$nonNtceNoCnt++;
					continue;
				}

				// 낙찰정보 업데이트
				updateOpenBidInfo($conn, $openBidInfo, $arr, $dbResult);
				
				// 화면그리드표시
				$prtcptCnum = '';
				$bidwinnrNm = '';
				if ($row = $dbResult->fetch_assoc()) {
					$prtcptCnum = $row['prtcptCnum'];
					$bidwinnrNm = $row['bidwinnrNm'];
				}
				insertTableBidInfo($i, $arr, $pss, $item2, $ri, $prtcptCnum, $bidwinnrNm);
				$i++;
			}

			echo '---------- 결과 --------------------------------';
			echo ' INSERT(입찰)= ' . $newRecord . '건 ' . "<br>";
			echo ' UPDATE(입찰/낙찰 포함)= ' . $updateRecord . '건 ' . "<br>";
			echo ' UPDATE(낙찰-입찰공고없음)= ' . $nonNtceNoCnt . '건 ' . "<br>";

			$workname = 'dailyDataFill';
			$workdt = $_POST['lastdt'];
			workdate($conn, $workname, $workdt);
		

			?>
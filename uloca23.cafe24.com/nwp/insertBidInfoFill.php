
<?php
// 1) 입찰정보로 업데이트
//  - 입찰방식: bidMethdNm, 입력(전자입찰, 직찰 등)
//  - 공고종류: ntceKindNm, 일반, 변경, 취소, 재입찰, <연기>, 긴급, 갱신, 긴급갱신
//  - 입찰방식: bidMethdNm,  전자입찰 외 직찰, 우편 등은 낙찰결과가 없음
//  - 등록타입: '연계기관 공고건' 은 낙찰결과 없음

@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); 
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');

$g2bClass = new g2bClass;
$dbConn   = new dbConn;
$conn     = $dbConn->conn();

$startDate   = $_GET['startDate'];
$endDate     = $_GET['endDate'];
$openBidInfo = $_GET['openBidInfo'];
$openBidSeq  = $_GET['openBidSeq'];
$bidSeqOn    = $_GET['bidSeqOn']; // 개찰결과 입력이 체크되어 있으면, 목록에 업데이트할 공고를 표시 함.

//--------------------------------------------------
// 입찰정보, 낙찰정보, 업체별 낙찰이력 추가
//--------------------------------------------------
if ($startDate == '' || $endDate == '') {
	return;
}

// ---------------------------------------
// 마지막 작업일 endDate를 DB저장
// ---------------------------------------
$workdt  = date('Ymd', strtotime(date("Ymd"))); 
$endDate = substr(str_replace('-', '', $endDate), 0, 8);
if ($workdt < $enddate) {
	$endDate = $workdt;
}
$sql = " UPDATE workdate SET workdt='" . $workdt . "', lastdt='" . $endDate . "'";
$sql .= " Where workname= 'dailyDataFill' ";
if (!($conn->query($sql))) echo "SQL Error: " . $sql;
// ----------------------------------------


if (strlen($startDate) == 10) $startDate = substr($startDate, 0, 4) . substr($startDate, 5, 2) . substr($startDate, 8, 2);
if (strlen($endDate) == 10) $endDate = substr($endDate, 0, 4) . substr($endDate, 5, 2) . substr($endDate, 8, 2);
$startDate .= '0000'; //=$g2bClass->changeDateFormat($startDate);
$endDate   .= '2359'; //=$g2bClass->changeDateFormat($endDate);

$kwd = '';
$dminsttNm = '';
$numOfRows = 999;
$pageNo = 1;

// html tag Table 헤드
$tag = "<thead>";
$tag .= "<tr>";
$tag .= "		<th scope='cols' width='5%;' >0)Data순서</th>";
$tag .= "		<th scope='cols' width='5%;' >1)공고번호</th>";
$tag .= "		<th scope='cols' width='13%;'>2)공고명</th>";
$tag .= "		<th scope='cols' width='12%;'>3)공고기관</th>";
$tag .= "		<th scope='cols' width='10%;'>4)수요기관</th>";
$tag .= "		<th scope='cols' width='10%;'>5)개찰일시</th>";
$tag .= "		<th scope='cols' width='5%;' >6)구분</th>";
$tag .= "		<th scope='cols' width='10%;'>7)공고Index</th>";
$tag .= "		<th scope='cols' width='5%;' >8)응찰건수</th>";
$tag .= "	</tr>";
$tag .= "</thead>";

//  echo ('ln39:: startDate=" .$startDate. ", endDate=" .$endDate. ", openBidInfo=" .$openBidInfo. ", oepnBidSeq=" .$openBidSeq);
//  return;

//-------------------------------------------------------------------------------
// 입찰공고:: 나라장터 검색조건에 의한 나라장터 입찰공고 정보서비스
//-------------------------------------------------------------------------------
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
// Sum
$item10 = array_merge($item11, $item12, $item13);

// echo "입찰정보=" . count($item10) . ", ";

//-------------------------------------------------------------------------------
// 낙찰정보 현황조회 -by jsj 200329
//-------------------------------------------------------------------------------
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

// echo "낙찰현황=" . count($item22) . ", ";

//-------------------------------------------------------------------------------
// 낙찰정보 목록조회 -by jsj 200329 ? 
// 낙찰정보 목록에 있는 낙찰진행(progrsDivCdNm (유찰, 개찰완료, 재입찰)) 
//-------------------------------------------------------------------------------
$inqryDiv = 3;	//1.등록일시(공고개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
$pss = '물품목록';
$response5 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
$json5 = json_decode($response5, true);
$item5 = $json5['response']['body']['items'];

$pss = '공사목록';
$response6 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
$json6 = json_decode($response6, true);
$item6 = $json6['response']['body']['items'];

$pss = '용역목록';
$response7 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
$json7 = json_decode($response7, true);
$item7 = $json7['response']['body']['items'];
// 낙찰정보 sum
$item23 = array_merge($item5, $item6, $item7);

//echo "낙찰목록=" . count($item23) . " <br>";

$i = 1; // 그리드 목록 번호
$newInfoRecord = 0;	   // 입찰정보 추가 Count
$updateOpenBidInfo = 0;
$updateRstCnt = 0; 	   // 낙찰정보 업데이트 count
// $insertSeqCnt = 0; 	   // seq 순위 입력
$updateProgrsDivCdNm = 0; // 낙찰목록의 진행구분을 입찰공고에 업뎃 
$updateOpenBidInfo_1th = 0; // openBidSeq_xxxx 입력시 1순위 낙찰정보를 입찰정보에 업뎃

// ----------------------------------------------
// 입찰정보 INSERT or UPDATE(낙찰정보아님) -by jsj 200321
// openBidSeq_xxxx 추가 (낙찰정보도 입력)
// ----------------------------------------------

foreach ($item11 as $arr) {
	$pss = '입찰물품';
	$tf = insertOpenBidInfo($i, $g2bClass, $conn, $openBidInfo, $pss, $arr, $openBidSeq); //DB에 있던 공고번호 return		
	$i++;
	clog($pss."=".$arr['bidNtceNo']);
}

foreach ($item12 as $arr) {
	$pss = '입찰공사';
	$tf = insertOpenBidInfo($i, $g2bClass, $conn, $openBidInfo, $pss, $arr, $openBidSeq); //DB에 있던 공고번호 return
	$i++;
	clog($pss."=".$arr['bidNtceNo']);
}

foreach ($item13 as $arr) {
	$pss = '입찰용역';
	$tf = insertOpenBidInfo($i, $g2bClass, $conn, $openBidInfo, $pss, $arr, $openBidSeq); //DB에 있던 공고번호 return
	$i++;
	clog($pss."=".$arr['bidNtceNo']);
}

//------------------------------------------------------------
// 낙찰목록의 진행구분 업뎃 -by jsj 20200329
// updateProgrsDivCdNm (progrsDivCdNm: 유찰, 개찰완료, 재입찰)
//------------------------------------------------------------
//var_dump ($item23);

$nonNtceNoCnt = 0;
foreach ($item23 as $arr) {
	$pss = "낙찰목록";
	clog($pss."=".$arr['bidNtceNo']);

	$sql = " SELECT idx, bidNtceOrd, bidNtceNo, bidwinnrBizno, progrsDivCdNm FROM openBidInfo ";
	$sql .= " WHERE bidNtceNo = '"  .$arr['bidNtceNo'].  "' ";
	$sql .= "   AND bidNtceOrd = '" .$arr['bidNtceOrd']. "' ";
	if (!($dbResult = $conn->query($sql))) echo "Error sql= " . $sql;
	if ($row = $dbResult->fetch_assoc()) {
		$bidwinnrBizno = $row['bidwinnrBizno'];	// 사업자번호
		$progrsDivCdNm = $row['progrsDivCdNm']; // 진행구분

		// 낙찰사업자번호가 없고, 진행구분(progrsDivCdNm) 없으면 업데이트
		if ( $bidwinnrBizno == '' &&  $progrsDivCdNm == '' ) {
			$sql = " UPDATE openBidInfo ";
			$sql .= "   SET progrsDivCdNm = '" .$arr['progrsDivCdNm']. "' ";
			$sql .= " WHERE bidNtceNo     = '" .$row['bidNtceNo'].     "' ";
			$sql .= "   AND bidNtceOrd    = '" .$row['bidNtceOrd'].    "' ";
			if (!($conn->query($sql))) {
				echo "REPLACE Error:" . $sql . "<br>";
			}
			$updateProgrsDivCdNm++; //진행구분 progrsDivCdNm 업데이트 건수
			displayBidInfo($i, $arr, $pss, $row['idx'] );
		}
	} else {
		// 업데이트할 공고가 없으면 continue
		// '01' Stauts 에 진행구분 progrsDivCdNm 입력
		if ($row['progrsDivCdNm'] <> '') {
			$sql =  " REPLACE INTO openBidInfo_status ( bidNtceNo, bidNtceOrd, statusCd, progrsDivCdNm )";
			$sql .= " VALUES ('" . $arr['bidNtceNo'] . "', '" . $arr['bidNtceOrd'] . "', '01', '" . $arr['progrsDivCdNm'] . "') ";
			if (!($conn->query($sql))) echo "REPLACE Error:" . $sql . "<br>";

			$nonNtceNoCnt++; // 진행구분 포함해서 Status에 임시 저장
			continue;
		}
	}
	$i++;
}

//------------------------------------------- 
// 낙찰현황 업데이트 -by jsj 200321 
// item2~4 입찰정보 merge=item22
// 진행구분은 progrsDivCdNm 는 낙찰목록에만 있음
//-------------------------------------------
foreach ($item22 as $arr) {
	$pss = '낙찰현황';
	clog($pss."=".$arr['bidNtceNo']);

	// DB 공고번호 조회
	$sql = " SELECT idx, bidwinnrBizNo FROM openBidInfo WHERE 1 ";
	$sql .= "   AND bidNtceNo = '"  .$arr['bidNtceNo'].   "' ";
	$sql .= "   AND bidNtceOrd = '" .$arr['bidNtceOrd'].  "' ";
	// $sql .= "   AND ntceKindNm NOT IN ('취소', '재입찰', '연기') ";
	// $sql .= "   AND progrsDivCdNm NOT IN ('유찰', '재입찰') ";
	if (!($dbResult = $conn->query($sql))) echo "Error sql= " . $sql;
	if ($row = $dbResult->fetch_assoc() ) {
		// 낙찰정보 업데이트
		if ($row['bidwinnrBizNo'] == '') {
			$sql = "UPDATE openBidInfo SET "; // bidNtceOrd ='".$arr['bidNtceOrd']."', ";
			$sql .= " prtcptCnum = '"    .$arr['prtcptCnum'].    "', "; // 참가업체수
			$sql .= " bidwinnrNm = '"    .$arr['bidwinnrNm'].    "', ";	// 최종낙찰업체명
			$sql .= " bidwinnrBizno = '" .$arr['bidwinnrBizno']. "', ";	// 최종낙찰업체사업자번호
			$sql .= " sucsfbidAmt = '"   .$arr['sucsfbidAmt'].   "', "; // 최종낙찰금액
			$sql .= " sucsfbidRate = '"  .$arr['sucsfbidRate'].  "', "; // 숫자
			$sql .= " rlOpengDt = '"     .$arr['rlOpengDt'].     "', ";	// 실개찰일시
			$sql .= " bidwinnrCeoNm = '" .$arr['bidwinnrCeoNm']. "', ";	// 대표자명
			$sql .= " bidwinnrTelNo = '" .$arr['bidwinnrTelNo']. "', ";	// 전화번호
			$sql .= " progrsDivCdNm = '" .$arr['progrsDivCdNm']. "'  "; // 진행구분
			$sql .= " WHERE bidNtceNo = '"  .$arr['bidNtceNo'].  "' ";
			$sql .= "   AND bidNtceOrd = '" .$arr['bidNtceOrd']. "' ";
			if ($conn->query($sql) == false) echo "Update Error:" .$sql. "<br>";
			$updateRstCnt++;
			// 화면표시
			displayBidInfo($i, $arr, $pss, $row['idx']);
		}
	} else { //입찰공고가 없으면
		// 공고번호 없으면 '01' Stauts 에 낙찰결과 INSERT
		$sql =  " REPLACE INTO openBidInfo_status (";
		$sql .= "              bidNtceNo,     bidNtceOrd, statusCd, ";
		$sql .= "              bidNtceDt,     opengDt, ";
		$sql .= "              prtcptCnum,    bidwinnrNm, ";
		$sql .= "              bidwinnrBizno, sucsfbidAmt, ";
		$sql .= "              sucsfbidRate,  rlOpengDt, ";
		$sql .= "              bidwinnrCeoNm, bidwinnrTelNo, progrsDivCdNm )";
		$sql .= " VALUES ('" . $arr['bidNtceNo'] . "',     '" . $arr['bidNtceOrd'] .   "', '01', ";
		$sql .= "         '" . $arr['bidNtceDt'] . "',     '" . $arr['opengDt'] .      "', ";
		$sql .= "         '" . $arr['prtcptCnum'] . "',    '" . $arr['bidwinnrNm'] .   "', ";
		$sql .= "         '" . $arr['bidwinnrBizno'] . "', '" . $arr['sucsfbidAmt'] .  "', ";
		$sql .= "         '" . $arr['sucsfbidRate'] . "',  '" . $arr['rlOpengDt'] .   "', ";
		$sql .= "         '" . $arr['bidwinnrCeoNm'] . "', '" . $arr['bidwinnrTelNo'] . "', '개찰완료' )";
		if (!($conn->query($sql))) echo "REPLACE Error:" . $sql . "<br>";
		$nonNtceNoCnt++; //공고없음
		continue;
	}
	$i++;
}
// ------------------
// table 최종 리턴값
// ------------------
$tag .= "</tbody></table>";
echo ($tag); 
// ------------------

function insertOpenBidInfo($i, $g2bClass, $conn, $openBidInfo, $pss, $arr, $openBidSeq)
{
	global $bidSeqOn; //개찰결과 입력on :: 목록에 있는것은 개찰결과가 없는 거임 // 개찰완료, 유찰 또는 연계기관 공고건은 제외
	
	// 공고는 있고 입찰결과 없는 공고목록 표시
	$sql = "SELECT idx, bidwinnrBizNo, opengDt, rgstTyNm, ntceKindNm, bidMethdNm, progrsDivCdNm FROM openBidInfo WHERE 1";
	$sql .= "  AND bidNtceNo  = '" .$arr['bidNtceNo'].  "' ";
	$sql .= "  AND bidNtceOrd = '" .$arr['bidNtceOrd']. "' ";
	if (!($dbResult = $conn->query($sql))) echo "ln239 error sql=" .$sql. "<br>";
	if ($row = $dbResult->fetch_assoc()){
		$idx = $row['idx'];
		$bidwinnrBizNo = $row['bidwinnrBizNo'];
		$opengDt       = $row['opengDt'];				// 개찰일시
		$rgstTyNm      = trim($row['rgstTyNm']);		// 연계기관 공고건 제외
		$ntceKindNm    = trim($row['ntceKindNm']);		// 취소, 연기공고  제외
		$bidMethdNm    = trim($row['bidMethdNm']);		// 입찰방식명 - 직찰은 제외
		$progrsDivCdNm = trim($row['progrsDivCdNm']); 	// 유찰, 개찰완료, 재입찰

		// 입찰공고는 '취소' 등 업데이트 필요
		$sql = " UPDATE openBidInfo SET  bidNtceNm    ='" .addslashes($arr['bidNtceNm']). "', ntceInsttNm ='" .addslashes($arr['ntceInsttNm']). "', dminsttNm=  '" .addslashes($arr['dminsttNm']). "', ";
		$sql .="                         opengDt      ='" .$arr['opengDt'].               "', bidtype     ='" .$pss.                "', reNtceYn        ='" .$arr['reNtceYn'].         "',";
		$sql .="                         rgstTyNm     ='" .$arr['rgstTyNm'].              "', ntceKindNm  ='" .$arr['ntceKindNm'].  "', bidMethdNm      ='" .$arr['bidMethdNm'].       "',";
		$sql .="                         bidNtceDt    ='" .$arr['bidNtceDt'].             "', ntceInsttCd ='" .$arr['ntceInsttCd']. "', dminsttCd       ='" .$arr['dminsttCd'].        "',";
		$sql .="                         bidBeginDt   ='" .$arr['bidBeginDt'].            "', bidClseDt   ='" .$arr['bidClseDt'].   "', presmptPrce     ='" .$arr['presmptPrce'].      "',";
		$sql .="                         bidNtceDtlUrl='" .$arr['bidNtceDtlUrl'].         "', bidNtceUrl  ='" .$arr['bidNtceUrl'].  "', sucsfbidLwltRate='" .$arr['sucsfbidLwltRate']. "', ";
		$sql .="                         bfSpecRgstNo ='" .$arr['bfSpecRgstNo'].          "' ";
		$sql .=" WHERE bidNtceNo=  '" .$arr['bidNtceNo'].  "' "; 
		$sql .="   AND bidNtceOrd= '" .$arr['bidNtceOrd']. "' ";
		if (!($conn->query($sql))) echo "ln308 error sql=" . $sql . "<br>";

	} else { // 입찰공고가 없으면 추가
		// 입찰공고는 항상 업데이트 - '취소'인 경우 업데이트해야 함.
		$sql = 'REPLACE INTO ' . $openBidInfo . ' (bidNtceNo, bidNtceOrd, bidNtceNm, ntceInsttNm, dminsttNm, opengDt, bidtype,';
		$sql .= '                                  reNtceYn, rgstTyNm, ntceKindNm, bidMethdNm, bidNtceDt, ntceInsttCd, dminsttCd, bidBeginDt, bidClseDt,';
		$sql .= '                                  presmptPrce, bidNtceDtlUrl, bidNtceUrl, sucsfbidLwltRate, bfSpecRgstNo)';
		$sql .= "VALUES ('"     .$arr['bidNtceNo'].     "', '" .$arr['bidNtceOrd'].       "', ";
		$sql .= "'"  .addslashes($arr['bidNtceNm']).    "', '" .addslashes($arr['ntceInsttNm']). "', ";
		$sql .= "'"  .addslashes($arr['dminsttNm']).    "', '" .$arr['opengDt'].          "', '" .$pss. "', ";
		$sql .= "'"             .$arr['reNtceYn'].      "', '" .$arr['rgstTyNm'].         "', ";
		$sql .= "'"             .$arr['ntceKindNm'].    "', '" .$arr['bidMethdNm'].       "', '" .$arr['bidNtceDt']. "', ";
		$sql .= "'"             .$arr['ntceInsttCd'].   "', '" .$arr['dminsttCd'].        "', ";
		$sql .= "'"             .$arr['bidBeginDt'].    "', '" .$arr['bidClseDt'].        "', ";
		$sql .= "'"             .$arr['presmptPrce'].   "', '" .$arr['bidNtceDtlUrl'].    "', ";
		$sql .= "'"             .$arr['bidNtceUrl'].    "', '" .$arr['sucsfbidLwltRate']. "', ";
		$sql .= "'"             .$arr['bfSpecRgstNo'].  "') ";
		if (!($conn->query($sql))) echo "ln325:Err sql=" . $sql . "<br>";

		// 입찰공고 신규 입력
		$sql = "SELECT idx, bidwinnrBizNo, opengDt, rgstTyNm, ntceKindNm, bidMethdNm, progrsDivCdNm FROM openBidInfo WHERE 1";
		$sql .= "  AND bidNtceNo  = '" .$arr['bidNtceNo'].  "' ";
		$sql .= "  AND bidNtceOrd = '" .$arr['bidNtceOrd']. "' ";
		if (!($dbResult = $conn->query($sql))) echo "ln331 Err sql=" .$sql. "<br>";
		if ($row = $dbResult->fetch_assoc()){
			$idx = $row['idx'];
			$bidwinnrBizNo = $row['bidwinnrBizNo'];
			$opengDt       = $row['opengDt'];				// 개찰일시
			$rgstTyNm      = trim($row['rgstTyNm']);		// 연계기관 공고건 제외
			$ntceKindNm    = trim($row['ntceKindNm']);		// 취소, 연기공고  제외
			$bidMethdNm    = trim($row['bidMethdNm']);		// 입찰방식명 - 직찰은 제외
			$progrsDivCdNm = trim($row['progrsDivCdNm']); 	// 유찰, 개찰완료, 재입찰
		}
	}
	// 개찰결과가 없거나 신규공고를을 화면에 보여줌, 개찰결과가 도래하지 않으면 목록 제외
	$timestamp = strtotime("-2 days"); // 개찰일시 > 오늘날짜-2일 :: 비교해서 날짜 도래하지 않으면 표시하지 않음
    $timestamp = date("Y-m-d", $timestamp);
    $opengDt   = date("Y-m-d", strtotime($opengDt));   
	if (($bidwinnrBizNo == '')  && ($rgstTyNm <> '연계기관 공고건') && ($ntceKindNm <> '취소')        && ($ntceKindNm <> '연기') && 
	    ($bidMethdNm <> '직찰') && ($progrsDivCdNm <> '유찰')      && ($progrsDivCdNm <> '개찰완료')  && ($opengDt < $timestamp )) {
		// 기존에 입찰현황이 있고, 개찰결과가 없는것만 표시
		displayBidInfo($i, $arr, $pss, $idx);		// 개찰결과가 없는 경우 표시, Fill 이후 신규로 되어 있는 경우는 나타남
	}
}

// --------------------------------------------------------------
// 입찰현황, 낙찰목록, 낙찰현황 3가지 업데이트 or Insert 후 화면표시
// --------------------------------------------------------------
function displayBidInfo($no, $arr, $pss='', $idx='0'){
	global $tag;
	if (mb_strlen($pss,'utf-8') == 2) {
		$pss = "입찰" .$pss;
	}

	$tag .= '<tr>';
	$tag .= '<td style="text-align: center;">' .$no. '</td>';											// no.
	$tag .= '<td style="text-align: center;">' .$arr['bidNtceNo']. '-' .$arr['bidNtceOrd']. '</td>';	// 공고번호
	$tag .= '<td style="text-align: left;">'   .$arr['bidNtceNm']. '</td>';								// 공고명
	$tag .= '<td style="text-align: center;">' .$arr['ntceInsttNm']. '</td>';    						// 공고기관
	$tag .= '<td style="text-align: center;">' .$arr['dminsttNm']. '</td>';								// 수요기관
	$tag .= '<td style="text-align: center;">' .$arr['rlOpengDt']. '</td>'; 							// 개찰일시
	$tag .= '<td style="text-align: center;">' .$pss. '</td>'; 											// 공고구분 ex 물품, 공사, 용역
	$tag .= '<td style="text-align: center;">' .$idx. '</td>'; 											// 공고Index
	$tag .= '<td style="text-align: right;">'  .$arr['prtcptCnum']. '</td>';							// 응찰건수
	$tag .= '</tr>';
}

function clog($data){
    echo "<script>console.log( 'PHP_Console: " . $data . "' );</script>";
}

?>
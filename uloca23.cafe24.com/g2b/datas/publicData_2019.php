<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$dbConn = new dbConn;
$conn = $dbConn->conn();

if ($compinfo != 1 && $kwd != '' && $dminsttNm != '') $key = '03';
else if ($compinfo != 1 && $kwd != '' && $dminsttNm == '') $key = '01';
else if ($compinfo != 1 && $kwd == '' && $dminsttNm != '') $key = '02';
else if ($compinfo == 1 && $compname != '') {
	$key = '04';
	$pss = '';
	$pss2 = '';
}
// --------------------------------- log
$rmrk = 'compname=' . $compname . ' kwd=' . $kwd . ' dminsttNm=' . $dminsttNm . 'pss=' . $pss; // '조건검색';
$dbConn->logWrite2($id, $_SERVER['REQUEST_URI'], $rmrk, $pss2, $key);
// --------------------------------- log

/* --------------------------------------------------------------------------------------------
	기업정보
--------------------------------------------------------------------------------------------- */
if ($compinfo == 1 && $compname != '') {

	$kwd1 = ''; // 1번째 문자열 (공고명, 공고번호)
	$kwd2 = ''; // 2번째 문자열 (수요기관)
	$kwd = explode('?', $compname); 
	for ($i=0;$i<sizeof($kwd);$i++) {
		if ($i == 0 ) {
			$kwd1 .= " ".$kwd[$i]. " "; // 기업명: 공고명 문자열은 ? 포함된 1번째열
		} else {
			$kwd2 .= " ".$kwd[$i]. " "; // 대표자명: 수요기관 문자열
		}
	}

	$kwds = ''; // 기업명 SQL
	$kwdN = ''; // 공고번호 SQL
	$kwdd = ''; // 대표자명 SQL

	// 기업명, 사업자번호 SQL 작성
	$kwd1 = preg_replace("/[#\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>\[\]\{\}]/i", "", $kwd1); //특수문자 없앰
	$kwdsn = explode(' ', trim($kwd1)); 
	for ($i=0;$i<sizeof($kwdsn);$i++) {
		if ($kwdsn[$i] == '') continue; 							
		if (ctype_alnum($kwdsn[$i]) == false ) { 					// 기업명은 영문자, 숫자만 있는 것은 제외
			$kwds .= " compname like '%" .$kwdsn[$i]. "%' AND "; 	// 기업명   (AND=포함된 키워드가 모두 있어야 함)
		}
		if (ctype_alnum($kwdsn[$i])) { 								// 사업자번호는 영문자, 숫자만 
			$kwdN .= " compno like '%" .$kwdsn[$i]. "%'   OR  "; 	// 사업자번호 (OR = 번호는 중복이 거의 없음)
		}
	}

	// 대표자명 SQL 작성 
	$kwd2 = preg_replace("/[#\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>\[\]\{\}]/i", "", $kwd2); //특수문자 없앰
	$kwddd = explode(' ', trim($kwd2));
	for ($i=0;$i<sizeof($kwddd);$i++) {		
		if ($kwddd[$i] == '') continue;
		$kwdd .= " repname like '%" .$kwddd[$i].  "%' OR  "; // 대표자명 (OR= 동일한 대표자명이 많으므로
	}

	// SQL보완 마지막 4문자 "and " 삭제해서 SQL 보완
	$kwds = substr($kwds,0,strlen($kwds)-4);  // 기업명
	$kwdN = substr($kwdN,0,strlen($kwdN)-4);  // 사업자번호
	$kwdd = substr($kwdd,0,strlen($kwdd)-4);  // 대표자명

	// ---------------------------------

	$sql  = " SELECT compno, compname, repname ";
	$sql .= "   FROM openCompany WHERE 1 ";
	if (trim($kwdd) <> '') $sql .= " AND (" .$kwdd. " ) "; // 대표자명
	if (trim($kwds) <> '') $sql .= " AND (" .$kwds. " ) "; // 기업명
	if (trim($kwdN) <> '') $sql .= " AND (" .$kwdN. " ) "; // 사업자번호			
	$sql .= "ORDER BY compname desc limit ".$curStart.",".$cntonce." ";
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);

	//$stmt->bind_param("ss", $qry, $qry);
	if (!$stmt->execute()) return $stmt->errno;
	$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	$json_string = $g2bClass->rs2Json1($stmt, $fields, '');
	echo ($json_string);
	$i--;
	exit;
}

/* --------------------------------------------------------------------------------------------
*	입찰정보 사전규격 Search -by jsj 20190730 
--------------------------------------------------------------------------------------------- */
$stmt = $dbConn->getSvrDataDB4($conn, $kwd, $dminsttNm, $curStart, $cntonce);
// debug -by jsj 20200327
// SQL debug 
// echo ($stmt); exit;

if (strpos($response1, 'Temporary Redirect')) {
	echo '<p style="font-weight: bold; color: rgb(255,0,0);">Server 일시 중단 상태 입니다.</p>';
	var_dump($response1);
	exit;
}

$rowCount = $stmt->num_rows;
$fields = $g2bClass->bindAll($stmt);
$json_string = $g2bClass->rs2Json1($stmt, $fields, '');

echo ($json_string);
exit;
//}

// == 사전규격정보 =====================
if ($bidhrc == 'hrc') {
	//$mobile = $g2bClass->MobileCheck();
	ob_end_flush();
	flush();
	if ($bidthing == '1' && $bidhrc == 'hrc') {
		//$response1 = $g2bClass->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 물품사전규격
		$pss = '물품';
		// $pss = '물품';
		//$stmt = $dbConn->getSvrDataDB($conn,'bidthing',$kwd,$dminsttNm,$pss); // 물품입찰 
		$stmt = $dbConn->getSvrDataDB2($conn, 'hrcthing', $kwd, $dminsttNm, $pss, $sYear, $LikeOrEqual, $startNo, $noOfRow);
	} else if ($bidcnstwk == '1' && $bidhrc == 'hrc') {
		//$response1 = $g2bClass->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 공사사전규격
		$pss = '공사';
		$stmt = $dbConn->getSvrDataDB2($conn, 'hrccnstwk', $kwd, $dminsttNm, $pss, $sYear, $LikeOrEqual, $startNo, $noOfRow);
	} else if ($bidservc == '1' && $bidhrc == 'hrc') {
		//$response1 = $g2bClass->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 용역사전규격
		$pss = '용역';
		$stmt = $dbConn->getSvrDataDB2($conn, 'hrcservc', $kwd, $dminsttNm, $pss, $sYear, $LikeOrEqual, $startNo, $noOfRow);
	}
	//$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰
	if (strpos($response1, 'Temporary Redirect')) {
		echo '<p style="font-weight: bold; color: rgb(255,0,0);">Server 일시 중단 상태 입니다.</p>';
		exit;
	}
	// 사전규격등록번호,품명,배정예산금액,등록일시,실수요기관명,의견등록마감일시
	//$colArray = [ 'bfSpecRgstNo', 'prdctClsfcNoNm', 'asignBdgtAmt', 'rgstDt', 'rlDminsttNm', 'opninRgstClseDt', 'bidNtceNoList' ];
	$colArray = array('bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt', 'bidNtceDtlUrl');
	$json_string = $g2bClass->compressJson($response1, $colArray, $pss);

	echo ($json_string);
	exit;
}
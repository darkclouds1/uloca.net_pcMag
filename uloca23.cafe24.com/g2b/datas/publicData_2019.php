<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();

if ($compinfo != 1 && $kwd != '' && $dminsttNm != '' ) $key = '03';
else if ($compinfo != 1 && $kwd != '' && $dminsttNm == '') $key = '01';
else if ($compinfo != 1 && $kwd == '' && $dminsttNm != '') $key = '02';
else if ($compinfo == 1 && $compname != '') {
	$key = '04';
	$pss = '';
	$pss2 = '';
}
// --------------------------------- log
$rmrk = 'compname='.$compname.' kwd='.$kwd.' dminsttNm='. $dminsttNm.'pss='.$pss; // '조건검색';
$dbConn->logWrite2($id,$_SERVER['REQUEST_URI'],$rmrk,$pss2,$key);
// --------------------------------- log


/* --------------------------------------------------------------------------------------------
	기업정보
--------------------------------------------------------------------------------------------- */
if ($compinfo == 1 && $compname != '') {
	
	$qry = '%'.$compname.'%';
	$kwd1 = explode(' ',$compname);
		$kwds = ' (';
		$kwd2 = ' (';
		for ($i=0;$i<sizeof($kwd1);$i++) {
			$kwds .= " b.compname like '%".$kwd1[$i]."%' and "; // or->and 로 바꿈 20190223
			$kwd2 .= " b.repname like '%".$kwd1[$i]."%' and "; // or->and 로 바꿈 20190223
			
		}
	$kwds = substr($kwds,0,strlen($kwds)-4). ' )';
	$kwd2 = substr($kwd2,0,strlen($kwd2)-4). ' )';
		
	//$sql = "select a.compno , b.compname, b.repname, a.cnt from (select count(idx) as cnt,compno  from ".$openBidSeq." group by compno) a,  openCompany b where a.compno=b.compno and compname like ? ";
	$sql = "SELECT b.compno, b.compname, b.repname FROM openCompany b WHERE ".$kwds. " or ".$kwd2. " limit ".$curStart.",".$cntonce;
	
	
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);

	//$stmt->bind_param("ss", $qry, $qry);
	if (!$stmt->execute()) return $stmt->errno;
	$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	
	$json_string = $g2bClass->rs2Json1($stmt, $fields,'');
	
	echo ($json_string);
	//exit;
	
	$i--;
	//echo '<table>';
	exit;
}

/* --------------------------------------------------------------------------------------------
*	입찰정보 DB Search -by jsj 20190730 
--------------------------------------------------------------------------------------------- */
$stmt = $dbConn->getSvrDataDB4($conn,$kwd,$dminsttNm,$curStart,$cntonce);

//var_dump($response1);
if (strpos($response1,'Temporary Redirect')) {
	echo '<p style="font-weight: bold; color: rgb(255,0,0);">Server 일시 중단 상태 입니다.</p>';
	var_dump($response1);
	exit;
}

	//if ($bidhrc=='bid') {
	//$colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt','bidNtceDtlUrl');

	$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);

	$json_string = $g2bClass->rs2Json1($stmt, $fields,'');

	//$json_string = $g2bClass->compressJson($response1, $colArray,$pss);
	//$js = substr($json_string,333200,800);
	//echo $js;
	echo ($json_string);

	exit;
//}

// ============================== 사전규격정보 =====================================================
if ($bidhrc == 'hrc') { 
	//$mobile = $g2bClass->MobileCheck();
	
	ob_end_flush();
	flush();
	if ($bidthing == '1' && $bidhrc=='hrc') {	
		//$response1 = $g2bClass->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 물품사전규격
		$pss = '물품';
		// $pss = '물품';
		//$stmt = $dbConn->getSvrDataDB($conn,'bidthing',$kwd,$dminsttNm,$pss); // 물품입찰 
		$stmt = $dbConn->getSvrDataDB2($conn,'hrcthing',$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
	
	} else if ($bidcnstwk == '1' && $bidhrc=='hrc') {
		//$response1 = $g2bClass->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 공사사전규격
		$pss = '공사';
		$stmt = $dbConn->getSvrDataDB2($conn,'hrccnstwk',$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
	
	} else if ($bidservc == '1' && $bidhrc=='hrc') {
		//$response1 = $g2bClass->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 용역사전규격
		$pss = '용역';
		$stmt = $dbConn->getSvrDataDB2($conn,'hrcservc',$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
	
	}
	//$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰
	if (strpos($response1,'Temporary Redirect')) {
		echo '<p style="font-weight: bold; color: rgb(255,0,0);">Server 일시 중단 상태 입니다.</p>';
		exit;
	}
	// 사전규격등록번호,품명,배정예산금액,등록일시,실수요기관명,의견등록마감일시
	//$colArray = [ 'bfSpecRgstNo', 'prdctClsfcNoNm', 'asignBdgtAmt', 'rgstDt', 'rlDminsttNm', 'opninRgstClseDt', 'bidNtceNoList' ];
	$colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt','bidNtceDtlUrl');
	$json_string = $g2bClass->compressJson($response1, $colArray,$pss);

	echo ($json_string);

	exit;
//var_dump($response1);

//$json1 = json_decode($response1, true);
//$item1 = $json1['response']['body']['items'];
//echo '<br>'.'물품사전규격<br>';
//var_dump($item1);



/*

$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];
//echo '<br>'.'공사입찰<br>';
//var_dump($item2);
$json3 = json_decode($response3, true);
$item3 = $json3['response']['body']['items'];

//var_dump($item4);
$item = array_merge($item1,$item2,$item3); //,$item4);
//var_dump($item);
*/
}




?>

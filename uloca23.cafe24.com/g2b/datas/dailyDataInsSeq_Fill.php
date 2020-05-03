<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');

//----------------------------------------
// g2b.js 파라미터
//----------------------------------------
// url = '/g2b/datas/dailyDataInsSeq.php?
// bidNtceNo='+bidNtceNo+'		 	//공고No
// bidNtceOrd='bidNtceOrd'			//공고차수
// &openBidSeq='+openBidSeq_xxxx+' 	//테이블명
// &bidIdx='+bidIdx+			 	//bidIdx (openbidinfo)
//'&pss='+pss;						//공사,물품,용역
 
$g2bClass = new g2bClass;
$dbConn   = new dbConn;
$conn       = $dbConn->conn();

$bidIdx     = $_GET['bidIdx'];
$openBidSeq = $_GET['openBidSeq'];
$bidNtceNo  = $_GET['bidNtceNo'];
$bidNtceOrd = $_GET['bidNtceOrd'];
$pss        = $_GET['pss'];

mysqli_set_charset($conn, 'utf8');
if ($openBidSeq == '') {
	$msg .= 'ln23::opneBidSeq_xxxx 테이블명이 없습니다.';
	exit;
}
if ($bidNtceNo == '') {
	$msg .= '공고번호가 없습니다.';
	exit;
}

// ----------------------------------------------------
// 누락된 입찰공고 INSERT 해야 함
// ----------------------------------------------------
$sql  = " SELECT bidNtceNo, bidNtceOrd, rgstTyNm FROM openBidInfo WHERE 1 ";
$sql .= "    AND bidNtceNo =  '" .$bidNtceNo. "' ";
$sql .= "    AND bidNtceOrd = '" .$bidNtceOrd. "' ";
$dbResult = $conn->query($sql);
if ($row = $dbResult->fetch_assoc()) {
	$rgstTyNm = $row['rgstTyNm'];	// 연계기관 공고건 확인필요
}

$rowCount = $dbResult->num_rows;
$startDate = '';
$endDate   = '';
$kwd       = '';
$dminsttNm = '';
$numOfRows = 999;
$pageNo = 1;

if ($rowCount == 0){ 	// 공고가 DB에 없음
	$response = $g2bClass->getBidInfo($bidNtceNo, $bidNtcOrd, $bidrdo);
	$json = json_decode($response, true);
	$item = $json['response']['body']['items'];
	// 공고차수 까지 오고 있음
	foreach ($item as $arr) {
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
		$sql .= "'"             .$arr['bfSpecRgstNo'].  "' ) ";
		if (!($conn->query($sql))) echo "ln83 error sql=" . $sql . "<br>";
		$rgstTyNm = $arr['rgstTyNm'];	// 연계기관 공고건 확인필요
	}

	$msg = "공고추가:" .$bidNtceNo. "-" .$bidNtcOrd. "<br>";
}

//---------------------------------------------------------
$idx++; //openbidSeq max(id)
//예정가격, 기초금액 업데이트
updateInfo($conn, $g2bClass, $bidNtceNo, $pss);
//---------------------------------------------------------
$cnt   = 0;

//-----------------------------------------------------------------
// 낙찰이력 가져옴 getRsltDataAll --> 999건 이상
//-----------------------------------------------------------------
$item1 = $g2bClass->getRsltDataAll($bidNtceNo, $bidNtceOrd);
// $item1 = $g2bClass->getRsltData($bidNtceNo, $bidNtceOrd);
// $json1 = json_decode($response1, true);
// $item1 = $json1['response']['body']['items'];
$cnt = count($item1);
$youchalCnt = 0; // '유찰'로 업데이트한 갯수
$i   = 1;
$k   = 1;
if ($cnt > 0) {
	foreach ($item1 as $arr) {
        //---------------------------
		$rmrk = addslashes($arr['rmrk']); // 비고 - '낙찰하한선 미달' 등
		$k = (int) $arr["opengRank"];

		switch ($k) {
			case 0: 
				$k = $i;				// 낙찰미달이면 opengRank에 값이 없음
				$Rank_rmark = $rmrk;    // 비고 대입
				break;

			default:
				$Rank_rmark = (string)$k; // 순위 대입
				break;
		} 

		//기초금액과 투찰율 계산
		 $bssamtrt = ( $arr['bidprcAmt'] / $bssamt1 ) * 100;
		//-------------------------------------------------
		//$openBidInfo 에 1순위 정보 추가 순위 -by jsj 190601 <== 데이터 없음
		//-------------------------------------------------
		if ($k == 1) { // openRank 1순위 or $ii = 1(첫번째) -by jsj 190601
			$sql  = "UPDATE openBidInfo SET ";
			$sql .= "       prtcptCnum = '"    .$cnt. "', ";								// 참가업체수
			$sql .= "       bidwinnrNm = '"    .addslashes($arr['prcbdrNm']). "', ";		// 최종낙찰업체명
			$sql .= "       bidwinnrBizno = '" .$arr['prcbdrBizno'].          "', ";		// 사업자번호
			$sql .= "       bidwinnrCeoNm = '" .$arr['bidwinnrCeoNm'].        "', ";		// 대표자명
			$sql .= "       bidwinnrTelNo = '" .$arr['bidwinnrTelNo'].        "', ";		// 업체연락처
			$sql .= "       sucsfbidAmt = '"   .$arr['sucsfbidAmt'].          "', ";		// 최종낙찰금액
			$sql .= "       sucsfbidRate = '"  .$arr['bidprcrt'].             "', ";		// 투찰율 = 투찰금액/예정가격
			$sql .= "       rlOpengDt = '"     .$arr['rlOpengDt'].            "', ";		// 실개찰일시
			$sql .= "       progrsDivCdNm =    '개찰완료', ";  								 // 진행구분 - '개찰완료'로 표시 (API 데이터가 안오는 경우가 있으므로 직접입력)
			$sql .= " 	    modifyDT = now()";
			$sql .= " WHERE bidNtceNo= '"      .$bidNtceNo. 				  "'  ";
			// $sql .= "   AND bidNtceOrd= '"     .$bidNtceOrd.                  "'  ";
			if (!($conn->query($sql)))  $msg .= ("ln99::Err Sql=" .$sql. ", <br>");
		}
		//----------------------------------------------------------------
		// openBidSeq_Fill 에 key ==> unique(bdidNtceNo, bidNtceOrd, compno)
		//----------------------------------------------------------------
		$sql  = " REPLACE INTO openBidSeq_Fill ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx )";
		$sql .= "  VALUES ( '" .$arr['bidNtceNo']. "','" .$arr['bidNtceOrd']. "','" .$arr['rbidNo'].   "','" .$arr['prcbdrBizno'] . "','" .$arr['bidprcAmt'] . "', ";
		$sql .= "           '" .$arr['bidprcrt'].  "','" .$arr['drwtNo1'].    "','" .$arr['bidprcDt']. "','" .trim($Rank_rmark).          "',"  .$bidIdx. ")";
		if (!($conn->query($sql))) $msg .= ("ln140::Err Sql=" .$sql. ", <br>");
		$i++;
	}
} else {  // 낙찰건수가 없으면 개찰일시(-1 day)와 비교해서 '유찰' 또는 '연계기관 공고건' 업데이트

		$sql  = "UPDATE openBidInfo SET ";
		if ($rgstTyNm == '연계기관 공고건') {
			$sql .= "       progrsDivCdNm = '연계기관'";
		} else {
			$sql .= "       progrsDivCdNm = '유찰'";
		}
		$sql .= " WHERE bidNtceNo  = '" .$bidNtceNo. "'";
		$sql .= "   AND bidNtceOrd = '" .$bidNtceOrd. "'";
		$sql .= "   AND date_format(opengDt,'%Y%m%d') < date_add(now(), interval -1 day)  ";

	if (!($conn->query($sql))) $msg .= ("ln150::Err Sql=" .$sql. ", <br>");	
	if ($rgstTyNm == '연계기관 공고건') {
		$msg .= "연계기관";
	} else {
		$youchalCnt++;
	}
}	// end if ($cnt>0)

// =======================================================================
// 업데이트 결과 - 개찰일시(opengDt)가 지나고 건수=0 이면 '유찰'로 업데이트 함
// =======================================================================
if ($youchalCnt == 1 ) {
	$msg .= "유찰";
} else {
	$msg .= "건수=" .$cnt; 
}

echo $msg;
// ============================================


function updateInfo($conn, $g2bClass, $bidNtceNo, $pss)
{
	if      ($pss == '입찰물품') $bidrdo = 'opnbidThng';   //$bsnsDivCd = '1'; // 물품
	else if ($pss == '입찰공사') $bidrdo = 'opnbidCnstwk'; //$bsnsDivCd = '1'; // 공사
	else if ($pss == '입찰용역') $bidrdo = 'opnbidservc';  //$bsnsDivCd = '1'; // 용역

	$itemr = $g2bClass->getSvrDataOpn($bidrdo, $bidNtceNo); //tot_getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd);
	$json1 = json_decode($itemr, true);
	//$iRowCnt = $json1['response']['body']['totalCount'];
	$items = $json1['response']['body']['items'];
	if (count($items) > 0) {
		foreach ($items as $arr) {
			// 예정가격, 기초금액 업데이트
			$bidNtceNo1 = updateopenBidInfo($conn, "", "", $arr, $bidNtceNo);
		}
	}
}

//------------------------------------
//예정가격, 기초금액 업데이트 -jsj 190507
//------------------------------------
function updateopenBidInfo($conn, $openBidInfo, $idx, $arr, $bidNtceNo)
{
	//echo '<br>'.$bidNtceNo .'/'. $arr['bidNtceNo'];
	//if ($bidNtceNo == $arr['bidNtceNo']) return $bidNtceNo;
	//$bidNtceNo = $arr['bidNtceNo'];
	$sql = "UPDATE openBidInfo SET rsrvtnPrce = '" . $arr['plnprc'] . "', ";
	$sql .= "bssAmt = '" . $arr['bssamt'] . "', ";
	$sql .= "ModifyDT = now() ";
	$sql .= " WHERE bidNtceNo='" . $bidNtceNo . "';";
	//echo $sql.'<br>';
	$conn->query($sql);
	return $bidNtceNo; // update
}

<?php

@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

/*
 * 이메일  찾기 
 */
//getCompInfo2($numOfRows,$pageNo,$inqryDiv,$inqryBgnDt,$inqryEndDt) 
//              한페이지결과수, 페이지번호, 등록일기준=1, 시작일, 종료일 
//companyInfoUpdate($conn,$g2bClass, $compno=1 );
//companyInfoUpdate($conn,$g2bClass, '20130101', '20130105');

// global 변수: 최종 INSERT, UPDATE, INSERT_email 갯수 
$insertCountSum = 0; 
$updateCountSum = 0; 
$insertCountSum_email = 0; 
$updateCountSum_email;

function companyInfoUpdate($conn, $g2bClass, $str, $end) {
	$strDate = $str;	// 시작일 
	$endDate = $end;	// 종료일 
	$numOfRows = 999; 	// 한페이지 결과
	$pageNo = 1;		// 페이지번호 

	// 기업검색 API call
	$response1 = $g2bClass->getCompInfo2($numOfRows, $pageNo, searType, $strDate, $endDate);
	
	// 최초 API 호출 후 numOfRows  계산 후 lastPageNo 계산
	$json1 = json_decode($response1, true);
	$totalCount = $json1['response']['body']['totalCount'];		// totalcount
	$lastPageNo = intval(($totalCount/$numOfRows)+1);			// 마지막 페이지번호
	$item0 = $json1['response']['body']['items'];

	echo ("<br> 전체시작 ==>startDate: ".$strDate." ~ ".$endDate." searType:[".searType."]");
	echo ("<br> totalCount=".$totalCount.", numOfRows=".$numOfRows.", lastPageNo=".$lastPageNo);
	echo ("<br>-------------------------------------------------------------------------------------");
	
	//return;
	/*
	 * 몇가지 조치 totalcoutn 없거 등 
	 */
	//var_dump($json1);
	
	if ($totalCount <= 0 || $totalCount == '') return;
	// 
	if($pageNo == $lastPageNo) {
		$numOfRows = $totalCount % $numOfRows;
		echo ("<br> numOfRows:   ".$numOfRows);
		if($numOfRows == 0) return;
	}
	//DB-Update function call, *(pageNo ==> 최초 1
	$rtn = updateCompInfo($conn, $item0, $pageNo, $lastPageNo, $numOfRows );
	
	// ---- 결과 표시 
	echo ("<br>------------------------------------------------------------------------------------------");
	echo ("<br>PageNo: [".$pageNo."] RESULT--> INSERT:[".$rtn[0]."] InsertEmail:[".$rtn[4]."], UPDATE:[".$rtn[2]."], updateEmail:[".$rtn[6]."]");
	
	// 첫페이지만 보기 -debug;
	//if ($pageNo == 1) return;
	// 두번째부터 loop API 호출 계산된 lastPageNo 만큼 loop
	while(++$pageNo <= $lastPageNo){
		
		// API call: pageNo: 2 ~ lastPageNo
		$response1 = $g2bClass->getCompInfo2($numOfRows, $pageNo, searType, $strDate,$endDate);
		$json1 = json_decode($response1, true);
		$item0 = $json1['response']['body']['items'];
		
		// total count % lastPageNo 마지막 페이지는 nomOfRows count 계산
		if($pageNo == $lastPageNo) {
			$numOfRows = $totalCount % $numOfRows;
			if($numOfRows == 0) continue; 
		}
		
		//DB-Update function call, *(pageNo -1) ==> 최초 1
		$rtn = updateCompInfo($conn, $item0, $pageNo, $lastPageNo, $numOfRows );
		echo ("<br>------------------------------------------------------------------------------------------");
		echo ("<br>PageNo: [".$pageNo."] RESULT--> INSERT:[".$rtn[0]."] InsertEmail:[".$rtn[4]."], UPDATE:[".$rtn[2]."], updateEmail:[".$rtn[6]."]");
		
		//if($pageNo = 2) break;
		//return; //debug 1 ~ 2 pageNo
	}
	
	echo ("<br>----------------------------------------------------------------------------------");
	echo ("<br> TOTAL--> INSERT:[".$rtn[1]."] Insert-Email:[".$rtn[5]."], UPDATE:[".$rtn[3]."], Update-Email:[".$rtn[7]."]");
	echo ("<br>----------------------------------------------------------------------------------");
	//process end
}	
//---------------------------------------------------
// 기업정보 API 사업자번호가 없으면 INSERT + 이메일추가 
// 사업자번호 있으면 업데이트 + 이메일추가 
//---------------------------------------------------
function updateCompInfo($conn, $item0, $pageNo, $lastPageNo, $numOfRows ) {
	/* 
	 * DB에 있으면 UPDATE - 이메일 있으면 제외하고 UPDATE --> 그냥 업데이트 하기로 
	 * DB에 없으면 INSERT - 이메일 없으면 제외하고 INSERT --> 있던 없던 걍 INSERT
	 */
	global $insertCountSum, $updateCountSum, $insertCountSum_email, $updateCountSum_email;
	$insertCount = 0; 
	$updateCount = 0; 
	$insertCount_email = 0;
	$updateCount_email = 0;
	
	$i = 0;
	do {	 // i =0 부터 시작해야 하므로 do ~ while
		
		$bizno    = trim($item0[$i]['bizno']); 		// 사업자번호
		$corpNm   = trim($item0[$i]['corpNm']);  	// 회사명
		$ceoNm    = trim($item0[$i]['ceoNm']); 	 	// 대표자
		$telNo    = trim($item0[$i]['telNo']); 	 	// 전화번호
		$faxNo    = trim($item0[$i]['faxNo']); 	 	// 팩스번호
		$hmpgAdrs = trim($item0[$i]['hmpgAdrs']);	// 기업홈페이지	
		
		// 홈페이지에 '//' 있으면 // 이후로 파싱해서 이메일 체크 
		if (strpos($hmpgAdrs, '//' ) ) { 
			$email = str_replace('//','',strstr($hmpgAdrs , "//")); //이메일
		} else {
			$email = $hmpgAdrs;
		}
		/*
		 * DB에 있으면 UPDATE 없으면 INSERT  && 이메일 파싱
		 */
		$sql = " SELECT compno, hmpgAdrs ";
		$sql .= "  FROM openCompany ";
		$sql .= " WHERE compno ='". $bizno . "'";
		$result0 = $conn->query($sql);

		if ($row = $result0->fetch_assoc()) {
			$compno = $row['compno'];			// 사업자번호 
			
			// 이메일 있으면 파싱해서 업데이트 
			if($check_email=filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = " UPDATE openCompany SET phone ='".$telNo."', faxNo ='".$faxNo."', hmpgAdrs = '".$hmpgAdrs."', email = '".$check_email."', ModifyDT = now()";
					$sql .= " WHERE compno = '".$compno."'";
					$updateCount_email++;
			} else { // 이메일 아니면 빼고 업데이트 
					$sql = " UPDATE openCompany SET phone ='".$telNo."', faxNo ='".$faxNo."', hmpgAdrs = '".$hmpgAdrs."', ModifyDT = now()";
					$sql .= " WHERE compno = '".$compno."'";
					$updateCount ++;
			}
			$result0 = $conn->query($sql);
		/*
		 * DB에 기업정보 없으면 API DATA INSERT
		 */
		} else {
			if($check_email=filter_var($email, FILTER_VALIDATE_EMAIL)){  // 이메일 있음 
				$sql =  "INSERT INTO openCompany_tmp (compno, compname, repname, phone, faxNo, hmpgAdrs, email)";
				$sql .= "VALUES ('".$bizno ."','".$corpNm."','".$ceoNm."','".$telNo."','".$faxNo."','".$hmpgAdrs."','".$check_email."')";
				$insertCount_email ++;
			} else{	//이메일 없이 INSERT
				$sql =  "INSERT INTO openCompany_tmp (compno, compname, repname, phone, faxNo, hmpgAdrs)";
				$sql .= "VALUES ('".$bizno ."','".$corpNm."','".$ceoNm."','".$telNo."','".$faxNo."','".$hmpgAdrs."')";
				$insertCount ++;				
			}
			$result0 = $conn->query($sql);
			
			//echo ("<br>".$sql);
		} 
	} while (++$i < $numOfRows);
		
	//echo("<br>----------------------------------------------------------------");
	//echo("<br> DB-Update결과 ==>  page/LastPage:[ ".$pageNo."/".$lastPageNo." ], cnt/numOfRows:[ ".($i)."/".$numOfRows." ]<br>");
	$insertCountSum += $insertCount;
	$updateCountSum += $updateCount;
	$insertCountSum_email += $insertCount_email;
	$updateCountSum_email += $updateCount_email;
	
	return ([$insertCount, $insertCountSum, $updateCount, $updateCountSum, $insertCount_email, $insertCountSum_email, $updateCount_email, $updateCountSum_email]);
	
} //updateCompInfo;

define("searType", 1); 		// 1:등록일기준, 2:변경일기준, 3:사업자등록번호
companyInfoUpdate($conn, $g2bClass, "20190101", "20200307");

?>
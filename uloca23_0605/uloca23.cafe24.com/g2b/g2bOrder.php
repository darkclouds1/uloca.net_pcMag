<?php
/*
	발주계획 받기 오늘부터 1달치 2019/02/11 
*/
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
$dbConn = new dbConn;

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck();

if(!isset($thismonth)) $thismonth = date("Ym"); //$today;
	if(!isset($endmonth)) {
		$timestamp = strtotime("3 month");
		$endmonth = date("Ym", $timestamp);
	}
//echo 'thismonth='.$thismonth.' endmonth='.$endmonth;
//exit;
$items = $g2bClass->getSvrDataOrderAll($thismonth,$endmonth);
//$json1 = json_decode($response1, true);
//$item1 = $json1['response']['body']['items'];

//echo ($items);
//exit;
/*
compressJsonOrder 만들것 ----------------------------------------------

공고번호: 발주시기	orderYear/orderMnth
구분: 문자4자리		업무구분명	bsnsDivNm	앞에 계획 붙일것
공고명: 사업명		사업명	bizNm
추정가격: 예산액		합계발주금액	sumOrderAmt
공고일: 게시일자		게시일시	nticeDt
수요기관: 발주기관	발주기관명	orderInsttNm
개찰일시: 빈칸
"items": [ { "bsnsDivCd": "1", "bsnsDivNm": "물품", "bsnsTyCd": "3", "bsnsTyNm": "해당없음", "orderYear": "2019", "orderInsttCd": "7780033", "totlmngInsttNm": "경기도교육청", "jrsdctnDivCd": "03", "jrsdctnDivNm": "교육기관", "orderInsttNm": "경기도시흥교육청 시흥초등학교", "orderPlanSno": "1", "prcrmntMethd": "자체조달", "orderMnth": "02", "bizNm": "2019학년도 돌봄교실 위탁 간식업체 선정", "cnstwkRgnNm": "", "cnsttyDivNm": "", "cntrctMthdNm": "제한경쟁", "orderContrctAmt": "", "orderGovsplyMtrcst": "", "orderEtcAmt": "", "sumOrderAmt": "18711000", "deptNm": "교육행정실", "ofclNm": "이현자", "telNo": "070-7097-2906", "agrmntYn": "", "usgCntnts": "돌봄교실 간식", "qtyCntnts": "12,474", "unit": "개", "prdctClsfcNo": "50181901", "dtilPrdctClsfcNo": "5018190101", "prdctClsfcNoNm": "신선한빵", "ntceNticeYn": "N", "cnstwkMngNo": "", "orderOrd": "", "sumOrderDolAmt": "", "rcritRgstNo": "", "specItemNm1": "", "specItemNm2": "", "specItemNm3": "", "specItemNm4": "", "specItemNm5": "", "specItemCntnts1": "", "specItemCntnts2": "", "specItemCntnts3": "", "specItemCntnts4": "", "specItemCntnts5": "", "bdgtDivCd": "", "cnstwkPrdCntnts": "", "nticeDt": "2019-02-26 21:46:47", "orderThtmContrctAmt": "", "orderNtntrsAuxAmt": "", "dtilPrdctClsfcNoNm": "신선한빵", "specCntnts": "돌봄교실 간식", "dsgnDocRdngPlceNm": "", "dsgnDocRdngPrdCntnts": "", "rmrkCntnts": "", "orderPlanUntyNo": "1-3-2019-7780033-000001", "bidNtceNoList": "" }
"orderYear": "2019", "orderMnth": "02", "bsnsDivNm": "물품", "bizNm": "2019학년도 3월 학교급식용 가금류 구입 소액수의 견적 제출공고", "sumOrderAmt": "1415820", "nticeDt": "2019-02-11 15:31:43", "orderInsttNm": "울산광역시강북교육청 명덕초등학교", "pss": ""
*/
$colArray1 = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'opengDt','bidNtceDtlUrl','pss','cntrctMthdNm','specCntnts','dtilPrdctClsfcNoNm','qtyCntnts','unit','telNo','ofclNm','deptNm');
$colArray2 = array ( 'orderYear', 'orderMnth',  'bizNm', 'sumOrderAmt', 'nticeDt', 'orderInsttNm','', '', 'bsnsDivNm','cntrctMthdNm','specCntnts','dtilPrdctClsfcNoNm','qtyCntnts','unit','telNo','ofclNm','deptNm');
// 사업명 bidNtceNm, 업무 pss, 발주시기 bidNtceNo-bidNtceOrd
// 계약방법 cntrctMthdNm,발주기관 orderInsttNm,게시일시 nticeDt,용도 specCntnts,품명 dtilPrdctClsfcNoNm,수량 qtyCntnts
// 구매예정금액 sumOrderAmt, 수량단위 unit, 전화번호 telNo,담당자 ofclNm,부서명 deptNm
//$kwd='개선';
//$dminsttNm = ''; //'해양경찰청';
	$json_string = $g2bClass->compressJsonOrder($items, $colArray1,$colArray2,$kwd,$dminsttNm);


echo $json_string; //response1;

?>
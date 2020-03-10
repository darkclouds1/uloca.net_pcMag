<?php
header('Content-Type: text/html; charset=UTF-8');

@extract($_GET); 
@extract($_POST); 
@extract($_SERVER);
session_start();
ob_start();
date_default_timezone_set('Asia/Seoul');

require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

//$bidNtceNo='20170532764'; 
//$bidNtceOrd='00';
//url = 'http://uloca23.cafe24.com/g2b/bidResult.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&pss='+pss;

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
$dbConn = new dbConn;
$conn = $dbConn->conn(); //
// 코드집 가져오기 (최초한번만 DB Call) -by jsj 20190913
// $codeBook = $dbConn->KedCdNmSearch($conn);
// 코드집 ->코드명 가져오기 (sample)
//$codeNm = $dbConn->codeCdNm($codeBook, 'cr_grd','01');

// KED API 여기서 안하고, ul_compSearch 에서 API 호출함 -by jsj 20190923
/* 
if ($compno == '') {
	echo ("사업자번호가 없습니다. (null): ".$compno);
	exit;
}

$kedUrl_E017 = "https://testkedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?user_id=ulocaonl&process=S&bzno=".$compno."&cono=&pid_agr_yn=N&jm_no=E017";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $kedUrl_E017);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($ch);
curl_close($ch);
$xml=simplexml_load_string($res);
if ($xml->HEADER->err_cd != "00") {
	echo "한국기업데이터의 신용 정보가 없습니다.";
	echo "err_cd = ".$xml->HEADER->err_cd;
	echo ", err_info = ".$xml->HEADER->err_info.'<br>';
	//exit;
}

$E017 = $xml->CONTENTS->E017;
$kedcd = $xml->CONTENTS->E017->kedcd;
//기업신용등급 코드명 
$cr_grd_codeNm = $dbConn->codeCdNm($codeBook, 'cr_grd', $E017->cr_grd);
//============================================== DB insert
$dbConn->cmpE01701M_1($conn,$xml);	// 기업 정보 메인
$dbConn->cmpCredit01M_1($conn,$xml);// 신용 정보
$dbConn->cmpE01702D_1($conn,$xml);	//	주주 현황
$dbConn->cmpFrsum01M_1($conn,$xml);	//	결산 내역
$dbConn->cmpFssum01M_1($conn,$xml);	//	자산 현황
$dbConn->cmpCfsum01M_1($conn,$xml);	//	요약현금흐름분석 cf_anal_summ
//==============================================
*/ //<== 여기까지 KED API 호출

// --------------------------------- log
$rmrk = '업체정보창';
$dbConn->logWrite2($id,$_SERVER['REQUEST_URI'],$rmrk,'','10');
// --------------------------------- log

$inqryDiv = 3; // 사업자등록번호 기준검색
//$bizno = '1048649560'; //'1068141991'; // (주)에이치케이비즈 '1048649560'; // 사업자등록번호 주식회사 디자인비즈
$response1 = $g2bClass->getCompInfo(1,1,$inqryDiv,$compno);
$json1 = json_decode($response1, true);
$item0 = $json1['response']['body']['items'];
//var_dump($response1);

// 업체정보창 열면 openCompany Table에 업뎃 -by jsj 20190502
$compname = $item0[0]['corpNm']; //회사
$repname = $item0[0]['ceoNm'];	//대표자
$phone = $item0[0]['telNo']; 	//전번
$faxNo = $item0[0]['faxNo']; 	//팩스

// 업체명, 대표자, 전번, 팩스 업데이트 ==> 무조건 업데이트 
$sql = "REPLACE openCompany SET compName='".$compname."', repName='".$repname."', phone ='".$phone."', faxNo ='".$faxNo."', hmpgAdrs = '".$hmpgAdrs."', ModifyDT = now()";
$sql .= " WHERE compno = '".$compno."'";
$conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
<title>업체정보<?=$compno?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="format-detection" content="telephone=no">  <!--//-by jsj 전화걸기로 링크되는 것 막음 -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">
<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script src="/g2b/g2b.js"></script>
<center>
<!-- 입찰정보 -->

<div id="contents">

<div class="detail_search" >

	<? if ($from != 'getBid') { ?>
		<div class="btn_area">
		<a onclick="detailCompany('<?=$item0[0]['bizno']?>')" class="search" style='width:140px'>KED 상세검색</a>
		<a onclick="self.close()" class="search">닫기</a>
		</div>
		<br>
	<? } ?>

	<table border="1" align=center cellpadding="0" cellspacing="0" width="100%" class="grid05">
		<tr>
			<th style='text-align:center;'>업체명</th>
			<td>&nbsp;<?=$item0[0]['corpNm']?></td>
			<th style='text-align:center;'>영문업체명</th>
			<td>&nbsp;<?=$item0[0]['engCorpNm']?></td>
		</tr>
		<tr>
			<th style='text-align:center;'>사업자등록번호</th>
			<td style='border-top:solid 1px #99bbe8;'>&nbsp; <?=$item0[0]['bizno']?></td>
			<th style='text-align:center;'>대표자명</th>
			<td>&nbsp;<?=$item0[0]['ceoNm']?></td>
		</tr>
		
		<tr>
			<th style='text-align:center;'>개업일시</th>
			<td>&nbsp;<?=$item0[0]['opbizDt']?></td>
			<th style='text-align:center;'>등록일시</th>
			<td>&nbsp;<?=$item0[0]['rgstDt']?></td>
		</tr>
		
		<tr>
			<th style='text-align:center;'>지역명</th>
			<td>&nbsp;<?=$item0[0]['rgnNm']?></td>
			<th style='text-align:center;'>우편번호</th>
			<td>&nbsp;<?=$item0[0]['zip']?></td>
		</tr>
		<tr>
			<th style='text-align:center;'>주소</th>
			<td>&nbsp;<?=$item0[0]['adrs']?>&nbsp;<?=$item0[0]['dtlAdrs']?></td>
			<th style='text-align:center;'>본사구분</th>
			<td>&nbsp;<?=$item0[0]['hdoffceDivNm']?></td>
		</tr>
		<tr>
			<th style='text-align:center;'>전화번호</th>
			<td>&nbsp;<?=$item0[0]['telNo']?></td>
			<th style='text-align:center;'>팩스번호</th>
			<td>&nbsp;<?=$item0[0]['faxNo']?></td>
		</tr>
		<tr>
			<th style='text-align:center;'>홈페이지주소</th>
			<td>&nbsp;<a href='<?=$hmpgAdrs?>' target='_blank'><?=$hmpgAdrs?></a></td>
			<th style='text-align:center;'>제조구분명</th>
			<td>&nbsp;<?=$item0[0]['mnfctDivNm']?></td>
		</tr>
		<tr>
			<th style='text-align:center;'>종업원수</th>
			<td>&nbsp;<?=number_format($item0[0]['emplyeNum'])?></td>
			<th style='text-align:center;'>업체업무구분</th>
			<td>&nbsp;<?=$item0[0]['corpBsnsDivNm']?></td>
		</tr>
		<tr>
		</tr>
	</table>

</div></div>

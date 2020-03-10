<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
session_start();
ob_start();
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
//$bidNtceNo='20170532764';
//$bidNtceOrd='00';
//url = 'http://uloca23.cafe24.com/g2b/bidResult.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&pss='+pss;
	
$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn(); //

// --------------------------------- log
$rmrk = '업체정보창';
$dbConn->logWrite2($id,$_SERVER['REQUEST_URI'],$rmrk,'','10');
// --------------------------------- log
$inqryDiv = 3; // 사업자등록번호 기준검색
//$bizno = '1048649560'; //'1068141991'; // (주)에이치케이비즈 '1048649560'; // 사업자등록번호 주식회사 디자인비즈

// 사용자정보서비스
$response1 = $g2bClass->getCompInfo(1,1,$inqryDiv,$compno); 
$json1 = json_decode($response1, true);
$item0 = $json1['response']['body']['items'];

if (substr($item0[0]['hmpgAdrs'], 0, 7) === "http://") $hmpgAdrs= $item0[0]['hmpgAdrs'];
else $hmpgAdrs= 'http://'.$item0[0]['hmpgAdrs'];
//var_dump($response1);
?>
<!DOCTYPE html>
<html>
<head>
<title>업체정보/<?=$compno?></title>
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
<div style='font-size:14px; color:blue;font-weight:bold'>- 발주 계획 -</div>


<?
if ($mobile == "Mobile") {
?>
<div id="contents">
<div class="detail_search" >
	<table align=center cellpadding="0" cellspacing="0" width="100%" class="grid05">
		<colgroup>
			<col style="width:15%;" /><col style="width:35%;" /><col style="width:15%;" /><col style="width:35%;" />
		</colgroup>
		<tbody>
			<tr>
				<th style='text-align:center;'>사업명</th>
				<td style='border-top:solid 1px #99bbe8;' colspan=3>&nbsp;
					<?=$bidNtceNm?>
				</td>
			</tr>
			<tr>
				<th style='text-align:center;'>업무</th>
				<td>&nbsp;<?=$pss?></td>
				<th style='text-align:center;'>발주시기</th>
				<td>&nbsp;<?=$bidNtceNo?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>계약방법</th>
				<td>&nbsp;<?=$cntrctMthdNm?></td>
				<th style='text-align:center;'>발주기관</th>
				<td>&nbsp;<?=$orderInsttNm?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>게시일시</th>
				<td>&nbsp;<?=$nticeDt?></td>
				<th style='text-align:center;'>용도</th>
				<td>&nbsp;<?=$specCntnts?>&nbsp;<?=$item0[0]['dtlAdrs']?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>품명</th>
				<td>&nbsp;<?=$dtilPrdctClsfcNoNm?></td>
				<th style='text-align:center;'>수량</th>
				<td>&nbsp;<?=$qtyCntnts?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>구매예정금액</th>
				<td>&nbsp;<?=number_format($sumOrderAmt)?> 원</td>
				<th style='text-align:center;'>수량단위</th>
				<td>&nbsp;<?=$unit?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>전화번호</th>
				<td>&nbsp;<?=$telNo?></td>
				<th style='text-align:center;'>담당자</th>
				<td>&nbsp;<?=$ofclNm?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>정보제공기관</th>
				<td>&nbsp;<?=$info?></td>
				<th style='text-align:center;'>부서명</th>
				<td>&nbsp;<?=$deptNm?></td>
			</tr>
			
		</table>
<? } else { ?>
<!--	<input id="bidNtceNm" name="bidNtceNm" type="hidden" value="사업명"/>
	<input id="pss" name="pss" type="hidden" value="업무"/>
	<input id="bidNtceNo" name="bidNtceNo" type="hidden" value="2019-02"/>
	<input id="cntrctMthdNm" name="cntrctMthdNm" type="hidden" value="계약방법"/>
	<input id="orderInsttNm" name="orderInsttNm" type="hidden" value="발주기관"/>
	<input id="info" name="info" type="hidden" value="나라장터"/>
	<input id="nticeDt" name="nticeDt" type="hidden" value="게시일시"/>
	<input id="specCntnts" name="specCntnts" type="hidden" value="용도"/>
	<input id="dtilPrdctClsfcNoNm" name="dtilPrdctClsfcNoNm" type="hidden" value="품명"/>
	<input id="qtyCntnts" name="qtyCntnts" type="hidden" value="수량"/>
	<input id="sumOrderAmt" name="sumOrderAmt" type="hidden" value="구매예정금액"/>
	<input id="unit" name="unit" type="hidden" value="수량단위"/>
	<input id="telNo" name="telNo" type="hidden" value="전화번호"/>
	<input id="ofclNm" name="ofclNm" type="hidden" value="담당자"/>
	<input id="deptNm" name="deptNm" type="hidden" value="부서명"/ --> 
<div id="contents" style='width:70%'>
<div class="detail_search" >
	<table align=center cellpadding="0" cellspacing="0" width="90%" class="grid05">
		<colgroup>
			<col style="width:15%;" /><col style="width:35%;" /><col style="width:15%;" /><col style="width:35%;" />
		</colgroup>
		<tbody>
			<tr>
				<th style='text-align:center;'>사업명</th>
				<td style='border-top:solid 1px #99bbe8;' colspan=3>&nbsp;
					<?=$bidNtceNm?>
				</td>
			</tr>
			<tr>
				<th style='text-align:center;'>업무</th> 
				<td>&nbsp;<?=$pss?></td>
				<th style='text-align:center;'>발주시기</th>
				<td>&nbsp;<?=$bidNtceNo?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>계약방법</th>
				<td>&nbsp;<?=$cntrctMthdNm?></td>
				<th style='text-align:center;'>발주기관</th>
				<td>&nbsp;<?=$orderInsttNm?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>게시일시</th>
				<td>&nbsp;<?=$nticeDt?></td>
				<th style='text-align:center;'>용도</th>
				<td>&nbsp;<?=$specCntnts?>&nbsp;<?=$item0[0]['dtlAdrs']?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>품명</th>
				<td>&nbsp;<?=$dtilPrdctClsfcNoNm?></td>
				<th style='text-align:center;'>수량</th>
				<td>&nbsp;<?=$qtyCntnts?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>구매예정금액</th>
				<td>&nbsp;<?=number_format($sumOrderAmt)?> 원</td>
				<th style='text-align:center;'>수량단위</th>
				<td>&nbsp;<?=$unit?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>전화번호</th>
				<td>&nbsp;<?=$telNo?></td>
				<th style='text-align:center;'>담당자</th>
				<td>&nbsp;<?=$ofclNm?></td>
			</tr>
			<tr>
				<th style='text-align:center;'>정보제공기관</th>
				<td>&nbsp;<?=$info?></td>
				<th style='text-align:center;'>부서명</th>
				<td>&nbsp;<?=$deptNm?></td>
			</tr>
			
		</table>
<? } ?>
		<div class="btn_area">
		<a onclick="self.close()" class="search">닫기</a>
		</div>
		
</div></div>

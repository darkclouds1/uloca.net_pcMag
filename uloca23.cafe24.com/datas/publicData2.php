<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
ob_start();
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

 
if ($endDate == "") {
	$endDate = date("Y-m-d"); //$today;
	$timestamp = strtotime("-1 months");
	$startDate = date("Y-m-d", $timestamp);
} 
if ($chkBid == '' && $chkHrc == '') $chkBid = 'bid';
?>


<?
$startDate = str_replace('-','',$startDate);
$endDate = str_replace('-','',$endDate);
//echo ('<br>startDate='.$startDate.'<br>');
if ($kwd == "") exit;

//echo 'bidrdo='.$bidrdo; // bid= 입찰 scsbid = 낙찰
/*
입찰정보
요청주소  http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch
서비스URL  http://apis.data.go.kr/1230000/BidPublicInfoService
https://www.data.go.kr/subMain.jsp?param=T1BFTkFQSUAxNTAwMDgwMg==#/L3B1YnIvcG90L215cC9Jcm9zTXlQYWdlL29wZW5EZXZHdWlkZVBhZ2UkQF4wMTJtMSRAXnB1YmxpY0RhdGFQaz0xNTAwMDgwMiRAXnB1YmxpY0RhdGFEZXRhaWxQaz11ZGRpOjY0ZWNjMDI2LWEyODItNDNkZi1iMGUxLWY1OTQxN2M2MDZjZV8yMDE4MDUxMTEwMDUkQF5vcHJ0aW5TZXFObz0yMDI2OCRAXm1haW5GbGFnPXRydWU=

낙찰된 목록 현황 물품조회
요청주소  http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch
서비스URL  http://apis.data.go.kr/1230000/ScsbidInfoService

*/
//$kwd1 = str_replace(' ','+',$kwd); // 할 필요없슴. spaces encoded as plus (+) signs
/*$kwd1 = explode(' ', $kwd);
//echo 'kwd='.$kwd1[0].count($kwd1);
$kwd2 = "";
    for ($i = 0; $i < count($kwd1); $i++) {
		$kwd2 .= urlencode($kwd1[$i]).'+';
		//echo $kwd2;
	}
$kwd2 = substr($kwd2,0,strlen($kwd2)-1);
echo $kwd2; */


//echo 'chkHrc='.$chkHrc.' chkBid='.$chkBid.'<br>';
//var_dump($data_object.response.body.items);

/*
폰트크기, 
<입찰정보>
항목: 공고번호, 공고명, 추정가격, 공고일시, 수요기관, 마감일시
상세링크(&개찰완료), 낙찰정보목록(과거목록: 키워드 & 수요기관)
*/
// ============================== 입찰정보 =====================================================
if ($chkBid == 'bid') { 

	ob_end_flush();
	flush();
//$kwd='';
	//$mobile = $g2bClass->MobileCheck();
$response1 = $g2bClass->getSvrData('bidthing',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 물품입찰
$response2 = $g2bClass->getSvrData('bidcnstwk',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 공사입찰
$response3 = $g2bClass->getSvrData('bidservc',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 용역입찰
//$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰
/*	unique column namme 
	용역입찰	용역구분명 srvceDivNm
	물품입찰	물품규격명 prdctSpecNm, 물품수량 prdctQty
	공사입찰	부대공종명1	subsiCnsttyNm1
	*/
//var_dump($response1);

$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
//echo '<br>'.'물품입찰<br>';
//var_dump($item1);
$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];
//echo '<br>'.'공사입찰<br>';
//var_dump($item2);
$json3 = json_decode($response3, true);
$item3 = $json3['response']['body']['items'];
//echo '<br>'.'용역입찰<br>';
//var_dump($item3);
/* $json4 = json_decode($response4, true);
$item4 = $json4['response']['body']['items']; */
//echo '<br>'.'외자입찰<br>';
//var_dump($item4);
//$item = array_merge($item2,$item3); //,$item4);
$item = array_merge($item1,$item2,$item3); //,$item4);
//var_dump($item);
?>
<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<!--link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" -->
<link rel="stylesheet" type="text/css" href="http://uloca.net/g2b/css/g2b.css" />
<link rel="stylesheet" href="http://uloca.net/jquery/jquery-ui.css">
<script src="http://uloca.net/jquery/jquery.min.js"></script>
<script src="http://uloca.net/jquery/jquery-ui.min.js"></script>
<script src="http://uloca.net/g2b/g2b.js"></script>
</head>

<body>
<div style='text-align:right'><a onclick='domCheck()'>dom</a></div>
<center><div style='font-size:18px;'><font color='blue'><strong>- 입찰 정보 -</strong></font></center>
<div id=totalrec>total record=<?=count($item)?></div>
<table class="type10" id="bidData">
    <tr>
        
		<th scope="cols" width="5%;" ><input type="checkbox" name='chk' onclick="javascript:CheckAll('chk')"></th>
		<th scope="cols" width="10%;">공고번호</th>
        <th scope="cols" width="25%;">공고명</th>
        <th scope="cols" width="10%;">추정가격</th>
        <th scope="cols" width="15%;">공고일시</th>
        <th scope="cols" width="20%;">수요기관</th>
		<th scope="cols" width="15%;">낙찰결과</th>
        <!-- col style="width:15%;" /><col style="width:auto;" / -->
		
    </tr>


<?
// 열 목록 얻기
/*
foreach ($item as $key => $row) {
    $bidClseDt[$key]  = $row['bidClseDt'];
} 
array_multisort($bidClseDt, SORT_DESC, $item); // 마김일시
*/

$i=0;
foreach($item as $arr ) { //foreach element in $arr
    $pss = $g2bClass->getDivNm($arr);
	if ($arr['presmptPrce']=="") $presmptPrce = "";
		else $presmptPrce = number_format($arr['presmptPrce']);
	if ($i % 2 == 0) {
		$tr = '<tr>';
		$tr .= '<td style="text-align: center;"><input id=chk name=chk type="checkbox" /></td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		
		$tr .= '<td title="'.$pss.'">'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td align=right>'.$presmptPrce.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceDt'].'</td>';
		$tr .= '<td><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['dminsttNm'].'</a></td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewRslt("'.$arr['bidNtceNo'].'","'.$arr['bidNtceOrd'].'","'.$arr['bidClseDt'].'","'.$pss.'")\'>'.$arr['bidClseDt'].'</a></td>';
		$tr .= '</tr>';
	} else {
		$tr = "<tr>";
		$tr .= '<td class="even" style="text-align: center;"><input id=chk name=chk type="checkbox" /></td>';
		$tr .= '<td class="even" style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		$tr .= '<td class="even" title="'.$pss.'">'.$arr['bidNtceNm'].'</td>';
		
		$tr .= '<td class="even" align=right>'.$presmptPrce.'</td>';
		$tr .= '<td class="even" style="text-align: center;">'.$arr['bidNtceDt'].'</td>';
		$tr .= '<td class="even"><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['dminsttNm'].'</a></td>';
		$tr .= '<td class="even" style="text-align: center;"><a onclick=\'viewRslt("'.$arr['bidNtceNo'].'","'.$arr['bidNtceOrd'].'","'.$arr['bidClseDt'].'","'.$pss.'")\'>'.$arr['bidClseDt'].'</a></td>';
		$tr .= '</tr>';
	}
	echo $tr;
	$i += 1;
}
echo '</table>';
?>
    
    </tbody>
</table>
<? 
	//ob_end_flush();
	//flush();
	} // end of bid ?>


<?
// ============================== 사전규격정보 =====================================================
if ($chkHrc == 'hrc') { 
	//$mobile = $g2bClass->MobileCheck();
	
	ob_end_flush();
	flush();
	
$response1 = $g2bClass->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 물품사전규격
$response2 = $g2bClass->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 공사사전규격
$response3 = $g2bClass->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 용역사전규격
//$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰

//var_dump($response1);

$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
//echo '<br>'.'물품사전규격<br>';
//var_dump($item1);

$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];
//echo '<br>'.'공사입찰<br>';
//var_dump($item2);
$json3 = json_decode($response3, true);
$item3 = $json3['response']['body']['items'];

//var_dump($item4);
$item = array_merge($item1,$item2,$item3); //,$item4);
//var_dump($item);
?>
<br><br><br>
<center><div style='font-size:18px;'><font color='blue'><strong>- 사전규격 정보 -</strong></font></center>
<div id=totalrechrc>total record=<?=count($item)?></div>
<table class="type10" id="specData">
    <tr>
        
		<th scope="cols" width="5%;"><input type="checkbox" onclick="javascript:CheckAll('chk2')"></th>
		<th scope="cols" width="10%;">등록번호</th>
        <th scope="cols" width="25%;">품명</th>
        <th scope="cols" width="15%;">예산금액</th>
        <th scope="cols" width="12%;">등록일시</th>
        <th scope="cols" width="20%;">수요기관</th>
        <th scope="cols" width="13%;">낙찰결과</th>
        <!-- col style="width:15%;" /><col style="width:auto;" / -->
		<!-- 등록번호, 품명, 예산금액, 등록일시, 수요기관, 마감일시
		https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo=605594 상세정보 -->
    </tr>


<?
// 열 목록 얻기
foreach ($item as $key => $row) {
    $opninRgstClseDt[$key]  = $row['opninRgstClseDt'];
} 
array_multisort($opninRgstClseDt, SORT_DESC, $item); // 등록일시


$i=0;
foreach($item as $arr ) { //foreach element in $arr
    //$uses = $item['var1']; //etc
	if ($i % 2 == 0) {
		$tr = '<tr>';
		$tr .= '<td scope="row" style="text-align: center;"><input id=chk2 name=chk2 type="checkbox" /></td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewDtls("'. $arr['bfSpecRgstNo'].'")\'>'.$arr['bfSpecRgstNo'].'</a></td>';
		$tr .= '<td>'.$arr['prdctClsfcNoNm'].'</td>';
		$tr .= '<td align=right>'.number_format($arr['asignBdgtAmt']).'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['rgstDt'].'</td>';
		
		$tr .= '<td><a onclick=\'viewscs("'.$arr['rlDminsttNm'].'")\'>'.$arr['rlDminsttNm'].'</a></td>';
		$tr .= '<td>'.$arr['opninRgstClseDt'].'</td>';
		$tr .= '</tr>';
	} else {
		$tr = "<tr>";
		$tr .= '<td class="even" style="text-align: center;"><input id=chk2 name=chk2 type="checkbox" /></td>';
		$tr .= '<td class="even" style="text-align: center;"><a onclick=\'viewDtls("'. $arr['bfSpecRgstNo'].'")\'>'.$arr['bfSpecRgstNo'].'</a></td>';
		$tr .= '<td class="even">'.$arr['prdctClsfcNoNm'].'</td>';
		$tr .= '<td class="even" align=right>'.number_format($arr['asignBdgtAmt']).'</td>';
		$tr .= '<td class="even" style="text-align: center;">'.$arr['rgstDt'].'</td>';
		$tr .= '<td class="even"><a onclick=\'viewscs("'.$arr['rlDminsttNm'].'")\'>'.$arr['rlDminsttNm'].'</a></td>';
		$tr .= '<td class="even">'.$arr['opninRgstClseDt'].'</td>';
		$tr .= '</tr>';
	}
	echo $tr;
	$i += 1;
}
echo '</table>';
?>
    
    </tbody>
</table>
<?
	} // end of hrc 
?>

</body>
</html>
<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
ob_start();
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

 $conn = $g2bClass->connDB('localhost','uloca22','w3m69p21!@','uloca22');
 $sql = 'select lastHist from autoPubHist where id = \''.$id.'\' and kwd = \''.$kwd.'\' and  and dminsttnm= \''.$dminsttnm.'\'';
 $result = $conn->query($sql);
 // 날자 계산 ----------------------------------------------
 if ($result->num_rows > 0) {
	 $endDate = new DateTime();
	 $startDate = $result["lastHist"];
 }
 else {$endDate = new DateTime(); //$today;
	$timestamp = strtotime("-1 months");
	$startDate = date("Y-m-d H:i:s", $timestamp);
 }
	$endDate = date("Y-m-d H:i:s", $endDate);
// 날자계산 끝 ------------------------------------------------------
// 검색종류------------------------------------------------------
if ($search == '1' || $searcg == '3') $chkBid = 'bid';
else if ($search == '2') $chkHrc = 'hrc';
// 알림------------------------------------------------------

if ($send == '1' || $searcg == '3' || $searcg == '7' ) $email = 1;
else if ($send == '2' || $searcg == '6' ) $katalk = 1;
else if ($send == '4' ) $cell = 1;
// 알림 끝 ------------------------------------------------------

if ($kwd == "") $kwd = ' ';

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
$item = array_merge($item1,$item2,$item3); //,$item4);
//var_dump($item);
$contentsbid='
<center><div style=\'font-size:18px;\'><font color=\'blue\'><strong>- 입찰 정보 -</strong></font></center>
<div id=totalrec>total record=<?=count($item)?></div>
<table class="type10" id="bidData">
    <tr>
		<th scope="cols" width="5%;" ><input type="checkbox"onclick="javascript:CheckAll(\'chk\')"></th>
		<th scope="cols" width="10%;">공고번호</th>
        <th scope="cols" width="25%;">공고명</th>
        <th scope="cols" width="10%;">추정가격</th>
        <th scope="cols" width="15%;">공고일시</th>
        <th scope="cols" width="20%;">수요기관</th>
		<th scope="cols" width="15%;">마감일시</th>
    </tr>
';

// 열 목록 얻기
foreach ($item as $key => $row) {
    $bidClseDt[$key]  = $row['bidClseDt'];
} 
array_multisort($bidClseDt, SORT_DESC, $item); // 마김일시


$i=0;
foreach($item as $arr ) { //foreach element in $arr
    //$uses = $item['var1']; //etc
	if ($i % 2 == 0) {
		$tr = '<tr>';
		$tr .= '<td scope="row" style="text-align: center;"><input id=chk name=chk type="checkbox" /></td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td align=right>'.number_format($arr['presmptPrce']).'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceDt'].'</td>';
		$tr .= '<td><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['dminsttNm'].'</a></td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidClseDt'].'</td>';
		$tr .= '</tr>';
		$contentsbid .= $tr;
	} else {
		$tr = "<tr>";
		$tr .= '<td scope="row" class="even" style="text-align: center;"><input id=chk name=chk type="checkbox" /></td>';
		$tr .= '<td scope="row" class="even" style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		$tr .= '<td scope="row" class="even">'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td scope="row" class="even" align=right>'.number_format($arr['presmptPrce']).'</td>';
		$tr .= '<td scope="row" class="even" style="text-align: center;">'.$arr['bidNtceDt'].'</td>';
		$tr .= '<td scope="row" class="even"><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['dminsttNm'].'</a></td>';
		$tr .= '<td scope="row" class="even" style="text-align: center;">'.$arr['bidClseDt'].'</td>';
		$tr .= '</tr>';
		$contentsbid .= $tr;
	}
	//echo $tr;
	$i += 1;
}
$contentsbid .= '</table>';
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
<? $contentshrc = 
'<center><div style='font-size:18px;'><font color='blue'><strong>- 사전규격 정보 -</strong></font></center>
<div id=totalrechrc>total record=<?=count($item)?></div>
<table class="type10" id="specData">
    <tr>
        
		<th scope="cols" width="5%;"><input type="checkbox" onclick="javascript:CheckAll('chk2')"></th>
		<th scope="cols" width="10%;">등록번호</th>
        <th scope="cols" width="25%;">품명</th>
        <th scope="cols" width="15%;">예산금액</th>
        <th scope="cols" width="12%;">등록일시</th>
        <th scope="cols" width="20%;">수요기관</th>
        <th scope="cols" width="13%;">마감일시</th>
        <!-- col style="width:15%;" /><col style="width:auto;" / -->
		<!-- 등록번호, 품명, 예산금액, 등록일시, 수요기관, 마감일시
		https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo=605594 상세정보 -->
    </tr>
'; ?>

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
		$tr .= '<td scope="row" class="even" style="text-align: center;"><input id=chk2 name=chk2 type="checkbox" /></td>';
		$tr .= '<td scope="row" class="even" style="text-align: center;"><a onclick=\'viewDtls("'. $arr['bfSpecRgstNo'].'")\'>'.$arr['bfSpecRgstNo'].'</a></td>';
		$tr .= '<td scope="row" class="even">'.$arr['prdctClsfcNoNm'].'</td>';
		$tr .= '<td scope="row" class="even" align=right>'.number_format($arr['asignBdgtAmt']).'</td>';
		$tr .= '<td scope="row" class="even" style="text-align: center;">'.$arr['rgstDt'].'</td>';
		$tr .= '<td scope="row" class="even"><a onclick=\'viewscs("'.$arr['rlDminsttNm'].'")\'>'.$arr['rlDminsttNm'].'</a></td>';
		$tr .= '<td scope="row" class="even">'.$arr['opninRgstClseDt'].'</td>';
		$tr .= '</tr>';
		
	}
	$contentshrc .= $tr;
	echo $tr;
	$i += 1;
}
$contentshrc .='</table>';
?>
    
    </tbody>
</table>
<?
} // end of hrc

header('X-XSS-Protection: 0');
$message = $contentsbid . $contentshrc;
$to  = $email2;
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$subject = 'uleca 에서 제공하는 입찰정보';
$headers .= 'From: 나라장터 정보 <uloca@uloca.net>' . "\r\n";
//echo 'subject='.$subject;
//echo 'message='.$message;
$msg2 = '<p>입찰 정보, 사전규격정보를 <font color=red>' . $to .'</font> 에게 보냈습니다.</p>';

mail($to, $subject, $message, $headers);
?>

</body>
</html>
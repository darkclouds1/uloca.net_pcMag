<?
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
?>
<!-- HEAD>
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
</HEAD>
<body -->
<style>

#list { clear:both; position:relative; width:100%; font-size:10px; color:#000000;  padding:4px 0 4px 4px;}
#list h1 { clear:both; position:relative; width:100%; border-top:1px solid #d7d7d7; }
#list ul { }
#list li { border-bottom:1px solid #d7d7d7; word-break:break-all; overflow:hidden; text-indent:-5px; }
#list li a { color:#000000; font-weight:normal; font-size:10px; padding: 4px 4px; display:block;text-decoration:none;}
#list li a.a1 { line-height:14px; font-weight:bold; }
#list li a.a2 { line-height:13px; font-weight:bold; }
#list li.pd18 a { padding: 4px 5px 4px 10px; }
#list li font.f1 { color:#767477; font-weight:normal; font-size:10px; line-height: 1em; }
#list li.op1 { padding:4px 0 14px 12px; word-break:break-all; overflow:hidden; }
#list li.op1 a { font-weight:bold; font-size:15px; line-height:22px; }
#list li.op2 { padding:7px 0 7px 12px; word-break:break-all; overflow:hidden; }
#list li.op2 a { font-weight:normal; font-size:13px; }
#list li.nothing { border-bottom:1px solid #d7d7d7; padding:4px 0 4px 4px; word-break:break-all; overflow:hidden; text-align:center; }

/* list 
#list { clear:both; position:relative; width:100%; font-size:10px; color:#000000; }
#list h1 { clear:both; position:relative; width:100%; border-top:1px solid #d7d7d7; }
#list ul { }
#list li { border-bottom:1px solid #d7d7d7; word-break:break-all; overflow:hidden; text-indent:-7px; }
#list li a { color:#000000; font-weight:normal; font-size:10px; padding: 11px 15px; display:block;text-decoration:none;}
#list li a.a1 { line-height:22px; font-weight:bold; }
#list li a.a2 { line-height:13px; font-weight:bold; }
#list li.pd18 a { padding: 18px 5px 18px 10px; }
#list li font.f1 { color:#767477; font-weight:normal; font-size:10px; line-height: 1em; }
#list li.op1 { padding:14px 0 14px 12px; word-break:break-all; overflow:hidden; }
#list li.op1 a { font-weight:bold; font-size:15px; line-height:22px; }
#list li.op2 { padding:7px 0 7px 12px; word-break:break-all; overflow:hidden; }
#list li.op2 a { font-weight:normal; font-size:13px; }
#list li.nothing { border-bottom:1px solid #d7d7d7; padding:27px 0 30px 19px; word-break:break-all; overflow:hidden; text-align:center; }
*/
</style>
<?
session_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;

if ($endDate == "") {
	$endDate = date("Y-m-d"); //$today;
	$timestamp = strtotime("-1 months");
	$startDate = date("Y-m-d", $timestamp);
} 
if ($chkBid == '' && $chkHrc == '') $chkBid = 'bid';

$startDate = str_replace('-','',$startDate);
$endDate = str_replace('-','',$endDate);
//echo ('<br>startDate='.$startDate.'<br> kwd='.$kwd);
if ($kwd == "") exit;

// ============================== 입찰정보 물품=====================================================

	ob_end_flush();
	flush();
if ($bidthing == '1') { 
//$kwd='';
	//$mobile = $g2bClass->MobileCheck();
//$response1 = $g2bClass->getSvrData('bidthing',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 물품입찰
$g2bClass->viewTable_m('물품',$startDate,$endDate,$kwd,$dminsttNm,'8000','1','1');
}
if ($bidcnstwk == '1') {
$g2bClass->viewTable_m('공사',$startDate,$endDate,$kwd,$dminsttNm,'8000','1','1');
}
if ($bidservc == '1') {
$g2bClass->viewTable_m('용역',$startDate,$endDate,$kwd,$dminsttNm,'8000','1','1');
}
//$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰
/*	unique column namme 
	용역입찰	용역구분명 srvceDivNm
	물품입찰	물품규격명 prdctSpecNm, 물품수량 prdctQty
	공사입찰	부대공종명1	subsiCnsttyNm1
	*/
//var_dump($response1);
if (strpos($response1,'Temporary Redirect')) {
	echo '<p style="font-weight: bold; color: rgb(255,0,0);">Server 일시 중단 상태 입니다.</p>';
	var_dump($response1);
	exit;
}

/*
$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
//echo '<br>'.'물품입찰<br>';
//var_dump($json1);
$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];
//echo '<br>'.'공사입찰<br>';
//var_dump($json2);
$json3 = json_decode($response3, true);
$item3 = $json3['response']['body']['items'];
//echo '<br>'.'용역입찰<br>';
//var_dump($json3);
/* $json4 = json_decode($response4, true);
$item4 = $json4['response']['body']['items']; * /
//echo '<br>'.'외자입찰<br>';
//var_dump($item4);
$item = array_merge($item1,$item2,$item3); //,$item4);
//var_dump($item);
?>
<center><div style='font-size:18px;'><font color='blue'><strong>- 입찰 정보 -</strong></font></center>
<div id=totalrec>total record=<?=count($item)?></div>
<table class="type10" id="bidData">
    <tr>
        
		<th scope="cols" width="5%;" ><input type="checkbox"onclick="javascript:CheckAll('chk')"></th>
		<th scope="cols" width="10%;">공고번호</th>
        <th scope="cols" width="25%;">공고명</th>
        <th scope="cols" width="10%;">추정가격</th>
        <th scope="cols" width="15%;">공고일시</th>
        <th scope="cols" width="20%;">수요기관</th>
		<th scope="cols" width="15%;">마감일시</th>
        <!-- col style="width:15%;" /><col style="width:auto;" / -->
		
    </tr>


<?
// 열 목록 얻기
foreach ($item as $key => $row) {
    $bidClseDt[$key]  = $row['bidClseDt'];
} 
array_multisort($bidClseDt, SORT_DESC, $item); // 마김일시


$i=0;
foreach($item as $arr ) { //foreach element in $arr
    $pss = $g2bClass->getDivNm($arr);
	if ($arr['presmptPrce']=="") $presmptPrce = "";
		else $presmptPrce = number_format($arr['presmptPrce']);
	$tr = '<tr>';
		$tr .= '<td style="text-align: center;"><input id=chk name=chk type="checkbox" /></td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		
		$tr .= '<td title="'.$pss.'">'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td align=right>'.$presmptPrce.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceDt'].'</td>';
		$tr .= '<td><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['dminsttNm'].'</a></td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewRslt("'.$arr['bidNtceNo'].'","'.$arr['bidNtceOrd'].'","'.$arr['bidClseDt'].'","'.$pss.'")\'>'.$arr['bidClseDt'].'</a></td>';
		$tr .= '</tr>';
	
	echo $tr;
	$i += 1;
}
echo '</table>'; */
?>
    
    <!-- /tbody>
</table -->
<? 
	//ob_end_flush();
	//flush();
//	} // end of bid ?>


<?
// ============================== 사전규격정보 =====================================================
if ($chkHrc == 'hrc') { 
	//$mobile = $g2bClass->MobileCheck();
	
	ob_end_flush();
	flush();
	
$response1 = $g2bClass->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,'1000','1','1'); // 물품사전규격
$response2 = $g2bClass->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,'1000','1','1'); // 공사사전규격
$response3 = $g2bClass->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,'1000','1','1'); // 용역사전규격
//$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰
if (strpos($response1,'Temporary Redirect')) {
	echo '<p style="font-weight: bold; color: rgb(255,0,0);">Server 일시 중단 상태 입니다.</p>';
	exit;
}
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
<center><div style='font-size:18px; color:blue;font-weight:bold'>- 사전규격 정보 -</div></center>
<div id=totalrechrc>total record=<?=count($item)?></div>
<table class="type10" id="specData">
    <tr>
        
		<th scope="cols" width="5%;"><input type="checkbox" onclick="javascript:CheckAll('chk2')"></th>
		<th scope="cols" width="10%;">등록번호</th>
        <th scope="cols" width="25%;">품명</th>
        <th scope="cols" width="15%;">예산금액</th>
        <th scope="cols" width="12%;">등록일시</th>
        <th scope="cols" width="20%;">수요기관</th>
        <th scope="cols" width="13%;">마감일</th>
        <!-- col style="width:15%;" /><col style="width:auto;" / -->
		<!-- 등록번호, 품명, 예산금액, 등록일시, 수요기관, 마감일시
		https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo=605594 상세정보 -->
    </tr>


<?
if (count($item) >0 )  {
// 열 목록 얻기
foreach ($item as $key => $row) {
    $opninRgstClseDt[$key]  = $row['opninRgstClseDt'];
} 
array_multisort($opninRgstClseDt, SORT_DESC, $item); // 등록일시


$i=0;
foreach($item as $arr ) { //foreach element in $arr
    //$uses = $item['var1']; //etc
	$tr = '<tr>';
		$tr .= '<td scope="row" style="text-align: center;"><input id=chk2 name=chk2 type="checkbox" /></td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewDtls("'. $arr['bfSpecRgstNo'].'")\'>'.$arr['bfSpecRgstNo'].'</a></td>';
		$tr .= '<td>'.$arr['prdctClsfcNoNm'].'</td>';
		$tr .= '<td align=right>'.number_format($arr['asignBdgtAmt']).'</td>';
		$tr .= '<td style="text-align: center;">'.substr($arr['rgstDt'],0,10).'</td>';
		
		$tr .= '<td><a onclick=\'viewscs("'.$arr['rlDminsttNm'].'")\'>'.$arr['rlDminsttNm'].'</a></td>';
		$tr .= '<td style="text-align: center;">'.substr($arr['opninRgstClseDt'],0,10).'</td>';
		$tr .= '</tr>';
	
	echo $tr;
	$i += 1;
}
echo '</table>';
}
?>
    
    <!-- /tbody>
</table -->
<?
	} // end of hrc 
?>






<!-- div id="list">
		<ul>
			
			
				
					<li>
						<a class="a1" href="/ep/invitation/publish/bidInfoDtl.do?bidno=20180906519&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1 ">김천의료원 노후설비 교체사업 노후 GHP 교체<br /><font class="f1">경상북도김천의료원&#32;|&#32;경상북도김천의료원<br />공고 : 20180906519-00&#32;|&#32;마감 : 2018/09/17 12:00</font> </a>
					</li>
				
					<li>
						<a class="a1" href="/ep/invitation/publish/bidInfoDtl.do?bidno=20180905434&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1 ">2019학년도 김천여자고등학교 교복(동복,하복,생활복,가디건) 단가구매 업체 선정 입찰 공고<br /><font class="f1">경상북도교육청 김천여자고등학교&#32;|&#32;경상북도교육청 김천여자고등학교<br />공고 : 20180905434-00&#32;|&#32;마감 : 2018/09/17 16:00</font> </a>
					</li>
				
					<li>
						<a class="a1" href="/ep/invitation/publish/bidInfoDtl.do?bidno=20180903077&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1 ">김천시 강남북 연결도로 개설공사(총괄 및 1차분)-SEB거더<br /><font class="f1">조달청 대구지방조달청&#32;|&#32;경상북도 김천시<br />공고 : 20180903077-00&#32;|&#32;마감 : 2018/09/13 12:00</font> </a>
					</li>
				
					<li>
						<a class="a1" href="/ep/invitation/publish/bidInfoDtl.do?bidno=20180815476&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1 ">김천시 강남북 연결도로 개설공사(총괄 및 1차분)-교량점검시설<br /><font class="f1">조달청 대구지방조달청&#32;|&#32;경상북도 김천시<br />공고 : 20180815476-00&#32;|&#32;마감 : 2018/09/10 15:00</font> </a>
					</li>
				
					<li>
						<a class="a1" href="/ep/tbid/selectProdBid.do?tbidno=20180821144&amp;bidseq=00">김천삼락 행복주택 태양광 발전설비 납품·설치<br /><font class="f1">한국토지주택공사&#32;|&#32;한국토지주택공사<br />공고 : 1802691-00&#32;|&#32;마감 : 2018/08/28 10:00</font> </a>
					</li>
				
					<li>
						<a class="a1" href="/ep/invitation/publish/bidInfoDtl.do?bidno=20180816560&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1 ">김천생명과학고 축산식품가공실 및 조리실 리모델링에 따른 도막형바닥재 구매설치<br /><font class="f1">경상북도교육청 김천생명과학고등학교&#32;|&#32;경상북도교육청 김천생명과학고등학교<br />공고 : 20180816560-00&#32;|&#32;마감 : 2018/08/24 10:00</font> </a>
					</li>
				
			
		</ul>
	</div -->

</body>
</html>
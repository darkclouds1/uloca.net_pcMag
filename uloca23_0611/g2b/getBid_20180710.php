<?
/*
낙찰정보 샘플
낙찰정보(수요기관 링크)==>입찰정보(키워드&수요기관) 목록으로	2018/07/10
*/
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
 ob_start();
require($_SERVER['DOCUMENT_ROOT'].'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
//$kwd = ''; // 검색어 없앰........... 2018/7/9

/*  2018/07/10 입찰정보로 수정 */
$response1 = $g2bClass->getSvrData('scsbidthing',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'100','1','1'); // 물품낙찰
$response2 = $g2bClass->getSvrData('scsbidcnstwk',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'100','1','1'); // 공사낙찰
$response3 = $g2bClass->getSvrData('scsbidservc',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'100','1','1'); // 용역낙찰
/*
$response1 = $g2bClass->getSvrData('bidthing',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'100','1','1'); // 물품입찰
$response2 = $g2bClass->getSvrData('bidcnstwk',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'100','1','1'); // 공사입찰
$response3 = $g2bClass->getSvrData('bidservc',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'100','1','1'); // 용역입찰
*/
//$response4 = $g2bClass->getSvrData('scsbidfrgcpt',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'100','1','1'); // 외자입찰
$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
//echo '<br>'.'물품입찰<br>';
//var_dump($response1);
$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];
//echo '<br>'.'공사입찰<br>';
//var_dump($item2);
$json3 = json_decode($response3, true);
$item3 = $json3['response']['body']['items'];
//echo '<br>'.'용역입찰<br>';
//var_dump($item3);
/*$json4 = json_decode($response4, true);
$item4 = $json4['response']['body']['items']; */
//echo '<br>'.'외자입찰<br>';
//var_dump($item4);
$item = array_merge($item1,$item2,$item3); //,$item4);


//var_dump($response);
?>

<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" -->
<link rel="stylesheet" type="text/css" href="css/g2b.css" />
<link rel="stylesheet" href="http://uloca.net/jquery/jquery-ui.css">
<script src="http://uloca.net/jquery/jquery.min.js"></script>
<script src="http://uloca.net/jquery/jquery-ui.min.js"></script>
<script src="http://uloca.net/g2b/g2b.js"></script>
</head>

<body>
<br>
<center><div style='font-size:14px;'>수요기관 : <font color='red'><strong>'<?=$dminsttNm?>'</strong></font>&nbsp;&nbsp; 검색어 : <font color='red'><strong>'<?=$kwd?>'</strong></font> &nbsp;&nbsp;&nbsp;&nbsp;최근 12개월간의 입찰정보</div></center>
<center>
<div id='loading' style='display: inline;'>
  <img src='http://uloca.net/g2b/loading.gif' width='100px' height='100px'>
</div></center>
<?
echo str_pad(" " , 256); 
ob_end_flush();
flush();
?>
<div id=totalrec></div>

<table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="5%;">번호</th>
		<th scope="cols" width="15%;">공고번호</th>
        <th scope="cols" width="30%;">공고명</th>
        <th scope="cols" width="10%;">최종낙찰금액</th>
        <th scope="cols" width="15%;">낙찰일자</th>
        <th scope="cols" width="25%;">낙찰업체</th>

    </tr>

<?


// 열 목록 얻기
foreach ($item as $key => $row) {
    $fnlSucsfDate[$key]  = $row['fnlSucsfDate'];
} 
array_multisort($fnlSucsfDate, SORT_DESC, $item); // 마김일시


$i=0;
foreach($item as $arr ) { //foreach element in $arr
    //$uses = $item['var1']; //etc
	$k = $i+1;
	if ($i % 2 == 0) {
		$tr = '<tr>';
		$tr .= '<td align=center>'.$k.'</td>';
		$tr .= '<td><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td align=right>'.number_format($arr['sucsfbidAmt']).'</td>';
		$tr .= '<td>'.$arr['fnlSucsfDate'].'</td>';
		$tr .= '<td><a onclick=\'viewscs("'.$arr['bidwinnrNm'].'")\'>'.$arr['bidwinnrNm'].'</a></td>';
		
		$tr .= '</tr>';
	} else {
		$tr = "<tr>";
		$tr .= '<td scope="row" class="even" align=center>'.$k.'</td>';
		$tr .= '<td scope="row" class="even"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		$tr .= '<td scope="row" class="even">'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td scope="row" class="even" align=right>'.number_format($arr['sucsfbidAmt']).'</td>';
		$tr .= '<td scope="row" class="even">'.$arr['fnlSucsfDate'].'</td>';
		$tr .= '<td scope="row" class="even"><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['bidwinnrNm'].'</a></td>';
		
		$tr .= '</tr>';
	}
	echo $tr;
	$i += 1;
}
echo '</table>';
?>

<script>
document.getElementById('totalrec').innerHTML = 'total record='+<?=count($item)?>; 
document.getElementById('loading').style.display = 'none'; //"none";= inline
</script>
</body>
</html>
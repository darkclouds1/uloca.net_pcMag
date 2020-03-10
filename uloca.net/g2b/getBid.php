<?
/*

입찰정보(수요기관 링크)==>입찰정보(키워드&수요기관) 목록으로	2018/07/10
2018-07-10	1년간 키워드&수요기관 검색 api
2018-11-30	2016년 부터 키워드&수요기관 검색 db openBidInfo 키워드 kwd=bidNtceNm 수요기관=dminsttNm
*/
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
session_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();
// --------------------------------- log
$rmrk = '';
$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log

function rs2Json11($stmt, $colArray) {
	$json_string = '{"response": { "header": { "resultCode": "00", "resultMsg":"정상"}, "body": { "items": [';
	$rowCount = $stmt->num_rows;
	$colcnt = count($colArray);
	$i = 1;
	while ($row = $g2bClass->fetchRowAssoc($stmt, $colArray)) { //while ($row = $result->fetch_assoc()) {
		//echo $i;
		$json_string .= '{ ';
		foreach ($row as $key => $value) {
			$json_string .= '"' . $key . '": "' .$value. '", ';
			
		}
		$json_string = substr($json_string,0,strlen($json_string)-2);
		if ($i > $rowCount-1) $json_string .= '}';
			else $json_string .= '},';
		$i ++;
	}
	$json_string .= '], "numOfRows": '.$rowCount. ', "pageNo": 1, "totalCount": '.$rowCount . '}} }'; // $json1['response']['body']['totalCount']. '}} }';
	return $json_string;

	//$i += 1;

}

//$kwd = ''; // 검색어 없앰........... 2018/7/9 다시 살림 11/28
$bidrdo = '';
$pss = ""; // 3개 한꺼번에
$sYear = '';
$LikeOrEqual = 'equal';
$startNo = 0;
$noOfRow = 1000;
$result = $dbConn->getSvrDataDB2_mysqli($conn,$bidrdo,$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
//$rowCount = $stmt->num_rows;
//	$fields = $g2bClass->bindAll($stmt);
//	$json_string = rs2Json11($stmt, $fields);
	
//	echo ($json_string);
/*$response1 = $g2bClass->getSvrData('bidthing',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'300','1','1'); // 물품입찰
$response2 = $g2bClass->getSvrData('bidcnstwk',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'300','1','1'); // 공사입찰
$response3 = $g2bClass->getSvrData('bidservc',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'300','1','1'); // 용역입찰
//$response4 = $g2bClass->getSvrData('scsbidfrgcpt',$inqryBgnDt,$inqryEndDt,$kwd,$dminsttNm,'100','1','1'); // 외자입찰

$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
//echo '<br>'.'물품입찰<br>';
//var_dump($response1);
$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];

$json3 = json_decode($response3, true);
*/
//var_dump($json_string);
//$item = $json_string['response']['body']['items'];

//$item = array_merge($item1,$item2,$item3); //,$item4);


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
<script src="/g2b/g2b.js"></script>
</head>

<body>
<br>
<center><div style='font-size:14px;'>수요기관 : <font color='red'><strong>'<?=$dminsttNm?>'</strong></font>&nbsp;&nbsp;
검색어 : <font color='red'><strong>'<?=$kwd?>'</strong></font> 
</div></center>
<center>
<div id='loading' style='display: inline;'>
  <img src='http://uloca.net/g2b/loading.gif' width='100px' height='100px'>
</div></center>

<div id=totalrec></div>

<table class="type10" id="bidData">
<thead>
    <tr>
        <th scope="cols" width="5%;">번호</th>
		<th scope="cols" width="15%;">공고번호</th>
        <th scope="cols" width="30%;">공고명</th>
        <th scope="cols" width="10%;">추정가격</th>
        <th scope="cols" width="8%;">공고일시</th>
        <th scope="cols" width="8%;">마감일시</th>
		<th scope="cols" width="14%;">낙찰기업</th>

    </tr>
</thead>
</tbody>
<?

// 열 목록 얻기
/* foreach ($item as $key => $row) {
    $bidClseDt[$key]  = $row['bidClseDt'];
} 
if (count($item)>1) array_multisort($bidClseDt, SORT_DESC, $item); // 마김일시
*/
$i=0;
//foreach($item as $arr ) { //foreach element in $arr
while($arr = $result->fetch_assoc()) {
    //$uses = $item['var1']; //etc
	$k = $i+1;
	 	
	$tr = '<tr>';
		//$tr .= '<td scope="row"><input id=chk name=chk type="checkbox" /></td>';
		$tr .= '<td style="text-align: center;">'.$k.'</td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
				
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		if ($arr['presmptPrce'] == '') $tr .= '<td align=right></td>';
		else $tr .= '<td align=right>'.number_format($arr['presmptPrce']).'</td>';
		$tr .= '<td style="text-align: center;">'.substr($arr['bidNtceDt'],0,10).'</td>';
		$tr .= '<td style="text-align: center;">'.substr($arr['bidClseDt'],0,10).'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidwinnrNm'].'</td>';
		$tr .= '</tr>';
	/*
	if ($i % 2 == 0) {
		$tr = '<tr>';
		$tr .= '<td scope="row"><input id=chk name=chk type="checkbox" /></td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td align=right>'.number_format($arr['presmptPrce']).'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceDt'].'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidClseDt'].'</td>';
		$tr .= '</tr>';
	} else {
		$tr = "<tr>";
		$tr .= '<td scope="row" class="even"><input id=chk name=chk type="checkbox" /></td>';
		$tr .= '<td scope="row" class="even" style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		$tr .= '<td scope="row" class="even">'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td scope="row" class="even" align=right>'.number_format($arr['presmptPrce']).'</td>';
		$tr .= '<td scope="row" class="even" style="text-align: center;">'.$arr['bidNtceDt'].'</td>';
		$tr .= '<td scope="row" class="even" style="text-align: center;">'.$arr['bidClseDt'].'</td>';
		$tr .= '</tr>';
	} */
	echo $tr;
	$i += 1;
}
echo '</tbody></table>';
?>

<script>
document.getElementById('totalrec').innerHTML = 'total record='+<?=$i?>; 
document.getElementById('loading').style.display = 'none'; //"none";= inline
</script>
</body>
</html>
<?
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck();

function getRsltDataAll($g2bClass,$bidNtceNo,$bidNtceOrd) {
	$noRow = 999;
	$pageNo=1;
	$response = $g2bClass->getRsltDataNo($bidNtceNo,$bidNtceOrd,$noRow);
	//var_dump($response);
	$json1 = json_decode($response, true);
	$totCnt = $json1['response']['body']['totalCount'];
	echo '<br>'.' totcnt='.$totCnt;
	$totCnt1 = $totCnt;
	$cnt = $totCnt - $noRow;
	$item = $json1['response']['body']['items'];
	echo '<br>'.'cnt='.$cnt.' totcnt1='.$totCnt1;
	
	while ($cnt > 0) {
		$pageNo++;
		$response1 = $g2bClass->getRsltDataNo($bidNtceNo,$bidNtceOrd,$noRow,$pageNo);
		$json2 = json_decode($response1, true);
		$item2 = $json2['response']['body']['items'];
		//$response = $response.concat($response1);
		$item = array_merge($item,$item2);
		$totCnt1 = count($json1['response']['body']['items']);
		$cnt = $cnt - $totCnt1;
		echo '<br>'.'cnt='.$cnt.' totcnt1='.$totCnt1.' item='.count($item).' pageNo='.$pageNo;
	}
	//echo 'item='.count($item).'<br><br><br><br>';
	return $item;
}
/*$response3 = $g2bClass->getSvrData('bidservc','20180718','20180718','부산','','100','1','1'); // 용역입찰
$json3 = json_decode($response3, true);
$item3 = $json3['response']['body']['items']; */
$bidNtceNo = '20180627179'; //'20180420631';//'20180421726' ; //'20180509427'; //'20170927549';
$bidNtceOrd = '00';
$kwd = '';
$dminsttNm = '';
$numOfRows = '999';
$pss = '물품';
$startDate = '201812050000';
$endDate = '201812052309';
//$response1 = $g2bClass->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1');
//$response1 = $g2bClass->getRsltData($bidNtceNo,$bidNtceOrd);
$totalCnt = $g2bClass->getRsltDataTotalCount($bidNtceNo,$bidNtceOrd) ;
echo ' totalCnt='.$totalCnt.'<br>';
$response1 = getRsltDataAll($g2bClass,$bidNtceNo,$bidNtceOrd);
//getBidRslt2($numOfRows,$pageNo,$inqryDiv,$inqryBgnDt,$inqryEndDt,$pss,$bidNtceNo);
//$json1 = json_decode($response1, true);
//$item1 = $json1['response']['body']['items'];

//$response2 = $g2bClass->getRsltDataNo($bidNtceNo,$bidNtceOrd,8000);

//var_dump($item0);
var_dump($response1);
/*foreach($item3 as $arr ) {
	$pss = $g2bClass->getDivNm($arr);
	echo $pss;
	queryParams=?numOfRows=999&pageNo=&bidNtceNo=20180509427&inqryDiv=3&type=json
	             numOfRows=999&pageNo=1&bidNtceNo=20180509427&inqryDiv=3&type=json
}*/
//echo $mobile;
?>
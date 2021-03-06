<?php
/*
 Plugin Name: 사용자 사용현황 : u_svc_status
 Plugin URI: http://uloca.net/ulocawp/?page_id=802
 Description: 워드프레스 사용자 사용현황.
 Version: 1.0
 Author: Monolith
 Author URI: /ulocawp/?page_id=802
 */

function u_svc_status_ShortCode() {

	date_default_timezone_set('Asia/Seoul');
	require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
	
	$g2bClass = new g2bClass;
	$uloca_live_test = $g2bClass->getSystem('1');
	$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

	//echo $mobile;
	$current_user = wp_get_current_user();
	$userid = $current_user->user_login;
	$_SESSION['current_user'] = $current_user;
	require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
	$dbConn = new dbConn;
	$conn = $dbConn->conn();
	// --------------------------------- log
	$rmrk = '사용자 사용현황'.$_SERVER ['HTTP_USER_AGENT'];
	$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
	// --------------------------------- log
	$sql = "select PayFreeCD from PayUser01M where user_login='".$userid."'";
	//echo $sql;
	$result2=$conn->query($sql);
	$PayFreeCD = '01';	// 01:미결제회원은 : 결제한번도 안하거나, 결제했어도 기간이 지난 경우 입니다.
	if ($row = $result2->fetch_assoc() )  $PayFreeCD = $row['PayFreeCD'];
	$today = date('Y-m-d');
	if ($PayFreeCD != '99') {
		$sql = "select payTypeCD,payDate,payAmt,payBank,payCard,payDuration,svrstrDT,svrendDT,svrserCnt from PayUser02H where PayUser01M_user_login='".$userid."' and svrstrDT <='".$today."' and svrendDT>='".$today."' "; ;
		$sql .= " order by payDate desc ";
		//echo $sql;
		$result=$conn->query($sql);

		if ($row = $result->fetch_assoc() ) {
			$svrstrDT = $row['svrstrDT'];
			
		} $svrstrDT = date("Y-m-d");

		$thismonth = date('m');
		$sdt = substr($svrstrDT,0,4)."-".$thismonth."-".substr($svrstrDT,8,2);
		$timestamp = strtotime($sdt);
		$fromdt = date("Y-m-d",$timestamp);
		$timestamp = strtotime($fromdt." +1 months");
		$todt = date("Y-m-d",$timestamp);
		
		echo 'fromdt='.$fromdt.' todt='.$todt;

		
		$cnt = $dbConn->countLogdt($conn,$userid,$fromdt,$todt);
		
	} else $cnt = '무제한';

	//exit;


?>
<!DOCTYPE html>
<html>
<head>
<title>사용자 사용현황</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="google-site-verification" content="l7HqV1KZeDYUI-2Ukg3U5c7FaCMRNe3scGPRZYjB_jM" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?version=20190102" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css"/>
<link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css"/>
<script src="/dhtml/codebase/dhtmlx.js"></script>
<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script src="/include/JavaScript/tableSort.js"></script>
<script src="/g2b/g2b.js?version=20190103"></script>
<style>
span { float: right;}
</style>
<script>
function goQna() {
	location.href='/ulocawp/?page_id=795';// live=1077'; // test = 795';
}
</script>
</head>

<body>
<center>
<div style='font-size:14px; color:blue;font-weight:bold'>< <?=$userid?> 사용현황 > 
<? if ($PayFreeCD == '99') { ?>
<font color=red>페이프리 회원</font>
<? } ?>
</center>
<div id=totalrechrc>total record=<?=$result->num_rows?><span style='text-align:right; font-size: 12px; font-weight: bold;'>정기결제 및 결제취소 문의는 메뉴>My유로카><a href='#' onclick='goQna(); return fallse;'>문의하기</a> 에서 글을 남겨주세요</span></div>

<table class="type10" id="specData" width="100%">
<thead>
    <tr>
        <th width="5%;">순위</th>
		<th width="10%;">결재일자</th>
        <th width="10%;">결재금액</th>
        <th width="15%;">결제형태</th>
        <th width="10%;">사용개월</th>
        <th width="15%;">서비스시작일</th>
		<th width="15%;">서비스종료일</th>
		<th width="10%;">검색횟수</th>
		<th width="10%;">취소일시</th>
    </tr>
</thead>
<tbody>

<?
//if ($PayFreeCD != '99') {
	$sql = "select payTypeCD,payDate,payAmt,payBank,payCard,payDuration,svrstrDT,svrendDT,svrserCnt from PayUser02H where PayUser01M_user_login='".$userid."'";
		$sql .= " order by payDate desc ";
		//echo $sql;
		$result=$conn->query($sql);
$i=1;
	while ($row = $result->fetch_assoc() ) {
		//if ($i==1) {
			$payTypeCD = $row['payTypeCD'];
			if ($payTypeCD == '01') $paytype = '1달사용';
			else if ($payTypeCD == '02') $paytype = '<font color=red>정기결제</font>';
			else if ($payTypeCD == '03') $paytype = '기간선택';
		//}
		$cancelDT = substr($row['cancelDT'],0,10);
		if ($cancelDT == '0000-00-00') $cancelDT = '';
		$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;">'.substr($row['payDate'],0,10).'</td>';
		$tr .= '<td style="text-align: right;">'.number_format($row['payAmt']).'</td>';
		//$tr .= '<td style="text-align: center;">'.$row['payBank'].'</td>';
		$tr .= '<td style="text-align: center;">'.$paytype.'</td>';
		$tr .= '<td align=center>'.$row['payDuration'].'</td>';
		$tr .= '<td align=center>'.substr($row['svrstrDT'],0,10).'</td>';
		$tr .= '<td align=center>'.substr($row['svrendDT'],0,10).'</td>';
		$tr .= '<td align=right>'.number_format($row['svrserCnt']).'</td>';
		$tr .= '<td align=center>'.$cancelDT.'</td>';
		$tr .= '</tr>';
		echo $tr;
		$i++;
	}

 if ($i == 1) {
	$tr = '<tr>';
		$tr .= '<td style="text-align: center;">1</td>';
		$tr .= '<td style="text-align: center;color:red;" colspan=8>데이타가 없습니다.</td>';

		$tr .= '</tr>';
		echo $tr;
} 
?>
</tbody>
</table>
</body>
</html>
<?
}
add_shortcode('u_svc_status','u_svc_status_ShortCode');

?>
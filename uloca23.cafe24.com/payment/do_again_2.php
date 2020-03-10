<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
	$dbConn = new dbConn;
	$conn = $dbConn->conn();
$sql = "select payTypeCD,customer_uid from PayUser02H where PayUser01M_user_login ='".$userid."' order by idx desc limit 0,1 ";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$customer_uid = $row['customer_uid'];
	if ($row['payTypeCD'] != '02') {
		echo '정기결제 사용자가 아닙니다.';
		exit;
	}
	$payTypeCD = '02'
}
	$strDT = date("YmdHis");
	$url = "https://api.iamport.kr/subscribe/payments/again";
	$parm = 'customer_uid='.$customer_uid;
	$parm .= '&merchant_uid='='uloca_'.$userid.'_'.$strDT;
	$parm .= '&amount='.$amount;
	$parm .= '&name='.'정기결제';
	$parm .='&paytype=02';					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
	$parm .='&paytypes=10';



/*		parm = 'imp_uid='+rsp.imp_uid;				// 아임포트 거래 고유 번호
		parm +='&merchant_uid='+rsp.merchant_uid;	//가맹점에서 생성/관리하는 고유 주문번호
		parm +='&amount='+rsp.paid_amount;			// 결제금액
		parm +='&name='+rsp.name;					// 주문명 할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
		parm +='&apply_num='+rsp.apply_num;			// 카드 승인번호
		parm +='&userid='+rsp.buyer_name;			// 주문자 이름
		parm +='&paytype='+paytype;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
*/
$dur = 1;

$sql = "select svrstrDT,svrendDT from PayUser02H where PayUser01M_user_login ='".$userid."' order by idx desc limit 0,1 ";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$svrstrDT = $row['svrstrDT'];
	$svrendDT = $row['svrendDT'];
	$strDT = $svrendDT;
	$timestamp = strtotime($strDT." +1 days");
	$strDT = date("Y-m-d", $timestamp);

} else {
	$strDT = date("Y-m-d"); // today
}
$timestamp = strtotime($strDT." $dur months");
$endDT = date("Y-m-d", $timestamp);
$timestamp = strtotime($endDT." -1 days");
$endDT = date("Y-m-d", $timestamp);

$sql = 'insert into PayUser02H (PayUser01M_user_login,payTypeCD,payDcCD,payAmt,payDate,payDuration,imp_uid,apply_num,merchant_uid,customer_uid,svrstrDT,svrendDT) ';
$sql .= " values ('".$userid."', '".$paytype."', '".$paytypes."', '".$amount."', now(), '".$dur."','".$imp_uid."', '".$apply_num."', '".$merchant_uid."', '".$customer_uid."', '".$strDT."', '".$endDT."') ";
$conn->query($sql);

//echo $sql;
echo 'ok';
?>

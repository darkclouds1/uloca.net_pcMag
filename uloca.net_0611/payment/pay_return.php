<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
	$dbConn = new dbConn;
	$conn = $dbConn->conn();

/* parm = 'imp_uid='+rspg.imp_uid;				// 아임포트 거래 고유 번호
		parm +='&merchant_uid='+rspg.merchant_uid;	//가맹점에서 생성/관리하는 고유 주문번호
		parm +='&amount='+amt;			// 결제금액
		parm +='&name='+rspg.name;					// 주문명 할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
		parm +='&apply_num='+rspg.apply_num;			// 카드 승인번호
		parm +='&userid='+userid; //rspg.buyer_name;			// 주문자 이름
		parm +='&paytype='+paytype;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&paytypes='+paytypes;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&customer_uid='+customer_uid;
		*/
//echo 'pay_return.php<br>';
//echo json_encode($data);
//echo 'imp_uid='.$data['imp_uid'];
if ($paytype == '01' || $paytype == '02') $dur = 1;
else $dur = $name;
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
if ($paytype == '03' && ($paytypes == '3' || $paytypes == '6' || $paytypes == '9' || $paytypes == '12' ) ) $dur = $paytypes;
$timestamp = strtotime($strDT." $dur months");
$endDT = date("Y-m-d", $timestamp);
$timestamp = strtotime($endDT." -1 days");
$endDT = date("Y-m-d", $timestamp);
echo 'dur='.$dur.' strDT='.$strDT.' endDT='.$endDT;
$sql = 'insert into PayUser02H (PayUser01M_user_login,payTypeCD,payDcCD,payAmt,payDate,payDuration,imp_uid,apply_num,merchant_uid,customer_uid,svrstrDT,svrendDT) ';
$sql .= " values ('".$userid."', '".$paytype."', '".$paytypes."', '".$amount."', now(), '".$dur."','".$imp_uid."', '".$apply_num."', '".$merchant_uid."', '".$customer_uid."', '".$strDT."', '".$endDT."') ";
$conn->query($sql);

//echo $sql;
echo 'ok';
?>


<?
@extract($_GET);
@extract($_POST);
	date_default_timezone_set('Asia/Seoul');
	require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
	
	$g2bClass = new g2bClass;
	$uloca_live_test = $g2bClass->getSystem('1');
	$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

	//echo $mobile;
	//$current_user = wp_get_current_user();
	$userid = 'blueoceans'; //$current_user->user_login;
	$_SESSION['current_user'] = $current_user;
	require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
	$dbConn = new dbConn;
	$conn = $dbConn->conn();
	// --------------------------------- log
	$rmrk = '사용자 사용현황'.$_SERVER ['HTTP_USER_AGENT'];
	$dbConn->logWrite('blueoceans',$_SERVER['REQUEST_URI'],$rmrk);
	exit;


	// --------------------------------- log
	$sql = "select PayFreeCD from PayUser01M where user_login='".$userid."'";
	//echo $sql;
	$result2=$conn->query($sql);
	$PayFreeCD = '01';	// 01:미결제회원은 : 결제한번도 안하거나, 결제했어도 기간이 지난 경우 입니다.
	if ($row = $result2->fetch_assoc() )  $PayFreeCD = $row['PayFreeCD'];
	$today = date('Y-m-d');
	//if ($PayFreeCD != '99') {
		$sql = "select payTypeCD,payDate,payAmt,payBank,payCard,payDuration,svrstrDT,svrendDT,svrserCnt from PayUser02H where PayUser01M_user_login='".$userid."' and svrstrDT <='".$today."' and svrendDT>='".$today."' "; ;
		$sql .= " order by payDate desc ";
		echo $sql;
		$result=$conn->query($sql);
	//}
	if ($row = $result->fetch_assoc() ) {
		$svrstrDT = $row['svrstrDT'];
		$cnt = $dbConn->countLogdt($conn,$userid,$svrstrDT);
	} else $cnt = 0;

	$thismonth = date('m');
	//$sdt = "'".substr($svrstrDT,0,4)."-".$thismonth."-".substr($svrstrDT,8,2)."'";
	$sdt = substr($svrstrDT,0,4)."-".$thismonth."-".substr($svrstrDT,8,2);
	echo $sdt;
	$edt = '2019-02-11';
	$timestamp = strtotime($sdt);
	//$timestamp = strtotime('2019-03-01');
	$fromdt = date("Y-m-d",$timestamp);
	$timestamp = strtotime($fromdt." +1 months");
	$todt = date("Y-m-d",$timestamp);
	
	echo 'fromdt='.$fromdt.' todt='.$todt;
	
	
	echo 'cnt='.$cnt;
	exit;

	?>
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
	$payTypeCD = '02';
}
	$strDT = date("YmdHis");
	$url = "https://api.iamport.kr/subscribe/payments/again";
	$parm = 'customer_uid='.$customer_uid;
	$parm .= '&merchant_uid='.'uloca_'.$userid.'_'.$strDT;
	$parm .= '&amount='.$amount;
	$parm .= '&name='.'정기결제';
	$parm .='&paytype=02';					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
	$parm .='&paytypes=10';



?>
<!DOCTYPE html>
<html>
<head>
<title>정기구매 재결재</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="google-site-verification" content="l7HqV1KZeDYUI-2Ukg3U5c7FaCMRNe3scGPRZYjB_jM" />
<meta name="format-detection" content="telephone=no">  <!--//-by jsj 전화걸기로 링크되는 것 막음 -->

<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">

<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.6-20181005.js?ver=20181009"></script>
<script src="/js/common.js?version=20190203"></script>
<script src="/payment/payment.js"></script>
<script>
var userid = '<?=$userid?>';
var usermail = '<?=$usermail?>';
var payTypeCD ='<?=$payTypeCD?>';
var customer_uid = '<?=$customer_uid?>';
</script>
</head>
<script>
var amt=19800;
var merchant_uid='';
function getAccessToken() {

server = 'https://uloca23.cafe24.com/payment/getaccesstoken.php';
parm='';
getAjaxPost(server,retAccesstoken,parm);
}
function retAccesstoken(data) {
	access_token = data;

	clog('access_token='+access_token);

	merchant_uid = "uloca_monthly_"+new Date().getTime();
	// 결제(재결제) 요청
	jQuery.ajax({
		url: 'https://api.iamport.kr/subscribe/payments/again', //schedule',
		method: "post",
		headers: { "Authorization": '"' + access_token +'"' }, // 인증 토큰 Authorization header에 추가
		contentType: "text/plain", //
		accessControlAllowCredentials: true,
		data: {
                customer_uid:customer_uid,
                merchant_uid: merchant_uid, //"uloca_monthly_"+new Date().getTime(), // 새로 생성한 결제(재결제)용 주문 번호
                amount: amt,
                name: "월간 이용권 정기결제"
            },
	success:function(data){
		var str = '';
                for(var name in data){
                    str += '<li>name='+name+'/'+data[name]+'</li>';
                }
                clog(str);
				recv(data);
            }

	});

}
function dopay(access_token) {
	amount = '19800';
	parm = 'customer_uid='+'<?=$customer_uid?>';
	parm += '&merchant_uid=uloca_'+'<?=$userid?>'+'_'+new Date().getTime();
	parm += '&amount='+amt;
	parm += '&name='+'정기결제';
	parm +='&paytype=02';					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
	parm +='&paytypes=10';
	server = "https://api.iamport.kr/subscribe/payments/again";
	console.log(server+'?'+parm);
		getAjaxPost(server,recv,parm);
}
function recv(data) {
	console.log(data);
	rspg=data;
	parm = 'imp_uid='+rspg.imp_uid;				// 아임포트 거래 고유 번호
		parm +='&merchant_uid='+merchant_uid;	//가맹점에서 생성/관리하는 고유 주문번호
		parm +='&amount='+amt;			// 결제금액
		parm +='&name='+rspg.name;					// 주문명 할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
		parm +='&apply_num='+rspg.apply_num;			// 카드 승인번호
		parm +='&userid='+userid; //rspg.buyer_name;			// 주문자 이름
		parm +='&paytype='+paytype;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&paytypes='+paytypes;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&customer_uid='+customer_uid;

		server="/payment/pay_return.php";
		console.log(server+'?'+parm);
		getAjaxPost(server,recv2,parm);
}
function recv2(data) {
	msg = '결제가 완료되었습니다. ';
	alert(msg);
}
getAccessToken();
</script>
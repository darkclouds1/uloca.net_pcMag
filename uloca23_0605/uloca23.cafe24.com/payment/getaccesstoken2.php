<?
@extract($_GET);
@extract($_POST);
$customer_uid= 'uloca_userid_1551083692709'; // 카드(빌링키)와 1:1로 대응하는 값
        $merchant_uid= 'uloca_userid_1551083692709'; // 
        $schedule_at='uloca_userid_1551083692709'; // 
        $amount= '19800'; // 
        $buyer_name= ''; // 
        $buyer_email= 'usermail@abc.com'; // 
$ch = curl_init();

    $url = 'https://api.iamport.kr/subscribe/payments/schedule';
	$parm = '{"customer_uid": '.$customer_uid.', ';
	$parm .= '[ { "merchant_uid": '.$merchant_uid.', ';
	$parm .= ' "schedule_at": '.$schedule_at.', ';
	$parm .= ' "amount": '.$amount.', ';
	$parm .= ' "name": ""월간 이용권 정기결제"';
	$parm .= ' "buyer_name": '.$buyer_name.', ';
	$parm .= ' "buyer_email": '.$buyer_email.', ';
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		// post_data
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parm);

		$response = curl_exec($ch);

	var_dump ($response); //['response']);
	
?>
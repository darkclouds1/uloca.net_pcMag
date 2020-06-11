<?php
@extract($_GET);
@extract($_POST);
// sbcr_schedule.php
require_once(dirname(__DIR__).'/payment/iamport.php');
echo 'customer_uid='.$customer_uid.' amount='.$amount.'<br>';
$iamport = new Iamport('3408802956806048', 'ejjd23hnwjekmujVa7ZR9pH3wRN2r8LGr4Tu8aRFu5ZRzjMJqMAAchDI9qLAzwg1BavJlmoProH1HtL8');
$result = $iamport->subscribeSchedule(
	array(
		'customer_uid' => "'".$customer_uid."'",
		'checking_amount' => $amount, 
		'card_number' => '1234-1234-1234-1234', 
		'expiry' => '2020-01', 
		'birth' => '780101',
		'pwd_2digit' => '22',
		//'pg' => 'kakaopay.TCSUBSCRIP',
		'schedules' => array(
			array(
				'merchant_uid' => 'uloca_'.$userid.'_'.time(),
				'amount' => $amount,
				'schedule_at' => time() + 1000,
				'name' => '예약결제',
				'buyer_name' => "'".$userid."'",
				'buyer_email' => "'".$usermail."'",
				'buyer_tel' => '',
				'buyer_addr' => '',
				'buyer_postcode' => '',
			),
			array(
				'merchant_uid' => 'uloca_'.$userid.'_'.(time()+1),
				'amount' => $amount,
				'schedule_at' => time() + 30 * 24 * 60 * 60
			)
		)
	)
);
if ( $result->success ) {
	$schedules = $result->data;
	echo "## 등록된 예약정보 ##" . "\n";
	var_dump($schedules);
} else {
	echo "## 오류코드 : " . $result->error['code'] . "\n";
	echo "## 오류내용 : " . $result->error['message'] . "\n";
}

?>
<?

$ch = curl_init();
//curl -H "Content-Type: application/json"
 //   POST -d '{"imp_key": "REST API키", "imp_secret":"REST API Secret"}'
    $url = 'https://api.iamport.kr/users/getToken';
	$parm = '{"imp_key": "3408802956806048", "imp_secret":"ejjd23hnwjekmujVa7ZR9pH3wRN2r8LGr4Tu8aRFu5ZRzjMJqMAAchDI9qLAzwg1BavJlmoProH1HtL8"}';
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		// post_data
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parm);

		$response = curl_exec($ch);
//echo $response;
		//$json1 = $response; //json_decode($response, true);
		$at1 = strpos($response,'response":{',5);
//echo $at1.'<br>';
$x = substr($response,$at1);
//echo 'x='.$x.'<br>';
		//$at1 = $json1['response']['access_token'];
		$at2 = strpos($response,',',$at1);
//		echo 'at2='.$at2.'<br>';
		$at = substr($response,$at1+27,$at2-$at1-28);
		// {"code":0,"message":null,"response":{"access_token":"492b1fcc13275d6c4d3e85ab397118f91b5e756b","now":1551078887,"expired_at":1551080515}}"
//	var_dump ($response); //['response']);
	echo ($at);
?>
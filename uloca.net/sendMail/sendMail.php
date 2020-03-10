<?PHP
/******************** 인증정보 ********************/
// 대량메일 인증 관련
$sendmail_url = "https://uloca22.sendmail.cafe24.com/sendmail_api.php"; // 전송요청 URL
$secureKey = "bc2d13a570cb4d4500625db4533b1c76"; // 인증키
$userId = "uloca22"; // 발송자ID

/******************** 요청변수 처리 ********************/
// 메일발송 관련
$sender = ($_POST['sender']) ? $_POST['sender'] : ''; // 발송자 이름
$email = ($_POST['email']) ? $_POST['email'] : ''; // 발송자 이메일
$receiverlist = ($_POST['receiverlist']) ? $_POST['receiverlist'] : ''; // 수신자 리스트
$receiverlistUrl= ($_POST['receiverlistUrl']) ? $_POST['receiverlistUrl'] : ''; // 수신자 리스트 URL

// 메일내용 관련
$subject = ($_POST['subject']) ? $_POST['subject'] : ''; // 메일 제목
$content = ($_POST['content']) ? $_POST['content'] : ''; // 메일 내용

// 수신자 처리 관련
$rejectType = ($_POST['rejectType']) ? $_POST['rejectType'] : 2; // 수신거부자 발송여부(2: 제외발송, 3:포함발송)
$overlapType = 2;

// 예약발송 관련
$sendType = ($_POST['sendType']) ? $_POST['sendType'] : 0; // 예약발송 여부(0:즉시발송, 1:예약발송)
$sendDate = ($_POST['sendDate']) ? $_POST['sendDate'] : ''; // 예약발송 시간(년-월-일 시:분:초)

// 파일첨부 관련
$file_name = $_FILES['addfile']['name'];
$tmp_name = $_FILES['addfile']['tmp_name'];
$content_type = $_FILES['addfile']['type'];

// 수신거부 기능 관련
$useRejectMemo = ($_POST['useRejectMemo']) ? $_POST['useRejectMemo'] : 0; // 수신거부 사용여부(0: 사용안함, 1: 사용)

// 메일주소 중복발송 관련
$overlapType = $_POST['overlapType'] == '1' ? '1' : '2';

// 요청 테스트
$testFlag = ($_POST['testFlag']) ? $_POST['testFlag'] : 0; // 요청 테스트 사용여부(0: 사용안함, 1: 사용)

/******************** 요청변수 처리 ********************/
$mail['secureKey'] = $secureKey;
$mail['userId'] = $userId;
$mail['sender'] = base64_encode($sender);
$mail['email'] = base64_encode($email);
$mail['receiverlist'] = base64_encode($receiverlist);
$mail['receiverlistUrl'] = base64_encode($receiverlistUrl);
$mail['subject'] = base64_encode($subject);
$mail['content'] = base64_encode($content);
$mail['rejectType'] = $rejectType;
$mail['overlapType'] = $overlapType;
$mail['sendType'] = $sendType;
$mail['sendDate'] = $sendDate;
$mail['useRejectMemo'] = $useRejectMemo;
$mail['testFlag'] = $testFlag;

$host_info = explode("/", $sendmail_url);
$host = $host_info[2];
$path = $host_info[3]."/".$host_info[4];

srand((double)microtime()*1000000);
$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);

// 헤더 생성
$header = "POST /".$path ." HTTP/1.0\r\n";
$header .= "Host: ".$host."\r\n";
$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

// 본문 생성
foreach($mail AS $index => $value){
	$data .="--$boundary\r\n";
	$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
	$data .= "\r\n".$value."\r\n";
	$data .="--$boundary\r\n";
}

// 첨부파일
if (is_uploaded_file($_FILES['addfile']['tmp_name'])) { 
	$data .= "--$boundary\r\n";
	$content_file = join("", file($tmp_name));
	$data .="Content-Disposition: form-data; name=\"addfile\"; filename=\"".$file_name."\"\r\n";
	$data .= "Content-Type: $content_type\r\n\r\n";
	$data .= "".$content_file."\r\n";
	$data .="--$boundary--\r\n";
}
$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

$fp = fsockopen($host, 80);

if ($fp) { 
	fputs($fp, $header.$data);

	$rsp = '';
    while(!feof($fp)) { 
		$rsp .= fgets($fp,8192); 
	}	

	fclose($fp);

	$msg = explode("\r\n\r\n",trim($rsp));
	echo $msg[1];
}
else {
	echo "Connection Failed";
}
?>
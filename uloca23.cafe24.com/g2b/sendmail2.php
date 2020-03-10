<?

@extract($_GET);
@extract($_POST);
header('X-XSS-Protection: 0');

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
// --------------------------------- log
$rmrk = '메일발송';
$dbConn->logWrite2($resudi,$_SERVER['REQUEST_URI'],$rmrk,'','12');
// --------------------------------- log

$to  = $email2;
if ($to == '') {
	echo '받는 메일 주소가 없습니다.';
	exit;
}
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

$headers .= 'From: 나라장터 정보 <uloca@uloca.net>' . "\r\n";
$msg2 = '<p>'.$subject.'을(를) <font color=red>' . $to .'</font> 에게 보냈습니다.</p>';

?>

<?

mail($to, $subject, $message, $headers);

//echo  $message;
echo $msg2;
?>

<div style='margin-right:2px; width:80px; height:30px; font-size:14px; line-height:30px; color:#fff; text-align:center; background-color:#438ad1; border:0; cursor:pointer;'><a onclick="closeMe();" class="search">닫기</a></div>
<br><br>
<script>
function closeMe() {
	self.close(); 
}
</script>
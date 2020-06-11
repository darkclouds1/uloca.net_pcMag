<?

@extract($_GET);
@extract($_POST);
header('X-XSS-Protection: 0');

$to  = 'jayhmj@gmail.com';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

$headers .= 'From: 나라장터 정보 <uloca@uloca.net>' . "\r\n";
$subject='mail test';
$message='<a href="http://blueoceans.dothome.co.kr/">blueoceans</a>';
//echo 'message='.$message;
$msg2 = '<p>입찰 정보, 사전규격정보를 <font color=red>' . $to .'</font> 에게 보냈습니다.</p>';

?>

<?

mail($to, $subject, $message, $headers);

echo  $message;
echo $msg2;
?>

<div style='margin-right:2px; width:80px; height:30px; font-size:14px; line-height:30px; color:#fff; text-align:center; background-color:#438ad1; border:0; cursor:pointer;'><a onclick="closeMe();" class="search">닫기</a></div>
<br><br>
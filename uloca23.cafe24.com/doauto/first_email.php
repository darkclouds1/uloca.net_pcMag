<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

header('X-XSS-Protection: 0');

$compName = "한솔문구 주식회사";
$compNo = "1010610481";
$bidNtceNM = "▷낙찰결과: " . " xxxx콜센 용역 원자력발전 안전교육원 ";
$bidNtceNo = "▷입찰이력: " . $compName;

$bidNtceNM_UrlLink = "https://uloca.net/g2b/bidResult.php?bidNtceNo=". $bidNtceNo ."&pss=입찰용역"; //낙찰결과 링크 
$compno_UrlLink = "https://uloca.net/g2b/datas/getInfobyComp.php?compno=". $compNo ."&id=";		 //사업자번호 링크 


$mailFrom = "uloca.net@gmail.com";
$bcc = "uloca.net@gmail.com"; //숨은참조
$to  = "enable21@gmail.com";	//받을업체 이메일  

$subject = '나라장터)입찰결과 및 경쟁업체 순위를 확인하세요';

$message = "		<table border='5' bordercolor='darkorange' background='darkorange' rules='none'>";
$message .= "		<tr border='none'' >";
$message .= "			<td border='none' bgcolor='white'><font size='+2' style='background-color: white'><strong> 유로카닷넷<br>https://uloca.net</strong></font></td>";
$message .= "			<td></td>";
$message .= "			<td border='none' bgcolor='yellow'> <font size='+1'><strong>&nbsp;나라장터 빠르고 간편하게 검색합니다<br>&nbsp;나라장터 입찰결과를 확인하세요.</strong></font> </td>";
$message .= "		</tr>";
$message .= "		<tr>";
$message .= "			<td bgcolor='darkorange' colspan='3'><br> </td>";
$message .= "		</tr>";
$message .= "		<tr border='5' bordercolor='darkorange' bgcolor='darkorange' >";
$message .= "			<td align='center' colspan='3' >";
$message .= "				<font size='+3' style='background-color: white'><strong>나의 경쟁기업은 어디일까?</strong></font>";
$message .= "			</td>";
$message .= "		</tr>";
$message .= "		<tr bgcolor='darkorange' >";
$message .= "			<td align='center' colspan='3' >";
$message .= "				<font size='+2' color='white' style='background-color: darkorange'><strong>경쟁기업의 결과순위와 입찰이력을 확인해보세요</strong></font>";
$message .= "			</td>";
$message .= "		</tr>";
$message .= "	</table> ";
$message .= " <p><a href = '" .$bidNtceNM_UrlLink."' target='_blank'><font size='+3' color=black' style='background-color: white'><strong>". $bidNtceNM . "</strong></font></a></p>";
$message .= " <p><strong>&ensp; - 입찰공고및 결과를 키워드로 간편하게 검색하세요</strong></p>";
$message .= " <p><a href = '" .$compno_UrlLink."' target='_blank'><font size='+3' color='teal' style='background-color: white'><strong>". $bidNtceNo . "</strong></font></a></p>";
$message .= " <p><strong>&ensp; - 경쟁기업 조회는 통합검색에서 회사명을 입력하세요.</strong></p>";
$message .= " <p><a href = 'https://uloca.net/ulocawp/?page_id=328' target='_blank'><font size='+3' color='darkorange' style='background-color: white'><strong> ▷유로카닷넷 회원모집: https://uloca.net  </strong></font></a></p>";
$message .= " <p><strong>&ensp; - 선착순 회원모집 후 회원제로만 운영합니다. </strong></p>";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: 입찰정보 <uloca@uloca.net>' . "\r\n";
$headers .= 'Reply-To: <'. $mailFrom . ">\r\n";
$headers .= "Bcc: ". $bcc ."\r\n";

$result = mail($to, $subject, $message, $headers);

if (!$result) {
    alert('메일전송실패!!! \n 다시 작성하세요');
}
//$msg2 = '<p>입찰 정보, 사전규격정보를 <font color=red>' . $to .'</font> 에게 보냈습니다. </p>';
//echo $msg2;
echo $headers;
echo 'subject='.$subject."<br>";
echo $message;

?>

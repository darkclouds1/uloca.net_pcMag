<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

function SendEmail($to_mail, $compNo, $compName, $bidNtceNo, $bidNtceOrd, $bidNtceNM, $new, $grade) {
	//공고명 링크
	$bidNtceNM_UrlLink = "https://uloca.net/g2b/bidResult.php?bidNtceNo=".$bidNtceNo."&bidNtceOrd=".$bidNtceOrd."&pss=‘입찰용역’"; //낙찰결과 링크
	//회사 입찰이력 링크
	$compno_UrlLink = "https://uloca.net/g2b/datas/getInfobyComp.php?compno=". $compNo ."&id=";		 //사업자번호 링크
	//기업검색(통합검색) 링크
	$compSearch_UrlLink = "https://uloca.net/ulocawp/?page_id=1134&searchType=2&kwd&dminsttNm&compname=".$compName."&curStart=0&cntonce=100&compinfo=1&id=";
	
	$bidNtceNM = "▷".$bidNtceNM;	//공고명에 ▷ 추가
	$compName = "▷".$compName;		//회사명에 ▷ 추가
	
	$mailFrom = "uloca@uloca.net";
	
	// new = true 일때만 숨은참조 보냄 (신규로 이메일 발굴된 때)
	// $rmark 순위문자
	if ($new || (int)$grade == 1 ) { // new = true or 1순위 일때만 숨은참조 보냄
		$bcc = "uloca.net@gmail.com";
	}
	//$bcc = "uloca.net@gmail.com"; //숨은참조 $new == ture 일때만
	
	$subject = "나라장터) 입찰결과 순위에서 경쟁업체를 확인하세요";
	
	$message =  " <table border='1' width='90%' cellspacing='1' cellpadding='4' bordercolor='darkorange' background='darkorange' rules='none'>";
	//유로카닷넷 http://uloca.net 기업검색 링크
	$message .= "		<tr bordercolor='white' bgcolor='white'>";
	$message .= "			<td colspan='3' align='center'><font size='+3' color='black'><strong>유로카 닷넷<br></strong></font><font size='+1'><a href='".$compSearch_UrlLink."'> https://uloca.net 기업검색</a></font> </td>";
	$message .= "		</tr>";
	//빈줄 row
	$message .= "		<tr bordercolor='darkorange' bgcolor='darkorange' >";
	$message .= "			<td colspan='3' bgcolor='darkorange' bordercolor='darkorange' align='center' >";
	$message .= "				<font size='0' style='background-color: orange'> <strong></strong></font>";
	$message .= "			</td>";
	$message .= "		</tr>";
	//나의 경쟁기업은?
	$message .= "		<tr width='80%' bordercolor='darkorange' bgcolor='darkorange' >";
	$message .= "			<td width='1%' bgcolor='darkorange' bordercolor='darkorange' align='center'></td>";
	$message .= "			<td bgcolor='white' bordercolor='darkorange' align='center'>";
	$message .= "				<font size='+2' style='background-color: white'><strong>'나의 경쟁 기업은?'</strong></font>";
	$message .= "			</td>";
	$message .= "			<td width='1%' bgcolor='darkorange' bordercolor='darkorange' align='center'></td>";
	$message .= "		</tr>";
	//빈줄 오렌지 바탕색
	$message .= "		<tr bordercolor='darkorange' bgcolor='darkorange' >";
	$message .= "			<td colspan='3' bgcolor='darkorange' bordercolor='darkorange' align='center' >";
	$message .= "				<font size='1' color='darkorange' style='background-color: darkorange'> <strong></strong></font>";
	$message .= "			</td>";
	$message .= "		</tr>";
	//기업검색으로 경쟁사 입찰이력을 확인해보세요
	$message .= "		<tr bgcolor='darkorange' >";
	$message .= "			<td colspan='3' align='center' >";
	$message .= "				<font size='+2' color='white' style='background-color: darkorange'><strong>기업검색으로 경쟁사 입찰이력을 확인해보세요</strong></font>";
	$message .= "			</td>";
	$message .= "		</tr>";
	//빈줄 오젠지 바탕색
	$message .= "		<tr bordercolor='darkorange' bgcolor='darkorange' >";
	$message .= "			<td colspan='3' bgcolor='darkorange' bordercolor='darkorange' align='center' >";
	$message .= "				<font color='darkorange' style='background-color: darkorange'> <strong> </strong></font>";
	$message .= "			</td>";
	$message .= "		</tr>";
	$message .= " </table><br> ";
	//입찰이력 링크
	$message .= " <p><a href = '".$compno_UrlLink."' target='_blank'><font size='+1' color='teal' style='background-color: white'><strong>".$compName."</strong></font></a></p>";
	$message .= " <p><font color='black'><strong> → 클릭해서 자신의 입찰이력을 확인해 보세요. 통합검색에서 모든 기업이 조회됩니다. </strong></font></p>";
	//낙찰결과 링크
	$message .= " <p><a href = '" .$bidNtceNM_UrlLink."' target='_blank'><font size='+1' color=black' style='background-color: white'><strong>". $bidNtceNM . "</strong></font></a></p>";
	$message .= " <p><font color='black'><strong> → 공고명을 클릭해서 경쟁기업의 입찰이력을 확인해 보세요. 통합검색에서 모든 공고가 검색됩니다.</strong></font></p>";
	//회원가입 링크
	$message .= " <p><a href = 'http://bit.ly/2VmBav4' target='_blank'><font size='+1' color='darkorange' style='background-color: white'><strong> ▷ 유로카닷넷 회원가입하기 </strong></font></a></p>";
	$message .= " <p><font color='black'><strong> ※선착순 회원제로 운영합니다.</strong></font></p>";
	//기업검색 링크
	$message .= " <p><<a href=".$compSearch_UrlLink."><font size='+1' color='darkorange' style='background-color: white'><strong>▷ 유로카닷넷 기업검색 바로가기 → <font size='+1'>https://uloca.net </strong></font></a></p>";
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: 입찰정보 <' . $mailFrom . ">\r\n";
	$headers .= 'Reply-To: <'. $mailFrom . ">\r\n";
	$headers .= "Bcc: ". $bcc ."\r\n";
	
	$result = mail($to_mail, $subject, $message, $headers);
	
	if (!$result) {	//전송실패 시 나에게 메일보냄
		mail("uloca.net@gmail.com", "메일전송실패!!", $message, $headers);
		//alert('메일전송실패!!! \n 다시 작성하세요');
		return false;
	} else {
		return true;
	}
} //send email

//email test 용
$to_mail = "enable21@gmail.com";
$compNo = "2464100220";
$compName = "모노리스";
$bidNtceNo = "test-bidNtceNo";
$bidNtceOrd = "00";
$bidNtceNM = "콜센터 운영";
$grade = '1';

if($rtn=SendEmail($to_mail, $compNo, $compName, $bidNtceNo, $bidNtceOrd, $bidNtceNM, true, $grade)){
	echo ($to_mail."--> 이메일을 발송했습니다.");
} else {
	echo ($to_mail."--> 이메일을 발송 실패 ");
}


?>
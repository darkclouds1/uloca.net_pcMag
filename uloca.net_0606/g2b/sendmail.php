<?php

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
require($_SERVER['DOCUMENT_ROOT'].'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck();

/* 다중 수신자 */
$to  = $email; //'jayhmj@hotmail.com' . ', ' ; // 콤마인 것에 주의.
//$to .= 'jayhmj@naver.com';

// 제목
$subject = '입찰 알림';
//echo 'bid'.$startDate.$endDate.$kwd.$dminsttNm.'100'.'1'.'1';
$response1 = $g2bClass->getSvrData('bidthing',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 물품입찰
$response2 = $g2bClass->getSvrData('bidcnstwk',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 공사입찰
$response3 = $g2bClass->getSvrData('bidservc',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 용역입찰
$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰

//var_dump($response);

$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
//echo '<br>'.'물품입찰<br>';
//var_dump($item1);
$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];
//echo '<br>'.'공사입찰<br>';
//var_dump($item2);
$json3 = json_decode($response3, true);
$item3 = $json3['response']['body']['items'];
//echo '<br>'.'용역입찰<br>';
//var_dump($item3);
$json4 = json_decode($response4, true);
$item4 = $json4['response']['body']['items'];
//echo '<br>'.'외자입찰<br>';
//var_dump($item4);
$item = array_merge($item1,$item2,$item3,$item4);
// 메세지
$message = '
<html>
<head>
  <title>입.낙찰 알림</title>
  <link rel="stylesheet" type="text/css" href="http://uloca.net/g2b/css/g2b.css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="http://uloca.net/g2b/g2b.js"></script>
</head>
<body>
    <table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="100px;">공고번호</th>
        <th scope="cols" width="150px;">공고명</th>
        <th scope="cols" width="100px;">추정가격</th>
        <th scope="cols" width="120px;">공고일시</th>
        <th scope="cols" width="100px;">수요기관</th>
		<th scope="cols" width="120px;">마감일시</th>
    </tr>
	';
	/*
	//array_multisort($bidClseDt, SORT_DESC, $item);
foreach ($item as $key => $row) {
    $bidClseDt[$key]  = $row['bidClseDt'];
} 
array_multisort($bidClseDt, SORT_DESC, $item); // 마김일시

	$i=0;
foreach($item as $arr ) { //foreach element in $arr
    //$uses = $item['var1']; //etc
	if ($i % 2 == 0) {
		$tr = '<tr>';
		
		$tr .= '<td><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td align=right>'.number_format($arr['presmptPrce']).'</td>';
		$tr .= '<td>'.$arr['bidNtceDt'].'</td>';
		//$tr .= '<td><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['dminsttNm'].'</a></td>';
		$tr .= '<td>'.$arr['dminsttNm'].'</td>';
		$tr .= '<td>'.$arr['bidClseDt'].'</td>';
		$tr .= '</tr>';
	} else {
		$tr = "<tr>";
		
		$tr .= '<td scope="row" class="even"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
		$tr .= '<td scope="row" class="even">'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td scope="row" class="even" align=right>'.number_format($arr['presmptPrce']).'</td>';
		$tr .= '<td scope="row" class="even">'.$arr['bidNtceDt'].'</td>';
		$tr .= '<td scope="row" class="even">'.$arr['dminsttNm'].'</td>';
		$tr .= '<td scope="row" class="even">'.$arr['bidClseDt'].'</td>';
		$tr .= '</tr>';
	}
	$message .= $tr;
	$i += 1;
}
$message .= '
  </table>
  
</body>
</html>

'; */
$msg2 = '<p>입.낙찰 - 위 정보를 <font color=red>' . $to .'</font> 에게 보냈습니다.</p>';
//<th scope="cols" width="40px;"><input type="checkbox"></th>
//$tr .= '<td scope="row" class="even"><input id=chk type="checkbox" /></td>';
// HTML 메일을 보내려면, Content-type 헤더를 설정해야 합니다.
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

// 추가 헤더
//$headers .= 'To: Mary <jayhmj@hotmail.com>, Kelly <jayhmj@gmail.com>' . "\r\n";
$headers .= 'From: 나라장터 정보 <uloca@uloca.net>' . "\r\n";
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";




?>
 <!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" -->
<link rel="stylesheet" type="text/css" href="css/g2b.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!-- script type="text/javascript" src="js/datepicker.js"></script -->
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="http://uloca.net/g2b/g2b.js"></script>
</head>

<body>
 <table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="100px;">공고번호</th>
        <th scope="cols" width="150px;">공고명</th>
        <th scope="cols" width="100px;">추정가격</th>
        <th scope="cols" width="120px;">공고일시</th>
        <th scope="cols" width="100px;">수요기관</th>
		<th scope="cols" width="120px;">마감일시</th>
    </tr>
</table>

<script language="javascript">
  function closeMe() {
	
	//alert("메일을 보냈습니다.");
  //history.go(-1);
  window.close();
	}
/*
function showView( bidno,bidseq ) {
	
	
	alert("showCartLinkView bidno="+bidno+' bidseq'+bidseq);
	url = "http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno="+bidno+"&bidseq="+bidseq+"&releaseYn=Y&taskClCd=5";
	//location.href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno="+bidno+"&bidseq="+bidseq+"&releaseYn=Y&taskClCd=5";
	
	popupWindow = window.open(
        url,'_blank','height=700,width=860,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')
}
*/
	tbls = document.getElementById("bidData");
	trs = selectOpenerCheckedRow();
	tblin =  tbls.innerHTML.replace("<tbody>", "").replace("</tbody>", "") + trs ;
	console.log(tblin);
	tbls.innerHTML = tblin;
	//alert (tbls);
	
 </script>

 <? 
 echo $msg2;
 
 ?>
 <br>
<center><div class="btn_area" style='width:80px; height:30px; display:inline-block; font-size:14px; line-height:30px; color:#fff; text-align:center; background-color:#438ad1; border:0; cursor:pointer;'><a onclick="closeMe();" class="search">닫기</a></div></center>
 
<?
// 메일 보내기
mail($to, $subject, $message, $headers);
?>
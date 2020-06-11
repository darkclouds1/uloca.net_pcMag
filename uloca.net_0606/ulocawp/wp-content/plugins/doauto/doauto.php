<?php
/*
Plugin Name: doauto : 국가 공공데이타 요청 실행
Plugin URI: http://uloca.net/ulocawp/wp-content/plugins/pubdatas.php
Description: 워드프레스 국가 공공데이타 자동 받기 실행.
Version: 1.0
Author: Monolith
Author URI: http://uloca.net
function: 입찰정보를 설정에 따라 자동으로 메일로 보냅니다.
*/

function pubdatas_doautoShortCode() {
	date_default_timezone_set('Asia/Seoul');
	/* require_once($DOCUMENT_ROOT.”/classphp/g2bClass.php”);

	$g2bClass = new g2bClass;
	$mobile = $g2bClass->MobileCheck();
	echo $mobile; */
	require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
	$dbConn = new dbConn;
	$conn = $dbConn->conn();
// --------------------------------- log
$rmrk = '자동받기 실행';
$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log
?>
<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">
<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script src="/datas/datas.js"></script>
<script src="/g2b/g2b.js"></script>
<script type="text/javascript">

function runAuto() {
	var table = document.getElementById('bidData');
	var currentEmail = '';
	var oldEmail = '';
	var kwdArray = [];
	var dminsttNmArray = [];
	var userId = '';
	var oldUserId  = '';
	var kwd = '';
	
	rows = table.getElementsByTagName('tr');
	for (i = 0; i < rows.length; i++) {
		cells = rows[i].getElementsByTagName('td');
		if (!cells.length) continue; // 헤드제외 
		
		//rows[i].style.backgroundColor = 'red';	
		parm = '?userid='+cells[1].innerHTML;
		parm += '&email='+cells[2].innerHTML;
		parm += '&kwd='+cells[3].innerHTML;
		parm += '&dminsttNm='+cells[4].innerHTML;
		parm += '&search='+cells[7].innerHTML;
		parm += '&send='+cells[6].innerHTML;

		userId = cells[1].innerHTML;
		currentEmail = cells[2].innerHTML;
		kwd = cells[3].innerHTML;
		if (kwd == "") kwd = "%";

		dminsttNm= cells[4].innerHTML;

		//if (kwd =='') continue;	// 키워드가 없슴
		
		// 이메일이 바뀌면 oldEmail 메일보냄 
		if (oldEmail != '' && oldEmail != currentEmail) {
			//이메일 보내고 kwd 초기화
			ajaxCall(kwdArray, dminsttNmArray, oldUserId, oldEmail);
			kwdArray = [];
			dminsttNmArray = [];
		}

		//마지막줄인 경우 current email로 보냄 -by jsj 20190803
		if (rows.length - 1 == i) {
			// 새로운 이메일이면 kwd 초기화 
			if (oldEmail != currentEmail) { 
				kwdArray= [];
				dminsttNmArray = [];
			} 				
			kwdArray.push(kwd);
			dminsttNmArray.push(dminsttNm);

			ajaxCall(kwdArray, dminsttNmArray, userId, currentEmail);
			//alert("아이디=" + oldUserId + ", 이메일= " + oldEmail + ", kwd갯수= " + kwdArray.length);
		}

		// 키워드 array 저장 후 색깔바꿈
		kwdArray.push(kwd);
		dminsttNmArray.push(dminsttNm);

		oldEmail = currentEmail;
		oldUserId = userId;
		rows[i].style.color = 'brown';
		//clog(server+parm);
	}
	i--;
	alert(i+'건이 자동 발송 되었습니다.');
}

function ajaxCall(kwdArray, dminsttNmArray, userId, Email ) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: '/ulocawp/wp-content/plugins/doauto/runauto_mail.php',
		data: {kwd:kwdArray, dminsttNm:dminsttNmArray, userid:userId, email:Email},
		success: function (data) {
			console.log(data);
		},
		error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});
}

/*
function ajaxUloca(server,client) {// XMLHttpRequest 객체의 생성
    xhr = new XMLHttpRequest();
    xhr.open('GET', server);
    xhr.send();
	xhr.onreadystatechange=client;
}
*/
//function ajaxClient() {}
//runAuto();

</script>

</head>

<body>
	
<center><div style='font-size:14px; color:blue;'>다음 사용자의 설정에 따라 자동 검색하여 메일로 보내줍니다.</div></center>

<div id=totalrec style="text-align: left;"></div>

<table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="5%;">번호</th>
		<th scope="cols" width="12%;">아이디</th>
        <th scope="cols" width="15%;">이메일</th>
        <th scope="cols" width="12%;">키워드</th>
        <th scope="cols" width="12%;">수요기관</th>
        <th scope="cols" width="12%;">검색종류</th>
		<!-- th scope="cols" width="15%;">알림</th -->
		<th scope="cols" width="16%;">종료일</th>
		<th style="display:none;" width="16%;">검색</th>
    </tr>

<?

	$conn = $dbConn->conn(); //

		$now = new DateTime();
		$nows = $now->format('Y-m-d'); // H:i:s');

		$sql = " SELECT * FROM autoPubDatas";
		$sql .= " WHERE kwd <> ''";
		$sql .= " ORDER BY email";

		//echo 'sql='.$sql;
		$result = $conn->query($sql);
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			$k = $i+1;
			$searchType = $row["searchType"];
			// searchType 1: 물품 2: 사전규격 4: 공사 8:용역
			if ($searchType == 1) $search = '물품';
			else if ($searchType == 2) $search = '사전규격';
			else if ($searchType == 3) $search = '물품+사전규격';
			else if ($searchType == 4) $search = '공사';
			else if ($searchType == 5) $search = '물품+공사';
			else if ($searchType == 6) $search = '공사+사전규격';
			else if ($searchType == 7) $search = '물품+공사+사전규격';
			else if ($searchType == 8) $search = '용역';
			else if ($searchType == 9) $search = '물품+용역';
			else if ($searchType == 10) $search = '용역+사전규격';
			else if ($searchType == 11) $search = '물품+용역+사전규격';
			else if ($searchType == 12) $search = '공사+용역';
			else if ($searchType == 13) $search = '물품+공사+용역';
			else if ($searchType == 14) $search = '공사+용역+사전규격';
			else if ($searchType == 15) $search = '물품+공사+용역+사전규격';
			//echo 'searchType='.$searchType.' search='.$search;
			$sendType = $row["sendType"];
			/*if ($sendType == 1) $send = '이메일';
			else if ($sendType == 2) $send = '카톡';
			else if ($sendType == 3) $send = '이메일+카톡';
			else if ($sendType == 4) $send = '문자';
			else if ($sendType == 5) $send = '이메일+문자';
			else if ($sendType == 6) $send = '카톡+문자';
			else if ($sendType == 7) $send = '이메일+카톡+문자'; */
			$send = '이메일';
			$tr = '<tr>';
			$tr .= '<td style="text-align: center; ">'.$k.'</td>';
			$tr .= '<td ">'.$row['id'].'</td>';
			$tr .= '<td>'.$row['email'].'</td>';
			$tr .= '<td>'.$row['kwd'].'</td>';
			$tr .= '<td>'.$row['dminsttnm'].'</td>';
			$tr .= '<td>'.$search.'</td>';
			//$tr .= '<td>'.$send.'</td>';
			$tr .= '<td style="text-align:center;">'.$row['svrendDT'].'</td>';
			$tr .= '<td style="display:none;">'.$searchType.'</td>';
			$tr .= '</tr>';
			
			echo $tr;
			$i += 1;
		}
echo '</table>';

ob_end_flush();
flush();

?>

<br>
<center>
<div class="btn_areas">
	<a onclick="runAuto();" class="search">실행</a>
</div>		
</center>
<script>
document.getElementById('totalrec').innerHTML = 'total record='+<?=$k?>; 
</script>

<div style='text-align:right; font-size:11px;color:red;'>실행 된 줄은 갈색으로 변합니다.</div>


</body>
</html>
<?
 
ob_end_flush();
flush();
} // end of pubdatas_setupShortCode


add_shortcode('doauto_do','pubdatas_doautoShortCode');

?>
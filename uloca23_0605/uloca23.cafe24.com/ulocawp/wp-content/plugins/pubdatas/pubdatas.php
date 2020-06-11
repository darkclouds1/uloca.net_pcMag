<?php
/*
Plugin Name: pubdatas : 국가 공공데이타 요청 설정
Plugin URI: /ulocawp/wp-content/plugins/pubdatas.php
Description: 워드프레스 국가 공공데이타 요청 설정 입니다.
Version: 1.0
Author: Monolith
Author URI: http://uloca23.cafe24.com
function: 아이디,키워드,검색종류(입찰,사전규격,낙찰), 보내는 방법-메일(1),문자(3),카톡(2)
*/

function pubdatas_setupShortCode() {
	global $current_user;
    $current_user = wp_get_current_user(); //get_currentuserinfo();
	$sendmail = 'email';
	
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
// --------------------------------- log
$rmrk = '자동받기설정';
$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

	$g2bClass = new g2bClass;
	$uloca_live_test = $g2bClass->getSystem('1');
	$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"	
?>
<!DOCTYPE html>
<html>
<head>
<title>자동받기설정</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">
<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script src="/datas/datas.js"></script>
</head>

<body>

<div align=left style='font-size:12px; color:red'>아래 목록을 클릭하면 수정,삭제 버튼이 뜹니다. 설정은 한 아이디에 5개까지 됩니다.</div>

<!-- ------------------ 검색창 ---------------------------------------------------------- -->
<!-- http://uloca23.cafe24.com/ulocawp/wp-content/plugins/pubdatas/ -->
<form action="/datas/insAutoSet.php" name="myForm" id="myform" method="post" >
<input type="hidden" name="resudi" value="<?=$current_user->user_login?>" />
    

<div id="contents">
<div class="detail_search" >

	<table id='tablei' align=center cellpadding="0" cellspacing="0" width="900px">
		<colgroup>
			<col style="width:20%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>
			<!-- 아이디,키워드,검색종류(입찰,사전규격,낙찰), 보내는 방법-메일(1),문자(3),카톡(2) -->
			<tr>
				<th>아이디</th>
				<td>
					<input class="input_style2" type="hidden" name="userid" id="userid" value="<?=$current_user->user_login?>" style="width:200px;" readonly=readonly />
					<label>&nbsp;&nbsp;&nbsp;<?=$current_user->user_login?></label>
				</td>
			</tr>
			<tr>
				<th>이메일</th>
				<td>
					<input class="input_style2" type="hidden" name="email" id="email" value="<?=$current_user->user_email?>" style="width:200px;" readonly=readonly />
					<label>&nbsp;&nbsp;&nbsp;<?=$current_user->user_email?></label>
				</td>
			</tr>
			<tr>
				<th>공고명</th>
				<td>&nbsp;&nbsp;
					<input class="input_style2" type="text" name="kwd" id="kwd" value="" maxlength="50" style="width:200px;" />
					
				</td>
			</tr>
			<tr>
				<th>수요기관</th>
				<td>&nbsp;&nbsp;
					<input class="input_style2" type="text" name="dminsttNm" id="dminsttNm" value="" maxlength="50" style="width:200px;" />
					
				</td>
			</tr>
			
			<tr>
				<th>검색종류</th>
				<td>&nbsp;&nbsp;
					<!-- input class="chkboxx" type="checkbox" id="chkBid" name="chkBid" value="bid" 
					<? if ($chkBid == 'bid') {?> checked <?}?>/><label for='chkBid' style='font-size:16px; font-weight: bold;vertical-align:text-middle'>입찰</label -->
					<input class="chkboxx" type="checkbox" id="bidthing" name="bidthing" value="bidthing" checked=checked/>
					<label for='bidthing' style='font-size:16px; font-weight: bold;vertical-align:text-middle'>물품</label>
					<input class="chkboxx" type="checkbox" id="bidcnstwk" name="bidcnstwk" value="bidcnstwk"  checked=checked/>
					<label for='bidcnstwk' style='font-size:16px; font-weight: bold;vertical-align:text-middle'>공사</label>
					<input class="chkboxx" type="checkbox" id="bidservc" name="bidservc" value="bidservc"  checked=checked/>
					<label for='bidservc' style='font-size:16px; font-weight: bold;vertical-align:text-middle'>용역</label>
					<? if ($mobile == 'Mobile') echo '<br>&nbsp&nbsp;'; ?>
					<input class="chkboxx" type="checkbox" id="chkHrc" name="chkHrc" value="hrc"  checked=checked
					<? if ($chkHrc == 'hrc') {?> checked <?}?>/><label for='chkHrc' style='font-size:16px; font-weight: bold;vertical-align:text-middle'>사전규격</label>
					<input type="hidden" id="searchType" name="searchType" /> <!--이전의 '입찰'로 선택하신분은 '물품' 으로 변경 설정되엇으니 바꾸실 분은 '물품','공사','용역'중에서 선택을 다시 하세요.-->
				</td>
			</tr>
			<!-- tr>
				<th>알림</th>
				<td>
					<input class="chkboxx" type="checkbox" id="sendmail" name="sendmail" value="email" 
					<? if ($sendmail == 'email') {?> checked <?}?>/><label for='sendmail' style='font-size:16px; font-weight: bold;vertical-align:text-middle'>이메일</label>
					<input class="chkboxx" type="checkbox" id="sendkatalk" name="sendkatalk" value="katalk" 
					<? if ($sendkatalk == 'katalk') {?> checked <?}?>/><label for='sendkatalk' style='font-size:16px; font-weight: bold;vertical-align:text-middle'>카카오톡</label>
					<input class="chkboxx" type="checkbox" id="sendsms" name="sendsms" value="sms" 
					<? if ($sendsms == 'sms') {?> checked <?}?>/><label for='sendsms' style='font-size:16px; font-weight: bold;vertical-align:text-middle'>문자</label> 현재는 이메일만.
					<input type="hidden" id="sendType" name="sendType" />
				</td>
			</tr>
			<tr>
				<th>카톡 아이디</th>
				<td>
					<input class="input_style2" type="text" name="katalk" id="katalk" value="<?=$katalk?>" maxlength="50" style="width:30%;" /> 카카오톡 선택하신분은 필수
					
				</td>
			</tr>
			<tr>
				<th>휴대폰</th>
				<td>
					<input class="input_style2" type="text" name="cellphone" id="cellphone" value="<?=$phoneno?>" maxlength="50" style="width:30%;" /> 문자로 선택하신분은 필수
					
				</td>
			</tr -->
			<input type="hidden" name="idx" id="idx" value="" />
		</tbody>
		</table>

<div class="btn_area" style='display:none;' id='doupdate'>
		<center>
		<a onclick="lineupdate();" class="search">수정</a>
		<a onclick="linedelete();" class="search">삭제</a>
		<a onclick="gobackins();" class="search">취소</a>
		</center>
</div>
<div class="btn_area" id='doins'>
	<center>
		<a onclick="savepubsetup();" class="search">추가</a>
	</center>
		</div>	
	</div>
	</div>
</form>


<div id=wasrec>
<div id=totalrec style="text-align: left;"></div>

<table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="5%;">번호</th>
		<!--th scope="cols" width="15%;">아이디</th>
        <th scope="cols" width="15%;">이메일</th -->
        <th scope="cols" width="15%;">공고명</th>
        <th scope="cols" width="15%;">수요기관</th>
        <th scope="cols" width="18%;">검색종류</th>
		<!-- th scope="cols" width="12%;">종료일</th -->

    </tr>


<?
//require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
//$dbConn = new dbConn;
$conn = $dbConn->conn();

		// Check connection
		if ($conn->connect_error) {
			die("DB Connection failed: " . $conn->connect_error);
		} 
	//echo $current_user;
	//if ($current_user->user_login != '' ) {
		$now = new DateTime();
		$nows = $now->format('Y-m-d H:i:s');
		
		//$sql = 'select a.*,b.till from autoPubDatas a, autoPubAccnt b where a.id=b.id and b.till>= \''.$nows.'\' and a.id = \''. $current_user->user_login . '\' order by a.id';
		$sql = 'SELECT a.*, b.till FROM autoPubDatas AS a LEFT OUTER JOIN autoPubAccnt AS b ON a.id = b.id where a.id = \''. $current_user->user_login . '\'  order by b.till, a.idx';
		//SELECT a.*, b.till FROM autoPubDatas AS a LEFT OUTER JOIN autoPubAccnt AS b ON a.id = b.id where a.id ='jayhmj@naver.com' and b.till >= '20180723' order by a.id
		//$sql = 'select a.*, b.till from autoPubDatas a, autoPubAccnt b where a.id=b.id  order by a.idx';
		//echo 'sql='.$sql;
		$result = $conn->query($sql);
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			$k = $i+1;
			$searchType = $row["searchType"];
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
			/* $sendType = $row["sendType"];
			if ($sendType == 1) $send = '이메일';
			else if ($sendType == 2) $send = '카톡';
			else if ($sendType == 3) $send = '이메일+카톡';
			else if ($sendType == 4) $send = '문자';
			else if ($sendType == 5) $send = '이메일+문자';
			else if ($sendType == 6) $send = '카톡+문자';
			else if ($sendType == 7) $send = '이메일+카톡+문자'; */
			if ($i % 2 == 0) {
				$tr = '<tr onclick="javascript:clickTrEvent(this)">';
				$tr .= '<td style="text-align: center; ">'.$k.'</td>';
				$tr .= '<td  hidden="hidden">'.$row['id'].'</td>';
				
				$tr .= '<td hidden="hidden">'.$row['email'].'</td>';
				$tr .= '<td>'.$row['kwd'].'</td>';
				$tr .= '<td>'.$row['dminsttnm'].'</td>';
				$tr .= '<td>'.$search.'</td>';
				//$tr .= '<td>'.$send.'</td>';
				$tr .= '<td  hidden="hidden" style="text-align:center;">'.$row[till].'</td>';
				//$tr .='<td hidden="hidden">'.$row['katalk'].'</td>';
				//$tr .='<td hidden="hidden">'.$row['cellphone'].'</td>';
				$tr .='<td hidden="hidden">'.$row['idx'].'</td>';
				$tr .= '</tr>';
			} else {
				//$tr = '<tr onclick="javascript:clickTrEvent(this)" onmouseover="javascript:changeTrColor(this) ">';
				$tr = '<tr onclick="javascript:clickTrEvent(this)">';
				$tr .= '<td class="even" style="text-align: center;">'.$k.'</td>';
				$tr .= '<td hidden="hidden" class="even">'.$row['id'].'</td>';
				$tr .= '<td hidden="hidden" class="even">'.$row['email'].'</td>';
				$tr .= '<td class="even" >'.$row['kwd'].'</td>';
				$tr .= '<td class="even" >'.$row['dminsttnm'].'</td>';
				$tr .= '<td class="even" >'.$search.'</td>';
				//$tr .= '<td class="even" >'.$send.'</td>';
				$tr .= '<td hidden="hidden" class="even" style="text-align:center;">'.$row[till].'</td>';
				//$tr .='<td hidden="hidden">'.$row['katalk'].'</td>';
				//$tr .='<td hidden="hidden">'.$row['cellphone'].'</td>';
				$tr .='<td hidden="hidden">'.$row['idx'].'</td>';
				//$tr .='<input type=hidden value='.$row['katalk'].' id="kt">';
				//$tr .='<input type=hidden value='.$row['cellphone'].' id="cp">';
				$tr .= '</tr>';
			}
			echo $tr;
			$i += 1;
		}
echo '</table>';
echo '</div>';
?>
<div id='rslt' style = "font-size: 14px; color:red;"></div>
<!-- div style = "font-size: 11px; color:blue;">
새로 등록된 입찰 정보를 받고 싶으신 분은 '회원가입'을 하시고 '자동 받기 설정'에서 설정 해 주시면 이메일로
하루에 2-3번 받으실수 있습니다. <font color=red>회비가 미납되면 안뜹니다.</font>
</div -->
<script>

document.getElementById('totalrec').innerHTML = 'total record='+<?=$k?>; 

//alert("이전의 '입찰'로 선택하신분은 '물품' 으로 변경 설정되엇으니 바꾸실 분은 '물품','공사','용역'중에서 선택을 다시 하세요. 새로 설정핫는 분은 그냥 하시면 됩니다.");
</script>
</body>
</html>

<?
} // end of pubdatas_setupShortCode


add_shortcode('pubdatas_setup','pubdatas_setupShortCode');


add_filter('wpmem_user_edit_heading', 'my_user_edit_heading');
function my_user_edit_heading($heading){
	$heading = '자동 발송 설정 페이지입니다.';
	return $heading;
} 
?>
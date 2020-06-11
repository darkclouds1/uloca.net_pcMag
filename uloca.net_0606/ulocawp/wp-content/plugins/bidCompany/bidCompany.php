<?php
/*
Plugin Name: bidCompany : 특정업체 응찰정보
Plugin URI: http://uloca.net/bidCompany/bidCompany.php
Description: 워드프레스 특정업체 응찰정보 입니다.
Version: 1.0
Author: Monolith
Author URI: http://uloca.net/bidCompany/bidCompany.php
*/
//include 'http://uloca.net/g2b/scsBid.php';
function bidCompanyShortCode() {
session_start();
$current_user = wp_get_current_user();
$_SESSION['current_user'] = $current_user;
//echo 'current_user->user_email='.$current_user->user_email;
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
// --------------------------------- log
$rmrk = ''.$_SERVER ['HTTP_USER_AGENT'];
$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log
$compname = '';
$compno = '';
?>
<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="http://uloca.net/jquery/jquery-ui.css">
<script src="http://uloca.net/jquery/jquery.min.js"></script>
<script src="http://uloca.net/jquery/jquery-ui.min.js"></script>
<script src="/g2b/g2b.js"></script>
<!-- script src="http://uloca.net/include/JavaScript/Ajax.js"></script -->
<script src="http://uloca.net/include/JavaScript/tableSort.js"></script>

<script>
var bidDataTable ; 
var sortreplace ;
function setSort() {
	bidDataTable = document.getElementById( "bidData" ); 
	sortreplace = replacement( bidDataTable ); 
}
function sortTD( index ){ sortreplace.ascending( index );  } 
function reverseTD( index ){ sortreplace.descending( index ); } 

function gotoBid() {
	url = '?page_id=337';
	location.href= url;
}
</script>
</head>
<body>
<div id="contents">
<div class="detail_search" >
<form action="/g2b/datas/bidCompanySearch.php" name="myForm" id="myForm" method="post" >

	<table align=center cellpadding="0" cellspacing="0" width="700px">
		<colgroup>
			<col style="width:20%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>
			<tr>
				<th>업체명</th>
				<td>
					<input class="input_style2" type="text" name="compname" id="compname" onkeypress="if(event.keyCode==13) {searchcomp(); return false;}" value="<?=$compname?>" maxlength="50" style="width:30%;ime-mode:active;"/>&nbsp;2글자 이상 입력하세요.&nbsp;&nbsp;
					</td>
			</tr>
			<tr>
				<th>사업자등록번호(10자리)</th>
				<td>
					<input class="input_style2" type="text" name="compno" id="compno" value="<?=$compno?>" maxlength="50" style="width:30%;" />&nbsp;둘 다 입력하면 사업자등록번호가 우선입니다.

					<input type="hidden" name="duration" id="duration" value="all" />
					</td>
			</tr>
			<!--tr>
				<th>기간</th>
				<td>
					<select name="duration">
						<option value="all">전체(시간 많이 걸림)</option>
						<option value="2018_2" selected="selected">2018년 후반기(7월-12월)</option>
						<option value="2018">2018년 전반기(1월-6월)</option>
						<option value="2017_2">2017년 후반기(7월-12월)</option>
						<option value="2017">2017년 전반기(1월-6월)</option>
						<option value="2016_2">2016년 후반기(6월-12월)</option>
						<option value="2016">2016년 전반기(1월-6월)</option>
						
					</select>
					<font color=blue>전반기:(1월-6월) 후반기:(7월-12월)</font>
					</td>
			</tr -->
		</tbody>
	</table>
	</form>
		<div class="btn_area">
					<a onclick="searchcomp();" class="search">검색</a>
					<a onclick="gotoBid();" class="search">입찰정보</a>
					</div>
</div></div>
<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
  <img src='http://uloca.net/g2b/loading3.gif' width='100px' height='100px'>
</div>
<font color='blue' size=2>검색 후 사업자 등록번호를 누르면 해당 업체의 응찰 이력이 나옵니다.</font/>
<br>
<div id = tablist></div>
<!--
<center><div style='font-size:18px;'><font color='blue'><strong>- 업체 응찰 정보 -</strong></font></div></center>
<div id=totalrec>total record=<?=count($item)?></div>
<table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="10%;" >번호</th>
		<th scope="cols" width="20%;" >사업자 등록번호</th>
		<th scope="cols" width="50%;">업체명</th>
        <th scope="cols" width="10%;">대표자</th>
        <th scope="cols" width="10%;">응찰건수</th>
		
    </tr>

< ?
$conn = new mysqli('localhost', 'uloca22', 'w3m69p21!@', 'uloca22');
mysqli_set_charset($conn, 'utf8');

$sql = "select a.compno , a.cnt, b.compname, b.repname from
(select count(idx) as cnt,compno  from openBidSeq group by compno) a,  openCompany b
where a.compno=b.compno and a.cnt>1 order by a.cnt desc limit 0,20";
$result = $conn->query($sql);
$i=1;

while ($row = $result->fetch_assoc()) {
	if ($i % 2 == 1) {
		$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'bidInfo('.$row['compno'].')\'>'.$row['compno'].'<a></td>';
		$tr .= '<td>'.$row['compname'].'</td>';
		$tr .= '<td style="text-align: center;">'.$row['repname'].'</td>';
		$tr .= '<td align=right>'.number_format($row['cnt']).'</td>';
		$tr .= '</tr>';
	} else {
		$tr = "<tr>";
		$tr .= '<td class="even" style="text-align: center;">'.$i.'</td>';
		$tr .= '<td class="even" style="text-align: center;"><a onclick=\'bidInfo('.$row['compno'].')\'>'.$row['compno'].'<a></td>';
		$tr .= '<td class="even">'.$row['compname'].'</td>';
		$tr .= '<td class="even" style="text-align: center;">'.$row['repname'].'</td>';
		$tr .= '<td class="even" align=right>'.number_format($row['cnt']).'</td>';
		$tr .= '</tr>';
	}
	echo $tr;
	$i += 1;
}
$i--;
echo '<table></div></div>';
?  >
<script>
document.getElementById('totalrec').innerHTML = 'total record=<?=$i?>';
</script -->
<?
}
add_shortcode('bidCompany','bidCompanyShortCode');

?>

<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

//Snoopy.class.php를 불러옵니다
//echo $_SERVER['DOCUMENT_ROOT'].'<br>';
require($_SERVER['DOCUMENT_ROOT'].'/Snoopy.class.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/parseG2B.php');
 
//스누피를 생성해줍시다
$snoopy = new Snoopy;
$parseG2B = new parseG2B;

$servername = "localhost";
$username = "uloca22";
$password = "w3m69p21!@";
$dbname = "uloca22";
$tableG2BLIST = "G2BLIST";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
} 

?>
<!DOCTYPE html>
<html>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!-- iframe src="http://www.g2b.go.kr/index.jsp" name="g2b" width=800 height=300></iframe -->
<!-- script type="text/javascript" src="js/datepicker.js"></script -->
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="js/morepage.js"></script>
    
<script>
function search() {
	
	var form = document.myForm;
	
		//form.fromDt1.value.replace("-", "/");
		
		//form.fromDt1.value.replace("-", "/");
		//alert(form.fromDt1.value+"/"+form.toDt1.value);
		form.submit();
		//document.getElementById("myForm").submit();
}
$(function() {
  $( "#fromDt1" ).datepicker({
    dateFormat: 'yy-mm-dd'
  });
  $( "#toDt1" ).datepicker({
    dateFormat: 'yy-mm-dd'
  });
});
</script>
<?

$sql = "select dtin,bid from G2BLIST order by dtin asc limit 0,1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    if ($row = $result->fetch_assoc()) {
        //echo "최소 bid: " . $row["bid"].  "<br>";
		$minbid= $row["bid"];
		$mindt = $row["dtin"];
    }
	$sql = "select dtin,bid from G2BLIST order by dtin desc limit 0,1";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
    // output data of each row
    if ($row = $result->fetch_assoc()) {
        //echo "최대 bid: " . $row["bid"].  "<br>";
		$maxbid= $row["bid"];
		$maxdt = $row["dtin"];
		}
	}
} else {
    echo "0 results";
}
?>
<br><br>
<form action="makelist.php" name=myForm id=myform>
<div style="background-color:lightblue;  top: 100px; left:50px" ><? echo "최소 공고일: ". $mindt." bid: " .$minbid;?></div>
<div style="background-color:lightblue;  top: 140px; left:50px"><? echo "최대 공고일: ". $maxdt." bid: " .$maxbid;?></div>
<br>
&nbsp;&nbsp;&nbsp;일자 <input autocomplete="off" id="fromDt1" name="fromDt1" type="text" maxlength="16" title="공고게시시작일" style="align:center" value='<?=$fromDt1?>'/>
~
<input autocomplete="off" id="toDt1" name="toDt1" type="text" maxlength="16" class="w70" title="공고게시종료일"  style="align:center" value='<?=$toDt1?>'/>
    <button type="button" class="btn_agree" onclick="search();" title="검색"><span class="blind">G2b->ULOCA</span></button> 최대 공고일보다 작은순으로 10 레코드씩 검색
</form>
 <br><br>

<table width=990 align=center>
<tr>
 <td width=40 style="background-color:lightblue;">업무</td>
 <td width=80 style="background-color:lightblue;">공고번호-차수</td>
 <td width=30 style="background-color:lightblue;">분류</td>
 <td width=160 style="background-color:lightblue;">공고명</td>
 <td width=160 style="background-color:lightblue;">공고기관</td>	
 <!-- td width=120>수요기관</td -->
 <td width=100 style="background-color:lightblue;">계약방법</td>	
 <td width=90 style="background-color:lightblue;">입력일시</td>
 <td width=40 style="background-color:lightblue;">입력</td>
</tr>
<?
$fromDt1 = str_replace("-", "/", $fromDt1);
$toDt1 = str_replace("-", "/", $toDt1);
//echo ('fromdt1='.$fromDt1. ' todt1='.$toDt1);
if ($fromDt1 == "") exit;
if ($toDt1 == "") exit;

// <input id="fromBidDt" name="fromBidDt" type="text" maxlength="10" class="w70" title="공고게시시작일"
//<input id="toBidDt" name="toBidDt" type="text" maxlength="10" class="w70" title="공고게시종료일" onblur="util_checkDate(this,'/');" />
//   <a href="javascript:search7();" class="btn_mdl"><strong>검색</strong></a>
/*function search7(){
	
   	var param = "?";
   	param += "estmtReqNo="+document.searchForm5.estmtReqNo.value;
   	
	document.searchForm5.method="post";
	document.searchForm5.action="/pt/menu/selectSubFrame.do?framesrc=/pt/menu/frameTgong.do?url=http://www.g2b.go.kr:8401/gtob/all/pr/estimate/reqEstimateOpenG2BList.do"+param;
	document.searchForm5.submit();
}
*/
?>

<?
$listUrl = 'http://www.g2b.go.kr:8101/ep/tbid/tbidList.do?taskClCds=&bidNm=&searchDtType=1&fromBidDt='.$fromDt1.'&toBidDt='.$toDt1.'&fromOpenBidDt=&toOpenBidDt=&radOrgan=1&instNm=&area=&regYn=Y&bidSearchType=1&searchType=1';
$snoopy->fetch($listUrl);
//iconv -f EUC-KR -t UTF8 $snoopy->results > $test_utf
$text_utf = iconv("EUC-KR","UTF-8", $snoopy->results);
//echo $text_utf; //$snoopy->results;

//include "../classphp/parseG2B.php";
//$parseG2B    = new parseG2B;
//$parseG2B->rec = 0;
//$parseG2B->suc = 0;
//echo '$parseG2B->suc='.$parseG2B->suc;
$parseG2B->makeInsert($text_utf,$tableG2BLIST,$conn);



$conn->close();
?>
</table>
</html>
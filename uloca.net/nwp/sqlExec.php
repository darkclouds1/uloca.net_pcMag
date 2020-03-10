<?
@extract($_GET);
@extract($_POST);
ob_start();
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
//http://uloca.net/nwp/dailyDataFill.php?startDate=20180930&endDate=20180930&pss=%EB%AC%BC%ED%92%88
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();
//echo $_POST['startDate'].'/'.$startDate;
$dur = '_2018_2';
if ($_POST['startDate'] != '') {
	$startDate1 = str_replace('-','',$_POST['startDate']);
	$startDate1 = substr($startDate1,0,6);
	if ($startDate1 >= '201601' && $startDate1 < '201607') {
		$dur = '_2016';
	} else if ($startDate1 >= '201607' && $startDate1 < '201701') {
		$dur = '_2016_2';
	} else if ($startDate1 >= '201701' && $startDate1 < '201707') {
		$dur = '_2017';
	} else if ($startDate1 >= '201707' && $startDate1 < '201801') {
		$dur = '_2017_2';
	} else if ($startDate1 >= '201801' && $startDate1 < '201807') {
		$dur = '_2018';
	} else if ($startDate1 >= '201807' && $startDate1 < '201901') {
		$dur = '_2018_2';
	} else if ($startDate1 >= '201901' && $startDate1 < '201907') {
		$dur = '_2019';
	} else if ($startDate1 >= '201907' && $startDate1 < '202001') {
		$dur = '_2019_2';
	} else $dur = '_2018_2';
}
$openBidInfo = 'openBidInfo'.$dur;
$openBidSeq = 'openBidSeq'.$dur;
//echo $openBidInfo.'/'.$startDate1;



?>
<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="http://uloca.net/g2b/css/g2b.css" />
<link rel="stylesheet" href="http://uloca.net/jquery/jquery-ui.css">
<script src="http://uloca.net/jquery/jquery.min.js"></script>
<script src="http://uloca.net/jquery/jquery-ui.min.js"></script>
<script src="http://uloca.net/g2b/g2b.js"></script>
<script>
function doit() {
	frm = document.myForm;
	frm.submit();
}
function donextday() {
	frm = document.myForm;
	if (frm.startDate.value >= '<?=$lastdt?>') return;
	//alert(frm.contn.checked);
	if (frm.contn.checked) {
		dts=dateAddDel(frm.startDate.value, 1, 'd');
		frm.startDate.value = dts;
		frm.endDate.value = dts;
		frm.submit();
	} else {frm.contn.checked = false;
		return;
	}
}
function chageLangSelect(){
    var sql1 = document.getElementById("sql1");
    // alert(sql1.options[sql1.selectedIndex].text);
    // select element에서 선택된 option의 value가 저장된다.
    //var selectValue = langSelect.options[langSelect.selectedIndex].value;
 
    // select element에서 선택된 option의 text가 저장된다.
    document.getElementById("sql2").value = sql1.options[sql1.selectedIndex].text;
}
</script>
<body >
<form action="" name="myForm" id="myform" method="post" >
<div id="contents">
<div class="detail_search" >


<table align=center cellpadding="0" cellspacing="0" width="700px">
		<colgroup>
			<col style="width:20%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>

		<tr>
				<th>기간</th>
				<td>
					<div class="calendar">
						
						<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?=$startDate?>" style="width:76px;" readonly='readonly' onchange='document.getElementById("endDate").value=this.value'/>
						
						~
						<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?=$endDate?>" style="width:76px;"  readonly='readonly'/>
						
				</div> 
				</td>
			</tr>
			<tr>
				<th>SQL1</th>
				<td>
					<select name="sql1" id="sql1" onchange="chageLangSelect()">
					  <option value="">select bidNtceNo,opengDt,bidNtceNm,bidwinnrNm from openBidInfo_2018_2 where opengDt>'2018-10-04'</option>
					  <option value="">select * from openBidInfo_2018_2 where opengDt>'2018-10-04'</option>
					  <option value="">Mercedes</option>
					  <option value="">Audi</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>SQL2</th>
				<td>
					<input autocomplete="off" type="text" name="sql2" id="sql2" value="<?=$sql2?>" style="width:700px;"/>
				</td>
			</tr>
		</table>
		<div class="btn_area">
		<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
		<a onclick="doit();" class="search">실행</a>
	</div>	
	</div>
	</div>
</form>
<?
if ($sql2 == '') exit;
?>
<center><div style='font-size:14px; color:blue;font-weight:bold'>- SQL 실행 결과 -</div></center>
<div id='totalRecords'>total records=<?=$countItem?></div>

<table class="type10" id="specData">
<?
$result = $conn->query($sql2);
if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
$col = mysqli_num_fields($result);
echo 'col 수 = '.$col;
if ($col>16) $col = 16;
$i = 1;
while ($row = mysqli_fetch_row($result) ) { //$result->fetch_assoc()) {
	$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
	for ($k=0; $k<=$col; $k++) { 
		$tr .= '<td>'.$row[$k].'</td>';
	}
		$tr .= '</tr>';

	echo $tr;

	$i++;
}
$i --;
echo "</table>";
?>
<script>
document.getElementById("totalRecords").innerHTML = 'total records= '+<?=$i?>;
</script>
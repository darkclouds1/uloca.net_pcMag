<?
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
//http://uloca.net/nwp/dailyDataFill.php?startDate=20180930&endDate=20180930&pss=%EB%AC%BC%ED%92%88
@extract($_GET);
@extract($_POST);
ob_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();


$logdb = 'logdb';

if ($startDate == '') $startDate = date("Y-m-d"); //$today;
if ($endDate == '') $endDate = date("Y-m-d"); //$today;

$cnt = $dbConn->countLog($conn,$_SERVER['REMOTE_ADDR']);
echo 'my ip='.$_SERVER['REMOTE_ADDR'].' cnt = '.$cnt;
?>
<!DOCTYPE html>
<html>
<head>
<title>LOG Viewer</title>
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
</script>
<form action="logviewer.php" name="myForm" id="myform" method="post" >
<div id="contents">
<div class="detail_search" >


<table align=center cellpadding="0" cellspacing="0" width="700px">
		<colgroup>
			<col style="width:20%;" />
			<col style="width:30%;" />
			<col style="width:20%;" />
			<col style="width:auto;" />
		</colgroup>
		<tbody>

		<tr>
				<th style='background-color:#f5f5f5; text-align:center;'>일자</th>
				<td colspan=3>&nbsp;&nbsp;
					<div class="calendar">
						 &nbsp;&nbsp;
						<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?=$startDate?>" style="width:76px;" readonly='readonly'/>
						
						~
						<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?=$endDate?>" style="width:76px;"  readonly='readonly'/>
						
						<div id="datepicker"></div>	
				</div>
				</td>
			</tr>
			<tr>
				<th style='background-color:#f5f5f5; text-align:center;'>id</th>
				<td>&nbsp;&nbsp;
					
						<input autocomplete="on" type="text" maxlength="10" name="userid" id="userid" value="<?=$userid?>" style="width:126px;" />
				</td>
				<th style='background-color:#f5f5f5; text-align:center;'>ip</th>
				<td>&nbsp;&nbsp;
					
						<input autocomplete="on" type="text" maxlength="20" name="ipaddr" id="ipaddr" value="<?=$ipaddr?>" style="width:126px;" />
				</td>
			</tr>
		</table>
		<div class="btn_area">
		<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
		<a onclick="doit();" class="search">보기</a>
	</div>	
	</div>
	</div>
</form>

<center><div style='font-size:14px; color:blue;font-weight:bold'>- 로그 보기 -</div></center>
<div id='totalRecords'>total records=<?=$countItem?></div>

<table class="type10" id="specData">
<thead>
    <tr>
        <th scope="cols" width="5%;">순위</th>
		<th scope="cols" width="15%;">일시</th>
        <th scope="cols" width="10%;">ID</th>
        <th scope="cols" width="10%;">IP</th>
        <th scope="cols" width="30%;">접속URL</th>
        <th scope="cols" width="20%;">비고</th>
       <th scope="cols" width="5%;">pg</th>
       <th scope="cols" width="5%;">Key</th>
       
    </tr>
</thead>
<tbody>
<?
if ($startDate == '' && $userid == '' && $ipaddr == '') {
	exit;
}
if ($startDate != '' && $endDate != '') $sql = "SELECT * FROM logdb  where dt>='" .$startDate . " 00:00:00' and dt<='".$endDate." 23:59:59' and id <> 'uloca22'";
if ($userid != '') $sql .= " and id='".$userid."' ";
if ($ipaddr != '') $sql .= " and ip='".$ipaddr."' ";
$sql .= " order by dt desc ";
//echo 'sql='.$sql;
$result = $conn->query($sql);
$i = 1;
while ($row = $result->fetch_assoc()) {
	$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;">'.$row['dt'].'</td>';
		
		$tr .= '<td>'.$row['id'].'</td>';
		$tr .= '<td>'.$row['ip'].'</td>';
		$tr .= '<td>'.$row['pg'].'</td>';
		$tr .= '<td style="text-align: center;">'.$row['rmrk'].'</td>';
		$tr .= '<td style="text-align: center;">'.$row['pgDtlCD'].'</td>';
		$tr .= '<td style="text-align: center;">'.$row['keyDtlCD'].'</td>';

		$tr .= '</tr>';

	echo $tr;

	$i++;
}
$i --;
echo "</table>";
?>
<script>
document.getElementById('totalRecords').innerHTML = 'totalRecords='+<?=$i?>;
</script>
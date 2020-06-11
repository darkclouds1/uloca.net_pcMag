<?
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
@extract($_GET);
@extract($_POST);
ob_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

$openBidInfo == 'openBidInfo_2018_2';
$openBidSeq == 'openBidSeq_2018_2';


if ($startDate == '' || $endDate == '') {
	echo '날자가 없습니다.'; exit;
}
if ($pss == '' ) {
	echo '물품,공사,용역 구분이 없습니다.'; exit;
}
$startDate.= '0000'; //=$g2bClass->changeDateFormat($startDate);
$endDate  .= '2359'; //=$g2bClass->changeDateFormat($endDate);
echo 'startDate='.$startDate.' endDate='.$endDate;


//http://uloca.net/nwp/infoDupCheck.php?startDate=20181001&endDate=20181002&pss=물품
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
<center><div style='font-size:14px; color:blue;font-weight:bold'>- 입찰 정보 중복 체크 -</div></center>
<div id='totalRecords'>total records=<?=$countItem?></div>

<table class="type10" id="specData">
<thead>
    <tr>
        <th scope="cols" width="5%;">순위</th>
		<th scope="cols" width="15%;">공고번호</th>
        <th scope="cols" width="50%;">공고명</th>
        <th scope="cols" width="15%;">차수</th>
    </tr>
</thead>
<tbody>
<?
$sql = "select bidNtceNo,bidNtceOrd,bidNtceNm from ".$openBidInfo." group by bidNtceNo order by bidNtceNo,bidNtceOrd";
$sql = "select bidNtceNo,bidNtceOrd,bidNtceNm,cnt from (select bidNtceNo,count(*) cnt from ".$openBidInfo." group by bidNtceNo) where cnt>1 order by bidNtceNo,bidNtceOrd";
select a.bidNtceNo,a.bidNtceOrd,a.bidNtceNm,b.cnt from openBidInfo_2018_2 a,(select bidNtceNo,count(bidNtceNo) as cnt from openBidInfo_2018_2 group by bidNtceNo order by bidNtceNo, cnt desc  ) b where a.bidNtceNo=b.bidNtceNo and b.cnt>1 order by bidNtceNo,bidNtceOrd

select * from (select bidNtceNo,count(bidNtceNo) as cnt from openBidInfo_2018_2 group by bidNtceNo) a where a.cnt>1
echo $sql;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()  ) {
	$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</td>';
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td>'.$arr['bidNtceOrd'].'</td>';
		$tr .= '</tr>';

	echo $tr;
}
?>
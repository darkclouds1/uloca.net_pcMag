<?
@extract($_GET);
@extract($_POST);
session_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); 
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
//bidNtceNo, bidNtceOrd

$conn = $dbConn->conn(); //
// --------------------------------- log
$rmrk = '';
$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log
//1010117311 풍양전자 김재열

//mysqli_set_charset($conn, 'utf8');
/*
if ($compno !='' && strlen($compno) == 10) {
	$sql = 'select * from openCompany where compno=\''.$compno.'\'';
} else {
	$sql = 'select * from openCompany where compname=\'%'.$compno.'%\'';
}
$result0 = $conn->query($sql);
//$compno =$row['compno'];	// 사업자등록번호
$compname ='없는 사업자등록번호'; // 업체명
$repname ='';	// 대표자
if ($row = $result0->fetch_assoc()) {
	$compno =$row['compno'];	// 사업자등록번호
	$compname =$row['compname']; // 업체명
	$repname =$row['repname'];	// 대표자
} */
if ($duration == '') $duration = '2018_2';
$openBidSeq = 'openBidSeq_'.$duration;
$openBidInfo = 'openBidInfo_'.$duration;
$_SESSION['duration']     = $duration;

if ($duration == 'all') { // 전체
	if ($compno != '') {
		$qry = $compno; //'a.compno=\''.$compno.'\'';
		//$sql = "select a.compno , a.cnt, b.compname, b.repname from (select count(idx) as cnt,compno  from ".$openBidSeq." group by compno) a,  openCompany b where a.compno=b.compno and a.compno= ?  order by b.compname asc";
		$sql = "select c.compno,sum(c.cnt) as cnt,c.compname, c.repname from ( ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2016 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and a.compno= ? ";
		$sql .= "union  ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2016_2 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and a.compno= ? ";
		$sql .= "union ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2017 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and a.compno= ? ";
		$sql .= "union ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2017_2 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and a.compno= ? ";
		$sql .= "union ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2018 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and a.compno= ? ";
		$sql .= ") c group by c.compno ";
		//b.compname like \'%?%\'
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);

		$stmt->bind_param("sssss", $qry, $qry, $qry, $qry, $qry);
	}
	else {
		$qry = '%'.$compname.'%';
		$sql = "select x.compno,x.compname,x.repname, (x.bcnt+x.ccnt+x.dcnt+x.ecnt+x.fcnt+x.gcnt) cnt from ( ";
		$sql .= "select a.*, IFNULL(b.cnt,0) as bcnt, IFNULL(c.cnt,0) ccnt, IFNULL(d.cnt,0) dcnt, IFNULL(e.cnt,0) ecnt, IFNULL(f.cnt,0) fcnt, IFNULL(g.cnt,0) as gcnt from openCompany a  "; 
		$sql .= "left outer join (select count(idx) cnt,compno from openBidSeq_2018_2 group by compno) b on a.compno = b.compno ";
		$sql .= "left outer join (select count(idx) cnt,compno from openBidSeq_2018 group by compno) c on a.compno = c.compno ";
		$sql .= "left outer join (select count(idx) cnt,compno from openBidSeq_2017_2 group by compno) d on a.compno = d.compno ";
		$sql .= "left outer join (select count(idx) cnt,compno from openBidSeq_2017 group by compno) e on a.compno = e.compno ";
		$sql .= "left outer join (select count(idx) cnt,compno from openBidSeq_2016_2 group by compno) f on a.compno = f.compno ";
		$sql .= "left outer join (select count(idx) cnt,compno from openBidSeq_2016 group by compno) g on a.compno = g.compno ";
		$sql .= "where a.compname like ? ";
		$sql .= ") x ";
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);

		$stmt->bind_param("s", $qry);
		//$sql = "select a.compno , a.cnt, b.compname, b.repname from (select count(idx) as cnt,compno  from ".$openBidSeq." group by compno) a,  openCompany b where a.compno=b.compno and compname like ?  order by b.compname asc";
		/*
		$sql = "select c.compno,sum(c.cnt) as cnt,c.compname, c.repname from ";
		$sql .= "( ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2016 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and b.compname like ? ";
		$sql .= "union  ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2016_2 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and b.compname like ? ";
		$sql .= "union ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2017 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and b.compname like ? ";
		$sql .= "union ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2017_2 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and b.compname like ? ";
		$sql .= "union ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2018 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and b.compname like ? ";
		$sql .= "union ";
		$sql .= "select a.compno , a.cnt, b.compname, b.repname from ";
		$sql .= "(select count(idx) as cnt,compno  from openBidSeq_2018_2 group by compno) a,  openCompany b ";
		$sql .= "where a.compno=b.compno and b.compname like ? ";
		$sql .= ") c group by c.compno ";
		
		//b.compname like \'%?%\'
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);

		$stmt->bind_param("ssssss", $qry, $qry, $qry, $qry, $qry, $qry);*/
	}
} else { // 반기별
	if ($compno != '') {
		$qry = 'a.compno=\''.$compno.'\'';
		$sql = "select a.compno , a.cnt, b.compname, b.repname from (select count(idx) as cnt,compno  from ".$openBidSeq." group by compno) a,  openCompany b where a.compno=b.compno and a.compno= ? ";
		//b.compname like \'%?%\'
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);

		$stmt->bind_param("s", $qry);
	}
	else {
		$qry = '%'.$compname.'%';
		$sql = "select a.compno , a.cnt, b.compname, b.repname from (select count(idx) as cnt,compno  from ".$openBidSeq." group by compno) a,  openCompany b where a.compno=b.compno and compname like ? ";
		//b.compname like \'%?%\'
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);

		$stmt->bind_param("s", $qry);
		
	}
}
/* $sql = "select a.compno , a.cnt, b.compname, b.repname from
(select count(idx) as cnt,compno  from ".$openBidSeq." group by compno) a,  openCompany b
where a.compno=b.compno and ".$qry."  order by b.compname asc"; */

//echo 'sql='.$sql.' parm='.$qry;
$stmt->execute();

//$result = $stmt->get_result();
$fields = $g2bClass->bindAll($stmt);


$i=1;
?>
<center><div style='font-size:18px; color:blue;font-weight:bold'>- 응찰 업체 정보 -</div></center>
<!-- div id=totalrec>total record=<?=mysqli_num_rows( $result )?></div -->
<div id=totalrec>total record=<?=$stmt->num_rows?></div>
<table class="type10" id="bidData">
<thead>
    <tr>
        <th scope="cols" width="10%;" >번호</th>
		<th scope="cols" width="20%;" >사업자 등록번호 <a onclick="sortTD ( 1 )">▲</a><a onclick="reverseTD ( 1 )">▼</a></th>
		<th scope="cols" width="50%;">업체명 <a onclick="sortTD ( 2 )">▲</a><a onclick="reverseTD ( 2 )">▼</a></th>
        <th scope="cols" width="10%;">대표자 <a onclick="sortTD ( 3 )">▲</a><a onclick="reverseTD ( 3 )">▼</a></th>
        <th scope="cols" width="10%;">응찰건수 <a onclick="sortTD ( 4 )">▲</a><a onclick="reverseTD ( 4 )">▼</a></th>
		
    </tr>
</thead>
 <tbody>
<?
while ($row = $g2bClass->fetchRowAssoc($stmt, $fields)) { //while ($row = $result->fetch_assoc()) {
	$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;"><a onclick=\'bidInfo('.$row['compno'].')\'>'.$row['compno'].'<a></td>';
		$tr .= '<td><a onclick=\'compInfo('.$row['compno'].')\'>'.$row['compname'].'</a></td>';
		$tr .= '<td style="text-align: center;">'.$row['repname'].'</td>';
		$tr .= '<td align=right>'.number_format($row['cnt']).'</td>';
		$tr .= '</tr>';

	echo $tr;
	$i += 1;
}
$i--;
echo '</tbody><table>';

?>




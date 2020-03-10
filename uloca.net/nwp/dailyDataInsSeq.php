<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;

$conn = $dbConn->conn(); 
mysqli_set_charset($conn, 'utf8');
if ($openBidInfo == '' || $openBidSeq == '') {
	echo '테이블 명이 없습니다.';
	exit;
}
if ($openBidSeq_tmp == '') {
	echo 'openBidSeq_tmp 를 입력하세요.';
	exit;
}
if ($bidNtceNo == '') {
	echo '공고번호가 없습니다.';
	exit;
}
if ($idx0 == '') {
	$sql = 'select max(bidIdx) as idx from '.$openBidSeq  ;
	$result0 = $conn->query($sql);
	if ($row = $result0->fetch_assoc()) {
		$idx0 = $row['idx'];
		if ($idx0 == 'NULL') $idx0 = 0;
	}
}
$idx0 ++;
//$idx0=58667; //45478; //34473; //34290;
$sql = 'select max(idx) as idx from '.$openBidSeq  ;
$result0 = $conn->query($sql);
if ($row = $result0->fetch_assoc()) {
	$idx = $row['idx'];
	if ($idx == 'NULL') $idx = 0;
}

function insertForecastInfo($conn,$bidNtceNo, $compno1, $tuchalrate1,$tuchalamt1,$compno2, $tuchalrate2,$tuchalamt2,$tuchalcnt,$pss) {
	
		//$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
		$sql = 'insert into forecastData ( bidNtceNo, compno1, tuchalrate1,tuchalamt1,compno2, tuchalrate2,tuchalamt2,tuchalcnt,pss)';

		$sql .= "VALUES ( '".$bidNtceNo."', '". $compno1 ."', '" .$tuchalrate1. "','" . $tuchalamt1 ."', '". $compno2 ."', '" .$tuchalrate2. "','" . $tuchalamt2 .  "','" . $tuchalcnt .  "','" . $pss . "')";

		//	echo($sql);
		if ($sql != '') {
			if ($conn->query($sql) === TRUE) {}
			//else echo 'error sql='.$sql.'<br>';
		}
		return true;
	
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}

function updateLcnsLmtNm($g2bClass,$conn,$openBidInfo,$bidNtceNo) {
	$responseLimit = $g2bClass->getSvrDataLimit12($bidNtceNo,'00','2'); //getSvrDataLimit($startDate,$endDate,$numOfRows,'1','1');
	$jsonLimit = json_decode($responseLimit, true);
	$itemLimit = $jsonLimit['response']['body']['items'];
	$lcnsLmtNm='';
	foreach($itemLimit as $arr ) $lcnsLmtNm .= $arr['lcnsLmtNm'].',';
	if ($lcnsLmtNm != '') {
		$lcnsLmtNm = substr($lcnsLmtNm,0,strlen($lcnsLmtNm)-1);
		$sql = "update ".$openBidInfo." set lcnsLmtNm = '".$lcnsLmtNm."' where bidNtceNo='".$bidNtceNo."';";
		//echo $sql.'<br>'; permsnIndstrytyList
		$conn->query($sql);
	}
	return $lcnsLmtNm;
}

$idx ++;
//$idx ++;



$start = 0;
//$idx = 0;
$cnt=0;
//$sql = 'select idx, opengDt, bidNtceNo,bidNtceOrd from '.$openBidInfo.' where idx>\''.$idx0.'\' order by idx limit '.$norec ;
//$msg = $sql.'<br>';
//$result = $conn->query($sql);
$tCnt=0;
$i=1;
//while ($row = $result->fetch_assoc()) {
	//$bidNtceNo = $row['bidNtceNo'];
	$bidNtceOrd = ''; //$row['bidNtceOrd'];
	//$bididx = $bididx;
	
	// 입찰 결과
	//$response1 = $g2bClass->getRsltData($bidNtceNo,$bidNtceOrd); 
	//$json1 = json_decode($response1, true);
	//$item1 = $json1['response']['body']['items'];
	$item1 = $g2bClass->getRsltDataAll($bidNtceNo,$bidNtceOrd); // 2018-12-11
	$cnt = count($item1);
	//var_dump($response1);
	//echo 'idx='.$row['idx'].' 개찰일시='.$row['opengDt'].' bidNtceNo='.$bidNtceNo.' bidNtceOrd='.$bidNtceOrd.' count='.$cnt.'<br>';
	$msg .= 'idx='.$row['idx'].' 개찰일시='.$row['opengDt'].' bidNtceNo='.$bidNtceNo.' count='.$cnt.'<br>';
	$ii=1;
	$tcnt += $cnt;
	//echo $cnt;
	if ($cnt>0) {
		$compno1='';
		$tuchalrate1='';
		$tuchalamt1='';
		$compno2='';
		$tuchalrate2='';
		$tuchalamt2='';
		$tuchalcnt = $g2bClass->getRsltDataTotalCount($bidNtceNo,$bidNtceOrd); //$json1['response']['body']['totalCount']; //count($item1); 
	foreach($item1 as $arr ) { // bidNtceNo 별
		$idx   += 1;
		$rmrk = addslashes($arr['rmrk']);
		if (trim($rmrk) == '') $rmrk = $ii; // 1순위 낙찰

		// $openBidInfo 에 1순위 정보 추가 
		if ($ii == 1) {
			$sql = "select bidwinnrBizno  from ".$openBidInfo." where bidNtceNo = '".$arr['bidNtceNo']."' ";
			$result0 = $conn->query($sql);
			//echo $sql;
			if ($row = $result0->fetch_assoc()) {
				if ($row['bidwinnrBizno'] == '') {
				$sql = "update ".$openBidInfo." set ";
				$sql .= " prtcptCnum = '". $cnt . "', ";
				$sql .= "bidwinnrNm = '". addslashes($arr['prcbdrNm']) . "', bidwinnrBizno = '". $arr['prcbdrBizno'] . "', ";
				$sql .= "sucsfbidAmt = '". $arr['tuchalamt'] . "', sucsfbidRate = '". $arr['tuchalrate'] . "', ";
				$sql .= "rlOpengDt = '". $arr['tuchaldatetime'] . "', bidwinnrCeoNm = '". $arr['prcbdrCeoNm'] . "' ";

				$sql .=" where bidNtceNo='".$arr['bidNtceNo']."';";
				$conn->query($sql);
				//echo $sql;
				}
			}
			$compno1 = $arr['prcbdrBizno'];
			$tuchalrate1 = $arr['bidprcrt'];
			$tuchalamt1 = $arr['bidprcAmt'];
		}
		if ($arr['rmrk'] == '낙찰하한선 미달' && (int)$tuchalamt2 < (int)$arr['bidprcAmt']) {
			$compno2 = $arr['prcbdrBizno'];
			$tuchalrate2 = $arr['bidprcrt'];
			$tuchalamt2 = $arr['bidprcAmt'];
		}

		$sql = "delete from ".$openBidSeq." where bidNtceNo = '".$arr['bidNtceNo']."' "; // 기존 데이타 삭제
		$conn->query($sql);

		if (true) { //is_numeric($rmrk) && (int)$rmrk >= 1 && (int)$rmrk <=5) {
			$sql = "select count(*) cnt from ".$openBidSeq." where bidNtceNo = '". $arr['bidNtceNo'] . "' and compno = '".$arr['prcbdrBizno']. "'";
			$result0 = $conn->query($sql);
			$dup = 1; // forecast 안함
			if ($row = $result0->fetch_assoc() && $row['cnt'] < 1) {
				// 2019-01-12 추가 ---------------------------중복 안되게 있으면 업데이트---------------------------------------------
				$dup = 1;
				$sql = "select bidNtceOrd from ".$openBidSeq." where bidNtceNo = '".$arr['bidNtceNo']."' and compno='".$arr['prcbdrBizno']."'; ";
				$result = $conn->query($sql);
				if ($row = $result->fetch_assoc()  ) {
					$sql = "update ".$openBidSeq." set tuchalamt ='". $arr['bidprcAmt'] . "', ";
					$sql .= "tuchalrate = '". $arr['bidprcrt'] . "', tuchaldatetime = '". $arr['bidprcDt'] . "', ";
					$sql .= "remark = '". $remark . "' ";
					$sql .=" where bidNtceNo='".$arr['bidNtceNo']."' and compno='".$arr['prcbdrBizno']."';";
				} else { 

				// 2019-01-12 추가 -------------------------------------------------------------------------
				
					$sql = 'insert into '.$openBidSeq_tmp.' ( bidNtceNo,bidNtceOrd, compno, tuchalamt,tuchalrate, tuchaldatetime	,remark, bidIdx, ModifyDT)';
					$sql .= " VALUES ( '".$arr['bidNtceNo']."', '". $arr['bidNtceOrd'] ."', '" .$arr['prcbdrBizno']. "','" . $arr['bidprcAmt'] . "','" . $arr['bidprcrt'] . "',";
					$sql .= "'".$arr['bidprcDt']."', '".$rmrk."','".$bididx."', now() )";
				}
					//echo '<br>'.$sql.'<br>';
				//$conn->query($sql);
				if ($conn->query($sql) === TRUE) {}
				else {
					//echo 'error sql='.$sql.'<br>';
					// $msg .= 'error sql='.$sql.'<br>'; // 다시 api query 한는 동안 잠금...
				}
				$sql = "select compno from openCompany where compno ='". $arr['prcbdrBizno'] . "'";
				$result0 = $conn->query($sql);
				if ($row = $result0->fetch_assoc()) {
					
				} else {
					$sql = 'insert into openCompany (compno,compname, repname, phone)';
					$sql .= " VALUES ('" . $arr['prcbdrBizno'] . "', '".addslashes($arr['prcbdrNm'])."', '". addslashes($arr['prcbdrCeoNm']) ."', '')";
					if ($conn->query($sql) === TRUE) {}
					else {
						//echo 'error Company sql='.$sql.'<br>';
						$msg .= 'error Company sql='.$sql.'<br>';
					}
				}
			}
		}
		$ii ++;
		
		//var_dump($arr);
	} // end for
	} // end if ($cnt>0)

	$lcnsLmtNm = updateLcnsLmtNm($g2bClass,$conn,$openBidInfo,$bidNtceNo);

	$year = substr($openBidSeq,11,4);
	if ($year == '2018') $year = '12';
	$sql = "update workdate set last='".$bidNtceNo."' where workname='getBid".$year."' ";
	$conn->query($sql);
	echo $year;
	if ($dup == 0 ) insertForecastInfo($conn,$bidNtceNo, $compno1, $tuchalrate1,$tuchalamt1,$compno2, $tuchalrate2,$tuchalamt2,$tuchalcnt,$pss);
	
	//echo '<br>';
	$i++;
	echo $bidNtceNo.'| cnt=|'.$cnt.'| lcnsLmtNm=|'.$lcnsLmtNm.'|<br>';
//}
//echo ($idx-$cnt);
//echo $msg;
?>

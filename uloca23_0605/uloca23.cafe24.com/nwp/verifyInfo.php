<?
/*
일일자료 자동 수집 결과를 다시 검정/보완 하는 프로그램
2019-01-25 by HMJ
http://uloca23.cafe24.com/nwp/verifyInfo.php?startDate=20190102&endDate=20190103&openBidSeq=openBidSeq_2019

*/
@extract($_GET);
@extract($_POST);
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn(); 

if ($startDate == '' || $endDate == '') {
	echo 'startDate'.$startDate.' endDate='.$endData;
	exit;
}
if (strlen($startDate) == 8) $startDate .= '0000';
if (strlen($endDate) == 8) $endDate .= '2359';
echo 'startDate='.$startDate.' endDate='.$endDate.'<br>';
echo '시작 : '.date('Y-m-d H:i:s');
// -------------------------------------------------------------------------------------
//  입찰공고
//-------------------------------------------------------------------------------------
$inqryDiv=1; // /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
$kwd='';
$dminsttNm='';
$numOfRows = 990;
$pageNo=1;

$itemLimit = $g2bClass->tot_getSvrDataLimit($startDate,$endDate,$numOfRows,'1','1');
$itemLimit = json_decode($itemLimit, true);
//$itemLimit = $jsonLimit['response']['body']['items'];
foreach ($itemLimit as $key => $row) {
    $bidNtceNo[$key]  = $row['bidNtceNo'];
} 
if (count($itemLimit) > 1) array_multisort($bidNtceNo, SORT_DESC, $itemLimit); // 마김일시

//echo ('count='.count($itemLimit));
//var_dump($itemLimit);


function updateLcnsLmtNm($conn,$itemLimit) {
	$lcnsLmtNm='';
	$bidNtceNo='';
	//$js = [;
	foreach($itemLimit as $arr ) {
		
		if ($bidNtceNo != $arr['bidNtceNo']) {
			if ($lcnsLmtNm != '') {
				$lcnsLmtNm = substr($lcnsLmtNm,0,strlen($lcnsLmtNm)-1);
				//json data 만들기
				$sql = "update openBidInfo set lcnsLmtNm = '".$lcnsLmtNm."' where bidNtceNo='".$bidNtceNo."';";
				//echo $sql.'<br>';
				$conn->query($sql);
				//$lcnsLmtNm='';
			}
			$bidNtceNo = $arr['bidNtceNo'];
			$lcnsLmtNm = $arr['lcnsLmtNm'].',';
		} else $lcnsLmtNm .= $arr['lcnsLmtNm'].',';
		if ($lcnsLmtNm != '' && $bidNtceNo !='') {
			$sql = "update openBidInfo set lcnsLmtNm = '".$lcnsLmtNm."' where bidNtceNo='".$bidNtceNo."';";
			//echo $sql.'<br>';
			$conn->query($sql);
		}
	}
}
function verifyItem($conn,$item1) {
	foreach($item1 as $arr ) {
		$pss = '물품'; //$g2bClass->getDivNm($arr);
		// ------------------------------ insert
		$tf = insertopenBidInfo($conn,'openBidInfo',$arr,$pss);
		if ($tf == false) continue;
		$idx ++;
		// ------------------------------ insert
		
		$i++;
	}
}
// --------------------------------------- function insertopenBidInfo
function insertopenBidInfo($g2bClass,$conn,$openBidInfo, $arr,$pss) {
	if ($arr['bidNtceNo'] == '' || strlen($arr['bidNtceNo']) != 11 ) return false;
	$lcnsLmtNm = ''; // 뒤에 일괄적으로 업데이트 $g2bClass->findColumn($itemLimit,'bidNtceNo',$arr['bidNtceNo'],'lcnsLmtNm');
	$sql = "select bidNtceOrd from openBidInfo where bidNtceNo = '".$arr['bidNtceNo']."'; ";
	//echo $sql;
	$result = $conn->query($sql);
	//if ($row = $result->fetch_assoc()) $bidNtceOrd = $row['Ord'];
	if ($row = $result->fetch_assoc()  ) {
		/* 

		} */
		return false; // update
	} else {

		$sql = 'insert into '.$openBidInfo.' ( bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
		$sql .= 'reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
		$sql .= 'presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo,lcnsLmtNm,ModifyDT)';
		// $sql .= 'prtcptCnum,bidwinnrNm,bidwinnrBizno,sucsfbidAmt,sucsfbidRate,rlOpengDt,bidwinnrCeoNm) '; 
		$sql .= "VALUES ( '".$arr['bidNtceNo']."', '". $arr['bidNtceOrd'] ."', '" .addslashes($arr['bidNtceNm']). "','" . addslashes($arr['ntceInsttNm']) . "','" . addslashes($arr['dminsttNm']) . "',";
		$sql .= "'".$arr['opengDt']."', '".$pss."', ";
		$sql .= "'".$arr['reNtceYn']."', '".$arr['rgstTyNm']."', ";
		$sql .= "'".$arr['ntceKindNm']."', '".$arr['bidNtceDt']."', ";
		$sql .= "'".$arr['ntceInsttCd']."', '".$arr['dminsttCd']."', ";
		$sql .= "'".$arr['bidBeginDt']."', '".$arr['bidClseDt']."', ";
		$sql .= "'".$arr['presmptPrce']."', '".$arr['bidNtceDtlUrl']."', ";
		$sql .= "'".$arr['bidNtceUrl']."', '".$arr['sucsfbidLwltRate']."', ";
		$sql .= "'".$arr['bfSpecRgstNo']."', '".$lcnsLmtNm."',now() ) "; 

		if ($sql != '') {
			if ($conn->query($sql) === TRUE) {}
			//else clog 'error sql='.$sql.'<br>';
		}
		return true;
	}
	
}
function doprocess($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv) {
	//$item1 = $g2bClass->tot_getSvrData($bid,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv); 
	$item1 = $g2bClass->tot_getSvrData($bid,$startDate,$endDate,'','',990,1,$inqryDiv); /// "numOfRows": 990, "pageNo": 1, "totalCount": 768 }
	$item1 = json_decode($item1, true);
	$insCnt = 0;
	foreach($item1 as $arr ) {
		$tf = insertopenBidInfo($g2bClass,$conn,$openBidInfo, $arr,$pss);
		if ($tf) $insCnt++;
	}
	echo 'doprocess start='.$startDate.' end='.$endDate.' pss='.$pss.' count='.count($item1).' insert count='.$insCnt.'<br>';
}
function chkSeqno($g2bClass,$conn,$openBidSeq, $bidNtceNo) {
	$sql = "select count(*) cnt from ".$openBidSeq." where bidNtceNo='".$bidNtceNo."' ";
	//echo $sql.'<br>';
	$result = $conn->query($sql);
	$cnt = 0;
	if ($row = $result->fetch_assoc() ) $cnt = $row['cnt'];
	return $cnt;
}
function insertopenSeqInfo($g2bClass,$conn,$openBidSeq, $bidNtceNo,$bidNtceOrd) {
	$item1 = $g2bClass->getRsltDataAll($bidNtceNo,$bidNtceOrd); // 2018-12-11
	$cnt = count($item1);
	//echo 'insertopenSeqInfo cnt='.$cnt.'/'.$bidNtceNo.'-'.$bidNtceOrd;
	$bididx=0;
	if ($cnt>0) {
		foreach($item1 as $arr ) {
			$rmrk = $arr['opengRank'];
			if ($rmrk == '') $rmrk = $arr['rmrk'];
			$sql = 'insert into '.$openBidSeq.' ( bidNtceNo,bidNtceOrd, compno, tuchalamt,tuchalrate, tuchaldatetime	,remark, bidIdx)';
			$sql .= " VALUES ( '".$arr['bidNtceNo']."', '". $arr['bidNtceOrd'] ."', '" .$arr['prcbdrBizno']. "','" . $arr['bidprcAmt'] . "','" . $arr['bidprcrt'] . "',";
			$sql .= "'".$arr['bidprcDt']."', '".$rmrk."','".$bididx."')";
			//echo $sql.'<br>';
			$conn->query($sql);
		}
		return true;
	} else return false;
}
function doprocess2($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv,$openBidSeq) {
	//$item1 = $g2bClass->tot_getSvrData($bid,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv); 
	$item1 = $g2bClass->tot_getSvrData($bid,$startDate,$endDate,'','',990,1,$inqryDiv); /// "numOfRows": 990, "pageNo": 1, "totalCount": 768 }
	$item1 = json_decode($item1, true);
	$cnt=0;
	if (count($item1)>0) {
		foreach($item1 as $arr ) {
			$chk = chkSeqno($g2bClass,$conn,$openBidSeq, $arr['bidNtceNo']); // get count of 공고번호 개찰 개수
			if ($chk<1) {
				$tf = insertopenSeqInfo($g2bClass,$conn,$openBidSeq, $arr['bidNtceNo'],$arr['bidNtceOrd']); // 0면 
				if ($tf) $cnt++;
			}
		}
	}
	echo 'doprocess2 start='.$startDate.' end='.$endDate.' pss='.$pss.' count='.count($item1).' insert count='.$cnt.'<br>';
}
// 입찰결과 ----------------------------------------------------
echo '<br>입찰 정보 수집 -----------------------------<br>';
$inqryDiv = '1';
$bid = 'bidthing'; 
$pss = '물품';
doprocess($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv);

$bid = 'bidcnstwk'; 
$pss = '공사';
doprocess($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv);

$bid = 'bidservc'; 
$pss = '용역';
doprocess($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv);

$bid = 'hrcthing'; 
$pss = '사물';
doprocess($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv);

$bid = 'hrccnstwk'; 
$pss = '사공';
doprocess($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv);

$bid = 'hrcservc'; 
$pss = '사용';
doprocess($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv);


// -------------------------------------------------------------------------------------
//  개찰일시
//-------------------------------------------------------------------------------------
echo '<br>개찰 정보 수집 -----------------------------<br>';
$inqryDiv=2; // 2. 개찰일시 : 개찰일시(opengDt)

$bid = 'bidthing'; 
$pss = '물품';
doprocess2($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv,$openBidSeq);

$bid = 'bidcnstwk'; 
$pss = '공사';
doprocess2($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv,$openBidSeq);

$bid = 'bidservc'; 
$pss = '용역';
doprocess2($g2bClass,$conn,$bid,$pss,$startDate,$endDate,$inqryDiv,$openBidSeq);

updateLcnsLmtNm($conn,$itemLimit);

echo '<br>종료 : '.date('Y-m-d H:i:s');
?>
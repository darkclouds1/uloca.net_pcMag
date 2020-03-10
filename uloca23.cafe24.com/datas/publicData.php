<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
session_start();
//echo 'SESSION["ServerAddr"]='.$_SESSION['ServerAddr'];

ob_start();
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();

if ($bidthing == '1') { 	//물품 
	if ($bidhrc=='bid') {
		$pss = '입찰물품';
		$pss2 = '03';
		$func = 'bidthing';
	} elseif ($bidhrc=='hrc') {
		$pss = '사전물품';
		$pss2 = '06';
		$func = 'hrcthing';
	} else {
		$pss = '발주계획';
		$pss2 = '06';
		$func = 'plnthing';
	}
}
if ($bidcnstwk == '1') {	//공사 
	if ($bidhrc=='bid') {
		$pss = '입찰공사';
		$pss2 = '02';
		$func = 'bidcnstwk';
	} elseif ($bidhrc=='hrc') {
		$pss = '사전공사';
		$pss2 = '05';
		$func = 'hrccnstwk';
	} else {
		$pss = '발주계획';
		$pss2 = '05';
		$func = 'plncnstwk';
	}
}

if ($bidservc == '1') {		//용역 
	if ($bidhrc=='bid') {
		$pss = '입찰용역';
		$pss2 = '01';
		$func = 'bidservc';
	} elseif ($bidhrc=='hrc') {
		$pss = '사전공사';
		$pss2 = '04';
		$func = 'hrcservc';
	} else {
		$pss = '발주계획';
		$pss2 = '04';
		$func = 'plnservc';
	}
}

if ($compinfo != 1 && $kwd != '' && $dminsttNm != '' ) {
	$key = '03';
} elseif ($compinfo != 1 && $kwd != '' && $dminsttNm == '') {
	$key = '01';
} elseif ($compinfo != 1 && $kwd == '' && $dminsttNm != '') {
	$key = '02';
} elseif ($compinfo == 1 && $compname != '') {
	$key = '04';
	$pss = '';
	$pss2 = '';
}

// --------------------------------- log
$rmrk = 'compname='.$compname.' kwd='.$kwd.' dminsttNm='. $dminsttNm.'pss='.$pss; // '조건검색';
$dbConn->logWrite2($id,$_SERVER['REQUEST_URI'],$rmrk,$pss2,$key);
// --------------------------------- log

//exit;
/*if ($mobile == "Mobile") {
	//echo $mobile;
	header("Location: /datas/m_publicData.php?kwd=".$kwd."&startDate=".$startDate."&endDate=".$endDate."&chkBid=".$chkBid."&chkHrc=".$chkHrc."&bidthing=".$bidthing."&bidcnstwk=".$bidcnstwk."&bidservc=".$bidservc);
	//echo $mobile;
	//exit();
} */
//var_dump($compname);
//if(!isset($cntonce)) $cntonce = 1000;
//if(!isset($curStart)) $curStart = 0;	
//	echo $compinfo.'/'.$compname;
/* --------------------------------------------------------------------------------------------
	기업정보
--------------------------------------------------------------------------------------------- */
if ($compinfo == 1 && $compname != '') {
	
	$qry = '%'.$compname.'%';
	$kwd1 = explode(' ',$compname);
		$kwds = ' (';
		$kwd2 = ' (';
		for ($i=0;$i<sizeof($kwd1);$i++) {
			$kwds .= " b.compname like '%".$kwd1[$i]."%' and "; // or->and 로 바꿈 20190223
			$kwd2 .= " b.repname like '%".$kwd1[$i]."%' and "; // or->and 로 바꿈 20190223
			
		}
	$kwds = substr($kwds,0,strlen($kwds)-4). ' )';
	$kwd2 = substr($kwd2,0,strlen($kwd2)-4). ' )';
		
	//$sql = "select a.compno , b.compname, b.repname, a.cnt from (select count(idx) as cnt,compno  from ".$openBidSeq." group by compno) a,  openCompany b where a.compno=b.compno and compname like ? ";
	$sql = "SELECT b.compno, b.compname, b.repname FROM openCompany b WHERE ".$kwds. " or ".$kwd2. " limit ".$curStart.",".$cntonce;
	//$sql = "SELECT b.compno, b.compname, b.repname FROM openCompany b WHERE b.compname like ? or b.repname like ? limit ".$curStart.",".$cntonce;
	//$sql = "SELECT b.compno, b.compname, b.repname, COUNT(a.compno) cnt FROM openBidSeq a LEFT JOIN openCompany b ON a.compno=b.compno WHERE b.compname like ? GROUP BY a.compno";
	//echo($sql);
	
	/* select a.compno , a.compname, a.repname, b.cnt as cnt from openCompany a,  
	(select count(*) as cnt,compno  from openBidSeq group by compno) b 
	join a.compno=b.compno
	 where a.compname like '%부산%'  and a.compno=b.compno   
	
SELECT b.compno, b.compname, b.repname, COUNT(a.compno) cnt FROM openBidSeq a LEFT JOIN openCompany b ON a.compno=b.compno WHERE b.compname like '%부산%' GROUP BY a.compno  -> 86초
	 */
	
	
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);

	//$stmt->bind_param("ss", $qry, $qry);
	
	if (!$stmt->execute()) return $stmt->errno;
	$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	//echo( 'fields='.$fields[0].'/'.$fields[1].'/'.$fields[2].'/'.$fields[3]);
	//$colArray = array ( 'compno', 'compname', 'repname', 'cnt');

	$json_string = $g2bClass->rs2Json1($stmt, $fields,'');
	
	echo ($json_string);
	//exit;
	/*$i=1;
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
	} */
	$i--;
	//echo '<table>';
	exit;
}

/* --------------------------------------------------------------------------------------------
	입찰정보
--------------------------------------------------------------------------------------------- */
if ($endDate == "") {
	$endDate = date("Y-m-d"); //$today;
	$timestamp = strtotime("-1 months");
	$startDate = date("Y-m-d", $timestamp);
} 
if ($chkBid == '' && $chkHrc == '') $chkBid = 'bid';
?>


<?
//echo $endDate;
//$startDate = str_replace('-','',$startDate);
//$endDate = str_replace('-','',$endDate);
//echo ('<br>startDate='.$startDate.'<br>');
//if ($kwd == "") exit;

//echo 'bidrdo='.$bidrdo; // bid= 입찰 scsbid = 낙찰
/*
입찰정보
요청주소  http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch
서비스URL  http://apis.data.go.kr/1230000/BidPublicInfoService
https://www.data.go.kr/subMain.jsp?param=T1BFTkFQSUAxNTAwMDgwMg==#/L3B1YnIvcG90L215cC9Jcm9zTXlQYWdlL29wZW5EZXZHdWlkZVBhZ2UkQF4wMTJtMSRAXnB1YmxpY0RhdGFQaz0xNTAwMDgwMiRAXnB1YmxpY0RhdGFEZXRhaWxQaz11ZGRpOjY0ZWNjMDI2LWEyODItNDNkZi1iMGUxLWY1OTQxN2M2MDZjZV8yMDE4MDUxMTEwMDUkQF5vcHJ0aW5TZXFObz0yMDI2OCRAXm1haW5GbGFnPXRydWU=
낙찰된 목록 현황 물품조회
요청주소  http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch
서비스URL  http://apis.data.go.kr/1230000/ScsbidInfoService

?kwd=%20&startDate=2018-09-25&endDate=2018-10-25&dminsttNm=&bidcnstwk=1&bidhrc=bid
*/

// ============================== 입찰정보 물품 DB 에서=====================================================

$numOfRows='999'; // 최대값이 999 이하로 서버에서 조정한듯. 2018/10/12
$pss = '';
$LikeOrEqual='like';
if ($curStart == '') $curStart=0;
if ($cntonce == '') $cntonce=1000;

$startNo=$curStart;
$noOfRow=$cntonce;
$prj = substr($func,0,3);
//echo $prj;
if ($prj != "pln") {
	if ($bidthing == '1') { 
		if ($bidhrc=='bid') {
			$pss = '입찰물품';
			$func = 'bidthing';
		}
		else {
			$pss = '사전물품';
			$func = 'hrcthing';
		}
		$stmt = $dbConn->getSvrDataDB2($conn,$func,$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow); // 물품입찰
		//viewTable($kind,$startDate,$endDate,$kwd,$dminsttNm,$noRow,$nopg,$inqryDiv) 1:공고게시일시
		//$g2bClass->viewTable('물품',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1');
		
	}
	if ($bidcnstwk == '1') {
		if ($bidhrc=='bid') {
			$pss = '입찰공사';
			$func = 'bidcnstwk';
		}
		else {
			$pss = '사전공사';
			$func = 'hrccnstwk';
		}
		$stmt = $dbConn->getSvrDataDB2($conn,$func,$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
		
	}
	if ($bidservc == '1') {
		if ($bidhrc=='bid') {
			$pss = '입찰용역';
			$func = 'bidservc';
		}
		else {
			$pss = '사전용역';
			$func = 'hrcservc';
		}
		$stmt = $dbConn->getSvrDataDB2($conn,$func,$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
		
	}

	//$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰
	/*	unique column namme 
		용역입찰	용역구분명 srvceDivNm
		물품입찰	물품규격명 prdctSpecNm, 물품수량 prdctQty
		공사입찰	부대공종명1	subsiCnsttyNm1
		*/
	//var_dump($response1);
	if (strpos($response1,'Temporary Redirect')) {
		echo '<p style="font-weight: bold; color: rgb(255,0,0);">Server 일시 중단 상태 입니다.</p>';
		var_dump($response1);
		exit;
	}

		//if ($bidhrc=='bid') {
		//$colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt','bidNtceDtlUrl','locate');

		$rowCount = $stmt->num_rows;
		$fields = $g2bClass->bindAll($stmt);

		$json_string = $g2bClass->rs2Json1($stmt, $fields,$pss);

		//$json_string = $g2bClass->compressJson($response1, $colArray,$pss);
		//$js = substr($json_string,333200,800);
		//echo $js;
		echo ($json_string);

		exit;
	//}

	// ============================== 사전규격정보 =====================================================
	if ($bidhrc == 'hrc') { 
		//$mobile = $g2bClass->MobileCheck();
		
		ob_end_flush();
		flush();
		if ($bidthing == '1' && $bidhrc=='hrc') {	
			//$response1 = $g2bClass->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 물품사전규격
			$pss = '물품';
			// $pss = '물품';
			//$stmt = $dbConn->getSvrDataDB($conn,'bidthing',$kwd,$dminsttNm,$pss); // 물품입찰 
			$stmt = $dbConn->getSvrDataDB2($conn,'hrcthing',$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
		
		} else if ($bidcnstwk == '1' && $bidhrc=='hrc') {
			//$response1 = $g2bClass->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 공사사전규격
			$pss = '공사';
			$stmt = $dbConn->getSvrDataDB2($conn,'hrccnstwk',$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
		
		} else if ($bidservc == '1' && $bidhrc=='hrc') {
			//$response1 = $g2bClass->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1'); // 용역사전규격
			$pss = '용역';
			$stmt = $dbConn->getSvrDataDB2($conn,'hrcservc',$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow);
		
		}

		//$response4 = $g2bClass->getSvrData('bidfrgcpt',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 외자입찰
		if (strpos($response1,'Temporary Redirect')) {
			echo '<p style="font-weight: bold; color: rgb(255,0,0);">Server 일시 중단 상태 입니다.</p>';
			exit;
		}
		// 사전규격등록번호,품명,배정예산금액,등록일시,실수요기관명,의견등록마감일시
		//$colArray = [ 'bfSpecRgstNo', 'prdctClsfcNoNm', 'asignBdgtAmt', 'rgstDt', 'rlDminsttNm', 'opninRgstClseDt', 'bidNtceNoList' ];
		$colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt','bidNtceDtlUrl','locate');
		$json_string = $g2bClass->compressJson($response1, $colArray,$pss);

		echo ($json_string);

		exit;
	}
}

?>
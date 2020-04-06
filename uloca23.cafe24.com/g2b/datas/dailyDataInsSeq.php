<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');

//----------------------------------------
// g2b.js 파라미터
//----------------------------------------
// url = '/g2b/datas/dailyDataInsSeq.php?
// bidNtceNo='+bidNtceNo+'		 	//공고No
// &openBidInfo='+openBidInfo + ' 	//테이블명
// &openBidSeq='+openBidSeq_tmp+' 	//테이블명
// &bididx='+bididx+			 	//bididx (openbidinfo)
//'&pss='+pss;						//공사,물품,용역

$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

//------------------------------------------
// prepareStatement for-loop 에 사용할 stmt
// -by jsj 20190601
//------------------------------------------
/*
 $stmt_dbconn = new dbConn;
 $stmt_connn = $stmt_dbconn->conn();
 $stmt = $stmt_connn->stmt_init();
 */

mysqli_set_charset($conn, 'utf8');
if ($openBidInfo == '' || $openBidSeq == '') {
	echo '테이블 명이 없습니다.';
	exit;
}
if ($bidNtceNo == '') {
	echo '공고번호가 없습니다.';
	exit;
}

$sql = 'select max(idx) as idx from ' . $openBidSeq;
$result0 = $conn->query($sql);
if ($row = $result0->fetch_assoc()) {
	$idx = $row['idx'];
	if ($idx == 'NULL') $idx = 0;
}

//하루 폼메일 보낸수가 500개 이상이면 못 보냄
$sql  = "SELECT COUNT(compno) AS CNT FROM openCompany WHERE 1";
$sql .= "   AND DATE(modifyDT) = DATE(now())";
$result = $conn->query($sql);
if ($result) {
	$row = $result->fetch_assoc();
	$sendEmailCnt = $row["CNT"];
}


//사용하지 않음 -by jsj 190601
function insertForecastInfo($conn, $bidNtceNo, $compno1, $tuchalrate1, $tuchalamt1, $compno2, $tuchalrate2, $tuchalamt2, $tuchalcnt, $pss)
{

	//$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
	$sql = 'insert into forecastData ( bidNtceNo, compno1, tuchalrate1,tuchalamt1,compno2, tuchalrate2,tuchalamt2,tuchalcnt,pss)';
	$sql .= "VALUES ( '" . $bidNtceNo . "', '" . $compno1 . "', '" . $tuchalrate1 . "','" . $tuchalamt1 . "', '" . $compno2 . "', '" . $tuchalrate2 . "','" . $tuchalamt2 .  "','" . $tuchalcnt .  "','" . $pss . "')";
	//	echo($sql);
	if ($sql != '') {
		if ($conn->query($sql) === TRUE) {
		}
		//else echo 'error sql='.$sql.'<br>';
	}
	return true;
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}

//------------------------------------
//예정가격, 기초금액 업데이트 -jsj 190507
//------------------------------------
function updateopenBidInfo($conn, $openBidInfo, $idx, $arr, $bidNtceNo)
{
	//echo '<br>'.$bidNtceNo .'/'. $arr['bidNtceNo'];
	//if ($bidNtceNo == $arr['bidNtceNo']) return $bidNtceNo;
	//$bidNtceNo = $arr['bidNtceNo'];

	$sql = "UPDATE openBidInfo SET rsrvtnPrce = '" . $arr['plnprc'] . "', ";
	$sql .= "bssAmt = '" . $arr['bssamt'] . "', ";
	$sql .= "ModifyDT = now() ";
	$sql .= " WHERE bidNtceNo='" . $bidNtceNo . "';";
	//echo $sql.'<br>';
	$conn->query($sql);

	return $bidNtceNo; // update
}

function updateInfo($conn, $g2bClass, $bidNtceNo, $pss)
{
	//$bidNtceNo='20190222287';
	//$pss='물품';
	if ($pss == '입찰물품') $bidrdo = 'opnbidThng'; //$bsnsDivCd = '1'; // 물품
	else if ($pss == '입찰공사') $bidrdo = 'opnbidCnstwk'; //$bsnsDivCd = '1'; // 공사
	else if ($pss == '입찰용역') $bidrdo = 'opnbidservc'; //$bsnsDivCd = '1'; // 용역

	$itemr = $g2bClass->getSvrDataOpn($bidrdo, $bidNtceNo); //tot_getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd);
	//echo ($itemr);
	$json1 = json_decode($itemr, true);
	//$iRowCnt = $json1['response']['body']['totalCount'];
	$items = $json1['response']['body']['items'];
	if (count($items) > 0) {
		foreach ($items as $arr) {
			// ------------------------------ update
			$bidNtceNo1 = updateopenBidInfo($conn, "", "", $arr, $bidNtceNo); // -by jsj 20190804
		}
	}
}

// 기업정보 전화번호, 팩스, 홈페이지, 이메일 업데이트  -by jsj 190507
// 홈페이지에 이메일 있으면 업데이트 후 이메일 return 없으면 false
function companyInfoUpdate($conn, $g2bClass, $compno)
{
	//$inqryDiv = 3; // 기업검색 사업자등록번호 기준검색
	$response1 = $g2bClass->getCompInfo(1, 1, 3, $compno);
	$json1 = json_decode($response1, true);
	$item0 = $json1['response']['body']['items'];

	// 업체정보창 열면 openCompany Table에 업뎃 -by jsj 20190502
	$compname = trim($item0[0]['corpNm']); //회사
	$repname = $item0[0]['ceoNm'];	//대표자
	$phone = $item0[0]['telNo']; 	//전번
	$faxNo = $item0[0]['faxNo']; 	//팩스
	$hmpgAdrs = trim($item0[0]['hmpgAdrs']);	// 기업홈페이지

	//회사명이 없으면 api 데이터 없는 것으로 간주하고 return -by jsj 190518
	if ($compname = '') {
		return false;
	}

	// $hmpgAdrs 에 "//"부터 시작하는 문자열 찾아서 '//' 제거하고 이메일 체크 함
	// 속도를 위해 이메일이 없으면 업데이트 하지 않음
	// 홈페이지에 '//' 있으면 // 이후로 파싱해서 이메일 체크
	if (strpos($hmpgAdrs, '//')) {
		$email = str_replace('//', '', strstr($hmpgAdrs, "//")); //이메일
	} else {
		$email = $hmpgAdrs;
	}

	if ($check_email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
		//openCompany update - 이메일이 있는 경우에만 업데이트 -by jsj 190518
		$sql = " UPDATE openCompany SET phone ='" . $phone . "', faxNo ='" . $faxNo . "', hmpgAdrs = '" . $hmpgAdrs . "', email = '" . $email . "', ModifyDT = now()";
		$sql .= " WHERE compno = '" . $compno . "'";
		$result = $conn->query($sql);
		return $check_email; 	//이메일 return
	} else { // 이메일 없으면 이메일 빼고 업데이트
		$sql = " UPDATE openCompany SET phone ='" . $phone . "', faxNo ='" . $faxNo . "', hmpgAdrs = '" . $hmpgAdrs . "', ModifyDT = now()";
		$sql .= " WHERE compno = '" . $compno . "'";
		$result = $conn->query($sql);
		return false;	// 이메일 없으면 false
	}
}

function SendEmail($to_mail, $compNo, $compName, $bidNtceNo, $bidNtceOrd, $bidNtceNM, $new)
{

	// 이메일 없거나 원치않는 사업 retrun false
	//return false; //test 시에 그냥 리턴 
	if (trim($to_mail) == '') return false;
	//-----아래 이메일은 기업에서 보내지말라고 연락옴----------------------------------------------
	if (trim($to_mail) == 'saeng3900@naver.com' || $compNo == '5138600757')  return false;  //주식회사신아엔지니어링
	if (trim($to_mail) == 'kaistec@naver.com'   || $compNo == '1428167662')  return false;  //지티엔지니어링
	if (trim($to_mail) == 'bluegkdud@naver.com' || $compNo == '6098213327')  return false;  // 해동문화재연구원
	//------------------------------------------------------------------------------------------

	//공고명 링크
	$bidNtceNM_UrlLink = "https://uloca.net/g2b/bidResult.php?bidNtceNo=" . $bidNtceNo . "&bidNtceOrd=" . $bidNtceOrd . "&pss=‘입찰용역’"; //낙찰결과 링크
	//회사 입찰이력 링크
	$compno_UrlLink = "https://uloca.net/g2b/datas/getInfobyComp.php?compno=" . $compNo . "&id=";		 //사업자번호 링크
	//기업검색(통합검색) 링크
	$compSearch_UrlLink = "https://uloca.net/ulocawp/?page_id=1134&searchType=2&kwd&dminsttNm&compname=" . $compName . "&curStart=0&cntonce=100&compinfo=1&id=";

	$bidNtceNM = "▷ " . $bidNtceNM;	// 공고명
	$compName = "▷ " . $compName;		// 회사명

	$mailFrom = "uloca@uloca.net";

	// new = true 일때만 숨은참조 보냄 (신규로 이메일 발굴된 때)
	// $rmark 순위문자 
	if ($new) $bcc = "uloca.net@gmail.com"; //신규는 참조메일 보냄

	$subject = "나라장터) 입찰결과 순위에서 경쟁업체를 확인하세요";
	$message =  " <table border='1' width='90%' cellspacing='1' cellpadding='4' bordercolor='darkorange' background='darkorange' rules='none'>";
	// 유로카닷넷 http://uloca.net 기업검색 링크
	$message .= "		<tr bordercolor='white' bgcolor='white'>";
	$message .= "			<td colspan='3' align='center'><font size='+3' color='black'><strong>유로카 닷넷<br></strong></font><font size='+1'><a href='" . $compSearch_UrlLink . "'> https://uloca.net 기업검색</a></font> </td>";
	$message .= "		</tr>";
	// 빈줄 row
	$message .= "		<tr bordercolor='darkorange' bgcolor='darkorange' >";
	$message .= "			<td colspan='3' bgcolor='darkorange' bordercolor='darkorange' align='center' >";
	$message .= "				<font size='0' style='background-color: orange'> <strong></strong></font>";
	$message .= "			</td>";
	$message .= "		</tr>";
	// 나의 경쟁기업은?
	$message .= "		<tr width='80%' bordercolor='darkorange' bgcolor='darkorange' >";
	$message .= "			<td width='1%' bgcolor='darkorange' bordercolor='darkorange' align='center'></td>";
	$message .= "			<td bgcolor='white' bordercolor='darkorange' align='center'>";
	$message .= "				<font size='+2' style='background-color: white'><strong>'나의 경쟁 기업은?'</strong></font>";
	$message .= "			</td>";
	$message .= "			<td width='1%' bgcolor='darkorange' bordercolor='darkorange' align='center'></td>";
	$message .= "		</tr>";
	// 빈줄 오렌지 바탕색
	$message .= "		<tr bordercolor='darkorange' bgcolor='darkorange' >";
	$message .= "			<td colspan='3' bgcolor='darkorange' bordercolor='darkorange' align='center' >";
	$message .= "				<font size='1' color='darkorange' style='background-color: darkorange'> <strong></strong></font>";
	$message .= "			</td>";
	$message .= "		</tr>";
	// 기업검색으로 경쟁사 입찰이력을 확인해보세요
	$message .= "		<tr bgcolor='darkorange' >";
	$message .= "			<td colspan='3' align='center' >";
	$message .= "				<font size='+2' color='white' style='background-color: darkorange'><strong>기업검색으로 경쟁사 입찰이력을 확인해보세요</strong></font>";
	$message .= "			</td>";
	$message .= "		</tr>";
	// 빈줄 오젠지 바탕색
	$message .= "		<tr bordercolor='darkorange' bgcolor='darkorange' >";
	$message .= "			<td colspan='3' bgcolor='darkorange' bordercolor='darkorange' align='center' >";
	$message .= "				<font color='darkorange' style='background-color: darkorange'> <strong> </strong></font>";
	$message .= "			</td>";
	$message .= "		</tr>";
	$message .= " </table><br> ";

	// 소개페이지 링크 - 프레지 동영상
	$youtube_prezi_UrlLink = "https://youtu.be/HJZSogkRUpk";
	$message .= " <p><a href = '" . $youtube_prezi_UrlLink . "' target='_blank'><font size='+1' color='teal' style='background-color: white'><strong>▷ 유로카닷넷 소개동영상 </strong></font></a></p>";
	$message .= " <p><font color='black'><strong> → 유튜브 소개동영상으로 유로카닷넷의 기능을 확인해 보세요.";

	// 입찰이력 링크
	$message .= " <p><a href = '" . $compno_UrlLink . "' target='_blank'><font size='+1' color='teal' style='background-color: white'><strong>" . $compName . "</strong></font></a></p>";
	$message .= " <p><font color='black'><strong> → 클릭해서 자신의 입찰이력을 확인해 보세요.";
	$message .= " (기업검색은 KED한국기업데이터(크레탑)의 기업정보를 제공합니다.) </strong></font></p>";

	// 낙찰결과 링크
	$message .= " <p><a href = '" . $bidNtceNM_UrlLink . "' target='_blank'><font size='+1' color=black' style='background-color: white'><strong>" . $bidNtceNM . "</strong></font></a></p>";
	$message .= " <p><font color='black'><strong> → 공고명을 클릭해서 경쟁기업의 입찰이력을 확인해 보세요. 통합검색에서 모든 공고가 검색됩니다.</strong></font></p>";

	// 입찰기초금액 계산링크
	$message .= " <p><a href = 'bit.ly/33rQril' target='_blank'><font size='+1' color='darkorange' style='background-color: white'><strong> ▷ 입찰기초금액계산 </strong></font></a></p>";
	$message .= " <p><font color='black'><strong> ※기초금액으로 예비가격과 추첨예가를 계산합니다. 예비가격과 추첨예가를 임의/랜덤(시스템 난수발생)으로 추첨합니다. 실제 입찰기초금액은 사전에 누구도 알수 없으나, 여러번 계산해서 참조용으로 활용하세요. </strong></font></p>";

	// 블로그 데이터바우처 페이지 링크 - 홍보만화
	$Blog_dataVoucher_UrlLink = "https://uloca.net/ulocawp/?p=2338";
	$message .= " <p><a href = '" . $Blog_dataVoucher_UrlLink . "' target='_blank'><font size='+1' color='teal' style='background-color: white'><strong>▷ 한국정보화진흥원 데이터바우처 홍보만화 </strong></font></a></p>";
	$message .= " <p><font color='black'><strong> → 한국정보화진흥원의 데이터바우처 <유로카닷넷> 홍보만화";

	// 회원가입 링크
	$message .= " <p><a href = 'http://bit.ly/2VmBav4' target='_blank'><font size='+1' color='darkorange' style='background-color: white'><strong> ▷ 유로카닷넷 회원가입하기 </strong></font></a></p>";
	$message .= " <p><font color='black'><strong> ※선착순 회원제로 운영합니다.</strong></font></p>";

	// 기업검색 링크
	$message .= " <p><<a href=" . $compSearch_UrlLink . "><font size='+1' color='darkorange' style='background-color: white'><strong>▷ 유로카닷넷 기업검색 바로가기 → <font size='+1'>https://uloca.net </strong></font></a></p>";

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: 입찰정보 <' . $mailFrom . ">\r\n";
	$$headers .= 'Reply-To: <' . $mailFrom . ">\r\n";
	$headers .= "Bcc: " . $bcc . "\r\n";

	$result = mail($to_mail, $subject, $message, $headers);

	if ($result) {	//전송실패 시 나에게 메일보냄
		return true;
	} else {
		return false;
	}
} //send email

//---------------------------------------------------------
$idx++; //openbidSeq max(id)
//예정가격, 기초금액 업데이트
updateInfo($conn, $g2bClass, $bidNtceNo, $pss);
//---------------------------------------------------------

$start = 0;
$cnt = 0;
$tCnt = 0;
$i = 1;
$bidNtceOrd = ''; //$row['bidNtceOrd'];

//-------------------------------------------------------------
// 입찰 결과 1000건이상일 때 Time-over (or 멈춤) 발생 -by jsj 190602
// getRsltDataAll --> 999 이상인 경우 g2bclass while 문에 break; 처리함
//-------------------------------------------------------------
$item1 = $g2bClass->getRsltData($bidNtceNo, $bidNtceOrd); // 2018-12-11
$cnt = count($item1);

//$msg .= 'idx='.$row['idx'].' 개찰일시='.$row['opengDt'].' bidNtceNo='.$bidNtceNo.' count='.$cnt.'<br>';
$ii = 1; //foreach ++ 처리건수
echo $cnt; //입찰기업 갯수 표시

if ($cnt > 0) {

	foreach ($item1 as $arr) {
		$rmrk = addslashes($arr['rmrk']);
		$openRank = (int) $arr["opengRank"];

		// rmrk=='' 이고 oepnRank에 값이있으면 정상
		if ($openRank <> 0) {
			$rmrk = $arr["opengRank"]; // 정상 case = 순위가 있으면 $rmrk에 순위를 적용
		} else {
			$openRank = $ii; // opengRank 가 잘못된 경우
		}

		//-------------------------------------------------
		//$openBidInfo 에 1순위 정보 추가 순위 -by jsj 190601 <== 데이터 없음
		//-------------------------------------------------
		if ($openRank == 1) { // openRank 1순위 or $ii = 1(첫번째) -by jsj 190601
			// getOpengResultListInfoOpengCompt 개찰결과 개찰완료 목록 조회
			$sql1 = "UPDATE openBidInfo SET ";
			$sql1 .= " 	prtcptCnum = "      . count($item1) . ", ";    			    // 입찰업체수
			$sql1 .= " 	bidwinnrNm = '"     . addslashes($arr['prcbdrNm']) . "', "; // 투찰업체명
			$sql1 .= " 	bidwinnrBizno = '"  . $arr['prcbdrBizno'] . "', ";			// 사업자번호
			$sql1 .= " 	sucsfbidAmt = '"    . $arr['bidprcAmt'] . "', ";			// 투찰금액
			$sql1 .= " 	sucsfbidRate = '"   . $arr['bidprcrt'] . "', ";				// 투찰률
			$sql1 .= " 	bidwinnrCeoNm = '"  . $arr['prcbdrCeoNm'] . "', ";			// 투찰대표자명
			$sql1 .= "  progrsDivCdNm = '"  . "개찰완료" . "', ";  		  			 // 개찰완료
			$sql1 .= " 	modifyDT = now()";
			$sql1 .= " WHERE bidNtceNo ='"  . $bidNtceNo . "' ";
			$conn->query($sql);
		}

		/* insertForecastInfo 사용안함 -by jsj 190601 ==> 용도 확인필요 -by jsj 20200401
			$compno1 = $arr['prcbdrBizno'];
			$tuchalrate1 = $arr['bidprcrt'];
			$tuchalamt1 = $arr['bidprcAmt'];
		*/
		
		//--> 예측 낙찰하한선 미달 -  재확인 필요  .... -by jsj 0631
		/* insertForecastInfo
		 if ($arr['rmrk'] == '낙찰하한선 미달' && (int)$tuchalamt2 < (int)$arr['bidprcAmt']) {
		 $compno2 = $arr['prcbdrBizno'];
		 $tuchalrate2 = $arr['bidprcrt'];
		 $tuchalamt2 = $arr['bidprcAmt'];
		 }
		 */

		//----------------------------------------------------------------
		// 999건 이상은 오지 않음  g2b -by jsj 0601
		// openBidSeq_tmp 에 key ==> unique(bdidNtceNo, bidNtceOrd, compno)
		//----------------------------------------------------------------
		// INSER 데이터량 조절 필요
		// if ($rmrk > 100) continue; //순위로 조정
		// if((int)$rmrk == 0 ) continue; //자격미달 등
		$sql  = ' REPLACE INTO openBidSeq_tmp ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx)';
		$sql .= " VALUES ( '" . $arr['bidNtceNo'] . "', '" . $arr['bidNtceOrd'] . "', '" . $arr['rbidNo'] . "', '" . $arr['prcbdrBizno'] . "','" . $arr['bidprcAmt'] . "','" . $arr['bidprcrt'] . "',";
		$sql .= " '', '" . $arr['bidprcDt'] . "', '" . $rmrk . "','" . $bididx . "')";
		$result = $conn->query($sql);

		if ($result == false) {
			$msg .= $ii . ": " . $sql . '<br>';  // err인 경우 SQL 표시
		}

		// 기업 홈페이지에 이메일이 있는지 확인 -by jsj 20190804
		$sql = " SELECT compno, hmpgAdrs, email, rmark, emailCnt";
		$sql .= "  FROM openCompany ";
		$sql .= " WHERE compno ='" . $arr['prcbdrBizno'] . "'";
		$result0 = $conn->query($sql);

		if ($row = $result0->fetch_assoc()) {

			//---------------------------------------------------
			// DB에 기업정보가 있고, 1~10 순위만 이메일 보냄. -by jsj 20190808
			//---------------------------------------------------
			if ($openRank >= 5 || $openRank == 0 || (int) $rmrk = 0) continue;
			// 이미 이메일 보낸 업체는 보내지 않음
			if ($row['emailCnt'] >= 3) continue;
			//하루 폼메일 가능갯수는 500개
			if ($sendEmailCnt >= 499) continue;

			$compno = $row['compno'];
			$email = $row['email'];		  //이메일
			$hmpgAdrs = $row['hmpgAdrs']; //홈페이지
			// -------------------------------------------------------------
			// DB의 이메일 있는지 체크 후 이메일 보냄
			//-------------------------------------------------------------
			if ($check_email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$rtn = SendEmail($check_email, $arr['prcbdrBizno'], addslashes($arr['prcbdrNm']), $arr['bidNtceNo'], $arr['bidNtceOrd'], $bidNtceNM, false);
				if ($rtn) {
					$sql = " UPDATE openCompany SET rmark = 'E-mail(DB)보냄', ModifyDT = now(), emailCnt = emailCnt + 1 ";
					$sql .= " WHERE compno = '" . $compno . "'";
					if ($result = $conn->query($sql)) {
						$msg .= '<br>순위[' . (string) $openRank . ']E-mail(DB)[' . $check_email . ']';
					} else {
						$msg .= $ii . ": " . $sql . '<br>'; //error 나면 sql 표시 
					}
				}
				// ---------------------------------------------------
				// 홈페이지 URL에 이메일이 있으면 보냄 - $hmpgAdrs에 이메일 체크 함
				// ----------------------------------------------------
			} else {
				if (strpos($hmpgAdrs, '//')) {
					$hmpgAdrs = str_replace('//', '', strstr($hmpgAdrs, "//"));
				}
				//홈페이지에 이메일이 있으면 이메일 보낸 후 DB에도 업데이트
				if ($check_email = filter_var($hmpgAdrs, FILTER_VALIDATE_EMAIL)) {
					$rtn = SendEmail($check_email, $arr['prcbdrBizno'], addslashes($arr['prcbdrNm']), $arr['bidNtceNo'], $arr['bidNtceOrd'], $bidNtceNM, false);
					if ($rtn) {
						$sql = " UPDATE openCompany SET email = '" . $check_email . "', rmark = 'E-mail(hp)보냄', ModifyDT = now(), emailCnt = emailCnt + 1 ";
						$sql .= " WHERE compno = '" . $compno . "'";
						if ($result = $conn->query($sql)) {
							$msg .= '<br>순위[' . (string) $openRank . ']E-mail(hp)[' . $check_email . ']';
						} else {
							$msg .= $ii . ": " . $sql . '<br>'; //error 나면 sql 표
						}
					}
				}
			}
			// ------------------------------------------------------------------
			// 기업정보 없으면 기업정보 추가하고 이메일 있으면 보냄 -by jsj 190601
			// ------------------------------------------------------------------
		} else {
			$sql =  "REPLACE INTO openCompany (compno,compname, repname, rmark, ModifyDT)";
			$sql .= "VALUES ('" . $arr['prcbdrBizno'] . "', '" . addslashes($arr['prcbdrNm']) . "', '" . addslashes($arr['prcbdrCeoNm']) . "', '신규추가', now())";
			if ($conn->query($sql) == TRUE) {
				if ($check_email = companyInfoUpdate($conn, $g2bClass, $arr['prcbdrBizno'])) {
					$rtn = SendEmail($check_email, $arr['prcbdrBizno'], addslashes($arr['prcbdrNm']), $arr['bidNtceNo'], $arr['bidNtceOrd'], $bidNtceNM, true); //이메일 보냄
					if ($rtn) {
						$sql = " UPDATE openCompany SET email = '" . $check_email . "', rmark = 'E-mail(hp)보냄', ModifyDT = now(),  emailCnt = emailCnt + 1 ";
						$sql .= " WHERE compno = '" . $compno . "'";
						if ($result = $conn->query($sql)) {
							$msg .= '**기업추가**<br>순위[' . (string) $openRank . ']E-mail(new)[' . $check_email . ']';
						}
					}
				}
			}
		}
		$ii++; //foreach ++
	} // end foreach
} // end if ($cnt>0)
//if ($dup == 0 ) insertForecastInfo($conn,$bidNtceNo, $compno1, $tuchalrate1,$tuchalamt1,$compno2, $tuchalrate2,$tuchalamt2,$tuchalcnt,$pss);
$i++; // 처리건수 return
//}
//echo ($idx-$cnt);
echo $msg;

<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
session_start();
ob_start();
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');

$dbConn = new dbConn;
$conn = $dbConn->conn();

//url = 'http://uloca23.cafe24.com/g2b/bidResult.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&pss='+pss;
$g2bClass = new g2bClass;
$uloca_live_test = $g2bClass->getSystem('1');
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

/*
if(isset($_COOKIE['resudi'])) {
    echo "Cookie named resudi='" . $resudi . "' ";
} else echo 'no cookie resudi';
echo 'userid='.$_SESSION['current_user']->user_login;
*/
// --------------------------------- log
$rmrk = '입찰결과창';
$dbConn->logWrite2($id, $_SERVER['REQUEST_URI'], $rmrk, '', '08'); // $_SESSION['current_user']->user_login
// --------------------------------- log

// 낙찰 결과
$response1 = $g2bClass->getRsltDataAll($bidNtceNo, $bidNtceOrd);
$item1 = $response1;
// $response1 = $g2bClass->getRsltData($bidNtceNo, $bidNtceOrd);
// $json1 = json_decode($response1, true);
// $item1 = $json1['response']['body']['items'];

// 입찰정보
//echo $bidNtceNo.'/'.$bidNtceOrd.'/'.$pss;
$response0 = $g2bClass->getBidInfo($bidNtceNo, $bidNtceOrd, $pss);
$json0 = json_decode($response0, true);
$item0 = $json0['response']['body']['items'];

//echo "입찰정보=" ;
//var_dump($item0);

if ($mobile == "Mobile") $mailtop = 80;
else $mailtop = 80;
$mailintop = $mailtop + 4;	// mail address

?>

<!DOCTYPE html>
<html>

<head>
	<title>낙찰결과/<?= $bidNtceNo ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="format-detection" content="telephone=no">
	<!--//-by jsj 전화걸기로 링크되는 것 막음 -->

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?version=20190102" />
	<link rel="stylesheet" href="/jquery/jquery-ui.css">

	<script src="/jquery/jquery.min.js">
	</script>
	<script src="/jquery/jquery-ui.min.js"></script>
	<script src="/js/common.js?version=20190203"></script>
	<script src="/g2b/g2b.js?version=20200401"></script>
	<script src="/g2b/g2b_2019.js?version=20190203"></script>

	<script>
		//copy URL ============================================================
		function copyURL() {
			var url = document.location.href;
			try {
				shortURL(url);
			} catch (e) {
				alert('Error:'.e);
			}
		}

		//var //resedi=GetCookie('resedi');
		var resudi = '<?= $id ?>';

		function mailMe3() {
			emaildiv = document.getElementById('emaildiv');
			emailid = document.getElementById('emailid');
			if (emaildiv.style.visibility == 'hidden') {
				emaildiv.style.visibility = 'visible';
				emailid.focus();
				return;
			}
			if (emailid.value == "") {
				alert('email 주소를 입력하세요.');
				emaildiv.style.visibility = 'hidden';
				return;
			}
			emaildiv.style.visibility = 'hidden';
			frm2 = document.mailForm;
			frm2.email2.value = emailid.value;

			msg = document.getElementById('contents').outerHTML; //json2table(data2) ; //document.getElementById('bidinfo').innerHTML;
			hdr = '<p><a href="http://uloca.net"><input  type="button" value="유로카 입찰정보" style="width:200px; background-color:#E9602C; height:28px; color:#ffffff; cursor:pointer; font-size:14px; font-weight: bold; text-align:center; border:solid 1px #99bbe8; border-bottom:solid 1px #99bbe8;"></a></p><br>';
			frm2.message.value = hdr + msg;
			//var gsWin = window.open('about:blank','new_blank','width=900,height=700,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
			frm2.submit();
		}
	</script>

</head>

<body>

	<div id="contents">
		<center>

			<table align=center cellpadding="0" cellspacing="0" width="100%" class="grid01">
				<colgroup>
					<col style="width:15%;" />
					<col style="width:auto;" />
				</colgroup>
				<tbody>
					<tr>
						<th style='font-size:1.0em; background-color:#E9602C;color:#ffffff;border:solid 1px #E9602C;text-align:center; cursor:pointer; '>
							<a onclick=viewDtls1("<?= $item0[0]['bidNtceNo'] ?>","<?= $bidNtceOrd ?>") style='color:#ffffff;'> 입찰공고 바로가기<br> (나라장터상세정보) </a></th>
						<td style='font-size:1.0em; cursor:pointer;color:#E9602C;border:solid 1px #E9602C; '>
							<a onclick=viewDtls1("<?= $item0[0]['bidNtceNo'] ?>","<?= $bidNtceOrd ?>") style='color:#E9602C;font-weight:bold; '>
								&nbsp;&nbsp;<?= $item0[0]['bidNtceNo'] ?> &nbsp;/&nbsp; <?= $item0[0]['bidNtceNm'] ?></a>
						</td>
					</tr>
				</tbody>
			</table>

			<?
			// 사정율 계산 ----------------------------------
			// 사정율 = 투찰금액/기초금액 (예정가격없을때)
			// 사정율(실제) = 예정가격/기초금액 (추첨후 예정가격 나옴)

			if ($pss == '입찰물품') $bidrdo = 'opnbidThng'; //$bsnsDivCd = '1'; // 물품
			else if ($pss == '입찰공사') $bidrdo = 'opnbidCnstwk'; //$bsnsDivCd = '1'; // 공사
			else if ($pss == '입찰용역') $bidrdo = 'opnbidservc'; //$bsnsDivCd = '1'; // 용역
			$itemr = $g2bClass->getSvrDataOpn($bidrdo, $bidNtceNo); // 예정가격,기초금액 가져옴
			$json1 = json_decode($itemr, true);
			$sasungyul = 0;
			$plnprc = 0; //예정금액
			$bssamt = ''; //기초금액
			$items = $json1['response']['body']['items'];
			if (count($items) > 0) {
				foreach ($items as $arr) {
					$plnprc           = $arr['plnprc'];
					$bssamt           = $arr['bssamt'];
					$bssamt1          = $arr['bssamt'];  //기초금액의 사정율 계산을 위한 변수
					$sucsfbidLwltRate = $ietm0['sucsfbidLwltRate']; // 낙찰하한율 (안나옴)
					if ($bssamt != '' && $bssamt != 0) $sasungyul = $plnprc / $bssamt ; // 사정율 = 예정가격 / 기초금액
				}
			}
			if ($plnprc != '') $plnprc = number_format($plnprc);
			if ($bssamt != '') $bssamt = number_format($bssamt);

			?>

			<div id=totalrechrc>낙찰결과 <br> (공공데이터포털 API) Total record=<?= count($item1) ?></div>
			<div id=LinKExplain align="left">
				예정가격(<?= $plnprc ?>) = 기초금액(<?= $bssamt ?>) * <font color=red>사정율(<?= number_format($sasungyul * 100, 3, '.', '') ?> %)</font>
				<!-- 낙찰하한율(=<?= $sucsfbidLwltRate ?>) -->
			</div>

			<table class="type10" id="specData" width="100%">
				<thead>
					<tr>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="5%;">순위</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="15%;">사업자등록번호<br>(클릭→낙찰이력)</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="20%;">업체명<br>(클릭→업체정보)</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="10%;">대표자</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="10%;">투찰금액(원)</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="10%;">예정가격의</br>투찰율(%)</th>

						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="10%;">기초금액의</br>투찰율(%)</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="5%;">입찰번호</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="15%;">비고</th>
					</tr>
				</thead>
				<?
				/* 정렬 ------------------------------------------------------------- */
				$rmrk = array();
				$bidprcAmt = array();
				foreach ($item1 as $key => $row) {
					$rmrk[$key]  = $row['rmrk'];
					$bidprcAmt[$key]  = $row['bidprcAmt'];
				}

				if (is_array($rmrk)) {
					array_multisort($rmrk, SORT_ASC, $bidprcAmt, SORT_ASC, SORT_NUMERIC, $item1); // 등록일시
				}
				/* 정렬 ------------------------------------------------------------- */

				$opengDt = $item0[0]['opengDt'];
				$opengDt = substr($opengDt, 0, 7);

				//-----------------------------------------------------------------
				// openBidInfo idx 찾아서 openBidSeq_xxxx의 bidIdx -by jsj 20190731 
				//-----------------------------------------------------------------
				$sql = " SELECT idx, rgstTyNm";
				$sql .= "  FROM openBidInfo ";
				$sql .= " WHERE bidNtceNo ='" . $bidNtceNo . "'";
				$sql .= "   AND bidNtceOrd ='" . $bidNtceOrd . "'";
				$result0 = $conn->query($sql);
				if ($row = $result0->fetch_assoc()) {
					$bidIdx = $row['idx'];
					$rgstTyNm = $row['resgTyNm'];
				} 
				$openBidSeq_xxxx = "openBidSeq_" . date("Y");

				//echo $opengDt;
				$i = 1;
				$k = 1;
				if (count($item1) == 0) {
					// 진행구분에 유찰로 업데이트 (개찰일시가 오늘날짜-1일 과 비교)
					if ($rgstTyNm == '연계기관 공고건') {
						$sql  = "UPDATE openBidInfo SET ";
						$sql .= "       progrsDivCdNm = '연계기관'";
						$sql .= " WHERE bidNtceNo  = '" .$bidNtceNo. "'";
						$sql .= "   AND bidNtceOrd = '" .$bidNtceOrd. "'";
						// $sql .= "   AND date_format(opengDt,'%Y%m%d') < date_add(now(), interval -7 day)  ";
						if (!($conn->query($sql))) $msg .= ("ln150::Err Sql=" .$sql. ", <br>");	
					}
					// 유찰 메시지 출력
					$tr = "<tr>";
					$tr .= '<td colspan=7  style="text-align: center; color:red;"><br>DATA가 없습니다. 개찰이 안되었거나, 유찰이 되었을수 있습니다.<br><br></td>';
					$tr .= "</tr>";
					echo $tr;
				} else {
					foreach ($item1 as $arr) { //foreach element in $arr
						//---------------------------
						$rmrk = addslashes($arr['rmrk']); // 비고 - '낙찰하한선 미달' 등
						$k = (int) $arr["opengRank"];

						switch ($k) {
							case 0: 
								$k = $i;				// 낙찰미달이면 opengRank에 값이 없음
								$Rank_rmark = $rmrk;    // 비고 대입
								break;
	
							default:
								$Rank_rmark = (string)$k; // 순위 대입
								break;
						} 

						//기초금액과 투찰율 계산
						 $bssamtrt = ( $arr['bidprcAmt'] / $bssamt1 ) * 100;

						// 1순위
						if ($k == 1) { //$i == 0) {
							$tr = '<tr>';
							$tr .= '<td scope="row" style="text-align: center; color:red;">' . $k . '</td>';
							$tr .= '<td style="text-align: center; color:red;"><a onclick=\'compInfobyComp(' . $arr['prcbdrBizno'] . ')\'>' . $arr['prcbdrBizno'] . '</a></td>';
							$tr .= '<td style="color:red;"><a onclick=\'compInfo(' . $arr['prcbdrBizno'] . ')\'>' . $arr['prcbdrNm'] . '</a></td>';
							$tr .= '<td style="color:red;">' . $arr['prcbdrCeoNm'] . '</td>';
							if ($arr['bidprcAmt'] == '') $tr .= '<td style="color:red; text-align: right;" > </td>';
							else $tr .= '<td style="color:red; text-align: right;" >' . number_format($arr['bidprcAmt']) . '</td>'; //투찰금액
							$tr .= '<td style="text-align: right; color:red;">' . $arr['bidprcrt'] . '</td>'; // 예정금액의 투찰율

							$tr .= '<td style="text-align: right; color:red;">' . number_format($bssamtrt, 3) . '</td>'; // 기초금액의 투찰율

							$tr .= '<td style="text-align: center; color:red;">' . $arr['rbidNo'] . '</td>';
							$tr .= '<td style="color:red;">' . $arr['rmrk'] . '</td>';
							$tr .= '</tr>';

							//-------------------------------------------------
							//$openBidInfo 에 1순위 정보 업데이트 -by jsj 190601
							//-------------------------------------------------
							$sql  = "UPDATE openBidInfo SET ";
							$sql .= "     	prtcptCnum = "     .count($item1). ", ";    			// 입찰업체수
							$sql .= "       bidwinnrNm = '"    .addslashes($arr['prcbdrNm']). "', ";		// 최종낙찰업체명
							$sql .= "       bidwinnrBizno = '" .$arr['prcbdrBizno'].          "', ";		// 사업자번호
							$sql .= "       bidwinnrCeoNm = '" .$arr['bidwinnrCeoNm'].        "', ";		// 대표자명
							$sql .= "       bidwinnrTelNo = '" .$arr['bidwinnrTelNo'].        "', ";		// 업체연락처
							$sql .= "       sucsfbidAmt = '"   .$arr['sucsfbidAmt'].          "', ";		// 최종낙찰금액
							$sql .= "       sucsfbidRate = '"  .$arr['bidprcrt'].             "', ";		// 투찰율 = 투찰금액/예정가격
							$sql .= "       rlOpengDt = '"     .$arr['rlOpengDt'].            "', ";		// 실개찰일시
							$sql .= "       progrsDivCdNm =    '개찰완료', ";  								 // 진행구분 - '개찰완료'로 표시 (API 데이터가 안오는 경우가 있으므로 직접입력)
							$sql .= " 	    modifyDT = now()";
							$sql .= " WHERE bidNtceNo= '"      .$bidNtceNo. 				  "'  ";

							// $sql1 .= "   AND bidNtceOrd ='" . $bidNtceOrd . "' ";  //차수에 관계없이 입찰결과는 업데이트
							if ($conn->query($sql) == false) {
								$msg = "SQL error=" . $sql . "<br>";
							}

							//---------------------------------------------------
							//$openBidSeq_xxxx 에 입찰이력 입력 -by jsj 190601
							//---------------------------------------------------
							$sql  = " REPLACE INTO " .$openBidSeq_xxxx. " ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx )";
							$sql .= "  VALUES ( '" .$arr['bidNtceNo']. "','" .$arr['bidNtceOrd']. "','" .$arr['rbidNo'].   "','" .$arr['prcbdrBizno'] . "','" .$arr['bidprcAmt'] . "', ";
							$sql .= "           '" .$arr['bidprcrt'].  "','" .$arr['drwtNo1'].    "','" .$arr['bidprcDt']. "','" .trim($Rank_rmark).          "',"  .$bidIdx. ")";

							if ($conn->query($sql) <> true) {
								echo "ln325::Err sql=" .$sql. "<br>";
							}

						} else {

							$tr = '<tr>';
							$tr .= '<td scope="row" style="text-align: center;">' . $k . '</td>';
							$tr .= '<td style="text-align: center;"><a onclick=\'compInfobyComp(' . $arr['prcbdrBizno'] . ')\'>' . $arr['prcbdrBizno'] . '</a></td>';
							$tr .= '<td><a onclick=\'compInfo(' . $arr['prcbdrBizno'] . ')\'>' . $arr['prcbdrNm'] . '</a></td>';
							$tr .= '<td>' . $arr['prcbdrCeoNm'] . '</td>';
							if ($arr['bidprcAmt'] == '') $tr .= '<td> </td>';
							else  $tr .= '<td align=right>' . number_format($arr['bidprcAmt']) . '</td>';
							$tr .= '<td style="text-align: right;">' . $arr['bidprcrt'] . '</td>';     // 예정금액의 투찰율
							$tr .= '<td style="text-align: right; ">' . number_format($bssamtrt,3) . '</td>'; // 기초금액의 투찰율
							$tr .= '<td style="text-align: center;">' . $arr['rbidNo'] . '</td>';
							$tr .= '<td>' . $arr['rmrk'] . '</td>';
							$tr .= '</tr>';
							//---------------------------------------------------
							//$openBidSeq_xxxx 에 입찰이력 입력 -by jsj 190601
							//---------------------------------------------------
							$sql  = " REPLACE INTO " .$openBidSeq_xxxx. " ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx )";
							$sql .= "  VALUES ( '" .$arr['bidNtceNo']. "','" .$arr['bidNtceOrd']. "','" .$arr['rbidNo'].   "','" .$arr['prcbdrBizno'] . "','" .$arr['bidprcAmt'] . "', ";
							$sql .= "           '" .$arr['bidprcrt'].  "','" .$arr['drwtNo1'].    "','" .$arr['bidprcDt']. "','" .trim($Rank_rmark).          "',"  .$bidIdx. ")";
							if ($conn->query($sql) <> true) {
								echo "ln349::Err sql=" .$sql. "<br>";
							}
						}

						echo $tr;
						$i++;
						//echo $sql;
					}
					//echo $sql1;
				}
				echo '</table>';

				?>
	</div>

	<div id='emaildiv' style='visibility:hidden;position: fixed; top: <?= $mailintop ?>px; right: 298px; '>
		<input type="text" style='border:1px solid #000;width:200px' name="emailid" id="emailid" autocomplete="on" placeholder="email 주소" onkeypress="if(event.keyCode==13) {mailMe3(); return false;}" value="<?= $current_user->user_email ?>" />
	</div>
	<!-- 이메일 보내기 삭제, 하루 300건 이상 못보냄 -by jsj 190518   
<div style='position: fixed; top: <?= $mailtop ?>px; right: 210px;' class="btn_areas"><a onclick="mailMe3();" class="search">이메일</a></div>
-->

	<div style='position: fixed; top: 62px; right: 210px;' class="btn_areas"><a onclick="location.href='https://uloca.net'" class="search">통합검색</a></div>
	<div style='position: fixed; top: 62px; right: 125px;' class="btn_areas"><a onclick="copyURL();" class="search">링크복사</a></div>
	<div style='position: fixed; top: 62px; right: 40px;' class="btn_areas"><a onclick="self.close();" class="search">닫 기</a></div>
	<div id=mail style='visibility: hidden;display:inline;' -->
		<form action="/g2b/sendmail2.php" name="mailForm" id="mailForm" target='new_blank' method="post" style='visibility: hidden;display:inline;'>
			<input type="text" name="email2" id="email2" autocomplete="on" value="" />
			<input type="text" name="subject" id="subject" value="낙찰 결과" />
			<input type="text" name="message" id="message" value="" />
			<input type="hidden" name="resudi" value="<?= $id ?>" />

		</form>
	</div><!-- end mail -->
	<form name="compInfoForm" style='visibility: hidden;display:inline;'>
		<input type="hidden" name="resudi" value="<?= $id ?>" />
		<input type="hidden" name="id" value="<?= $id ?>" />
		<input type="hidden" name="compno" />
		<input type="hidden" name="opengDt" />
	</form>

</body>

</html>
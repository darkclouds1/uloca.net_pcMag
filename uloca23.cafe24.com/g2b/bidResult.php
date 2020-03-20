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

//$bidNtceNo='20170532764';
//$bidNtceOrd='00';
//url = 'http://uloca23.cafe24.com/g2b/bidResult.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&pss='+pss;
$g2bClass = new g2bClass;
$uloca_live_test = $g2bClass->getSystem('1');
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
/*session_id ($ss);

if(isset($_COOKIE['resudi'])) {
    echo "Cookie named resudi='" . $resudi . "' ";
} else echo 'no cookie resudi';
echo 'userid='.$_SESSION['current_user']->user_login;  */
// --------------------------------- log
$rmrk = '입찰결과창';
$dbConn->logWrite2($id, $_SERVER['REQUEST_URI'], $rmrk, '', '08'); // $_SESSION['current_user']->user_login
// --------------------------------- log
// 입찰 결과
$response1 = $g2bClass->getRsltData($bidNtceNo, $bidNtceOrd);
$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];

// 입찰정보
//echo $bidNtceNo.'/'.$bidNtceOrd.'/'.$pss;
$response0 = $g2bClass->getBidInfo($bidNtceNo, $bidNtceOrd, $pss);
$json0 = json_decode($response0, true);
$item0 = $json0['response']['body']['items'];

//var_dump($item1);
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

	<script src="/jquery/jquery.min.js"></script>
	<script src="/jquery/jquery-ui.min.js"></script>
	<script src="/js/common.js?version=20190203"></script>
	<script src="/g2b/g2b.js?version=20190203"></script>
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
			//hdr2 = document.getElementById('bidinfohead').innerHTML;
			//i = hdr2.indexOf('<a ');
			//if (i>2) hdr2 = hdr2.substring(0,i);

			msg = document.getElementById('contents').outerHTML; //json2table(data2) ; //document.getElementById('bidinfo').innerHTML;
			//s = msg.indexOf('<a onclick=\"showhide'); //낙찰');
			//e = msg.indexOf('표준편차',s)+10;
			//e = msg.indexOf('</font>',e)+4;
			/* hdr = '<html>';
			hdr += '<head>';
			hdr += '<title>ULOCA</title>';
			hdr += '<meta http-equiv="Content-Type" content="text/html; charset=utf8" />';
			hdr += '<meta name="viewport" content="width=device-width, initial-scale=1">';
			hdr += '<meta http-equiv="X-UA-Compatible" content="IE=Edge" />';
			hdr += '<link rel="stylesheet" type="text/css" href="http://uloca23.cafe24.com/g2b/css/g2b.css" />';
			hdr += '</head>';
			hdr += '<body>'; */
			hdr = '<p><a href="http://uloca.net"><input  type="button" value="유로카 입찰정보" style="width:200px; background-color:#E9602C; height:28px; color:#ffffff; cursor:pointer; font-size:14px; font-weight: bold; text-align:center; border:solid 1px #99bbe8; border-bottom:solid 1px #99bbe8;"></a></p><br>';
			/*
				hdr2 = '<style>table.type10 {width: 100%;border-collapse: collapse; text-align: left; line-height: 1.5;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;';
			    hdr2 += 'margin: 0px 0px; font-size:12px; overflow-x: auto;}';
				hdr2 += 'table.type10 th {padding: 6px;font-weight: bold;vertical-align: top;text-align: center;color: #fff;background: #666666;margin: 2px 2px;} ';
				hdr2 += '.grid01 th { background-color:#E9602C; height:24px; color:#fff; font-size:11px; font-family:Dotum; text-align:center; border-right:solid 1px #E9602C; border-bottom:solid 1px #E9602C; }';
				hdr2 += '.grid01 td {  height:24px; color:#E9602C; font-size:11px; font-family:Dotum; text-align:left; border-top:solid 1px #E9602C; border-right:solid 1px #E9602C; border-bottom:solid 1px #E9602C;font-weight:bold }';
				hdr2 += '</style>';
			*/
			//msg = msg.substr(0,s)+msg.substr(e);
			//clog(msg.substr(0,2400));
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
						<th style='font-size:1.0em; background-color:#E9602C;color:#ffffff;border:solid 1px #E9602C;text-align:center; cursor:pointer;'><a onclick=viewDtls1("<?= $item0[0]['bidNtceNo'] ?>","<?= $bidNtceOrd ?>") style="color:#ffffff">입찰공고</a></th>
						<td style='font-size:1.0em; cursor:pointer;color:#E9602C;border:solid 1px #E9602C;'>&nbsp;
							<a onclick=viewDtls1("<?= $item0[0]['bidNtceNo'] ?>","<?= $bidNtceOrd ?>") style='color:#E9602C;font-weight:bold; '> <?= $item0[0]['bidNtceNo'] ?>&nbsp;/&nbsp;<?= $item0[0]['bidNtceNm'] ?></a>
						</td>
					</tr>
				</tbody>
			</table>

			<?
			// 사정율 계산 ----------------------------------
			if ($pss == '입찰물품') $bidrdo = 'opnbidThng'; //$bsnsDivCd = '1'; // 물품
			else if ($pss == '입찰공사') $bidrdo = 'opnbidCnstwk'; //$bsnsDivCd = '1'; // 공사
			else if ($pss == '입찰용역') $bidrdo = 'opnbidservc'; //$bsnsDivCd = '1'; // 용역

			$itemr = $g2bClass->getSvrDataOpn($bidrdo, $bidNtceNo); // 예정가격,기초금액
			$json1 = json_decode($itemr, true);
			$sasungyul = 0;
			$plnprc = 0;
			$bssamt = '';
			$items = $json1['response']['body']['items'];
			if (count($items) > 0) {
				foreach ($items as $arr) {
					$plnprc = $arr['plnprc'];
					$bssamt = $arr['bssamt'];
					if ($bssamt != '' && $bssamt != 0) $sasungyul = $plnprc / $bssamt; // 예정가격/기초금액

				}
			}
			if ($plnprc != '') $plnprc = number_format($plnprc);
			if ($bssamt != '') $bssamt = number_format($bssamt);


			?>

			<!-- 입찰 결과 -->
			<!-- <div style='font-size:14px; color:blue;font-weight:bold'>- 낙찰 결과 data.go.kr API -</center> //이미지로 바 -by jsj 190320 -->
			<!--  <center><a href="/ulocawp/?page_id=1138"><img src="/img/bidResult_uloca.gif" boarder="0"></a></center>
-->

			<div id=totalrechrc>낙찰결과 (공공데이터포털 API) Total record=<?= count($item1) ?></div>
			<div id=LinKExplain align="left">
				<br>예정가격: <?= $plnprc ?> , 기초금액: <?= $bssamt ?>, 사정율: <?= number_format($sasungyul, 4, '.', '') ?>
				<br><br>✔︎<font color="red">[사업자번호]클릭</font>→입찰이력 <font color="red"> [업체명]클릭</font>→ 업체정보
			</div>

			<table class="type10" id="specData" width="100%">
				<thead>
					<tr>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="5%;">순위</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="15%;">사업자등록번호</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="25%;">업체명</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="10%;">대표자</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="15%;">입찰금액(원)</th>
						<th style='background-color:#666666;; height:16px; color:#fff; font-size:11px; font-family:Dotum; text-align:center;  ' width="10%;">투찰율(%)</th>
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
				$sql = " SELECT idx";
				$sql .= "  FROM openBidInfo ";
				$sql .= " WHERE bidNtceNo ='" . $bidNtceNo . "'";
				$sql .= "   AND bidNtceOrd ='" . $bidNtceOrd . "'";
				$result0 = $conn->query($sql);
				if ($row = $result0->fetch_assoc()) {
					$bididx = $row['idx'];
				}
				$openBidSeq_xxxx = "openBidSeq_" . date("Y");

				//echo $opengDt;
				$i = 1;
				$k = 1;
				if (count($item1) == 0) {
					$tr = "<tr>";
					$tr .= '<td colspan=7  style="text-align: center; color:red;"><br>DATA가 없습니다. 개찰이 안되었거나, 유찰이 되었을수 있습니다.<br><br></td>';
					$tr .= "</tr>";
					echo $tr;
				} else {
					foreach ($item1 as $arr) { //foreach element in $arr
						//---------------------------
						$k = (int) $arr["opengRank"]; //순위 - Null이 오는 경우 순서대로 순위를 매김
						if ($k == 0) $k = $i; 			// opengRank null 오는 경우, 순서대로 순위를 매김
						$rmrk = addslashes($arr['rmrk']);

						// rmrk 에 순위와 비고 같이 들어감 -by jsj 20200318
						if ($arr['rmrk'] == '') {  //openRank 비어있고, rmark 도 비어 있는 경우 순위만 대입
							$Rank_rmark = (string) $k;
						} else {
							$Rank_rmark = $arr['rmrk'];
						}

						if ($k == 1) { //$i == 0) {
							$tr = '<tr>';
							$tr .= '<td scope="row" style="text-align: center; color:red;">' . $k . '</td>';
							$tr .= '<td style="text-align: center; color:red;"><a onclick=\'compInfobyComp(' . $arr['prcbdrBizno'] . ')\'>' . $arr['prcbdrBizno'] . '</a></td>';
							$tr .= '<td style="color:red;"><a onclick=\'compInfo(' . $arr['prcbdrBizno'] . ')\'>' . $arr['prcbdrNm'] . '</a></td>';
							$tr .= '<td style="color:red;">' . $arr['prcbdrCeoNm'] . '</td>';
							if ($arr['bidprcAmt'] == '') $tr .= '<td style="color:red; text-align: right;" > </td>';
							else $tr .= '<td style="color:red; text-align: right;" >' . number_format($arr['bidprcAmt']) . '</td>';
							$tr .= '<td style="text-align: right; color:red;">' . $arr['bidprcrt'] . '</td>';
							$tr .= '<td style="text-align: center; color:red;">' . $arr['rbidNo'] . '</td>';
							$tr .= '<td style="color:red;">' . $arr['rmrk'] . '</td>';
							$tr .= '</tr>';

							//-------------------------------------------------
							//$openBidInfo 에 1순위 정보 업데이트 -by jsj 190601
							//-------------------------------------------------
							$sql1 = "UPDATE openBidInfo SET ";
							$sql1 .= " 	prtcptCnum = " . (int) $arr['prtcptCnum'] . ", ";
							$sql1 .= " 	bidwinnrNm = '" . addslashes($arr['prcbdrNm']) . "', ";
							$sql1 .= " 	bidwinnrBizno = '" . $arr['prcbdrBizno'] . "', ";
							$sql1 .= " 	sucsfbidAmt = '" . $arr['bidprcAmt'] . "', ";
							$sql1 .= " 	sucsfbidRate = '" . $arr['bidprcrt'] . "', ";
							$sql1 .= " 	rlOpengDt = '" . $arr['rlOpengDt'] . "', ";
							$sql1 .= " 	bidwinnrCeoNm = '" . $arr['prcbdrCeoNm'] . "', ";
							$sql .=  "  progrsDivCdNm = '" . "개찰완료" . "', ";  				// 1순위 들어가면 = 개찰완료
							$sql1 .= " 	modifyDT = now()";
							$sql1 .= " WHERE bidNtceNo ='" . $bidNtceNo . "' ";
							$sql1 .= "   AND bidNtceOrd ='" . $bidNtceOrd . "'; ";
							$conn->query($sql1);

							//---------------------------------------------------
							//$openBidSeq_xxxx 에 입찰이력 업데이트 -by jsj 190601
							//---------------------------------------------------
							$sql  = ' REPLACE INTO ' . $openBidSeq_xxxx . ' ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx)';
							$sql .= " VALUES ( '" . $bidNtceNo . "', '" . $bidNtceOrd . "', '" . $arr['rbidNo'] . "', '" . $arr['prcbdrBizno'] . "','" . $arr['bidprcAmt'] . "','" . $arr['bidprcrt'] . "',";
							$sql .= " '" . $arr['drwtNo1'] . "', '" . $arr['bidprcDt'] . "', '" . $Rank_rmark . "','" . $bididx . "')";
							$result = $conn->query($sql);
							//debug
							//echo $sql;

						} else {

							$tr = '<tr>';
							$tr .= '<td scope="row" style="text-align: center;">' . $k . '</td>';
							$tr .= '<td style="text-align: center;"><a onclick=\'compInfobyComp(' . $arr['prcbdrBizno'] . ')\'>' . $arr['prcbdrBizno'] . '</a></td>';
							$tr .= '<td><a onclick=\'compInfo(' . $arr['prcbdrBizno'] . ')\'>' . $arr['prcbdrNm'] . '</a></td>';
							$tr .= '<td>' . $arr['prcbdrCeoNm'] . '</td>';
							if ($arr['bidprcAmt'] == '') $tr .= '<td> </td>';
							else  $tr .= '<td align=right>' . number_format($arr['bidprcAmt']) . '</td>';
							$tr .= '<td style="text-align: right;">' . $arr['bidprcrt'] . '</td>';
							$tr .= '<td style="text-align: center;">' . $arr['rbidNo'] . '</td>';
							$tr .= '<td>' . $arr['rmrk'] . '</td>';
							$tr .= '</tr>';

							//---------------------------------------------------
							//$openBidSeq_2019 에 입찰이력 업데이트 -by jsj 190601
							//---------------------------------------------------
							$sql  = ' REPLACE INTO ' . $openBidSeq_xxxx . ' ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx)';
							$sql .= " VALUES ( '" . $bidNtceNo . "', '" . $bidNtceOrd . "', '" . $arr['rbidNo'] . "', '" . $arr['prcbdrBizno'] . "','" . $arr['bidprcAmt'] . "','" . $arr['bidprcrt'] . "',";
							$sql .= " '" . $arr['drwtNo1'] . "', '" . $arr['bidprcDt'] . "', '" . $Rank_rmark . "','" . $bididx . "')";
							$result = $conn->query($sql);
						}
						echo $tr;
						$i += 1;
						//echo $sql;
					}
					//echo $sql1;
				}
				echo '</table>';

				?>
	</div>
	<!-- div class="btn_area" style='width:100% !important;' >
	<input type=button style='height:30px; width:120px; font-size:14px; line-height:30px; color:#fff; text-align:center; padding:2px; background-color:#438ad1;; border:0;vertical-align: middle; cursor:pointer;' value="닫  기" onclick="self.close()" />
</div -->
	<div id='emaildiv' style='visibility:hidden;position: fixed; top: <?= $mailintop ?>px; right: 298px; '>
		<input type="text" style='border:1px solid #000;width:200px' name="emailid" id="emailid" autocomplete="on" placeholder="email 주소" onkeypress="if(event.keyCode==13) {mailMe3(); return false;}" value="<?= $current_user->user_email ?>" />
	</div>
	<!-- 이메일 보내기 삭제, 하루 300건 이상 못보냄 -by jsj 190518   
<div style='position: fixed; top: <?= $mailtop ?>px; right: 210px;' class="btn_areas"><a onclick="mailMe3();" class="search">이메일</a></div>
-->

	<div style='position: fixed; top: 115px; right: 210px;' class="btn_areas"><a onclick="locaciotn.href='https://uloca23.cafe24.com'" class="search">통합검색</a></div>
	<div style='position: fixed; top: 115px; right: 125px;' class="btn_areas"><a onclick="copyURL();" class="search">링크복사</a></div>
	<div style='position: fixed; top: 115px; right: 40px;' class="btn_areas"><a onclick="self.close();" class="search">닫 기</a></div>
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
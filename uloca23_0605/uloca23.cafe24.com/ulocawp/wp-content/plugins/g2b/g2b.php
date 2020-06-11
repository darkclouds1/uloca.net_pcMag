<?php
/*
 Plugin Name: g2b_tst : 나라장터 입찰정보&기업검색 (uloca23)
 Plugin URI: http://uloca.net/g2b/scsBid.php
 Description: 워드프레스 나라장터 입찰정보 입니다.
 Version: 1.0
 Author: Monolith
 Author URI: http://uloca.net/g2b/scsBid.php
 */
//include 'http://uloca.net/g2b/scsBid.php';

function g2bShortCode()
{
	//----------------------------
	// 상세검색은 --> newG2B.php
	//----------------------------
	//	session_start();
	@extract($_GET);
	@extract($_POST);
	date_default_timezone_set('Asia/Seoul');
	require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php');
	require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');

	$g2bClass = new g2bClass;
	$dbConn = new dbConn;
	$conn = $dbConn->conn();
	$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
	
	global $current_user;
	$current_user = wp_get_current_user();
	$_SESSION['current_user'] = $current_user;

	// echo ("ln32::current_user=" .$current_user );
	// setcookie('resedi',$_SESSION['current_user']->user_login,time() + (86400)); // 1day
	//  $current_user = get_currentuserinfo();
	//      echo 'Username: ' . $current_user->user_login . "\n";
	//  echo 'User email: ' . $current_user->user_email . "\n";
	//  echo 'User level: ' . $current_user->user_level . "\n";
	//  echo 'User first name: ' . $current_user->user_firstname . "\n";
	//  echo 'User last name: ' . $current_user->user_lastname . "\n";
	//  echo 'User display name: ' . $current_user->display_name . "\n";
	//  echo 'User ID: ' . $current_user->ID . "\n";

	// --------------------------------- log
	//pgDtlCD		01:용역, 02:공사:, 03:물품, 04:사전용역, 05:사공, 06:사물
	//keyDtlCD		01:공고명, 02:수요기관, 03:공고+수요기관, 04:기업검색, 05:차트보기, 06:공고상세, 07:수요기관재검색,
	//				08:낙찰결과, 09:기업응찰기록, 10:업체정보, 11:사용자 사용현황, 12:메일발송

	$id = $_SESSION['current_user']->user_login;
	// $rmrk = $mobile . ' ' . $_SERVER['HTTP_USER_AGENT'];
	//$dbConn->logWrite($id,$_SERVER['REQUEST_URI'],$rmrk);
	// --------------------------------- log

	$SearchCount = $dbConn->countLog($conn, $_SERVER['REMOTE_ADDR']);
	//echo 'my ip='.$_SERVER['REMOTE_ADDR'].' cnt = '.$SearchCount;

	//if(isset($_GET['endDate'])) $endDate = $_GET['endDate'];
	//else $endDate = NULL;
	if (!isset($endDate)) $endDate = date("Y-m-d"); //$today;
	if (!isset($startDate)) {
		$timestamp = strtotime("-1 year");
		$startDate = date("Y-m-d", $timestamp);
	}
	if (!isset($lastStartDate)) {
		$timestamp = strtotime("-10 year");
		$lastStartDate = date("Y-m-d", $timestamp);
	}
	$sYear = date("Y");
	$i = 0;
	//echo $sYear;
	while ($sYear >= 2016) {
		$ssYear[$i] = $sYear;
		//echo $sYear.'/'.$ssYear[$i].'-';
		$i++;
		//$timestamp = strtotime("-1 year");
		$sYear--; //= date("Y", $timestamp);
	}
	$sYear = date("Y");

	// login 여부 0 or 1(login &&  기한 termDate>now())
	// PayUser01M:: termDate (99권한이라도 기한termDate 기한내에서만 유효) -by jsj 20200427
	$loginSW = $dbConn->getMemberFee($conn, $id);

	//echo 'loginSW='.$loginSW;
	/* ---------------------------------------------- graph ---------------------------- * /
	 if ($frrange == '') $frrange =75;
	 if ($torange == '') $torange =99;
	 if ($bidthing == '' && $bidcnstwk == '' && $bidservc == '') $bidall=1;
	 $pss = '전체';
	 if ($bidthing == 1) $pss = '물품';
	 if ($bidcnstwk == 1) $pss = '공사';
	 if ($bidservc == 1) $pss = '용역';
	 $range = 1;
	 if ($bidall == 1) { // 통합
	 if ($range == 1) {
	 $sql = "select ROUND(tuchalrate1) tr, count(*) cnt from forecastData where ROUND(tuchalrate1)>='".$frrange."' and ROUND(tuchalrate1)<='".$torange."' group by ROUND(tuchalrate1)";
	 } else if ($range == 2) {
	 $sql = "select ROUND(tuchalrate1,1) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,1)>='".$frrange."' and ROUND(tuchalrate1,1)<='".$torange."' group by ROUND(tuchalrate1,1)";
	 } else if ($range == 3) {
	 $sql = "select ROUND(tuchalrate1,2) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,2)>='".$frrange."' and ROUND(tuchalrate1,2)<='".$torange."' group by ROUND(tuchalrate1,2)";
	 } else if ($range == 4) {
	 $sql = "select ROUND(tuchalrate1,3) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,3)>='".$frrange."' and ROUND(tuchalrate1,3)<='".$torange."' group by ROUND(tuchalrate1,3)";
	 }
	 $stmt = $conn->stmt_init();
	 $stmt = $conn->prepare($sql);
	 //$stmt->bind_param("dd", $frrange, $torange);
	 }
	 
	 else { // 물품,공사,용역
	 if ($range == 1) {
	 $sql = 'select ROUND(tuchalrate1) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1)>=? and ROUND(tuchalrate1)<=? group by ROUND(tuchalrate1)';
	 } else if ($range == 2) {
	 $sql = 'select ROUND(tuchalrate1,1) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,1)>=? and ROUND(tuchalrate1,1)<=? group by ROUND(tuchalrate1,1)';
	 } else if ($range == 3) {
	 $sql = 'select ROUND(tuchalrate1,2) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,2)>=? and ROUND(tuchalrate1,2)<=? group by ROUND(tuchalrate1,2)';
	 } else if ($range == 4) {
	 $sql = 'select ROUND(tuchalrate1,3) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,3)>=? and ROUND(tuchalrate1,3)<=? group by ROUND(tuchalrate1,3)';
	 }
	 $stmt = $conn->stmt_init();
	 $stmt = $conn->prepare($sql);
	 //$stmt->bind_param("sdd", $pss,$frrange, $torange);
	 }
	 
	 
	 if (!$stmt->execute()) return $stmt->errno;
	 $rowCount = $stmt->num_rows;
	 $fields = $g2bClass->bindAll($stmt);
	 
	 $json_string = $g2bClass->rs2Json11($stmt, $fields);
	 
	 / * ---------------------------------------------- graph ---------------------------- */
?>
	<!DOCTYPE html>
	<html>

	<head>
		<!-- naver SEO -by jsj 20200106-->
		<title>나라장터 입찰정보 기업검색</title>
		<meta name="description" content="나라장터 입찰정보 조달청 입찰기업 기업상세정보 낙찰정보 낙찰이력 크레탑 한국기업데이터 ">
		<meta name="google-site-verification" content="l7HqV1KZeDYUI-2Ukg3U5c7FaCMRNe3scGPRZYjB_jM" />
		<meta name="msvalidate.01" content="5D12479BBA55E7ECB0C1B1EF4CC4218C" />
		<title>Your SEO optimized title</title>

		<meta property="og:type" content="website">
		<meta property="og:title" content="유로카닷넷:: 나라장터 입찰정보 기업검색">
		<meta property="og:description" content="유로카닷넷:: 나라장터 입찰정보 기업검색 조달청 입찰기업 낙찰이력">
		<meta property="og:image" content="http://uloca.net/uloca.jpg">
		<meta property="og:url" content="http://uloca.net">

		<meta name="twitter:card" content="summary">
		<meta name="twitter:title" content="유로카닷넷:: 나라장터 입찰정보 기업검색">
		<meta name="twitter:description" content="유로카닷넷:: 나라장터 입찰정보 기업검색 조달청 입찰기업 낙찰이력">
		<meta name="twitter:image" content="http://www.uloca.net/uloca.jpg">
		<meta name="twitter:domain" content="유로카닷넷">
		<!-- naver SEO -by jsj 20200106-->

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta name="google-site-verification" content="l7HqV1KZeDYUI-2Ukg3U5c7FaCMRNe3scGPRZYjB_jM" />

		<!--
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	-->
		<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?version=20190102" />
		<link rel="stylesheet" href="/jquery/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css" />
		<link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css" />

		<style>
			.btnorange1 {
				height: 25px;
				width: 80px;
				font-size: 14px;
				line-height: 25px;
				color: #fff;
				text-align: center;
				background-color: #E9602C;
				border: 0;
				vertical-align: middle;
				cursor: pointer;
			}
		</style>

		<script src="/dhtml/codebase/dhtmlx.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="/jquery/jquery.min.js"></script>
		<script src="/jquery/jquery-ui.min.js"></script>
		<script src="/include/JavaScript/tableSort.js"></script>
		<script src="/js/common.js?version=20190203"></script>

		<script src="/g2b/g2b.js"></script>			
		<script src="/g2b/g2b_2019.js"></script>	
		<script src="/g2b/g2bCookie.js"></script>

		<script>
			var SearchCounts = '<?= $SearchCount ?>'; // 검색회수
		</script>
	</head>

	<body onload="init()">
		<!-- onload='reinit()'> <!-- onscroll='viewScroll()' -->
		<!-- ------------------ 검색창 ---------------------------------------------------------- -->

		<div style='position: fixed; top: 140px; right: 10px; color: #000000;'><a href="#top">↑</a></div>
		<div style='position: fixed; top: 180px; right: 10px; color: #000000;'><a href="#buttom">↓</a></div>
		<!-- <form action="g2b.php" name="myForm" id="myForm" method="post" >  -->
		<form action="g2b.php" name="myForm" id="myForm" method="get" autocomplete="on">
			<!-- get 방식 테스트 -by jsj 190318  -->
			<div id="contents">
				<div class="detail_search">
					<?
					if ($mobile == "Mobile") {
					?>

						<table align=center cellpadding="0" cellspacing="0" width="700px" id='choice'>
							<colgroup>
								<col style="width:25%;" />
								<col style="width:auto;" />
							</colgroup>

							<tbody>

								<tr>
									<th>입찰정보</th>
									<td>&nbsp;
										<input class="input_style2" autocomplete="kwd" type="text" name="kwd" id="kwd" size='25' style="ime-mode:active;" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" onclick="chBack(1)" maxlength="50" style="width:80%;" placeholder="' ex.'정보 감리? 서울' <= 키워드 or 공고번호? 수요기관  '" />
									</td>

								</tr>

								<tr>
									<th>기업검색</th>
									<td>&nbsp;
										<input class="input_style2" autocomplete="compname" type="text" name="compname" id="compname" size='25' value="" style="ime-mode:active;" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" onclick="chBack(2)" maxlength="50" style="width:80%;" placeholder="' 업체명 ' or ' 대표자명 '" />
									</td>
								</tr>
								<input type="hidden" name="id" id="id" value="<?= $id ?>" />
								<input type="hidden" name="lastStartDate" id="lastStartDate" value="<?= $lastStartDate ?>" />
								<input type="hidden" name="startDate" id="startDate" value="<?= $startDate ?>" />
								<input type="hidden" name="endDate" id="endDate" value="<?= $endDate ?>" />
								<input type="hidden" name="dminsttNm" id="dminsttNm" value="" />

								<!-- tr>
				<th>검색년도</th>
				<td>
				<!-- 입찰/사전정보 물품/공사/용역 -- >
					<select name="syear" id="syear">
						<?
						for ($i = 0; $i < count($ssYear); $i++) {
							if ($sYear == $ssYear[$i]) echo "<option value='$ssYear[$i]'selected='selected' >$ssYear[$i]</option>";
							else echo "<option value='$ssYear[$i]'>$ssYear[$i]</option>";
						}
						?>
					</select>
				</td>
			</tr -->
						</table>
					<? } else { ?>

						<table align=center cellpadding="0" cellspacing="0" width="700px" id='choice'>
							<colgroup>
								<col style="width:20%;" />
								<col style="width:80%;" />
							</colgroup>
							<tbody>
								<tr>
									<th>입찰정보</th>
									<td>&nbsp;
										<input class="input_style2" autocomplete="kwd" type="text" name="kwd" id="kwd" size='60' style="ime-mode:active;" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" onclick="chBack(1)" maxlength="50" style="width:80%;" size='50px' autofocus placeholder="ex.공고명or공고번호 ?[물음표] 수요기관 " />
									</td>
								<tr>
									<th>기업검색</th>
									<td colspan=5>&nbsp;
										<input class="input_style2" autocomplete="compname" type="text" name="compname" id="compname" size='60' style="ime-mode:active;" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" onclick="chBack(2)" maxlength="50" style="width:80%;" size='50px' placeholder="ex.기업명or사업번호 ?[물음표] 대표자명" />
									</td>
									<input type="hidden" name="id" id="id" value="<?= $id ?>" />
									<input type="hidden" name="lastStartDate" id="lastStartDate" value="<?= $lastStartDate ?>" />
									<input type="hidden" name="startDate" id="startDate" value="<?= $startDate ?>" />
									<input type="hidden" name="endDate" id="endDate" value="<?= $endDate ?>" />
									<input type="hidden" name="dminsttNm" id="dminsttNm" value="" />
									<!-- th>검색년도</th>
				<td>
				<!-- 입찰/사전정보 물품/공사/용역 -- >
					<select name="syear" id="syear">
						<?
						for ($i = 0; $i < count($ssYear); $i++) {
							if ($sYear == $ssYear[$i]) echo "<option value='$ssYear[$i]'selected='selected' >$ssYear[$i]</option>";
							else echo "<option value='$ssYear[$i]'>$ssYear[$i]</option>";
						}
						?>
					</select -->
								</tr>

								<!-- tr>
				<th>이메일</th>
				<td>
					<input class="input_style2" type="text" name="email" id="email" value="<?= $current_user->user_email ?>" maxlength="50" style="width:70%;" />
					
				</td>
			</tr -->
						</table>
					<? } ?>
					<!--  시작 searchajax()-->
					<div class="btn_area">
						<a onclick="searchajax();" class="search">검색</a>
						<a onclick="showhidegr();" class="search">Chart보기</a>
						<a onclick="copyURL();" class="search">링크복사</a>
						<a onclick="searchBidNoList()" class="search">관심입찰</a></br>
						<div id='bidKwd'></div>
						<div id='compKwd'></div>
						<!-- a onclick="mailMe2();" class="search">이메일</a>
						<a onclick="gotoComp();" class="search">기업정보</a><!-- a onclick="tableToExcel('bidData','bidData','bid_<?= $endDate ?>.xls')" class="search">엑셀</a -->
					</div>
				</div>
			</div>
		</form>

		<!-- ------------------------------------- chart ----------------------------------------------------- -->
		<div id='chartbox0' style="overflow:auto; width:100%; height:240px; padding:10px; background-color:#eeeeee;">
			<div id="chartbox" style="width:100%;height:220px;border:1px solid #c0c0c0;"></div>
		</div>
		<!-- ------------------------------------- chart ----------------------------------------------------- -->
		<p id='nochartmsg' style='display:none;color:blue;text-align:center; font-size:12px;'>기업정보는 그래프가 없습니다.</p>

		<a name="top"></a>
		<div id='useExplain'></div>
		<div id='linkExplain'></div>
		<div id='totalrec'></div>
		<div id='tables' style='width: 100%;'></div>
		<!-- 더보기 -->
		<a name="buttom"></a>
		<div class="btn_area" id='continueSearch' style='visibility: hidden;'>
			<a onclick="searchajaxmore();" class="search"><input type=button value="더보기"></a>
		</div>
		<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
			<img src='/g2b/loading3.gif' width='100px' height='100px'>
		</div>
		<form name="popForm">
			<input type="hidden" name="bidNtceNo" value="" />
			<input type="hidden" name="bidNtceOrd" />
			<input type="hidden" name="pss" />
			<input type="hidden" name="id" value="<?= $current_user->user_login ?>" />
			<input type="hidden" name="from" />
		</form>
		<form name="compInfoForm">
			<input type="hidden" name="id" value="<?= $current_user->user_login ?>" />
			<input type="hidden" name="compno" />
			<input type="hidden" name="opengDt" />
		</form>

		<!--  form id="searchFormCurrent" action="/g2b/baljuinfo.php" method="post" target="_blank"> -->
		<!--  get test -by jsj 190318 -->
		<form id="searchFormCurrent" action="/g2b/baljuinfo.php" method="get" target="_blank">
			<input id="bidNtceNm" name="bidNtceNm" type="hidden" value="사업명" />
			<input id="pss" name="pss" type="hidden" value="업무" />
			<input id="bidNtceNo" name="bidNtceNo" type="hidden" value="2019-02" />
			<input id="cntrctMthdNm" name="cntrctMthdNm" type="hidden" value="계약방법" />
			<input id="orderInsttNm" name="orderInsttNm" type="hidden" value="발주기관" />
			<input id="info" name="info" type="hidden" value="나라장터" />
			<input id="nticeDt" name="nticeDt" type="hidden" value="게시일시" />
			<input id="specCntnts" name="specCntnts" type="hidden" value="용도" />
			<input id="dtilPrdctClsfcNoNm" name="dtilPrdctClsfcNoNm" type="hidden" value="품명" />
			<input id="qtyCntnts" name="qtyCntnts" type="hidden" value="수량" />
			<input id="sumOrderAmt" name="sumOrderAmt" type="hidden" value="구매예정금액" />
			<input id="unit" name="unit" type="hidden" value="수량단위" />
			<input id="telNo" name="telNo" type="hidden" value="전화번호" />
			<input id="ofclNm" name="ofclNm" type="hidden" value="담당자" />
			<input id="deptNm" name="deptNm" type="hidden" value="부서명" />
		</form>

		<script>
			$("#kwd").click(function() {
				// $(this).select();
				$(this).val('');
			});
			$("#compname").click(function() {
				// $(this).select();
				$(this).val('');
			});

			//-----------------------------------------
			// 쿠키명: 쿠키저장 최근 조회한 입찰정보 키워드
			// kwd: 입찰정보, compName: 기업검색
			// 
			//-----------------------------------------
			var isRun = false;      // ajax 완료전에 다시 클릭 금지 (관심목록 DB저장 적용)
			var userId = "<?= $current_user->user_login ?>";	// 사용자
			
			// 최초 관심 목록 DB 조회 쿠키에 저장 type: C=업데이터, R=읽기
			function init() {
				// 쿠키 입찰검색
				cookieKwd.length = 0;
				cookieKwd = unescape(getCookieArray('kwd', cookieCnt));
				cookieKwd = cookieKwd.split(',');
				cookieLink = "";
				for (var key in cookieKwd) {
					if (cookieKwd[key] == "undefined" || cookieKwd[key] == undefined) continue;
					// if (cookieKwd[key] == undefined) continue;
					cntNum = key;	cntNum++;
					if (cntNum == 1) cntNum = '[입찰정보] ' + cntNum;
					cookieLink += cntNum +')<a onclick=\'viewKwd(\"' + cookieKwd[key] + '")\'>' + cookieKwd[key] + '&nbsp</a> ';
					// 마지막에 쿠키'삭제' 링크 추가
					if (key == cookieKwd.length -1 ) {
						delStr = "&nbsp&nbsp → <a onclick=\"delKwd(\'kwd\',1,1)\;\">삭제</a>";
						alert (delStr);
						cookieLink += delStr;
						//cookieLink += '&nbsp&nbsp → <a onclick=\'delKwd(\"kwd")\'>삭제</a>';
					}
				}
				document.getElementById('bidKwd').innerHTML = cookieLink

				// 쿠키 기업검색
				cookieCompName.length = 0; // 초기화
				cookieCompName = unescape(getCookieArray('compName', cookieCnt));
				cookieCompName = cookieCompName.split(',');
				cookieLink = "";
				for (var key in cookieCompName) {
					if (cookieCompName[key] == "undefined" || cookieCompName[key] == undefined) continue;
					cntNum = key;	cntNum++;
					if (cntNum == 1) cntNum = '[기업검색] ' + cntNum;
					cookieLink += cntNum + ')<a onclick=\'viewCompKwd(\"' + cookieCompName[key] + '")\'>' + cookieCompName[key] + '&nbsp</a> ';
					// 마지막에 쿠키'삭제' 링크 추가
					if (key == cookieCompName.length -1 ) {
						cookieLink += '&nbsp&nbsp → <a onclick=\'delKwd(\"compName")\'>삭제</a>';
					}
				}
				document.getElementById('compKwd').innerHTML = cookieLink;

				//--------------------------------------------------------------------------------------		
				// DB->쿠키로 저장 후 관심 자동 조회 됨, 로그인 상태로 id가 있는 경우만 해당, type: R=읽기
				//--------------------------------------------------------------------------------------
				cookieUse = false;	// 최초 자동 검색은 쿠키 저장 없음. (입찰정보 쿠키에 적용되는 것을 막기 위함)
				if (userId != '') {
					isRun = true;
					url = "./wp-content/plugins/g2b/updateBidNoList.php?bidNoList=" + bidNoList + "&userId=" + userId + "&type=" +"R";	// 관심 입찰번호, id
					getAjax(url, updateDbBidNoList);
					console.log(url);
				}
			}

			function reinit9() {
				myLineChart = new dhtmlXChart({
					view: "line",
					container: "chartbox",
					value: "#sales#",
					item: {
						borderColor: "#1293f8",
						color: "#ffffff"
					},
					line: {
						color: "#1293f8",
						width: 3
					},
					xAxis: {
						template: "'#year#"
					},
					offset: 0,
					yAxis: {
						start: 0,
						end: 100,
						step: 10,
						template: function(obj) {
							return (obj % 20 ? "" : obj)
						}
					}
				});
				myLineChart.parse(dataset, "json");
			}

			var mobile = '<?= $mobile ?>'; // "Mobile" : "Computer"
			var myLineChart;
			var parm = "";
			var curpos = 0;
			var lowidx = [75, 0, 0, 0, 0];
			var hiidx = [99, 0, 0, 0, 0];
			var chartbox0;

			function doplus() {
				if (curpos > 3) {
					alert("이미 최대 확대 입니다.");
					return;
				}
				curpos++;
				dif = eval(orgdata[1]['tr']) - eval(orgdata[0]['tr']);
				fridx = maxindex - 2;
				toidx = maxindex + 2;
				//document.getElementById('frrange').value = orgdata[fridx]['tr'];
				//document.getElementById('torange').value = orgdata[toidx]['tr'];
				lowidx[curpos] = orgdata[fridx]['tr'];
				hiidx[curpos] = orgdata[toidx]['tr'];
				clog(curpos + '/lowidx[curpos]=' + lowidx[curpos] + '/hiidx[curpos]' + hiidx[curpos]);
				refresh2();
			}

			function dominus() {
				if (curpos < 1) {
					alert("이미 시작 크기 입니다.");
					return;
				}
				curpos--;
				//document.getElementById('frrange').value = lowidx[curpos]; //orgdata[lowidx[cuspos]]['tr'];
				//document.getElementById('torange').value = hiidx[curpos]; //orgdata[hiidx[curpos]]['tr'];
				clog(curpos + '/lowidx[curpos]=' + lowidx[curpos] + '/hiidx[curpos]' + hiidx[curpos]);
				refresh2();
			}

			function refresh2() {
				makeparm2();
				parm = parm.substring(1);
				url = '/nwp/statistics3s.php'; //+parm;
				clog(url + '?' + parm);
				//location.href = url;
				getAjaxPost(url, drawchart, parm);
			}

			function reinit() {
				clog('reinit');
				chartbox0 = document.getElementById("chartbox0");
				chartbox0.style.display = 'none'; //'block';
				frrange = 75;
				torange = 99;
				curpos = 0;
				//makeparm();
				//url = '/nwp/statistics3s.php'; //+parm;
				//location.href = url;
				//clog(url);
				//move();
				//getAjax(url,drawchart);
			}

			function makeparm2() {
				//var sel = document.getElementById("kind1");
				var val = 'bid'; //sel.options[sel.selectedIndex].value;
				if (val == 'hrc') return; // 사전규격이면 안함...
				var form = document.myForm;
				parm = "?a=1&pall=1";
				parm += '&kwd=' + encodeURIComponent(form.kwd.value);
				parm += '&dminsttNm=' + encodeURIComponent(form.dminsttNm.value);
				parm += '&id=' + document.popForm.id.value;
				//clog(parm);
			}

			function drawchart(datas) {
				move_stop();
				showchart();
				data = JSON.parse(datas); //JSON.stringify(datas);
				orgdata = JSON.parse(datas);
				mydrawChart();
			}

			var maxindex;
			var maxvalue;
			var stepvalue;
			var data;
			var orgdata;
			function getMaxno() {
				max = 0;
				for (i = 0; i < data.length; i++) {
					if (eval(data[i]['cnt']) > max) {
						max = data[i]['cnt'];
						maxindex = i;
					}
					//clog ("data[i]['cnt']="+data[i]['cnt']+" max="+max);
					if (mobile == "Mobile" && i % 5 != 0) data[i]['tr'] = '';

				}
				if (max < 1000) {
					maxvalue = Math.ceil(max / 100) * 100;
					stepvalue = maxvalue / 5;
				} else if (max < 10000) {
					maxvalue = Math.ceil(max / 1000) * 1000;
					stepvalue = maxvalue / 5;
				} else if (max < 100000) {
					maxvalue = Math.ceil(max / 10000) * 10000;
					stepvalue = maxvalue / 5;
				} else if (max < 1000000) {
					maxvalue = Math.ceil(max / 100000) * 100000;
					stepvalue = maxvalue / 5;
				}
				return max;
			}

			function mydrawChart() {
				//clog('drawChart');
				chartbox = document.getElementById("chartbox");
				chartbox.innerHTML = '';
				//if (data.length<100) chartbox.style.width= '100%';
				//else chartbox.style.width= (data.length * 12) + 'px'; //2400px
				max = getMaxno();
				half = maxvalue / 2;
				//clog('maxvalue='+maxvalue+' stepvalue='+stepvalue);
				//myLineChart.clearAll();
				myLineChart = new dhtmlXChart({
					view: "spline",
					container: "chartbox",
					value: "#cnt#",
					label: "#cnt#",

					item: {
						borderColor: "#1293f8",
						color: function(obj) {
							if (obj.cnt > half) return "#E9602C";
							return "#66cc00";
						}, //"#ffffff"
						template: function(obj) {
							return number_format(obj) //(obj%20?"":obj)
						}
					},
					line: {
						color: "#1293f8",
						width: 3
					},
					xAxis: {
						title: "<span style='font: normal bold small 궁서 ; color: #E9602C;'>투찰율</span>",
						template: "#tr#"
					},
					offset: 0,
					yAxis: {
						start: 0,
						end: maxvalue,
						step: stepvalue,
						title: "<span style='font: normal bold small 궁서 ; color: #E9602C;'>투찰건수</span>",
						template: function(obj) {
							return number_format(obj) //(obj%20?"":obj)
						}
					},
					padding: {
						left: 65,
						bottom: 50
					}
				});
				myLineChart.parse(data, "json");

			}
			var tr1 = 75;
			var tr2 = 99;

			var data; //= <?= $json_string ?>;
			var orgdata; // = <?= $json_string ?>;
		</script>
		<script>
			var selcolor = '#eeeeee';
			var unselcolor = '#ffffff';
			var choiceTable = '';

			// setSort()를 위한 전역변수
			var head = document.getElementById('tables').innerHTML; 
			var table1 = document.createElement("table");			

			function chBack(ln) {
				<? if ($mobile == "Mobile") { ?>
					choiceTable = document.getElementById("choice");
					if (ln == 1) {
						choiceTable.rows[0].style.background = selcolor;
						choiceTable.rows[1].style.background = unselcolor;
						searchType = 1;
					} else {
						choiceTable.rows[0].style.background = unselcolor;
						choiceTable.rows[1].style.background = selcolor;
						searchType = 2;
					}
				<? } else { ?>
					choiceTable = document.getElementById("choice");
					if (ln == 1) {
						choiceTable.rows[0].style.background = selcolor;
						choiceTable.rows[1].style.background = unselcolor;
						searchType = 1;
					} else {
						choiceTable.rows[0].style.background = unselcolor;
						choiceTable.rows[1].style.background = selcolor;
						searchType = 2;
					}
				<? } ?>
				//	document.getElementById('totalrec').innerHTML = '';
			}

		</script>

		<script>
			var mobile = '<?= $mobile ?>'; // "Mobile" : "Computer"
			//mobile = "Mobile";
			//alert(mobile);
			var today = '<?= $endDate ?>';

			function gotoComp() {
				url = '?page_id=408';
				location.href = url;
			}

			function gotoComp2(comp) {
				url = '?page_id=1557&compno=' + comp;
				location.href = url;
			}

			function chkAllFunc(obj) {
				tf = obj.checked;
				//alert(tf);
				var form = document.myForm;
				form.bidthing.checked = tf;
				form.bidcnstwk.checked = tf;
				form.bidservc.checked = tf;
				form.chkHrc.checked = tf;
			}

			// table1 헤드정보
			var table1;
			// 입찰정보 -----------------------------------------------
			var col = ["번호", "구분", "공고번호(→나라장터)", "공고명", "'??'계약방법(or)", "추정가격", "공고일", "'?'수요기관(or)", "낙찰기업(→낙찰기록)", "개찰일시<br>(→개찰결과)", "찜하기"];
			var col2 = ['', 'pss', 'bidNtceNo', 'bidNtceNm', 'cntrctCnclsMthdNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidwinnrNm', 'opengDt', 'check']; //, 'pss' ];
			var col3 = ['c', 'c', 'c', 'l', 'l', 'r', 'd', 'l', 'c', 'd', 'c']; //,'c' ];
			var colw = ['5%', '6%', '10%', '20%', '10%', '7%', '7%', '10%', '9%', '9%', '5%']; //, '10%' ]; // width

			// 사전규격 -----------------------------------------------
			var colsx = ["번호", "등록번호", "품명", "계약방법", "예산금액", "등록일", "수요기관", "마감일시"]; //,"구분"];
			var colsx2 = ['', 'bidNtceNo', 'bidNtceNm', 'cntrctCnclsMthdNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt']; //, 'pss' ];
			var colsx3 = ['c', 'c', 'l', 'l', 'r', 'd', 'l', 'd']; //,'c' ];
			var colsxw = ['5%', '5%', '25%', '20%', '10%', '10%', '10%', '15%']; //, '10%' ]; // width

			// 기업검색 -----------------------------------------------
			var colc = ["번호", "사업자등록번호<br>(→ 응찰기록)", "업체명 (→ 기업정보)", "대표자"]; //,"응찰건수"];
			var colc2 = ['', 'compno', 'compname', 'repname']; //, 'cnt' ];
			var colc3 = ['c', 'c', 'l', 'c']; //, 'r' ];
			var colcw = ['15%', '15%', '50%', '20%'] //, '10%' ]; // width

			var durationatonce = [3, 5, 30, 30, 30, 30, 30, 30, 30, 30, 60, 60];
			var durationIndex = 0;
			var dstart = '';
			var dend = '';
			var endSw = false;
			var eDate1 = '';
			var sDate1 = '';
			var lastStartDate = '2010';
			var doing = false;
			var doingDate = '';
			//$colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt');

			var items;
			var chkid = 'chk2';
			var ajaxCnt = 0;
			var idx = 0;
			// chart clear

			function nochart() {
				chartbox0 = document.getElementById("chartbox0");
				//chartbox.innerHTML = '<br><b><br><center><p style="color:blue;">사전규격과 기업정보는 그래프가 없습니다.</p></center>';
				chartbox0.style.display = 'none';
				document.getElementById("nochartmsg").style.display = 'block';
				move_stop();
			}

			function showchart() {
				chartbox0 = document.getElementById("chartbox0");
				chartbox0.style.display = 'block';
				document.getElementById("nochartmsg").style.display = 'none';
			}

			function hidechart() {
				chartbox0 = document.getElementById("chartbox0");
				chartbox0.style.display = 'none';
				document.getElementById("nochartmsg").style.display = 'none';
			}

			function showhidegr() {
				if (loginCheck() == false) return;

				//var sel = document.getElementById("kind1");
				var val = 'bid'; //sel.options[sel.selectedIndex].value; // 입찰 = bid 사전규격 = hrc
				if (val != 'bid' || searchType == 2) {
					alert('기업정보는 그래프가 없습니다.');
					return;
				}
				chartbox0 = document.getElementById("chartbox0");
				if (chartbox0.style.display == 'block') chartbox0.style.display = 'none';
				else {
					document.getElementById("nochartmsg").style.display = 'none';
					chartbox0.style.display = 'block';
					move();
					refresh2();
				}
			}

			//copy URL ============================================================
			function copyURL() {
				var ourl = "<?php echo wp_get_shortlink(get_the_ID()); ?>";
				var url = ourl + parm;
				try {
					shortURL(url);
				} catch (e) {
					alert('Error:'.e);
				}
			}

			//------------------
			// 통합검색 전용
			//------------------
			var val = '';
			function makeTable(data) {
				document.getElementById("nochartmsg").style.display = 'none';
				var searchUrl = '';
				// 기업정보
				if (searchType == 2) {
					searchComp(data);
					return;
				}
				// 입찰정보 - 통합검색 전용
				val = 'bid';
				parm = '&searchType=1&' + parm; //copyURL() 사용 -by jsj 190320
				if (mobile == "Computer") {
					if (val == 'bid') { // 입찰정보
						makeTableHead(col, col2, col3, colw);
						makeTabletr(data);
					}
				} else { // "Mobile"
					if (val == 'bid') {
						//makeTableHead(col);
						makeTabletr2bid(data);	// Mobile
					}
					document.getElementById('totalrec').innerHTML = "<font size='2em'>[" + String(SearchCounts) + "]total record=" + idx;
				}
				isRun = false  // 조회완료
				viewmore();
				move_stop();
				hidechart();
			}

			function viewmore() {
				cnts = document.getElementById('totalrec').innerHTML;
				document.getElementById('continueSearch').style.visibility = 'visible';
				//cnt = eval(cnts.substr(13));
				//alert('cnt='+cnt);
				/*if (searchType==1 && searchDuration[duridx] > -25) //cnt > 0 && cnt % cntonce == 0 )
				{
					document.getElementById('continueSearch').style.visibility = 'visible';
				} else document.getElementById('continueSearch').style.visibility = 'hidden';
				*/
			}

			function custonSort(a, b) {
				if (a.bidClseDt == b.bidClseDt) {
					return 0
				}
				return a.bidClseDt > b.bidClseDt ? -1 : 1;
			}

			function custonSortHrc(a, b) {
				if (a.opninRgstClseDt == b.opninRgstClseDt) {
					return 0
				}
				return a.opninRgstClseDt > b.opninRgstClseDt ? -1 : 1;
			}

			function makeTabletr2bid(datas) { // mobile
				if (datas == '') return;
				//clog(datas);
				var data;
				try {
					data = JSON.parse(datas);
				} catch (ex) {
					return;
				}

				//r = data.response;
				//alert('makeTabletr2bid');
				items = data.response.body.items;
				//if (items.length>1) items.sort(custonSort);
				var lic = document.getElementById("tables").innerHTML;
				var licn = '';
				for (var i = 0; i < items.length; i++) {
					try {
						/* <li>
								<a class="a1" href="/ep/invitation/publish/bidInfoDtl.do?bidno=20180903077&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1 ">김천시 강남북 연결도로 개설공사(총괄 및 1차분)-SEB거더<br /><font class="f1">조달청 대구지방조달청&#32;|&#32;경상북도 김천시<br />공고 : 20180903077-00&#32;|&#32;마감 : 2018/09/13 12:00</font> </a>
							</li>
							var col2 = [ '','check','bidNtceNo', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt', 'pss' ];

							*/
						//if (items[i]['bidNtceNo'] == '') continue;

						idx = idx + 1;
						//licn += '<li><a class="a1" href = "http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='+items[i]['bidNtceNo']+'&bidseq='+items[i]['bidNtceOrd']+'&releaseYn=Y&taskClCd=1 ">';
						pss = items[i]['pss']; //getPSS();
						if (pss.substr(0, 2) == '계획') {
							balju = true;
							bidClseDt = "&nbsp;&nbsp;&nbsp;&nbsp;<font color=red>" + pss + "</font>";
						} else {
							balju = false;
							bidClseDt = items[i]['bidClseDt'].substr(0, 10);
						}

						if (pss.substr(0, 2) == '사전') pss = "&nbsp;&nbsp;<font color=orange>" + pss + "</font>";
						else pss = "&nbsp;&nbsp;<font color=red>" + pss + "</font>";
						if (balju) {
							licn += '<li><a class="a1" onclick=openButton(' + idx + ')>';
							licn += items[i]['bidNtceNm'] + '<br /><font class="f1">' + items[i]['dminsttNm'] + '<br />공고:' + items[i]['bidNtceNo'] + '-' + items[i]['bidNtceOrd'] + ' ' + bidClseDt + '</font> </a>';
							licn += '<div id="link' + idx + '" style="display:none;">';
							licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="상세정보" onclick=\'viewBalju(' + i + ')\'></center>';
							licn += '</div>';
							licn += '</li>';
						} else if (items[i]['pss'].substr(0, 2) == '사전') {
							licn += '<li><a class="a1" onclick=openButton(' + idx + ')>';
							licn += items[i]['bidNtceNm'] + '<br /><font class="f1">' + items[i]['dminsttNm'] + '<br /> 금액: ' + number_format(items[i]['presmptPrce']) + ' ' + items[i]['locate'] + '<br />공고:' + items[i]['bidNtceNo'] + ' 마감:' + bidClseDt + pss + '</font> </a>';
							licn += '<div id="link' + idx + '" style="display:none;">';
							licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="상세정보" onclick=\'viewDtl("' + items[i]['bidNtceDtlUrl'] + '","' + items[i]['bidNtceNo'] + '","' + items[i]['bidNtceOrd'] + '")\'>&nbsp;<input type=button value="수요기관" onclick=\'viewscs("' + items[i]['dminsttNm'] + '")\'></center><br>';

							licn += '</div>';
							licn += '</li>';
						} else {
							licn += '<li><a class="a1" onclick=openButton(' + idx + ')>';
							licn += items[i]['bidNtceNm'] + '<br /><font class="f1">' + items[i]['dminsttNm'] + '<br /> 금액: ' + number_format(items[i]['presmptPrce']) + ' ' + items[i]['locate'] + '<br /> 공고:' + items[i]['bidNtceNo'] + '-' + items[i]['bidNtceOrd'] + ' 마감:' + bidClseDt + pss + '</font> </a>';
							licn += '<div id="link' + idx + '" style="display:none;">';
							licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="상세정보" onclick=\'viewDtl("' + items[i]['bidNtceDtlUrl'] + '","' + items[i]['bidNtceNo'] + '","' + items[i]['bidNtceOrd'] + '")\'>&nbsp;<input type=button value="수요기관" onclick=\'viewscs("' + items[i]['dminsttNm'] + '")\'><br>';
							licn += '<p ><input type=button value="낙찰결과" onclick=\'viewRslt("' + items[i]['bidNtceNo'] + '","' + items[i]['bidNtceOrd'] + '","' + items[i]['opengDt'] + '","' + items[i]['pss'] + '")\'>';
							licn += '&nbsp;<input type=button value="낙찰기업" onclick=\'viewComp("' + items[i]['bidNtceNo'] + '","' + items[i]['bidNtceOrd'] + '","' + bidClseDt + '")\'></p></center>';
							licn += '</div>';
							licn += '</li>';
						}

						//clog('licn = '+licn);			
						//if (today<=items[i]['opengDt'].substr(0,10)) return cell.innerHTML;
					} catch (ex) {
						clog(ex.message);
					}
				}
				//clog(licn);
				lic += licn;
				//alert(lic);
				document.getElementById("tables").innerHTML = lic;
			}

			function openButton(idx) {
				if (document.getElementById("link" + idx).style.display == 'inline') {
					document.getElementById("link" + idx).style.display = 'none'
				} else document.getElementById("link" + idx).style.display = 'inline';
			}

			function custonSorthrc(a, b) {
				if (a.opninRgstClseDt == b.opninRgstClseDt) {
					return 0
				}
				return a.opninRgstClseDt > b.opninRgstClseDt ? -1 : 1;
			}

			// 사전규격 공고목록 
			function makeTabletrhrc(datas) {
				// ADD JSON DATA TO THE TABLE AS ROWS.
				//json_result =  jdata.js_result[0]["rows"];
				//viewObject(data);
				//clog(datas);
				var data = JSON.parse(datas);
				//r = data.response;

				items = data.response.body.items;
				//clog('items.length='+items.length);
				if (items.length > 1) items.sort(custonSort); //hrc);

				//alert(items.length);
				for (var i = 0; i < items.length; i++) {
					try {
						//clog('col.length='+col.length);
						idx = idx + 1;
						tr = table1.insertRow(-1);
						var tabCells = tr.insertCell(-1);
						tabCells.innerHTML = idx;
						tabCells.setAttribute('style', 'text-align:center;');
						for (var j = 1; j < colsx.length; j++) {
							var tabCell = tr.insertCell(-1);
							tabCell.innerHTML = items[i][colsx2[j]];
							attr = '';
							if (colsx3[j] == 'c') attr += 'text-align:center;';
							else if (colsx3[j] == 'l') attr += 'text-align:left;';
							else if (colsx3[j] == 'r') {
								attr += 'text-align:right;';
								if (tabCell != null) tabCell.innerHTML = tabCell.innerHTML.format();
							} else if (colsx3[j] == 'd') {
								attr += 'text-align:center;';
								if (tabCell != null && tabCell.innerHTML.length > 10) tabCell.innerHTML = tabCell.innerHTML.substr(0, 10);
							}

							tabCell.setAttribute('style', attr);
							tabCell.innerHTML = setLinksx(items, i, j, tabCell);
						}
					} catch (ex) {}
				}

				// FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
				var divContainer = document.getElementById("tables");
				//divContainer.innerHTML = "";
				divContainer.appendChild(table1);
			}

			function getPSS() {
				var form = document.myForm;
				if (form.kind21.checked) pss = '물품';
				if (form.kind22.checked) pss = '공사';
				if (form.kind23.checked) pss = '용역';
				return pss;
			}


			function setLinksx(items, i, j, cell) { // 사전규격
				//if (i<2) clog('j='+j+'/'+cell.innerHTML);

				if (j == 1) {
					//cell.innerHTML = '<a href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='+items[i]['bidNtceNo']+'&bidseq='+items[i]['bidNtceOrd']+'&releaseYn=Y&taskClCd=5" target="_blank">' + cell.innerHTML + '</a>';
					cell.innerHTML = '<a onclick=\'viewDtls("' + items[i]['bidNtceNo'] + '")\'>' + cell.innerHTML + '</a>';
				} else if (j == 5) {
					eDate = new Date().toISOString().slice(0, 10);
					sDate = dateAddDel(eDate, -1, 'y');
					cell.innerHTML = '<a onclick=\'viewscs("' + items[i]['dminsttNm'] + '")\'>' + cell.innerHTML + '</a>';
				}

				return cell.innerHTML;
			}

			function makeTabletr2hrc(datas) { // mobile 사전규격
				if (datas == '') return;
				var data;
				try {
					data = JSON.parse(datas);
				} catch (ex) {
					return;
				}

				//r = data.response;
				//alert('makeTabletr2bid');
				items = data.response.body.items;
				//items.sort(custonSort);
				var lic = document.getElementById("tables").innerHTML;
				var licn = '';
				for (var i = 0; i < items.length; i++) {
					try {
						/* 
				var colsx = ["번호","check","등록번호","품명","예산금액","등록일","수요기관","낙찰결과","구분"];
				var colsx2 = [ '','check','bfSpecRgstNo', 'prdctClsfcNoNm', 'asignBdgtAmt', 'rgstDt', 'rlDminsttNm', 'opninRgstClseDt', 'pss' ];

				<li>
						<a class="a1" href="/ep/invitation/publish/bidInfoDtl.do?bidno=20180903077&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=1 ">김천시 강남북 연결도로 개설공사(총괄 및 1차분)-SEB거더<br /><font class="f1">조달청 대구지방조달청&#32;|&#32;경상북도 김천시<br />공고 : 20180903077-00&#32;|&#32;마감 : 2018/09/13 12:00</font> </a>
					</li>
					var col2 = [ '','check','bidNtceNo', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt', 'pss' ];
					$sql = 'insert into '.$openBidInfo.' (bidNtceNo, bidNtceOrd, bidNtceNm,';
					$sql .= 'presmptPrce,bidNtceDt,dminsttNm,bidClseDt,bidNtceDtlUrl,bidtype)';
					$sql .= "VALUES ('".$arr['bfSpecRgstNo']."', '', '" .addslashes($arr['prdctClsfcNoNm']). "','" . $arr['asignBdgtAmt'] . "','" . $arr['rgstDt'] . "',";
					$sql .= "'".addslashes($arr['rlDminsttNm'])."',  ";
					$sql .= "'".$arr['opninRgstClseDt']."', '".$arr['bidNtceNoList']."', '".$pss."')";
								*/
						idx = idx + 1;
						licn += '<li><a  class="a1"  onclick=openButton(' + idx + ')>';
						licn += items[i]['bidNtceNm'] + '<br /><font class="f1">' + items[i]['dminsttNm'] + '<br />공고 : ' + items[i]['bidNtceNo'] + ' 마감 : ' + items[i]['bidClseDt'].substr(0, 10) + '</font> </a>';
						licn += '<div id="link' + idx + '" style="display:none;">';
						licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="상세정보" onclick=\'viewDtls("' + items[i]['bidNtceNo'] + '")\'>&nbsp;<input type=button value="수요기관" onclick=\'viewscs("' + items[i]['dminsttNm'] + '")\'><br>';
						//licn += '<p ><input type=button value="낙찰결과" onclick=\'viewRslt("'+items[i]['bidNtceNo']+'","'+items[i]['bidNtceOrd']+'","'+items[i]['bidClseDt']+'","'+pss+'")\'>&nbsp;<input type=button value="낙찰기업"></p></center>';
						licn += '</div>';
						licn += '</li>';
						//clog('licn = '+licn);			
						pss = getPSS();

						licn += '</div>';
						licn += '</li>';
					} catch (ex) {}

				}
				lic += licn;
				//alert(lic);
				document.getElementById("tables").innerHTML = lic;
			}

			function makeTabletrCompany(datas) {
				//clog (datas);
				if (datas == '') return;
				//clog('datas='+datas);
				var data;
				try {
					data = JSON.parse(datas);
				} catch (ex) {
					alert(ex);
					return;
				}
				//clog('makeTabletrCompany table1='+table1);
				//document.getElementById("tables").innerHTML = '';
				data = JSON.parse(datas);
				//r = data.response;
				if (table1 == '') table1 = document.createElement("table");
				var tbody = table1.createTBody();
				items = data.response.body.items;
				//items.sort(custonSort);
				//idx = 0;
				//alert(items.length);
				for (var i = 0; i < items.length; i++) {
					try {
						//clog('col.length='+col.length);
						idx = idx + 1;
						tr = tbody.insertRow(-1);
						var tabCells = tr.insertCell(-1);
						tabCells.innerHTML = idx;
						tabCells.setAttribute('style', 'text-align:center;');
						for (var j = 1; j < colc.length; j++) {
							var tabCell = tr.insertCell(-1);
							tabCell.innerHTML = items[i][colc2[j]];
							attr = '';
							if (colc3[j] == 'c') attr += 'text-align:center;';
							else if (colc3[j] == 'l') attr += 'text-align:left;';
							else if (colc3[j] == 'r') {
								attr += 'text-align:right;';
								if (tabCell != null) tabCell.innerHTML = tabCell.innerHTML.format();
							} else if (colc3[j] == 'd') {
								attr += 'text-align:center;';
								if (tabCell != null && tabCell.innerHTML.length > 10) tabCell.innerHTML = tabCell.innerHTML.substr(0, 10);
							}
							tabCell.setAttribute('style', attr);

							tabCell.innerHTML = setLinkcomp(items, i, j, tabCell);
						}
					} catch (ex) {}
				}
				// FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
				var divContainer = document.getElementById("tables");
				//divContainer.innerHTML = "";
				divContainer.appendChild(table1);
			}
			
			function searchajaxmore() {
				clog('searchajaxmore duridx=' + duridx);
				// https://uloca.net/ulocawp/?page_id=1134&searchType=1&kwd=%EB%B6%80%EC%82%B0&dminsttNm&compname&curStart=0&cntonce=100&bidinfo=1&id#top
				if (duridx >= 0) {
					searchajax0();
					return;
				}

				curStart += cntonce;
				var form = document.myForm;

				durationIndex = 0;
				endSw = false;
				ajaxCnt = 0;

				parm = 'kwd=' + encodeURIComponent(form.kwd.value); // +'&startDate='+encodeURIComponent(sDate1)+'&endDate='+eDate1;
				parm += '&compname=' + encodeURIComponent(form.compname.value);
				parm += '&curStart=' + curStart + '&cntonce=' + cntonce;
				if (searchType == 2) {
					parm += '&compinfo=1';
				} else {
					parm += '&bidinfo=1';
				}
				parm += '&bidhrc=bid'; //+val;
				parm += '&id=' + document.popForm.id.value;
				server = "/g2b/datas/publicData_2019.php";

				clog('searchajaxmore ' + server + "?" + parm);
				getAjaxPost(server, recv, parm);

				// pram 초기화가 안됨  -by jsj 190320 1356줄이 클리어하고 들어감.... by hmj
				parm = "";
			}

			loginSW = <?= $loginSW ?>;
			reinit();
		</script>

		<?
		/* 입찰정보 검색 ----------------------------------------------------- */
		//echo $kwd.'/'.$searchType;
		if (isset($kwd) && isset($searchType) && $searchType == 1) {
			//echo 'kwd='.$kwd;
		?>

			<script>
				var form = document.myForm;
				searchType = 1;
				kwd = decodeURIComponent('<?= $kwd ?>');
				form.kwd.value = kwd;
				searchajax0(); // 링크에도 발주계획 없이 보냄 //searchajax_balju(); (발주계획포함)
			</script>

		<?
		} else if (isset($compname) && isset($searchType) && $searchType == 2) {
		?>
			<script>
				var form = document.myForm;
				searchType = 2;
				compname = decodeURIComponent('<?= $compname ?>');
				form.compname.value = compname;
				searchajax0_1();
			</script>
	<?
		}
	}
	add_shortcode('g2bx', 'g2bShortCode');

	?>
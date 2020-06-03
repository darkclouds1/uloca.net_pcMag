<?php
/*
 Plugin Name: 유로카 KED기업정보 : ul_compSearch
 Plugin URI: http://uloca23.cafe24.com/ulocawp/?page_id=1557
 Description: ked 기업정보 조회
 Version: 1.0
 Author: Monolith
 Author URI: /ulocawp/?page_id=1557
 */

header('Content-Type: text/html; charset=UTF-8');

function ul_compSearch_ShortCode()
{
	global $current_user;
	$current_user = wp_get_current_user(); //get_currentuserinfo();
	$id = $current_user->user_login;

	require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php');
	$g2bClass = new g2bClass;

	$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
	if ($mobile == "Mobile") {
		$emaildiv_top1 = 180;
		$emaildiv_top2 = 48;
		$emaildiv_right1 = 10;
		$emaildiv_right2 = 53;
		$emaildiv_right3 = 180;
		$emaildiv_right4 = 240;
	} else {
		$emaildiv_top1 = 210;
		$emaildiv_top2 = 90;
		$emaildiv_right1 = 60;
		$emaildiv_right2 = 105;
		$emaildiv_right3 = 190;
		$emaildiv_right4 = 275;
	}
	require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
	$dbConn = new dbConn;

	$conn = $dbConn->conn(); //
	// --------------------------------- log
	$rmrk = '기업상세검색';
	$dbConn->logWrite2($id, $_SERVER['REQUEST_URI'], $rmrk, '', '09');
	// --------------------------------- log
?>

	<!doctype html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1, maximum-scale=2, user-scalable=no">

		<title>KED 기업정보 </title>

		<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?ver=1.3" />
		<link rel="stylesheet" href="/jquery/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css" />
		<link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css" />
		<script src="/jquery/jquery.min.js"></script>
		<script src="/jquery/jquery-ui.min.js"></script>
		<script src="/g2b/g2b.js"></script>
		<script src="/js/common.js"></script>
		<script src="/js/cmp.js?ver=2.16"></script>
		<script src="/dhtml/codebase/dhtmlx.js"></script>

		<script type="text/javascript">
			function searchFunction() {
				var xhr = new XMLHttpRequest;
				var postParam = "?userId="+ encodeURIComponent(document.getElementById("userId").value, true) + "&searchKwd=" + encodeURIComponent(document.getElementById("searchKwd").value, true);
				xhr.open("POST", "./wp-content/plugins/ul_compSearch/compSearchServlet.php" + postParam);

				//var url = "https://testkedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?user_id=ulocaonl&process=S&bzno=6098164815&cono=&pid_agr_yn=N&jm_no=E017";
				//xhr.open('GET', url, true);

				move();
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.setRequestHeader('Content-Type', 'application/xml');
				xhr.send(null);

				//alert ("xhr.send");
				//$('#xml').text(xhr.readyState + ", " + xhr.status);

				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						// return 값에 "운영자" 메시지가 있으면 alert
						if (xhr.responseText.match("운영자")) {
							alert (xhr.responseText);
							return;
						}
						makecomptable(this); //xhr.responseXML);
					} else if (xhr.readState == 1) {
						alert("readyState: " + xhr.readyState + ", status: " + xhr.status);
						// alert(xhr.responseText + "이 기능은 IE를 지원하지 않습니다. 다른 웹브라우저를 사용해 보세요!");
					}
					move_stop();
				}
			}

			window.onload = function() {
				//document.getElementById("searchKwd").value="1248100998"; //"1048126067"; //"6098164815"; //
				<? if ($_GET['compno'] == "") {
					$compno = "1248100998";
				} else $compno = $_GET['compno']; ?>
				searchFunction();
			}

			function makecomptable(xml) {
				//viewObject(xml.response);
				try {
					//mydrawChart(); // chart

					var xmlDoc = xml.response;
					parser = new DOMParser();
					xml = parser.parseFromString(xmlDoc, "text/xml");

					// clog(xmlDoc); //->CONTENTS->E017->bzno);

					// getElementsByTagName : 태그 호출
					// childNodes : 자식 노드
					// nodeValue : 해당 노드의 값(text)
					// var err = xml.getElementsByTagName('err_cd')[0].innerHTML;
					// var errInfo = xml.getElementsByTagName('err_info')[0].innerHTML;
					var err = xml.getElementsByTagName('err_cd')[0].textContent;
					var errInfo = xml.getElementsByTagName('err_info')[0].textContent;
					if (err != "OK" && err != '00') {
						clog("err_cd=" + err + " err_info=" + errInfo);
						alert("err_cd=" + err + " err_info=" + errInfo);
						return;
					}
				} catch (e) {
					// alert (xhr.responseText + "IE는 일부 기능을 지원하지 않습니다. 다른 Web Browser 를 사용해 보세요.");

					alert(e.message);
					return;
				}
				var E017 = xml.getElementsByTagName('CONTENTS')[0].getElementsByTagName('E017')[0]; //.childNodes[0].nodeValue;
				//viewObject(E017.getElementsByTagName('sttl_base_dt')[0]);
				//clog(name);
				//var enp_nm = E017.getElementsByTagName('enp_nm')[0].innerHTML;
				//bzno = E017.getElementsByTagName('bzno')[0].innerHTML; //innerHTML;

				//alert("enp="+enp_nm+" bzno="+bzno);
				make_basic(E017);
				make_etc(E017);
				make_fs_summ(E017); // 요약재무제표
				make_fr_summ(E017); // 요약재무비율
				make_cf_anal_summ(E017); // 요약현금흐름분석 
				// chart ----------------------------------------------
				make_fs_summ_json(E017);
				mydrawChart(E017); // chart

			}
			//copy URL ============================================================
			var parm = "&compno=" + <?= $compno ?>; // https://uloca23.cafe24.com/ulocawp/?page_id=1557&compno=1234567890
			function copyURL() {
				var ourl = "<?php echo wp_get_shortlink(get_the_ID()); ?>";
				var url = ourl + parm;
				try {
					shortURL(url);
				} catch (e) {
					alert('Error:'.e);
				}
			}

			function gotoComp() {
				url = '?page_id=1557&compno=6098164815'; //url = 'http://uloca23.cafe24.com/ulocawp/?page_id=337';
				//url = '?page_id=2074&compno=6098164815'; //url = 'http://uloca23.cafe24.com/ulocawp/?page_id=337';
				location.href = url;
			}
		</script>
	</head>

<form action="" name="myForm" id="myform" method="post" >
	<input type="hidden" id="userId" name="userId" value="<?=$current_user->user_login?>" />
</form>    

	<body>
		<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
			<img src='/g2b/loading3.gif' width='100px' height='100px'>
		</div>
		<div id='xml'>

		</div>
		<div class="container-fluid; " style="display:inline;">
			<!-- div id='totalCnt' class="col-xs-2">
			totalCnt=
		</div -->
			<div class="form-group row pull-right">사업자번호: &nbsp;
				<input id="searchKwd" onkeyup="" type="text" size="20" value="<?= $compno ?>" style="text-align:center" placeholder="사업자등록번호 10자리">
				<button class="btn-primary" onclick="searchFunction();" type="button">검색</button>
			</div>

		</div>

		<div style='position: fixed; width:120px; top: <?= $emaildiv_top1 ?>px; right: <?= $emaildiv_right2 ?>px;' class="btn_areas"><a onclick="copyURL();" class="search">링크복사</a></div>
		<div style='position: fixed; top: <?= $emaildiv_top1 ?>px; right: <?= $emaildiv_right1 ?>px;' class="btn_areas"><a onclick="self.close();" class="search">닫 기</a></div>

		<div class="detail_search">
			<table align=center class="grid06" style="text-align: left;  word-break:break-all;">
				<colgroup>
					<col style="width:25%;" />
					<col style="width:auto;" />
				</colgroup>
				<tbody>
					<tr>
						<th style='text-align:center; border-top:solid 1px #99bbe8; '>사업자등록번호</th>
						<td id='bzno'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>업체명</th>
						<td id='enp_nm'></td>
					</tr>

					<tr>
						<th style='text-align:center;'>법인주민등록번호</th>
						<td id='cono_pid'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>영문기업명</th>
						<td id='eng_enp_nm'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>대표자명</th>
						<td id='reper_nm'>&nbsp;</td>
					</tr>
					<tr>
						<th style='text-align:center;'>기업형태</th>
						<td id='enp_fcd'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>기업공개형태</th>
						<td id='ipo_cd'></td>
					</tr>

					<tr>
						<th style='text-align:center;'>설립일</th>
						<td id='estb_dt'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>결산월</th>
						<td id='acct_mm'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>그룹</th>
						<td id='group_nm'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>종업원수</th>
						<td id='em_cnt'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>업종코드</th>
						<td id='bzc_cd'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>업종명</th>
						<td id='bzc_nm'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>우편번호</th>
						<td id='zip'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>주소</th>
						<td id='addr1'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>상세주소</th>
						<td id='addr2'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>전화번호</th>
						<td id='tel_no'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>팩스번호</th>
						<td id='fax_no'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>홈페이지</th>
						<td id='hpage_url'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>대표email</th>
						<td id='email'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>주요상품</th>
						<td id='major_pd'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>주거래은행</th>
						<td id='mtx_bnk_nm'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>기업상태</th>
						<td id='enp_scd'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>기업상태변경일</th>
						<td id='enp_scd_chg_dt'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>기업규모</th>
						<td id='enp_sze'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>기업개요정보기준일</th>
						<td id='std_dt'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>기업신용등급</th>
						<td style='color:white;' id='cr_grd'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>기업신용등급설명</th>
						<td style='color:white;' id='cr_grd_dtl'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>등급구분</th>
						<td style='color:white;' id='grd_cls'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>평가(산출)일자</th>
						<td id='evl_dt'></td>
					</tr>
					<tr>
						<th style='text-align:center;'>재무기준일자</th>
						<td id='sttl_base_dt'></td>
					</tr>
					<tr>
						<th style='text-align:center;height:60px'>종합의견_기업체개요</th>
						<td id='opnn_enp'></td>
					</tr>
					<tr>
						<th style='text-align:center;height:60px'>종합의견_경영진</th>
						<td id='opnn_reper'></td>
					</tr>
					<tr>
						<th style='text-align:center;height:60px'>종합의견_영업현황</th>
						<td id='opnn_sales'></td>
					</tr>

			</table>

			<table align='center' class="grid05" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
				<colgroup>
					<col style="width:25%;" />
					<col style="width:25%;" />
					<col style="width:25%;" />
					<col style="width:auto;" />
				</colgroup>
				<tbody>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;'>기타정보</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fs_acct_dt1'>1</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fs1_acct_dt1'>2</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fs2_acct_dt1'>3</th>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>주요주주(지분율)</th>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='sth_nm1'>주요주주1(지분율1)</td>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='sth_nm2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='sth_nm3'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>관계회사(출자비율)</th>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='renp_nm1'>관계회사명(출자비율)</td>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='renp_nm2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='renp_nm3'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>주요구매처(거래비중)</th>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='customer_nm1'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='customer_nm2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='customer_nm3'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>주요판매처(거래비중)</th>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='supplier_nm1'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='supplier_nm2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:center;' id='supplier_nm3'></td>
					</tr>
			</table>

			<table align='center' class="grid05" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
				<colgroup>
					<col style="width:25%;" />
					<col style="width:25%;" />
					<col style="width:25%;" />
					<col style="width:auto;" />
				</colgroup>
				<tbody>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;'>요약재무제표</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fs_acct_dt'>최근결산년_결산일</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fs1_acct_dt'>최근결산직전년_결산일</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fs2_acct_dt'>최근결산직직전년_결산일</th>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>유동자산</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val1'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val1'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val1'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>비유동자산</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val2'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:red;'>자산총계</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs_val3'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs1_val3'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs2_val3'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>유동부채</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val4'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val4'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val4'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>비유동부채</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val5'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val5'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val5'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:red;'>부채총계</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs_val6'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs1_val6'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs2_val6'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>자본금</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val7'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val7'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val7'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:red;'>자본총계</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs_val8'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs1_val8'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs2_val8'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:red;'>매출액</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs_val9'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs1_val9'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs2_val9'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:red;'>매출총이익</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs_val10'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs1_val10'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs2_val10'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>영업이익</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val11'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val11'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val11'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>영업외수익</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val12'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val12'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val12'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>영업외비용</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val13'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val13'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val13'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>법인세비용차감전순손익</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val14'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val14'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val14'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>법인세비용</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs_val15'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs1_val15'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fs2_val15'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:red;'>당기순이익</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs_val16'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs1_val16'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right; color:red;' id='fs2_val16'></td>
					</tr>
				</tbody>
			</table>

			<div style="overflow:auto; width:100%; height:320px; padding:10px; background-color:#eeeeee;">
				<div id="chartbox" style="width:100%;height:300px;border:1px solid #c0c0c0;"></div>
			</div>

			<br>
			<table align='center' class="grid05" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
				<colgroup>
					<col style="width:25%;" />
					<col style="width:25%;" />
					<col style="width:25%;" />
					<col style="width:auto;" />
				</colgroup>
				<tbody>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;'>요약재무비율</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fr_acct_dt'>최근결산년_결산일</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fr1_acct_dt'>최근결산직전년_결산일</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='fr2_acct_dt'>최근결산직직전년_결산일</th>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>총자산증가율</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val1'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val1'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val1'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>매출액증가율</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val2'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>순이익증가율</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val3'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val3'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val3'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>영업이익율</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val4'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val4'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val4'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>ROE</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val5'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val5'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val5'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>ROIC</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val6'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val6'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val6'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>부채비율</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val7'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val7'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val7'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>이자보상배수</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val8'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val8'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val8'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>차입금의존도</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val9'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val9'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val9'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>매출채권회전율</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val10'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val10'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val10'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>재고자산회전율</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val11'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val11'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val11'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>총자본회전율</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr_val12'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr1_val12'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='fr2_val12'></td>
					</tr>
			</table>


			<table align='center' class="grid05" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
				<colgroup>
					<col style="width:25%;" />
					<col style="width:25%;" />
					<col style="width:25%;" />
					<col style="width:auto;" />
				</colgroup>
				<tbody>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;'>요약현금흐름분석</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='cf_acct_dt'>최근결산년_결산일</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='cf1_acct_dt'>최근결산직전년_결산일</th>
						<th style='border-top:solid 1px #99bbe8; text-align:center;' id='cf2_acct_dt'>최근결산직직전년_결산일</th>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>현금영업이익</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf_anal1'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf1_anal1'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf2_anal1'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>경상활동후의현금흐름</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf_anal2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf1_anal2'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf2_anal2'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>투자활동후의현금흐름</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf_anal3'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf1_anal3'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf2_anal3'></td>
					</tr>
					<tr>
						<th style='text-align:center; border: 1px solid #dddddd;'>현금흐름등급</th>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf_anal4'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf1_anal4'></td>
						<td style='border-top:solid 1px #99bbe8; text-align:right;' id='cf2_anal4'></td>
					</tr>

			</table>


		</div>
	</body>

	</html>
<?
}
add_shortcode('ul_compSearch', 'ul_compSearch_ShortCode');

?>
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
	//	session_start();
	@extract($_GET);
	@extract($_POST);
	date_default_timezone_set('Asia/Seoul');
	require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php');
	require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');

	$g2bClass = new g2bClass;
	$dbConn   = new dbConn;
	$conn     = $dbConn->conn();
	$platForm   = $g2bClass->MobileCheck(); // "mobile" : "computer"
	global $current_user;
	$current_user = wp_get_current_user();
	$_SESSION['current_user'] = $current_user;

	// --------------------------------- log
	//pgDtlCD		01:용역, 02:공사:, 03:물품, 04:사전용역, 05:사공, 06:사물
	//keyDtlCD		01:공고명, 02:수요기관, 03:공고+수요기관, 04:기업검색, 05:차트보기, 06:공고상세, 07:수요기관재검색,
	//				08:낙찰결과, 09:기업응찰기록, 10:업체정보, 11:사용자 사용현황, 12:메일발송
	$userId = $_SESSION['current_user']->user_login;

	// PayUser01M:: termDate (99권한이라도 기한termDate 기한내에서만 유효) -by jsj 20200427
	$loginSW = $dbConn->getMemberFee($conn, $userId);

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


?>
<!DOCTYPE html>
<html>

    <head>
        <!-- naver SEO -by jsj 20200106-->
        <title>나라장터 입찰정보 기업검색</title>
        <meta
            name="description"
            content="나라장터 입찰정보 조달청 입찰기업 기업상세정보 낙찰정보 낙찰이력 크레탑 한국기업데이터 ">
        <meta
            name="google-site-verification"
            content="l7HqV1KZeDYUI-2Ukg3U5c7FaCMRNe3scGPRZYjB_jM"/>
        <meta name="msvalidate.01" content="5D12479BBA55E7ECB0C1B1EF4CC4218C"/>
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

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
        <meta
            name="google-site-verification"
            content="l7HqV1KZeDYUI-2Ukg3U5c7FaCMRNe3scGPRZYjB_jM"/>

        <!-- <link rel="stylesheet"
        href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->
        <link
            rel="stylesheet"
            type="text/css"
            href="/g2b/css/g2b.css?version=20190102"/>
        <link rel="stylesheet" href="/jquery/jquery-ui.css">
        <link
            rel="stylesheet"
            type="text/css"
            href="/dhtml/codebase/fonts/font_roboto/roboto.css"/>
        <link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css"/>

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
        <!-- <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        -->
        <script src="/jquery/jquery.min.js"></script>
        <script src="/jquery/jquery-ui.min.js"></script>

        <script src="/include/JavaScript/tableSort.js"></script>
        <script src="/js/common.js?version=20190203"></script>

        <script src="/g2b/g2b_2019.js"></script>
        <script src="/g2b/g2b.js"></script>
        <script src="/g2b/g2bCookie.js"></script>

        <script>
            var SearchCounts = '<?= $SearchCount ?>'; // 검색회수

            // table1 헤드정보
            var table1;
            // 입찰정보 -----------------------------------------------
            var col = [
                "번호",
                "구분",
                "공고번호(→나라장터)",
                "공고명",
                "'??'계약방법(or)",
                "추정가격",
                "공고일",
                "'?'수요기관(or)",
                "낙찰기업(→낙찰기록)",
                "개찰일시<br>(→개찰결과)",
                "찜하기"
            ];
            var col2 = [
                '',
                'pss',
                'bidNtceNo',
                'bidNtceNm',
                'cntrctCnclsMthdNm',
                'presmptPrce',
                'bidNtceDt',
                'dminsttNm',
                'bidwinnrNm',
                'opengDt',
                'check'
            ]; //, 'pss' ];
            var col3 = [
                'c',
                'c',
                'c',
                'l',
                'l',
                'r',
                'd',
                'l',
                'c',
                'd',
                'c'
            ]; //,'c' ];
            var colw = [
                '5%',
                '6%',
                '10%',
                '20%',
                '10%',
                '7%',
                '7%',
                '10%',
                '9%',
                '9%',
                '5%'
            ]; //, '10%' ];  width
            // 사전규격 -----------------------------------------------
            var colsx = [
                "번호",
                "등록번호",
                "품명",
                "계약방법",
                "예산금액",
                "등록일",
                "수요기관",
                "마감일시"
            ]; //,"구분"];
            var colsx2 = [
                '',
                'bidNtceNo',
                'bidNtceNm',
                'cntrctCnclsMthdNm',
                'presmptPrce',
                'bidNtceDt',
                'dminsttNm',
                'bidClseDt'
            ]; //, 'pss' ];
            var colsx3 = [
                'c',
                'c',
                'l',
                'l',
                'r',
                'd',
                'l',
                'd'
            ]; //,'c' ];
            var colsxw = [
                '5%',
                '5%',
                '25%',
                '20%',
                '10%',
                '10%',
                '10%',
                '15%'
            ]; //, '10%' ];  width
            // 기업검색 -----------------------------------------------
            var colc = ["번호", "사업자등록번호<br>(→ 응찰기록)", "업체명 (→ 기업정보)", "대표자"]; //,"응찰건수"];
            var colc2 = ['', 'compno', 'compname', 'repname']; //, 'cnt' ];
            var colc3 = ['c', 'c', 'l', 'c']; //, 'r' ];
            var colcw = ['15%', '15%', '50%', '20%'] //, '10%' ];  width

            var durationatonce = [
                3,
                5,
                30,
                30,
                30,
                30,
                30,
                30,
                30,
                30,
                60,
                60
            ];
            var durationIndex = 0;
            var dstart = '';
            var dend = '';
            var endSw = false;
            var eDate1 = '';
            var sDate1 = '';
            var lastStartDate = '2010';
            var doing = false;
            var doingDate = '';
        </script>
    </head>

    <body onload="init();">
        <!-- onload='reinit()'> <!-- onscroll='viewScroll()' -->
        <!-- ------------------ 검색창
        ---------------------------------------------------------- -->

        <div style='position: fixed; top: 140px; right: 10px; color: #000000;'>
            <a href="#top">↑</a>
        </div>
        <div style='position: fixed; top: 180px; right: 10px; color: #000000;'>
            <a href="#buttom">↓</a>
        </div>
        <!-- <form action="g2b.php" name="myForm" id="myForm" method="post" > -->
        <form action="g2b.php" name="myForm" id="myForm" method="get" autocomplete="on">
            <!-- get 방식 테스트 -by jsj 190318 -->
            <div id="contents">
                <div class="detail_search">
                    <?
					if ($platForm == "mobile") {
					?>

                    <table align="center" cellpadding="0" cellspacing="0" width="700px" id='choice'>
                        <colgroup>
                            <col style="width:25%;"/>
                            <col style="width:auto;"/>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>입찰정보</th>
                                <td>&nbsp;
                                    <input
                                        class="input_style2"
                                        autocomplete="kwd"
                                        type="text"
                                        name="kwd"
                                        id="kwd"
                                        size='25'
                                        style="width:80%;"
                                        value=""
                                        onkeypress="if(event.keyCode==13) {searchajax(); return false;}"
                                        onclick="chBack(1)"
                                        maxlength="50"
                                        placeholder="' ex.'정보 감리? 서울' <= 키워드 or 공고번호? 수요기관  '"/>
                                </td>
                            </tr>
                            <tr>
                                <th>기업검색</th>
                                <td>&nbsp;
                                    <input
                                        class="input_style2"
                                        autocomplete="compname"
                                        type="text"
                                        name="compname"
                                        id="compname"
                                        size='25'
                                        value=""
                                        style="width:80%;"
                                        onkeypress="if(event.keyCode==13) {searchajax(); return false;}"
                                        onclick="chBack(2)"
                                        maxlength="50"
                                        placeholder="' 업체명 ' or ' 대표자명 '"/>
                                </td>
                            </tr>
                            <input type="hidden" name="userId" id="userId" value="<?= $userId ?>"/>
                            <input
                                type="hidden"
                                name="lastStartDate"
                                id="lastStartDate"
                                value="<?= $lastStartDate ?>"/>
                            <input type="hidden" name="startDate" id="startDate" value="<?= $startDate ?>"/>
                            <input type="hidden" name="endDate" id="endDate" value="<?= $endDate ?>"/>
                            <input type="hidden" name="dminsttNm" id="dminsttNm" value=""/>

                            <!-- tr> <th>검색년도</th> <td> <!-- 입찰/사전정보 물품/공사/용역 -- > <select name="syear"
                            id="syear"> <? for ($i = 0; $i < count($ssYear); $i++) { if ($sYear ==
                            $ssYear[$i]) echo "<option value='$ssYear[$i]'selected='selected'
                            >$ssYear[$i]</option>"; else echo "<option
                            value='$ssYear[$i]'>$ssYear[$i]</option>"; } ?> </select> </td> </tr -->
                        </table>
                    <? } else { ?>

                        <table align="center" cellpadding="0" cellspacing="0" width="700px" id='choice'>
                            <colgroup>
                                <col style="width:20%;"/>
                                <col style="width:80%;"/>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>입찰정보</th>
                                    <td>&nbsp;
                                        <input
                                            class="input_style2"
                                            autocomplete="kwd"
                                            type="text"
                                            name="kwd"
                                            id="kwd"
                                            size='50px'
                                            style="width:80%;"
                                            value=""
                                            onkeypress="if(event.keyCode==13) {searchajax(); return false;}"
                                            onclick="chBack(1)"
                                            maxlength="50"
                                            autofocus="autofocus"
                                            placeholder="ex.공고명or공고번호 ?[물음표] 수요기관 "/>
                                    </td>
                                    <tr>
                                        <th>기업검색</th>
                                        <td colspan="5">&nbsp;
                                            <input
                                                class="input_style2"
                                                autocomplete="compname"
                                                type="text"
                                                name="compname"
                                                id="compname"
                                                size='50px'
                                                style="width:80%;"
                                                value=""
                                                onkeypress="if(event.keyCode==13) {searchajax(); return false;}"
                                                onclick="chBack(2)"
                                                maxlength="50"
                                                placeholder="ex.기업명or사업번호 ?[물음표] 대표자명"/>
                                        </td>
                                        <input type="hidden" name="userId" id="userId" value="<?= $userId ?>"/>
                                        <input
                                            type="hidden"
                                            name="lastStartDate"
                                            id="lastStartDate"
                                            value="<?= $lastStartDate ?>"/>
                                        <input type="hidden" name="startDate" id="startDate" value="<?= $startDate ?>"/>
                                        <input type="hidden" name="endDate" id="endDate" value="<?= $endDate ?>"/>
                                        <input type="hidden" name="dminsttNm" id="dminsttNm" value=""/>
                                        <!-- th>검색년도</th> <td> <!-- 입찰/사전정보 물품/공사/용역 -- > <select name="syear"
                                        id="syear"> <? for ($i = 0; $i < count($ssYear); $i++) { if ($sYear ==
                                        $ssYear[$i]) echo "<option value='$ssYear[$i]'selected='selected'
                                        >$ssYear[$i]</option>"; else echo "<option
                                        value='$ssYear[$i]'>$ssYear[$i]</option>"; } ?> </select -->
                                    </tr>

                                    <!-- tr> <th>이메일</th> <td> <input class="input_style2" type="text" name="email"
                                    id="email" value="<?= $current_user->user_email ?>" maxlength="50"
                                    style="width:70%;" /> </td> </tr -->
                                </table>
                                <? } ?>
                                <!-- searchajax()-->
                                <div class="btn_area">
                                    <a onclick="searchajax();" class="search">검색</a>
                                    <!-- <a onclick="showhidegr();" class="search">Chart보기</a> -->
                                    <a onclick="copyURL();" class="search">링크복사</a>
                                    <a onclick="searchBidNoList()" class="search">관심입찰</a>
                                    <div id='bidKwd'></div>
                                    <div id='compKwd'></div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <a name="top"></a>
                    <div id='useExplain'></div>
                    <div id='linkExplain'></div>
                    <div id='totalrec'></div>
                    <div id='tables' style='width: 100%;'></div>
                    <!-- 더보기 -->
                    <a name="buttom"></a>
                    <div class="btn_area" id='continueSearch' style='visibility: hidden;'>
                        <a onclick="searchajaxmore();" class="search"><input type="button" value="더보기"></a>
                    </div>
                    <div
                        id='loading'
                        style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
                        <img src='/g2b/loading3.gif' width='100px' height='100px'>
                    </div>
                    <form name="popForm">
                        <input type="hidden" name="bidNtceNo" value=""/>
                        <input type="hidden" name="bidNtceOrd"/>
                        <input type="hidden" name="pss"/>
                        <input type="hidden" name="userId" value="<?= $current_user->user_login ?>"/>
                        <input type="hidden" name="from"/>
                    </form>
                    <form name="compInfoForm">
                        <input type="hidden" name="userId" value="<?= $current_user->user_login ?>"/>
                        <input type="hidden" name="compno"/>
                        <input type="hidden" name="opengDt"/>
                    </form>

                    <!-- form id="searchFormCurrent" action="/g2b/baljuinfo.php" method="post"
                    target="_blank"> -->
                    <!-- get test -by jsj 190318 -->
                    <form
                        id="searchFormCurrent"
                        action="/g2b/baljuinfo.php"
                        method="get"
                        target="_blank">
                        <input id="bidNtceNm" name="bidNtceNm" type="hidden" value="사업명"/>
                        <input id="pss" name="pss" type="hidden" value="업무"/>
                        <input id="bidNtceNo" name="bidNtceNo" type="hidden" value="2019-02"/>
                        <input id="cntrctMthdNm" name="cntrctMthdNm" type="hidden" value="계약방법"/>
                        <input id="orderInsttNm" name="orderInsttNm" type="hidden" value="발주기관"/>
                        <input id="info" name="info" type="hidden" value="나라장터"/>
                        <input id="nticeDt" name="nticeDt" type="hidden" value="게시일시"/>
                        <input id="specCntnts" name="specCntnts" type="hidden" value="용도"/>
                        <input
                            id="dtilPrdctClsfcNoNm"
                            name="dtilPrdctClsfcNoNm"
                            type="hidden"
                            value="품명"/>
                        <input id="qtyCntnts" name="qtyCntnts" type="hidden" value="수량"/>
                        <input id="sumOrderAmt" name="sumOrderAmt" type="hidden" value="구매예정금액"/>
                        <input id="unit" name="unit" type="hidden" value="수량단위"/>
                        <input id="telNo" name="telNo" type="hidden" value="전화번호"/>
                        <input id="ofclNm" name="ofclNm" type="hidden" value="담당자"/>
                        <input id="deptNm" name="deptNm" type="hidden" value="부서명"/>
                    </form>

                    <script>
                        $("#kwd").click(function () {
                            // $(this).select(); $(this).val(''); chBack(1)
                        });
                        $("#kwd").focus(function () {
                            chBack(1)
                        });

                        $("#compname").click(function () {
                            // $(this).select(); $(this).val(''); chBack(2)
                        });
                        $("#compname").focus(function () {
                            // $(this).select(); $(this).val('');
                            chBack(2)
                        });

                        // $colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce',
                        // 'bidNtceDt', 'dminsttNm', 'bidClseDt');
                        var items;
                        var chkid = 'chk2';
                        var ajaxCnt = 0;
                        var idx = 0;

                        // -----------------------------------------
                        // 쿠키명: 쿠키저장 최근 조회한 입찰정보 키워드 kwd: 입찰정보, compName: 기업검색
                        // -----------------------------------------
                        var isRun = false; // ajax 완료전에 다시 클릭 금지 (관심목록 DB저장 적용)
                        var userId = "<?= $current_user->user_login ?>"; // 사용자
                        var platForm = "computer";
                        var loginSW = 0;
                        // 최초 관심 목록 DB 조회 쿠키에 저장 type: C=업데이터, R=읽기
                        function init() {
                            var form = document.myForm;

                            // ----------------------------
                            // navigator.platform 확인
                            // ----------------------------
                            var computer = "win16|win32|win64|mac|ipad|MacIntel";
                            // var strMobile = "iPhone|iPod|Android|Linux armv8I|Blackberry|Opera
                            // Mini|Windows ce|Nokia|sony";
                            if (navigator.platform) {
                                if (computer.indexOf(navigator.platform.toLowerCase()) < 0) {
                                    // alert (computer.indexOf(navigator.platform.toLowerCase()));
                                    platForm = 'mobile';
                                } else {
                                    platForm = 'computer';
                                }
                            }
                            naviPlatform = navigator.platform;
                            if (navigator.platform == "MacIntel")
                                platForm = 'computer';

                            // debug platForm = 'computer'; 화면 refresh 전역변수 멈통 대비
                            userId = '<?= $userId ?>';
                            loginSW = <?= $loginSW ?>;
                            
                            // 링크 표시 쿠키 입찰정보
                            cookieKwd = unescape(getCookieArray('kwd', cookieCnt));
                            if (cookieKwd.length > 0 ) {
                                cookieKwd = cookieKwd.split(',');
                                document.getElementById('bidKwd').innerHTML = makeLink_cookieKwd('kwd', cookieKwd);
                            }
                            // 링크 표시 쿠키 기업검색
                            cookieCompName = unescape(getCookieArray('compName', cookieCnt));
                            if (cookieCompName.length > 0 ) {
                                cookieCompName = cookieCompName.split(',');
                                document.getElementById('compKwd').innerHTML = makeLink_cookieKwd('compName', cookieCompName);
                            }

                            //--------------------------------------------------------------------------------------
                            // DB->쿠키로 저장 후 관심 자동 조회 됨, 로그인 상태로 id가 있는 경우만 해당, type: R=읽기
                            //--------------------------------------------------------------------------------------
                            cookieUse = false; // 최초 자동 검색은 쿠키 저장 없음. (입찰정보 쿠키에 적용되는 것을 막기 위함)
                            isRun = true;
                            url = "./wp-content/plugins/g2b/updateBidNoList.php?bidNoList="
                                    + cookieBidNoList + "&userId=" + userId + "&type=R";
                            // 관심 입찰번호, id
                            getAjax(url, updateDbBidNoList);
                            console.log(url);
                            cookieUse = true;
                        }

                        var selcolor = '#eeeeee'; var unselcolor = '#ffffff'; var choiceTable = '';
                        function chBack(ln) {
                            if (platForm == "mobile") {
                                choiceTable = document.getElementById("choice");
                                if (ln == 1) {
                                    choiceTable
                                        .rows[0]
                                        .style
                                        .background = selcolor;
                                    choiceTable
                                        .rows[1]
                                        .style
                                        .background = selcolor;
                                    // choiceTable.rows[2].style.background = selcolor;
                                    // choiceTable.rows[3].style.background = unselcolor;
                                    searchType = 1;
                                } else {
                                    choiceTable
                                        .rows[0]
                                        .style
                                        .background = unselcolor;
                                    choiceTable
                                        .rows[1]
                                        .style
                                        .background = unselcolor;
                                    // choiceTable.rows[2].style.background = unselcolor;
                                    // choiceTable.rows[3].style.background = selcolor;
                                    searchType = 2;
                                }
                            } else { // computer
                                choiceTable = document.getElementById("choice");
                                if (ln == 1) {
                                    choiceTable
                                        .rows[0]
                                        .style
                                        .background = selcolor;
                                    choiceTable
                                        .rows[1]
                                        .style
                                        .background = unselcolor;
                                    searchType = 1;
                                } else {
                                    choiceTable
                                        .rows[0]
                                        .style
                                        .background = unselcolor;
                                    choiceTable
                                        .rows[1]
                                        .style
                                        .background = selcolor;
                                    searchType = 2;
                                }
                            }
                        }

                            function makeparm2() {
                                //var sel = document.getElementById("kind1");
                                var val = 'bid'; //sel.options[sel.selectedIndex].value;
                                if (val == 'hrc')
                                    return; // 사전규격이면 안함...
                                var form = document.myForm;
                                parm = "?a=1&pall=1";
                                parm += '&kwd=' + encodeURIComponent(form.kwd.value);
                                parm += '&dminsttNm=' + encodeURIComponent(form.dminsttNm.value);
                                parm += '&id=' + document.popForm.id.value;
                                //clog(parm);
                            }

                            var bidDataTable; var sortreplace; function setSort() {
                                bidDataTable = document.getElementById("specData");
                                sortreplace = replacement(bidDataTable);
                            }

                            function sortTD(index) {
                                sortreplace.ascending(index);
                            }

                            function reverseTD(index) {
                                sortreplace.descending(index);
                            }

                            var today = '<?= $endDate ?>'; function gotoComp() {
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

                            //---------------------------- //bitly Copy URL -by jsj 190325
                            function copyURL() {
                                var ourl = "<?php echo wp_get_shortlink(get_the_ID()); ?>";
                                var url = ourl + parm;
                                try {
                                    shortURL(url);
                                } catch (e) {
                                    alert('Error:'.e);
                                }
                            }

                            function makeTable(data) {
                                document
                                    .getElementById('useExplain')
                                    .innerHTML = "";
                                document
                                    .getElementById('totalrec')
                                    .innerHTML = "";
                                /* ----------------------------------------------------------------------------
					                기업정보 - computer 와 mobile 목록은 통일
				                ----------------------------------------------------------------------------- */
                                if (searchType == 2) {
                                    //searchUrl = searchUrl_path + '?page_id=1134&searchType=2&' + parm;
                                    parm = '&searchType=2&' + parm; //copyURL() 사용 -by jsj 190320

                                    // 기업정보는 그리드로 통일 -by jsj 20200609  if (platForm == "computer") {
                                    makeTableHead(colc, colc2, colc3, colcw);
                                    makeTabletrCompany(data);

                                    var useExplain = "<font size='2em'>✔︎︎[기업검색과 대표자명 동시검색] 기업명? 대표자명 → [?]물음표 뒤에 대표자명을 입력하세요.</font" +
                                            ">";
                                    useExplain += " ✔︎︎[수요기관검색] 공고명 ?수요기관  → [?]물음표 뒤에 수요기관 키워드를 입력하세요.(ex.정보 감리 ?서울)</br>"
                                    useExplain += " <font size='2em'>✔︎︎[사업자번호]클릭→기업의응찰기록  <font color=red>✔︎︎[업체명]</font>클릭→업체정보" +
                                            "팝업 </font>"; //-by jsj 링크설명
                                    if (loginSW)
                                        useExplain = ''; // 1:관리자 or 유료사용자는 설명없이 진행
                                    document
                                        .getElementById('useExplain')
                                        .innerHTML = "<font size='2em'>✔︎︎[기업검색과 대표자명 동시검색] 기업명? 대표자명 → [?]물음표 뒤에 대표자명을 입력하세요.</font" +
                                                "> "; //-by jsj 링크설명
                                    document
                                        .getElementById('totalrec')
                                        .innerHTML = "<font size='2em'>[" + naviPlatform + "::" + String(SearchCounts) +
                                                "]total record=" + idx;
                                    setSort();

                                    // chart clear
                                    clog('makeTable5');
                                    return;
                                }
                                /* ----------------------------------------------------------------------------
					입찰정보 - mobile 목록을 따로 표시
				----------------------------------------------------------------------------- */
                                var val = 'bid';
                                parm = '&searchType=1&' + parm; //copyURL() 사용 -by jsj 190320
                                if (platForm == "computer") {
                                    if (val == 'bid') { // 입찰정보
                                        makeTableHead(col, col2, col3, colw);
                                        makeTabletr(data);
                                    }
                                    var useExplain = "<font size='2em'>✔︎︎[공고명검색] 입찰정보를 검색하려면 공고명에 포함된 단어(=키워드)를 입력하세요. 제외하려면 키워드 앞에" +
                                            "[-]마이너스를 사용하세요.(ex.정보 감리 -공사)</br>";
                                    useExplain += " ✔︎︎[수요기관검색] 공고명 ?수요기관  → [?]물음표 뒤에 수요기관 키워드를 입력하세요.(ex.정보 감리 ?서울)</br>"
                                    useExplain += " ✔︎︎[계약방법검색] 공고명 ??계약방법 → [??]물음표 2개 뒤에 계약방법 키워드를 입력하세요.(ex. 정보 감리 ??총액), 제외하려" +
                                            "면 (ex.정보 감리 ??-협상) [-]마이너스를 사용하세요.</font>"; //-by jsj 링크설명
                                    if (loginSW)
                                        useExplain = ''; // 1:관리자 or 유료사용자는 설명없이 진행
                                    document
                                        .getElementById('useExplain')
                                        .innerHTML = useExplain;
                                    document
                                        .getElementById('totalrec')
                                        .innerHTML = "<font size='2em'>[" + naviPlatform + "::" + String(SearchCounts) +
                                                "]total record=" + idx;

                                    clog('val=' + val + ' table1.rows.length=' + table1.rows.length);
                                    if (val == 'bid' && table1.rows.length > 2)
                                        setSort();

                                        // 입찰정보 - mobile
                                    }
                                else {
                                    if (val == 'bid') {
                                        makeTabletr2bid(data);
                                    }
                                    //total record
                                    document
                                        .getElementById('totalrec')
                                        .innerHTML = "<font size='2em'>[" + naviPlatform + "::" + String(SearchCounts) +
                                                "]total record=" + idx; // + "<hr size=\'1px\'>";
                                }

                                isRun = false // 조회완료
                                viewmore();
                                move_stop();
                            }

                            function viewmore() {
                                cnts = document
                                    .getElementById('totalrec')
                                    .innerHTML;
                                document
                                    .getElementById('continueSearch')
                                    .style
                                    .visibility = 'visible';
                            }

                            // 더보기 헤드 구성
                            function makeTableHead(column, column2, columnattr, columnw) {
                                var head = document
                                    .getElementById('tables')
                                    .innerHTML;
                                //clog('searchType='+searchType+' head='+head);
                                if (head != '')
                                    return; // 제목란이 있으면..
                                table1 = document.createElement("table");
                                table1.setAttribute('class', 'type10');
                                table1.setAttribute('id', 'specData');
                                table1.setAttribute('style', 'width:100%'); //'width', '700px');
                                //clog('makeTableHead '+document.getElementById('specData').innerHTML);
                                var header = table1.createTHead();
                                var tr = header.insertRow(-1); // TABLE ROW.

                                for (var i = 0; i < column.length; i++) {
                                    var th = document.createElement("th"); // TABLE HEADER.
                                    if (column[i] == 'check')
                                        th.innerHTML = '<input type="checkbox" onclick="javascript:CheckAll(\'' +
                                                chkid + '\')">';
                                    else if (i != 0)
                                        th.innerHTML = column[i] + '<a onclick="sortTD (' + i + ')">▲</a><a onclick="re' +
                                                'verseTD (' + i + ')">▼</a>';
                                    else
                                        th.innerHTML = column[i];
                                    th.setAttribute('style', 'width:' + columnw[i] + ';');
                                    tr.appendChild(th);
                                    //clog(th.innerHTML);
                                }
                                if (document.getElementById('tables').innerHTML = '')
                                    document
                                        .getElementById('tables')
                                        .innerHTML = table1.outerHTML;
                                idx = 0;
                            }

                            function custonSort(a, b) {
                                if (a.bidClseDt == b.bidClseDt) {
                                    return 0
                                }
                                return a.bidClseDt > b.bidClseDt
                                    ? -1
                                    : 1;
                            }

                            function custonSortHrc(a, b) {
                                if (a.opninRgstClseDt == b.opninRgstClseDt) {
                                    return 0
                                }
                                return a.opninRgstClseDt > b.opninRgstClseDt
                                    ? -1
                                    : 1;
                            }

                            // ----------------------
                            // 입찰정보 - mobile
                            // ----------------------
                            var items;
                            function makeTabletr2bid(datas) { // mobile
                                if (datas == '')
                                    return;

                                //clog(datas);
                                var data;
                                try {
                                    data = JSON.parse(datas);
                                } catch (ex) {
                                    return;
                                }

                                items = data.response.body.items;
                                //if (items.length>1) items.sort(custonSort);
                                var lic = document
                                    .getElementById("tables")
                                    .innerHTML;
                                var licn = '';
                                licn += "<hr size=\'1px\'>";
                                for (var i = 0; i < items.length; i++) {
                                    try {
                                        idx = idx + 1;
                                        pss = items[i]['pss']; //getPSS();
                                        if (pss.substr(0, 2) == '계획') {
                                            balju = true;
                                            bidClseDt = "&nbsp;&nbsp;&nbsp;&nbsp;<font color=red>" + pss + "</font>";
                                        } else {
                                            balju = false;
                                            bidClseDt = items[i]['bidClseDt'].substr(0, 10);
                                        }

                                        if (pss.substr(0, 2) == '사전')
                                            pss = "&nbsp;&nbsp;<font color=orange>" + pss + "</font>";
                                        else
                                            pss = "&nbsp;&nbsp;<font color=red>" + pss + "</font>";
                                        if (balju) {
                                            licn += '<li><a class="a1" onclick=openButton(' + idx + ')>';
                                            licn += items[i]['bidNtceNm'] + '<br /><font class="f1">' + items[i]['dminsttNm'] + '<b' +
                                                    'r />공고:' + items[i]['bidNtceNo'] + '-' + items[i]['bidNtceOrd'] + ' ' +
                                                    bidClseDt + '</font> </a>';
                                            licn += '<div id="link' + idx + '" style="display:none;">';
                                            licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="상세정보" onclick=' +
                                                    '\'viewBalju(' + i + ')\'></center>';
                                            licn += '</div>';
                                            licn += '</li>';
                                        } else if (items[i]['pss'].substr(0, 2) == '사전') {
                                            licn += '<li><a class="a1" onclick=openButton(' + idx + ')>';
                                            licn += items[i]['bidNtceNm'] + '<br /><font class="f1">' + items[i]['dminsttNm'] + '<b' +
                                                    'r /> 금액: ' + number_format(items[i]['presmptPrce']) + ' ' + items[i]['locate'] +
                                                    '<br />공고:' + items[i]['bidNtceNo'] + ' 마감:' + bidClseDt + pss +
                                                    '</font> </a>';
                                            licn += '<div id="link' + idx + '" style="display:none;">';
                                            licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="상세정보" onclick=' +
                                                    '\'viewDtl("' + items[i]['bidNtceDtlUrl'] + '","' + items[i]['bidNtceNo'] +
                                                    '","' + items[i]['bidNtceOrd'] + '")\'>&nbsp;<input type=button value="수요기관" on' +
                                                    'click=\'viewscs("' + items[i]['dminsttNm'] + '")\'></center><br>';

                                            licn += '</div>';
                                            licn += '</li>';
                                        } else {
                                            licn += '<li><a class="a1" onclick=openButton(' + idx + ')>';
                                            licn += items[i]['bidNtceNm'] + '<br /><font class="f1">' + items[i]['dminsttNm'] + '<b' +
                                                    'r /> 금액: ' + number_format(items[i]['presmptPrce']) + ' ' + items[i]['locate'] +
                                                    '<br /> 공고:' + items[i]['bidNtceNo'] + '-' + items[i]['bidNtceOrd'] + ' 마감:' +
                                                    bidClseDt + pss + '</font> </a>';
                                            licn += '<div id="link' + idx + '" style="display:none;">';
                                            licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="상세정보" onclick=' +
                                                    '\'viewDtl("' + items[i]['bidNtceDtlUrl'] + '","' + items[i]['bidNtceNo'] +
                                                    '","' + items[i]['bidNtceOrd'] + '")\'>&nbsp;<input type=button value="수요기관" on' +
                                                    'click=\'viewscs("' + items[i]['dminsttNm'] + '")\'><br>';
                                            licn += '<p ><input type=button value="낙찰결과" onclick=\'viewRslt("' + items[i]['bidNtceNo'] +
                                                    '","' + items[i]['bidNtceOrd'] + '","' + items[i]['opengDt'] + '","' + items[i]['pss'] +
                                                    '")\'>';
                                            licn += '&nbsp;<input type=button value="낙찰기업" onclick=\'viewComp("' + items[i]['bidNtceNo'] +
                                                    '","' + items[i]['bidNtceOrd'] + '","' + bidClseDt + '")\'></p></center>';
                                            licn += '</div>';
                                            licn += '</li>';
                                        }

                                        // clog('licn = '+licn); if (today<=items[i]['opengDt'].substr(0,10)) return
                                        // cell.innerHTML;
                                    } catch (ex) {
                                        clog(ex.message);
                                    }
                                }
                                if (items.length < 1)
                                    licn = "데이터가 없습니다.";

                                //clog(licn);
                                lic += licn;
                                //alert(lic);
                                document
                                    .getElementById("tables")
                                    .innerHTML = lic;
                            }

                            function openButton(idx) {
                                if (document.getElementById("link" + idx).style.display == 'inline') {
                                    document
                                        .getElementById("link" + idx)
                                        .style
                                        .display = 'none'
                                } else
                                    document
                                        .getElementById("link" + idx)
                                        .style
                                        .display = 'inline';
                                }

                            function custonSorthrc(a, b) {
                                if (a.opninRgstClseDt == b.opninRgstClseDt) {
                                    return 0
                                }
                                return a.opninRgstClseDt > b.opninRgstClseDt
                                    ? -1
                                    : 1;
                            }

                            // 사전규격 공고목록
                            function makeTabletrhrc(datas) {
                                // ADD JSON DATA TO THE TABLE AS ROWS. json_result = jdata.js_result[0]["rows"];
                                // viewObject(data); clog(datas);
                                var data = JSON.parse(datas);
                                //r = data.response;

                                items = data.response.body.items;
                                //clog('items.length='+items.length);
                                if (items.length > 1)
                                    items.sort(custonSort); //hrc);

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
                                            if (colsx3[j] == 'c')
                                                attr += 'text-align:center;';
                                            else if (colsx3[j] == 'l')
                                                attr += 'text-align:left;';
                                            else if (colsx3[j] == 'r') {
                                                attr += 'text-align:right;';
                                                if (tabCell != null)
                                                    tabCell.innerHTML = tabCell
                                                        .innerHTML
                                                        .format();
                                                }
                                            else if (colsx3[j] == 'd') {
                                                attr += 'text-align:center;';
                                                if (tabCell != null && tabCell.innerHTML.length > 10)
                                                    tabCell.innerHTML = tabCell
                                                        .innerHTML
                                                        .substr(0, 10);
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
                                if (form.kind21.checked)
                                    pss = '물품';
                                if (form.kind22.checked)
                                    pss = '공사';
                                if (form.kind23.checked)
                                    pss = '용역';
                                return pss;
                            }

                            // 링크 makeTabletr
                            function setLink(items, i, j, cell) {
                                //if (i<2) console.log('j='+j+'/'+cell.innerHTML);
                                switch (j) {
                                    case 2: // 공고번호
                                        //$arr['bidNtceDtlUrl'] 가 없는 경우 viewDtl에 링크 삽입  -by jsj 20181129
                                        if (items[i]['bidNtceNo'].length == 6) { // 사전규격
                                            cell.innerHTML = '<a onclick=\'viewDtls("' + items[i]['bidNtceNo'] + '")\'>' +
                                                    cell
                                                .innerHTML
                                                .substr(0, 6) + '</a>';
                                            return cell.innerHTML;
                                        }
                                        if (items[i]['bidNtceDtlUrl'] == '') { // 공고번호
                                            items[i]['bidNtceDtlUrl'] = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=' +
                                                    items[i]['bidNtceNo'] + '&bidseq=' + items[i]['bidNtceOrd'] + '&releaseYn=Y&tas' +
                                                    'kClCd=5';
                                        }
                                        //clog('setlink '+ (items[i]['pss'].substr(0,2)));
                                        if (items[i]['pss'].substr(0, 2) != '계획')
                                            cell.innerHTML = '<a onclick=\'viewDtl("' + items[i]['bidNtceDtlUrl'] +
                                                    '")\'>' + cell.innerHTML + '</a>';
                                        else
                                            cell.innerHTML = '<a onclick=\'viewBalju(' + i + ')\'>' + cell.innerHTML +
                                                    '</a>';
                                        break;
                                    case 3: // 공고명
                                        break;
                                    case 4: // 계약방법
                                        break;

                                    case 7: // 수요기관으로 재검색
                                        cell.innerHTML = '<a onclick=\'viewscs("' + items[i]['dminsttNm'] + '")\'>' +
                                                cell.innerHTML + '</a>';
                                        break;

                                    case 8: // 낙찰기업 link bidwinnerBizno or '유찰'
                                        pss = items[i]['pss']; // getPSS(); 사전규격
                                        if (items[i]['pss'].substr(0, 2) == '사전') {
                                            return "사전공개일→";
                                        }
                                        if (items[i]['progrsDivCdNm'] == '개찰완료') {
                                            bidwinnrNm = items[i]['bidwinnrNm'];
                                            bidwinnrNm = bidwinnrNm.replace("주식회사", "");
                                            bidwinnrNm = bidwinnrNm.replace("(주)", "");
                                            cell.innerHTML = '<a onclick=\'compInfobyComp("' + items[i]['bidwinnrBizno'] +
                                                    '")\'>' + bidwinnrNm + '</a>';
                                            break;
                                        } else {
                                            // 유찰인 경우 (유찰사유 nobidRsn ) 표시
                                            progrsDivCdNm = items[i]['progrsDivCdNm'];
                                            if (progrsDivCdNm == '유찰') {
                                                if (items[i]['nobidRsn'] !== '') {
                                                    bidwinnrNm = '유찰<br>(' + items[i]['nobidRsn'] + ')';
                                                } else {
                                                    bidwinnrNm = '유찰';
                                                }
                                            } else if (progrsDivCdNm == '0' || progrsDivCdNm == '') {
                                                bidwinnrNm = '-';
                                            } else { // 재입찰 등 진행현황이 있을 경우 표시
                                                bidwinnrNm = progrsDivCdNm;
                                                if (items[i]['nobidRsn'] != '') {
                                                    bidwinnrNm += '<br>(' + items[i]['nobidRsn'] + ')'
                                                }
                                            }
                                            return bidwinnrNm;
                                        }
                                        // 응찰이력 링크 만듬
                                        break;

                                    case 9: // 개찰일시
                                        if (today < items[i]['opengDt'].substr(0, 10))
                                            return cell.innerHTML;
                                        bidwinnrNm = items[i]['progrsDivCdNm']; // 유찰 or 재입찰 외
                                        pss = items[i]['pss']; // getPSS(); 사전규격
                                        if (items[i]['pss'].substr(0, 2) == '사전') {
                                            return cell.innerHTML;
                                        } else if (bidwinnrNm == '유찰') {
                                            return cell.innerHTML;
                                        } else { // 개찰결과 링크
                                            cell.innerHTML = '<a onclick=\'viewRslt("' + items[i]['bidNtceNo'] + '","' +
                                                    items[i]['bidNtceOrd'] + '","' + items[i]['opengDt'] + '","' + pss + '","' +
                                                    userId + '")\'>' + cell.innerHTML + '</a>';
                                        }
                                        break;
                                } // switch
                                return cell.innerHTML;
                            }

                            function setLinksx(items, i, j, cell) { // 사전규격
                                //if (i<2) clog('j='+j+'/'+cell.innerHTML);

                                if (j == 1) {
                                    // cell.innerHTML = '<a
                                    // href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='+items[i]['bidNtceNo']+'&bidseq='+items[i]['bidNtceOrd']+'&releaseYn=Y&taskClCd=5"
                                    // target="_blank">' + cell.innerHTML + '</a>';
                                    cell.innerHTML = '<a onclick=\'viewDtls("' + items[i]['bidNtceNo'] + '")\'>' +
                                            cell.innerHTML + '</a>';
                                } else if (j == 5) {
                                    eDate = new Date()
                                        .toISOString()
                                        .slice(0, 10);
                                    sDate = dateAddDel(eDate, -1, 'y');
                                    cell.innerHTML = '<a onclick=\'viewscs("' + items[i]['dminsttNm'] + '")\'>' +
                                            cell.innerHTML + '</a>';
                                }

                                return cell.innerHTML;
                            }

                            function makeTabletr2hrc(datas) { // mobile 사전규격
                                if (datas == '')
                                    return;
                                var data;
                                try {
                                    data = JSON.parse(datas);
                                } catch (ex) {
                                    return;
                                }

                                //r = data.response; alert('makeTabletr2bid');
                                items = data.response.body.items;
                                //items.sort(custonSort);
                                var lic = document
                                    .getElementById("tables")
                                    .innerHTML;
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
                                        licn += items[i]['bidNtceNm'] + '<br /><font class="f1">' + items[i]['dminsttNm'] + '<b' +
                                                'r />공고 : ' + items[i]['bidNtceNo'] + ' 마감 : ' + items[i]['bidClseDt'].substr(
                                            0,
                                            10
                                        ) + '</font> </a>';
                                        licn += '<div id="link' + idx + '" style="display:none;">';
                                        licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="상세정보" onclick=' +
                                                '\'viewDtls("' + items[i]['bidNtceNo'] + '")\'>&nbsp;<input type=button value="' +
                                                '수요기관" onclick=\'viewscs("' + items[i]['dminsttNm'] + '")\'><br>';
                                        // licn += '<p ><input type=button value="낙찰결과"
                                        // onclick=\'viewRslt("'+items[i]['bidNtceNo']+'","'+items[i]['bidNtceOrd']+'","'+items[i]['bidClseDt']+'","'+pss+'")\'>&nbsp;<input
                                        // type=button value="낙찰기업"></p></center>';
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
                                document
                                    .getElementById("tables")
                                    .innerHTML = lic;
                            }

                            function makeTabletrCompany(datas) {
                                //clog (datas);
                                if (datas == '')
                                    return;

                                //clog('datas='+datas);
                                var data;
                                try {
                                    data = JSON.parse(datas);
                                } catch (ex) {
                                    alert(ex);
                                    return;
                                }
                                // clog('makeTabletrCompany table1='+table1);
                                // document.getElementById("tables").innerHTML = '';
                                data = JSON.parse(datas);
                                //r = data.response;
                                if (table1 == '')
                                    table1 = document.createElement("table");
                                var tbody = table1.createTBody();
                                items = data.response.body.items;
                                //items.sort(custonSort); idx = 0; alert(items.length);
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
                                            if (colc3[j] == 'c')
                                                attr += 'text-align:center;';
                                            else if (colc3[j] == 'l')
                                                attr += 'text-align:left;';
                                            else if (colc3[j] == 'r') {
                                                attr += 'text-align:right;';
                                                if (tabCell != null)
                                                    tabCell.innerHTML = tabCell
                                                        .innerHTML
                                                        .format();
                                                }
                                            else if (colc3[j] == 'd') {
                                                attr += 'text-align:center;';
                                                if (tabCell != null && tabCell.innerHTML.length > 10)
                                                    tabCell.innerHTML = tabCell
                                                        .innerHTML
                                                        .substr(0, 10);
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

                            function setLinkcomp(items, i, j, cell) { // 기업정보
                                //if (i<2) clog('j='+j+'/'+cell.innerHTML);

                                if (j == 1) {
                                    // cell.innerHTML = '<a
                                    // href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='+items[i]['bidNtceNo']+'&bidseq='+items[i]['bidNtceOrd']+'&releaseYn=Y&taskClCd=5"
                                    // target="_blank">' + cell.innerHTML + '</a>';
                                    cell.innerHTML = '<a onclick=\'compInfobyComp("' + items[i]['compno'] +
                                            '")\'>' + cell.innerHTML + '</a>';
                                } else if (j == 2) {
                                    cell.innerHTML = '<a onclick=\'compInfo("' + items[i]['compno'] + '")\'>' +
                                            cell.innerHTML + '</a>';
                                }
                                return cell.innerHTML;
                            }

                            function makeTabletrCompany2(datas) { // mobile 사전규격
                                if (datas == '')
                                    return;
                                var data;
                                try {
                                    data = JSON.parse(datas);
                                } catch (ex) {
                                    return;
                                }

                                //r = data.response; alert('makeTabletr2bid');
                                items = data.response.body.items;
                                //items.sort(custonSort);
                                var lic = document
                                    .getElementById("tables")
                                    .innerHTML;
                                var licn = '';
                                for (var i = 0; i < items.length; i++) {
                                    try {
                                        // colc2 = [ '','compno', 'compname', 'repname', 'cnt' ];
                                        idx = idx + 1;
                                        licn += '<li><a  class="a1"  onclick=openButton(' + idx + ')>';
                                        // licn += items[i]['compname']+'<br /> <font class="f1">- ' +
                                        // items[i]['compno']+ '대표 : '+items[i]['repname'] + ' 응찰 : '+ items[i]['cnt'] +
                                        // '</font> </a>';
                                        licn += items[i]['compname'] + '<br /> <font class="f1">- ' + items[i]['compno'] + ' 대표' +
                                                ' : ' + items[i]['repname'] + '</font> </a>';
                                        licn += '<div id="link' + idx + '" style="display:none;">';
                                        licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="응찰기록" onclick=' +
                                                '\'bidInfo("' + items[i]['compno'] + '")\'>&nbsp;<input type=button value="업체정보' +
                                                '" onclick=\'compInfo("' + items[i]['compno'] + '")\'><br>';

                                        licn += '</div>';
                                        licn += '</li>';
                                        //clog('licn = '+licn); pss = getPSS();

                                        licn += '</div>';
                                        licn += '</li>';
                                    } catch (ex) {}

                                }
                                lic += licn;
                                //alert(lic);
                                document
                                    .getElementById("tables")
                                    .innerHTML = lic;
                            }
                            var curStart = 0;
                            var cntonce = 100;

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

                            // 데이터 recv
                            function recv(data) {

                                SearchCounts++; //무료검색횟수 count+ -by jsj 0314
                                try {
                                    makeTable(data);
                                } catch (e) {
                                    alert('ln1428::데이타에 에러가 있는것 같습니다. 관리자에게 문의하세요.' + e.message);
                                    // clog(data);
                                }
                                move_stop();
                            }
                            // reinit();
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
                        searchajax0(); // 링크에도 발주계획 없이 보냄 searchajax_balju(); (발주계획포함)
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
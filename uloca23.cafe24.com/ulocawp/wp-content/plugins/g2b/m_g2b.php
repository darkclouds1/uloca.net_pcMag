<!DOCTYPE html>
<html>

<head>
	<!-- naver SEO -by jsj 20200106-->
	<title>나라장터 입찰정보 기업검색</title>
	<meta name="description" content="나라장터 입찰정보 조달청 입찰기업 기업상세정보 낙찰정보 낙찰이력 크레탑 한국기업데이터 ">
	<meta name="google-site-verification" content="l7HqV1KZeDYUI-2Ukg3U5c7FaCMRNe3scGPRZYjB_jM" />

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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
	<link rel="stylesheet" href="http://uloca.net/jquery/jquery-ui.css">
	<script src="http://uloca.net/jquery/jquery.min.js"></script>
	<script src="http://uloca.net/jquery/jquery-ui.min.js"></script>
	<script src="/g2b/g2b.js"></script>

</head>

<body>
	<!-- ------------------ 검색창 ---------------------------------------------------------- -->

	<form action="g2b.php" name="myForm" id="myform" method="post">
		<div id="contents">
			<div class="detail_search">

				<table align=center cellpadding="0" cellspacing="0" width="700px">
					<colgroup>
						<col style="width:20%;" />
						<col style="width:auto;" />
					</colgroup>
					<tbody>

						<tr>
							<th>공고명</th>
							<td>
								<input class="input_style2" type="text" name="kwd" id="kwd" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" maxlength="50" style="width:80%;" />

							</td>
						</tr>
						<tr>
							<th>수요기관</th>
							<td>
								<input class="input_style2" type="text" name="dminsttNm" id="dminsttNm" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" maxlength="50" style="width:80%;" />

							</td>
						</tr>
						<tr>
							<th>입찰기업명</th>
							<td>
								<input class="input_style2" type="text" name="compname" id="compname" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" maxlength="50" style="width:80%;" />

							</td>
						</tr>
				</table>
				<div class="btn_area">
					<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
					<a onclick="searchajax();" class="search">검색</a> 작업중 .....
					<!-- a onclick="mailMe2();" class="search">이메일</a>
		<a onclick="gotoComp();" class="search">기업정보</a><!-- a onclick="tableToExcel('bidData','bidData','bid_<?= $endDate ?>.xls')" class="search">엑셀</a -->
				</div>
			</div>
		</div>
	</form>
	<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
		<img src='http://uloca.net/g2b/loading3.gif' width='100px' height='100px'>
	</div>
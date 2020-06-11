<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

//echo 'HTTP_USER_AGENT='.$_SERVER['HTTP_USER_AGENT'].' 기기='. $mobile ;


 
if ($endDate == "") {
	$endDate = date("Y-m-d"); //$today;
	$timestamp = strtotime("-6 months");
	$startDate = date("Y-m-d", $timestamp);
} 
?>

<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" -->
<link rel="stylesheet" type="text/css" href="css/g2b.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!-- script type="text/javascript" src="js/datepicker.js"></script -->
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="http://uloca.net/g2b/g2b.js"></script>
</head>

<body>
<!-- ------------------ 검색창 ---------------------------------------------------------- -->
<form action="scsBid.php" name="myForm" id="myform" method="post" >
<div id="contents">
<div class="detail_search" >



	<table align=center cellpadding="0" cellspacing="0" width="700px">
		<colgroup>
			<col style="width:15%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>
			<!-- tr>
				<th>검색</th>
				<td>
					<input type="radio" name="bidrdo" value="bid" <?if ($bidrdo != 'scsbid') { ?> checked="checked" <? } ?> >입찰
					<!-- input type="radio" name="bidrdo" value="scsbid" <? if ($bidrdo == 'scsbid') { ?> checked="checked" <? } ?> >낙찰 -- >
					</td>
			</tr -->
			<tr>
				<th>키워드</th>
				<td>
					<input class="input_style2" type="text" name="kwd" id="kwd" value="<?=$kwd?>" maxlength="50" style="width:30%;" />
					
				</td>
			</tr>
			<tr>
				<th>기간</th>
				<td>
					<div class="calendar">
						<!-- select class="select_style1" id="date_select" name="dateType" onchange="isGcDate(this.value);" style="width:87px;">
							<option value="1" selected>공고일시</option>
							<option value="2" >개찰일자</option>								
						</select -->
						<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?=$startDate?>" style="width:76px;" />
						
						~
						<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?=$endDate?>" style="width:76px;" />
						
						<div id="datepicker"></div>	
						
							<!-- input type="radio" id="search_date1" onclick="getDate('1','startDateInput','endDateInput');" name="dateMonth" value="1" checked class="ml10" />
							<label id="search_date_label1" for="search_date1">최근 1개월</label>
							<input type="radio" id="search_date2" onclick="getDate('3','startDateInput','endDateInput');" name="dateMonth" value="3"  class="ml10" />
							<label id="search_date_label2" for="search_date2">최근 3개월</label>
							<input type="radio" id="search_date3" onclick="getDate('6','startDateInput','endDateInput');" name="dateMonth" value="6"  class="ml10" />
							<label id="search_date_label3" for="search_date3">최근 6개월</label -->
						
						
					</div>
				</td>
			</tr>
			
			<!-- tr>
				<th>수요기관</th>
				<td>
					<input class="input_style2" type="text" name="dminsttNm" id="dminsttNm" value="<?=$dminsttNm?>" maxlength="50" style="width:60%;" />
					
				</td>
			</tr -->
			<tr>
				<th>이메일</th>
				<td>
					<input class="input_style2" type="text" name="email" id="email" value="<?=$email?>" maxlength="50" style="width:30%;" />
					
				</td>
			</tr>	
		</table>
		<div class="btn_area">
		<input type="submit" class="search" value="검색" onclick="searchx()" />
		<a onclick="mailMe();" class="search">이메일</a>
		<!-- a onclick="tableToExcel('bidData','bidData','bid_<?=$endDate?>.xls')" class="search">엑셀</a -->
	</div>	
	</div>
</form>

<?
$startDate = str_replace('-','',$startDate);
$endDate = str_replace('-','',$endDate);
//echo ('<br>startDate='.$startDate.'<br>');
if ($kwd == "") exit;

//echo 'bidrdo='.$bidrdo; // bid= 입찰 scsbid = 낙찰
/*
입찰정보
요청주소  http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch
서비스URL  http://apis.data.go.kr/1230000/BidPublicInfoService
https://www.data.go.kr/subMain.jsp?param=T1BFTkFQSUAxNTAwMDgwMg==#/L3B1YnIvcG90L215cC9Jcm9zTXlQYWdlL29wZW5EZXZHdWlkZVBhZ2UkQF4wMTJtMSRAXnB1YmxpY0RhdGFQaz0xNTAwMDgwMiRAXnB1YmxpY0RhdGFEZXRhaWxQaz11ZGRpOjY0ZWNjMDI2LWEyODItNDNkZi1iMGUxLWY1OTQxN2M2MDZjZV8yMDE4MDUxMTEwMDUkQF5vcHJ0aW5TZXFObz0yMDI2OCRAXm1haW5GbGFnPXRydWU=

낙찰된 목록 현황 물품조회
요청주소  http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch
서비스URL  http://apis.data.go.kr/1230000/ScsbidInfoService

*/
//$kwd1 = str_replace(' ','+',$kwd); // 할 필요없슴. spaces encoded as plus (+) signs
/*$kwd1 = explode(' ', $kwd);
//echo 'kwd='.$kwd1[0].count($kwd1);
$kwd2 = "";
    for ($i = 0; $i < count($kwd1); $i++) {
		$kwd2 .= urlencode($kwd1[$i]).'+';
		//echo $kwd2;
	}
$kwd2 = substr($kwd2,0,strlen($kwd2)-1);
echo $kwd2; */
$ch = curl_init();
if ($bidrdo == 'scsbid') {
$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch'; // 낙찰
} else {
$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch'; // 입찰
}
//'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoServc'; /*URL*/
/*Service Key*/
$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode('30'); /*한 페이지 결과 수*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /*페이지 번호*/
//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode('1'); /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode($startDate.'0000'); /*검색하고자하는 시작일시 'YYYYMMDDHHMM', 조회구분 1,2,3일 경우 필수*/
$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode($endDate.'2359'); /*검색하고자하는 종료일시 'YYYYMMDDHHMM', 조회구분 1,2,3일 경우 필수*/
$queryParams .= '&' . urlencode('bidNtceNo') . '=' . ''; //urlencode('20160421357'); /*검색하고자하는 입찰공고번호, 조회구분 4인 경우 필수*/
$queryParams .= '&' . urlencode('bidNtceNm') . '=' . urlencode($kwd); // encodeURIComponent,
$queryParams .= '&' . urlencode('dminsttNm') . '=' . urlencode($dminsttNm);

$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */ 

//echo ('queryParams='.$queryParams);

if ($bidrdo == 'scsbid') {
$queryParams .= '&' . urlencode('ServiceKey') . '=' . 'mCQAlSkRqyZb00fZumkGyJin7uoOD7C8%2BKNRtfUUDEnnJa4p7c71m%2B%2F1h7cmFOFn87UCrnoTxzFPsd81kLuZww%3D%3D';
} else {
$queryParams .= '&' . urlencode('ServiceKey') . '=' . 'mCQAlSkRqyZb00fZumkGyJin7uoOD7C8%2BKNRtfUUDEnnJa4p7c71m%2B%2F1h7cmFOFn87UCrnoTxzFPsd81kLuZww%3D%3D'; // 입찰
}
//$urls = $url . $queryParams;
//echo $urls;
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);

//var_dump($response);

// parse to php object
/*
$data_object = json_decode($response);
print_r( $data_object);
$items = $data_object['body']; //['body']['items'];
print_r( $items);
function cmp($a, $b)
{
    return strcmp($a['rgstDt'], $b['rgstDt']);
}
usort($items, "cmp");
print_r( $items); */
// parse to php array
//$data_array = json_decode($response, true);


//echo "<br><br>";
//var_dump($data_object.response.body.items);

/*
폰트크기, 
<입찰정보>
항목: 공고번호, 공고명, 추정가격, 공고일시, 수요기관, 마감일시
상세링크(&개찰완료), 낙찰정보목록(과거목록: 키워드 & 수요기관)
*/
?>
<div id=totalrec></div>
<table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="40px;"><input type="checkbox"></th>
        <th scope="cols" width="100px;">공고번호</th>
        <th scope="cols" width="150px;">공고명</th>
        <th scope="cols" width="100px;">추정가격</th>
        <th scope="cols" width="80px;">공고일시</th>
        <th scope="cols" width="100px;">수요기  관</th>
		<th scope="cols" width="80px;">낙찰결과</th>
        
		
    </tr>
<script>


myJSON = JSON.stringify(<?=$response?>);
//console.log(myJSON); 
//myJSON.response.body.items.sort(SortByDesc);
var obj = JSON.parse(myJSON);
//alert(obj);
//obj.response.body.items.reverse();
//obj.response.body.items.sort(SortByDesc);
items = obj.response.body.items;
items.sort(SortByDesc);
//alert(JSON.stringify(items));
document.getElementById('totalrec').innerHTML = 'total record='+obj.response.body.totalCount;
var trLine = "";
for (x in items) {
	trLine = "<tr>";
	if (x%2 == 1) {
		trLine += '<td scope="row" class="even"><input id=chk type="checkbox" /></td>';
		// <a onclick="mailMe();" class="search">이메일</a>
		// <a onclick='viewDtl("http://ebid.korail.com/popup/bidDetailPopup.do?zzbidinv=9210051-00&zzstnum=00")' >
		trLine += '<td scope="row" class="even" style="cursor:pointer;"><a onclick=\'viewDtl("' + items[x].bidNtceDtlUrl+'")\'>' + items[x].bidNtceNo+'</a></td>';
		//trLine += '<td scope="row" class="even">' + items[x].bidNtceNo+'</td>';
		/*<? if (items[x].bidNtceDtlUrl != '') echo "trLine += '<a href=' + items[x].bidNtceDtlUrl ;"; ?>
		trLine += items[x].bidNtceNo;
		<? if (items[x].bidNtceDtlUrl != '') echo "trLine += '</a>' ;"; ?>
		trLine += '</td>'; */
		trLine += '<td scope="row" class="even">'+items[x].bidNtceNm+'</td>'; 
		trLine += '<td scope="row" class="even" align=right>'+items[x].presmptPrce.format()+'</td>'; 
		trLine += '<td scope="row" class="even">'+items[x].bidNtceDt+'</td>';
		trLine += '<td scope="row" class="even"><a onclick="viewscs(\''+items[x].dminsttNm+'\')">'+items[x].dminsttNm+'</a></td>';
		trLine += '<td scope="row" class="even">'+items[x].bidClseDt+'</td>';
		
	} else {
		trLine += '<td scope="row"><input id=chk type="checkbox" /></td>';
		trLine += '<td style="cursor:pointer;"><a onclick=\'viewDtl("' + items[x].bidNtceDtlUrl+'")\'>' + items[x].bidNtceNo+'</a></td>';
		
		/*if (items[x].bidNtceDtlUrl != '') trLine += '<a href=' + items[x].bidNtceDtlUrl ; 
		trLine += items[x].bidNtceNo;
		if (items[x].bidNtceDtlUrl != '') trLine += '</a>' ; 
		trLine += '</td>';*/

		//trLine += '<td>'+items[x].bidNtceNo+'</td>';
		trLine += '<td>'+items[x].bidNtceNm+'</td>';
		trLine += '<td align=right>'+items[x].presmptPrce.format()+'</td>'; 
		trLine += '<td>'+items[x].bidNtceDt+'</td>';
		trLine += '<td><a onclick="viewscs(\''+items[x].dminsttNm+'\')">'+items[x].dminsttNm+'</a></td>';
		trLine += '<td>'+items[x].bidClseDt+'</td>';
		
	}
	//console.log(trLine);
	trLine += "<tr>";
	var tableRef = document.getElementById('bidData').getElementsByTagName('tbody')[0];

	var newRow   = tableRef.insertRow(tableRef.rows.length);
	newRow.innerHTML = trLine;
	
}

//obj.response.body.items
</script>
    
    </tbody>
</table>

<form action="sendmail.php" name="chkrow" id="chkrow" method="post" >

<div id=mail style='visibility: hidden;'>


</div>
</form>

</body>
</html>
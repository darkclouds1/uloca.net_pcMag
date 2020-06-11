<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);




?>

<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" -->
<link rel="stylesheet" type="text/css" href="css/style.css" />
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
			<tr>
				<th>검색</th>
				<td>
					<input type="radio" name="bidrdo" value="bid">입찰
					<input type="radio" name="bidrdo" value="scsbid" checked="checked">낙찰
					</td>
			</tr>
			<tr>
				<th>키워드</th>
				<td>
					<input class="input_style2" type="text" name="kwd" id="kwd" value="<?=$kwd?>" maxlength="50" style="width:358px;" />
					
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
						<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDateInput" value="<?=$startDate?>" style="width:84px;" />
						
						~
						<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDateInput" value="<?=$endDate?>" style="width:84px;" />
						
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
			<tr>
				<th>이메일</th>
				<td>
					<input class="input_style2" type="text" name="email" id="email" value="<?=$email?>" maxlength="50" style="width:358px;" />
					
				</td>
			</tr>	
		</table>
		<div class="btn_area">
		<input type="submit" class="search" value="검색" onclick="searchx()" />
		<a onclick="mailMe();" class="reset">이메일</a>
		<a onclick="tableToExcel('bidData','bidData','bidData.xls')" class="reset">엑셀 다운로드</a>
	</div>	
	</div>
</form>

<?
$startDate = str_replace('-','',$startDate);
$endDate = str_replace('-','',$endDate);
//echo ('<br>startDate='.$startDate.'<br>');
if ($kwd == "") exit;

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
$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch'; // 낙찰
//$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch'; // 입찰
//'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoServc'; /*URL*/
/*Service Key*/
$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode('30'); /*한 페이지 결과 수*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /*페이지 번호*/
//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode('2'); /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode($startDate.'0000'); /*검색하고자하는 시작일시 'YYYYMMDDHHMM', 조회구분 1,2,3일 경우 필수*/
$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode($endDate.'2359'); /*검색하고자하는 종료일시 'YYYYMMDDHHMM', 조회구분 1,2,3일 경우 필수*/
$queryParams .= '&' . urlencode('bidNtceNo') . '=' . ''; //urlencode('20160421357'); /*검색하고자하는 입찰공고번호, 조회구분 4인 경우 필수*/
$queryParams .= '&' . urlencode('bidNtceNm') . '=' . urlencode($kwd); // encodeURIComponent,
//$queryParams .= '&' . urlencode('schCateGu') . '=' . urlencode('all');

$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */ 

//echo ('queryParams='.$queryParams);
$queryParams .= '&' . urlencode('ServiceKey') . '=' . 'mCQAlSkRqyZb00fZumkGyJin7uoOD7C8%2BKNRtfUUDEnnJa4p7c71m%2B%2F1h7cmFOFn87UCrnoTxzFPsd81kLuZww%3D%3D'; // 낙찰
//$queryParams .= '&' . urlencode('ServiceKey') . '=' . 'mCQAlSkRqyZb00fZumkGyJin7uoOD7C8%2BKNRtfUUDEnnJa4p7c71m%2B%2F1h7cmFOFn87UCrnoTxzFPsd81kLuZww%3D%3D'; // 입찰
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

?>
<div id=totalrec></div>
<table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="40px;"><input type="checkbox"></th>
        <th scope="cols" width="100px;">공고번호</th>
        <th scope="cols" width="100px;">수요기관</th>
		<th scope="cols" width="200px;">제목</th>
        <th scope="cols" width="100px;">개찰일</th>
		<th scope="cols" width="100px;">낙찰자상호명</th>
        
    </tr>
<script>


myJSON = JSON.stringify(<?=$response?>);
console.log(myJSON);
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
		trLine += '<td scope="row" class="even"><input type="checkbox" /></td>';
		trLine += '<td scope="row" class="even">'+items[x].bidNtceNo+'</td>';
		trLine += '<td scope="row" class="even">'+items[x].dminsttNm+'</td>';
		trLine += '<td scope="row" class="even">'+items[x].bidNtceNm+'</td>';
		trLine += '<td scope="row" class="even">'+items[x].rgstDt+'</td>';
		//trLine += '<td scope="row" class="even">'+obj.response.body.items[x].opengCorpInfo.split('^')[0]+'</td>';
		trLine += '<td scope="row" class="even">'+items[x].bidwinnrNm+'</td>';
	} else {
		trLine += '<td scope="row"><input type="checkbox" /></td>';
		trLine += '<td>'+items[x].bidNtceNo+'</td>';
		trLine += '<td>'+items[x].dminsttNm+'</td>';
		trLine += '<td>'+items[x].bidNtceNm+'</td>';
		trLine += '<td>'+items[x].rgstDt+'</td>';
		trLine += '<td>'+items[x].bidwinnrNm+'</td>';
	}
	
	trLine += "<tr>";
	var tableRef = document.getElementById('bidData').getElementsByTagName('tbody')[0];

	var newRow   = tableRef.insertRow(tableRef.rows.length);
	newRow.innerHTML = trLine;
	
}

//obj.response.body.items
</script>
    
    </tbody>
</table>

</body>
</html>
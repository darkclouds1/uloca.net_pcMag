<?
@extract($_GET);
// uloca.net/test/ipchal.php

function getipchal($url,$sdate,$edate) {
	$ServiceKey= 'Q5eI3cYX4CzlTWVI4YMFOkw41NPqQMvSZ%2FXhLM9eud43t%2B7NKImGzkhz4%2F4iIHi0SmrZBkgYSrlhhyshLQhVvA%3D%3D';  
	$ServiceKey2= 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D'; // 입찰

$ch = curl_init();
	$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode('999'); /*한 페이지 결과 수*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /*페이지 번호*/
$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode('1'); /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시(방위사업청 연계건의 경우 아래 내용 참고하여 검색) 1. 공고게시일시 : 공고일자(pblancDate) 2. 개찰일시 : 개찰일시(opengDt)*/
$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode($sdate.'0000'); /*검색하고자 하는 조회시작일시 "YYYYMMDDHHMM", 조회구분이 '1'인 경우 공고게시일시 필수, '2'인 경우 개찰일시 필수*/
$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode($edate.'2359'); /*검색하고자 하는 조회종료일시 "YYYYMMDDHHMM", 조회구분이 '1'인 경우 공고게시일시 필수, '2'인 경우 개찰일시 필수*/
$queryParams .= '&' . urlencode('bidNtceNm') . '=' . urlencode(''); /*검색하고자하는 공고명 ※ 공고명 일부 입력시에도 조회 가능(방위사업청 연계건의 경우 : 입찰명(bidNm)) 으로 검색*/
$queryParams .= '&' . urlencode('ntceInsttCd') . '=' . urlencode(''); /*검색하고자하는 공고기관코드(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('ntceInsttNm') . '=' . urlencode(''); /*검색하고자하는 공고기관명 ※ 공고기관명 일부 입력시에도 조회 가능(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('dminsttCd') . '=' . urlencode(''); /*검색하고자하는 수요기관코드(방위사업청 연계건의 경우 : 발주기관코드(orntCode)) 으로 검색*/
$queryParams .= '&' . urlencode('dminsttNm') . '=' . urlencode(''); /*검색하고자하는 수요기관명 ※ 수요기관명 일부 입력시에도 조회 가능(방위사업청 연계건의 경우 : 발주기관(ornt)) 으로 검색*/
$queryParams .= '&' . urlencode('refNo') . '=' . urlencode(''); /*검색하고자하는 참조번호(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('prtcptLmtRgnCd') . '=' . urlencode(''); /*검색하고자하는 참가제한지역코드 11 : 서울특별시, 26 : 부산광역시, 27 : 대구광역시, 28 : 인천광역시, 29 : 광주광역시, 30 : 대전광역시, 31 : 울산광역시, 36 : 세종특별자치시, 41 : 경기도, 42 : 강*/
$queryParams .= '&' . urlencode('prtcptLmtRgnNm') . '=' . urlencode(''); /*검색하고자하는 참가제한지역명 ※ 참가제한지역명 일부 입력시에도 조회 가능(방위사업청 연계건의 경우 : 지역제한목록(areaLmttList) 내 지역명으로 검색)*/
$queryParams .= '&' . urlencode('indstrytyCd') . '=' . urlencode(''); /*검색하고자하는 업종명 ※ 업종명 일부 입력시에도 조회 가능(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('indstrytyNm') . '=' . urlencode(''); /*검색하고자하는 업종명 ※ 업종명 일부 입력시에도 조회 가능(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('presmptPrceBgn') . '=' . urlencode(''); /*검색하고자하는 추정가격범위시작금액이상(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('presmptPrceEnd') . '=' . urlencode(''); /*검색하고자하는 추정가격범위종료금액이하(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('dtilPrdctClsfcNo') . '=' . urlencode(''); /*검색하고자하는 세부품명번호(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('masYn') . '=' . urlencode('Y'); /*검색하고자하는 다수공급경쟁자여부(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('prcrmntReqNo') . '=' . urlencode(''); /*검색하고자하는 조달요청번호(방위사업청 연계건의 경우 : 해당 컬럼 검색 불가)*/
$queryParams .= '&' . urlencode('bidClseExcpYn') . '=' . urlencode(''); /*검색하고자하는 입찰마감제외여부*/
$queryParams .= '&' . urlencode('intrntnlDivCd') . '=' . urlencode('1'); /*검색하고자하는 국제구분코드 국내:1, 국제:2(방위사업청 연계건의 경우 아래 내용 참고하여 검색) 국내/시설 입찰 공고일 경우 : 1, 국외 입찰 공고일 경우 : 2*/
$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 'json' 으로 지정*/
//echo $queryParams.'<br>';
$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);
return $response;

}

if ($sdate== '') $sdate = date("Ymd"); //'20190101';
if ($edate== '') $edate =  date("Ymd");


$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoServcPPSSrch'; // 용역
$response2 = getipchal($url,$sdate,$edate);
$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoCnstwkPPSSrch'; // 공사
$response3 = getipchal($url,$sdate,$edate);
$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch'; //물품
$response1 = getipchal($url,$sdate,$edate);

echo '<br><br>용역<br>';
echo($response2);
echo '<br><br>공사<br>';
echo($response3);
echo '<br><br>물품<br>';
echo($response1);

?>
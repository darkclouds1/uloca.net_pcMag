<?
/* PHP 샘플 코드 */


$ch = curl_init();
//$url = 'http://apis.data.go.kr/1230000/UsrInfoService/getPrcrmntCorpBasicInfo'; /*URL*/
$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoChgHstryServc';
//$queryParams = '?' . urlencode('ServiceKey') . '=BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D'; /*Service Key*/
$queryParams = '?' . urlencode('ServiceKey') . '=Q5eI3cYX4CzlTWVI4YMFOkw41NPqQMvSZ%2FXhLM9eud43t%2B7NKImGzkhz4%2F4iIHi0SmrZBkgYSrlhhyshLQhVvA%3D%3D';
$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('100'); /*한 페이지 결과 수*/
$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode('2'); /*검색하고자하는 조회구분 입력 1: 등록일기준 검색, 2: 변경일기준검색, 3: 사업자등록번호 기준검색*/
//$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode('201601010000'); /*검색하고자하는 검색기준시작일시 입력 "YYYYMMDDHHMM", 조회구분 1,2인 경우 필수*/
//$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode('201809122000'); /*검색하고자하는 검색기준종료일시 입력 "YYYYMMDDHHMM", 조회구분 1,2인 경우 필수*/
$queryParams .= '&' . urlencode('bidNtceNo') . '=' . urlencode('20180816479'); /*검색하고자 하는 업체명 조회구분 1,2인 경우 선택*/
//$queryParams .= '&' . urlencode('corpNm') . '=' . urlencode('유노'); /*검색하고자 하는 업체명 조회구분 1,2인 경우 선택*/
//$queryParams .= '&' . urlencode('bizno') . '=' . urlencode(''); /* 1378196923 검색하고자 하는 사업자등록번호 조회구분 3인 경우 필수*/
$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 'json' 으로 지정함*/

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);

var_dump($response);
/*
{"response": { "header": { "resultCode": "00", "resultMsg": "정상" }, "body": { "items": [ { "bizno": "1378196923", "corpNm": "주식회사 유노비젼", "engCorpNm": "UNOVISION", "opbizDt": "2009-09-01 00:00:00", "rgnCd": "41171", "rgnNm": "경기도 안양시 만안구", "zip": "14093", "adrs": "경기도 안양시 만안구 안양로", "dtlAdrs": "111-0, 1210호 (안양동)", "telNo": "031-987-5178", "faxNo": "031-696-5196", "cntryNm": "대한민국", "hmpgAdrs": "unovision.co.kr", "mnfctDivCd": "03", "mnfctDivNm": "공급", "emplyeNum": "14", "corpBsnsDivCd": "10110", "corpBsnsDivNm": "물품,-,용역,일반용역,-", "hdoffceDivNm": "본사", "rgstDt": "2012-05-07 11:24:11", "chgDt": "2018-08-02 11:18:49", "esntlNoCertRgstYn": "N", "ceoNm": "김종인" } ], "numOfRows": 10, "pageNo": 1, "totalCount": 1 } }}"
*/
?>
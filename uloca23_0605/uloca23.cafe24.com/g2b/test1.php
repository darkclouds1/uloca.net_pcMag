<?
/* PHP 샘플 코드 */


$ch = curl_init();
$url = 'http://apis.data.go.kr/1230000/PubDataOpnStdService/getDataSetOpnStdScsbidInfo'; // 데이터셋 개방표준에 따른 낙찰정보
$queryParams = '?' . urlencode('ServiceKey') . '=BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D'; /*Service Key*/
$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('100'); /*한 페이지 결과 수*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /*페이지 번호*/
$queryParams .= '&' . urlencode('opengBgnDt') . '=' . urlencode('201902110000'); /*검색하고자하는 조회시작일시 "YYYYMMDDHHMM", (조회구분이 1인 경우 필수)*/
$queryParams .= '&' . urlencode('opengEndDt') . '=' . urlencode('201902112359'); /*검색하고자하는 조회종료일시 "YYYYMMDDHHMM", (조회구분이 1인 경우 필수)*/
$queryParams .= '&' . urlencode('bsnsDivCd') . '=' . urlencode('3'); /*업무구분코드가 1이면 물품, 2면 외자, 3이면 공사, 5면 용역*/
$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 'json' 으로 지정*/

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);

var_dump($response);
/*
면허제한명	lcnsLmtNm	200
ALTER TABLE `openBidInfo_tmp` ADD `lcnsLmtNm` VARCHAR(200)  COMMENT '면허제한명' AFTER `bidwinnrCeoNm`;
ALTER TABLE `openBidInfo` ADD `lcnsLmtNm` VARCHAR(200)  COMMENT '면허제한명' AFTER `bidwinnrCeoNm`;


{"response": { "header": { "resultCode": "00", "resultMsg": "정상" }, "body": { "items": [ 
{ "bidNtceNo": "20160503379", "bidNtceOrd": "00", "lmtGrpNo": "1", "lmtSno": "1", "lcnsLmtNm": "도장공사업/0010", "permsnIndstrytyList": "", "rgstDt": "2016-05-04 10:59:57", "d2bMngDmndYear": "", "d2bMngCnstwkNo": "" }, 
{ "bidNtceNo": "20160503367", "bidNtceOrd": "00", "lmtGrpNo": "1", "lmtSno": "1", "lcnsLmtNm": "상.하수도설비공사업/0018", "permsnIndstrytyList": "", "rgstDt": "2016-05-04 11:06:27", "d2bMngDmndYear": "", "d2bMngCnstwkNo": "" }
], "numOfRows": 10, "pageNo": 1, "totalCount": 5167 } }}
*/
?>
<?
@extract($_GET);
// uloca.net/test/nakchal.php
if ($bidno == '') $bidNtceNo = '20190112627'; //20181134739';
else $bidNtceNo = $bidno;
$startDate = '20181201';
$endDate = '20181205';
$ch = curl_init();
$url = 'http://apis.data.go.kr/1230000/CntrctInfoService/getCntrctInfoListThng'; // 계약현황에 대한 물품조회
//$url = 'http://apis.data.go.kr/1230000/CntrctInfoService/getCntrctInfoListThngDetail'; // 계약현황에 대한 물품세부조회
//$url = 'http://apis.data.go.kr/1230000/CntrctInfoService/getCntrctInfoListThngPPSSrch'; // 나라장터검색조건에 의한 계약현황 물품조회
//$url = 'http://apis.data.go.kr/1230000/CntrctInfoService/getCntrctInfoListCnstwk'; // 계약현황에 대한 공사조회
//$url = 'http://apis.data.go.kr/1230000/CntrctInfoService/getCntrctInfoListServc'; // 계약현황에 대한 용역조회


$ServiceKey= 'Q5eI3cYX4CzlTWVI4YMFOkw41NPqQMvSZ%2FXhLM9eud43t%2B7NKImGzkhz4%2F4iIHi0SmrZBkgYSrlhhyshLQhVvA%3D%3D';  
//$ServiceKey2= 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D'; // 입찰
$ServiceKey_uloca23 = 'aQAvWmy3XF13lanl8ELaaOBq%2Fw4W1OHXY9b40KiZQ1hZuMX0Cv6B3ickvm5tzWMcMaw0VsYbRayxIwSVCAPybw%3D%3D';

$queryParams = '?' . urlencode('numOfRows') . '=' . '999'; /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . '1'; /*페이지 번호*/
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*json*/
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode(3); /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
		//$queryParams .= '&' . urlencode('bidNtceNo') . '=' . urlencode($bidNtceNo); /*검색하고자하는 입찰공고번호*/
		//$queryParams .= '&' . urlencode('bidNtceOrd') . '=' . ''; //urlencode($bidNtceOrd); /*검색하고자하는 입찰공고차수*/

		if (strlen($startDate) <=10) {
			$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode($startDate.'0000'); /*검색하고자하는 시작일시 'YYYYMMDDHHMM', 조회구분 1,2,3일 경우 필수*/
		} else {
			$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode($startDate);
		}
		if (strlen($endDate) <=10) {
			$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode($endDate.'2359'); /*검색하고자하는 종료일시 'YYYYMMDDHHMM', 조회구분 1,2,3일 경우 필수*/
		} else {
			$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode($endDate);
		}

		//$uloca_live_test = '2';
		//echo 'uloca_live_test='.$uloca_live_test;

		//$ServiceKey = $this->getServiceKey($uloca_live_test);
		//echo ' ServiceKey='.$ServiceKey;
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);

echo($response);
?>
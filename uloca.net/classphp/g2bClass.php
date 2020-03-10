<?php
/*
 공공데이타 포탈 https://www.data.go.kr/
 2018/06/20 by HMJ
 
 */
class g2bClass {
	public $ServiceKey= 'Q5eI3cYX4CzlTWVI4YMFOkw41NPqQMvSZ%2FXhLM9eud43t%2B7NKImGzkhz4%2F4iIHi0SmrZBkgYSrlhhyshLQhVvA%3D%3D';  //'mCQAlSkRqyZb00fZumkGyJin7uoOD7C8%2BKNRtfUUDEnnJa4p7c71m%2B%2F1h7cmFOFn87UCrnoTxzFPsd81kLuZww%3D%3D';
	public $ServiceKey2= 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D'; // 입찰
	public $ServiceKey_uloca23 = 'aQAvWmy3XF13lanl8ELaaOBq%2Fw4W1OHXY9b40KiZQ1hZuMX0Cv6B3ickvm5tzWMcMaw0VsYbRayxIwSVCAPybw%3D%3D';
	public $uloca_live_test = '1'; // 1:live 2:test
	//bitly -by jsj 0323
	public $login = 'enable21';
	public $appkey = 'R_cb94c2cd92bba988984791acf7704b6e';
	
	function MobileCheck() {
		global $HTTP_USER_AGENT;
		$MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","sony","phone");
		$HTTP_USER_AGENT = $_SERVER ['HTTP_USER_AGENT'];
		//console.log($HTTP_USER_AGENT);
		$checkCount = 0;
		for($i=0; $i<sizeof($MobileArray); $i++){
			if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; }
		}
		//echo $HTTP_USER_AGENT;
		return ($checkCount >= 1) ? "Mobile" : "Computer";
		
		// PC Chrome - Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36
		// Tablet/Mobile - Android
	} // MobileCheck
	
	function getSystem($uloca) {
		global $uloca_live_test;
		$uloca_live_test = $uloca;
		return $uloca_live_test;
	}
	function getServiceKey($uloca_live_test) { // 입찰 공고
		if ($uloca_live_test == '2') { // uloca23
			return 'aQAvWmy3XF13lanl8ELaaOBq%2Fw4W1OHXY9b40KiZQ1hZuMX0Cv6B3ickvm5tzWMcMaw0VsYbRayxIwSVCAPybw%3D%3D';
		} else { // uloca
			return 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		}
	}
	function getServiceKeySuc($uloca_live_test) { // 낙찰 결과
		if ($uloca_live_test == '2') { // uloca23
			return 'aQAvWmy3XF13lanl8ELaaOBq%2Fw4W1OHXY9b40KiZQ1hZuMX0Cv6B3ickvm5tzWMcMaw0VsYbRayxIwSVCAPybw%3D%3D';
		} else { // uloca
			return 'Q5eI3cYX4CzlTWVI4YMFOkw41NPqQMvSZ%2FXhLM9eud43t%2B7NKImGzkhz4%2F4iIHi0SmrZBkgYSrlhhyshLQhVvA%3D%3D';
		}
	}
	function getServiceKeyOrder($uloca_live_test) { // 발주정보
		if ($uloca_live_test == '2') { // uloca23
			return 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		} else { // uloca
			return 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		}
	}
	
	function countDuration($startDate) {
		$dur = '2018';
		//echo 'countDuration startDate='.$startDate;
		if ($startDate != '') {
			$startDate1 = str_replace('-','',$startDate);
			//echo 'countDuration startDate1='.$startDate1;
			$dur = substr($startDate1,0,4);
			/* if ($startDate1 >= '201601' && $startDate1 < '201607') {
			 $dur = '_2016';
			 } else if ($startDate1 >= '201607' && $startDate1 < '201701') {
			 $dur = '_2016_2';
			 } else if ($startDate1 >= '201701' && $startDate1 < '201707') {
			 $dur = '_2017';
			 } else if ($startDate1 >= '201707' && $startDate1 < '201801') {
			 $dur = '_2017_2';
			 } else if ($startDate1 >= '201801' && $startDate1 < '201807') {
			 $dur = '_2018';
			 } else if ($startDate1 >= '201807' && $startDate1 < '201901') {
			 $dur = '_2018_2';
			 } else if ($startDate1 >= '201901' && $startDate1 < '201907') {
			 $dur = '_2019';
			 } else if ($startDate1 >= '201907' && $startDate1 < '202001') {
			 $dur = '_2019_2';
			 } else $dur = '_2018_2'; */
		}
		return $dur;
	}
	function getAddr($bidrdo) {
		
		switch ($bidrdo) {
			
			case 'scsbidthing':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch'; // 물품낙찰
				break;
			case 'scsbidcnstwk':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusCnstwkPPSSrch'; // 공사낙찰
				break;
			case 'scsbidservc':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusServcPPSSrch'; // 용역낙찰
				break;
			case 'scsbidfrgcpt':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusFrgcptPPSSrch'; // 외자낙찰
				break;
			case 'bidthing':
				$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch'; // 물품입찰
				break;
			case 'bidcnstwk':
				$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoCnstwkPPSSrch'; // 공사입찰
				break;
			case 'bidservc':
				$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoServcPPSSrch'; // 용역입찰
				break;
			case 'bidfrgcpt':
				$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoFrgcptPPSSrch'; // 외자입찰
				break;
			case 'hrcthing':
				$url = 'http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoThngPPSSrch'; // 물품사전규격
				break;
			case 'hrccnstwk':
				$url = 'http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoCnstwkPPSSrch'; // 공사사전규격
				break;
			case 'hrcservc':
				$url = 'http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoServcPPSSrch'; // 용역사전규격
				break;
			case 'hrcfrgcpt':
				$url = 'http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoFrgcptPPSSrch'; // 외자사전규격
				break;
			case 'bidopen':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoOpengCompt'; // 개찰결과 개찰완료 목록 조회,물품, 공사, 용역, 외자 공통
				break;
			case 'scsbidthing1':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThng';	// 낙찰된 목록 현황 물품조회 1등만
				break;
			case 'scsbidcnstwk1':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusCnstwk'; // 낙찰된 목록 현황 공사조회 1등만
				break;
			case 'scsbidservc1':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusServc';  // 낙찰된 목록 현황 용역조회 1등만
				break;
		}
		return $url;
		
		/*
		 if ($bidrdo == 'scsbidthing') {
		 $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch'; // 물품낙찰
		 } else if ($bidrdo == 'scsbidcnstwk') {
		 $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusCnstwkPPSSrch'; // 공사낙찰
		 } else if ($bidrdo == 'scsbidservc') {
		 $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusServcPPSSrch'; // 용역낙찰
		 } else if ($bidrdo == 'scsbidfrgcpt') {
		 $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusFrgcptPPSSrch'; // 외자낙찰
		 } else if ($bidrdo == 'bidthing') {
		 $url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch'; // 물품입찰
		 } else if ($bidrdo == 'bidcnstwk') {
		 $url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoCnstwkPPSSrch'; // 공사입찰
		 } else if ($bidrdo == 'bidservc') {
		 $url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoServcPPSSrch'; // 용역입찰
		 } else if ($bidrdo == 'bidfrgcpt') {
		 $url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoFrgcptPPSSrch'; // 외자입찰
		 } else if ($bidrdo == 'hrcthing') {
		 $url = 'http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoThngPPSSrch'; // 물품사전규격
		 } else if ($bidrdo == 'hrccnstwk') {
		 $url = 'http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoCnstwkPPSSrch'; // 공사사전규격
		 } else if ($bidrdo == 'hrcservc') {
		 $url = 'http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoServcPPSSrch'; // 용역사전규격
		 } else if ($bidrdo == 'hrcfrgcpt') {
		 $url = 'http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoFrgcptPPSSrch'; // 외자사전규격
		 } else if ($bidrdo == 'bidopen') {
		 $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoOpengCompt'; // 개찰결과 개찰완료 목록 조회,물품, 공사, 용역, 외자 공통
		 }
		 http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThng;	// 낙찰된 목록 현황 물품조회 1등만
		 http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusCnstwk; // 낙찰된 목록 현황 공사조회 1등만
		 http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusServc;  // 낙찰된 목록 현황 용역조회 1등만
		 */
		/*낙찰된 목록 현황 용역조회
		 http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusServcPPSSrch
		 낙찰된 목록 현황 공사조회
		 http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusCnstwkPPSSrch
		 낙찰된 목록 현황 물품조회
		 http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch
		 목록 현황 외자조회
		 http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusFrgcptPPSSrch
		 
		 입찰공고물품조회
		 http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoThngPPSSrch
		 입찰공고외자조회
		 http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoFrgcptPPSSrch
		 입찰공고용역조회
		 http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoServcPPSSrch
		 입찰공고공사조회
		 http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoCnstwkPPSSrch
		 
		 사전규격 물품 목록 조회
		 http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoThngPPSSrch
		 사전규격 공사 목록 조회
		 http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoCnstwkPPSSrch
		 사전규격 용역 목록 조회
		 http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoServcPPSSrch
		 사전규격 외자 목록 조회
		 http://apis.data.go.kr/1230000/HrcspSsstndrdInfoService/getPublicPrcureThngInfoFrgcptPPSSrch
		 
		 */
	}
	
	/*
	 bidrdo=scsbid(낙찰),bid(입찰)
	 토탈 items의 페이지 만클 호출
	 */
	function tot_getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv){
		
		$rtn = $this->getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv);
		
		$json1 = json_decode($rtn, true);
		$iRowCnt = $json1['response']['body']['totalCount'];
		$item1 = $json1['response']['body']['items'];
		
		//$mergeJSON[] = json_decode($rtn, true);
		//var_dump($mergeJSON);
		//$totCnt = $iRowCnt;
		//echo '1. '.count($item1).'( '.$iRowCnt.'/'.$totCnt.' )'.':::';
		
		$iRowCnt -= $numOfRows; $pg=2;
		while ($iRowCnt >0) { //> $numOfRows){
			$rtn2 = $this->getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pg,$inqryDiv);
			// Json_decode 한다음 배열에 담는다.
			$json2 = json_decode($rtn2, true);
			$item2 = $json2['response']['body']['items'];
			$item1 = array_merge($item1,$item2);
			
			//echo $pg.'. '.count($item1).'( '.$iRowCnt.'/'.$totCnt.' )'.'<br>';
			$iRowCnt -= $numOfRows; $pg++;
		}
		// 배열에 담은 데이터를 다시 Json_encode 한다.
		return json_encode($item1);
	}
	
	function getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv) {
		$ch = curl_init();
		global $ServiceKey,$ServiceKey2,$ServiceKey_uloca23,$uloca_live_test;
		// 입찰분류 용역, 공사, 물품,외자,
		// 개찰결과 공사, 용역 목록 조회
		//echo 'startDate,endDate '.$startDate.'/'.$endDate;
		$url = $this->getAddr($bidrdo);
		//echo 'url='.$url.'<br>';
		$hrc = '';
		if (strlen($bidrdo) > 3) $hrc = substr($bidrdo,0,3);
		//'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoServc'; /*URL*/
		/*Service Key*/
		//getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv)
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqryDiv); /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
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
		
		if ($hrc == 'hrc') {
			// prdctClsfcNoNm : 사전규격 일 경우
			$queryParams .= '&' . urlencode('prdctClsfcNoNm') . '=' . urlencode($kwd); // 검색어
			if ($dminsttNm != '') $queryParams .= '&' . urlencode('dminsttNm') . '=' . urlencode($dminsttNm); // 수요기관
		} else {
			$queryParams .= '&' . urlencode('bidNtceNo') . '=' . ''; //urlencode('20160421357'); /*검색하고자하는 입찰공고번호, 조회구분 4인 경우 필수*/
			$queryParams .= '&' . urlencode('bidNtceNm') . '=' . urlencode($kwd); // 검색어
			if ($dminsttNm != '') $queryParams .= '&' . urlencode('dminsttNm') . '=' . urlencode($dminsttNm); // 수요기관
		}
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */
		
		//echo('getSvrData queryParams='.$queryParams);
		//$uloca_live_test = '2';
		//echo 'uloca_live_test='.$uloca_live_test;
		$ServiceKey = $this->getServiceKey($uloca_live_test);
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
		/*if ($uloca_live_test == '2') {	// test
		 $queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey_uloca23;
		 
		 }
		 else if ($bidrdo == 'scsbid') {
		 $queryParams .= '&' . urlencode('ServiceKey') . '=' .  $ServiceKey; //'Q5eI3cYX4CzlTWVI4YMFOkw41NPqQMvSZ%2FXhLM9eud43t%2B7NKImGzkhz4%2F4iIHi0SmrZBkgYSrlhhyshLQhVvA%3D%3D';
		 } else {
		 $queryParams .= '&' . urlencode('ServiceKey') . '=' . 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D'; // 입찰
		 } */
		// mCQAlSkRqyZb00fZumkGyJin7uoOD7C8%2BKNRtfUUDEnnJa4p7c71m%2B%2F1h7cmFOFn87UCrnoTxzFPsd81kLuZww%3D%3D // 사전규격
		//$urls = $url . $queryParams;
		//echo $queryParams;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	} // getSvrData
	// 발주계획 --------------------------------
	function getPlnData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv) {
		$plnthing = "http://apis.data.go.kr/1230000/OrderPlanSttusService/getOrderPlanSttusListThng";			// 물품
		$plnservc = "http://apis.data.go.kr/1230000/OrderPlanSttusService/getOrderPlanSttusListServcPPSSrch";	// 용역
		$plncnstwk = "http://apis.data.go.kr/1230000/OrderPlanSttusService/getOrderPlanSttusListCnstwkPPSSrch"; // 공사
		if ($bidrdo == "thing") $url = $plnthing;
		else if ($bidrdo == "servc") $url = $plnservc;
		else $url = $plncnstwk;
		$ch = curl_init();
		global $ServiceKey,$ServiceKey2,$ServiceKey_uloca23, $uloca_live_test;
		// 입찰분류 용역, 공사, 물품,외자,
		// 개찰결과 공사, 용역 목록 조회
		//echo 'startDate,endDate '.$startDate.'/'.$endDate;
		
		//echo 'url='.$url.'<br>';
		
		//getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv)
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		//$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqryDiv); /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
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
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */
		
		$ServiceKey = $this->getServiceKey($uloca_live_test);
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	} // getPlnData
	
	
	// 입찰공고목록 정보에 대한 면허제한정보조회 - 공고번호로 찾기
	function getSvrDataLimit12($bidNtceNo,$bidNtceOrd,$inqryDiv='2') {
		$ch = curl_init();
		global $ServiceKey,$ServiceKey2,$ServiceKey_uloca23, $uloca_live_test;
		// $inqryDiv = 2
		// 개찰결과 공사, 용역 목록 조회
		
		$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoLicenseLimit'; //$this->getAddr($bidrdo);
		//	console.log("%s",$bidrdo);
		
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode(10); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode(1); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqryDiv); /*검색하고자하는 조회구분 1:등록일시, 2.입찰공고번호*/
		
		$queryParams .= '&' . urlencode('bidNtceNo') . '=' . urlencode($bidNtceNo); /*검색하고자 하는 입찰공고번호 (조회구분이 '2'인 경우 필수)*/
		$queryParams .= '&' . urlencode('bidNtceOrd') . '=' . urlencode($bidNtceOrd); /*검색하고자 하는 입찰공고차수 (조회구분이 2인 경우 필수)*/
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 'json' 으로 지정*/
		
		$ServiceKey = $this->getServiceKey($uloca_live_test);
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
		
	} // getSvrDataLimit
	
	// 입찰공고목록 정보에 대한 면허제한정보조회 - 기간내에 전부 찾기
	function tot_getSvrDataLimit($startDate,$endDate,$numOfRows,$pageNo,$inqryDiv){
		
		$rtn = $this->getSvrDataLimit($startDate,$endDate,$numOfRows,$pageNo,$inqryDiv);
		
		$json1 = json_decode($rtn, true);
		$iRowCnt = $json1['response']['body']['totalCount'];
		$item1 = $json1['response']['body']['items'];
		
		$mergeJSON[] = json_decode($rtn, true);
		//var_dump($mergeJSON);
		//echo '<br>newnew<br><br>';
		//$totCnt = $iRowCnt;
		//echo '1. '.count($item1).'( '.$iRowCnt.'/'.$totCnt.' )'.':::';
		
		$iRowCnt -= $numOfRows; $pg=2;
		while ($iRowCnt >0) { //> $numOfRows){
			$rtn2 = $this->getSvrDataLimit($startDate,$endDate,$numOfRows,$pageNo,$inqryDiv);
			// Json_decode 한다음 배열에 담는다.
			$json2 = json_decode($rtn2, true);
			$item2 = $json2['response']['body']['items'];
			if (count($item2) > 0 ) $item1 = array_merge($item1,$item2);
			
			//echo $pg.'. '.count($item1).'( '.$iRowCnt.'/'.$totCnt.' )'.'<br>';
			$iRowCnt -= $numOfRows; $pg++;
		}
		// 배열에 담은 데이터를 다시 Json_encode 한다.
		return json_encode($item1);
	}
	
	// 입찰공고목록 정보에 대한 면허제한정보조회 - 기간으로 찾기
	function getSvrDataLimit($startDate,$endDate,$numOfRows,$pageNo,$inqryDiv) {
		$ch = curl_init();
		global $ServiceKey,$ServiceKey2,$ServiceKey_uloca23, $uloca_live_test;
		// $inqryDiv = 1
		// 개찰결과 공사, 용역 목록 조회
		
		$url = 'http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoLicenseLimit'; //$this->getAddr($bidrdo);
		//	console.log("%s",$bidrdo);
		
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqryDiv); /*검색하고자하는 조회구분 1:등록일시, 2.입찰공고번호*/
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
		$queryParams .= '&' . urlencode('bidNtceNo') . '=' . urlencode(''); /*검색하고자 하는 입찰공고번호 (조회구분이 '2'인 경우 필수)*/
		$queryParams .= '&' . urlencode('bidNtceOrd') . '=' . urlencode(''); /*검색하고자 하는 입찰공고차수 (조회구분이 2인 경우 필수)*/
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 'json' 으로 지정*/
		
		$ServiceKey = $this->getServiceKey($uloca_live_test);
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
		
	} // getSvrDataLimit
	
	// 입찰공고목록 정보에 대한 발주계획정보조회 - 기간내에 전부 찾기
	function tot_getSvrDataOrder($bidrdo,$startDate,$endDate,$numOfRows,$pageNo,$inqryDiv){
		
		$rtn = $this->getSvrDataOrder($bidrdo,$startDate,$endDate,$numOfRows,$pageNo,$inqryDiv);
		
		$json1 = json_decode($rtn, true);
		$iRowCnt = $json1['response']['body']['totalCount'];
		$item1 = $json1['response']['body']['items'];
		
		$mergeJSON[] = json_decode($rtn, true);
		//var_dump($mergeJSON);
		//echo '<br>newnew<br><br>';
		//$totCnt = $iRowCnt;
		//echo '1. '.count($item1).'( '.$iRowCnt.'/'.$totCnt.' )'.':::';
		
		$iRowCnt -= $numOfRows; $pg=2;
		while ($iRowCnt >0) { //> $numOfRows){
			$rtn2 = $this->getSvrDataOrder($bidrdo,$startDate,$endDate,$numOfRows,$pageNo,$inqryDiv);
			// Json_decode 한다음 배열에 담는다.
			$json2 = json_decode($rtn2, true);
			$item2 = $json2['response']['body']['items'];
			if (count($item2) > 0 ) $item1 = array_merge($item1,$item2);
			
			//echo $pg.'. '.count($item1).'( '.$iRowCnt.'/'.$totCnt.' )'.'<br>';
			$iRowCnt -= $numOfRows; $pg++;
		}
		// 배열에 담은 데이터를 다시 Json_encode 한다.
		return json_encode($item1);
	}
	function getAddrPlan($bidrdo) {
		
		switch ($bidrdo) {
			case 'plnbidThng':
				$url = 'http://apis.data.go.kr/1230000/OrderPlanSttusService/getOrderPlanSttusListThng';  // 발주계획현황에 대한 물품조회
				break;
			case 'plnbidCnstwk':
				$url = 'http://apis.data.go.kr/1230000/OrderPlanSttusService/getOrderPlanSttusListCnstwk';  // 발주계획현황에 대한 공사조회
				break;
			case 'plnbidservc':
				$url = 'http://apis.data.go.kr/1230000/OrderPlanSttusService/getOrderPlanSttusListServc';  // 발주계획현황에 대한 용역조회
				break;
		}
		return $url;
	}
	// 입찰공고목록 정보에 대한 발주계획정보조회 - 기간으로 찾기
	function getSvrDataOrder($bidrdo,$thismonth,$endmonth,$numOfRows,$pageNo,$inqryDiv) {
		$ch = curl_init();
		global $ServiceKey,$ServiceKey2,$ServiceKey_uloca23;
		// $inqryDiv = 1
		// 개찰결과 공사, 용역 목록 조회
		
		$url = $this->getAddrPlan($bidrdo);
		//echo $url;
		
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqryDiv); /*검색하고자하는 조회구분 1:등록일시, 2.입찰공고번호*/
		
		$queryParams .= '&' . urlencode('orderBgnYm') . '=' . urlencode($thismonth); /*검색하고자하는 발주년도, 발주월 기준 조회시작 'YYYYMM' * 입력값이 없을 경우 현재일로부터 한달기준 조회. 조회구분이 1인 경우 필수 */
		$queryParams .= '&' . urlencode('orderEndYm') . '=' . urlencode($endmonth); /*검색하고자하는 발주년도, 발주월 기준 조회종료 'YYYYMM' *입력값이 없을 경우 현재일로부터 한달기준 조회. 조회구분이 1인 경우 필수*/
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 'json' 으로 지정*/
		
		$ServiceKey = $this->getServiceKeyOrder('1');
		//echo ' '.$ServiceKey;
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
		
	} // getSvrDataOrder
	
	// 발주계획정보조회 전부 이번달-3번째달 201902-201905
	function getSvrDataOrderAll($thismonth,$endmonth) {
		$inqryDiv = 1; //검색하고자하는 조회구분 1. 발주년월, 게시일시 2. 발주계획통합번호
		$numOfRows = 900;
		$pageNo = 1;
		$bidrdo = 'plnbidThng'; // 물품
		$rtn = $this->getSvrDataOrder($bidrdo,$thismonth,$endmonth,$numOfRows,$pageNo,$inqryDiv);
		$json1 = json_decode($rtn, true);
		//$iRowCnt = $json1['response']['body']['totalCount'];
		$item1 = $json1['response']['body']['items'];
		//echo $rtn;
		$bidrdo = 'plnbidCnstwk'; // 공사
		$rtn = $this->getSvrDataOrder($bidrdo,$thismonth,$endmonth,$numOfRows,$pageNo,$inqryDiv);
		$json1 = json_decode($rtn, true);
		//$iRowCnt = $json1['response']['body']['totalCount'];
		$item2 = $json1['response']['body']['items'];
		if (count($item2) > 0 ) $item1 = array_merge($item1,$item2);
		$bidrdo = 'plnbidservc'; // 용역
		$rtn = $this->getSvrDataOrder($bidrdo,$thismonth,$endmonth,$numOfRows,$pageNo,$inqryDiv);
		$json1 = json_decode($rtn, true);
		//$iRowCnt = $json1['response']['body']['totalCount'];
		$item2 = $json1['response']['body']['items'];
		if (count($item2) > 0 ) $item1 = array_merge($item1,$item2);
		//echo item1;
		return $item1;
	}
	
	/* -------------------------------------------------------------
	 예비가격상세 목록 조회
	 ------------------------------------------------------------   */
	function getAddrOpn($bidrdo) {
		
		switch ($bidrdo) {
			case 'opnbidThng':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoThngPreparPcDetail';  // 개찰결과 물품 예비가격상세 목록 조회
				break;
			case 'opnbidCnstwk':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoCnstwkPreparPcDetail';  // 개찰결과 공사 예비가격상세 목록 조회
				break;
			case 'opnbidservc':
				$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoServcPreparPcDetail';  // 개찰결과 용역 예비가격상세 목록 조회
				break;
		}
		return $url;
	}
	function getSvrDataOpn($bidrdo,$bidNtceNo) {
		$url = $this->getAddrOpn($bidrdo);
		
		$ServiceKey = 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		$ch = curl_init();
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode('1'); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /*페이지 번호*/
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode('2'); /*검색하고자하는 조회구분 1.입력일시, 2.입찰공고번호*/
		//$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode('201605010000'); /*검색하고자하는 시작일시 'YYYYMMDDHHMM', 조회구분이 1일 경우 필수*/
		//$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode('201605052359'); /*검색하고자하는 종료일시 'YYYYMMDDHHMM', 조회구분이 1일 경우 필수*/
		$queryParams .= '&' . urlencode('bidNtceNo') . '=' . urlencode($bidNtceNo); /*검색하고자하는 입찰공고번호, 조회구분이 2일 경우 필수*/
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 'json' 으로 지정*/
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		
		return($response);
	}
	
	/* -------------------------------------------------------------------------
	 데이터셋 개방표준에 따른 낙찰정보 목록을 조회
	 ------------------------------------------------------------------------- */
	function tot_getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd){
		
		$rtn = $this->getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd);
		
		$json1 = json_decode($rtn, true);
		$iRowCnt = $json1['response']['body']['totalCount'];
		$item1 = $json1['response']['body']['items'];
		
		$mergeJSON[] = json_decode($rtn, true);
		//var_dump($mergeJSON);
		//echo '<br>newnew<br><br>';
		//$totCnt = $iRowCnt;
		//echo '1. '.count($item1).'( '.$iRowCnt.'/'.$totCnt.' )'.':::';
		
		$iRowCnt -= $numOfRows; $pg=2;
		while ($iRowCnt >0) { //> $numOfRows){
			$rtn2 = $this->getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd);
			// Json_decode 한다음 배열에 담는다.
			$json2 = json_decode($rtn2, true);
			$item2 = $json2['response']['body']['items'];
			if (count($item2) > 0 ) $item1 = array_merge($item1,$item2);
			
			//echo $pg.'. '.count($item1).'( '.$iRowCnt.'/'.$totCnt.' )'.'<br>';
			$iRowCnt -= $numOfRows; $pg++;
		}
		// 배열에 담은 데이터를 다시 Json_encode 한다.
		return $item1; //json_encode($item1);
	}
	
	// 데이터셋 개방표준에 따른 낙찰정보 목록을 조회 - 기간으로 찾기
	function getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd) {
		$ch = curl_init();
		global $ServiceKey,$ServiceKey2,$ServiceKey_uloca23;
		
		$ServiceKey = 'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		//echo ' '.$ServiceKey;
		$ch = curl_init();
		$url = 'http://apis.data.go.kr/1230000/PubDataOpnStdService/getDataSetOpnStdScsbidInfo'; // 데이터셋 개방표준에 따른 낙찰정보
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		$queryParams .= '&' . urlencode('opengBgnDt') . '=' . urlencode($startDate); /*검색하고자하는 조회시작일시 "YYYYMMDDHHMM", (조회구분이 1인 경우 필수)*/
		$queryParams .= '&' . urlencode('opengEndDt') . '=' . urlencode($endDate); /*검색하고자하는 조회종료일시 "YYYYMMDDHHMM", (조회구분이 1인 경우 필수)*/
		$queryParams .= '&' . urlencode('bsnsDivCd') . '=' . $bsnsDivCd; /*업무구분코드가 1이면 물품, 2면 외자, 3이면 공사, 5면 용역*/
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 'json' 으로 지정*/
		//echo $queryParams;
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
		
	} // getSvrDataOpnStd
	
	/* -------------------------------------------------------------------------
	 검색조건에 사업자등록번호와 업체명을 입력하여 사업자등록번호, 업체명, 영문업체명, 개업일시, 지역코드, 지역명,
	 우편번호, 주소, 상세주소, 전화번호, 팩스번호, 국가명, 홈페이지주소, 제조구분코드, 제조구분명, 종업원수,
	 업체업무구분코드, 업체업무구분명, 본사구분명, 등록일시, 변경일시, 고유번호증명등록여부, 대표자명 등 조달업체 기본정보 목록을 조회
	 ------------------------------------------------------------------------- */
	function getCompInfo($numOfRows,$pageNo,$inqryDiv,$bizno) {
		$ch = curl_init();
		global $ServiceKey;
		$url = 'http://apis.data.go.kr/1230000/UsrInfoService/getPrcrmntCorpBasicInfo'; // 조달업체 기본정보
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . '';
		$queryParams .= '&' . urlencode('inqryEndDt') . '=' . '';
		$queryParams .= '&' . urlencode('corpNm') . '=' . ''; // 검색하고자 하는 업체명 조회구분 1,2인 경우 선택
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqryDiv); /*검색하고자하는 조회구분 입력 1: 등록일기준 검색, 2: 변경일기준검색, 3: 사업자등록번호 기준검색 */
		$queryParams .= '&' . urlencode('bizno') . '=' . urlencode($bizno); // 사업자등록번호
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */
		
		//echo ('queryParams='.$queryParams);
		
		$queryParams .= '&' . urlencode('ServiceKey') . '=' .
				'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		
		
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	
	function getCompInfo2($numOfRows,$pageNo,$inqryDiv,$inqryBgnDt,$inqryEndDt) {
		$ch = curl_init();
		global $ServiceKey;
		$url = 'http://apis.data.go.kr/1230000/UsrInfoService/getPrcrmntCorpBasicInfo'; // 조달업체 기본정보
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode($inqryBgnDt).'0000';
		$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode($inqryEndDt).'2359';
		$queryParams .= '&' . urlencode('corpNm') . '=' . ''; // 검색하고자 하는 업체명 조회구분 1,2인 경우 선택
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqryDiv); /*검색하고자하는 조회구분 입력 1: 등록일기준 검색, 2: 변경일기준검색, 3: 사업자등록번호 기준검색 */
		//$queryParams .= '&' . urlencode('bizno') . '=' . urlencode($bizno); // 사업자등록번호
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */
		
		//echo ('queryParams='.$queryParams);
		
		$queryParams .= '&' . urlencode('ServiceKey') . '=' .
				'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		
		
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	} // getCompInfo2
	
	function getCompInfo3($numOfRows,$pageNo,$inqryDiv,$inqryBgnDt,$inqryEndDt,$corpNm) {
		$ch = curl_init();
		global $ServiceKey;
		$url = 'http://apis.data.go.kr/1230000/UsrInfoService/getPrcrmntCorpBasicInfo'; // 조달업체 기본정보
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . urlencode($inqryBgnDt).'0000';
		$queryParams .= '&' . urlencode('inqryEndDt') . '=' . urlencode($inqryEndDt).'2359';
		$queryParams .= '&' . urlencode('corpNm') . '=' . urlencode($corpNm); // 검색하고자 하는 업체명 조회구분 1,2인 경우 선택
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqryDiv); /*검색하고자하는 조회구분 입력 1: 등록일기준 검색, 2: 변경일기준검색, 3: 사업자등록번호 기준검색 */
		//$queryParams .= '&' . urlencode('bizno') . '=' . urlencode($bizno); // 사업자등록번호
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */
		
		//echo ('queryParams='.$queryParams);
		
		$queryParams .= '&' . urlencode('ServiceKey') . '=' .
				'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		
		
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	} // getCompInfo3
	
	/* -------------------------------------------------------------------------
	 낙찰 조회
	 ------------------------------------------------------------------------- */
	function getBidRslt($numOfRows,$pageNo,$inqryDiv,$inqryBgnDt,$inqryEndDt,$pss) {
		$inqDiv = 2; // 개찰일시
		$ch = curl_init();
		global $ServiceKey;
		$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusServcPPSSrch'; // 나라장터 검색조건에 의한 낙찰된 목록 현황 용역조회
		if ($pss == "공사") $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusCnstwkPPSSrch'; // 공사조회
		if ($pss == "물품") $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch'; // 물품
		
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . $inqryBgnDt;
		$queryParams .= '&' . urlencode('inqryEndDt') . '=' . $inqryEndDt;
		$queryParams .= '&' . urlencode('corpNm') . '=' . ''; // 검색하고자 하는 업체명 조회구분 1,2인 경우 선택
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqDiv); /*검색하고자하는 조회구분 입력 1:공고게시일시, 2:개찰일시, 3:입찰공고번호 */
		$queryParams .= '&' . urlencode('bizno') . '=' . ''; // 사업자등록번호
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */
		
		//echo ('queryParams='.$queryParams);
		
		$queryParams .= '&' . urlencode('ServiceKey') . '=' .
				'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		
		
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	} // getBidRslt
	
	function getBidRslt2($numOfRows,$pageNo,$inqryDiv,$inqryBgnDt,$inqryEndDt,$pss,$bidNtceNo) {
		$inqDiv = 3; // 3:입찰공고번호
		$ch = curl_init();
		global $ServiceKey;
		$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusServcPPSSrch'; // 나라장터 검색조건에 의한 낙찰된 목록 현황 용역조회
		if ($pss == "공사") $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusCnstwkPPSSrch'; // 공사조회
		if ($pss == "물품") $url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch'; // 물품
		
		$queryParams = '?' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/
		//$queryParams .= '&' . urlencode('ServiceKey') . '=' . urlencode('-'); /*공공데이터포털에서 받은 인증키*/
		//$queryParams .= '&' . urlencode('inqryBgnDt') . '=' . $inqryBgnDt;
		//$queryParams .= '&' . urlencode('inqryEndDt') . '=' . $inqryEndDt;
		$queryParams .= '&' . urlencode('bidNtceNo') . '=' . $bidNtceNo; // 검색하고자 하는 공고번호
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode($inqDiv); /*검색하고자하는 조회구분 입력 1:공고게시일시, 2:개찰일시, 3:입찰공고번호 */
		//$queryParams .= '&' . urlencode('bizno') . '=' . ''; // 사업자등록번호
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*오픈API 리턴 타입을 JSON으로 받고 싶을 경우 */
		
		echo ('queryParams='.$queryParams.'<br>');
		
		$queryParams .= '&' . urlencode('ServiceKey') . '=' .
				'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		
		
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	} // getBidRslt
	
	/* ---------------------------------------------------------------------------------
	 입찰정보 보기
	 ------------------------------------------------------------------------------------ */
	function viewTable($kind,$startDate,$endDate,$kwd,$dminsttNm,$noRow,$nopg,$inqryDiv) {
		// kind 물품 = bidthing, 공사 = bidcnstwk, 용역 = bidservc
		if ($kind == '물품') $func = 'bidthing';
		if ($kind == '공사') $func = 'bidcnstwk';
		if ($kind == '용역') $func = 'bidservc';
		$chkid = 'chk'.$func;
		//$noRow = 8000;
		// getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv)
		$response1 = $this->getSvrData($func,$startDate,$endDate,$kwd,$dminsttNm,$noRow,$nopg,$inqryDiv);
		$json1 = json_decode($response1, true);
		$item = $json1['response']['body']['items'];
		if ($item == NULL) return;
		// 열 목록 얻기
		foreach ($item as $key => $row) {
			$bidClseDt[$key]  = $row['bidClseDt'];
		}
		if (count($item) > 1) array_multisort($bidClseDt, SORT_DESC, $item); // 마김일시
		
		$msg = "<center><div style='font-size:18px;'><font color='blue'><strong>- 입찰 정보 (".$kind.")-</strong></font></center>";
		$msg .= '<div id=totalrec>total record='.count($item).'</div>';
		$msg .= '<table class="type10" id="bidData'.$func.'">';
		$msg .= '    <tr>';
		
		$msg .= '		<th scope="cols" width="5%;" ><input type="checkbox" onclick="javascript:CheckAll(\''.$chkid.'\')"></th>';
		$msg .= '		<th scope="cols" width="10%;">공고번호</th>';
		$msg .= '        <th scope="cols" width="25%;">공고명</th>';
		$msg .= '        <th scope="cols" width="10%;">추정가격</th>';
		$msg .= '        <th scope="cols" width="15%;">공고일</th>';
		$msg .= '        <th scope="cols" width="20%;">수요기관</th>';
		$msg .= '		<th scope="cols" width="15%;">낙찰결과</th>';
		$msg .= '    </tr>';
		echo $msg;
		$i=0;
		//var_dump($item);
		foreach($item as $arr ) { //foreach element in $arr
			$pss = $kind; //$g2bClass->getDivNm($arr);
			if ($arr['presmptPrce']=="") $presmptPrce = "";
			else $presmptPrce = number_format($arr['presmptPrce']);
			$tr = '<tr>';
			$tr .= '<td style="text-align: center;"><input id='.$chkid.' name='.$chkid.' type="checkbox" /></td>';
			$tr .= '<td style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
			
			$tr .= '<td title="'.$pss.'">'.$arr['bidNtceNm'].'</td>';
			$tr .= '<td align=right>'.$presmptPrce.'</td>';
			$tr .= '<td style="text-align: center;">'.substr($arr['bidNtceDt'],0,10).'</td>';
			$tr .= '<td><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['dminsttNm'].'</a></td>';
			$tr .= '<td style="text-align: center;"><a onclick=\'viewRslt("'.$arr['bidNtceNo'].'","'.$arr['bidNtceOrd'].'","'.$arr['bidClseDt'].'","'.$pss.'")\'>'.substr($arr['bidClseDt'],0,10).'</a></td>';
			$tr .= '</tr>';
			
			echo $tr;
			$i += 1;
		}
		echo '</table><br>';
	}
	
	/* ---------------------------------------------------------------------------------
	 입찰정보 보기 - mobille
	 ------------------------------------------------------------------------------------ */
	function viewTable_m($kind,$startDate,$endDate,$kwd,$dminsttNm,$noRow,$nopg,$inqryDiv) {
		// kind 물품 = bidthing, 공사 = bidcnstwk, 용역 = bidservc
		if ($kind == '물품') $func = 'bidthing';
		if ($kind == '공사') $func = 'bidcnstwk';
		if ($kind == '용역') $func = 'bidservc';
		$chkid = 'chk'.$func;
		// getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv)
		$response1 = $this->getSvrData($func,$startDate,$endDate,$kwd,$dminsttNm,$noRow,$nopg,$inqryDiv);
		$json1 = json_decode($response1, true);
		$item = $json1['response']['body']['items'];
		if ($item == NULL) return;
		// 열 목록 얻기
		foreach ($item as $key => $row) {
			$bidClseDt[$key]  = $row['bidClseDt'];
		}
		if (count($item) > 1) array_multisort($bidClseDt, SORT_DESC, $item); // 마김일시
		
		
		$msg = "<center><div style='font-size:18px; color:blue; font-weight:bold;'>-- 입찰 정보 (".$kind.")--</center>";
		$msg .= '<div id=totalrec style="font-size:12px;">total record='.count($item).'</div>';
		$msg .= '<div id="list">';
		/*$msg .= '<table class="type10" id="bidData'.$func.'">';
		 $msg .= '    <tr>';
		 
		 $msg .= '		<th scope="cols" width="5%;" ><input type="checkbox" onclick="javascript:CheckAll(\''.$chkid.'\')"></th>';
		 $msg .= '		<th scope="cols" width="10%;">공고번호</th>';
		 $msg .= '        <th scope="cols" width="25%;">공고명</th>';
		 $msg .= '        <th scope="cols" width="10%;">추정가격</th>';
		 $msg .= '        <th scope="cols" width="15%;">공고일시</th>';
		 $msg .= '        <th scope="cols" width="20%;">수요기관</th>';
		 $msg .= '		<th scope="cols" width="15%;">마감일</th>';
		 $msg .= '    </tr>'; */
		echo $msg;
		$i=0;
		//var_dump($item);
		foreach($item as $arr ) { //foreach element in $arr
			$pss = $kind; //$g2bClass->getDivNm($arr);
			if ($arr['presmptPrce']=="") $presmptPrce = "";
			else $presmptPrce = number_format($arr['presmptPrce']);
			// 8088 = 입찰공고 8081 = 일찰공고 상세
			$tr = "<li>";
			$tr .= '<a class="a1" href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='.$arr['bidNtceNo'].'&bidseq='.$arr['bidNtceOrd'].'&releaseYn=Y&taskClCd=1 ">'.$arr['bidNtceNm'].'<br /><font class="f1">'.$arr['ntceInsttNm'].'&#32;|&#32;'.$arr['dminsttNm'].'<br />공고: '.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'&#32;|&#32;마감: '.substr($arr['bidClseDt'],0,10).'</font> </a>';
			$tr .= '</li>';
			/*
			 $tr = '<tr>';
			 $tr .= '<td style="text-align: center;"><input id='.$chkid.' name='.$chkid.' type="checkbox" /></td>';
			 $tr .= '<td style="text-align: center;"><a onclick=\'viewDtl("'. $arr['bidNtceDtlUrl'].'")\'>'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
			 
			 $tr .= '<td title="'.$pss.'">'.$arr['bidNtceNm'].'</td>';
			 $tr .= '<td align=right>'.$presmptPrce.'</td>';
			 $tr .= '<td style="text-align: center;">'.substr($arr['bidNtceDt'],0,10).'</td>';
			 $tr .= '<td><a onclick=\'viewscs("'.$arr['dminsttNm'].'")\'>'.$arr['dminsttNm'].'</a></td>';
			 $tr .= '<td style="text-align: center;"><a onclick=\'viewRslt("'.$arr['bidNtceNo'].'","'.$arr['bidNtceOrd'].'","'.$arr['bidClseDt'].'","'.$pss.'")\'>'.substr($arr['bidClseDt'],0,10).'</a></td>';
			 $tr .= '</tr>';
			 */
			echo $tr;
			$i += 1;
		}
		echo '</div><br>';
	}
	/* ---------------------------------------------------------------------------------
	 낙찰 결과 : 응찰 업체 전부
	 ------------------------------------------------------------------------------------ */
	function getRsltData($bidNtceNo,$bidNtceOrd) {
		return $this->getRsltDataNo($bidNtceNo,$bidNtceOrd,'999','1');
	} // getRsltData
	function getRsltDataTotalCount($bidNtceNo,$bidNtceOrd) {
		$response = $this->getRsltDataNo($bidNtceNo,$bidNtceOrd,'1');
		$json1 = json_decode($response, true);
		$totCnt = $json1['response']['body']['totalCount'];
		return $totCnt;
	}

	
	//------------------------------------
	// 최고 999건으로 나눠서 계속 받음
	// --> max 999건으로 제한 필요 -by jsj
	//------------------------------------
	function getRsltDataAll($bidNtceNo,$bidNtceOrd) {
		$noRow = 999;
		$pageNo=1;
		$response = $this->getRsltDataNo($bidNtceNo,$bidNtceOrd,$noRow);
		//var_dump($response);
		$json1 = json_decode($response, true);
		$totCnt = $json1['response']['body']['totalCount'];
		//echo '<br>'.' totcnt='.$totCnt;
		$totCnt1 = $totCnt;
		$cnt = $totCnt - $noRow;
		$item = $json1['response']['body']['items'];
		//echo '<br>'.'cnt='.$cnt.' totcnt1='.$totCnt1;
		
		
		while ($cnt > 0) {
			//-----------------------------------
			// 999건 까지만 적용  -by jsj 190602
			// openBidSeq 수집 시 에러가 남
			break;
			//-----------------------------------
			$pageNo++;
			$response2 = $this->getRsltDataNo($bidNtceNo,$bidNtceOrd,$noRow,$pageNo);
			$json2 = json_decode($response2, true);
			$item2 = $json2['response']['body']['items'];
			//$response = $response.concat($response1);
			$item = array_merge($item,$item2);
			$totCnt1 = count($json2['response']['body']['items']);
			$cnt = $cnt - $totCnt1;
			//echo '<br>'.'cnt='.$cnt.' totcnt1='.$totCnt1.' item='.count($item).' pageNo='.$pageNo;
		}
		//echo 'item='.count($item).'<br><br><br><br>';
		return $item;
	}
	/* ---------------------------------------------------------------------------------
	 낙찰 결과 : 1위 업체
	 ------------------------------------------------------------------------------------ */
	function getRsltData1($bidNtceNo,$bidNtceOrd) {
		return $this->getRsltDataNo($bidNtceNo,$bidNtceOrd,'1');
	}
	
	/* ---------------------------------------------------------------------------------
	 낙찰 결과 : 응찰 업체 전부
	 ------------------------------------------------------------------------------------ */
	
	function getRsltDataNo($bidNtceNo,$bidNtceOrd,$numOfRows,$pageNo=1) {
		global $ServiceKey,$ServiceKey2,$ServiceKey_uloca23,$uloca_live_test;
		$ch = curl_init();
		//global $ServiceKey;
		//http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoOpengCompt?pageNo=1&numOfRows=100&type=json&inqryDiv=3&bidNtceNo=20170525353&bidNtceOrd=00&ServiceKey=mCQAlSkRqyZb00fZumkGyJin7uoOD7C8%2BKNRtfUUDEnnJa4p7c71m%2B%2F1h7cmFOFn87UCrnoTxzFPsd81kLuZww%3D%3D
		// 개찰결과 개찰완료 목록 조회
		$bidrdo = 'bidopen';
		$url = $this->getAddr($bidrdo);
		//$url = 'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoOpengCompt';
		
		
		//'http://apis.data.go.kr/1230000/ScsbidInfoService/getOpengResultListInfoServc'; /*URL*/
		/*Service Key*/
		$queryParams = '?' . urlencode('numOfRows') . '=' . $numOfRows; /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . $pageNo; /*페이지 번호*/
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*json*/
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode(3); /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
		$queryParams .= '&' . urlencode('bidNtceNo') . '=' . urlencode($bidNtceNo); /*검색하고자하는 입찰공고번호*/
		$queryParams .= '&' . urlencode('bidNtceOrd') . '=' . ''; //urlencode($bidNtceOrd); /*검색하고자하는 입찰공고차수*/
		//$uloca_live_test = '2';
		//echo 'uloca_live_test='.$uloca_live_test;
		
		$ServiceKey = $this->getServiceKey($uloca_live_test);
		//echo ' ServiceKey='.$ServiceKey;
		$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey;
		
		
		/* if ($uloca_live_test == '2') {	// test
		 //$queryParams .= '&' . urlencode('ServiceKey') . '=' . $ServiceKey_uloca23;
		 $queryParams .= '&' . urlencode('ServiceKey') . '=' . 'aQAvWmy3XF13lanl8ELaaOBq%2Fw4W1OHXY9b40KiZQ1hZuMX0Cv6B3ickvm5tzWMcMaw0VsYbRayxIwSVCAPybw%3D%3D';
		 }
		 else {
		 $queryParams .= '&' . urlencode('ServiceKey') . '=' . 'Q5eI3cYX4CzlTWVI4YMFOkw41NPqQMvSZ%2FXhLM9eud43t%2B7NKImGzkhz4%2F4iIHi0SmrZBkgYSrlhhyshLQhVvA%3D%3D';
		 } */
		//$urls = $url . $queryParams;
		//echo $urls;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	/* ---------------------------------------------------------------------------------
	 공고번호 최대 차수 구하기 :
	 ------------------------------------------------------------------------------------ */
	function getMaxOrd($bidNtceNo='20171217933',$tbl='openBidInfo2') {
		$conn = new mysqli('localhost', 'uloca22', 'w3m69p21!@', 'uloca22');
		mysqli_set_charset($conn, 'utf8');
		$bidNtceOrd = '00';
		$sql = "select max(bidNtceOrd) as Ord from ".$tbl." where bidNtceNo ='".$bidNtceNo."'";
		$result = $conn->query($sql);
		if ($row = $result->fetch_assoc()) $bidNtceOrd = $row['Ord'];
		return $bidNtceOrd;
		//select max(bidNtceOrd) as Ord from openBidInfo2 where bidNtceNo ='20171217933'
	}
	/* ---------------------------------------------------------------------------------
	 입찰 정보 :
	 ------------------------------------------------------------------------------------ */
	function getBidInfo($bidNtceNo,$bidNtceOrd,$pss) {
		$ch = curl_init();
		global $ServiceKey;
		if ($pss == '입찰용역') $pg = 'BidPublicInfoService/getBidPblancListInfoServc'; // 입찰공고목록 정보에 대한 용역조회
		else if ($pss == '입찰물품') $pg = 'BidPublicInfoService/getBidPblancListInfoThng'; // 입찰공고목록 정보에 대한 물품조회
		else if ($pss == '입찰공사') $pg = 'BidPublicInfoService/getBidPblancListInfoCnstwk'; // 입찰공고목록 정보에 대한 공사조회
		else if ($pss == '사전용역') $pg = 'HrcspSsstndrdInfoService/getPublicPrcureThngInfoServc'; // 사전공고목록 정보에 대한 용역조회
		else if ($pss == '사전물품') $pg = 'HrcspSsstndrdInfoService/getPublicPrcureThngInfoThng'; // 사전공고목록 정보에 대한 물품조회
		else if ($pss == '사전공사') $pg = 'HrcspSsstndrdInfoService/getPublicPrcureThngInfoCnstwk'; // 사전공고목록 정보에 대한 공사조회
		else $pg = 'BidPublicInfoService/getBidPblancListInfoCnstwk';
		//조회구분	inqryDiv 1.등록일시, 2.입찰공고번호 3.변경일시
		//http://apis.data.go.kr/1230000/BidPublicInfoService/getBidPblancListInfoCnstwk?pageNo=1&numOfRows=100&type=json&inqryDiv=2&bidNtceNo=20170525353&bidNtceOrd=00&ServiceKey=mCQAlSkRqyZb00fZumkGyJin7uoOD7C8%2BKNRtfUUDEnnJa4p7c71m%2B%2F1h7cmFOFn87UCrnoTxzFPsd81kLuZww%3D%3D
		
		$url = 'http://apis.data.go.kr/1230000/'.$pg;
		/*URL*/
		/*Service Key*/
		$queryParams = '?' . urlencode('numOfRows') . '=' . '900'; /*한 페이지 결과 수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . '1'; /*페이지 번호*/
		$queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /*json*/
		$queryParams .= '&' . urlencode('inqryDiv') . '=' . urlencode(2); /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
		$queryParams .= '&' . urlencode('bidNtceNo') . '=' . urlencode($bidNtceNo); /*검색하고자하는 입찰공고번호*/
		$queryParams .= '&' . urlencode('bidNtceOrd') . '=' . ''; //urlencode($bidNtceOrd); /*검색하고자하는 입찰공고차수*/
		
		$queryParams .= '&' . urlencode('ServiceKey') . '=' .  'BT4h3Pd5ovl0%2BOWmcIGClMw42vc%2F%2B9Asx6MAg%2Fa4xt1jg%2BF4q9ZfU9Tm8qlo09bZWZjSlcr3Uf062qMVG56vpA%3D%3D';
		//$urls = $url . $queryParams;
		//echo $urls;
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	} // getBidInfo
	/* ----------------------------------------------------------------------
	 json 에서 물품, 용역, 공사 구분자
	 unique column namme
	 용역입찰	용역구분명 srvceDivNm
	 물품입찰	물품규격명 prdctSpecNm, 물품수량 prdctQty
	 공사입찰	부대공종명1	subsiCnsttyNm1
	 --------------------------------------------------------------------- */
	function getDivNm($arr) {
		$rslt = '입찰물품';
		if (array_key_exists('srvceDivNm', $arr)) $rslt = '입찰용역';
		if (array_key_exists('subsiCnsttyNm1', $arr)) $rslt = '입찰공사';
		
		return $rslt;
	}
	/* ----------------------------------------------------------------------
	 입찰 정보
	 ---------------------------------------------------------------------- */
	function getBidAllJson($startDate,$endDate,$kwd,$dminsttNm,$num=100,$inqryDiv=2) {
		// default inqryDiv=2 개찰일시
		$response1 = $this->getSvrData('bidthing',$startDate,$endDate,$kwd,$dminsttNm,$num,'1',$inqryDiv); // 물품입찰
		$response2 = $this->getSvrData('bidcnstwk',$startDate,$endDate,$kwd,$dminsttNm,$num,'1',$inqryDiv); // 공사입찰
		$response3 = $this->getSvrData('bidservc',$startDate,$endDate,$kwd,$dminsttNm,$num,'1',$inqryDiv); // 용역입찰
		//echo 'getBidOne';
		//var_dump($response1);
		
		$json1 = json_decode($response1, true);
		$item1 = $json1['response']['body']['items'];
		//echo '<br>'.'물품입찰<br>';
		//var_dump($item1);
		$json2 = json_decode($response2, true);
		$item2 = $json2['response']['body']['items'];
		
		$json3 = json_decode($response3, true);
		$item3 = $json3['response']['body']['items'];
		
		$item = array_merge($item1,$item2,$item3); //,$item4);
		//var_dump($item);
		return $item;
	}
	function changeDateFormat($frdate) {
		//	2018-09-01 12:12:12 --> 201809011212
		// substr($endDate,0,4).'-'.substr($endDate,4,2).'-'.substr($endDate,6,2).' 23:59:59';
		$todate = substr($frdate,0,4).substr($frdate,5,2).substr($frdate,8,2) .substr($frdate,11,2).substr($frdate,14,2);
		return $todate;
	}
	
	function findColumn($item,$srchcol,$srchval,$retcol) {
		//echo 'findColumn '.count($item);
		foreach($item as $arr ) { //foreach element in $arr
			if ($srchval == $arr[$srchcol]) return $arr[$retcol];
		}
		return ''; // not found
	}
	
	// call from plugins/doauto/runauto.php
	//
	function getBidOne($startDate,$endDate,$kwd,$dminsttNm,$num=20,$inqryDiv=2,$search=15) {
		// 디폴트 전체..
		/*if ($searchType == 1) $search = '물품';
		 else if ($searchType == 2) $search = '사전규격';
		 else if ($searchType == 3) $search = '물품+사전규격';
		 else if ($searchType == 4) $search = '공사';
		 else if ($searchType == 5) $search = '물품+공사';
		 else if ($searchType == 6) $search = '공사+사전규격';
		 else if ($searchType == 7) $search = '물품+공사+사전규격';
		 else if ($searchType == 8) $search = '용역';
		 else if ($searchType == 9) $search = '물품+용역';
		 else if ($searchType == 10) $search = '용역+사전규격';
		 else if ($searchType == 11) $search = '물품+용역+사전규격';
		 else if ($searchType == 12) $search = '공사+용역';
		 else if ($searchType == 13) $search = '물품+공사+용역';
		 else if ($searchType == 14) $search = '공사+용역+사전규격';
		 else if ($searchType == 15) $search = '물품+공사+용역+사전규격'; */
		$response1 = NULL;
		$response2 = NULL;
		$response3 = NULL;
		//getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv)
		// 물품
		if ($search == 1 || $search == 3 || $search == 5 || $search == 7 || $search == 9 || $search == 11 || $search == 13 || $search == 15 ) {
			$response1 = $this->getSvrData('bidthing',$startDate,$endDate,$kwd,$dminsttNm,$num,'1',$inqryDiv); // 물품입찰
		}
		// 공사
		if ($search == 4 || $search == 5 || $search == 6 || $search == 7 || $search == 12 || $search == 13 || $search == 14 || $search == 15 ) {
			$response2 = $this->getSvrData('bidcnstwk',$startDate,$endDate,$kwd,$dminsttNm,$num,'1',$inqryDiv); // 공사입찰
		}
		// 용역
		if ($search == 8 || $search == 9 || $search == 10 || $search == 11 || $search == 12 || $search == 13 || $search == 14 || $search == 15 ) {
			$response3 = $this->getSvrData('bidservc',$startDate,$endDate,$kwd,$dminsttNm,$num,'1',$inqryDiv); // 용역입찰
		}
		//echo 'getBidOne';
		//var_dump($response1);
		$header = '';
		if ($response1 != NULL) {
			$json1 = json_decode($response1, true);
			$item1 = $json1['response']['body']['items'];
			//echo '<br>'.'물품입찰<br>';
			//var_dump($item1);
			$header .= $this->getBidInfo2($item1,'물품',$kwd,$dminsttNm,$startDate,$endDate);
		}
		if ($response2 != NULL) {
			$json2 = json_decode($response2, true);
			$item2 = $json2['response']['body']['items'];
			$header .= $this->getBidInfo2($item2,'공사',$kwd,$dminsttNm,$startDate,$endDate);
		}
		if ($response3 != NULL) {
			$json3 = json_decode($response3, true);
			$item3 = $json3['response']['body']['items'];
			$header .= $this->getBidInfo2($item3,'용역',$kwd,$dminsttNm,$startDate,$endDate);
		}
		
		
		return $header;
		
	}
	// 자동받기 입찰정보..
	function getBidInfo2($item,$pss,$kwd,$dminsttNm,$startDate,$endDate) {
		$header = '
		&nbsp;&nbsp;&nbsp;&nbsp<div style=\'font-size:18px; color:black;font-weight:bold;\'>[입찰 정보 ('.$pss.')]</center>
		<div id=totalrec>total record='.count($item).'</div>
		';
		
		$i=0;
		foreach($item as $arr ) { //foreach element in $arr
			$k = $i+1;
			$bidno = $arr['bidNtceNo'];
			$bidseq = $arr['bidNtceOrd'];
			$pss = $arr['pss'];
			$bidurl = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='.$bidno.'&bidseq='.$bidseq;
			/*나라장터검색조건에 의한 입찰공고물품조회,용역,건설	www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180810710&bidseq=01
			 */
			
			if (substr($pss,0,2) == '계획') {
				$balju=true;
				$bidClseDt = "&nbsp;&nbsp;&nbsp;&nbsp;"+$pss;
			} else {
				$balju = false;
				$bidClseDt = substr($arr['bidClseDt'],0,10);
			}
			
			$tr = '<li style="font-size:12px;"><a href="'.$bidurl.'">';
			$tr .= $arr['bidNtceNm'].'<br>';
			$tr .= '공고:'.$arr['bidNtceNo'].'-' . $arr['bidNtceOrd'] . ' 마감:'. $bidClseDt .  '</a></li><br>';
			
			
			$header .= $tr;
			$i += 1;
		}
		if ($i==0) $header.='<p colspan=7 style="text-align:center;">해당 자료가 없습니다.</p>';
		//$header .= '';
		
		return $header;
	}
	function getBidInfo2_1($item,$pss,$kwd,$dminsttNm,$startDate,$endDate) {
		$header = '
		<center><div style=\'font-size:18px; color:blue;font-weight:bold;\'>- 입찰 정보 ('.$pss.') -</center>
		<div id=totalrec>total record='.count($item).'</div>
		<table class="type10" id="bidData">
			<tr>
				
				<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="5%;">no.</th>
				<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="10%;">공고번호</th>
				<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="25%;">공고명</th>
				<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="10%;">추정가격</th>
				<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="15%;">공고일</th>
				<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="20%;">수요기관</th>
				<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="15%;">마감일</th>
				<!-- col style="width:15%;" /><col style="width:auto;" / -->
				
			</tr>
		';
		
		$i=0;
		foreach($item as $arr ) { //foreach element in $arr
			$k = $i+1;
			$bidno = $arr['bidNtceNo'];
			$bidseq = $arr['bidNtceOrd'];
			//$pss = $this->getDivNm($arr);
			$bidurl = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='.$bidno.'&bidseq='.$bidseq;
			/*나라장터검색조건에 의한 입찰공고물품조회,용역,건설	www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180810710&bidseq=01
			 */
			$tr = '<tr>';
			$tr .= '<td scope="row" style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;">'.$k.'</td>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;"><a href="'.$bidurl.'">'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</a></td>';
			
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; background: #ffffff; margin: 2px 2px;">'.$arr['bidNtceNm'].'</td>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: right;  background: #ffffff; margin: 2px 2px;">'.number_format($arr['presmptPrce']).'</td>';
			if ($arr['bidNtceDt'] == '') $dts = '';
			else $dts = substr($arr['bidNtceDt'],0,10);
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center; background: #ffffff; margin: 2px 2px;">'.$dts.'</td>';
			//$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; background: #ffffff; margin: 2px 2px;"><a href="http://uloca.net/g2b/getBid.php?kwd='.$kwd.'&dminsttNm='.$dminsttNm.'&inqryBgnDt='.$startDate.'&inqryEndDt='.$endDate.'\">'.$arr['dminsttNm'].'</a></td>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; background: #ffffff; margin: 2px 2px;">'.$arr['dminsttNm'].'</td>';
			if ($arr['bidClseDt'] == '') $dts = '';
			else $dts = substr($arr['bidClseDt'],0,10);
			//$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center; background: #ffffff; margin: 2px 2px;"><a href="http://uloca.net/g2b/bidResult.php?bidNtceNo='.$bidno."&bidNtceOrd=".$bidseq."&pss=".$pss.'">'.$dts.'</a></td>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center; background: #ffffff; margin: 2px 2px;">'.$dts.'</td>';
			$tr .= '</tr>';
			$header .= $tr;
			$i += 1;
		}
		if ($i==0) $header.='<tr><td colspan=7 style="text-align:center;">해당 자료가 없습니다.</td></tr>';
		$header .= '</table>';
		
		return $header;
	}
	/* ----------------------------------------------------------------------
	 사전규격 정보
	 ---------------------------------------------------------------------- */
	function getHrcOne($startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$inqryDiv,$search) {
		//target = hrcthing/hrccnstwk/hrcservc
		//if ($search == 2 || $search == 3 || $search == 6 || $search == 7 || $search == 10 || $search == 11 || $search == 14 || $search == 15 ) $contents2 = $g2bClass->getHrcOne($startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$inqryDiv,$search);
		
		if ($search == 2 || $search == 3 || $search == 7 || $search == 11 || $search == 15 ) {
			$target = 'hrcthing';
			$response1 = $this->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1',$inqryDiv); // 물품입찰
		}
		// 공사
		if ($search == 2 || $search == 6 || $search == 7 || $search == 14 || $search == 15 ) {
			$target = 'hrccnstwk';
			$response2 = $this->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1',$inqryDiv); // 공사입찰
		}
		// 용역
		if ($search == 2 || $search == 10 || $search == 11 || $search == 14 || $search == 15 ) {
			$target = 'hrcservc';
			$response3 = $this->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1',$inqryDiv); // 용역입찰
		}
		//echo 'getBidOne';
		//var_dump($response1);
		$header = '';
		if ($response1 != NULL) {
			$json1 = json_decode($response1, true);
			$item1 = $json1['response']['body']['items'];
			//echo '<br>'.'물품입찰<br>';
			//var_dump($item1);
			$header .= $this->getHrcInfo2($item1,'물품',$kwd,$dminsttNm,$startDate,$endDate);
		}
		if ($response2 != NULL) {
			$json2 = json_decode($response2, true);
			$item2 = $json2['response']['body']['items'];
			$header .= $this->getHrcInfo2($item2,'공사',$kwd,$dminsttNm,$startDate,$endDate);
		}
		if ($response3 != NULL) {
			$json3 = json_decode($response3, true);
			$item3 = $json3['response']['body']['items'];
			$header .= $this->getHrcInfo2($item3,'용역',$kwd,$dminsttNm,$startDate,$endDate);
		}
		return $header;
		
	}
	// 자동받기 입찰정보..
	function getHrcInfo2($item,$pss,$kwd,$dminsttNm,$startDate,$endDate) {
		$header = '
		&nbsp;&nbsp;&nbsp;&nbsp<div style=\'font-size:18px; color:black;font-weight:bold;\'>[사전규격 정보 ('.$pss.')]
		<div id=totalrec>total record='.count($item).'</div>
		';
		
		$i=0;
		foreach($item as $arr ) { //foreach element in $arr
			$k = $i+1;
			
			$bidurl = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='.$arr['bidno'].'&bidseq='.$arr['bidseq'];
			$bidno = $arr['bfSpecRgstNo'];
			$bidurl = 'https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo='.$arr['bfSpecRgstNo']; //622051
			
			/*나라장터검색조건에 의한 입찰공고물품조회,용역,건설	www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180810710&bidseq=01
			 */
			
			if (substr($pss,0,2) == '계획') {
				$balju=true;
				$bidClseDt = "&nbsp;&nbsp;&nbsp;&nbsp;"+$pss;
			} else {
				$balju = false;
				$bidClseDt = substr($arr['opninRgstClseDt'],0,10);
			}
			
			$tr = '<li style="font-size:12px;"><a href="'.$bidurl.'">';
			$tr .= $arr['prdctClsfcNoNm'].'<br>';
			$tr .= '공고:'.$arr['bfSpecRgstNo']. ' 마감:'. $bidClseDt . '</a></li><br>';
			
			
			$header .= $tr;
			$i += 1;
		}
		if ($i==0) $header.=''; //'<p colspan=7 style="text-align:center;">해당 자료가 없습니다.</p>';
		//$header .= '';
		
		return $header;
	}
	function getHrcOne1($target,$startDate,$endDate,$kwd,$dminsttNm,$pss) {
		//$pss = '사전물품';
		//target = hrcthing/hrccnstwk/hrcservc
		$response1 = $this->getSvrData($target,$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 물품사전규격
		$json1 = json_decode($response1, true);
		$item = $json1['response']['body']['items'];
		foreach ($item as $key => $row) {
			$opninRgstClseDt[$key]  = $row['opninRgstClseDt'];
		}
		if (count($item) > 1) array_multisort($opninRgstClseDt, SORT_DESC, $item); // 등록일시
		$i=0;
		$header = '
	&nbsp;&nbsp;&nbsp;&nbsp<div style=\'font-size:18px;\'><font color=\'black\'><strong>[사전규격 .'.$pss.']</strong></font>
	<div id=totalrechrc>total record='.count($item).'</div>
			
	';
		foreach($item as $arr ) { //foreach element in $arr
			$k = $i + 1;
			$bidno = $arr['bfSpecRgstNo'];
			$bidurl = 'https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo='.$arr['bfSpecRgstNo']; //622051
			
			
			$tr = '<li><a href="'.$bidurl.'">';
			$tr .= $arr['prdctClsfcNoNm'].'<br>';
			$tr .= '공고:'.$arr['bfSpecRgstNo'] . ' 마감:'. substr($arr['opninRgstClseDt'],0,10) . '</a></li><br>';
			
			$header .= $tr;
			$i += 1;
		}
		//$header .=  '</table>';
		return $header;
	}
	function getHrcOne2($startDate,$endDate,$kwd,$dminsttNm,$search=15) {
		$response1 = $this->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 물품사전규격
		$response2 = $this->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 공사사전규격
		$response3 = $this->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 용역사전규격
		
		//var_dump($response1);
		
		$json1 = json_decode($response1, true);
		$item1 = $json1['response']['body']['items'];
		//echo '<br>'.'물품사전규격<br>';
		//var_dump($item1);
		
		$json2 = json_decode($response2, true);
		$item2 = $json2['response']['body']['items'];
		
		$json3 = json_decode($response3, true);
		$item3 = $json3['response']['body']['items'];
		
		$item = array_merge($item1,$item2,$item3); //,$item4);
		//var_dump($item);
		
		$header = '
	&nbsp;&nbsp;&nbsp;&nbsp<div style=\'font-size:18px;\'><font color=\'black\'><strong>[사전규격 정보]</strong></font>
	<div id=totalrechrc>total record='.count($item).'</div>
			
	';
		
		// 열 목록 얻기
		foreach ($item as $key => $row) {
			$opninRgstClseDt[$key]  = $row['opninRgstClseDt'];
		}
		if (count($item) > 1) array_multisort($opninRgstClseDt, SORT_DESC, $item); // 등록일시
		
		
		$i=0;
		foreach($item as $arr ) { //foreach element in $arr
			$k = $i + 1;
			$bidno = $arr['bfSpecRgstNo'];
			//$bidseq = $arr['bidNtceOrd'];
			//$pss = $this->getDivNm($arr);
			//$bidurl = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='.$bidno.'&bidseq='.$bidseq;
			$bidurl = 'https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo='.$arr['bfSpecRgstNo']; //622051
			
			/*if (substr($pss,0,2) == '계획') {
			 $balju=true;
			 $bidClseDt = "&nbsp;&nbsp;&nbsp;&nbsp;"+$pss;
			 } else {
			 $balju = false;
			 $bidClseDt = substr($arr['bidClseDt'],0,10);
			 }
			 */
			$tr = '<li><a href="'.$bidurl.'">';
			$tr .= $arr['prdctClsfcNoNm'].'<br>';
			$tr .= '공고:'.$arr['bfSpecRgstNo'] . ' 마감:'. substr($arr['opninRgstClseDt'],0,10) . '</a></li><br>';
			
			$header .= $tr;
			$i += 1;
		}
		//$header .=  '</table>';
		return $header;
	}
	function getHrcOne_old($startDate,$endDate,$kwd,$dminsttNm,$search=15) {
		$response1 = $this->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 물품사전규격
		$response2 = $this->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 공사사전규격
		$response3 = $this->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,'100','1','1'); // 용역사전규격
		
		//var_dump($response1);
		
		$json1 = json_decode($response1, true);
		$item1 = $json1['response']['body']['items'];
		//echo '<br>'.'물품사전규격<br>';
		//var_dump($item1);
		
		$json2 = json_decode($response2, true);
		$item2 = $json2['response']['body']['items'];
		
		$json3 = json_decode($response3, true);
		$item3 = $json3['response']['body']['items'];
		
		$item = array_merge($item1,$item2,$item3); //,$item4);
		//var_dump($item);
		
		$header = '
	<center><div style=\'font-size:18px;\'><font color=\'blue\'><strong>- 사전규격 정보 -</strong></font></center>
	<div id=totalrechrc>total record='.count($item).'</div>
	<table class="type10" id="specData">
		<tr>
			
			<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="5%;">no.</th>
			<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="10%;">등록번호</th>
			<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="25%;">품명</th>
			<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="15%;">예산금액</th>
			<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="12%;">등록일</th>
			<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="20%;">수요기관</th>
			<th style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #438ad1; margin: 2px 2px;" width="13%;">마감일</th>
			<!-- col style="width:15%;" /><col style="width:auto;" / -->
			<!-- 등록번호, 품명, 예산금액, 등록일시, 수요기관, 마감일시
			https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo=605594 상세정보 -->
		</tr>
	';
		
		// 열 목록 얻기
		foreach ($item as $key => $row) {
			$opninRgstClseDt[$key]  = $row['opninRgstClseDt'];
		}
		if (count($item) > 1) array_multisort($opninRgstClseDt, SORT_DESC, $item); // 등록일시
		
		
		$i=0;
		foreach($item as $arr ) { //foreach element in $arr
			$k = $i + 1;
			$bidno = $arr['bidNtceNo'];
			$bidseq = $arr['bidNtceOrd'];
			$pss = $this->getDivNm($arr);
			//$bidurl = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='.$bidno.'&bidseq='.$bidseq;
			$bidurl = 'https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo='.$arr['bfSpecRgstNo']; //622051
			
			$tr = '<tr>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;">'.$k.'</td>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;"><a href="https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo='.$arr['bfSpecRgstNo'].'">'.$arr['bfSpecRgstNo'].'</a></td>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; background: #ffffff; margin: 2px 2px;">'.$arr['prdctClsfcNoNm'].'</td>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: right;  background: #ffffff; margin: 2px 2px;">'.number_format($arr['asignBdgtAmt']).'</td>';
			if ($arr['rgstDt'] == '') $dts = '';
			else $dts = substr($arr['rgstDt'],0,10);
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;">'.$dts.'</td>';
			
			//$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; background: #ffffff; margin: 2px 2px;"><a href="http://uloca.net/g2b/getBid.php?kwd='.$kwd.'&dminsttNm='.$dminsttNm.'&inqryBgnDt='.$startDate.'&inqryEndDt='.$endDate.'">'.$arr['rlDminsttNm'].'</a></td>';
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; background: #ffffff; margin: 2px 2px;">'.$arr['rlDminsttNm'].'</td>';
			
			
			if ($arr['opninRgstClseDt'] == '') $dts = '';
			else $dts = substr($arr['opninRgstClseDt'],0,10);
			$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;">'.$dts.'</td>';
			//$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;"><a href="http://uloca23.cafe24.com/g2b/bidResult.php?bidNtceNo='.$bidno."&bidNtceOrd=".$bidseq."&pss=".$pss.'">'.$arr['opninRgstClseDt'].'</a></td>';
			$tr .= '</tr>';
			
			/*
			 if ($i % 2 == 0) {
			 $tr = '<tr>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;">'.$k.'</td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;"><a href="https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo='.$arr['bfSpecRgstNo'].'">'.$arr['bfSpecRgstNo'].'</a></td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; background: #ffffff; margin: 2px 2px;">'.$arr['prdctClsfcNoNm'].'</td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: right;  background: #ffffff; margin: 2px 2px;">'.number_format($arr['asignBdgtAmt']).'</td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;">'.$arr['rgstDt'].'</td>';
			 
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; background: #ffffff; margin: 2px 2px;"><a href="http://uloca23.cafe24.com/g2b/getBid.php?kwd='.$kwd.'&dminsttNm='.$dminsttNm.'&inqryBgnDt='.$startDate.'&inqryEndDt='.$endDate.'">'.$arr['rlDminsttNm'].'</a></td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;">'.$arr['opninRgstClseDt'].'</td>';
			 //$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #ffffff; margin: 2px 2px;"><a href="http://uloca23.cafe24.com/g2b/bidResult.php?bidNtceNo='.$bidno."&bidNtceOrd=".$bidseq."&pss=".$pss.'">'.$arr['opninRgstClseDt'].'</a></td>';
			 $tr .= '</tr>';
			 } else {
			 $tr = "<tr>";
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #aaccff; margin: 2px 2px;">'.$k.'</td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #aaccff; margin: 2px 2px;"><a href="https://www.g2b.go.kr:8143/ep/preparation/prestd/preStdDtl.do?preStdRegNo='.$arr['bfSpecRgstNo'].'">'.$arr['bfSpecRgstNo'].'</a></td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top;  background: #aaccff; margin: 2px 2px;">'.$arr['prdctClsfcNoNm'].'</td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: right;  background: #aaccff; margin: 2px 2px;">'.number_format($arr['asignBdgtAmt']).'</td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #aaccff; margin: 2px 2px;">'.$arr['rgstDt'].'</td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top;  background: #aaccff; margin: 2px 2px;"><a href="http://uloca23.cafe24.com/g2b/getBid.php?kwd='.$kwd.'&dminsttNm='.$dminsttNm.'&inqryBgnDt='.$startDate.'&inqryEndDt='.$endDate.'">'.$arr['rlDminsttNm'].'</a></td>';
			 $tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #aaccff; margin: 2px 2px;">'.$arr['opninRgstClseDt'].'</td>';
			 //$tr .= '<td style="padding: 6px; font-size:12px; vertical-align: top; text-align: center;  background: #aaccff; margin: 2px 2px;"><a href="http://uloca23.cafe24.com/g2b/bidResult.php?bidNtceNo='.$bidno."&bidNtceOrd=".$bidseq."&pss=".$pss.'">'.$arr['opninRgstClseDt'].'</a></td>';
			 $tr .= '</tr>';
			 }*/
			$header .= $tr;
			$i += 1;
		}
		$header .=  '</table>';
		return $header;
	}
	function dateTimeFormat($dt,$sep='/',$sep2=':') {
		$ndt='';
		//echo $dt.$sep.$sep2;
		if (strlen($dt) < 9 || $sep2 == '') {
			$ndt = substr($dt,0,4).$sep.substr($dt,4,2).$sep.substr($dt,6,2);
		}
		else if (strlen($dt) > 11) {
			$ndt = substr($dt,0,4).$sep.substr($dt,4,2).$sep.substr($dt,6,2).' '.substr($dt,8,2).$sep2.substr($dt,10,2);
		}
		else { $ndt = $dt; }
		
		return $ndt;
	}
	function dt2duration($opengdt) {
		if ($opengdt == '') $opengdt = '2018'; //-12';
		//$mon = substr($opengdt,5,2);
		$yr = substr($opengdt,0,4);
		//if ($mon >= '01' && $mon< '07') $duration = $yr;
		$duration = $yr; //.'_2';
		/*if ($opengdt <= '2020-12' && $opengdt >= '2020-07') $duration = '2020_2';
		 else if ($opengdt <= '2020-06' && $opengdt >= '2020-01') $duration = '2020';
		 else if ($opengdt <= '2019-12' && $opengdt >= '2019-07') $duration = '2019_2';
		 else if ($opengdt <= '2019-06' && $opengdt >= '2019-01') $duration = '2019';
		 else if ($opengdt <= '2018-12' && $opengdt >= '2018-07') $duration = '2018_2';
		 else if ($opengdt <= '2018-06' && $opengdt >= '2018-01') $duration = '2018';
		 else if ($opengdt <= '2017-12' && $opengdt >= '2017-07') $duration = '2017_2';
		 else if ($opengdt <= '2017-06' && $opengdt >= '2017-01') $duration = '2017';
		 else if ($opengdt <= '2016-12' && $opengdt >= '2016-07') $duration = '2016_2';
		 else if ($opengdt <= '2016-06' && $opengdt >= '2016-01') $duration = '2016'; */
		return $duration;
	}
	function autoRecList($login_userid) {
		
		
		$conn = new mysqli("localhost", "uloca22", "w3m69p21!@", "uloca22");
		//$conn = new mysqli('localhost', 'uloca23', 'uloca23090(', 'uloca23');
		// Check connection
		if ($conn->connect_error) {
			die("DB Connection failed: " . $conn->connect_error);
		}
		//echo $current_user;
		//if ($current_user->user_login != '' ) {
		$now = new DateTime();
		$nows = $now->format('Y-m-d H:i:s');
		
		//$sql = 'select a.*,b.till from autoPubDatas a, autoPubAccnt b where a.id=b.id and b.till>= \''.$nows.'\' and a.id = \''. $current_user->user_login . '\' order by a.id';
		$sql = 'SELECT a.*, b.till FROM autoPubDatas AS a LEFT OUTER JOIN autoPubAccnt AS b ON a.id = b.id where a.id = \''. $login_userid . '\'  order by b.till';
		//SELECT a.*, b.till FROM autoPubDatas AS a LEFT OUTER JOIN autoPubAccnt AS b ON a.id = b.id where a.id ='jayhmj@naver.com' and b.till >= '20180723' order by a.id
		//$sql = 'select a.*, b.till from autoPubDatas a, autoPubAccnt b where a.id=b.id  order by a.idx';
		//echo 'sql='.$sql;
		$result = $conn->query($sql);
		$cont = '
<div id=totalrec style="text-align: left;">total records='.mysqli_num_rows($result).'</div>
		
<table class="type10" id="bidData">
    <tr>
        <th scope="cols" width="5%;">번호</th>
		<th scope="cols" width="12%;">아이디</th>
        <th scope="cols" width="15%;">이메일</th>
        <th scope="cols" width="12%;">키워드</th>
        <th scope="cols" width="12%;">수요기관</th>
        <th scope="cols" width="12%;">검색종류</th>
		<th scope="cols" width="15%;">알림</th>
		<th scope="cols" width="16%;">종료일</th>
		
    </tr>
';
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			$k = $i+1;
			$searchType = $row["searchType"];
			
			if ($searchType == 1) $search = '물품';
			else if ($searchType == 2) $search = '사전규격';
			else if ($searchType == 3) $search = '물품+사전규격';
			else if ($searchType == 4) $search = '공사';
			else if ($searchType == 5) $search = '물품+공사';
			else if ($searchType == 6) $search = '공사+사전규격';
			else if ($searchType == 7) $search = '물품+공사+사전규격';
			else if ($searchType == 8) $search = '용역';
			else if ($searchType == 9) $search = '물품+용역';
			else if ($searchType == 10) $search = '용역+사전규격';
			else if ($searchType == 11) $search = '물품+용역+사전규격';
			else if ($searchType == 12) $search = '공사+용역';
			else if ($searchType == 13) $search = '물품+공사+용역';
			else if ($searchType == 14) $search = '공사+용역+사전규격';
			else if ($searchType == 15) $search = '물품+공사+용역+사전규격';
			
			$sendType = $row["sendType"];
			if ($sendType == 1) $send = '이메일';
			else if ($sendType == 2) $send = '카톡';
			else if ($sendType == 3) $send = '이메일+카톡';
			else if ($sendType == 4) $send = '문자';
			else if ($sendType == 5) $send = '이메일+문자';
			else if ($sendType == 6) $send = '카톡+문자';
			else if ($sendType == 7) $send = '이메일+카톡+문자';
			if ($i % 2 == 0) {
				$tr = '<tr onclick="javascript:clickTrEvent(this)">';
				$tr .= '<td style="text-align: center; ">'.$k.'</td>';
				$tr .= '<td ">'.$row['id'].'</td>';
				
				$tr .= '<td>'.$row['email'].'</td>';
				$tr .= '<td>'.$row['kwd'].'</td>';
				$tr .= '<td>'.$row['dminsttnm'].'</td>';
				$tr .= '<td>'.$search.'</td>';
				$tr .= '<td>'.$send.'</td>';
				$tr .= '<td style="text-align:center;">'.$row[till].'</td>';
				$tr .='<td hidden="hidden">'.$row['katalk'].'</td>';
				$tr .='<td hidden="hidden">'.$row['cellphone'].'</td>';
				$tr .='<td hidden="hidden">'.$row['idx'].'</td>';
				$tr .= '</tr>';
			} else {
				$tr = '<tr onclick="javascript:clickTrEvent(this)">';
				$tr .= '<td class="even" style="text-align: center;">'.$k.'</td>';
				$tr .= '<td class="even">'.$row['id'].'</td>';
				$tr .= '<td class="even">'.$row['email'].'</td>';
				$tr .= '<td class="even" >'.$row['kwd'].'</td>';
				$tr .= '<td class="even" >'.$row['dminsttnm'].'</td>';
				$tr .= '<td class="even" >'.$search.'</td>';
				$tr .= '<td class="even" >'.$send.'</td>';
				$tr .= '<td class="even" style="text-align:center;">'.$row[till].'</td>';
				$tr .='<td hidden="hidden">'.$row['katalk'].'</td>';
				$tr .='<td hidden="hidden">'.$row['cellphone'].'</td>';
				$tr .='<td hidden="hidden">'.$row['idx'].'</td>';
				$tr .= '</tr>';
			}
			$cont .= $tr;
			$i += 1;
		}
		$cont .= '</table>';
		
		return $cont;
	}
	
	function bindAll($stmt) {
		$meta = $stmt->result_metadata();
		$fields = array();
		$fieldRefs = array();
		while ($field = $meta->fetch_field())
		{
			$fields[$field->name] = "";
			$fieldRefs[] = &$fields[$field->name];
		}
		
		call_user_func_array(array($stmt, 'bind_result'), $fieldRefs);
		$stmt->store_result();
		//var_dump($fields);
		return $fields;
	}
	
	function fetchRowAssoc($stmt, &$fields) {
		if ($stmt->fetch()) {
			return $fields;
		}
		return false;
	}
	
	function compressJson($response, $colArray,$pss) {
		$json1 = json_decode($response, true);
		$rowcnt = $json1['response']['body']['totalCount'];
		$colcnt = count($colArray);
		//echo 'colcnt='.$colcnt;
		$json_string = '{"response": { "header": { "resultCode": "'.$json1['response']['header']['resultCode']. '", "resultMsg":"'. $json1['response']['header']['resultMsg'].'"}, "body": { "items": [';
		for ($i = 0; $i<$rowcnt;$i++) {
			/*if ($json1['response']['body']['items'][$i]['bidNtceNo'] == '') {
			 $rowcnt -=1;
			 continue;
			 } */
			$json_string .= '{ ';
			for ($k=0;$k<$colcnt;$k++) {
				$value = str_replace("\"","'",$json1['response']['body']['items'][$i][$colArray[$k]]);
				$json_string .= '"' . $colArray[$k] . '": "' .$value. '", ';
				
			}
			$json_string .= '"pss": "' .$pss.'" '; // 물품,공사,용역
			if ($i == $json1['response']['body']['totalCount']-1) $json_string .= '}';
			else $json_string .= '},';
		}
		$json_string .= '], "numOfRows": '.$json1['response']['body']['numOfRows']. ', "pageNo": '.$json1['response']['body']['pageNo']. ', "totalCount": '.$rowcnt . '}} }'; // $json1['response']['body']['totalCount']. '}} }';
		return $json_string;
	}
	
	// 발주계획 조회 칼럼-----------------------
	function compressJsonOrder($response, $colArray1,$colArray2,$kwd,$dminsttNm) {
		/*
		 $colArray1 = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt','bidNtceDtlUrl','pss');
		 $colArray2 = array ( 'orderYear', 'orderMnth',  'bizNm', 'sumOrderAmt', 'nticeDt', 'orderInsttNm','', '', 'bsnsDivNm');
		 */
		$json1 = $response; //json_decode($response, true);
		//var_dump($json1);
		$rowcnt = count($json1); //['items']); //$json1['response']['body']['totalCount'];
		$colcnt = count($colArray1);
		/*
		 foreach ($json1 as $key => $row) {
		 $bidNtceDt[$key]  = $row['bidNtceDt'];
		 }
		 if (count($json1) > 1) array_multisort($bidNtceDt, SORT_DESC, $json1); // 마김일시
		 */
		//echo 'colcnt='.$colcnt;
		$kwd1 = explode(' ',trim($kwd));
		for ($k=0;$k<sizeof($kwd1);$k++) {
			$kwd1[$k] = trim($kwd1[$k]);
		}
		$json_string = '{"response": { "header": { "resultCode": "00", "resultMsg":"정상"}, "body": { "items": [';
		$pcnt=0;
		for ($i = 0; $i<$rowcnt;$i++) {
			/*if ($json1['response']['body']['items'][$i]['bidNtceNo'] == '') {
			 $rowcnt -=1;
			 continue;
			 } */
			if ($kwd != '' ) {
				
				$value = str_replace("\"","'",$json1[$i][$colArray2[2]]); // bizNm
				$tf = 1;
				for ($k=0;$k<sizeof($kwd1);$k++) {
					if (trim($kwd1[$k]) == '') continue;
					$pos = strpos($value, trim($kwd1[$k]));
					if ($pos === false) $tf = $tf * 0;
					else $tf = $tf * 1;
					//echo ($value.'/'.$pos.'/'.$tf.'/'.$kwd1[$k]."<br>");
					
				}
				//$tf = strpos($value, $kwd);
				//echo '<br>bizNm '.$colArray2[2].' '.$value.' '.$kwd.' '.$pos.' tf='.$tf.' tf1 ='.$tf1;
				/*if ($tf == 0) continue;  */
				/*
				 $value = str_replace("\"","'",$json1[$i][$colArray2[5]]); // orderInsttNm
				 $tf1 = 0;
				 for ($k=0;$k<sizeof($kwd1);$k++) {
				 if (trim($kwd1[$k]) == '') continue;
				 $pos = strpos($value, trim($kwd1[$k]));
				 if ($pos === false) $tf1 = $tf1 + 0;
				 else $tf1 = $tf1 + 1;
				 //echo ($value.'/'.$pos.'/'.$tf.'/'.$kwd1[$k]."<br>");
				 
				 }
				 //$tf = strpos($value, $kwd);
				 //echo '<br>orderInsttNm '.$colArray2[5].' '.$value.' '.$kwd.' '.$pos.' tf='.$tf.' tf1 ='.$tf1;
				 */
				if ($tf <1 ) continue;
				
			} else {
				$value = str_replace("\"","'",$json1[$i][$colArray2[$i]]); // bizNm
			}
			//}
			//echo '<br>수요기관 '.$json1['response']['body']['items'][$i]['orderInsttNm'].'/'.$dminsttNm;
			if ($dminsttNm != '' && $json1[$i]['orderInsttNm'] != $dminsttNm) continue;
			// --------------------------------------------------------
			$json_string .= '{ ';
			$pcnt++;
			for ($k=0;$k<$colcnt;$k++) {
				
				//if ($kwd != '' && $colArray2[$k].indexOf) {
				if ($colArray1[$k] == 'pss') $val = '계획'.$json1[$i][$colArray2[$k]];
				else $val = $json1[$i][$colArray2[$k]];
				if ($k == $colcnt - 1) { //$colArray1[$k] == 'pss') {
					$value = str_replace("\"","'",$val); //$json1[$i][$colArray2[$k]]);
					$json_string .= '"' . $colArray1[$k] . '": "' .$value. '" ';
				} else {
					$value = str_replace("\"","'",$val); //$json1[$i][$colArray2[$k]]);
					$json_string .= '"' . $colArray1[$k] . '": "' .$value. '", ';
				}
				
				//}
				
			}
			//if ($i == rowcnt-1) $json_string .= '}';
			//else
			$json_string .= '},';
		}
		if ($pcnt>0) $json_string = substr($json_string,0,strlen($json_string)-1);
		$json_string .= '], "numOfRows": 900, "pageNo": 1, "totalCount": '.$pcnt . '}} }'; // $json1['response']['body']['totalCount']. '}} }';
		return $json_string;
	}
	
	function rs2Json($stmt, $colArray) {
		$json_string = '{"response": { "header": { "resultCode": "00", "resultMsg":"정상"}, "body": { "items": [';
		$rowCount = $stmt->num_rows;
		$colcnt = count($colArray);
		$i = 1;
		//$colArray = array ( 'compno', 'compname', 'repname', 'cnt');
		while ($row = $this->fetchRowAssoc($stmt, $colArray)) { //while ($row = $result->fetch_assoc()) {
			//echo $i;
			$json_string .= '{ ';
			//for ($k=0;$k<$colcnt;$k++) {
			//if ($k == $colcnt-1) $json_string .= '"' . $colArray[$k] . '": "' .$row[$colArray[$k]]. '" ';
			//else $json_string .= '"' . $colArray[$k] . '": "' .$row[$colArray[$k]]. '", ';
			$json_string .= '"compno": "' .$row['compno']. '", ';
			$json_string .= '"compname": "' .$row['compname']. '", ';
			$json_string .= '"repname": "' .$row['repname']. '", ';
			$json_string .= '"cnt": "' .$row['cnt']. '" ';
			//}
			if ($i > $rowCount-1) $json_string .= '}';
			else $json_string .= '},';
			$i ++;
		}
		$json_string .= '], "numOfRows": '.$rowCount. ', "pageNo": 1, "totalCount": '.$rowCount . '}} }'; // $json1['response']['body']['totalCount']. '}} }';
		return $json_string;
		
		//$i += 1;
		
	}
	function rs2Json1($stmt, $colArray,$pss) {
		$json_string = '{"response": { "header": { "resultCode": "00", "resultMsg":"정상"}, "body": { "items": [';
		$rowCount = $stmt->num_rows;
		$colcnt = count($colArray);
		$i = 1;
		//$colArray = array ( 'compno', 'compname', 'repname', 'cnt');
		while ($row = $this->fetchRowAssoc($stmt, $colArray)) { //while ($row = $result->fetch_assoc()) {
			//echo $i;
			$json_string .= '{ ';
			foreach ($row as $key => $value) {
				$value = str_replace("\"","'",$value);
				$json_string .= '"' . $key . '": "' .$value. '", ';
			}
			if ($pss != '') $json_string .= '"pss": "' .$pss. '", ';
			$json_string = substr($json_string,0,strlen($json_string)-2);
			if ($i > $rowCount-1) $json_string .= '}';
			else $json_string .= '},';
			$i ++;
		}
		$json_string .= '], "numOfRows": '.$rowCount. ', "pageNo": 1, "totalCount": '.$rowCount . '}} }'; // $json1['response']['body']['totalCount']. '}} }';
		return $json_string;
		
		//$i += 1;
		
	}
	function rs2Json11($stmt, $colArray) {
		$json_string = '[';
		$rowCount = $stmt->num_rows;
		$colcnt = count($colArray);
		$i = 1;
		while ($row = $this->fetchRowAssoc($stmt, $colArray)) { //while ($row = $result->fetch_assoc()) {
			//echo $i;
			$json_string .= '{ ';
			foreach ($row as $key => $value) {
				$value = str_replace("\"","'",$value);
				$json_string .= '"' . $key . '": "' .$value. '", ';
				
			}
			$json_string = substr($json_string,0,strlen($json_string)-2);
			if ($i > $rowCount-1) $json_string .= '}';
			else $json_string .= '},';
			$i ++;
		}
		$json_string .= '] '; // $json1['response']['body']['totalCount']. '}} }';
		return $json_string;
		
	}
	
	function rs2Json2($result) {
		$json_string = '['; //'{ "items": [';
		$rowCount = mysqli_num_rows($result); //$stmt->num_rows;
		//$colcnt = count($colArray);
		$i = 1;
		while ($row = $result->fetch_assoc()) {
			$json_string .= '{ ';
			$json_string .= '"tuchalrate": "' .$row['tuchalrate']. '", ';
			$json_string .= '"sucsfbidRate": "' .$row['sucsfbidRate']. '", '; // 1등
			if ($i % 5 == 0) $in = $i;
			else $in = '';
			$json_string .= '"seq": "' .$in. '" ';
			
			if ($i > $rowCount-1) $json_string .= '}';
			else $json_string .= '},';
			
			$i ++;
		}
		$json_string .= ']'; // }'; // $json1['response']['body']['totalCount']. '}} }';
		return $json_string;
		
		//$i += 1;
		
	}
	function rs2Json2bidRec($result) {
		$json_string = '['; //'{ "items": [';
		$rowCount = mysqli_num_rows($result); //$stmt->num_rows;
		
		$i = 1;
		while ($row = $result->fetch_assoc()) {
			$json_string .= '{ ';
			$json_string .= '"bidNtceNo": "' .$row['bidNtceNo']. '", ';
			$json_string .= '"bidNtceOrd": "' .$row['bidNtceOrd']. '", ';
			$json_string .= '"opengDt": "' .$row['opengDt']. '", ';
			$json_string .= '"bidtype": "' .$row['bidtype']. '", ';
			$json_string .= '"bidNtceNm": "' .$row['bidNtceNm']. '", ';
			$json_string .= '"dminsttNm": "' .$row['dminsttNm']. '", ';
			$json_string .= '"tuchalamt": "' .$row['tuchalamt']. '", ';
			$json_string .= '"tuchalrate": "' .$row['tuchalrate']. '", ';
			$json_string .= '"tuchaldatetime": "' .$row['tuchaldatetime']. '", ';
			$json_string .= '"rank": "' .$row['remark']. '", ';
			
			
			if ($i > $rowCount-1) $json_string .= '}';
			else $json_string .= '},';
			
			$i ++;
		}
		$json_string .= ']'; // }'; // $json1['response']['body']['totalCount']. '}} }';
		return $json_string;
		
		//$i += 1;
		
	}

	function getFromUrl($url, $method = 'POST') {
		$ch = curl_init();
		$agent = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)';
		
		switch(strtoupper($method)) {
		case 'GET':
			curl_setopt($ch, CURLOPT_URL, $url);
			break;

		case 'POST':
			$info = parse_url($url);
			$url = $info['scheme'] . '://' . $info['host'] . $info['path'];
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $info['query']);
            break;
		
		default:
			return false;
		}
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_REFERER, $url);
	    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		$res = curl_exec($ch);
	    curl_close($ch);
		
		return $res;
	}	//getFromUrl

} 	// g2bClass

?>
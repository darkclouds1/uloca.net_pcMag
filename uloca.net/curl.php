<?php 
// curl이 설치 되었는지 확인 
if (function_exists('curl_init')) { 
	// curl 리소스를 초기화 
	$ch = curl_init(); 
	// url을 설정 
	curl_setopt($ch, CURLOPT_URL, 'http://www.g2b.go.kr:8340/body.do?kwd=구미'); 
	// 헤더는 제외하고 content 만 받음 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	// 응답 값을 브라우저에 표시하지 말고 값을 리턴 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// 브라우저처럼 보이기 위해 user agent 사용 
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
	$form_data = array(
    'submit_form' => 1,
    'startDate' => '20170901',
	'endDate' => '20170930',
	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $form_data);
	$content = curl_exec($ch); 
	$info = curl_getinfo($ch);
	// 리소스 해제를 위해 세션 연결 닫음 
	echo 'info='.$info;
	echo '결과=<pre>'.$content.'</pre>';
	curl_close($ch); 
	} else { 
		// curl 라이브러리가 설치 되지 않음. 다른 방법 알아볼 것 }
	echo 'no curl';
	}     
//출처: http://tipland.tistory.com/57 [외로운 개발자]

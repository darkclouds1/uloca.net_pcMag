<?php 
$content = file_get_contents('http://www.google.com/'); 
	if ($content !== false) { 
		// content 사용 
		echo $content;
		} else { 
		// error 발생 
		echo 'Error';
		}

//출처: http://tipland.tistory.com/57 [외로운 개발자]

// 원격 파일을 사용하기 전에 성공적으로 open 되었는지 확인 
if ($fp = fopen('http://www.google.com/', 'r')) { 
	$content = ''; 
	// 전부 읽을때까지 계속 읽음 
	while ($line = fread($fp, 1024)) { 
		$content .= $line; 
		} 
		// content 사용 // ... 
		} else { 
			// 파일 open시 에러 발생
		}
//출처: http://tipland.tistory.com/57 [외로운 개발자]

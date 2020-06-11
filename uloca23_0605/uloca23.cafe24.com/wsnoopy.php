<?php
//Snoopy.class.php를 불러옵니다
//echo $_SERVER['DOCUMENT_ROOT'].'<br>';
require($_SERVER['DOCUMENT_ROOT'].'/Snoopy.class.php');
 
//스누피를 생성해줍시다
$snoopy = new Snoopy;
 
//스누피의 fetch함수로 제 웹페이지를 긁어볼까요? :)

//$snoopy->fetchtext('http://bibleoceans.dothome.co.kr/index.php');
$snoopy->fetch('http://naver.com/');
//echo "<PRE>\n";
echo $snoopy->results;
//echo "</PRE>\n";

//	echo htmlentities($snoopy->results[0]);  
//	

//결과는 $snoopy->results에 저장되어 있습니다
//preg_match 정규식을 사용해서 이제 본문인 article 요소만을 추출해보도록 하죠
//preg_match('/<div class="article">(.*?)<\/div>/is', $snoopy->results, $text);
 
//이제 결과를 보면...?
//echo $text[1];

//그리고 정규식을 이용해서 해당 엘리먼트를 뽑아옵니다 
//preg_match('/<table align=center width=900">(.*?)<\/table>/is', $snoopy->results, $result); 
//마지막으로 결과를 출력하구요 
//echo '<table id="info">'.$result[1].'</table>';

?>
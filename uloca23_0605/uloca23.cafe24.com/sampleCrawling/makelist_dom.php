
<?php
//Snoopy.class.php를 불러옵니다
//echo $_SERVER['DOCUMENT_ROOT'].'<br>';
require($_SERVER['DOCUMENT_ROOT'].'/simple_html_dom.php');
 
//스누피를 생성해줍시다
//$snoopy = new Snoopy;
 
//스누피의 fetch함수로 제 웹페이지를 긁어볼까요? :)

//$snoopy->fetchtext('http://bibleoceans.dothome.co.kr/index.php');
$listUrl = 'http://www.g2b.go.kr:8101/ep/tbid/tbidList.do?taskClCds=&bidNm=&searchDtType=1&fromBidDt=2018/05/19&toBidDt=2018/06/19&fromOpenBidDt=&toOpenBidDt=&radOrgan=1&instNm=&area=&regYn=Y&bidSearchType=1&searchType=1';
//$snoopy->fetch($listUrl);
$html = file_get_html($listUrl); 
echo 'html='.$html;
//iconv -f EUC-KR -t UTF8 $snoopy->results > $test_utf
$html = iconv("EUC-KR","UTF-8", $html);

foreach($html->find('a') as $element)                             
       echo $element->href . '<br>';
/*
//html 을 가져오고
$html = file_get_html('http://www.google.com/');            

// 모든 이미지태그를 찾아냅니다.
foreach($html->find('img') as $element) 
       echo $element->src . '<br>';                                  

// 모든 a태그를 찾아내어 href속성을 뿌려줍니다.
foreach($html->find('a') as $element)                             
       echo $element->href . '<br>';

// 컨텐츠내에 텍스트들만 가져옵니다.
echo file_get_html('http://www.google.com/')->plaintext; 

// css jquery 많이 만져보셨다면 선택자는 거의 동일합니다.
//몇번째 a태그 같은 경우도 가져올수 있구요
foreach($html->find('div.article') as $article) {
    $item['title']     = $article->find('div.title', 0)->plaintext;
    $item['intro']    = $article->find('div.intro', 0)->plaintext;
    $item['details'] = $article->find('div.details', 0)->plaintext;
    $articles[] = $item;
}

//가져오기전 해당 태그내의 텍스트도 이렇게 간단히 변경가능합니다.
$html->find('div[id=hello]', 0)->innertext = 'foo';

*/
?>
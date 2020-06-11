<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

$bidNtceNo   = $_GET['bidNtceNo'];
$bidNtceOrd     = $_GET['bidNtceOrd'];

$numOfRows = 10;
$pageNo = 1;
// $inqryDiv = 4;	//1.등록일시(공고개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
$bidNtceNo = '20200442801';
$bidNtceOrd = '00';
$pss = '유찰';

//            function getBidRslt2($numOfRows, $pageNo, $inqryDiv,$inqryBgnDt,$inqryEndDt,$pss,$bidNtceNo, $bidNtceOrd) {
$response = $g2bClass->getBidRslt2($numOfRows, $pageNo, $inqryDiv, '', '', $pss, $bidNtceNo, $bidNtceOrd);
$json = json_decode($response, true);
$item = $json['response']['body']['items'];

// var_dump($item);
echo 'count=' .count($item). '<br>';
echo 'ln25:: foreach 시작<br>';
$arr = $item[0];
var_dump ($arr);
// foreach ($item as $arr) {
    echo '결과코드='         .$arr['resultCode']. '<br>'; 
    echo '결과메세지='       .$arr['resultMsg']. '<br>'; 
    echo '한페이지결과수='   .$arr['numOfRows']. '<br>'; 
    echo '페이지번호='       .$arr['pageNo']. '<br>'; 
    echo '데이터 총 개수='   .$arr['totalCount']. '<br>'; 
    echo '개찰결과구분명='   .$arr['opengRsltDivNm']. '<br>';  // 개찰결과구분명
    echo '입찰공고번호='     .$arr['bidNtceNo']. '<br>'; 
    echo '입찰공고차수='     .$arr['bidNtceOrd']. '<br>'; 
    echo '입찰분류번호='     .$arr['bidClsfcNo']. '<br>';      // 입찰분류번호
    echo '재입찰번호='       .$arr['rbidNo']. '<br>';          // 재입찰번호
    echo '유찰사유='         .$arr['nobidRsn']. '<br>';        // 유찰사유
    echo '<br>';
// }

echo '<br>----------------- end';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?version=20190102" />
    <link rel="stylesheet" href="/jquery/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css" />
    <link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css" />

    <script src="/dhtml/codebase/dhtmlx.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <script src="/jquery/jquery.min.js"></script>
    <script src="/jquery/jquery-ui.min.js"></script>
    <script src="/include/JavaScript/tableSort.js"></script>
    <script src="/js/common.js?version=20190203"></script>
    <script src="/g2b/g2b.js"></script>
    <script src="/g2b/g2b_2019.js"></script>
    <script>


    </script>


</head>

<body>




</body>

</html>
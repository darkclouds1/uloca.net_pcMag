<!DOCTYPE html>
<html>
<head>
<title>업체정보/<?=$compno?></title>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="format-detection" content="telephone=no">  <!--//-by jsj 전화걸기로 링크되는 것 막음 -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">

<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script src="/js/common.js?version=20190203"></script>
<script src="/g2b/g2b.js"></script>

<script>
	//wait(5000);
	var doingSW = true;
	
	function stopSW(){
		doingSW = false;
		alert (doingSW);
		document.getElementById('Count').value = '';
	}
	
	function startSW(){
		doingSW = true;
		alert (doingSW);
		document.getElementById('Count').value = "1";
	}

	function form_sumit(){
		if ( doingSW ) {
			setTimeout(function(){
				location.reload(true);
			},1000); //1초
			//alert ("reload");
			wait(100);
		}
	}
	function wait(msecs){
		var start = new Date().getTime();
		var cur = start;
		while(cur - start < msecs){
			cur = new Date().getTime();
		}
		return;
	}
	if (doingSW ) {
		//wait(2000);
		form_sumit();
	} else {
		
	}
</script>

<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
session_start();
date_default_timezone_set('Asia/Seoul');

require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');	
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 

$repeatCnt = $_POST['Count'];  //post 파라미터
if ($repeatCnt == 0) {
	$repeatCnt = 1 ; 
}

$action = '';
if(isset($_POST['action'])){
  $action = $_POST['action'];
}

//폼이 입력되었을 때 처리부
if($action == 'form_submit') {
  echo '<xmp>';
  print_r($_POST); //모든 Post 파라미터 표시 
  
  $repeatCnt = $_POST['Count'];  //post 파라미터 
  print "반복횟수: ". $repeatCnt . '</br>';
  echo '</xmp>';
}

 // 재귀함수 콜 
set_time_limit(1);
header('content-type:text/html;charset=utf-8');
rfc();

function rfc( $cnt=0 ){
  $fc_name = __FUNCTION__;  //자신의 function 
  global $repeatCnt;
  
  if ( $cnt > $repeatCnt -1 ){
    return;
  } 
  
  // 홈페이지 업데이트 php 함수콜
  comInfoUpdate();
  //sleep(1);	
  echo ($cnt + 1); 
  
  $fc_name(++$cnt);
}

//기업정보 중 홈페이지 외 전화번호  DB업데이터
function comInfoUpdate(){
	$g2bClass = new g2bClass;
	$dbConn = new dbConn;
	$conn = $dbConn->conn(); //

  //openCompany 의 사업자번호에 해당하는 전화번호, 팩스번호, 홈페이지_URL 3개를 업데이트
  //20만업체 100개씩*2000번 반복필요 - test limit 10 
  
  $sql = "SELECT compno FROM openCompany WHERE phone = '' AND faxNo = '' AND hmpgAdrs = ''";
  $sql .= " ORDER BY compno LIMIT 100";
  $result = $conn->query($sql);
  echo '<table align=center cellpadding="0" cellspacing="0" width="100%" class="grid05">';

  while ($row = $result->fetch_assoc()){
    $compno = $row['compno'];

    //사업자등록번호에 해당하는 전화번호, 팩스번호, 홈페이지url API 호출 
    $inqryDiv = 3; // 사업자등록번호 기준검색
    $response1 = $g2bClass->getCompInfo(1,1,$inqryDiv,$compno); 
    sleep(1);
    //var_dump($response1);
    $json1 = json_decode($response1, true);
    $item0 = $json1['response']['body']['items'];	
 
    if (substr($item0[0]['hmpgAdrs'], 0, 7) === "http://") $hmpgAdrs= $item0[0]['hmpgAdrs'];
    else $hmpgAdrs= 'http://'.$item0[0]['hmpgAdrs'];

    // $hmpgAdrs //홈피 
    $compno = $row['compno']; 		//사업자번호
    $phone = $item0[0]['telNo']; 	//전번 
    $faxNo = $item0[0]['faxNo']; 	//팩스 
    $bizno = $item0[0]['bizno'];
    //openCompany update 
    $sql = "Update openCompany SET phone ='".$phone."', faxNo ='".$faxNo."', hmpgAdrs = '".$hmpgAdrs."', ModifyDT = now()";
    $sql .= " WHERE compno = '".$compno."'";
    $conn->query($sql);
    
    $i++;
    if ( $hmpgAdrs <> "http://" ) {
    	$j++;
    	flush();
    	usleep(100);
	    echo $i.'['.$compno.'='.$bizno.']'.$hmpgAdrs."</br>";
	    
    }
  } //while
    //echo '<hr><br>';

  //openCompany update finish count 
  $sql = "SELECT COUNT(compno) as finish FROM openCompany WHERE hmpgAdrs <> ''";
  $result = $conn->query($sql);
  if ($row = $result->fetch_assoc()) {
    $finishCnt = $row['finish'];
  }

  //openCompany update finish count 
  $sql = "SELECT COUNT(compno) as unKnown FROM openCompany WHERE hmpgAdrs = '' AND hmpgAdrs <> 'http://'";
  $result = $conn->query($sql);
  if ($row = $result->fetch_assoc()) {
    $unKnown = $row['unKnown'];
  }
  echo "미완료건수: " . $unKnown . " , 완료건수: " . $finishCnt .", 업데이트갯수:".$j."</br>";
  
} //comInfoUpdate

?>

<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
  <input type="hidden" name="action" value="form_submit" />
  <input type="text" name="Count" id="Count" value="<?=$repeatCnt?>" />
  <input type="text" name="unKnown" id="unKnown" value="<?=$unKnown?>" />
  <input type="submit" value="수집" />
<div class="btn_area" id='btn'>
  <button onclick='stopSW()'>중지</button>
  <button onclick='startSW()'>시작</button>
</div>
 </form>

</html>

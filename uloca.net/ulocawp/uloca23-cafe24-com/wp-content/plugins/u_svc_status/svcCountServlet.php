<?php
header('Content-Type: text/html; charset=UTF-8');

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
//session_start();
date_default_timezone_set('Asia/Seoul');
require('./svcCountDAO.php');

//==================================
//$user_login = $_POST["user_login"]; //Get 파라미터
$searchDate = $_GET["searchDate"]; //post 파라미터
getJSon($searchDate);

function getJSon($searchDate){
	if($searchDate == null) $searchDate = date("Y-m-d");
	$result = "";
	$result = "{\"result\":[";
	$svcDAO = new classCountDAO;
	
	$list = array();
	$list = $svcDAO->searchSQL($searchDate);
	
	//echo $list; exit;

	foreach ($list as $key=>$value) {
		$result .= "[{\"value\":\"" . $value->_cnt . "\"}],";
	}
	$result .= "]}";
	
	echo($result);
}

?>
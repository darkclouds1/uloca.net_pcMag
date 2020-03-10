<?php
header('Content-Type: text/html; charset=UTF-8');

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
//session_start();
date_default_timezone_set('Asia/Seoul');
require('./svcDAO.php');

//==================================
//$user_login = $_POST["user_login"]; //Get 파라미터
$searchDate = $_GET["searchDate"]; //post 파라미터
getJSon($searchDate);

function getJSon($searchDate){
	if($searchDate == null) $searchDate = date("Y-m-d");
	$result = "";
	$result = "{\"result\":[";
	$svcDAO = new classDAO; 
	$list = array();
	$list = $svcDAO->searchUserlogin($searchDate);
	
	foreach ($list as $key=>$value) {
		$result .= "[{\"value\":\"" . $value->_dt ."\"},";
		$result .= "{\"value\":\"" . $value->_id . "\"},";
		$result .= "{\"value\":\"" . $value->_ip . "\"},";
		$result .= "{\"value\":\"" . $value->_rmrk ."\"},";
		$result .= "{\"value\":\"" . $value->_pg . "\"}],";
	}
	$result .= "]}";
	
	echo($result);
}

?>
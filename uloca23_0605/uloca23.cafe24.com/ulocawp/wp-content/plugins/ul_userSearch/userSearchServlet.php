<?php
header('Content-Type: text/html; charset=UTF-8');

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
//session_start();
date_default_timezone_set('Asia/Seoul');
require('./userDAO.php');

//==================================
//$user_login = $_POST["user_login"]; //Get 파라미터
$name = $_GET["name"]; //post 파라미터

getJSon($name);

function getJSon($name){
	if($name == null) $name = "";
	$result = "";
	$result = "{\"result\":[";
	$userDAO = new classDAO;
	$list = array();
	$list = $userDAO->searchUserlogin($name);
	
	foreach ($list as $key=>$value) {
		$result .= "[{\"value\":\"" . $value->_idx ."\"},";
		$result .= "{\"value\":\"" . $value->_user_login ."\"},";
		$result .= "{\"value\":\"" . $value->_name ."\"},";
		$result .= "{\"value\":\"" . $value->_user_email ."\"},";
		$result .= "{\"value\":\"" . $value->_payFreeCD ."\"},";
		$result .= "{\"value\":\"" . $value->_modifyDT ."\"}],";
	}
	$result .= "]}";
	
	//$result = json_encode($result);
	echo($result);
	//return (string)$result;
}

?>
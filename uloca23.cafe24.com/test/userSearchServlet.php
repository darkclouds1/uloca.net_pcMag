<?php 
header('Content-Type: text/html; charset=UTF-8');

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
//session_start();
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/test/userDAO.php');

//==================================
//$user_login = $_POST["user_login"]; //Get 파라미터 
$user_login = $_GET["user_login"]; //post 파라미터 

getJSon($user_login);

function getJSon($user_login){
	if($user_login == null) $user_login = "";
	$result = "";
	$result = "{\"result\":[";
	$userDAO = new classDAO;
	$list = array();
	$list = $userDAO->searchUserlogin($user_login);
	
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
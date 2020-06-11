<?
		
	$dbconn = mysql_connect("localhost","jayhmj","zxcv23");
	$status = mysql_select_db("jayhmj",$dbconn);
	if(!$status) {
		echo(" 접속에 실패했습니다.");
		exit;
	}

?>
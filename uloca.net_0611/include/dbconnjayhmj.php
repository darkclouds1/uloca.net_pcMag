<?
		
	$dbconn = mysql_connect("localhost","jayhmj","zxcv23");
	$status = mysql_select_db("jayhmj",$dbconn);
	if(!$status) {
		echo(" ���ӿ� �����߽��ϴ�.");
		exit;
	}

?>
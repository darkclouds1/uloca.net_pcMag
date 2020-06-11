<?
		
	$dbconn = mysql_connect("oceans.javasarang.net","oceans","snaeco");
	$status = mysql_select_db("oceans",$dbconn);
	if(!$status) {
		echo(" 접속에 실패했습니다.");
		exit;
	}
		/* ORacle connect
	function dbConn($dbUser="NAMDOTEST",$dbPasswd="NAMDOTEST", $dbName ="(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP) (HOST = 211.39.94.112) (PORT = 1521)) (CONNECT_DATA = (SID = NAMDO)))") {
		$this->dbUser   = $dbUser;
		$this->dbPasswd = $dbPasswd;
		$this->dbName   = $dbName ;
		$this->Connect	= OCILogon($dbUser,$dbPasswd,$dbName);
	}//end dbConn
	*/
?>
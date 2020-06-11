<?php
class dbFunc {
	var $dbHost="localhost";
	var $dbUser,$dbPasswd,$dbName,$Connect;


	function dbFunc($servername="localhost",$dbName = "uloca22", $dbUser = "uloca22", $dbPasswd = "w3m69p21!@") {
		$this->dbUser   = $dbUser;
		$this->dbPasswd = $dbPasswd;
		$this->dbName   = $dbName;
		//$this->Connect  = mysql_connect($this->dbHost,$this->dbUser,$this->dbPasswd);
		//mysql_select_db($this->dbName,$this->Connect);
		//echo $dbName. $dbUser.$dbPasswd;
		$this->Connect = new mysqli($servername, $dbUser, $dbPasswd, $dbName);
		//$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($this->Connect->connect_error) {
			die("DB Connection failed: " . $conn->connect_error);
		}
		//$this->Connect = $conn; 
		return $this->Connect;
	}
	function closeDB() {
		$this->Connect->close();
	}
/*
                      Class Name dbFunc (PHP 4 >= 4.3.0, PHP 5)
 -------------------------------------------------------------------------------------------------------------
 *  Source Path		: /include/dbFunc.php (PHP 4 >= 4.3.0, PHP 5)
 *  시스템명		: 
 * 업무대중소분류	:
 * 프로그램설명		: DB 기능 라이브러리
 * 파일명			: dbFunc.php
 * Called By		: All
 * Calling			:
 *  작성자			: 황명제
 *  작성날짜		: 2008. 10. 20
 * ----------------------------------------------------------------------
 * 변경사항
 * 변경일자	    변경자		    내용
 * -------		-----			------------------------------
 * 2008.10.30	황명제			새로 만듬

--------사용법 샘플-----------------------------------------------------------
	include "../include/dbFunc.php";
	$db    = new dbConn;
	$db->setQuery("SELECT * FROM p_projectinfo where tb_nm='테이블명' ");

--------function list-----------------------------------------------------------
function dbConn($dbName = "oceans", $dbUser = "oceans", $dbPasswd = "snaeco") {
function setQuery($query) {
function setCnt($query){
function getCnt($table,$field,$where){
function setSelect($table,$field,$where) {
function getSelectAssoc( $table, $field, $where, $orders ) {
function setInsert($table,$arr,$arr2) {
function setUpdate($table,$arr,$where="") {
function setDelete($table,$where) {
function setMax($table,$field,$where)
function TableList()						//테이블 목록
function TableExists($tablename)			//테이블 존재 여부
 -------------------------------------------------------------------------------------------------------------

*/
	function setQuery($query) {
		//$result = mysql_query($query,$this->Connect);
		//echo 'setQuery';
		$result = $this->Connect->query($query);
		if (!$result) {
			echo mysql_error();
		}
	
		
		return $result;
	}


	function setCnt($query){
		$result = $this->setQuery($query);
		$info   = $result->num_rows; //mysql_num_rows($result);

		if (!$info) {
			echo mysql_error();
		}
		return $info;
	}


	/*
	 * 특정 조건에 해당하는 특정 필드값의 수를 반환한다.
	 */
	function getCnt($table,$field,$where){
		$query  = "SELECT IFNULL(COUNT($field),0) AS $field FROM $table $where";
		$result = $this->setQuery($query);
		$info   = mysql_fetch_array($result);
		$count  = $info[$field];
		mysql_free_result($result);

		return $count;
	}


	function setSelect($table,$field,$where) {
		$query  = "SELECT $field FROM $table $where";
		$result = $this->setQuery($query);
		$info    = mysql_fetch_array($result);
		return $info;
	}//end setSelect


	/*
	 * DB에 질의한 결과 레코드 SET 을 얻어 연관배열로 만든후 그 연관배열을 반환한다.
	 *
	 * 반환값 : $assoc_arr
	 *          - 선택된 레코드의 순서에 따라 DB 의 컬럼명을 배열의 key 값, 컬럼값을 배열의 value로 하는
	 *            연관 배열을 만든다.
	 *          - $assoc_arr[0][row_count] : select 된 레코드의 개수를 저장한다.
	 */
	function getSelectAssoc( $table, $field, $where, $orders ) {
		$query  = "SELECT $field FROM $table $where $orders";
		$result = $this->setQuery($query);

		if (!$result) {
			echo mysql_error();
			echo "DB에러 입니다.";
			exit;
		}

		$idx = 0 ;
		while( $func_row = mysql_fetch_array($result) ) {
			// 각 레코드의 각 항목을 구분하여 연관배열로 처리한다.
			while( list( $key, $value ) = each( $func_row ) ) {
				$assoc_arr[$idx][$key] = $value ;
			}

			$idx++ ;
		}	//end while

		mysql_free_result($result);

		// 0 번째 첨자의 row_count 라는 항목에 select 된 RECORD 의 개수를 저장한다.
		$assoc_arr[0][row_count] = $idx ;

		return $assoc_arr ;
	}	// end getSelectAssoc

// $parseG2B->makeInsert($text_utf,$tableG2BLIST,$conn);

	function setInsert($table,$arr,$arr2) {
		$query  = "INSERT INTO $table ($arr) values ($arr2)";
		$result = $conn->query($sql); //$this->setQuery($query);
		if ( $result === TRUE)
			return $result;
		else echo "DB setInsert 에러 입니다.";
	}//end setInsert


	function setUpdate($table,$arr,$where="") {
		$query  = "UPDATE $table SET $arr $where" ;
		$result = $this->setQuery($query);
		return $result;
	}//end setUpdate


	function setDelete($table,$where) {
		$query  = "DELETE FROM $table $where";
		$result = $this->setQuery($query);
		return $result;
	}//end setDelete


	function setMax($table,$field,$where)
	{
		$query  = "SELECT IFNULL(MAX($field),0) AS $field FROM $table $where";
		$result = $this->setQuery($query);
		$info   = mysql_fetch_array($result);
		$number = $info[$field];
		return $number;
	}


	function TableList()
	{
		// Get a list of tables contained within the database.
		$result = mysql_list_tables($this->dbName);
		return $result;
	}
	function TableExists($tablename)
	{
		// Get a list of tables contained within the database.
		$result = mysql_list_tables($this->dbName);
		$rcount = mysql_num_rows($result);

		// Check each in list for a match.
		for ($i=0;$i<$rcount;$i++) {
			if (mysql_tablename($result, $i)==$tablename) return true;
		} 
	return false;
	}
	function VisitLog($ip,$url,$rem) 
	{
		$sqlLog="insert into visit (ip,dt,url,rem) values('".$ip."',now(),'".$url."','".$rem."')";
		$result = $this->setQuery($sqlLog);
		return $result;
	}
	
	// 방문자 로그 =======================================================



}
?>

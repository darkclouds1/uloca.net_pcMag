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
 *  �ý��۸�		: 
 * �������߼Һз�	:
 * ���α׷�����		: DB ��� ���̺귯��
 * ���ϸ�			: dbFunc.php
 * Called By		: All
 * Calling			:
 *  �ۼ���			: Ȳ����
 *  �ۼ���¥		: 2008. 10. 20
 * ----------------------------------------------------------------------
 * �������
 * ��������	    ������		    ����
 * -------		-----			------------------------------
 * 2008.10.30	Ȳ����			���� ����

--------���� ����-----------------------------------------------------------
	include "../include/dbFunc.php";
	$db    = new dbConn;
	$db->setQuery("SELECT * FROM p_projectinfo where tb_nm='���̺��' ");

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
function TableList()						//���̺� ���
function TableExists($tablename)			//���̺� ���� ����
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
	 * Ư�� ���ǿ� �ش��ϴ� Ư�� �ʵ尪�� ���� ��ȯ�Ѵ�.
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
	 * DB�� ������ ��� ���ڵ� SET �� ��� �����迭�� ������ �� �����迭�� ��ȯ�Ѵ�.
	 *
	 * ��ȯ�� : $assoc_arr
	 *          - ���õ� ���ڵ��� ������ ���� DB �� �÷����� �迭�� key ��, �÷����� �迭�� value�� �ϴ�
	 *            ���� �迭�� �����.
	 *          - $assoc_arr[0][row_count] : select �� ���ڵ��� ������ �����Ѵ�.
	 */
	function getSelectAssoc( $table, $field, $where, $orders ) {
		$query  = "SELECT $field FROM $table $where $orders";
		$result = $this->setQuery($query);

		if (!$result) {
			echo mysql_error();
			echo "DB���� �Դϴ�.";
			exit;
		}

		$idx = 0 ;
		while( $func_row = mysql_fetch_array($result) ) {
			// �� ���ڵ��� �� �׸��� �����Ͽ� �����迭�� ó���Ѵ�.
			while( list( $key, $value ) = each( $func_row ) ) {
				$assoc_arr[$idx][$key] = $value ;
			}

			$idx++ ;
		}	//end while

		mysql_free_result($result);

		// 0 ��° ÷���� row_count ��� �׸� select �� RECORD �� ������ �����Ѵ�.
		$assoc_arr[0][row_count] = $idx ;

		return $assoc_arr ;
	}	// end getSelectAssoc

// $parseG2B->makeInsert($text_utf,$tableG2BLIST,$conn);

	function setInsert($table,$arr,$arr2) {
		$query  = "INSERT INTO $table ($arr) values ($arr2)";
		$result = $conn->query($sql); //$this->setQuery($query);
		if ( $result === TRUE)
			return $result;
		else echo "DB setInsert ���� �Դϴ�.";
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
	
	// �湮�� �α� =======================================================



}
?>

<?php
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

class  classCountDAO {
	function searchSQL($SearchDate) {
		$dbConn = new dbConn;
		$conn = $dbConn->conn();

		$SQL  = "SELECT COUNT(compno) AS cnt FROM openCompany WHERE 1";
		$SQL .= "   AND DATE(modifyDT) = DATE('".$SearchDate."')";
		$SQL .= "   AND rmark like 'E-mail%'";

		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($SQL);
        
        $stmt->bind_param('s', $SearchDate);
		//$stmt->bind_param('ss', $name, $name);
		//return $SQL; //debug SQL

		if ($stmt->execute()) {

			$fields = bindAll($stmt);
			//return (var_dump($fields));
			
			$list = array();
			while (	$row = fetchRowAssoc($stmt,$fields)) {
				// oSvc Class
				$oSvc = new svc;
				$oSvc->set_cnt($row['cnt']);
				array_push($list, $oSvc);
			}
		} else {
			$list = 'errorStmt SQL= '.$SQL.'<br>';
			echo($list.'<br>'.$SQL.", SearchDate= ".$SearchDate); exit;
		}
		$stmt->close();
		$conn->close();
		return $list;
	} //search
} //classDAO

class svc {
	public $_cnt;

	/**
	 * Get the value of _cnt
	 */ 
	public function get_cnt()
	{
		return $this->_cnt;
	}

	/**
	 * Set the value of _cnt
	 *
	 * @return  self
	 */ 
	public function set_cnt($_cnt)
	{
		$this->_cnt = $_cnt;

		return $this;
	}
}

?>
<?php
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

class  classDAO {
	function searchUserlogin($SearchDate) {
		$dbConn = new dbConn;
		$conn = $dbConn->conn();

		$SQL  = "SELECT dt , id, ip, pg, rmrk FROM `logdb` WHERE 1";
        $SQL .= "   AND id <> 'uloca22' ";
        $SQL .= "   AND rmrk <> '일일자료수집' ";	//
        // $SQL .= "   AND ip <> '222.108.91.28'";  	// 오류동 IP
        // $SQL .= "   AND ip <> '175.197.112.218'";  	// 모바일(노트9) IP
        // $SQL .= "   AND ip <> '222.237.175.195'";  	// 유노비젼 IP
        $SQL .= "   AND date(dt) = date(?)";  		// 입력날
        $SQL .= " GROUP BY `ip` "; 
        $SQL .= " limit 1000; "; 

		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($SQL);
        
        $stmt->bind_param('s', $SearchDate);
		//$stmt->bind_param('ss', $name, $name);

		if ($stmt->execute()) {

			$fields = bindAll($stmt);
			//return (var_dump($fields));
			
			$list = array();
			while (	$row = fetchRowAssoc($stmt,$fields)) {
				// oSvc Class
				$oSvc = new svc;
				$oSvc->set_dt($row['dt']);
				$oSvc->set_id($row['id']);
				$oSvc->set_ip($row['ip']);
				$oSvc->set_pg($row['pg']);
				$oSvc->set_rmrk($row['rmrk']);
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
	public $_dt;
	public $_id;
	public $_ip;
	public $_pg;
	public $_rmrk;

	/**
	 * Get the value of _dt
	 */ 
	public function get_dt()
	{
		return $this->_dt;
	}

	/**
	 * Set the value of _dt
	 *
	 * @return  self
	 */ 
	public function set_dt($_dt)
	{
		$this->_dt = $_dt;

		return $this;
	}

	/**
	 * Get the value of _id
	 */ 
	public function get_id()
	{
		return $this->_id;
	}

	/**
	 * Set the value of _id
	 *
	 * @return  self
	 */ 
	public function set_id($_id)
	{
		$this->_id = $_id;

		return $this;
	}

	/**
	 * Get the value of _ip
	 */ 
	public function get_ip()
	{
		return $this->_ip;
	}

	/**
	 * Set the value of _ip
	 *
	 * @return  self
	 */ 
	public function set_ip($_ip)
	{
		$this->_ip = $_ip;

		return $this;
	}

	/**
	 * Get the value of _pg
	 */ 
	public function get_pg()
	{
		return $this->_pg;
	}

	/**
	 * Set the value of _pg
	 *
	 * @return  self
	 */ 
	public function set_pg($_pg)
	{
		$this->_pg = $_pg;

		return $this;
	}

	/**
	 * Get the value of _rmrk
	 */ 
	public function get_rmrk()
	{
		return $this->_rmrk;
	}

	/**
	 * Set the value of _rmrk
	 *
	 * @return  self
	 */ 
	public function set_rmrk($_rmrk)
	{
		$this->_rmrk = $_rmrk;

		return $this;
	}
}

?>
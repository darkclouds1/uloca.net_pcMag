<?php
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

class  classDAO {
	function searchUserlogin($SearchDate) {
		$dbConn = new dbConn;
		$conn = $dbConn->conn();

		$SQL  = "SELECT dt, id, ip, ";
		$SQL .= "       ( CASE pgDtlCD ";
		$SQL .= "         WHEN '01' THEN '01:용역' ";
		$SQL .= "         WHEN '02' THEN '02:공사' ";
		$SQL .= "         WHEN '03' THEN '03:물품' ";
		$SQL .= "         WHEN '04' THEN '04:사용' ";
		$SQL .= "         WHEN '04' THEN '05:사공' ";
		$SQL .= "         WHEN '04' THEN '06:사물' ";
		$SQL .= "         END ) AS pgDtlCD, ";
		$SQL .= "       pg, rmrk, ";
		$SQL .= "       ( CASE keyDtlCD ";
		$SQL .= "         WHEN '01' THEN '01:공고명' ";
		$SQL .= "         WHEN '02' THEN '02:수요기관' ";
		$SQL .= "         WHEN '03' THEN '03:공고+수요기관' ";
		$SQL .= "         WHEN '04' THEN '04:사전용역' ";
		$SQL .= "         WHEN '05' THEN '05:차트보기' ";
		$SQL .= "         WHEN '06' THEN '06:공고상세' ";
		$SQL .= "         WHEN '07' THEN '07:수요기관재검색' ";
		$SQL .= "         WHEN '08' THEN '08:낙찰결과' ";
		$SQL .= "         WHEN '09' THEN '09:기업응찰기록' ";
		$SQL .= "         WHEN '10' THEN '10:업체정보' ";
		$SQL .= "         END ) AS keyDtlCD, ";
		$SQL .= "       modifydt FROM `logdb` WHERE 1";
        $SQL .= "   AND rmrk <> '일일자료수집'";	//
		// $SQL .= "   AND ip <> '175.197.112.218'";  	// 모바일(노트9) IP
		$SQL .= "   AND ip <> '220.79.57.179'";		    // 상일로 71
		$SQL .= "   AND ip <> '220.85.206.164'";		// 모바일 note9
        $SQL .= "   AND date(dt) = date(?)";  		// 입력날
		// $SQL .= " GROUP BY `ip` ";
		$SQL .= " ORDER BY dt DESC ";

		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($SQL);
        
        $stmt->bind_param('s', $SearchDate);
		//$stmt->bind_param('ss', $name, $name);
		//return $SQL; //debug SQL++
		
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
				$oSvc->set_cnt($row['pgDtlCD']);
				$oSvc->set_pg($row['pg']);
				$oSvc->set_rmrk($row['rmrk']);
				$oSvc->set_keyDtlCD($row['keyDtlCD']);
				$oSvc->set_modifydt($row['modifydt']);
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
	public $_cnt;
	public $_pg;
	public $_rmrk;
	public $_keyDtlCD;
	public $_modifydt;

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

	/**
	 * Get the value of _keyDtlCD
	 */ 
	public function get_keyDtlCD()
	{
		return $this->_keyDtlCD;
	}

	/**
	 * Set the value of _keyDtlCD
	 *
	 * @return  self
	 */ 
	public function set_keyDtlCD($_keyDtlCD)
	{
		$this->_keyDtlCD = $_keyDtlCD;

		return $this;
	}

	/**
	 * Get the value of _modifydt
	 */ 
	public function get_modifydt()
	{
		return $this->_modifydt;
	}

	/**
	 * Set the value of _modifydt
	 *
	 * @return  self
	 */ 
	public function set_modifydt($_modifydt)
	{
		$this->_modifydt = $_modifydt;

		return $this;
	}
}

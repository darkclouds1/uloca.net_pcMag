<?php
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

class  classDAO {
	function searchDAO($name) {
		
		// KED(한국기업데이터) 전문 E017
		$E017 = "https://kedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?user_id=ulocaonl&process=S&bzno=6098164815&cono=&pid_agr_yn=N&jm_no=E017";
		$result = getFromUrl($E017, "POST");

		// TEST
		return $result;

		$dbConn = new dbConn;
		$conn = $dbConn->conn();
		$SQL =  "";
		
		
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($SQL);
		$name = '%'.$name.'%';
		
		//echo($name);
		$stmt->bind_param('ss', $name, $name);
		if ($stmt->execute()) {
			$fields = bindAll($stmt);
			$list = array();
			while (	$row = fetchRowAssoc($stmt,$fields)) {
				// User Class
				$user = new User;
				$user->setIdx((string)$row['idx']);
				array_push($list, $user);
			}
		} else {
			$list = 'errorStmt sql= '.$SQL.'<br>';
		}
		$stmt->close();
		$conn->close();
		return $list;
	} //search
} //classDAO

class E017 {
	public $_idx;
	

	/**
	 * Get the value of _idx
	 */ 
	public function get_idx()
	{
		return $this->_idx;
	}

	/**
	 * Set the value of _idx
	 *
	 * @return  self
	 */ 
	public function set_idx($_idx)
	{
		$this->_idx = $_idx;

		return $this;
	}
}

?>
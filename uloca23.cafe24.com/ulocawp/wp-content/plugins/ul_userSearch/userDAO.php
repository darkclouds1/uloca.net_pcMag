<?php
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

class  classDAO {
	function searchUserlogin($name) {
		$dbConn = new dbConn;
		$conn = $dbConn->conn();
		$SQL =  "SELECT P.idx as idx, ";
		$SQL .= "  		CONCAT(( M1.meta_value),'', ( M2.meta_value)) as name";
		$SQL .= "		U.display_name "; 
		$SQL .= "		P.user_login as user_login, ";
		$SQL .= "  		P.user_email as user_email, ";
		$SQL .= "		P.PayFreeCD as PayFreeCD, ";
		$SQL .= "		U.user_status, ";
		$SQL .= "		U.user_registered, ";
		$SQL .= "		P.modifyDT as modifyDT, ";
		$SQL .= "  FROM PayUser01M as P, wp_users U, wp_usermeta M1, wp_usermeta M2";
		$SQL .= " WHERE P.user_login = U.user_login";
		$SQL .= " 	AND U.ID = M1.user_id";
		$SQL .= " 	AND U.ID = M2.user_id";
		$SQL .= " 	AND M1.meta_key = 'last_name'";
		$SQL .= " 	AND M2.meta_key = 'first_name'";
		$SQL .= " 	AND ((M1.meta_value like ?) or (M2.meta_value like ?))";
		$SQL .= " ORDER BY PayFreeCD DESC";

		/*
		 * $SQL TEST
		 */
		//echo $SQL; exit;
		
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
				$user->setUser_login($row['user_login']);
				$user->setName($row['name']);
				$user->setUser_email($row['user_email']);
				$user->setPayFreeCD((string)$row['PayFreeCD']);
				$user->setModifyDT($row['modifyDT']);
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

class User {
	public $_idx;
	public $_user_login;
	public $_user_email;
	public $_payFreeCD;
	public $_modifyDT;
	public $_name;
	
	public function getIdx() {
		return $this->_idx;
	}
	public function setIdx($idx) {
		$this->_idx = $idx;
	}
	public function getUser_login() {
		return $this->_user_login;
	}
	public function setUser_login($user_login) {
		$this->_user_login = $user_login;
	}
	public function getUser_email() {
		return $this->_user_email;
	}
	public function setUser_email($user_email) {
		$this->_user_email = $user_email;
	}
	public function getPayFreeCD() {
		return $this->_payFreeCD;
	}
	public function setPayFreeCD($payFreeCD) {
		$this->_payFreeCD = $payFreeCD;
	}
	public function getModifyDT() {
		return $this->_modifyDT;
	}
	public function setModifyDT($modifyDT) {
		$this->_modifyDT = $modifyDT;
	}
	public function getName() {
		return $this->_name;
	}
	public function setName($name) {
		$this->_name = $name;
	}
}

?>
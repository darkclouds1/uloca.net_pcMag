<?php 
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
//require($_SERVER['DOCUMENT_ROOT'].'/test/userClass.php');

class  classDAO {
	function searchUserlogin($user_login) {
		$dbConn = new dbConn;
		$conn = $dbConn->conn();
		$SQL =  "SELECT P.idx as idx, P.user_login as user_login,";
		$SQL .= "  		P.user_email as user_email, P.PayFreeCD as PayFreeCD, P.modifyDT as modifyDT, ";
		$SQL .= "  		CONCAT(( SELECT MAX(T2.Meta_value) ";
		$SQL .= "  				   FROM wp_usermeta T2";
		$SQL .= "  				  WHERE U.ID = T2.user_id";
		$SQL .= "  					AND T2.meta_key = 'last_name'),'',";
		$SQL .= "  			   ( SELECT MAX(T1.Meta_value)";
		$SQL .= "  				   FROM wp_usermeta T1";
		$SQL .= "				  WHERE U.ID = T1.user_id ";
		$SQL .= "  					AND T1.meta_key = 'first_name')) as name";
		$SQL .= "  FROM PayUser01M as P, wp_users U, wp_usermeta M";
		$SQL .= " WHERE U.ID = M.user_id";
		$SQL .= " 	AND M.meta_key = 'last_name'";
		$SQL .= " 	AND U.User_email = P.user_email";
		$SQL .= " 	AND P.user_login like ?";
		$SQL .= " ORDER BY PayFreeCD DESC";
		/*
		 * $SQL TEST
		 */
		//echo $SQL; exit;
		
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($SQL);
		$user_login = '%'.$user_login.'%';

		//echo($user_login);
		$stmt->bind_param('s', $user_login);
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
			$list = 'errorStmt sql= '.$sql.'<br>';
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
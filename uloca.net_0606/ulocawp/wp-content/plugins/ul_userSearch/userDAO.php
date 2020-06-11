<?php
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

class  classDAO {
	function searchUserlogin($name) {
		$dbConn = new dbConn;
		$conn = $dbConn->conn();
		$SQL =  "SELECT P.idx as idx, ";
		$SQL .= "  		CONCAT(( M1.meta_value),'', ( M2.meta_value)) as name, ";
		$SQL .= "		U.display_name as display_name ,"; 
		$SQL .= "		P.user_login as user_login, ";
		$SQL .= "  		P.user_email as user_email, ";
		$SQL .= "		P.PayFreeCD as PayFreeCD, ";
		$SQL .= "		U.user_status as user_status, ";
		$SQL .= "		U.user_registered as user_registered, ";
		$SQL .= "		P.modifyDT as modifyDT ";
		$SQL .= "  FROM PayUser01M as P, wp_users U, wp_usermeta M1, wp_usermeta M2";
		$SQL .= " WHERE P.user_login = U.user_login";
		$SQL .= " 	AND U.ID = M1.user_id";
		$SQL .= " 	AND U.ID = M2.user_id";
		$SQL .= " 	AND M1.meta_key = 'last_name'";
		$SQL .= " 	AND M2.meta_key = 'first_name'";
		$SQL .= " 	AND ((M1.meta_value like ?) or (M2.meta_value like ?))";
		$SQL .= " ORDER BY modifyDT DESC ";

		/*
		 * $SQL TEST
		 return $SQL;
		 */
		
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
				$user->set_idx((string)$row['idx']);
				$user->set_name($row['name']);
				$user->set_display_name($row['display_name']);
				$user->set_user_login($row['user_login']);
				$user->set_user_email($row['user_email']);
				$user->set_PayFreeCD((string)$row['PayFreeCD']);
				$user->set_user_status($row['user_status']);
				$user->set_user_registered($row['user_registered']);
				$user->set_modifyDT($row['modifyDT']);
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
	public $_name;
	public $_display_name;
	public $_user_login;
	public $_user_email;
	public $_payFreeCD;
	public $_user_status;
	public $_user_registered;
	public $_modifyDT;
	

	public function get_idx()
	{
		return $this->_idx;
	}
	public function set_idx($_idx)
	{
		$this->_idx = $_idx;

		return $this;
	}

	public function get_name()
	{
		return $this->_name;
	}
	public function set_name($_name)
	{
		$this->_name = $_name;

		return $this;
	}

	public function get_display_name()
	{
		return $this->_display_name;
	}
	public function set_display_name($_display_name)
	{
		$this->_display_name = $_display_name;

		return $this;
	}

	public function get_user_login()
	{
		return $this->_user_login;
	}
	public function set_user_login($_user_login)
	{
		$this->_user_login = $_user_login;

		return $this;
	}

	public function get_user_email()
	{
		return $this->_user_email;
	}
	public function set_user_email($_user_email)
	{
		$this->_user_email = $_user_email;

		return $this;
	}

	public function get_payFreeCD()
	{
		return $this->_payFreeCD;
	}
	public function set_payFreeCD($_payFreeCD)
	{
		$this->_payFreeCD = $_payFreeCD;

		return $this;
	}

	public function get_user_status() 
	{
		return $this->_user_status;
	}
	public function set_user_status($_user_status)
	{
		$this->_user_status = $_user_status;

		return $this;
	}

	public function get_user_registered()
	{
		return $this->_user_registered;
	}
	public function set_user_registered($_user_registered)
	{
		$this->_user_registered = $_user_registered;

		return $this;
	}

	public function get_modifyDT()
	{
		return $this->_modifyDT;
	}
	public function set_modifyDT($_modifyDT)
	{
		$this->_modifyDT = $_modifyDT;

		return $this;
	}
}

?>
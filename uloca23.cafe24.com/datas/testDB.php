<?

include "../include/dbFunc.php";
include "../ulocawp/wp-includes/user.php";
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); // '/g2b/classPHP/g2bClass.php'); 
$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck();
	echo $mobile;
$timestamp = strtotime("Now");
$endDate = date('YmdHi',$timestamp); //'20180725';
$timestamp = strtotime("-1 days");

$startDate = date('YmdHi',$timestamp); //'20180720';
echo 'startDate='.$startDate.' endDate='.$endDate;
/* function get_current_user_id() {
    if ( ! function_exists( 'wp_get_current_user' ) )
        return 0;
    $user = wp_get_current_user();
    return ( isset( $user->ID ) ? (int) $user->ID : 0 );
}	*/
	$db    = new dbFunc("localhost","uloca22", "uloca22", "w3m69p21!@");
	//echo 'db';
	$result = $db->setQuery("SELECT * FROM wp_users order by user_login ");
	//echo 'result';
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        //echo "최소 bid: " . $row["bid"].  "<br>";
		$userid= $row["user_login"];
		$email = $row["user_email"];
		echo 'userid='.$userid;
		echo ' email='.$email.'<br>';
    }
}
$cnt = $db->setCnt("SELECT * FROM wp_users");
echo 'cnt='.$cnt;
$db->closeDB();

$id = get_current_user_id();
echo 'id='.$id.'is='.isset( $_POST['email'] );
/*$user = wp_get_current_user();
echo 'user='.$user;
echo $user->roles[0];

echo $user->user_email;

$user = new WP_User(get_current_user_id());
echo 'WP_User='.$user->roles[0];

// wp-admin/includes/user.php
if ( isset( $_POST['email'] )) {
		$user->user_email = sanitize_text_field( wp_unslash( $_POST['email'] ) );
		echo 'true';
} else echo 'false';
echo $user_data->user_login;
echo $user->user_email; */
?>
hi!!
<?php 
/*
global $current_user;
      $current_user = get_currentuserinfo();

      echo 'Username: ' . $current_user->user_login . "\n";
      echo 'User email: ' . $current_user->user_email . "\n";
      echo 'User level: ' . $current_user->user_level . "\n";
      echo 'User first name: ' . $current_user->user_firstname . "\n";
      echo 'User last name: ' . $current_user->user_lastname . "\n";
      echo 'User display name: ' . $current_user->display_name . "\n";
      echo 'User ID: ' . $current_user->ID . "\n";
*/
?>
<script src="http://uloca.net/datas/datas.js"></script>
<script>

	parm = '?kwd=부산&startDate=20180726&endDate=20180727';
		parm +='&dminsttNm=';
		parm +='&chkBid=bid';
		parm +='&chkHrc=hrc';
		server="http://uloca.net/datas/ajaxserver.php"+parm;

getAjax(server,cli);

function cli(data) {
	document.getElementById('wasrec').innerHTML = data;
}
</script>
<div id='wasrec'></div>
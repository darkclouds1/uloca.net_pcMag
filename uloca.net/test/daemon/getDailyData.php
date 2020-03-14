<?
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();
$sql = "insert into daemonhistory (ip) VALUES('".$_SERVER['REMOTE_ADDR']."')";
$conn->query($sql);
$conn->close();
//echo $sql;
//$myself='http://uloca23.cafe24.com/daemon/getDailyData.php';
//$sec = "10";
//header('Refresh: 600;'); // Location: $myself');  
//header("Refresh: 5; url=index.php");
//$secondsWait = 5;
//<meta http-equiv="refresh" content="'".$secondsWait."'; ">';
?>
<script>
//window.close();
</script>
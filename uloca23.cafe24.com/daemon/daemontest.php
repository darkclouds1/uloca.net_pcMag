<?
/*require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();
$sql = "insert into daemonhistory (ip) VALUES('".$_SERVER['REMOTE_ADDR']."')";
$conn->query($sql);
$conn->close(); */
//echo $sql;
//$myself='http://uloca23.cafe24.com/daemon/daemontest.php';
//$sec = "10";
//header('Refresh: 600;'); // Location: $myself');  
//header("Refresh: 5; url=index.php");
//$secondsWait = 5;
//<meta http-equiv="refresh" content="'".$secondsWait."'; ">';
?>
<html>
<head>
<title> uloca data gathering </title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="icon" href="img/clock.jpg" sizes="128x128">
<link rel="apple-touch-icon-precomposed" href="img/clock.jpg">
</head>
<body>
<div id="datetime" style='font-size:64px;'></div>
<script>
function getToday(sep) {
	var sep2 = ':';
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) dd = '0'+dd
	if(mm<10) mm = '0'+mm

	var msg = yyyy + sep + mm + sep + dd  ;
	hh = today.getHours();
    mn = today.getMinutes();
    ss = today.getSeconds();
	if(hh<10) hh = '0'+ hh;
	if(mn<10) mn = '0'+ mn;
	if(ss<10) ss = '0'+ ss;
	msg += " "+hh+sep2+mn+sep2+ss;
	return msg;
}

//dt = getToday('-');
//document.getElementById("datetime").innerHTML=dt;

    window.onload = function() {
        doagain();
     };
var url = 'getDailyData.php';
var mywin;
function doagain() {
	dt = getToday('-');
	document.getElementById("datetime").innerHTML+=dt+'<br>';
	mywin = window.open(url,'doloop');
	setTimeout(function () {
            //location.reload()
			doagain();
        }, 600000); // 10분
}
</script>
</body>
</html>
<?php
/*
 Plugin Name: 유로카 사용 현황 : u_svc_status
 Plugin URI: http://uloca.net/ulocawp/?page_id=1130
 Description: 유로카 사용자 조회
 Version: 2.0
 Author: Monolith
 Author URI: /ulocawp/?page_id=1130
 */
header('Content-Type: text/html; charset=UTF-8');

function u_svc_status_ShortCode()
{

	@extract($_GET);
	@extract($_POST);
	@extract($_SERVER);
	//session_start();

	date_default_timezone_set('Asia/Seoul');
	require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php');
	require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
	//$g2bClass = new g2bClass;

	$searchDate = $_POST["searchDate"];
	if (isset($_POST["searchDate"])) {
		$searchDate = $_POST["searchDate"];
	} else {
		$searchDate = date("Y-m-d");
	}

	//$curUrl = wp_get_shortlink(get_the_ID());
	$http_host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$curUrl = 'https://' . $http_host . $request_uri;

	$dbConn = new dbConn;
	$conn = $dbConn->conn();

	$sql  = "SELECT COUNT(compno) AS CNT FROM openCompany WHERE 1";
	$sql .= "   AND DATE(modifyDT) = DATE('" . $searchDate . "')";
	$sql .= "   AND rmark like 'E-mail%'";
	//echo $sql;

	$result = $conn->query($sql);
	if ($result) {
		$row = $result->fetch_assoc();
		$changeCompCnt = $row["CNT"];
	} else {
		echo $sql;
	}


?>

	<!doctype html>
	<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1, maximum-scale=1, user-scalable=no">

		<title>유로카닷넷-사용현황</title>

		<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
		<link rel="stylesheet" href="/jquery/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css" />
		<link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css" />
		<script src="/jquery/jquery.min.js"></script>
		<script src="/jquery/jquery-ui.min.js"></script>
		<script src="/g2b/g2b.js"></script>
		<script src="/dhtml/codebase/dhtmlx.js"></script>

		<script type="text/javascript">
			var sendEmailCount = 0;
			//var path = window.location.pathname;
			var request = new XMLHttpRequest();

			function searchFunction() {
				request.open("Post", "./wp-content/plugins/u_svc_status/svcSearchServlet.php?searchDate=" + encodeURIComponent(document.getElementById("searchDate").value), true);
				request.onreadystatechange = searchProcess;
				request.send(null);
			}

			function searchProcess() {
				var table = document.getElementById("ajaxTable");
				table.innerHTML = "";
				if (request.readyState == 4 && request.status == 200) {
					//debug
					//alert (request.responseText);

					var obj = eval("(" + request.responseText + ")");
					var result = obj.result;
					var totCnt = 0;
					for (var i = 0; i < result.length; i++) {
						var row = table.insertRow(table.rows.length); //마지막에 insert
						var cell = row.insertCell(0);
						cell.innerHTML = i + 1;
						for (var j = 1; j < (result[i].length + 1); j++) {
							var cell = row.insertCell(j);
							if (j == 4) {
								cell.innerHTML = result[i][j - 1].value;
								totCnt = totCnt + Number(result[i][j - 1].value);
							} else {
								cell.innerHTML = result[i][j - 1].value;
							}
						}
					}
					//document.getElementById("totalCnt").innerHTML = 'totalCnt=' + i;
					$("#totalCnt").text('totalRow(IP)=' + i + ', totalCnt=' + totCnt);
					// alert ('searchcount() call');
					searchCount();

				}
			} //searchProcess

			//이메일 보낸 갯수
			function searchCount() {

				//------------------------------------------------------
				// 입찰정보 (from~to) 수집  , bidSeqOn=true 개찰이력 입력
				//------------------------------------------------------
				url = "./wp-content/plugins/u_svc_status/svcCountServlet.php?searchDate=" + encodeURIComponent(document.getElementById('searchDate').value);
				//document.getElementById('btn').style.display = 'none';
				getSvc_Ajax(url, emailCntSearch);
				console.log(url);
			}

			function emailCntSearch(data) {
				var obj = eval("(" + data + ")");
				var result = obj.result;
				sendEmailCount = result[0][0].value;
				$("#emailCnt").text('이메일 보낸 갯수= ' + sendEmailCount);
			}

			window.onload = function() {
				searchFunction();
				//searchCount();
			}

			//document.getElementById("toDay").value = new Date().toISOString().substring(0, 10);
			function sm() {
				document.frm.submit();
			}

			/* -------------------------------------------------------------------------
			new Version of get ajax : 20180728 HMJ
			-------------------------------------------------------------------------- */
			function getSvc_Ajax(server, client) {
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						///document.getElementById("demo").innerHTML = this.responseText;
						client(this.responseText);
					} else if (this.status == 504) {
						move_stop();
						alert('Time-out Error...');
						return;
					} else {
						//alert ("Error" + xhttp.status);
					}
				};
				xhttp.open("GET", server, true);
				xhttp.send();
			}
		</script>
	</head>

	<body>
		<div class="container-fluid">
			<div id='totalCnt' class="col-xs-8 col-sm-6 col-md-4 col-lg-3">	totalCnt= 
			</div>
			<div id='emailCnt'>	이메일 보낸 갯수 = 0 
			</div>

			<div class="form-group row pull-right">
				<input type="date" name="searchDate" id="searchDate" onchange="searchFunction();" value="<?php echo $searchDate; ?>">
				<button class="btn-primary" onclick="searchFunction();" type="button">검색</button>
			</div>

			<table class="type10" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
				<thead>
					<tr style="border: 1px solid #dddddd;">
						<th scope="cols" width="3%;"> no.</th>
						<th scope="cols" width="7%;">date</th>
						<th scope="cols" width="7%;"> Id</th>
						<th scope="cols" width="10%;">IP</th>
						<th scope="cols" width="5%;">공고</th>
						<th scope="cols" width="25%;">rmrk</th>
						<th scope="cols" width="25%;">pg</th>
						<th scope="cols" width="7%;">keyDtlCD</th>
						<th scope="cols" width="7%;">modifydt</th>
					</tr>
				</thead>
				<tbody id="ajaxTable" style="word-break:break-all;">
					<tr>
						<!-- svc list -->
					</tr>
				</tbody>
			</table>
		</div>
	</body>

	</html>
<?
}
add_shortcode('u_svc_status', 'u_svc_status_ShortCode');

?>
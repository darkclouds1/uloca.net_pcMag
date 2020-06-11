<?php
/*
 Plugin Name: 유로카 사용자현황 : ul_userSearch
 Plugin URI: http://uloca.net/ulocawp/?page_id=1534
 Description: 유로카 사용자 조회
 Version: 1.0
 Author: Monolith
 Author URI: /ulocawp/?page_id=1534
 */
header('Content-Type: text/html; charset=UTF-8');

function ul_userSearch_ShortCode() {
	
	?>
 
<!doctype html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<meta name="viewport" content="width=device-width, initial-scal=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<link rel="stylesheet" href="./css/bootstrap.css">

	<title>PHP JSon search</title>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="./js/bootstrap.js"></script>
	<script type="text/javascript">
		//var path = window.location.pathname;
		var request = new XMLHttpRequest();
		function searchFunction() {
			request.open("Post", "./wp-content/plugins/ul_userSearch/userSearchServlet.php?name=" + encodeURIComponent(document.getElementById("name").value),true);
			request.onreadystatechange = searchProcess;
			request.send(null);
		}
		function searchProcess() {
			var table = document.getElementById("ajaxTable");
			table.innerHTML = "";
			if(request.readyState == 4 && request.status == 200) {
				var obj = eval("("+request.responseText+")");
				var result = obj.result;
				for(var i = 0; i < result.length; i++) { 
					var row = table.insertRow(table.rows.length ); //마지막에 insert
					//rownum 
					var cell = row.insertCell(0);	
					cell.innerHTML = i+1;
					//==rownum 			
					for(var j = 1; j < (result[i].length + 1); j++) {
						var cell = row.insertCell(j);
						cell.innerHTML = result[i][j-1].value;
					}
				}
				//document.getElementById("totalCnt").innerHTML = 'totalCnt=' + i;
				$("#totalCnt").text('totalCnt='+i);
			}
		} //searchProcess
		window.onload = function() {
			searchFunction();
		}
	</script>
</head>
<body>
	<br>
	<div class="container">
		<div id='totalCnt' class="col-xs-2">
			totalCnt=
		</div>
		<div class="form-group row pull-right">
			<div class="col-xs-8">
				<input class="form-control" id="name" onkeyup="searchFunction();" type="text" size="20">
			</div>
			<div class="col-xs-2">
				<button class="btn btn-primary" onclick="searchFunction();" type="button">검색</button>
			</div>
		</div>
		<table class="table" style="text-align: center; border: 1px solid #dddddd">
			<thead>
				<tr>
					<th style="background-color: #fafafa; text-align: center;">no.</th>
					<th style="background-color: #fafafa; text-align: center;">idx</th>
					<th style="background-color: #fafafa; text-align: center;">로그인아이디</th>
					<th style="background-color: #fafafa; text-align: center;">이름</th>
					<th style="background-color: #fafafa; text-align: center;">이메일</th>					
					<th style="background-color: #fafafa; text-align: center;">결재상태</th>					
					<th style="background-color: #fafafa; text-align: center;">업데이트일시</th>		
				</tr>
			</thead>
			<tbody id="ajaxTable">
			 	<tr>
			 		<!--  
			 		<td>idx</td>
			 		<td>로그인아이디</td>
			 		<td>이메일</td>
			 		<td>결재상태</td>
			 		<td>일시</td>
			 		-->
			 	</tr>
			</tbody>
		</table>
	</div>
</body>
</html>
<?
}
add_shortcode('ul_userSearch','ul_userSearch_ShortCode');

?>
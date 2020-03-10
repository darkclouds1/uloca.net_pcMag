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
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1, maximum-scale=1, user-scalable=no">

	<title>회원현황</title>

	<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
	<link rel="stylesheet" href="/jquery/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css"/>
	<link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css"/>
	<script src="/jquery/jquery.min.js"></script>
	<script src="/jquery/jquery-ui.min.js"></script>
	<script src="/g2b/g2b.js"></script>
	<script src="/dhtml/codebase/dhtmlx.js"></script>

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
				//alert (request.responseText);
				
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
	<div class="container-fluid">
		<div id='totalCnt' class="col-xs-8 col-sm-6 col-md-4 col-lg-3">
				totalCnt=
		</div>
		<div class="form-group row pull-right">
				<input id="name" onchange="searchFunction();" type="text" value="">
				<button class="btn-primary" onclick="searchFunction();" type="button">검색</button>
		</div>
		<table class="type10" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
			<thead>
				<tr style="border: 1px solid #dddddd;">
					<th  scope="cols" width="3%;">no.</th>
					<th  scope="cols" width="3%;">idx</th>
					<th  scope="cols" width="12%;">이름</th>
					<th  scope="cols" width="12%;">표시명</th>
					<th  scope="cols" width="18%;">로그인ID</th>
					<th  scope="cols" width="18%;">email</th>
					<th  scope="cols" width="5%;" align="center">결재상태</th>
					<th  scope="cols" width="5%;" align="center">사용자상태</th>
					<th  scope="cols" width="12%;">등록일시</th>
					<th  scope="cols" width="12%;">업데이트일시</th>
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